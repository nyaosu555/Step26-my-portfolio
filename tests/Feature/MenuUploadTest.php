<?php

namespace Tests\Feature;

use App\Enums\MenuType;
use App\Models\Menu;
use App\Models\User;
use Database\Seeders\TypeSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MenuUploadTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    /**
     * 各テストが実行される前の共通準備
     */
    protected function setUp(): void
    {
        parent::setUp();
        // メニュー登録に必須なマスターデータ（主菜・副菜など）を流し込む
        $this->seed(TypeSeeder::class);

        // 画像保存先のストレージをテスト用の「偽物（Fake）」に差し替える
        Storage::fake('public');

        // テスト用のユーザーをあらかじめ1人作成しておく
        $this->user = User::factory()->create();
    }

    /**
     * テスト環境の疎通確認
     */
    public function test_upload_env_is_working(): void
    {
        // ログインした状態で、メニュー登録画面にアクセス
        $response = $this->actingAs($this->user)->get('menus');

        // 画面が無事に開けることを（200 OK）を確認
        $response->assertStatus(200);
    }

    /**
     * 【正常系】ケース1：画像を新規選択して通常通り登録できるか
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function test_user_can_upload_image_and_register_menu(): void
    {
        // テスト用のダミー画像ファイル（100 x 100）をメモリ上に偽造する
        // 変更： 1x1ピクセルの本物の最軽量GIF画像のバイナリデータを直接フェイクファイルにする
        // これにより、どんな環境のfileinfoでも確実に「image/gif」として認識されます
        $gifContent = base64_decode('R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7');
        $dummyImage = \Illuminate\Http\UploadedFile::fake()->createWithContent(
            'normal_dish.gif',
            $gifContent
        );

        // フォームにデータを入力して「メニューを追加」を押した時の同じPOSTリクエストを送信
       $response = $this->actingAs($this->user)->post('/menus', [
            'menu_name'            => '通常登録の唐揚げ',
            'type_id'              => MenuType::Main->value,
            'image_path'           => $dummyImage, // 確実に画像認識されるファイル
            'current_image_status' => 'new_image',
            'buffered_image_data'  => null,
        ]);

        $response->dumpSession();

        // 通常の登録完了後、一覧画面などにリダイレクトされることを確認
        $response->assertRedirect();

        // データベースに料理名が正しく保存されているか確認
        $this->assertDatabaseHas('menus', [
            'name'  =>  '通常登録の唐揚げ',
        ]);

        // 【画像テストの核心】DBから今登録されたメニーを取得し
        // Storage（ストレージ）の中に本当にその画像gファイルが物理的に保存されているかを検証
        $menu = Menu::where('name', '通常登録の唐揚げ')->first();

        $this->assertNotNull($menu->image_path);
        Storage::disk('public')->assertExists($menu->image_path);
    }

     /**
     * 【正常系】更新ケース1：既存画像がある状態から新しい別の画像ファイルに上書き更新できるか
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function test_user_can_update_image_and_old_image_is_deleted(): void
    {
        // 既存の流儀に合わせて、このテスト用のユーザーをその場で作成
        $user = User::factory()->create();

        // あらかじめ「古い画像」を持っているメニューをデータベースに用意
        $oldImagePath = 'menu_images/old_dish.jpg';

        $menu = Menu::factory()->create([
            'user_id'    => $user->id, // 💡 全て $user->id に統一
            'name'       => '古いメニュー',
            'image_path' => $oldImagePath,
        ]);

        // 仮想ストレージ（public）の中に、古い画像ファイルを実際に配置
        Storage::disk('public')->put($oldImagePath, 'old image content');
        Storage::disk('public')->assertExists($oldImagePath);

        // 新しい別の画像ファイル（GIFバイナリ）を用意
        $gifContent = base64_decode('R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7');
        $newImage = \Illuminate\Http\UploadedFile::fake()->createWithContent(
            'new_dish.gif',
            $gifContent
        );

        // postメソッドを使い、データ内に _method => PUT を仕込んで送信
        $response = $this->actingAs($user)->post("/menus/{$menu->id}", [
            '_method'              => 'PATCH', // 💡 PUT から PATCH に修正！
            'menu_name'            => '新しいメニュー名に変更',
            'type_id'              => MenuType::Main->value,
            'image_path'           => $newImage,
            'current_image_status' => 'new_image',
            'buffered_image_data'  => null,
        ]);
        // 更新完了後のリダイレクト確認
        $response->assertRedirect();

        // データベースの検証
        $menu->refresh();
        $this->assertEquals('新しいメニュー名に変更', $menu->name);
        $this->assertNotEquals($oldImagePath, $menu->image_path);

        // ストレージの検証
        // ① 新しい画像ファイルが保存されているか
        Storage::disk('public')->assertExists($menu->image_path);

        // 古い画像ファイルが綺麗に消去されているか
        Storage::disk('public')->assertMissing($oldImagePath);

    }

    /**
     * 【正常系】更新ケース2：既存画像がある状態から、画像は変更せずにテキストだけを更新できるか
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function test_user_can_update_text_only_without_changing_image(): void
    {
        // 既存の流儀に合わせてユーザーを作成
        $user = User::factory()->create();

        // あらかじめ「元の画像（original_dish.jpg）」を持っているメニューをデータベースに用意
        $originalImagePath = 'menu_images/original_dish.jpg';

        $menu = Menu::factory()->create([
            'user_id'    => $user->id,
            'name'       => '古いメニュー名',
            'image_path' => $originalImagePath,
        ]);

        //  仮想ストレージ（public）の中に、その画像を配置しておく
        Storage::disk('public')->put($originalImagePath, 'original image content');
        Storage::disk('public')->assertExists($originalImagePath); // 存在する事を確認

        // 編集画面から「画像ファイルは選択せず」にテキストだけを変えて更新（PATCH）リクエストを送信
        // 実際のフォームに合わせて POST + _method => PATCH 構成
        $response = $this->actingAs($user)->post("/menus/{$menu->id}", [
            '_method'              => 'PATCH',
            'menu_name'            => '料理名だけ変更しました', // テキストは新しく
            'type_id'              => MenuType::Main->value,
            'image_path'           => null,                   // 💡 ファイルは選択しない（null）
            'current_image_status' => 'fallback',           // 💡 フロントから「画像維持」のステータスが来る想定
            'buffered_image_data'  => null,
        ]);

        // 更新完了後のリダイレクト確認
        $response->assertRedirect();

        // データベースの検証
        $menu->refresh();
        $this->assertEquals('料理名だけ変更しました', $menu->name); // 名前が新しくなっていること

        //【最重要】画像パスがNULLに上書きされず、元のパスがそのまま残っていること！
        $this->assertEquals($originalImagePath, $menu->image_path);

        // ストレージの検証
        // 元の画像ファイルが、誤って削除されずにストレージに残っていること！
        Storage::disk('public')->assertExists($originalImagePath);

    }

    /**
     * 画像以外のファイルをアップロードした際、バリデーションで弾かれるか
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function test_cannot_upload_non_image_file(): void
    {
        // 既存の流儀に合わせてユーザーを作成
        $user = User::factory()->create();

        // 画像ではない「ただのテキストファイル」のフェイクを用意する
        $invalidFile = \Illuminate\Http\UploadedFile::fake()->create(
            'bad_file.txt',
            100,          // 100 KB
            'text/plain'  // MIMEタイプをテキストに指定
        );

        // 不正なファイルを添付して、新規登録（POST）リクエストを送信
        $response = $this->actingAs($user)->post('/menus', [
            'menu_name'            => '不正ファイルのテスト料理',
            'type_id'              => MenuType::Main->value,
            'image_path'           => $invalidFile, // 💡 ここにテキストファイルを仕込む
            'current_image_status' => 'new_image',
            'buffered_image_data'  => null,
        ]);

        $response->assertStatus(302);

        // 【最重要】セッションの中に 'image_path' のバリデーションエラーメッセージが含まれているか確認
        $response->assertSessionHasErrors(['image_path']);

        // データベースにこの料理が登録「されていない」ことを確認
        $this->assertDatabaseMissing('menus', [
            'name' => '不正ファイルのテスト料理',
        ]);
    }

    /**
     * 【異常系】更新ケース3：他のユーザーが所有するメニューを勝手に更新しようとしたら弾かれるか（認可エラー）
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function test_user_cannot_update_other_users_menu(): void
    {
        //  「メニューの本当の持ち主」となるユーザーAを作成
        $userA = User::factory()->create();

        // ユーザーAのメニューを作成
        $menuA = Menu::factory()->create([
            'user_id' => $userA->id,
            'name'    => 'ユーザーAの秘密のメニュー',
        ]);

        // 別のユーザーBを作成
        $userB = User::factory()->create();

        // ユーザーBとしてログインし、ユーザーAのメニューを勝手に書き換えるリクエストを送信
        $response = $this->actingAs($userB)->post("/menus/{$menuA->id}", [
            '_method'              => 'PATCH',
            'menu_name'            => 'ハッカーによって書き換えられた名前',
            'type_id'              => MenuType::Main->value,
            'image_path'           => null,
            'current_image_status' => 'fallback',
            'buffered_image_data'  => null,
        ]);

         $response->assertRedirect();

        // データベースのメニュー名が「書き換えられていないこと」を確認
        $menuA->refresh();
        $this->assertEquals('ユーザーAの秘密のメニュー', $menuA->name);
    }
}

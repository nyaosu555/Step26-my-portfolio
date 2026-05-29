# 📱 今日のおかずなんにしよ
<img width="800" alt="Image" src="https://github.com/user-attachments/assets/91aa0bed-6efe-4007-a55f-4d6fbd201464" />
毎日の献立選びに迷う時間をゼロにする、スロット型の献立決定サポートアプリです。

## 🗒️ サービス概要

### 開発背景とペルソナ（対象ユーザー）
仕事や家事で日々忙しい「20代〜40代の主婦・共働き世帯」の方々をターゲットにしています。
毎日仕事終わりに「今日のご飯、何にしよう…」と頭を悩ませる時間は、小さなストレスになりがちです。そんな夕食の献立を
- **「短時間で」**
- **「手軽に」**

決めたいというニーズを解決するために開発しました。

### アプリが提供する価値
- **スロット形式でパパッと自動決定**
  主菜・副菜など「3品のおかず」をスロット形式で一瞬で提案。悩む時間をゼロにします。
- **献立決めを「楽しい体験」に**
  ただ機械的に決めるだけでなく、ゲーム感覚の演出（スロットアニメーションなど）を取り入れることで、面倒な献立決めをワクワクする楽しい時間へと変える体験を提供します。
- **いつでも、どこからでも**
  スマートフォン・PCのどちらからでも、手軽にストレスなく利用できるレスポンシブな操作性を実現しています。

## 🚀 主な機能

### 🎯 献立決定（メイン機能）
- **スロット式・3品おかず決定機能**
  - 主菜・副菜・副菜の3菜をスロットアニメーションの演出とともに自動で決定します。
<table>
  <tr>
    <td valign="top" width="50%">
      <p><b>▼ スロット画面</b></p>
      <img width="350" alt="Image" src="https://github.com/user-attachments/assets/07bbfbbd-a56a-4838-bfcc-4adc6caac2d0" />
    </td>
    <td valign="top" width="50%">
      <p><b>▼ スロットで当選したメニューを表示</b></p>
      <img width="350" alt="Image" src="https://github.com/user-attachments/assets/df4e7db6-e8bf-40c1-9a5c-9e4091d3d865" />
    </td>
  </tr>
</table>

### 📅 履歴機能
- **献立履歴機能**
  - スロットで決定したその日の献立をストックできます。
  - 過去に何を食べたかを振り返ることで、日々の栄養管理や「最近これ食べてないな」という気づきをサポートします。
<table>
  <tr>
    <td valign="top" width="50%">
      <p><b>▼ 献立履歴の一覧</b></p>
      <img width="350" alt="Image" src="https://github.com/user-attachments/assets/85de1b58-c98b-4d30-a625-7d03583aca93" />
    </td>
    <td valign="top" width="50%">
      <p><b>▼ 履歴の削除確認</b></p>
      <img width="350" alt="Image" src="https://github.com/user-attachments/assets/7743294f-10f3-4a4a-ae08-8feb4fe7e021" />
    </td>
  </tr>
</table>

### 🛠️ 管理機能
- **メニュー管理機能（CRUD）**
  - 自分食べたいメニューを登録・編集・削除できます。
  - **ソフトデリート（論理削除）対応:** 誤ってメニューを削除しても、過去の「献立履歴（ meal_records ）」が壊れないように、データを完全に消さず安全に保持する設計にしています。
- **リアルタイム類似メニュー検索機能（重複登録防止）✨**
  - メニュー登録時、入力した文字に合わせて「すでに同じメニューが登録されていないか」をJavaScriptで裏から自動検索（API連携）して通知します。
  - **デバウンス（300ms）の実装:** 1文字入力するごとにサーバーへ無駄な通信がいかないよう、入力が少し止まったタイミングで賢く通信を走らせる、実務レベルの負荷対策・UI/UXを施しています。
<table>
  <tr>
    <td valign="top" width="50%">
      <p><b>▼ メニュー登録</b></p>
      <img width="350" alt="Image" src="https://github.com/user-attachments/assets/82f60bc6-0980-4189-ac61-e9b7257273e8" />
    </td>
    <td valign="top" width="50%">
      <p><b>▼ メニュー一覧（CRUD）</b></p>
      <img width="350" alt="Image" src="https://github.com/user-attachments/assets/474ff513-03da-4bf6-97bc-52923b485498" />
    </td>
  </tr>
  <tr>
    <td valign="top" width="50%">
      <p><b>▼ 類似メニュー検索（重複警告）</b></p>
      <img width="350" alt="Image" src="https://github.com/user-attachments/assets/2345d76a-c94c-484d-88fc-7624b5bc0a6b" />
    </td>
    <td valign="top" width="50%">
      <p><b>▼ メニュー更新画面</b></p>
      <img width="350" alt="Image" src="https://github.com/user-attachments/assets/82f7d89d-bc06-4950-825e-3b207c9d29a0" />
    </td>
  </tr>
  <tr>
    <td valign="top" width="50%">
      <p><b>▼ メニュー削除時</b></p>
      <img width="350" alt="Image" src="https://github.com/user-attachments/assets/24ed178a-9bd0-4016-903e-1edf507f1cb7" />
    </td>
    <td valign="top" width="50%"></td>
  </tr>
</table>

## 🛠️ 使用技術

### 💻 フロントエンド
- JavaScript（ES6+ / 非同期通信・DOM操作）
- Alpine.js（軽量フロントエンドフレームワーク / 状態管理・UIインタラクション）
- Tailwind CSS（レスポンシブ対応・UIデザイン）

### ⚙️ バックエンド / インフラ
- PHP 8.5.3
- Laravel 12.53.0
- PostgreSQL
- Docker / Laravel Sail（コンテナ開発環境）

### 🧪 テスト・品質保証
品質担保およびバグの早期発見のため、PHPUnitを用いたFeatureテスト（機能テスト）を網羅的に実装しています。

- **MenuControllerTest / MenuUploadTest**
  - メニューの登録・編集・削除（CRUD機能）が、認証されたユーザーごとに正しく制限されて動作するかを検証。
  - 画像アップロード時のバリデーションや、ストレージへの保存処理が正常が行われるかを厳密にテスト。
- **MenuSearchTest（リアルタイム類似検索テスト）**
  - サジェストAPIの挙動を検証。他人のメニューが混ざらないことや、**ソフトデリート（論理削除）されたメニューが確実に検索結果から除外されること**を担保。
- **SlotControllerTest**
  - アプリのメイン機能である「スロットによるおかず決定ロジック」の検証。主菜・副菜・汁物が仕様通り正しくランダムに選出・返却されるかをテスト。
- **MealRecordControllerTest**
  - スロットで決定したメニューが、ユーザーの「献立履歴」としてデータベースへ正確に保存・管理できるかをテスト。

## 📊 データベース設計（ER図）

<img width="860" height="692" alt="Image" src="https://github.com/user-attachments/assets/7a53122d-375f-47f1-a2d1-573bc4fca093" />

---

## 💻 動作確認・ローカルセットアップ方法

Docker環境（Laravel Sail）を使用して簡単にローカル環境を構築できます。

```bash
# 1. リポジトリのクローン
git clone 【あなたのGitHubリポジトリのURL】
cd 【リポジトリのフォルダ名】

# 2. パッケージのインストール
composer install
npm install

# 3. .envファイルの準備と設定変更
cp .env.example .env
php artisan key:generate

# 4. Dockerコンテナの起動（Laravel Sail）
./vendor/bin/sail up -d

# 5. マイグレーションと初期データ（シーダー）の投入
./vendor/bin/sail artisan migrate --seed


import axios from "axios";
import { gsap } from "gsap";

let allMenus = [];
// 各スロットのアニメーションを保存するオブジェクト
const activeTimelines = {
    main: null,
    sub_a: null,
    sub_b: null,
}

// 当選したメニューを保持するオブジェクト（グローバルに近い場所で定義）
let selectedResults = {
    1: null, // 主菜
    2: null, // 副菜A
    3: null  // 副菜B
};

const saveBtn = document.getElementById('save-button');

window.toggleSlot = function(type, isRolling) {
    const idMap = {
        1: 'slot-main',
        2: 'slot-sub-a',
        3: 'slot-sub-b'
    };

    const ul = document.getElementById(idMap[type]);
    if(!ul) return;

    const liElements = ul.querySelectorAll('.slot-li');
    // const itemCount = allMenus.filter(menu => menu.type_id === type).length;

    if (isRolling) {
        selectedResults[type] = null;
        updateSaveButtonState();

        const items = allMenus.filter(m => m.type_id === type);
        const h = 75;
        const totalHeight = items.length * h;

        // 初回のみタイムラインを作成、2回目以降は既存のものを使う
        if (!activeTimelines[type]) {
            activeTimelines[type] = gsap.to(liElements, {
                y: `+=${totalHeight}%`, // 1周分の距離
                duration: 0.5,         // 速度
                ease: "none",
                repeat: -1,
            });
        }

        // 提示コードの応用：
        // timeScale(1) で速度を戻し、resume() で止まった所から再開
        activeTimelines[type].timeScale(1).resume();
    } else {
        if (activeTimelines[type]) {
            // 1. まずアニメーションを一時停止
            activeTimelines[type].pause();

            // 2. 現在の「中途半端な位置」から「キリの良い位置（中央）」へ吸着させる
            const items = allMenus.filter(m => m.type_id === type);
            const randomIndex = Math.floor(Math.random() * items.length);
            const offset = -12.5;

            // 当選したメニューのデータを保存
            const wonMenu = items[randomIndex];
            selectedResults[type] = wonMenu;
            selectedResults[type] = items[randomIndex];
            updateSaveButtonState();
            // 確認用ログ
            console.log(`当選ID: ${wonMenu.id}, 当選名: ${wonMenu.name}`);


            gsap.to(liElements, {
                y: (i) => (-(i - 1 - randomIndex) * 75 + offset) + "%",
                duration: 1.5,
                ease: "back.out(1.2)",
                onComplete: () => {
                    isSelectionComplete();
                }
            });
        }
    }

};

window.initSlot = function(menus) {
    allMenus = menus;
    console.log('スロット初期化開始', menus);

    allMenus = menus;
    // 2. それぞれのスロット（ul）に要素を追加する
    setupSlot('slot-main', 1);
    setupSlot('slot-sub-a', 2);
    setupSlot('slot-sub-b', 3);

    updateSaveButtonState();
};

function setupSlot(elementId, category) {
    const ul = document.getElementById(elementId);
    if (!ul) return;

    const items = allMenus.filter(menu => menu.type_id === category);
    if (items.length === 0) return;

    ul.innerHTML = '';

    // ★ ループ用に、最後の商品を先頭にも入れる
    // const displayItems = [items[items.length - 1], ...items, items[0]];
    // const displayItems = items;
    const displayItems = (items.length >= 3)
    ? [items[items.length - 1], ...items, items[0]]
    : items;

    displayItems.forEach((item) => {
        const li = document.createElement('li');
        li.dataset.typeId = item.type_id; // data-type-id になる
        li.dataset.name = item.name;      // data-name になる
        li.textContent = `${item.name} (ID:${item.type_id})`; // 画面上にも出しちゃう
        // 画像パスの組み立て
            const rawPath = item.image_path;
            let imgPath = 'images/no_image.png';

            if(rawPath) {
                imgPath = `/storage/${rawPath}`;
            }

        // 前にお話しした「上下を暗くするグラデーション」をセット
        li.style.backgroundImage = `url("${imgPath}")`;
        li.classList.add('slot-li');
        ul.appendChild(li);
    });

    // ★ GSAPで初期位置を決定（これで上部がチラ見えします）
    const liElements = ul.querySelectorAll('.slot-li');

    // 1. スロットの1枚の高さを変数にする（CSSと合わせる）
    const h = 75;

    // 2. 中央に配置するためのオフセット計算
    // (100% - 75%) / 2 = 12.5%
    // const offset = (100 - h) / 2;
    // const offset = 12.5;
    const offset = -12.5;

    gsap.set(liElements, {
        // y: (i) => {
        //     // (i - 1) * h  => 縦に隙間なく並べる
        //     // + offset     => 全体を12.5%分下げて、1枚目をど真ん中に持ってくる
        //     return ((i - 1) * h + offset) + "%";
        // }
        y: (i) => {
            // 【修正ポイント】
            // (i - 1) * h ではなく -(i - 1) * h にする
            // これにより、0番目が中央、1番目がその上、2番目がさらに上...と配置されます
            return (-(i - 1) * h + offset) + "%";
        }
    });
}

function isSelectionComplete() {
    return selectedResults[1] && selectedResults[2] && selectedResults[3];
}

function updateSaveButtonState() {
    const saveBtn = document.getElementById('save-button');
    if (!saveBtn) return;

    // 3つすべてにデータが入っているかチェック
    const isComplete = selectedResults[1] && selectedResults[2] && selectedResults[3];

    if (isComplete) {
        // 押せる状態
        saveBtn.style.pointerEvents = 'auto';
        saveBtn.style.opacity = '1';
        saveBtn.classList.remove('cursor-not-allowed'); // カーソルを通常に
    } else {
        // 押せない状態
        saveBtn.style.pointerEvents = 'none';
        saveBtn.style.opacity = '0.3'; // 少し暗くして「無効」を表現
        saveBtn.classList.add('cursor-not-allowed'); // 禁止マークのカーソルに（Tailwind）
    }
}

if(saveBtn) {
    saveBtn.addEventListener('click', async(e) => {
        e.preventDefault();

        // 万が一、無効化をすり抜けて押された場合の最終ガード
        if(!isSelectionComplete()) {
            alert('すべてのスロットを止めて、献立を確定させてください。');
            return; // ここで処理を中断
        }

        // 3. 送信データの作成
        console.log("保存処理を開始します...");
        const postData = {
            main_dish_id: selectedResults[1].id,
            sub_dish_a_id: selectedResults[2].id,
            sub_dish_b_id: selectedResults[3].id,
        };

        try {
            // 4. LaravelのルートへPOST送信
            const response = await axios.post('/meal-records', postData);

            if(response.status === 200) {
                alert('今日の献立を保存しました。');
                saveBtn.style.pointerEvents = 'none';
                saveBtn.style.opacity = '0.3';
            }
        } catch (error) {
            console.error('保存エラー', error);

            if(error.response && error.response.status === 442) {
                alert(error.response.data.message);
            } else {
                alert('保存に失敗しました。ログイン状態を確認してください。');
            }
        }
    });
}


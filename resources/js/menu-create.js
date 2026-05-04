let lastValidSrc = null;
const fileNameDisplay = document.getElementById('fileNameDisplay');
let fileName = '画像が選択されていません。';

window.previewImage = function(input) {
    const file = input.files[0];
    const preview = document.getElementById('imagePreview');
    const placeHolder = document.getElementById('placeholder');
    const deleteBtn = document.getElementById('deleteImageBtn');
    const laravelErrorMsg = document.querySelector('.laravel-image-error');
    const erroeMsg = document.getElementById('jsImageError');

    // バリデーションルール
    const maxSize = 2 * 1024 * 1024;    //2M
    const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'];

    if (erroeMsg) {
        erroeMsg.textContent = '';
    }
    // PHP（Laravel）側のエラー表示も画面に残っているなら一緒に消す
    if (laravelErrorMsg) {
        laravelErrorMsg.style.display = 'none';
    }

    if(file) {
        // バリデーションチェックの開始
        let errorMessage = '';
        if(!allowedTypes.includes(file.type)) {
            errorMessage = '画像ファイルは（jpg, jpeg, png, gif）の中から選択してください。\n別の画像を選ぶか、画像なしで登録する場合は、画像を削除して「登録」を押してください。';
        } else if(file.size > maxSize) {
            errorMessage = '画像サイズは2MB以下にしてください。\n別の画像を選ぶか、画像なしで登録する場合は、画像を削除して「登録」を押してください。';
        }

        // 変数名を imageReader にして衝突を回避
        const imageReader = new FileReader();

        imageReader.onload = function(e) {
            preview.src = e.target.result;
            preview.classList.remove('hidden');
            deleteBtn.classList.remove('hidden');
            if (placeHolder) placeHolder.classList.add('hidden');
            if (fileNameDisplay) fileNameDisplay.textContent = file.name;

            // エラーがあればメッセージを表示
            if (erroeMsg) {
                erroeMsg.textContent = errorMessage;
            }
        };

        imageReader.readAsDataURL(file);

        if(errorMessage) {
            // エラーがある場合：ボタン無効化, プレビューは更新しない
            if(erroeMsg) erroeMsg.textContent = errorMessage;
            // setSubmitButtonState(saveMenuBtn, false);
            // input.value = '';
            return;
        }

        // エラーがない場合：ボタンを有効化、メッセージを消す
        if(erroeMsg) erroeMsg.textContent = '';
        // setSubmitButtonState(saveMenuBtn, true);


        const reader = new FileReader();
        reader.onload = function(e) {
            lastValidSrc = e.target.result; //成功した画像を保存
            fileName = file.name;
                preview.src = lastValidSrc;
                preview.classList.remove('hidden');
                deleteBtn.classList.remove('hidden');
                placeHolder.classList.add('hidden');
                fileNameDisplay.textContent = fileName;
            }

            reader.readAsDataURL(file);
    } else {
        // キャンセルされた場合：もし過去に成功した画像があればそれを表示
        if(lastValidSrc && fileName) {
            preview.src = lastValidSrc;
            placeHolder.classList.add('hidden');
            fileNameDisplay.textContent = fileName;
        }

        // setSubmitButtonState(saveMenuBtn, true);
    }
}

// function setSubmitButtonState(btn, isEnable) {
//     if(!btn) return;
//     if(isEnable) {
//         btn.disabled = false;
//         btn.classList.remove('opacity-50', 'cursor-not-allowed');
//         btn.classList.add('cursor-pointer');
//     } else {
//         btn.disabled = true;
//         btn.classList.add('opacity-50', 'cursor-not-allowed');
//         btn.classList.remove('cursor-pointer');
//     }
// }

window.clearImage = function() {
    const input = document.getElementById('imageInput');
    const preview = document.getElementById('imagePreview');
    const placeHolder = document.getElementById('placeholder');
    const deleteBtn = document.getElementById('deleteImageBtn');
    const saveMenuBtn =document.getElementById('save-menu-btn');
    const erroeMsg = document.getElementById('js-ImageError');

    input.value = '';  //選択されているファイルをリセット
    lastValidSrc = null;  //保持していた画像も消す
    fileNameDisplay.textContent = '画像が選択されていません。';
    if(erroeMsg) erroeMsg.textContent = '';     //エラーを消す

    preview.src = '';
    preview.classList.add('hidden');
    deleteBtn.classList.add('hidden');
    placeHolder.classList.remove('hidden');

    // クリア後は登録ボタンを押せる状態に戻す
    // setSubmitButtonState(saveMenuBtn, true);

}

document.addEventListener('DOMContentLoaded', () => {
    const menuForm = document.getElementById('menu-form');
    const saveMenuBtn = document.getElementById('save-menu-btn');
    const deleteBtns = document.querySelectorAll('.delete-individual-btn');

    // メニュー登録処理
    if (saveMenuBtn && menuForm) {
        saveMenuBtn.addEventListener('click', (e) => {
            // 1. まずは通常のフォーム送信を止める
            e.preventDefault();

            // 2. 自作モーダルを呼び出す
            window.dispatchEvent(new CustomEvent('confirm-delete', {
                detail: {
                    message: 'この内容でメニューを登録しますか？',
                    btnText: '登録する', // ボタンの文字を「登録する」に変更
                    action: () => {
                        // 3. モーダルで「登録する」が押されたらフォームを送信
                        menuForm.submit();
                    }
                }
            }));
        });
    }

    // メニュー削除処理
    if(deleteBtns.length > 0) {
        deleteBtns.forEach((btn) => {
            btn.addEventListener('click', () => {
                const menuName = btn.dataset.name;
                const form = btn.closest('.delete-menu-form');

                window.dispatchEvent(new CustomEvent('confirm-delete', {
                    detail: {
                        message: `${menuName}を削除します。よろしいですか？`,
                        btnText: '削除する',
                        action: () => {
                            form.submit();
                        }
                    }
                }));
            });
        });
    }

    const messageBox = document.getElementById('flash-message');

    // if(messageBox) {
    if(messageBox && messageBox.textContent.trim() !== '') {
        requestAnimationFrame(() => {
            messageBox.classList.remove('-translate-y-full');
            messageBox.classList.add('translate-y-0');
            messageBox.classList.add('opacity-100');

        });

        setTimeout(() => {
            messageBox.classList.remove('opacity-100', 'translate-y-0');
            messageBox.classList.add('opacity-0', '-translate-y-full');
            setTimeout(() => {
                messageBox.remove();
            }, 500);
        }, 7000);
    }
});



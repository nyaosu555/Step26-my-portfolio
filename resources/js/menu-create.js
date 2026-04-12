let lastValidSrc = null;
const fileNameDisplay = document.getElementById('fileNameDisplay');
let fileName = '画像が選択されていません。';

window.previewImage = function(input) {
    const file = input.files[0];
    const preview = document.getElementById('imagePreview');
    const placeHolder = document.getElementById('placeholder');
    const deleteBtn = document.getElementById('deleteImageBtn');

    if(file) {
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
    }
}

window.clearImage = function() {
    const input = document.getElementById('imageInput');
    const preview = document.getElementById('imagePreview');
    const placeHolder = document.getElementById('placeholder');
    const deleteBtn = document.getElementById('deleteImageBtn');

    input.value = '';  //選択されているファイルをリセット
    lastValidSrc = null;  //保持していた画像も消す
    fileNameDisplay.textContent = '画像が選択されていません。';

    preview.src = '';
    preview.classList.add('hidden');
    deleteBtn.classList.add('hidden');
    placeHolder.classList.remove('hidden');

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



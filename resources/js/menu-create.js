let lastValidSrc = null;

window.previewImage = function(input) {
    const file = input.files[0];
    const preview = document.getElementById('imagePreview');
    const placeHolder = document.getElementById('placeholder');
    const deleteBtn = document.getElementById('deleteImageBtn');

    if(file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            lastValidSrc = e.target.result; //成功した画像を保存
                preview.src = lastValidSrc;
                // preview.classList.remove('hidden');
                deleteBtn.classList.remove('hidden');
                placeHolder.classList.add('hidden');
            }

            reader.readAsDataURL(file);
    } else {
        // キャンセルされた場合：もし過去に成功した画像があればそれを表示
        if(lastValidSrc) {
            preview.src = lastValidSrc;
            placeHolder.classList.add('hidden');
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

    preview.src = '';
    deleteBtn.classList.add('hidden');
    placeHolder.classList.remove('hidden');

}

const messageBox = document.getElementById('flash-message');

if(messageBox) {
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

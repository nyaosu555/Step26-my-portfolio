import axios from "axios";

document.addEventListener('DOMContentLoaded', () => {
    const selectDeleteBtn = document.getElementById('select-delete-btn');

    if(selectDeleteBtn) {
        selectDeleteBtn.addEventListener('click', async() => {
            // チェックされているチェックボックスを全て取得
            const checkedBoxes = document.querySelectorAll('.record-checkbox:checked');
            const selectedIds = Array.from(checkedBoxes).map(checkedBox => checkedBox.value);

            if(selectedIds.length === 0) {
                alert('削除する項目を選択してください。');
                return;
            }

            if(!confirm(`${selectedIds.length}件の献立を削除してもよろしいでしょうか？`)) {
                return;
            }

            try {
                const response = await axios.post('/meal-records/select-delete', {
                    ids: selectedIds,
                });

                if(response.status === 200) {
                    alert(`データを${selectedIds.length}件削除しました。`);
                    location.reload();
                }
            } catch (error) {
                console.error('削除エラー:', error);
                alert('削除に失敗しました。');
            }
        });
    }
});

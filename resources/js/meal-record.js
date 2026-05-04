import axios from "axios";

document.addEventListener('DOMContentLoaded', () => {
    const selectDeleteBtn = document.getElementById('select-delete-btn');

    if(selectDeleteBtn) {
        selectDeleteBtn.addEventListener('click', async() => {
            // チェックされているチェックボックスを全て取得
            const checkedBoxes = document.querySelectorAll('.record-checkbox:checked');
            const selectedIds = Array.from(checkedBoxes).map(checkedBox => checkedBox.value);

            // if(selectedIds.length === 0) {
            //     alert('削除する項目を選択してください。');
            //     return;
            // }
            if(selectedIds.length === 0) {
                // 自作のモーダル「通知モード」を呼び出す
                window.dispatchEvent(new CustomEvent('alert-message', {
                    detail: {
                        message: '削除する項目を選んでください。',
                    }
                }));
                return;
            }

            // if(!confirm(`${selectedIds.length}件の献立を削除してもよろしいでしょうか？`)) {
            //     return;
            // }
            // 自作の確認モーダルを呼び出す
            window.dispatchEvent(new CustomEvent('confirm-delete', {
                detail: {
                    message: `${selectedIds.length}件の献立を削除してもよろしいでしょうか？`,
                    btnText: '削除する',
                    action: async() => {
                        try {
                            // const response = await axios.post('/meal-records/select-delete', {
                            const response = await axios.delete('/meal-records/select-delete', {
                                data: {
                                    // ids: selectedIds,
                                    ids: selectedIds,
                                }
                            });

                            if(response.status === 200) {
                                // alert(`データを${selectedIds.length}件削除しました。`);
                                location.reload();
                            }
                        } catch (error) {
                            console.error('削除エラー:', error);
                            // alert('削除に失敗しました。');
                            window.dispatchEvent(new CustomEvent('alert-message', {
                                detail: {message: '削除に失敗しました。時間をおいて再度お試しください。'}
                            }));
                        }
                    }
                }
            }));

            // try {
            //     const response = await axios.post('/meal-records/select-delete', {
            //         ids: selectedIds,
            //     });

            //     if(response.status === 200) {
            //         alert(`データを${selectedIds.length}件削除しました。`);
            //         location.reload();
            //     }
            // } catch (error) {
            //     console.error('削除エラー:', error);
            //     alert('削除に失敗しました。');
            // }
        });
    }

    const messageBox = document.getElementById('flash-message');
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

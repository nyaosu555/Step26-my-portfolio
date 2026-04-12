{{-- resources/views/components/confirm-modal.blade.php --}}
<div
    x-data="{
        show: false,
        message: '',
        btnText: 'はい',
        mode: 'confirm',
        confirmAction: null,

        {{-- trigger関数をここで定義 --}}
        trigger(msg, action = null, mode = 'confirm', btnText = 'はい') {
            this.message = msg;
            this.confirmAction = action;
            this.mode = mode;
            this.btnText = btnText || (mode === 'confirm' ? 'はい' : 'わかった');
            this.show = true;
        },

        doConfirm() {
            if (this.mode === 'confirm' && typeof this.confirmAction === 'function') {
                this.confirmAction();
            }
            this.show = false;
        }
    }"
    {{-- $event.detail から各値を取り出して trigger メソッドに渡す --}}
    @confirm-delete.window="trigger($event.detail.message, $event.detail.action, 'confirm', $event.detail.btnText)"
    @alert-message.window="trigger($event.detail.message, null, 'alert', $event.detail.btnText)"
    x-show="show"
    x-cloak
    class="fixed inset-0 z-[100] flex items-center justify-center p-4"
>
    {{-- 背景のオーバーレイ --}}
    <div x-show="show" x-transition.opacity class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="show = false"></div>

    {{-- モーダル本体 --}}
    <div x-show="show" x-transition.scale.90
        class="relative bg-white rounded-[2em] shadow-2xl p-8 max-w-sm w-full text-center border-4"
        :class="mode === 'confirm' ? 'border-red-500' : 'border-[#F0BA32]'">

        <div class="text-4xl mb-4">
            <span x-text="mode === 'confirm' ? '⚠️' : '💡'"></span>
        </div>

        <p class="text-gray-800 font-bold text-lg mb-6" x-text="message"></p>

        <div class="flex justify-center gap-4">
            {{-- キャンセルボタン（確認モードの時だけ表示） --}}
            <template x-if="mode === 'confirm'">
                <button @click="show = false" class="px-6 py-2 rounded-full bg-gray-200 text-gray-700 font-bold hover:bg-gray-300 transition">
                    キャンセル
                </button>
            </template>

            {{-- 実行ボタン --}}
            <button @click="mode === 'confirm' ? doConfirm() : show = false"
                class="px-6 py-2 rounded-full text-white font-bold shadow-md transition active:scale-95"
                :class="mode === 'confirm' ? 'bg-red-500 hover:bg-red-600' : 'bg-[#DA5019] hover:bg-[#b84315]'">
                <span x-text="btnText"></span>
            </button>
        </div>
    </div>
</div>

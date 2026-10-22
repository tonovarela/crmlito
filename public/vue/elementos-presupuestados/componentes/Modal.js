const Modal = Vue.component('modal', {
    props: {
        show: {
            type: Boolean,
            required: true
        },
    },
    methods: {
        close() {
            this.$emit('close');
        },
        clickOutside(e) {
            if (e.target === this.$refs.overlay) {
                this.close();
            }
        }
    },
    template: `
        <div class="text-base m-0 min-h-full min-w-full overflow-y-auto bg-transparent backdrop:bg-transparent" v-if="show" @click="clickOutside" ref="dialog">
            <div class="fixed inset-0 overflow-y-auto px-4 py-6 sm:px-0" scroll-region>
                <div v-if="show" class="fixed inset-0 transform" @click="close">
                    <div class="absolute inset-0 bg-gray-500 opacity-75" />
                </div>

                <div v-if="show" class="mb-6 bg-white rounded-lg overflow-hidden shadow-xl transform sm:w-full sm:mx-auto max-w-lg">
                    <div class="px-6 py-4">
                        <div class="text-lg font-medium text-gray-900">
                            <slot name="title" />
                        </div>

                        <div class="mt-4 text-sm text-gray-600">
                            <slot name="content" />
                        </div>
                    </div>

                    <div class="flex flex-row justify-end px-6 py-4 bg-gray-100 text-end">
                        <slot name="footer" />
                    </div>
                </div>
            </div>
        </div>
    `
});
export default Modal;
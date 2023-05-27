<template>
    <Modal @modal-close="handleClose"
           :show="show"
           role="alertdialog"
           size="sm">
        <form
                @submit.prevent="handleConfirm"
                class="mx-auto bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden"
        >
            <slot/>
            <ModalFooter>
                <div class="ml-auto">
                    <LinkButton
                            type="button"
                            data-testid="cancel-button"
                            dusk="cancel-delete-button"
                            @click.prevent="handleClose"
                            class="mr-3"
                    >
                        {{ __('Cancel') }}
                    </LinkButton>

                    <LoadingButton
                            ref="confirmButton"
                            dusk="confirm-delete-button"
                            :processing="working"
                            :disabled="working"
                            component="DangerButton"
                            type="submit"
                    >
                        {{ confirmButtonText }}
                    </LoadingButton>
                </div>
            </ModalFooter>
        </form>
    </Modal>
</template>

<script setup>


    export default {
        name: "UploadImageModal",
    }
    import {ref, watchEffect} from 'vue'

    const props = defineProps({
        confirmButtonText: {
            type: String,
            default: 'Delete'
        }
    })
    const emit = defineEmits(['close', 'confirm'])

    const confirmButton = ref(null)
    watchEffect(() => {
        // Only focus when mounted (e.g. if hidden through :show)
        if (confirmButton.value) {
            confirmButton.value.focus()
        }
    })

    const handleClose = () => {
        emit('close')
    };

    const handleConfirm = () => {
        emit('confirm')
    };
</script>
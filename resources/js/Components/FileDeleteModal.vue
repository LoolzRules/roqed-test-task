<script setup>
import Modal from "@/Components/Modal.vue"
import {ref} from "vue";
import ErrorMessage from "@/Components/ErrorMessage.vue";

const props = defineProps({
    show: Boolean,
    slug: String,
})
const emit = defineEmits(['close'])

const loading = ref(false)
const loadingError = ref('')

function close() {
    if (!loading.value) {
        emit('close')
    }
}
async function deleteFile() {
    loading.value = true
    loadingError.value = ''

    try {
        const response = await axios.delete(route('api.delete', { slug: props.slug }))
        if (response.data.message === 'ok') {
            window.location.href = route('app.main')
            return
        }
        loadingError.value = response.data.message
    } catch (error) {
        loadingError.value = 'An error occured while making request.'
    }

    loading.value = false
}
</script>

<template>
    <Modal :show="show" @close="close">
        <form
            @submit.prevent="deleteFile"
            class="flex flex-col p-4">
            <h4 class="text-lg font-bold mb-3">Are you sure you want to delete this file?</h4>
            <div class="flex">
                <button
                    type="submit"
                    :disabled="loading"
                    class="w-full mr-2 custom-button negative">Delete</button>
                <button
                    type="button"
                    :disabled="loading"
                    @click="close"
                    class="w-full ml-2 custom-button info">Cancel</button>
            </div>
            <ErrorMessage :message="loadingError" class="mt-3 text-center"/>
        </form>
    </Modal>
</template>


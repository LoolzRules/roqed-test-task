<script setup>
import Modal from "@/Components/Modal.vue"
import {computed, ref} from "vue";
import ProgressBar from "@/Components/ProgressBar.vue";
import ErrorMessage from "@/Components/ErrorMessage.vue";

const props = defineProps({
    show: Boolean,
    slug: {
        type: String,
        default: ''
    },
    formData: Object,
})
const emit = defineEmits(['close', 'errors'])

const loading = ref(false)
const uploadProgress = ref(0)
const loadingError = ref('')

const isUpdate = computed(() => !!props.slug)
const submitParams = computed(() => {
    return !isUpdate.value
        ? ['api.upload']
        : ['api.update', { slug: props.slug }]
})

function close() {
    if (!loading.value) {
        emit('close')
    }
}
async function saveFile() {
    console.log('lol')
    loading.value = true
    loadingError.value = ''


    try {
        const response = await axios.post(route(...submitParams.value), props.formData, {
            headers: { 'Content-Type': 'multipart/form-data' },
            onUploadProgress: progressEvent => {
                uploadProgress.value = Math.round((progressEvent.loaded / progressEvent.total) * 100)
            },
        })

        if (response.data.message === 'ok') {
            if (isUpdate.value) {
                window.location.reload()
            } else {
                window.location.href = route('app.edit', { slug: response.data.slug })
            }
            return
        }

        loadingError.value = response.data.message
    } catch (error) {
        if (error?.response?.data?.errors) {
            emit('close', error.response.data.errors)
        } else {
            loadingError.value = 'Upload error.'
        }
    }

    loading.value = false
}
</script>

<template>
    <Modal :show="show" @close="close">
        <form
            @submit.prevent="saveFile"
            class="flex flex-col p-4">
            <h4 class="text-lg font-bold mb-3">Save changes?</h4>
            <div class="flex">
                <button
                    type="button"
                    :disabled="loading"
                    @click="close"
                    class="w-full mr-2 custom-button negative">Cancel</button>
                <button
                    type="submit"
                    :disabled="loading"
                    class="w-full ml-2 custom-button positive">Save</button>
            </div>
            <ProgressBar v-if="uploadProgress" :value="uploadProgress" class="mt-3"/>
            <ErrorMessage :message="loadingError" class="mt-3 text-center"/>
        </form>
    </Modal>
</template>


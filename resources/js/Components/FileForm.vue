<script setup>
import TextInput from "@/Components/TextInput.vue";
import FileInput from "@/Components/FileInput.vue";
import {computed, ref} from "vue";
import FileDeleteModal from "@/Components/FileDeleteModal.vue";
import ProgressBar from "@/Components/ProgressBar.vue";

const props = defineProps({
    existingFile: Object
})

const file = ref(null)
const filename = ref(props.existingFile?.title ?? '')
const loading = ref(false)
const loadingError = ref('')
const errors = ref({})
const uploadProgress = ref(0)

const submitParams = computed(() => {
    return !props.existingFile
        ? ['api.upload']
        : ['api.update', { slug: props.existingFile.slug }]
})

const showDeleteModal = ref(false)
const validate = {
    filename() {
        if (filename.value && filename.value.length > 127) {
            errors.value.file = ['File name is too long.']
            return
        }

        errors.value.file = ''
    },
    file() {
        if (!file.value && !props.existingFile) {
            errors.value.file = ['File is required.']
            return
        }
        if (file.value && file.value.size > 8 * 1024 * 1024) {
            errors.value.file = ['File is too big.']
            return
        }

        errors.value.file = ''
    }
}

const update = {
    filename(f) {
        filename.value = f
        validate.filename()
    },
    file(f) {
        file.value = f
        validate.file()
    }
}

async function postData() {
    const fields = ['file', 'filename']
    for (const key of fields) {
        validate[key]()
    }
    for (const key of fields) {
        if (errors.value[key]) return
    }


    loading.value = true
    const formData = new FormData()
    if (file.value) {
        formData.append('file', file.value)
    }
    formData.append('filename', filename.value)

    try {
        const response = await axios.post(route(...submitParams.value), formData, {
            headers: { 'Content-Type': 'multipart/form-data' },
            onUploadProgress: progressEvent => {
                uploadProgress.value = Math.round((progressEvent.loaded / progressEvent.total) * 100)
            },
        })

        if (response.data.message !== 'ok') {
            loadingError.value = response.data.message
            return
        }

        if (props.existingFile) {
            window.location.reload()
        } else {
            window.location.href = route('app.edit', { slug: response.data.slug })
        }
    } catch (error) {
        if (error?.response?.data?.errors) {
            errors.value = error.response.data.errors
        } else {
            loadingError.value = 'Upload error.'
        }
    }

    loading.value = false
    uploadProgress.value = 0
}
</script>

<template>
    <form @submit.prevent="postData"
          class="flex flex-col">
        <TextInput
            label="File name"
            name="filename"
            :model-value="filename"
            :errors="errors.filename"
            @update:model-value="update.filename"
            class="my-4"/>

        <FileInput
            :label="`${!existingFile ? 'File' : 'Replacement file'} (max 8MB)`"
            name="file"
            :model-value="file"
            :errors="errors.file"
            :required="!existingFile"
            @update:model-value="update.file"
            class="my-4"/>

        <div class="flex">
            <button
                v-if="existingFile"
                type="button"
                @click="showDeleteModal = true"
                :disabled="loading"
                class="w-full mr-2 custom-button negative">Delete</button>
            <button
                type="submit"
                :disabled="loading"
                :class="{ 'ml-2': existingFile }"
                class="w-full custom-button positive">Save</button>
        </div>

        <ProgressBar v-if="uploadProgress" :value="uploadProgress" class="mt-3"/>

        <span v-else-if="loadingError" class="text-md text-red-500 mt-3 text-center uppercase">{{ loadingError }}</span>

        <FileDeleteModal
            v-if="existingFile"
            :slug="existingFile.slug"
            :show="showDeleteModal"
            @close="() => showDeleteModal = false"/>
    </form>
</template>

<style scoped>

</style>

<script setup>
import TextInput from "@/Components/TextInput.vue";
import FileInput from "@/Components/FileInput.vue";
import {ref} from "vue";
import FileDeleteModal from "@/Components/FileDeleteModal.vue";
import FileSaveModal from "@/Components/FileSaveModal.vue";

const props = defineProps({
    existingFile: Object
})

const file = ref(null)
const filename = ref(props.existingFile?.title ?? '')
const loading = ref(false)
const loadingError = ref('')
const errors = ref({})
const formData = ref(null)


const showSaveModal = ref(false)
function closeSaveModal(errs) {
    if (errs) {
        errors.value = errs
    }
    showSaveModal.value = false
}

const showDeleteModal = ref(false)
function closeDeleteModal() {
    showDeleteModal.value = false
}


const validate = {
    filename() {
        if (filename.value && filename.value.length > 127) {
            errors.value.filename = ['File name is too long.']
            return
        }

        delete errors.value.filename
    },
    file() {
        console.log(file.value, props.existingFile)
        if (!file.value && !props.existingFile) {
            errors.value.file = ['File is required.']
            return
        }
        if (file.value && file.value.size > 8 * 1024 * 1024) {
            errors.value.file = ['File is too big.']
            return
        }

        delete errors.value.file
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

    if (!file.value && !filename.value) {
        loadingError.value = 'Nothing to upload'
        return
    }

    formData.value = new FormData()
    if (file.value) {
        formData.value.append('file', file.value)
    }
    if (filename.value) {
        formData.value.append('filename', filename.value)
    }

    showSaveModal.value = true
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

        <span v-if="loadingError" class="text-md text-red-500 mt-3 text-center">{{ loadingError }}</span>

        <FileSaveModal
            :show="showSaveModal"
            :slug="existingFile?.slug"
            :form-data="formData"
            @close="closeSaveModal"/>

        <FileDeleteModal
            v-if="existingFile"
            :show="showDeleteModal"
            :slug="existingFile.slug"
            @close="closeDeleteModal"/>
    </form>
</template>

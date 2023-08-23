<script setup>
import {computed} from "vue";
import {formatFileSize} from "@/utils.js";
import FileDownloadLink from "@/Components/FileDownloadLink.vue";
import FilePreview from "@/Components/FilePreview.vue";

const props = defineProps({
    data: Array,
})

const fields = ['title', 'size', 'extension', 'previewLink', 'fileLink', 'editLink']
const headers = {
    title: 'Title',
    size: 'Size (MB)',
    extension: 'Extension',
    previewLink: 'Preview',
    fileLink: 'Download link',
    editLink: 'Edit link'
}
const textAlignments = {
    title: 'left',
    size: 'right',
    extension: 'right',
    previewLink: 'right',
    fileLink: 'right',
    editLink: 'right'
}

const fileListToDisplay = computed(() => {
    return props.data.map(file => ({
        title: file.title,
        size: formatFileSize(file.size),
        extension: file.extension.toUpperCase(),
        file_link: file.file_link,
        preview_link: file.preview_link,
        editLink: route('app.edit', { slug: file.slug }),
    }))
})
</script>

<template>
    <div class="overflow-x-auto">
        <table class="w-full border text-sm">
            <thead>
            <tr class="border bg-gray-200">
                <th v-for="field of fields" :key="field"
                    :class="`text-${textAlignments[field]}`"
                    class="py-2 px-4">
                    {{ headers[field] }}
                </th>
            </tr>
            </thead>
            <tbody>
            <template v-if="fileListToDisplay.length">
                <tr v-for="file of fileListToDisplay" :key="file.id" class="border [&:nth-child(2)]:bg-gray-100">
                    <td :class="`text-${textAlignments.title}`" class="py-2 px-4">
                        {{ file.title }}
                    </td>
                    <td :class="`text-${textAlignments.size}`" class="py-2 px-4">
                        {{ file.size }}
                    </td>
                    <td :class="`text-${textAlignments.extension}`" class="py-2 px-4">
                        {{ file.extension }}
                    </td>
                    <td :class="`text-${textAlignments.previewLink}`" class="py-2 px-4">
                        <FilePreview :file="file"/>
                    </td>
                    <td :class="`text-${textAlignments.fileLink}`" class="py-2 px-4">
                        <FileDownloadLink :file="file"/>
                    </td>
                    <td :class="`text-${textAlignments.editLink}`" class="py-2 px-4">
                        <a :href="file.editLink" class="custom-button info">Edit</a>
                    </td>
                </tr>
            </template>
            <template v-else>
                <tr><td colspan="6" class="py-2 px-4 text-center">No results</td></tr>
            </template>
            </tbody>
        </table>
    </div>
</template>

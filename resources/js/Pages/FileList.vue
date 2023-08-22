<script setup>
import {Head} from "@inertiajs/vue3"
import {computed} from "vue"
import {formatDownloadLink, formatEditLink} from "@/utils.js";

const props = defineProps({
    fileList: Array,
    pageNumber: Number,
    totalNumberOfPages: Number,
})
const fields = ['title', 'size', 'extension', 'preview100x100', 'downloadLink', 'editLink']
const headers = {
    title: 'Название файла',
    size: 'Размер (МБ)',
    extension: 'Расширение',
    preview100x100: 'Предпросмотр',
    downloadLink: 'Ссылка на скачивание',
    editLink: 'Редактировать'
}
const textAlignments = {
    title: 'left',
    size: 'right',
    extension: 'right',
    preview100x100: 'right',
    downloadLink: 'right',
    editLink: 'right'
}

const fileListToDisplay = computed(() => {
    props.fileList.map(file => ({
        title: file.title,
        size: file.size,
        extension: file.extension.toUpperCase(),
        preview100x100: file.preview100x100,
        downloadLink: formatDownloadLink(file.id),
        editLink: formatEditLink(file.id),
    }))
})
</script>

<template>
    <Head title="File List" />

    <div class="relative flex-column min-h-screen">
        <table class="overflow-x-auto w-full">
            <thead>
                <tr>
                    <th colspan="3" class="text-left">
                        <input type="text"/>
                    </th>
                    <th colspan="3" class="text-right">
                        <a href="/">&leftarrow;</a>
                        <span>{{ pageNumber }} из {{ totalNumberOfPages }}</span>
                        <a href="/">&rightarrow;</a>
                    </th>
                </tr>
                <tr>
                    <th v-for="field of fields" :key="field"
                        :class="`text-${textAlignments[field]}`">{{ headers[field] }}</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="file of fileListToDisplay" :key="file.id">
                    <td class="">{{ file.title }}</td>
                    <td>{{ file.size }}</td>
                    <td>{{ file.extension }}</td>
                    <td>
                        <template v-if="file.preview">
                            <img :src="file.preview"/>
                        </template>
                    </td>
                    <td><a :href="file.downloadLink">Скачать</a></td>
                    <td><a :href="file.editLink">Редактировать</a></td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<style>
th, td {
    padding: 0.5rem 1rem;
}
</style>

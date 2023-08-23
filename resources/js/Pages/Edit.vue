<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import FileForm from "@/Components/FileForm.vue";
import FileDownloadLink from "@/Components/FileDownloadLink.vue";
import FilePreview from "@/Components/FilePreview.vue";

const props = defineProps({
    file: Object,
})
</script>

<template>
    <AppLayout :title="`${file.title} - edit`">
        <div class="max-w-[420px] mx-auto flex flex-col">
            <div class="flex justify-center pb-4">
                <FilePreview :file="file"/>
            </div>

            <FileDownloadLink :file="file"/>

            <FileForm :existing-file="file" title="Update file"/>
        </div>
    </AppLayout>
</template>

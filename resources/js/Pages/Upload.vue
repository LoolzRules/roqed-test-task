<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import FileForm from "@/Components/FileForm.vue";
</script>

<template>
    <AppLayout title="Upload file">
        <div class="max-w-[420px] mx-auto flex flex-col">
            <FileForm title="Upload file"/>
        </div>
    </AppLayout>
</template>

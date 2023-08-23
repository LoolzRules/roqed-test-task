<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import SearchBar from "@/Components/SearchBar.vue";
import Pagination from "@/Components/Pagination.vue";
import FileList from "@/Components/FileList.vue";

const props = defineProps({
    search: String,
    pagination: Object,
})
</script>

<template>
    <AppLayout title="File List">
        <SearchBar :search="search"/>

        <Pagination :pagination="pagination"/>

        <FileList :data="pagination.data"/>
    </AppLayout>
</template>

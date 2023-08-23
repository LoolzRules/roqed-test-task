<script setup>
const props = defineProps({
    file: Object,
})
</script>

<template>
    <a
        :href="file.file_link"
        :download="`${file.title}.${file.extension.toLowerCase()}`"
        class="custom-button info">Download</a>
</template>

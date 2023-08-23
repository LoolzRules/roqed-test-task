<script setup>
const props = defineProps({
    file: Object,
})
</script>

<template>
    <img v-if="file.preview_link" :src="file.preview_link" class="preview-image"/>
    <span v-else>Preview unavailable</span>
</template>

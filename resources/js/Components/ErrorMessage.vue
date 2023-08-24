<script setup>
defineProps({
    message: String
})
</script>

<template>
    <span v-if="message" class="text-md text-red-500">{{ message }}</span>
</template>


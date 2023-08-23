<script setup>
import {computed} from "vue";

const props = defineProps({
    value: Number,
    max: {
        type: Number,
        default: 100
    }
})
const displayValue = computed(() => `${props.value * 100 / props.max}%`)
</script>

<template>
    <div class="w-full relative bg-gray-200 rounded-md h-6">
        <div class="bg-blue-600 h-6 rounded-md" :style="{ width: displayValue }"></div>
        <span class="absolute w-full h-full top-0 text-center">{{ displayValue }}</span>
    </div>
</template>

<style scoped>

</style>

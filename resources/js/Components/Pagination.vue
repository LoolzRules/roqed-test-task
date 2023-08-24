<script setup>
import {computed} from "vue";

const props = defineProps({
    pagination: Object,
})

const linksToDisplay = computed(() => {
    return props.pagination.links.slice(1, props.pagination.links.length-1)
})
</script>

<template>
    <div class="flex justify-between align-items-center font-bold py-2">
        <span class="self-center text-sm mr-4">Records {{ pagination.from }}-{{ pagination.to }} of total {{ pagination.total }}</span>
        <ul v-if="linksToDisplay.length > 1" class="border rounded-md">
            <li v-for="(link, idx) of linksToDisplay" :key="idx"
                class="border inline-flex text-sm">
                <span v-if="link.active" class="text-gray-400 bg-blue-100 px-3 py-2">{{ link.label }}</span>
                <a v-else :href="link.url" class="px-3 py-2">{{ link.label }}</a>
            </li>
        </ul>
    </div>
</template>


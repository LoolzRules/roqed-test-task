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
        <span class="self-center text-sm mr-4">{{ pagination.data.length }} records of {{ pagination.total }}</span>
        <ul v-if="linksToDisplay.length > 1" class="border">
            <li v-for="(link, idx) of linksToDisplay" :key="idx"
                  class="border inline-block text-sm px-3 py-2"
                  :class="{ 'text-gray-400': link.active }">
                <template v-if="link.active">{{ link.label }}</template>
                <a v-else :href="link.url">{{ link.label }}</a>
            </li>
        </ul>
    </div>
</template>


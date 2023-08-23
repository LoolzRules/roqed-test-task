<script setup>
import {computed, ref} from "vue";
import TextInput from "@/Components/TextInput.vue";
import SearchButton from "@/Components/SearchButton.vue";

const props = defineProps({
    search: String
})

const searchValue = ref(props.search ?? '')
const href = computed(() => {
    const url = new URL(window.location.href)
    if (!searchValue.value) {
        url.searchParams.delete('search')
    } else {
        url.searchParams.set('search', searchValue.value.trim())
    }
    return url.toString()
})
</script>

<template>
    <div class="flex justify-center">
        <TextInput
            placeholder="Search here"
            class="mr-1"
            :model-value="searchValue"
            @update:model-value="v => searchValue = v"/>

        <SearchButton :href="href">Search</SearchButton>
    </div>
</template>


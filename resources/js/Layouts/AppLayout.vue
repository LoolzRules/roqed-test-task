<script setup>
import { Head } from '@inertiajs/vue3'
import NavLink from "@/Components/NavLink.vue"

defineProps({
    title: String,
})
</script>

<template>
    <Head :title="title" />

    <div class="relative flex-column min-h-screen page">
        <!-- Page Heading -->
        <header class="bg-blue px-4 pt-4">
            <NavLink :href="route('app.main')" :active="route().current('app.main')">
                Main
            </NavLink>
            <NavLink :href="route('app.upload')" :active="route().current('app.upload')">
                Upload
            </NavLink>
        </header>

        <!-- Page Content -->
        <main class="p-4">
            <slot />
        </main>
    </div>
</template>

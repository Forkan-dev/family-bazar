<script setup lang="ts">
import AppHeader from '../components/AppHeader.vue'
import AppSidebar from '../components/AppSidebar.vue'
import AppFooter from '../components/AppFooter.vue'
</script>

<template>
    <div class="min-h-screen bg-background flex">
        <AppSidebar />
        <div class="ml-16 lg:ml-64 flex flex-col flex-1 transition-all duration-300">
            <AppHeader />
            <main class="flex-1 overflow-auto">
                <slot />
            </main>
            <AppFooter />
        </div>
    </div>
</template>

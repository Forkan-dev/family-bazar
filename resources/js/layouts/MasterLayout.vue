<script setup lang="ts">
import AppSidebar from '../components/AppSidebar.vue';
import AppHeader from '../components/AppHeader.vue';
import AppFooter from '../components/AppFooter.vue';
</script>

<template>
    <v-app>
        <AppSidebar />
        <AppHeader />
        <v-main class="bg-lighten-0">
            <v-container fluid class="pa-6">
                <slot />
            </v-container>
        </v-main>
        <AppFooter />
    </v-app>
</template>
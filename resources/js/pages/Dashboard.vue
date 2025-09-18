<script setup lang="ts">
// import AppLayout from '@/layouts/AppLayout.vue';
// import { dashboard } from '@/routes';
// import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
// import PlaceholderPattern from '../components/PlaceholderPattern.vue';

// const breadcrumbs: BreadcrumbItem[] = [
//     {
//         title: 'Dashboard',
//         href: dashboard().url,
//     },
// ];
import { ref } from 'vue'

const drawer = ref(true)
const rail = ref(false)
const activeItem = ref('dashboard')

const breadcrumbs = [
    { title: 'Home', href: '/' },
    { title: 'Dashboard', disabled: true }
]

const stats = [
    {
        title: 'Total Users',
        value: '1,234',
        icon: 'mdi-account-group',
        color: 'primary'
    },
    {
        title: 'Revenue',
        value: '$45,678',
        icon: 'mdi-currency-usd',
        color: 'success'
    },
    {
        title: 'Orders',
        value: '856',
        icon: 'mdi-shopping',
        color: 'warning'
    },
    {
        title: 'Growth',
        value: '+12%',
        icon: 'mdi-trending-up',
        color: 'info'
    }
]

const logout = () => {
    console.log('Logging out...')
    // Add your logout logic here
}
</script>

<template>

    <Head title="Dashboard" />

    <!-- <AppLayout :breadcrumbs="breadcrumbs">

    </AppLayout> -->
    <!-- start from here -->

    <v-app>
        <!-- Navigation Drawer - Full height, always visible -->
        <v-navigation-drawer v-model="drawer" :rail="rail" permanent app @click="rail = false" class="elevation-2">

            <!-- User Profile Section -->
            <v-list class="pa-2">
                <v-list-item prepend-avatar="https://randomuser.me/api/portraits/men/85.jpg" title="John Leider"
                    subtitle="Admin">
                    <template v-slot:append>
                        <v-btn :icon="rail ? 'mdi-chevron-right' : 'mdi-chevron-left'" variant="text" size="small"
                            @click.stop="rail = !rail">
                        </v-btn>
                    </template>
                </v-list-item>
            </v-list>

            <v-divider></v-divider>

            <!-- Navigation Menu -->
            <v-list density="compact" nav class="mt-2">
                <v-list-item prepend-icon="mdi-view-dashboard" title="Dashboard" value="dashboard"
                    :active="activeItem === 'dashboard'" @click="activeItem = 'dashboard'" rounded="xl" class="ma-2">
                </v-list-item>

                <v-list-item prepend-icon="mdi-account" title="My Account" value="account"
                    :active="activeItem === 'account'" @click="activeItem = 'account'" rounded="xl" class="ma-2">
                </v-list-item>

                <v-list-item prepend-icon="mdi-account-group-outline" title="Users" value="users"
                    :active="activeItem === 'users'" @click="activeItem = 'users'" rounded="xl" class="ma-2">
                </v-list-item>

                <v-list-item prepend-icon="mdi-chart-line" title="Analytics" value="analytics"
                    :active="activeItem === 'analytics'" @click="activeItem = 'analytics'" rounded="xl" class="ma-2">
                </v-list-item>

                <v-list-item prepend-icon="mdi-cog" title="Settings" value="settings"
                    :active="activeItem === 'settings'" @click="activeItem = 'settings'" rounded="xl" class="ma-2">
                </v-list-item>
            </v-list>

            <!-- Logout at bottom -->
            <template v-slot:append>
                <v-list class="pa-2">
                    <v-list-item prepend-icon="mdi-logout" title="Logout" @click="logout" rounded="xl" class="ma-2">
                    </v-list-item>
                </v-list>
            </template>
        </v-navigation-drawer>

        <!-- App Bar - Positioned next to sidebar, not floating -->
        <v-app-bar app flat class="border-b" color="white" height="64">


            <v-spacer></v-spacer>

            <!-- Search -->
            <v-text-field prepend-inner-icon="mdi-magnify" placeholder="Search..." variant="outlined" density="compact"
                hide-details style="max-width: 300px" class="me-4">
            </v-text-field>

            <!-- Action Buttons -->
            <v-btn icon="mdi-bell-outline" variant="text" class="me-2">
                <v-icon>mdi-bell-outline</v-icon>
                <v-badge color="error" content="3" offset-x="12" offset-y="12">
                </v-badge>
            </v-btn>

            <v-btn icon="mdi-email-outline" variant="text" class="me-2">
                <v-icon>mdi-email-outline</v-icon>
            </v-btn>

            <!-- User Menu -->
            <v-menu>
                <template v-slot:activator="{ props }">
                    <v-btn v-bind="props" class="me-4" variant="text">
                        <v-avatar size="32">
                            <v-img src="https://randomuser.me/api/portraits/men/85.jpg"></v-img>
                        </v-avatar>
                        <v-icon class="ml-2">mdi-chevron-down</v-icon>
                    </v-btn>
                </template>
                <v-list>
                    <v-list-item>
                        <v-list-item-title>Profile</v-list-item-title>
                    </v-list-item>
                    <v-list-item>
                        <v-list-item-title>Settings</v-list-item-title>
                    </v-list-item>
                    <v-divider></v-divider>
                    <v-list-item @click="logout">
                        <v-list-item-title>Logout</v-list-item-title>
                    </v-list-item>
                </v-list>
            </v-menu>
        </v-app-bar>

        <!-- Main Content Area -->
        <v-main class="bg-lighten-0">
            <v-container fluid class="pa-6">
                <!-- Breadcrumbs -->
                <!-- <v-breadcrumbs :items="breadcrumbs" class="pa-0 mb-4">
                </v-breadcrumbs> -->

                <v-row align="center" justify="center" dense>
                    <v-col cols="12" md="6">
                        <v-card append-icon="mdi-check" class="mx-auto" prepend-icon="mdi-account"
                            subtitle="prepend-icon and append-icon" title="Icons">
                            <v-card-text>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
                                eiusmod.</v-card-text>
                        </v-card>
                    </v-col>

                    <v-col cols="12" md="6">
                        <v-card class="mx-auto" subtitle="prepend and append" title="Icons">
                            <template v-slot:prepend>
                                <v-icon color="primary" icon="mdi-account"></v-icon>
                            </template>
                            <template v-slot:append>
                                <v-icon color="success" icon="mdi-check"></v-icon>
                            </template>
                            <v-card-text>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
                                eiusmod.</v-card-text>
                        </v-card>
                    </v-col>

                    <v-col cols="12" md="6">
                        <v-card append-avatar="https://cdn.vuetifyjs.com/images/john.jpg" class="mx-auto"
                            prepend-avatar="https://cdn.vuetifyjs.com/images/logos/v-alt.svg"
                            subtitle="prepend-avatar and append-avatar" title="Avatars">
                            <v-card-text>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
                                eiusmod.</v-card-text>
                        </v-card>
                    </v-col>

                    <v-col cols="12" md="6">
                        <v-card class="mx-auto" subtitle="prepend and append" title="Avatars">
                            <template v-slot:prepend>
                                <v-avatar color="blue-darken-2">
                                    <v-icon icon="mdi-alarm"></v-icon>
                                </v-avatar>
                            </template>
                            <template v-slot:append>
                                <v-avatar size="24">
                                    <v-img alt="John" src="https://cdn.vuetifyjs.com/images/john.png"></v-img>
                                </v-avatar>
                            </template>
                            <v-card-text>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
                                eiusmod.</v-card-text>
                        </v-card>
                    </v-col>
                </v-row>
            </v-container>
        </v-main>
    </v-app>
</template>

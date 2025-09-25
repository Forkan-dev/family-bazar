<script setup lang="ts">
import { ref } from 'vue'
import { Link } from '@inertiajs/vue3';

const drawer = ref(true)
const rail = ref(false)
const activeItem = ref('dashboard')

const logout = () => {
    console.log('Logging out...')
    // Add your logout logic here
}
</script>

<template>
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
            <Link :href="route('dashboard')" style="text-decoration: none; color: inherit;">
            <v-list-item prepend-icon="mdi-view-dashboard" title="Dashboard" value="dashboard"
                :active="activeItem === 'dashboard'" rounded="xl" class="ma-2">
            </v-list-item>
            </Link>

            <v-list-group value="Products ">
                <template v-slot:activator="{ props }">
                    <v-list-item v-bind="props" prepend-icon="mdi-package-variant" title="Products"></v-list-item>
                </template>

                <Link :href="route('products.index')" style="text-decoration: none; color: inherit;">
                <v-list-item prepend-icon="mdi-package" title="All Products" value="products"
                    :active="route().current('products.index') || route().current('products.create') || route().current('products.edit')"
                    rounded="xl" class="ma-2 ps-10"></v-list-item>
                </Link>

                <!-- <Link :href="route('categories.index')" style="text-decoration: none; color: inherit;">
                    <v-list-item
                        prepend-icon="mdi-shape"
                        title="Categories"
                        value="categories"
                        :active="route().current('categories.index') || route().current('categories.create') || route().current('categories.edit')"
                        rounded="xl" class="ma-2 ps-10"
                    ></v-list-item>
                </Link> -->


            </v-list-group>

                <Link :href="route('categories.index')" style="text-decoration: none; color: inherit;">
                    <v-list-item
                        prepend-icon="mdi-shape"
                        title="Categories"
                        value="categories"
                        :active="route().current('categories.index')
                            || route().current('categories.create')
                            || route().current('categories.edit')"
                        rounded="xl"
                        class="ma-2"
                    />
                    </Link>




            <v-list-item prepend-icon="mdi-account" title="My Account" value="account"
                :active="activeItem === 'account'" @click="activeItem = 'account'" rounded="xl" class="ma-2">
            </v-list-item>

            <v-list-item prepend-icon="mdi-account-group-outline" title="Users" value="users"
                :active="activeItem === 'users'" @click="activeItem = 'users'" rounded="xl" class="ma-2">
            </v-list-item>

            <v-list-item prepend-icon="mdi-chart-line" title="Analytics" value="analytics"
                :active="activeItem === 'analytics'" @click="activeItem = 'analytics'" rounded="xl" class="ma-2">
            </v-list-item>

            <v-list-item prepend-icon="mdi-cog" title="Settings" value="settings" :active="activeItem === 'settings'"
                @click="activeItem = 'settings'" rounded="xl" class="ma-2">
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
</template>

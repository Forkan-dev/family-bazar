<script setup lang="ts">
import { ref } from 'vue'
import { Link } from '@inertiajs/vue3';

const drawer = ref(true)
const rail = ref(false)
const open = ref([])

const logout = () => {
    console.log('Logging out...')
    // Add your logout logic here
}

const isActive = (routeName: string) => {
    const currentRoute = route().current();
    return currentRoute ? currentRoute.startsWith(routeName) : false;
}

const isGroupActive = (routes: string[]) => {
    const currentRoute = route().current();
    return currentRoute ? routes.some(routeName => currentRoute.startsWith(routeName)) : false;
}

if (isGroupActive(['products', 'categories', 'tags'])) {
    open.value.push('Product')
}
</script>

<template>
    <v-navigation-drawer v-model="drawer" :rail="rail" permanent app @click="rail = false" class="elevation-1"
        color="surface" :width="260" :rail-width="64">
        <!-- Header with User Profile -->
        <div class="d-flex flex-column h-100">
            <div class="pa-3 border-b">
                <div class="d-flex align-center">
                    <v-avatar size="36" class="me-3">
                        <v-img src="https://randomuser.me/api/portraits/men/85.jpg"></v-img>
                    </v-avatar>
                    <div v-if="!rail" class="flex-grow-1">
                        <div class="text-subtitle-2 font-weight-medium">John Leider</div>
                        <div class="text-caption text-medium-emphasis">Administrator</div>
                    </div>
                    <v-btn :icon="rail ? 'mdi-chevron-right' : 'mdi-chevron-left'" variant="text" size="small"
                        class="ms-auto" @click.stop="rail = !rail" />
                </div>
            </div>

            <!-- Navigation Menu -->
            <v-list class="flex-grow-1 pa-1" density="compact" nav v-model:opened="open">
                <!-- Dashboard -->
                <Link :href="route('dashboard')" class="text-decoration-none">
                <v-list-item prepend-icon="mdi-view-dashboard" title="Dashboard" value="dashboard"
                    :active="route().current('dashboard')" rounded="lg" class="mb-1" />
                </Link>

                <!-- Products Section -->
                <v-list-group  value="Product" class="mb-1">
                    <template v-slot:activator="{ props }">
                        <v-list-item v-bind="props" prepend-icon="mdi-package-variant" title="Product " rounded="lg"
                            :active="isGroupActive(['products', 'categories', 'tags'])" />
                    </template>

                    <div class="ps-6 child-nav-align">
                        <Link :href="route('products.index')" class="text-decoration-none">
                        <v-list-item prepend-icon="mdi-package" title="Product List" :active="isActive('products')"
                            rounded="lg" class="mb-1" density="compact" />
                        </Link>

                        <!-- Uncomment when ready to use
                        <Link :href="route('categories.index')" class="text-decoration-none">
                            <v-list-item
                                prepend-icon="mdi-shape"
                                title="Categories"
                                :active="isActive('categories')"
                                rounded="lg"
                                class="mb-1"
                                density="compact"
                            />
                        </Link>

                        <Link :href="route('tags.index')" class="text-decoration-none">
                            <v-list-item
                                prepend-icon="mdi-tag"
                                title="Tags"
                                :active="isActive('tags')"
                                rounded="lg"
                                class="mb-1"
                                density="compact"
                            />
                        </Link>
                        -->
                    </div>
                </v-list-group>

                <!-- Other Menu Items -->
                <v-list-item prepend-icon="mdi-account" title="My Account" value="account" rounded="lg" class="mb-1" />

                <v-list-item prepend-icon="mdi-account-group-outline" title="Users" value="users" rounded="lg"
                    class="mb-1" />

                <v-list-item prepend-icon="mdi-chart-line" title="Analytics" value="analytics" rounded="lg"
                    class="mb-1" />

                <v-list-item prepend-icon="mdi-cog" title="Settings" value="settings" rounded="lg" class="mb-1" />
            </v-list>

            <!-- Footer with Logout -->
            <div class="pa-1 border-t">
                <v-list-item prepend-icon="mdi-logout" title="Logout" @click="logout" rounded="lg" color="error"
                    class="text-error" />
            </div>
        </div>
    </v-navigation-drawer>
</template>

<style scoped>
.child-nav-align {
    margin-left: -78px;

}

.border-b {
    border-bottom: 1px solid rgb(var(--v-theme-surface-variant));
}

.border-t {
    border-top: 1px solid rgb(var(--v-theme-surface-variant));
}

/* Custom hover effects */
.v-list-item:hover {
    background-color: rgba(var(--v-theme-primary), 0.08) !important;
}

/* Active state styling */
.v-list-item--active {
    background-color: rgba(var(--v-theme-primary), 0.12) !important;
    color: rgb(var(--v-theme-primary)) !important;
}

.v-list-item--active .v-icon {
    color: rgb(var(--v-theme-primary)) !important;
}

/* Rail mode adjustments */
.v-navigation-drawer--rail .v-list-item {
    justify-content: center;
}
</style>

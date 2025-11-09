<script setup lang="ts">
import { ref } from 'vue'
import { Link, usePage } from '@inertiajs/vue3';

const drawer = ref(true)
const rail = ref(false)
const open = ref([])

const { auth } = usePage().props

const hasPermission = (permission: string) => {
    if (!auth) {
        return false;
    }
    if (auth.is_super_admin) {
        return true;
    }
    if (!auth.permissions) {
        return false;
    }
    return auth.permissions.includes(permission)
}

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

if (isGroupActive(['product.products', 'product.categories', 'product.brands', 'product.locations'])) {
    open.value.push('Product')
}

if (isGroupActive(['admin.roles', 'admin.permissions', 'admin.roles.assign'])) {
    open.value.push('Admin')
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
                <Link :href="route('dashboard')" class="text-decoration-none" v-if="hasPermission('dashboard.view')">
                <v-list-item prepend-icon="mdi-view-dashboard" title="Dashboard" value="dashboard"
                    :active="route().current('dashboard')" rounded="lg" class="mb-1" />
                </Link>

                <!-- Content Section -->
                <v-list-group value="Content" class="mb-1" v-if="hasPermission('banner.view')">
                    <template v-slot:activator="{ props }">
                        <v-list-item v-bind="props" prepend-icon="mdi-folder-multiple-outline" title="Content" rounded="lg"
                            :active="isGroupActive(['admin.banners.index'])" />
                    </template>

                    <div class="ps-6 child-nav-align">
                        <Link :href="route('admin.banners.index')" class="text-decoration-none" v-if="hasPermission('banner.view')">
                        <v-list-item prepend-icon="mdi-watermark" title="Banners" :active="isActive('admin.banners.index')"
                            rounded="lg" class="mb-1" density="compact" />
                        </Link>
                    </div>
                </v-list-group>

                <!-- Products Section -->
                <v-list-group value="Product" class="mb-1" v-if="hasPermission('product.view')">
                    <template v-slot:activator="{ props }">
                        <v-list-item v-bind="props" prepend-icon="mdi-package-variant" title="Product " rounded="lg"
                            :active="isGroupActive(['product.products', 'product.categories', 'product.brands', 'product.locations'])" />
                    </template>

                    <div class="ps-6 child-nav-align">
                        <Link :href="route('product.products.index')" class="text-decoration-none" v-if="hasPermission('product.view')">
                        <v-list-item prepend-icon="mdi-package" title="Product List"
                            :active="isActive('product.products')" rounded="lg" class="mb-1" density="compact" />
                        </Link>

                        <Link :href="route('product.categories.index')" class="text-decoration-none" v-if="hasPermission('category.view')">
                        <v-list-item prepend-icon="mdi-shape" title="Categories"
                            :active="isActive('product.categories')" rounded="lg" class="mb-1" density="compact" />
                        </Link>

                        <Link :href="route('product.brands.index')" class="text-decoration-none" v-if="hasPermission('brand.view')">
                        <v-list-item prepend-icon="mdi-watermark" title="Brands" :active="isActive('product.brands')"
                            rounded="lg" class="mb-1" density="compact" />
                        </Link>

                        <Link :href="route('product.locations.index')" class="text-decoration-none" v-if="hasPermission('location.view')">
                        <v-list-item prepend-icon="mdi-map-marker" title="Locations"
                            :active="isActive('product.locations')" rounded="lg" class="mb-1" density="compact" />
                        </Link>
                    </div>
                </v-list-group>

                <!-- Users Section -->
                <v-list-group value="Users" class="mb-1" v-if="hasPermission('user.view')">
                    <template v-slot:activator="{ props }">
                        <v-list-item v-bind="props" prepend-icon="mdi-account-group-outline" title="Users" rounded="lg"
                            :active="isGroupActive(['users', 'account'])" />
                    </template>

                    <div class="ps-6 child-nav-align">
                        <v-list-item prepend-icon="mdi-account-group-outline" title="Users" value="users" rounded="lg"
                            class="mb-1" v-if="hasPermission('user.view')" />
                        <v-list-item prepend-icon="mdi-account" title="My Account" value="account" rounded="lg" class="mb-1" />
                    </div>
                </v-list-group>

                <!-- Admin Section -->
                <v-list-group value="Admin" class="mb-1" v-if="hasPermission('role.view') || hasPermission('permission.view')">
                    <template v-slot:activator="{ props }">
                        <v-list-item v-bind="props" prepend-icon="mdi-shield-crown-outline" title="Admin" rounded="lg"
                            :active="isGroupActive(['admin.roles', 'admin.permissions','admin.roles.assign'])" />
                    </template>

                    <div class="ps-6 child-nav-align">
                        <Link :href="route('admin.roles.index')" class="text-decoration-none" v-if="hasPermission('role.view')">
                        <v-list-item prepend-icon="mdi-shield-account" title="Roles" :active="isActive('admin.roles.index')"
                            rounded="lg" class="mb-1" density="compact" />
                        </Link>
                        <Link :href="route('admin.permissions.index')" class="text-decoration-none" v-if="hasPermission('permission.view')">
                        <v-list-item prepend-icon="mdi-shield-key" title="Permissions" :active="isActive('admin.permissions.index')"
                            rounded="lg" class="mb-1" density="compact" />
                        </Link>
                        <Link :href="route('admin.roles.assign.create')" class="text-decoration-none" v-if="hasPermission('role.update')">
                        <v-list-item prepend-icon="mdi-account-key" title="Assign Role" :active="isActive('admin.roles.assign')"
                            rounded="lg" class="mb-1" density="compact" />
                        </Link>
                    </div>
                </v-list-group>

                <!-- Other Menu Items -->
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

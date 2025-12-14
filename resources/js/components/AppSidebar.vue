<script setup lang="ts">
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar'
import { Button } from '@/components/ui/button'
import { Collapsible, CollapsibleContent, CollapsibleTrigger } from '@/components/ui/collapsible'
import { Separator } from '@/components/ui/separator'
import { Link, usePage } from '@inertiajs/vue3'
import {
  BarChart3,
  ChevronDown,
  ChevronLeft,
  ChevronRight,
  Cog,
  FolderOpen,
  Home,
  LogOut,
  MapPin,
  Package,
  Shield,
  ShieldCheck,
  Tag,
  Users,
  Zap,
} from 'lucide-vue-next'
import { ref } from 'vue'

const isCollapsed = ref(false)
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

</script>

<template>
    <div
        :class="[
            'fixed left-0 top-0 z-40 h-screen border-r bg-sidebar transition-all duration-300',
            isCollapsed ? 'w-16' : 'w-50'
        ]"
    >
        <div class="flex h-full flex-col">
            <!-- Header with User Profile -->
            <div class="flex items-center justify-between border-b border-sidebar-border p-4">
                <div v-if="!isCollapsed" class="flex items-center space-x-3">
                    <Avatar class="h-9 w-9">
                        <AvatarImage
                            src="https://randomuser.me/api/portraits/men/85.jpg"
                            alt="User avatar"
                        />
                        <AvatarFallback>JL</AvatarFallback>
                    </Avatar>
                    <div>
                        <p class="text-xs font-medium text-sidebar-foreground">John Leider</p>
                        <p class="text-xs text-sidebar-foreground/60">Administrator</p>
                    </div>
                </div>
                <Button
                    variant="ghost"
                    size="sm"
                    @click="isCollapsed = !isCollapsed"
                    class="ml-auto h-8 w-8 p-0"
                >
                    <ChevronLeft v-if="!isCollapsed" class="h-4 w-4" />
                    <ChevronRight v-else class="h-4 w-4" />
                </Button>
            </div>

            <!-- Navigation Menu -->
            <nav class="flex-1 space-y-1 p-3">
                <!-- Dashboard -->
                <Link
                    v-if="hasPermission('dashboard.view')"
                    :href="route('dashboard')"
                    class="block"
                >
                    <Button
                        variant="ghost"
                        :class="[
                            'w-full justify-start',
                            route().current('dashboard') && 'bg-sidebar-accent text-sidebar-accent-foreground'
                        ]"
                        size="sm"
                    >
                        <Home class="h-4 w-4" />
                        <span v-if="!isCollapsed" class="ml-2">Dashboard</span>
                    </Button>
                </Link>

                <!-- Content Section -->
                <Collapsible v-if="hasPermission('banner.view')">
                    <CollapsibleTrigger as-child>
                        <Button
                            variant="ghost"
                            class="w-full justify-start"
                            size="sm"
                            :class="[
                                isGroupActive(['admin.banners.index']) && 'bg-sidebar-accent text-sidebar-accent-foreground'
                            ]"
                        >
                            <FolderOpen class="h-4 w-4" />
                            <span v-if="!isCollapsed" class="ml-2">Content</span>
                            <ChevronDown v-if="!isCollapsed" class="ml-auto h-4 w-4" />
                        </Button>
                    </CollapsibleTrigger>
                    <CollapsibleContent class="space-y-1 pl-6">
                        <Link
                            v-if="hasPermission('banner.view')"
                            :href="route('admin.banners.index')"
                            class="block"
                        >
                            <Button
                                variant="ghost"
                                size="sm"
                                :class="[
                                    'w-full justify-start text-xs',
                                    isActive('admin.banners.index') && 'bg-sidebar-accent text-sidebar-accent-foreground'
                                ]"
                            >
                                <Zap class="h-4 w-4" />
                                <span v-if="!isCollapsed" class="ml-2">Banners</span>
                            </Button>
                        </Link>
                    </CollapsibleContent>
                </Collapsible>

                <!-- Products Section -->
                <Collapsible v-if="hasPermission('product.view')">
                    <CollapsibleTrigger as-child>
                        <Button
                            variant="ghost"
                            class="w-full justify-start"
                            size="sm"
                            :class="[
                                isGroupActive(['product.products', 'product.categories', 'product.brands', 'product.locations']) &&
                                'bg-sidebar-accent text-sidebar-accent-foreground'
                            ]"
                        >
                            <Package class="h-4 w-4" />
                            <span v-if="!isCollapsed" class="ml-2">Products</span>
                            <ChevronDown v-if="!isCollapsed" class="ml-auto h-4 w-4" />
                        </Button>
                    </CollapsibleTrigger>
                    <CollapsibleContent class="space-y-1 pl-6">
                        <Link
                            v-if="hasPermission('product.view')"
                            :href="route('product.products.index')"
                            class="block"
                        >
                            <Button
                                variant="ghost"
                                size="sm"
                                :class="[
                                    'w-full justify-start text-xs',
                                    isActive('product.products') && 'bg-sidebar-accent text-sidebar-accent-foreground'
                                ]"
                            >
                                <Package class="h-4 w-4" />
                                <span v-if="!isCollapsed" class="ml-2">Product List</span>
                            </Button>
                        </Link>

                        <Link
                            v-if="hasPermission('category.view')"
                            :href="route('product.categories.index')"
                            class="block"
                        >
                            <Button
                                variant="ghost"
                                size="sm"
                                :class="[
                                    'w-full justify-start text-xs',
                                    isActive('product.categories') && 'bg-sidebar-accent text-sidebar-accent-foreground'
                                ]"
                            >
                                <Tag class="h-4 w-4" />
                                <span v-if="!isCollapsed" class="ml-2">Categories</span>
                            </Button>
                        </Link>

                        <Link
                            v-if="hasPermission('brand.view')"
                            :href="route('product.brands.index')"
                            class="block"
                        >
                            <Button
                                variant="ghost"
                                size="sm"
                                :class="[
                                    'w-full justify-start text-xs',
                                    isActive('product.brands') && 'bg-sidebar-accent text-sidebar-accent-foreground'
                                ]"
                            >
                                <Zap class="h-4 w-4" />
                                <span v-if="!isCollapsed" class="ml-2">Brands</span>
                            </Button>
                        </Link>

                        <Link
                            v-if="hasPermission('location.view')"
                            :href="route('product.locations.index')"
                            class="block"
                        >
                            <Button
                                variant="ghost"
                                size="sm"
                                :class="[
                                    'w-full justify-start text-xs',
                                    isActive('product.locations') && 'bg-sidebar-accent text-sidebar-accent-foreground'
                                ]"
                            >
                                <MapPin class="h-4 w-4" />
                                <span v-if="!isCollapsed" class="ml-2">Locations</span>
                            </Button>
                        </Link>
                    </CollapsibleContent>
                </Collapsible>

                <!-- Users Section -->
                <Collapsible v-if="hasPermission('user.view')">
                    <CollapsibleTrigger as-child>
                        <Button
                            variant="ghost"
                            class="w-full justify-start"
                            size="sm"
                            :class="[
                                isGroupActive(['users', 'account']) && 'bg-sidebar-accent text-sidebar-accent-foreground'
                            ]"
                        >
                            <Users class="h-4 w-4" />
                            <span v-if="!isCollapsed" class="ml-2">Users</span>
                            <ChevronDown v-if="!isCollapsed" class="ml-auto h-4 w-4" />
                        </Button>
                    </CollapsibleTrigger>
                    <CollapsibleContent class="space-y-1 pl-6">
                        <Button
                            v-if="hasPermission('user.view')"
                            variant="ghost"
                            size="sm"
                            class="w-full justify-start text-xs"
                        >
                            <Users class="h-4 w-4" />
                            <span v-if="!isCollapsed" class="ml-2">Users</span>
                        </Button>
                        <Button
                            variant="ghost"
                            size="sm"
                            class="w-full justify-start text-xs"
                        >
                            <Users class="h-4 w-4" />
                            <span v-if="!isCollapsed" class="ml-2">My Account</span>
                        </Button>
                    </CollapsibleContent>
                </Collapsible>

                <!-- Admin Section -->
                <Collapsible v-if="hasPermission('role.view') || hasPermission('permission.view')">
                    <CollapsibleTrigger as-child>
                        <Button
                            variant="ghost"
                            class="w-full justify-start"
                            size="sm"
                            :class="[
                                isGroupActive(['admin.roles', 'admin.permissions', 'admin.roles.assign']) &&
                                'bg-sidebar-accent text-sidebar-accent-foreground'
                            ]"
                        >
                            <Shield class="h-4 w-4" />
                            <span v-if="!isCollapsed" class="ml-2">Admin</span>
                            <ChevronDown v-if="!isCollapsed" class="ml-auto h-4 w-4" />
                        </Button>
                    </CollapsibleTrigger>
                    <CollapsibleContent class="space-y-1 pl-6">
                        <Link
                            v-if="hasPermission('role.view')"
                            :href="route('admin.roles.index')"
                            class="block"
                        >
                            <Button
                                variant="ghost"
                                size="sm"
                                :class="[
                                    'w-full justify-start text-xs',
                                    isActive('admin.roles.index') && 'bg-sidebar-accent text-sidebar-accent-foreground'
                                ]"
                            >
                                <ShieldCheck class="h-4 w-4" />
                                <span v-if="!isCollapsed" class="ml-2">Roles</span>
                            </Button>
                        </Link>

                        <Link
                            v-if="hasPermission('permission.view')"
                            :href="route('admin.permissions.index')"
                            class="block"
                        >
                            <Button
                                variant="ghost"
                                size="sm"
                                :class="[
                                    'w-full justify-start text-xs',
                                    isActive('admin.permissions.index') && 'bg-sidebar-accent text-sidebar-accent-foreground'
                                ]"
                            >
                                <Shield class="h-4 w-4" />
                                <span v-if="!isCollapsed" class="ml-2">Permissions</span>
                            </Button>
                        </Link>

                        <Link
                            v-if="hasPermission('role.update')"
                            :href="route('admin.roles.assign.create')"
                            class="block"
                        >
                            <Button
                                variant="ghost"
                                size="sm"
                                :class="[
                                    'w-full justify-start text-xs',
                                    isActive('admin.roles.assign') && 'bg-sidebar-accent text-sidebar-accent-foreground'
                                ]"
                            >
                                <ShieldCheck class="h-4 w-4" />
                                <span v-if="!isCollapsed" class="ml-2">Assign Role</span>
                            </Button>
                        </Link>
                    </CollapsibleContent>
                </Collapsible>

                <!-- Other Menu Items -->
                <Button
                    variant="ghost"
                    class="w-full justify-start"
                    size="sm"
                >
                    <BarChart3 class="h-4 w-4" />
                    <span v-if="!isCollapsed" class="ml-2">Analytics</span>
                </Button>

                <Button
                    variant="ghost"
                    class="w-full justify-start"
                    size="sm"
                >
                    <Cog class="h-4 w-4" />
                    <span v-if="!isCollapsed" class="ml-2">Settings</span>
                </Button>
            </nav>

            <!-- Footer with Logout -->
            <div class="border-t border-sidebar-border p-3">
                <Button
                    variant="ghost"
                    class="w-full justify-start text-destructive hover:text-destructive"
                    size="sm"
                    @click="logout"
                >
                    <LogOut class="h-4 w-4" />
                    <span v-if="!isCollapsed" class="ml-2">Logout</span>
                </Button>
            </div>
        </div>
    </div>
</template>


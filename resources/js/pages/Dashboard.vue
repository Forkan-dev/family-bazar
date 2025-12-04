<script setup lang="ts">
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import { Head } from '@inertiajs/vue3'
import { User, Check, Bell } from 'lucide-vue-next'
import MasterLayout from '../layouts/MasterLayout.vue'
</script>

<template>
    <MasterLayout>
        <Head title="Dashboard" />

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6">
            <Card>
                <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                    <div class="flex items-center space-x-2">
                        <User class="h-4 w-4 text-primary" />
                        <div>
                            <CardTitle class="text-lg">Icons</CardTitle>
                            <p class="text-sm text-muted-foreground">prepend-icon and append-icon</p>
                        </div>
                    </div>
                    <Check class="h-4 w-4 text-green-600" />
                </CardHeader>
                <CardContent>
                    <p class="text-sm text-muted-foreground">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod.
                    </p>
                </CardContent>
            </Card>

            <Card>
                <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                    <div class="flex items-center space-x-2">
                        <User class="h-4 w-4 text-primary" />
                        <div>
                            <CardTitle class="text-lg">Icons</CardTitle>
                            <p class="text-sm text-muted-foreground">prepend and append</p>
                        </div>
                    </div>
                    <Check class="h-4 w-4 text-green-600" />
                </CardHeader>
                <CardContent>
                    <p class="text-sm text-muted-foreground">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod.
                    </p>
                </CardContent>
            </Card>

            <Card>
                <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                    <div class="flex items-center space-x-2">
                        <Avatar class="h-8 w-8">
                            <AvatarImage src="https://cdn.vuetifyjs.com/images/logos/v-alt.svg" alt="Logo" />
                            <AvatarFallback>V</AvatarFallback>
                        </Avatar>
                        <div>
                            <CardTitle class="text-lg">Avatars</CardTitle>
                            <p class="text-sm text-muted-foreground">prepend-avatar and append-avatar</p>
                        </div>
                    </div>
                    <Avatar class="h-8 w-8">
                        <AvatarImage src="https://cdn.vuetifyjs.com/images/john.jpg" alt="John" />
                        <AvatarFallback>JD</AvatarFallback>
                    </Avatar>
                </CardHeader>
                <CardContent>
                    <p class="text-sm text-muted-foreground">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod.
                    </p>
                </CardContent>
            </Card>

            <Card>
                <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                    <div class="flex items-center space-x-2">
                        <Avatar class="h-8 w-8 bg-blue-600">
                            <Bell class="h-4 w-4 text-white" />
                        </Avatar>
                        <div>
                            <CardTitle class="text-lg">Avatars</CardTitle>
                            <p class="text-sm text-muted-foreground">prepend and append</p>
                        </div>
                    </div>
                    <Avatar class="h-6 w-6">
                        <AvatarImage src="https://cdn.vuetifyjs.com/images/john.png" alt="John" />
                        <AvatarFallback>J</AvatarFallback>
                    </Avatar>
                </CardHeader>
                <CardContent>
                    <p class="text-sm text-muted-foreground">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod.
                    </p>
                </CardContent>
            </Card>
        </div>
    </MasterLayout>
</template>

<script setup lang="ts">
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import { Badge } from '@/components/ui/badge'
import MasterLayout from '@/layouts/MasterLayout.vue'
import { Head, Link } from '@inertiajs/vue3'
import { ArrowLeft, Edit, MapPin, User, Building2, Percent, CheckCircle2, XCircle } from 'lucide-vue-next'

const route = (name: string, params?: any) => {
    return (window as any).route(name, params)
}

const props = defineProps<{
    shop: {
        id: number
        name: string
        shop_owner_id: number | null
        commission_rate: number | null
        zone_id: number
        lat: number | null
        lon: number | null
        type: string
        is_commission_based: boolean
        status: boolean
        created_at: string
        updated_at: string
        shop_owner?: {
            id: number
            user: {
                name: string
                email: string
                phone?: string
            }
        }
        zone?: {
            name: string
            address: string
        }
    }
}>()

const getTypeColor = (type: string) => {
    const colors: Record<string, string> = {
        retail: 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
        wholesale: 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
        distributor: 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300'
    }
    return colors[type] || 'bg-gray-100 text-gray-800'
}

const formatDate = (date: string) => {
    return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    })
}
</script>

<template>
    <MasterLayout>

        <Head :title="`Shop - ${shop.name}`" />

        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <Link :href="route('admin.shops.index')">
                <Button variant="ghost" size="sm">
                    <ArrowLeft class="h-4 w-4 mr-2" />
                    Back to Shops
                </Button>
                </Link>
                <Link :href="route('admin.shops.edit', shop.id)">
                <Button>
                    <Edit class="h-4 w-4 mr-2" />
                    Edit Shop
                </Button>
                </Link>
            </div>
        </div>

        <!-- Shop Details -->
        <div class="max-w-4xl mx-auto space-y-6">
            <!-- Main Info Card -->
            <Card>
                <CardHeader>
                    <div class="flex items-start justify-between">
                        <div>
                            <CardTitle class="text-3xl">{{ shop.name }}</CardTitle>
                            <div class="flex items-center gap-2 mt-2">
                                <Badge :class="getTypeColor(shop.type)">
                                    {{ shop.type }}
                                </Badge>
                                <Badge v-if="shop.status" class="bg-green-100 text-green-800">
                                    <CheckCircle2 class="h-3 w-3 mr-1" />
                                    Active
                                </Badge>
                                <Badge v-else class="bg-red-100 text-red-800">
                                    <XCircle class="h-3 w-3 mr-1" />
                                    Inactive
                                </Badge>
                            </div>
                        </div>
                    </div>
                </CardHeader>
            </Card>

            <!-- Details Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Shop Owner Info -->
                <Card>
                    <CardHeader>
                        <CardTitle class="text-lg flex items-center">
                            <User class="h-5 w-5 mr-2" />
                            Shop Owner
                        </CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div v-if="shop.shop_owner" class="space-y-2">
                            <div>
                                <p class="text-sm text-muted-foreground">Name</p>
                                <p class="font-medium">{{ shop.shop_owner.user.name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-muted-foreground">Email</p>
                                <p class="font-medium">{{ shop.shop_owner.user.email }}</p>
                            </div>
                            <div v-if="shop.shop_owner.user.phone">
                                <p class="text-sm text-muted-foreground">Phone</p>
                                <p class="font-medium">{{ shop.shop_owner.user.phone }}</p>
                            </div>
                        </div>
                        <p v-else class="text-muted-foreground">No owner assigned</p>
                    </CardContent>
                </Card>

                <!-- Zone Info -->
                <Card>
                    <CardHeader>
                        <CardTitle class="text-lg flex items-center">
                            <Building2 class="h-5 w-5 mr-2" />
                            Zone Information
                        </CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div v-if="shop.zone" class="space-y-2">
                            <div>
                                <p class="text-sm text-muted-foreground">Zone Name</p>
                                <p class="font-medium">{{ shop.zone.name }}</p>
                            </div>
                            <div v-if="shop.zone.address">
                                <p class="text-sm text-muted-foreground">Address</p>
                                <p class="font-medium">{{ shop.zone.address }}</p>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <!-- Commission Info -->
                <Card>
                    <CardHeader>
                        <CardTitle class="text-lg flex items-center">
                            <Percent class="h-5 w-5 mr-2" />
                            Commission
                        </CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div class="space-y-2">
                            <div>
                                <p class="text-sm text-muted-foreground">Commission Based</p>
                                <p class="font-medium">
                                    {{ shop.is_commission_based ? 'Yes' : 'No' }}
                                </p>
                            </div>
                            <div v-if="shop.is_commission_based">
                                <p class="text-sm text-muted-foreground">Rate</p>
                                <p class="font-medium text-2xl">
                                    {{ parseFloat(shop.commission_rate || 0).toFixed(2) }}%
                                </p>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <!-- Location Info -->
                <Card>
                    <CardHeader>
                        <CardTitle class="text-lg flex items-center">
                            <MapPin class="h-5 w-5 mr-2" />
                            Location
                        </CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div class="space-y-2">
                            <div v-if="shop.lat && shop.lon">
                                <div>
                                    <p class="text-sm text-muted-foreground">Latitude</p>
                                    <p class="font-medium">{{ shop.lat }}</p>
                                </div>
                                <div class="mt-2">
                                    <p class="text-sm text-muted-foreground">Longitude</p>
                                    <p class="font-medium">{{ shop.lon }}</p>
                                </div>
                                <a :href="`https://www.google.com/maps?q=${shop.lat},${shop.lon}`" target="_blank"
                                    class="inline-flex items-center text-sm text-primary hover:underline mt-2">
                                    View on Map
                                </a>
                            </div>
                            <p v-else class="text-muted-foreground">No coordinates set</p>
                        </div>
                    </CardContent>
                </Card>
            </div>

            <!-- Timestamps -->
            <Card>
                <CardContent class="pt-6">
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <p class="text-muted-foreground">Created</p>
                            <p class="font-medium">{{ formatDate(shop.created_at) }}</p>
                        </div>
                        <div>
                            <p class="text-muted-foreground">Last Updated</p>
                            <p class="font-medium">{{ formatDate(shop.updated_at) }}</p>
                        </div>
                    </div>
                </CardContent>
            </Card>
        </div>
    </MasterLayout>
</template>

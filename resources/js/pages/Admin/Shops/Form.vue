<script setup lang="ts">
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import {
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select'
import { Switch } from '@/components/ui/switch'
import SearchableSelect from '@/components/ui/SearchableSelect.vue'
import VInputField from '@/components/VInputField.vue'
import MasterLayout from '@/layouts/MasterLayout.vue'
import { Head, useForm, Link } from '@inertiajs/vue3'
import { ArrowLeft } from 'lucide-vue-next'
import { computed } from 'vue'

const route = (name: string, params?: any) => {
    return (window as any).route(name, params)
}

const props = defineProps<{
    shop?: {
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
    }
    zones: Array<{ id: number; name: string; address: string }>
    initialShopOwner?: { value: number; label: string } | null
    initialShopOwners?: Array<{ value: number; label: string }>
}>()

console.log('Zones:', props.zones)

const form = useForm({
    name: props.shop?.name || '',
    shop_owner_id: props.shop?.shop_owner_id?.toString() || '',
    commission_rate: props.shop?.commission_rate || 0,
    zone_id: props.shop?.zone_id?.toString() || '',
    lat: props.shop?.lat || null,
    lon: props.shop?.lon || null,
    type: props.shop?.type || 'retail',
    is_commission_based: props.shop?.is_commission_based ?? true,
    status: props.shop?.status ?? true,
})

const areaOptions = computed(() =>
    props.zones.map(zone => ({
        value: zone.id.toString(),
        label: zone.address ? `${zone.name} - ${zone.address}` : zone.name
    }))
)

const shopTypes = [
    { value: 'retail', label: 'Retail' },
    { value: 'wholesale', label: 'Wholesale' },
    { value: 'distributor', label: 'Distributor' }
]

const submit = () => {
    if (props.shop) {
        form.put(route('admin.shops.update', props.shop.id))
    } else {
        form.post(route('admin.shops.store'))
    }
}
</script>

<template>
    <MasterLayout>

        <Head :title="shop ? `Edit ${shop.name}` : 'Create Shop'" />

        <!-- Header -->
        <div class="mb-8">
            <Link :href="route('admin.shops.index')">
            <Button variant="ghost" size="sm">
                <ArrowLeft class="h-4 w-4 mr-2" />
                Back to Shops
            </Button>
            </Link>
        </div>

        <!-- Form -->
        <div class="max-w-4xl mx-auto">
            <Card>
                <CardHeader>
                    <CardTitle>
                        {{ shop ? 'Edit Shop' : 'Create Shop' }}
                    </CardTitle>
                </CardHeader>

                <CardContent>
                    <form @submit.prevent="submit">
                        <div class="space-y-6">
                            <!-- Shop Name -->
                            <VInputField v-model="form.name" label="Shop Name" placeholder="Enter shop name"
                                :error-messages="form.errors.name" required />

                            <!-- Shop Owner Selection -->
                            <SearchableSelect v-model="form.shop_owner_id"
                                :search-url="route('admin.shops.search-owners')" label="Shop Owner"
                                placeholder="Search shop owner..." :error-messages="form.errors.shop_owner_id"
                                :initial-option="initialShopOwner" :initial-options="initialShopOwners" />

                            <!-- Zone Selection -->
                            <div class="space-y-2">
                                <label class="block text-sm font-medium">
                                    Zone
                                    <span class="text-red-500 ml-1">*</span>
                                </label>
                                <Select v-model="form.zone_id">
                                    <SelectTrigger :class="{ 'border-red-500': form.errors.zone_id }">
                                        <SelectValue placeholder="Choose a zone" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectGroup>
                                            <SelectItem v-for="zone in zones" :key="zone.id"
                                                :value="zone.id.toString()">
                                                {{ zone.address ? `${zone.name} - ${zone.address}` : zone.name }}
                                            </SelectItem>
                                        </SelectGroup>
                                    </SelectContent>
                                </Select>
                                <p v-if="form.errors.zone_id" class="text-sm text-red-600">
                                    {{ form.errors.zone_id }}
                                </p>
                            </div> <!-- Shop Type -->
                            <div class="space-y-2">
                                <label class="block text-sm font-medium">
                                    Shop Type
                                    <span class="text-red-500 ml-1">*</span>
                                </label>
                                <Select v-model="form.type">
                                    <SelectTrigger :class="{ 'border-red-500': form.errors.type }">
                                        <SelectValue placeholder="Select shop type" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectGroup>
                                            <SelectItem v-for="type in shopTypes" :key="type.value" :value="type.value">
                                                {{ type.label }}
                                            </SelectItem>
                                        </SelectGroup>
                                    </SelectContent>
                                </Select>
                                <p v-if="form.errors.type" class="text-sm text-red-600">
                                    {{ form.errors.type }}
                                </p>
                            </div>

                            <!-- Commission Settings -->
                            <div class="space-y-4 p-4 border rounded-lg bg-muted/50">
                                <div class="flex items-center justify-between">
                                    <div class="space-y-0.5">
                                        <label class="text-sm font-medium">Commission Based</label>
                                        <p class="text-sm text-muted-foreground">
                                            Enable commission for this shop
                                        </p>
                                    </div>
                                    <Switch :checked="form.is_commission_based"
                                        @update:checked="form.is_commission_based = $event" />
                                </div>

                                <VInputField v-if="form.is_commission_based" v-model="form.commission_rate"
                                    label="Commission Rate (%)" type="number" step="0.01" min="0" max="100"
                                    placeholder="0.00" :error-messages="form.errors.commission_rate" />
                            </div>

                            <!-- Location Coordinates -->
                            <div class="grid grid-cols-2 gap-4">
                                <VInputField v-model="form.lat" label="Latitude" type="number" step="0.0000001"
                                    placeholder="23.8103" :error-messages="form.errors.lat" />

                                <VInputField v-model="form.lon" label="Longitude" type="number" step="0.0000001"
                                    placeholder="90.4125" :error-messages="form.errors.lon" />
                            </div>

                            <!-- Status -->
                            <div class="flex items-center justify-between p-4 border rounded-lg">
                                <div class="space-y-0.5">
                                    <label class="text-sm font-medium">Shop Status</label>
                                    <p class="text-sm text-muted-foreground">
                                        Set shop as active or inactive
                                    </p>
                                </div>
                                <Switch :checked="form.status" @update:checked="form.status = $event" />
                            </div>

                            <!-- Form Actions -->
                            <div class="flex justify-end space-x-3 pt-6 border-t">
                                <Link :href="route('admin.shops.index')">
                                <Button type="button" variant="outline">
                                    Cancel
                                </Button>
                                </Link>
                                <Button type="submit" :disabled="form.processing">
                                    {{ form.processing
                                        ? (shop ? 'Updating...' : 'Creating...')
                                        : (shop ? 'Update Shop' : 'Create Shop')
                                    }}
                                </Button>
                            </div>
                        </div>
                    </form>
                </CardContent>
            </Card>
        </div>
    </MasterLayout>
</template>

<script setup lang="ts">
import { ref, onMounted, watch, nextTick } from 'vue'
import L from 'leaflet'
import 'leaflet/dist/leaflet.css'
import { Input } from '@/components/ui/input'
import { Button } from '@/components/ui/button'
import { Search, MapPin } from 'lucide-vue-next'

interface Props {
    lat?: number | null
    lon?: number | null
    zoneName?: string
}

const props = defineProps<Props>()

const emit = defineEmits<{
    'update:lat': [value: number]
    'update:lon': [value: number]
}>()

const mapContainer = ref<HTMLElement | null>(null)
const searchQuery = ref('')
const searching = ref(false)
let map: L.Map | null = null
let marker: L.Marker | null = null

// Initialize map
const initMap = () => {
    if (!mapContainer.value || map) return

    // Default center (Dhaka, Bangladesh)
    const defaultLat = props.lat || 23.8103
    const defaultLon = props.lon || 90.4125

    map = L.map(mapContainer.value).setView([defaultLat, defaultLon], 13)

    // Add OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
        maxZoom: 19,
    }).addTo(map)

    // Fix for default marker icon
    delete (L.Icon.Default.prototype as any)._getIconUrl
    L.Icon.Default.mergeOptions({
        iconRetinaUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-icon-2x.png',
        iconUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-icon.png',
        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-shadow.png',
    })

    // Add marker if coordinates exist
    if (props.lat && props.lon) {
        addMarker(props.lat, props.lon)
    }

    // Click on map to set location
    map.on('click', (e: L.LeafletMouseEvent) => {
        const { lat, lng } = e.latlng
        addMarker(lat, lng)
        emit('update:lat', lat)
        emit('update:lon', lng)
    })
}

// Add or update marker
const addMarker = (lat: number, lon: number) => {
    if (!map) return

    if (marker) {
        marker.setLatLng([lat, lon])
    } else {
        marker = L.marker([lat, lon], { draggable: true }).addTo(map)

        // Update coordinates when marker is dragged
        marker.on('dragend', () => {
            if (marker) {
                const position = marker.getLatLng()
                emit('update:lat', position.lat)
                emit('update:lon', position.lng)
            }
        })
    }

    map.setView([lat, lon], 15)
}

// Search location using Nominatim (OpenStreetMap geocoding)
const searchLocation = async () => {
    if (!searchQuery.value.trim()) return

    searching.value = true
    try {
        const response = await fetch(
            `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(searchQuery.value)}&limit=1`
        )
        const data = await response.json()

        if (data && data.length > 0) {
            const { lat, lon } = data[0]
            const latitude = parseFloat(lat)
            const longitude = parseFloat(lon)

            addMarker(latitude, longitude)
            emit('update:lat', latitude)
            emit('update:lon', longitude)
        }
    } catch (error) {
        console.error('Error searching location:', error)
    } finally {
        searching.value = false
    }
}

// Watch for zone name changes to auto-search
watch(() => props.zoneName, async (newZone) => {
    if (newZone && map) {
        searchQuery.value = newZone
        await nextTick()
        searchLocation()
    }
}, { immediate: false })

// Watch for coordinate changes from parent
watch(() => [props.lat, props.lon], ([newLat, newLon]) => {
    if (newLat && newLon && map) {
        addMarker(newLat, newLon)
    }
})

onMounted(() => {
    initMap()
})
</script>

<template>
    <div class="space-y-4">
        <div class="space-y-2">
            <label class="block text-sm font-medium">Location</label>
            <div class="flex gap-2">
                <Input v-model="searchQuery" placeholder="Search location or address..."
                    @keydown.enter.prevent="searchLocation" />
                <Button type="button" @click="searchLocation" :disabled="searching" variant="outline">
                    <Search class="h-4 w-4" />
                </Button>
            </div>
            <p class="text-xs text-muted-foreground">
                <MapPin class="inline h-3 w-3 mr-1" />
                Click on the map or drag the marker to set location
            </p>
        </div>

        <div ref="mapContainer" class="w-full h-[400px] rounded-lg border overflow-hidden"></div>
    </div>
</template>

<style scoped>
:deep(.leaflet-container) {
    font-family: inherit;
}
</style>

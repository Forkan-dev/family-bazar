<script setup lang="ts">
import DataTable from '@/components/ui/DataTable.vue'
import MasterLayout from '@/layouts/MasterLayout.vue'
import { Head, router } from '@inertiajs/vue3'
import { Edit, Trash2, Eye } from 'lucide-vue-next'
import { Badge } from '@/components/ui/badge'

const route = (name: string, params?: any) => {
    return (window as any).route(name, params)
}

const { shops } = defineProps<{
    shops: {
        data: any[]
        current_page: number
        per_page: number
        total: number
        last_page: number
        from: number
        to: number
    }
}>()

const columns = [
    {
        key: 'name',
        label: 'Shop Name',
        sortable: true,
        searchable: true,
        class: 'font-medium'
    },
    {
        key: 'shop_owner',
        label: 'Owner',
        render: (item: any) => item.shop_owner?.name || 'N/A'
    },
    {
        key: 'zone',
        label: 'Zone',
        render: (item: any) => item.zone?.name || 'N/A'
    },
    {
        key: 'type',
        label: 'Type',
        sortable: true,
        render: (item: any) => {
            const typeColors: Record<string, string> = {
                retail: 'bg-blue-100 text-blue-800',
                wholesale: 'bg-green-100 text-green-800',
                distributor: 'bg-purple-100 text-purple-800'
            }
            return `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${typeColors[item.type] || 'bg-gray-100 text-gray-800'}">${item.type}</span>`
        }
    },
    {
        key: 'commission_rate',
        label: 'Commission',
        sortable: true,
        render: (item: any) => item.is_commission_based
            ? `${parseFloat(item.commission_rate || 0).toFixed(2)}%`
            : 'N/A'
    },
    {
        key: 'status',
        label: 'Status',
        sortable: true,
        render: (item: any) => item.status
            ? '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Active</span>'
            : '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Inactive</span>'
    }
]

const actions = [
    {
        label: 'View',
        icon: Eye,
        variant: 'ghost' as const,
        action: (item: any) => {
            router.visit(route('admin.shops.show', item.id))
        }
    },
    {
        label: 'Edit',
        icon: Edit,
        variant: 'ghost' as const,
        action: (item: any) => {
            router.visit(route('admin.shops.edit', item.id))
        }
    },
    {
        label: 'Delete',
        icon: Trash2,
        variant: 'destructive' as const,
        action: (item: any) => {
            if (confirm('Are you sure you want to delete this shop?')) {
                router.delete(route('admin.shops.destroy', item.id))
            }
        }
    }
]

const handleSearch = (query: string) => {
    router.get(route('admin.shops.index'), { search: query }, {
        preserveState: true,
        preserveScroll: true
    })
}

const handlePagination = (page: number) => {
    router.get(route('admin.shops.index'), { page }, {
        preserveState: true,
        preserveScroll: true
    })
}

const handleSort = (column: string, direction: 'asc' | 'desc') => {
    router.get(route('admin.shops.index'), {
        sort: column,
        direction
    }, {
        preserveState: true,
        preserveScroll: true
    })
}
</script>

<template>
    <MasterLayout>

        <Head title="Shops" />

        <div>
            <DataTable title="Shops" description="Manage shop listings and configurations" :columns="columns"
                :data="shops.data" :actions="actions" :create-url="route('admin.shops.create')" create-text="Add Shop"
                :current-page="shops.current_page" :per-page="shops.per_page" :total="shops.total"
                @search="handleSearch" @paginate="handlePagination" @sort="handleSort" />
        </div>
    </MasterLayout>
</template>

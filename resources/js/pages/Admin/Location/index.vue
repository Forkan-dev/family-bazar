<script setup lang="ts">
import DataTable from '@/components/ui/DataTable.vue'
import MasterLayout from '@/layouts/MasterLayout.vue'
import { Head, router } from '@inertiajs/vue3'
import { Edit, Trash2 } from 'lucide-vue-next'

const route = (name: string, params?: any) => {
    return (window as any).route(name, params)
}

const { unions } = defineProps<{
    unions: {
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
        key: 'name_en',
        label: 'Union Name',
        sortable: true,
        searchable: true,
        class: 'font-medium'
    },
    {
        key: 'upazila',
        label: 'Upazila',
        render: (item: any) => item.upazila?.name_en || 'N/A'
    },
    {
        key: 'district',
        label: 'District',
        render: (item: any) => item.upazila?.district?.name_en || 'N/A'
    },
    {
        key: 'division',
        label: 'Division',
        render: (item: any) => item.upazila?.district?.division?.name_en || 'N/A'
    }
]

const actions = [
    {
        label: 'Edit',
        icon: Edit,
        variant: 'ghost' as const,
        action: (item: any) => {
            router.visit(route('product.locations.edit', item.id))
        }
    },
    {
        label: 'Delete',
        icon: Trash2,
        variant: 'destructive' as const,
        action: (item: any) => {
            if (confirm('Are you sure you want to delete this union?')) {
                router.delete(route('product.locations.destroy', item.id))
            }
        }
    }
]

const handleSearch = (query: string) => {
    router.get(route('product.locations.index'), { search: query }, {
        preserveState: true,
        preserveScroll: true
    })
}

const handlePagination = (page: number) => {
    router.get(route('product.locations.index'), { page }, {
        preserveState: true,
        preserveScroll: true
    })
}

const handleSort = (column: string, direction: 'asc' | 'desc') => {
    router.get(route('product.locations.index'), {
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

        <Head title="Locations" />

        <div>
            <DataTable title="Locations" description="Manage unions and their administrative divisions"
                :columns="columns" :data="unions.data" :actions="actions"
                :create-url="route('product.locations.create')" create-text="Add Union"
                :current-page="unions.current_page" :per-page="unions.per_page" :total="unions.total"
                @search="handleSearch" @paginate="handlePagination" @sort="handleSort" />
        </div>
    </MasterLayout>
</template>

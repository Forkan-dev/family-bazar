<script setup lang="ts">
import DataTable from '@/components/ui/DataTable.vue'
import MasterLayout from '@/layouts/MasterLayout.vue'
import { Head, router } from '@inertiajs/vue3'
import { Edit, Trash2, Eye } from 'lucide-vue-next'

const route = (name: string, params?: any) => {
    return (window as any).route(name, params)
}

const { products } = defineProps<{
    products: {
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
        label: 'Product Name',
        sortable: true,
        searchable: true,
        class: 'font-medium'
    },
    {
        key: 'price',
        label: 'Price',
        sortable: true,
        render: (item: any) => `৳${parseFloat(item.price).toFixed(2)}`
    },
    {
        key: 'stock_quantity',
        label: 'Stock',
        sortable: true,
        class: 'text-center'
    },
    {
        key: 'category',
        label: 'Category',
        render: (item: any) => item.category?.title_en || 'N/A'
    },
    {
        key: 'tags',
        label: 'Tags',
        render: (item: any) => item.tags?.map((tag: any) => tag.name).join(', ') || 'No tags'
    }
]

const actions = [
    {
        label: 'View',
        icon: Eye,
        variant: 'ghost' as const,
        action: (item: any) => {
            router.visit(route('product.products.show', item.id))
        }
    },
    {
        label: 'Edit',
        icon: Edit,
        variant: 'ghost' as const,
        action: (item: any) => {
            router.visit(route('product.products.edit', item.id))
        }
    },
    {
        label: 'Delete',
        icon: Trash2,
        variant: 'destructive' as const,
        action: (item: any) => {
            if (confirm('Are you sure you want to delete this product?')) {
                router.delete(route('product.products.destroy', item.id))
            }
        }
    }
]

const handleSearch = (query: string) => {
    router.get(route('product.products.index'), { search: query }, {
        preserveState: true,
        preserveScroll: true
    })
}

const handlePagination = (page: number) => {
    router.get(route('product.products.index'), { page }, {
        preserveState: true,
        preserveScroll: true
    })
}

const handleSort = (column: string, direction: 'asc' | 'desc') => {
    router.get(route('product.products.index'), {
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

        <Head title="Products" />

        <div class="p-8">
            <DataTable title="Products" description="Manage your product inventory" :columns="columns"
                :data="products.data" :actions="actions" :create-url="route('product.products.create')"
                create-text="Add Product" :current-page="products.current_page" :per-page="products.per_page"
                :total="products.total" @search="handleSearch" @paginate="handlePagination" @sort="handleSort" />
        </div>
    </MasterLayout>
</template>

<script setup lang="ts">
import DataTable from '@/components/ui/DataTable.vue'
import MasterLayout from '@/layouts/MasterLayout.vue'
import { Head, router } from '@inertiajs/vue3'
import { Eye, Edit, Trash2 } from 'lucide-vue-next'

const route = (name: string, params?: any) => {
    return (window as any).route(name, params)
}

const props = defineProps({
    brands: Array,
})

const columns = [
    {
        key: 'image',
        label: 'Logo',
        render: (item) => item.image ? `<img src="${item.image}" alt="Brand Logo" class="w-12 h-12 object-cover rounded">` : 'No logo'
    },
    {
        key: 'en_name',
        label: 'Brand Name',
        sortable: true,
        searchable: true
    },
    {
        key: 'bn_name',
        label: 'Bengali Name',
        sortable: true,
        searchable: true
    },
    {
        key: 'slug',
        label: 'Slug',
        sortable: true,
        render: (item) => `<code class="text-xs bg-gray-100 px-2 py-1 rounded">${item.slug}</code>`
    },
    {
        key: 'created_at',
        label: 'Created',
        sortable: true,
        render: (item) => new Date(item.created_at).toLocaleDateString()
    }
]

const actions = [
    {
        label: 'View',
        icon: Eye,
        variant: 'ghost' as const,
        action: (item) => router.visit(route('product.brands.show', item.id))
    },
    {
        label: 'Edit',
        icon: Edit,
        variant: 'ghost' as const,
        action: (item) => router.visit(route('product.brands.edit', item.id))
    },
    {
        label: 'Delete',
        icon: Trash2,
        variant: 'ghost' as const,
        class: 'text-red-600 hover:text-red-700 hover:bg-red-50',
        action: (item) => deleteBrand(item.id)
    }
]

const deleteBrand = (id: number) => {
    if (confirm('Are you sure you want to delete this brand?')) {
        router.delete(route('product.brands.destroy', id), {
            onSuccess: () => {
                // Handle success
            },
            onError: (errors) => console.error(errors),
        });
    }
}
</script>

<template>
    <MasterLayout>

        <Head title="Brands" />

        <div>
            <DataTable title="Brands" description="Manage product brands and manufacturers" :columns="columns"
                :data="brands || []" :actions="actions" :create-url="route('product.brands.create')"
                create-text="Add Brand" searchable :total="brands?.length || 0" />
        </div>
    </MasterLayout>
</template>

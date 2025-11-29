<script setup lang="ts">
import DataTable from '@/components/ui/DataTable.vue'
import MasterLayout from '@/layouts/MasterLayout.vue'
import { Head, router } from '@inertiajs/vue3'
import { Eye, Edit, Trash2 } from 'lucide-vue-next'

const route = (name: string, params?: any) => {
    return (window as any).route(name, params)
}

defineProps<{
    categories: any[]
}>()

const columns = [
    {
        key: 'image',
        label: 'Image',
        render: (item: any) => item.image ? `<img src="${item.image}" alt="Category" class="w-12 h-12 object-cover rounded">` : 'No image'
    },
    {
        key: 'title_en',
        label: 'Category Name',
        sortable: true,
        searchable: true
    },
    {
        key: 'title_bn',
        label: 'Bengali Name',
        sortable: true,
        searchable: true
    },
    {
        key: 'slug',
        label: 'Slug',
        sortable: true,
        render: (item: any) => `<code class="text-xs bg-gray-100 px-2 py-1 rounded">${item.slug}</code>`
    },
    {
        key: 'parent_id',
        label: 'Parent',
        render: (item: any) => item.parent ? item.parent.title_en : 'Root Category'
    },
    {
        key: 'created_at',
        label: 'Created',
        sortable: true,
        render: (item: any) => new Date(item.created_at).toLocaleDateString()
    }
]

const actions = [
    {
        label: 'View',
        icon: Eye,
        variant: 'ghost' as const,
        action: (item: any) => router.visit(route('product.categories.show', item.id))
    },
    {
        label: 'Edit',
        icon: Edit,
        variant: 'ghost' as const,
        action: (item: any) => router.visit(route('product.categories.edit', item.id))
    },
    {
        label: 'Delete',
        icon: Trash2,
        variant: 'ghost' as const,
        class: 'text-red-600 hover:text-red-700 hover:bg-red-50',
        action: (item: any) => deleteCategory(item.id)
    }
]

const deleteCategory = (id: number) => {
    if (confirm('Are you sure you want to delete this category?')) {
        router.delete(route('product.categories.destroy', id), {
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

        <Head title="Categories" />

        <div class="p-6">
            <DataTable title="Categories" description="Manage product categories and subcategories" :columns="columns"
                :data="categories || []" :actions="actions" :create-url="route('product.categories.create')"
                create-text="Add Category" searchable :total="categories?.length || 0" />
        </div>
    </MasterLayout>
</template>

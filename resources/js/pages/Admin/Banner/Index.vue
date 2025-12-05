<script setup lang="ts">
import DataTable from '@/components/ui/DataTable.vue'
import MasterLayout from '@/layouts/MasterLayout.vue'
import { Head, router } from '@inertiajs/vue3'
import { Eye, Edit, Trash2 } from 'lucide-vue-next'
import { computed } from 'vue'

const route = (name: string, params?: any) => {
    return (window as any).route(name, params)
}

const props = defineProps({
    banners: Array,
})

const processedBanners = computed(() => {
    return props.banners?.map(banner => {
        const parseJson = (jsonString) => {
            try {
                return typeof jsonString === 'string' ? JSON.parse(jsonString) : jsonString;
            } catch {
                return {};
            }
        };

        const title = parseJson(banner.title);
        const subTitle = parseJson(banner.sub_title);

        return {
            ...banner,
            title_en: title?.en || '',
            title_bn: title?.bn || '',
            sub_title_en: subTitle?.en || '',
            sub_title_bn: subTitle?.bn || '',
        };
    }) || [];
});

const columns = [
    {
        key: 'title_en',
        label: 'Title (English)',
        sortable: true,
        searchable: true
    },
    {
        key: 'title_bn',
        label: 'Title (Bengali)',
        sortable: true,
        searchable: true
    },
    {
        key: 'sub_title_en',
        label: 'Sub Title',
        sortable: true
    },
    {
        key: 'image',
        label: 'Image',
        render: (item) => item.image ? `<img src="${item.image}" alt="Banner" class="w-16 h-10 object-cover rounded">` : 'No image'
    },
    {
        key: 'type.name_en',
        label: 'Type',
        sortable: true,
        render: (item) => item.type?.name_en || 'N/A'
    },
    {
        key: 'status',
        label: 'Status',
        sortable: true,
        render: (item) => `<span class="px-2 py-1 text-xs rounded-full ${item.status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">${item.status}</span>`
    }
]

const actions = [
    {
        label: 'View',
        icon: Eye,
        variant: 'ghost' as const,
        action: (item) => router.visit(route('admin.banners.show', item.id))
    },
    {
        label: 'Edit',
        icon: Edit,
        variant: 'ghost' as const,
        action: (item) => router.visit(route('admin.banners.edit', item.id))
    },
    {
        label: 'Delete',
        icon: Trash2,
        variant: 'ghost' as const,
        class: 'text-red-600 hover:text-red-700 hover:bg-red-50',
        action: (item) => deleteBanner(item.id)
    }
]

const deleteBanner = (id: number) => {
    if (confirm('Are you sure you want to delete this banner?')) {
        router.delete(route('admin.banners.destroy', id), {
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

        <Head title="Banners" />

        <div>
            <DataTable title="Banners" description="Manage website banners and promotional content" :columns="columns"
                :data="processedBanners" :actions="actions" :create-url="route('admin.banners.create')"
                create-text="Add Banner" searchable :total="processedBanners.length" />
        </div>
    </MasterLayout>
</template>

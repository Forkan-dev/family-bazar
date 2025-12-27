<script setup lang="ts">
import DataTable from '@/components/ui/DataTable.vue'
import MasterLayout from '@/layouts/MasterLayout.vue'
import { Head, router } from '@inertiajs/vue3'
import { Eye, Edit, Trash2 } from 'lucide-vue-next'

const route = (name: string, params?: any) => {
    return (window as any).route(name, params)
}

const props = defineProps({
    offers: Array,
})

const columns = [
    {
        key: 'image',
        label: 'Image',
        render: (item) => item.image ? `<img src="${item.image}" alt="offer image" class="w-12 h-12 object-cover rounded">` : 'N/A'
    },
    {
        key: 'name',
        label: 'Offer Name',
        sortable: true,
        searchable: true
    },
    {
        key: 'name_bn',
        label: 'Offer Name BN',
        sortable: true,
        searchable: true
    },
      {
        key: 'discount_type',
        label: 'Discount Type',
        sortable: true,
        searchable: true
    },
    {
       
        label: ' Type',
        sortable: true,
        searchable: true,
        render: (item : any) => 
        {
            if (item.offer_targets && item.offer_targets.length > 0) {
                const targetTypes = item.offer_targets.some((target:any) =>  (target.target_type === 'product') );
               return targetTypes ? 'Product' : 'Category'; 
            }
            return 'N/A';
        }

    },

    {
        key: 'start_date',
        label: 'Start Date',
        sortable: true,
        render: (item:any) => new Date(item.start_at).toLocaleDateString()
    },
    {
        key: 'end_date',
        label: 'End Date',
        sortable: true,
        render: (item:any) => new Date(item.end_at).toLocaleDateString()
    }
]

const actions = [
    {
        label: 'Delete',
        icon: Trash2,
        variant: 'ghost' as const,
        class: 'text-red-600 hover:text-red-700 hover:bg-red-50',
        action: (item) => deleteOffer(item.id)
    }
]

const deleteOffer = (id: number) => {
    if (confirm('Are you sure you want to delete this Offer?')) {
        router.delete(route('admin.offers.destroy', id), {
            onSuccess: () => {
                // Handle success
            },
            onError: (errors) => console.error(errors),
        });
    }
}


const handleRedirectUrl = (id: number) => {
    router.visit(route('admin.offers.edit', id));
}



</script>

<template>
    <MasterLayout>

        <Head title="Offers" />

        <div>
            <DataTable title="Offers" description="Manage offers" :columns="columns"
                :data="offers?.data || []" :actions="actions" :create-url="route('admin.offers.create')"
                create-text="Add Offer" searchable :total="offers?.data?.length || 0"  @redirectUrl="handleRedirectUrl"/>
        </div>
    </MasterLayout>
</template>

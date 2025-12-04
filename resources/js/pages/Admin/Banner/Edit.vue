<script setup lang="ts">
import BannerForm from '@/components/forms/BannerForm.vue'
import MasterLayout from '@/layouts/MasterLayout.vue'
import { Head, router } from '@inertiajs/vue3'

const props = defineProps<{
    banner: {
        id: number
        title: string | { en?: string; bn?: string }
        sub_title: string | { en?: string; bn?: string }
        description?: string | { en?: string; bn?: string }
        image?: string
        type_id: number
        position?: number
        status: 'active' | 'inactive'
        button_text_1?: string
        button_url_1?: string
        button_text_2?: string
        button_url_2?: string
    }
    types: Array<{ id: number; name: string }>
}>()

const route = (name: string, params?: any) => {
    return (window as any).route(name, params)
}

const handleSubmit = (form: any) => {
    form.post(route('admin.banners.update', props.banner.id))
}

const handleCancel = () => {
    router.visit(route('admin.banners.index'))
}
</script>

<template>
    <MasterLayout>

        <Head title="Edit Banner" />

        <BannerForm :banner="banner" :types="types" action="edit" @submit="handleSubmit" @cancel="handleCancel" />
    </MasterLayout>
</template>

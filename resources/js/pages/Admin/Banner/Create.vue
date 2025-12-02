<script setup lang="ts">
import BannerForm from '@/components/forms/BannerForm.vue'
import MasterLayout from '@/layouts/MasterLayout.vue'
import { Head, router } from '@inertiajs/vue3'

const props = defineProps<{
    types: Array<{ id: number; name: string }>
}>()

const route = (name: string) => {
    return (window as any).route(name)
}

const handleSubmit = (form: any) => {
    form.post(route('admin.banners.store'))
}

const handleCancel = () => {
    router.visit(route('admin.banners.index'))
}
</script>

<template>
    <MasterLayout>

        <Head title="Create Banner" />

        <BannerForm :types="props.types" action="create" @submit="handleSubmit" @cancel="handleCancel" />
    </MasterLayout>
</template>

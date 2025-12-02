<script setup lang="ts">
import { FormContainer } from '@/components/ui/form-container'
import { FormSelect } from '@/components/ui/form-select'
import VInputField from '@/components/VInputField.vue'
import MasterLayout from '@/layouts/MasterLayout.vue'
import { Head, useForm } from '@inertiajs/vue3'
import { MapPin } from 'lucide-vue-next'
import { computed } from 'vue'

const props = defineProps<{
    upazilas: Array<{ id: number; name_en: string; name_bn: string }>;
}>();

const form = useForm({
    upazila_id: null as number | null,
    name_en: '',
    name_bn: '',
});

const upazilaOptions = computed(() =>
    props.upazilas.map(upazila => ({
        value: upazila.id,
        label: `${upazila.name_en} (${upazila.name_bn})`
    }))
)

const submit = () => {
    form.post(route('locations.store'));
};
</script>

<template>
    <MasterLayout>

        <Head title="Add Union" />

        <FormContainer title="Create Union" :back-url="route('locations.index')" back-text="Back to Locations"
            :loading="form.processing" submit-text="Create Union" show-cancel :grid-cols="2" max-width="3xl"
            @submit="submit" @cancel="$inertia.visit(route('locations.index'))">
            <FormSelect v-model="form.upazila_id" label="Select Upazila" placeholder="Choose an Upazila"
                :options="upazilaOptions" :error-messages="form.errors.upazila_id" required />

            <VInputField v-model="form.name_en" label="Union Name (English)" placeholder="Enter union name in English"
                :error-messages="form.errors.name_en" required />

            <div class="md:col-span-2">
                <VInputField v-model="form.name_bn" label="Union Name (Bengali)"
                    placeholder="ইউনিয়নের নাম বাংলায় লিখুন" :error-messages="form.errors.name_bn" />
            </div>
        </FormContainer>
    </MasterLayout>
</template>

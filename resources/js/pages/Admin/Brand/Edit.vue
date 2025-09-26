
<script setup lang="ts">
import MasterLayout from '@/layouts/MasterLayout.vue';
import { computed, watch } from 'vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import VInputField from '@/components/VInputField.vue';
import { VButton } from '@/components/ui/button';
import VFileInput from '@/components/ui/VFileInput.vue';

const props = defineProps<{
    brand: {
        id: number;
        en_name: string;
        bn_name: string;
        slug: string;
        image?: string;
        image_url?: string;
    };
}>();

const form = useForm({
    _method: 'put',
    en_name: props.brand.en_name,
    bn_name: props.brand.bn_name,
    slug: props.brand.slug,
    image: null as File | null,
});

const submit = () => {
    form.post(route('product.brands.update', props.brand.id));
};

</script>

<template>
    <MasterLayout>
        <Head title="Edit Brand" />
        <v-container>
            <v-row>
                <v-col cols="12">
                    <v-card>
                        <v-card-title class="d-flex align-center justify-space-between">
                            Edit Brand
                            <Link :href="route('product.brands.index')">
                                <VButton variant="outlined">
                                    <v-icon left class="mr-2">mdi-arrow-left</v-icon>
                                    Back to Brands
                                </VButton>
                            </Link>
                        </v-card-title>
                        <v-divider></v-divider>
                        <v-card-text>
                            <v-form @submit.prevent="submit">
                                <v-row>
                                    <v-col cols="12" md="6">
                                        <VInputField
                                            v-model="form.en_name"
                                            label="Name (English)"
                                            :error-messages="form.errors.en_name"
                                            required
                                            density="compact"
                                            variant="outlined"
                                        />
                                    </v-col>
                                    <v-col cols="12" md="6">
                                        <VInputField
                                            v-model="form.bn_name"
                                            label="Name (Bengali)"
                                            :error-messages="form.errors.bn_name"
                                            density="compact"
                                            variant="outlined"
                                        />
                                    </v-col>
                                    <v-col cols="12">
                                        <VInputField
                                            v-model="form.slug"
                                            label="URL Slug"
                                            :error-messages="form.errors.slug"
                                            required
                                            density="compact"
                                            variant="outlined"
                                        />
                                    </v-col>
                                    <v-col cols="12" md="6">
                                        <VFileInput
                                            v-model="form.image"
                                            title="Upload New Image"
                                            variant="outlined"
                                            density="compact"
                                            :error-messages="form.errors.image"
                                            accept="image/*"
                                            prepend-icon=""
                                            prepend-inner-icon="mdi-camera"
                                        />
                                    </v-col>
                                    <v-col cols="12" md="6" v-if="props.brand.image_url">
                                        <div class="text-caption mb-2">Current Image</div>
                                        <v-img
                                            :src="props.brand.image_url"
                                            height="100"
                                            max-width="150"
                                            class="rounded"
                                        />
                                    </v-col>
                                </v-row>

                                <div class="d-flex gap-3 mt-8">
                                    <VButton
                                        type="submit"
                                        :disabled="form.processing"
                                        :loading="form.processing"
                                        size="large"
                                    >
                                        <v-icon left>mdi-content-save</v-icon>
                                        Update Brand
                                    </VButton>

                                    <Link :href="route('product.brands.index')">
                                        <VButton
                                            variant="tonal"
                                            size="large"
                                        >
                                            <v-icon left>mdi-close</v-icon>
                                            Cancel
                                        </VButton>
                                    </Link>
                                </div>
                            </v-form>
                        </v-card-text>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
    </MasterLayout>
</template>

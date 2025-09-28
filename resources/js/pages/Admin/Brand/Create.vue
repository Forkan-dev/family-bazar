
<script setup lang="ts">
import MasterLayout from '@/layouts/MasterLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import VInputField from '@/components/VInputField.vue';
import { VButton } from '@/components/ui/button';
import VFileInput from '@/components/ui/VFileInput.vue';
import { watch } from 'vue';

const form = useForm({
    en_name: '',
    bn_name: '',
    slug: '',
    image: null as File | null,
});

const submit = () => {
    form.post(route('product.brands.store'));
};

// Watch for changes in name to auto-generate slug
watch(() => form.en_name, (newName) => {
    form.slug = newName.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '');
});

</script>

<template>
    <MasterLayout>
        <Head title="Add Brand" />
        <v-container>
            <v-row>
                <v-col cols="12">
                    <v-card>
                        <v-card-title class="d-flex align-center justify-space-between">
                            Create Brand
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
                                            hint="Auto-generated from English name"
                                        />
                                    </v-col>
                                    <v-col cols="12">
                                        <VFileInput
                                            v-model="form.image"
                                            title="Upload Brand Image"
                                            variant="outlined"
                                            density="compact"
                                            :error-messages="form.errors.image"
                                            accept="image/*"
                                            prepend-icon=""
                                            prepend-inner-icon="mdi-camera"
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
                                        <v-icon left>mdi-plus</v-icon>
                                        Create Brand
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


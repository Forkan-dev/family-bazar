<script setup lang="ts">
import MasterLayout from '@/layouts/MasterLayout.vue';
import { computed, watch } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import { VTextField } from 'vuetify/components';

const props = defineProps<{
    brand: {
        id: number;
        en_name: string;
        bn_name: string;
        slug: string;
        image?: string;
    };
}>();

const form = useForm({
    _method: 'put',
    en_name: props.brand.en_name,
    bn_name: props.brand.bn_name,
    slug: props.brand.slug,
    image: null, // Initialize as null for file input
});

const isEdit = computed(() => !!props.brand?.id);

const submit = () => {
    form.post(route('brands.update', props.brand.id));
};

watch(
    () => form.image,
    () => {
        if (form.errors.image) {
            form.clearErrors('image');
        }
    },
);
</script>

<template>
    <MasterLayout>
        <Head title="Edit Brand" />

        <v-container>
            <v-row>
                <v-col cols="12">
                    <v-card>
                        <v-card-title>Edit Brand</v-card-title>

                        <v-card-text class="mt-5">
                            <v-form @submit.prevent="submit">
                                <!-- ✅ Basic Information -->
                                <div class="form-section mb-6">
                                    <div
                                        class="form-section-title d-flex align-center mb-3"
                                    >
                                        <v-icon class="me-2" color="primary"
                                            >mdi-information</v-icon
                                        >
                                        <span class="text-h6 font-weight-medium"
                                            >Basic Information</span
                                        >
                                    </div>
                                    <v-row dense>
                                        <v-col cols="12" md="6">
                                            <v-text-field
                                                v-model="form.en_name"
                                                placeholder="Name (English)"
                                                :error-messages="
                                                    form.errors.en_name
                                                "
                                                required
                                                density="compact"
                                                variant="outlined"
                                                hide-details="auto"
                                            />
                                        </v-col>
                                        <v-col cols="12" md="6">
                                            <v-text-field
                                                v-model="form.bn_name"
                                                placeholder="Name (Bangla)"
                                                :error-messages="
                                                    form.errors.bn_name
                                                "
                                                required
                                                density="compact"
                                                variant="outlined"
                                                hide-details="auto"
                                            />
                                        </v-col>
                                    </v-row>
                                </div>

                                <!-- ✅ SEO -->
                                <div class="form-section mb-6">
                                    <div
                                        class="form-section-title d-flex align-center mb-3"
                                    >
                                        <v-icon class="me-2" color="green"
                                            >mdi-tag</v-icon
                                        >
                                        <span class="text-h6 font-weight-medium"
                                            >SEO Settings</span
                                        >
                                    </div>
                                    <v-row dense>
                                        <v-col cols="12" md="6">
                                            <v-text-field
                                                v-model="form.slug"
                                                placeholder="Slug"
                                                :error-messages="
                                                    form.errors.slug
                                                "
                                                required
                                                density="compact"
                                                variant="outlined"
                                                hide-details="auto"
                                            />
                                        </v-col>
                                    </v-row>
                                </div>

                                <!-- ✅ Media -->
                                <div class="form-section mb-6">
                                    <div
                                        class="form-section-title d-flex align-center mb-3"
                                    >
                                        <v-icon class="me-2" color="orange"
                                            >mdi-image</v-icon
                                        >
                                        <span class="text-h6 font-weight-medium"
                                            >Media</span
                                        >
                                    </div>
                                    <v-row dense>
                                        <v-col cols="12" md="6">
                                           <!-- Existing Image Display -->
                                           <div v-if="props.brand.image && !form.image" class="mb-4">
                                               <img :src="`/${props.brand.image}`" alt="Brand Image" class="w-24 h-24 object-cover rounded-lg" />
                                               <p class="text-sm text-gray-500 mt-1">Current Image</p>
                                           </div>

                                            <!-- Image upload field -->
                                            <v-file-input
                                                @change="form.image = $event.target.files[0]"
                                                label="Upload New Image"
                                                placeholder="Choose file"
                                                :error-messages="
                                                    form.errors.image
                                                "
                                                density="compact"
                                                variant="outlined"
                                                hide-details="auto"
                                                accept="image/*"
                                                show-size
                                                clearable
                                            ></v-file-input>
                                        </v-col>
                                    </v-row>
                                </div>

                                <!-- ✅ Submit -->
                                <v-btn
                                    type="submit"
                                    color="primary"
                                    :loading="form.processing"
                                >
                                    {{ isEdit ? 'Update' : 'Save' }}
                                </v-btn>
                            </v-form>
                        </v-card-text>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
    </MasterLayout>
</template>

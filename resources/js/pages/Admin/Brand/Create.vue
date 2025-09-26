<script setup lang="ts">
import MasterLayout from '@/layouts/MasterLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { VTextField } from 'vuetify/components';

const form = useForm({
    en_name: '',
    bn_name: '',
    slug: '',
    image: null,
});

const submit = () => {
    form.post(route('brands.store'));
};
</script>

<template>
    <MasterLayout>
        <Head title="Add Brand" />

        <v-container>
            <v-row>
                <v-col cols="12">
                    <v-card-text class="mt-5">
                        <v-form @submit.prevent="submit">
                            <!-- ✅ Basic Information Section -->
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

                            <!-- ✅ SEO Section -->
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
                                            :error-messages="form.errors.slug"
                                            required
                                            density="compact"
                                            variant="outlined"
                                            hide-details="auto"
                                        />
                                    </v-col>
                                </v-row>
                            </div>

                            <!-- ✅ Media Section -->
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
                                        <v-file-input
                                            v-model="form.image"
                                            label="Brand Image"
                                            placeholder="Upload image"
                                            show-size
                                            truncate-length="15"
                                            accept="image/*"
                                            :error-messages="form.errors.image"
                                            density="compact"
                                            variant="outlined"
                                            hide-details="auto"
                                            clearable
                                        />
                                    </v-col>
                                </v-row>
                            </div>

                            <!-- ✅ Submit Button -->
                            <v-btn
                                type="submit"
                                color="primary"
                                :loading="form.processing"
                            >
                                Save
                            </v-btn>
                        </v-form>
                    </v-card-text>
                </v-col>
            </v-row>
        </v-container>
    </MasterLayout>
</template>

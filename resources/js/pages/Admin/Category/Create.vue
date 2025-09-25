<script setup lang="ts">
import MasterLayout from '@/layouts/MasterLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { VTextField } from 'vuetify/components';

const props = defineProps<{
    categories: Array<{ id: number; title_en: string; title_bn: string }>;
}>();

const form = useForm({
    title_en: '',
    title_bn: '',
    slug: '',
    description: '',
    icon: '',
    image: null,
    parent_id: null,
});

const submit = () => {
    form.post(route('categories.store'));
};
</script>

<template>
    <MasterLayout>
        <Head title="Add Category" />

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
                                            v-model="form.title_en"
                                            placeholder="Title (English)"
                                            :error-messages="
                                                form.errors.title_en
                                            "
                                            required
                                            density="compact"
                                            variant="outlined"
                                            hide-details="auto"
                                        />
                                    </v-col>

                                    <v-col cols="12" md="6">
                                        <v-text-field
                                            v-model="form.title_bn"
                                            placeholder="Title (Bangla)"
                                            :error-messages="
                                                form.errors.title_bn
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

                                    <v-col cols="12" md="6">
                                        <v-text-field
                                            v-model="form.description"
                                            placeholder="Description"
                                            :error-messages="
                                                form.errors.description
                                            "
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
                                        <v-text-field
                                            v-model="form.icon"
                                            placeholder="Icon"
                                            :error-messages="form.errors.icon"
                                            density="compact"
                                            variant="outlined"
                                            hide-details="auto"
                                        />
                                    </v-col>

                                    <v-col cols="12" md="6">
                                        <v-file-input
                                            v-model="form.image"
                                            label="Category Image"
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

                            <div class="form-section mb-6">
                                <div
                                    class="form-section-title d-flex align-center mb-3"
                                >
                                    <v-icon class="me-2" color="purple"
                                        >mdi-family-tree</v-icon
                                    >
                                    <span class="text-h6 font-weight-medium"
                                        >Parent Category</span
                                    >
                                </div>

                                <v-row dense>
                                    <v-col cols="12" md="6">
                                        <v-select
                                            v-model="form.parent_id"
                                            :items="categories"
                                            :item-title="
                                                (category) =>
                                                    `${category.title_en} (${category.title_bn})`
                                            "
                                            item-value="id"
                                            label="Select Parent Category"
                                            placeholder="Choose a Parent Category"
                                            clearable
                                            density="compact"
                                            variant="outlined"
                                            :error-messages="
                                                form.errors.parent_id
                                            "
                                            hide-details="auto"
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

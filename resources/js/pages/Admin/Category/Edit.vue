<script setup lang="ts">
import MasterLayout from '@/layouts/MasterLayout.vue';
import { computed, watch } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import { VTextField } from 'vuetify/components';

// const props = defineProps({
//     category: Object,
// });

// props: Inertia থেকে category data ও categories list
const props = defineProps<{
    category: {
        id: number;
        title_en: string;
        title_bn: string;
        slug: string;
        description?: string;
        icon?: string;
        image?: string;
        parent_id?: number | null;
    };
    categories: Array<{ id: number; title_en: string; title_bn: string }>;
}>();

const form = useForm({
    _method: 'put',
    title_en: props.category.title_en,
    title_bn: props.category.title_bn,
    slug: props.category.slug,
    description: props.category.description,
    icon: props.category.icon,
    image: null, // Initialize as null for file input
    parent_id: props.category.parent_id,
});

const isEdit = computed(() => !!props.category?.id);

const submit = () => {
    form.post(route('categories.update', props.category.id));
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
        <Head title="Edit Category" />

        <v-container>
            <v-row>
                <v-col cols="12">
                    <v-card>
                        <v-card-title>Edit Category</v-card-title>

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
                                            <v-text-field
                                                v-model="form.icon"
                                                placeholder="Icon"
                                                :error-messages="
                                                    form.errors.icon
                                                "
                                                density="compact"
                                                variant="outlined"
                                                hide-details="auto"
                                            />
                                        </v-col>
                                        <v-col cols="12" md="6">
                                           <!-- Existing Image Display -->
                                           <div v-if="props.category.image && !form.image" class="mb-4">
                                               <img :src="`/${props.category.image}`" alt="Category Image" class="w-24 h-24 object-cover rounded-lg" />
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

                                <!-- ✅ Parent -->
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
                                                :items="props.categories"
                                                :item-title="
                                                    (cat) =>
                                                        `${cat.title_en} (${cat.title_bn})`
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

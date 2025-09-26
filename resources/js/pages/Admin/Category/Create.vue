<script setup lang="ts">
import { VButton } from '@/components/ui/button';
import VFileInput from '@/components/ui/VFileInput.vue';
import VInputField from '@/components/VInputField.vue';
import MasterLayout from '@/layouts/MasterLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { watch } from 'vue';

const props = defineProps<{
    categories: Array<{ id: number; title_en: string; title_bn: string }>;
}>();

const form = useForm({
    title_en: '',
    title_bn: '',
    slug: '',
    description: '',
    icon: '',
    image: null as File | null,
    parent_id: null,
});

const submit = () => {
    form.post(route('product.categories.store'));
};

// Watch for changes in name to auto-generate slug
watch(
    () => form.title_en,
    (newName) => {
        form.slug = newName
            .toLowerCase()
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/^-|-$/g, '');
    },
);
</script>

<template>
    <MasterLayout>
        <Head title="Add Category" />
        <v-container>
            <v-row>
                <v-col cols="12">
                    <v-card>
                        <v-card-title
                            class="d-flex align-center justify-space-between"
                        >
                            Create Category
                            <Link :href="route('product.categories.index')">
                                <VButton variant="outlined">
                                    <v-icon left class="mr-2"
                                        >mdi-arrow-left</v-icon
                                    >
                                    Back to Categories
                                </VButton>
                            </Link>
                        </v-card-title>
                        <v-divider></v-divider>
                        <v-card-text>
                            <v-form @submit.prevent="submit">
                                <v-row>
                                    <v-col cols="12" md="6">
                                        <VInputField
                                            v-model="form.title_en"
                                            label="Title (English)"
                                            :error-messages="
                                                form.errors.title_en
                                            "
                                            required
                                            density="compact"
                                            variant="outlined"
                                        />
                                    </v-col>
                                    <v-col cols="12" md="6">
                                        <VInputField
                                            v-model="form.title_bn"
                                            label="Title (Bengali)"
                                            :error-messages="
                                                form.errors.title_bn
                                            "
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
                                            hint="Auto-generated from English title"
                                        />
                                    </v-col>
                                    <v-col cols="12">
                                        <VInputField
                                            v-model="form.description"
                                            label="Description"
                                            :error-messages="
                                                form.errors.description
                                            "
                                            multiline
                                            density="compact"
                                            variant="outlined"
                                            rows="3"
                                        />
                                    </v-col>
                                    <v-col cols="12" md="6">
                                        <v-select
                                            v-model="form.parent_id"
                                            :items="categories"
                                            :item-title="
                                                (category) =>
                                                    `${category.title_en} (${category.title_bn})`
                                            "
                                            item-value="id"
                                            label="Parent Category"
                                            variant="outlined"
                                            density="compact"
                                            :error-messages="
                                                form.errors.parent_id
                                            "
                                            prepend-inner-icon="mdi-folder-tree"
                                            clearable
                                        />
                                    </v-col>


                                    <!-- Category Image Upload -->
                                    <v-col cols="12" md="8">
                                        <v-file-input
                                            v-model="form.image"
                                            title="Upload Category Image"
                                            variant="outlined"
                                            density="compact"
                                            :error-messages="form.errors.image"
                                            accept="image/*"
                                            prepend-inner-icon="mdi-camera"
                                        />
                                    </v-col>
                                </v-row>

                                <div class="d-flex mt-8 gap-3">
                                    <VButton
                                        type="submit"
                                        :disabled="form.processing"
                                        :loading="form.processing"
                                        size="large"
                                    >
                                        <v-icon left>mdi-plus</v-icon>
                                        Create Category
                                    </VButton>

                                    <Link
                                        :href="
                                            route('product.categories.index')
                                        "
                                    >
                                        <VButton variant="tonal" size="large">
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

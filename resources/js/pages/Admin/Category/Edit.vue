<script setup lang="ts">
import { VButton } from '@/components/ui/button';
import VFileInput from '@/components/ui/VFileInput.vue';
import VInputField from '@/components/VInputField.vue';
import MasterLayout from '@/layouts/MasterLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps<{
    category: {
        id: number;
        title_en: string;
        title_bn: string;
        slug: string;
        description?: string;
        icon?: string;
        image?: string;
        image_url?: string;
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
    image: null as File | null,
    parent_id: props.category.parent_id,
});

const submit = () => {
    form.post(route('product.categories.update', props.category.id));
};
</script>

<template>
    <MasterLayout>
        <Head title="Edit Category" />
        <v-container>
            <v-row>
                <v-col cols="12">
                    <v-card>
                        <v-card-title
                            class="d-flex align-center justify-space-between"
                        >
                            Edit Category
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
                                            :items="props.categories"
                                            :item-title="
                                                (cat) =>
                                                    `${cat.title_en} (${cat.title_bn})`
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
                                    <v-col
                                        cols="12"
                                        v-if="props.category.image_url"
                                    >
                                        <div class="text-caption mb-2">
                                            Current Image
                                        </div>
                                        <v-img
                                            :src="props.category.image_url"
                                            height="100"
                                            max-width="150"
                                            class="rounded"
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
                                        <v-icon left>mdi-content-save</v-icon>
                                        Update Category
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

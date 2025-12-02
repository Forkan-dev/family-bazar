<script setup lang="ts">
import { Button } from '@/components/ui/button'
import FileDropzone from '@/components/ui/FileDropzone.vue'
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs'
import VInputField from '@/components/VInputField.vue'
import MasterLayout from '@/layouts/MasterLayout.vue'
import { Head, useForm, Link } from '@inertiajs/vue3'
import { computed, watch } from 'vue'
import { ArrowLeft } from 'lucide-vue-next'

const route = (name: string, params?: any) => {
    return (window as any).route(name, params)
}

const props = defineProps<{
    brand?: {
        id: number;
        en_name: string;
        bn_name: string;
        slug: string;
        image: string;
    };
}>();

const form = useForm({
    en_name: props.brand?.en_name || '',
    bn_name: props.brand?.bn_name || '',
    slug: props.brand?.slug || '',
    image: null as File | null,
});

const submit = () => {
    if (props.brand) {
        form.put(route('product.brands.update', props.brand.id));
    } else {
        form.post(route('product.brands.store'));
    }
};

// Watch for changes in name to auto-generate slug
watch(
    () => form.en_name,
    (newName) => {
        if (!props.brand) { // Only auto-generate slug for new brands
            form.slug = newName
                .toLowerCase()
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/^-|-$/g, '');
        }
    },
);
</script>

<template>
    <MasterLayout>

        <Head :title="brand ? `Edit ${brand.en_name}` : 'Create Brand'" />

        <!-- Header -->
        <div class="mb-8">
            <Link :href="route('product.brands.index')">
            <Button variant="ghost" size="sm">
                <ArrowLeft class="h-4 w-4 mr-2" />
                Back to Brands
            </Button>
            </Link>
        </div>

        <!-- Form -->
        <div class="max-w-4xl mx-auto">
            <div class="bg-card border rounded-lg">
                <div class="p-6 border-b">
                    <h1 class="text-2xl font-bold">
                        {{ brand ? 'Edit Brand' : 'Create Brand' }}
                    </h1>
                </div>

                <form @submit.prevent="submit" class="p-6">
                    <div class="space-y-8">
                        <!-- Language Tabs -->
                        <Tabs default-value="en" class="space-y-6">
                            <TabsList class="grid w-full grid-cols-2 max-w-sm">
                                <TabsTrigger value="en">English</TabsTrigger>
                                <TabsTrigger value="bn">Bengali</TabsTrigger>
                            </TabsList>

                            <TabsContent value="en" class="space-y-4">
                                <VInputField v-model="form.en_name" label="Brand Name EN" placeholder="Enter brand name"
                                    :error-messages="form.errors.en_name" required />
                            </TabsContent>

                            <TabsContent value="bn" class="space-y-4">
                                <VInputField v-model="form.bn_name" label="Brand Name BN"
                                    placeholder="Enter brand name in Bengali" :error-messages="form.errors.bn_name" />
                            </TabsContent>
                        </Tabs>

                        <!-- URL Slug -->
                        <VInputField v-model="form.slug" label="URL Slug" placeholder="brand-slug"
                            :error-messages="form.errors.slug" required />

                        <!-- Image Upload -->
                        <FileDropzone v-model="form.image" label="Brand Logo" :error-messages="form.errors.image"
                            accept="image/*" />

                        <!-- Current Image (when editing) -->
                        <div v-if="brand?.image" class="space-y-2">
                            <label class="text-sm font-medium">Current Logo</label>
                            <img :src="`/${brand.image}`" :alt="brand.en_name"
                                class="w-32 h-32 object-cover rounded-lg border" />
                        </div>

                        <!-- Form Actions -->
                        <div class="flex justify-end space-x-3 pt-6 border-t">
                            <Link :href="route('product.brands.index')">
                            <Button type="button" variant="outline">
                                Cancel
                            </Button>
                            </Link>
                            <Button type="submit" :disabled="form.processing">
                                {{ form.processing
                                    ? (brand ? 'Updating...' : 'Creating...')
                                    : (brand ? 'Update Brand' : 'Create Brand')
                                }}
                            </Button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </MasterLayout>
</template>

<script setup lang="ts">
import { VButton } from '@/components/ui/button';
import VInputField from '@/components/VInputField.vue';
import MasterLayout from '@/layouts/MasterLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps<{
    banner: {
        id: number;
        title: string;
        sub_title: string;
        description?: string;
        image?: string;
        type_id: number;
        language: string;
        position?: number;
        status: 'active' | 'inactive';
        button_text_1?: string;
        button_url_1?: string;
        button_text_2?: string;
        button_url_2?: string;
    };
    types: Array<{ id: number; name: string; }>
}>();

const form = useForm({
    _method: 'put',
    title: props.banner.title,
    sub_title: props.banner.sub_title,
    description: props.banner.description,
    image: null as File | null,
    type_id: props.banner.type_id,
    language: props.banner.language,
    position: props.banner.position,
    status: props.banner.status,
    button_text_1: props.banner.button_text_1,
    button_url_1: props.banner.button_url_1,
    button_text_2: props.banner.button_text_2,
    button_url_2: props.banner.button_url_2,
});

const submit = () => {
    form.post(route('admin.banners.update', props.banner.id));
};

</script>

<template>
    <MasterLayout>
        <Head title="Edit Banner" />
        <v-container>
            <v-row>
                <v-col cols="12">
                    <v-card>
                        <v-card-title
                            class="d-flex align-center justify-space-between"
                        >
                            Edit Banner
                            <Link :href="route('admin.banners.index')">
                                <VButton variant="outlined">
                                    <v-icon left class="mr-2"
                                        >mdi-arrow-left</v-icon
                                    >
                                    Back to Banners
                                </VButton>
                            </Link>
                        </v-card-title>
                        <v-divider></v-divider>
                        <v-card-text>
                            <v-form @submit.prevent="submit">
                                <v-row class="mt-4" >
                                    <v-col cols="12">
                                        <VInputField
                                            v-model="form.title"
                                            label="Title"
                                            :error-messages="
                                                form.errors.title
                                            "
                                            required
                                            density="compact"
                                            variant="outlined"
                                        />
                                    </v-col>

                                    <v-col cols="12">
                                        <VInputField
                                            v-model="form.sub_title"
                                            label="Sub Title"
                                            :error-messages="form.errors.sub_title"
                                            density="compact"
                                            variant="outlined"
                                        />
                                    </v-col>

                                    <v-col cols="12">
                                        <VInputField
                                            v-model="form.description"
                                            label="Description"
                                            :error-messages="form.errors.description"
                                            multiline
                                            density="compact"
                                            variant="outlined"
                                            rows="3"
                                        />
                                    </v-col>

                                    <v-col cols="4">
                                        <v-select
                                            v-model="form.type_id"
                                            :items="props.types"
                                            item-title="name"
                                            item-value="id"
                                            label="Type"
                                            variant="outlined"
                                            density="compact"
                                            :error-messages="
                                                form.errors.type_id
                                            "
                                            prepend-inner-icon="mdi-folder-tree"
                                            clearable
                                        />
                                    </v-col>

                                    <v-col cols="4">
                                        <VInputField
                                            v-model="form.position"
                                            label="Position"
                                            type="number"
                                            :error-messages="form.errors.position"
                                            density="compact"
                                            variant="outlined"
                                        />
                                    </v-col>

                                    <v-col cols="4">
                                        <v-select
                                            v-model="form.status"
                                            :items="['active', 'inactive']"
                                            label="Status"
                                            variant="outlined"
                                            density="compact"
                                            :error-messages="form.errors.status"
                                        />
                                    </v-col>

                                    <v-col cols="6">
                                        <VInputField
                                            v-model="form.button_text_1"
                                            label="Button Text 1"
                                            :error-messages="form.errors.button_text_1"
                                            density="compact"
                                            variant="outlined"
                                        />
                                    </v-col>

                                    <v-col cols="6">
                                        <VInputField
                                            v-model="form.button_url_1"
                                            label="Button URL 1"
                                            :error-messages="form.errors.button_url_1"
                                            density="compact"
                                            variant="outlined"
                                        />
                                    </v-col>

                                    <v-col cols="6">
                                        <VInputField
                                            v-model="form.button_text_2"
                                            label="Button Text 2"
                                            :error-messages="form.errors.button_text_2"
                                            density="compact"
                                            variant="outlined"
                                        />
                                    </v-col>

                                    <v-col cols="6">
                                        <VInputField
                                            v-model="form.button_url_2"
                                            label="Button URL 2"
                                            :error-messages="form.errors.button_url_2"
                                            density="compact"
                                            variant="outlined"
                                        />
                                    </v-col>



                                    <v-col cols="12" >
                                        <v-file-input
                                            v-model="form.image"
                                            title="Upload Banner Image"
                                            variant="outlined"
                                            density="compact"
                                            :error-messages="form.errors.image"
                                            accept="image/*"
                                            prepend-inner-icon="mdi-camera"
                                        />
                                    </v-col>
                                    <v-col
                                        cols="12"
                                        v-if="props.banner.image"
                                    >
                                        <div class="text-caption mb-2">
                                            Current Image
                                        </div>
                                        <v-img
                                            :src="`/${props.banner.image}`"
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
                                        Update Banner
                                    </VButton>

                                    <Link
                                        :href="
                                            route('admin.banners.index')
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

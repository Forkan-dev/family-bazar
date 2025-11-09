<script setup lang="ts">
import { VButton } from '@/components/ui/button';
import VInputField from '@/components/VInputField.vue';
import MasterLayout from '@/layouts/MasterLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';

const props = defineProps<{
    banner: {
        id: number;
        title: string | { en?: string; bn?: string };       // JSON string or object
        sub_title: string | { en?: string; bn?: string };   // JSON string or object
        description?: string | { en?: string; bn?: string }; // JSON string or object
        image?: string;
        type_id: number;
        position?: number;
        status: 'active' | 'inactive';
        button_text_1?: string;
        button_url_1?: string;
        button_text_2?: string;
        button_url_2?: string;
    };
    types: Array<{ id: number; name: string }>;
}>();

const tab = ref('en');

const form = useForm({
    _method: 'PUT',
    title_en: '',
    title_bn: '',
    sub_title_en: '',
    sub_title_bn: '',
    description_en: '',
    description_bn: '',
    position: props.banner?.position || null,
    status: props.banner?.status || 'inactive',
    button_text_1: props.banner?.button_text_1 || '',
    button_url_1: props.banner?.button_url_1 || '',
    button_text_2: props.banner?.button_text_2 || '',
    button_url_2: props.banner?.button_url_2 || '',
    type_id: props.banner?.type_id || null,
    image: null as File | null,
});

onMounted(() => {
    if (props.banner) {
        // title
        if (props.banner.title) {
            try {
                const title = typeof props.banner.title === 'string' 
                    ? JSON.parse(props.banner.title) 
                    : props.banner.title;
                form.title_en = title?.en || '';
                form.title_bn = title?.bn || '';
            } catch {
                form.title_en = props.banner.title || '';
                form.title_bn = '';
            }
        }

        // sub_title
        if (props.banner.sub_title) {
            try {
                const subTitle = typeof props.banner.sub_title === 'string' 
                    ? JSON.parse(props.banner.sub_title) 
                    : props.banner.sub_title;
                form.sub_title_en = subTitle?.en || '';
                form.sub_title_bn = subTitle?.bn || '';
            } catch {
                form.sub_title_en = props.banner.sub_title || '';
                form.sub_title_bn = '';
            }
        }

        // description (optional)
        if (props.banner.description) {
            try {
                const description = typeof props.banner.description === 'string' 
                    ? JSON.parse(props.banner.description) 
                    : props.banner.description;
                form.description_en = description?.en || '';
                form.description_bn = description?.bn || '';
            } catch {
                form.description_en = props.banner.description || '';
                form.description_bn = '';
            }
        }
    }
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
                                <v-tabs v-model="tab" class="mb-4">
                                    <v-tab value="en">English</v-tab>
                                    <v-tab value="bn">Bengali</v-tab>
                                </v-tabs>

                                <v-window v-model="tab">
                                    <v-window-item value="en">
                                        <v-row class="mt-2">
                                            <v-col cols="6">
                                                <VInputField
                                                    v-model="form.title_en"
                                                    label="Title (English)"
                                                    :error-messages="form.errors.title_en"
                                                    required
                                                    density="compact"
                                                    variant="outlined"
                                                />
                                            </v-col>
                                            <v-col cols="6">
                                                <VInputField
                                                    v-model="form.sub_title_en"
                                                    label="Sub Title (English)"
                                                    :error-messages="form.errors.sub_title_en"
                                                    density="compact"
                                                    variant="outlined"
                                                />
                                            </v-col>
                                            <v-col cols="12">
                                                <VInputField
                                                    v-model="form.description_en"
                                                    label="Description (English)"
                                                    :error-messages="form.errors.description_en"
                                                    multiline
                                                    density="compact"
                                                    variant="outlined"
                                                    rows="3"
                                                />
                                            </v-col>
                                        </v-row>
                                    </v-window-item>
                                    <v-window-item value="bn">
                                        <v-row class="mt-2">
                                            <v-col cols="6">
                                                <VInputField
                                                    v-model="form.title_bn"
                                                    label="Title (Bengali)"
                                                    :error-messages="form.errors.title_bn"
                                                    required
                                                    density="compact"
                                                    variant="outlined"
                                                />
                                            </v-col>
                                            <v-col cols="6">
                                                <VInputField
                                                    v-model="form.sub_title_bn"
                                                    label="Sub Title (Bengali)"
                                                    :error-messages="form.errors.sub_title_bn"
                                                    density="compact"
                                                    variant="outlined"
                                                />
                                            </v-col>
                                            <v-col cols="12">
                                                <VInputField
                                                    v-model="form.description_bn"
                                                    label="Description (Bengali)"
                                                    :error-messages="form.errors.description_bn"
                                                    multiline
                                                    density="compact"
                                                    variant="outlined"
                                                    rows="3"
                                                />
                                            </v-col>
                                        </v-row>
                                    </v-window-item>
                                </v-window>

                                <v-row class="mt-2">
                                    <v-col cols="4">
                                        <v-select
                                            v-model="form.type_id"
                                            :items="props.types"
                                            item-title="name_en"
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
                                            :error-messages="form.errors.position"
                                            required
                                            density="compact"
                                            variant="outlined"
                                            @input="
                                            form.position =
                                                form.position.replace(
                                                    /[^0-9]/g,
                                                    '',
                                                )
                                        "
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
                                            required
                                            density="compact"
                                            variant="outlined"
                                        />
                                    </v-col>
                                    <v-col cols="6">
                                        <VInputField
                                            v-model="form.button_url_1"
                                            label="Button Url 1"
                                            :error-messages="form.errors.button_url_1"
                                            required
                                            density="compact"
                                            variant="outlined"
                                        />
                                    </v-col>

                                    <v-col cols="6">
                                        <VInputField
                                            v-model="form.button_text_2"
                                            label="Button Text 2"
                                            :error-messages="form.errors.button_text_2"
                                            required
                                            density="compact"
                                            variant="outlined"
                                        />
                                    </v-col>
                                    <v-col cols="6">
                                        <VInputField
                                            v-model="form.button_url_2"
                                            label="Button Url 2"
                                            :error-messages="form.errors.button_url_2"
                                            required
                                            density="compact"
                                            variant="outlined"
                                        />
                                    </v-col>


                                    <!-- Banner Image Upload -->

                                    <v-col cols="12">
                                        <v-file-input
                                            label="Upload Banner Image"
                                            variant="outlined"
                                            density="compact"
                                            :error-messages="form.errors.image"
                                            accept="image/*"
                                            prepend-inner-icon="mdi-camera"
                                            @input="form.image = $event.target.files[0]"
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
                                                :src="props.banner.image"
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

                                    <Link :href="route('admin.banners.index')">
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

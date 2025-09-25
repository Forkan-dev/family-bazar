<script setup lang="ts">
import MasterLayout from '@/layouts/MasterLayout.vue';
import { computed } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import { VTextField } from 'vuetify/components';

declare function route(name: string, params?: any): string;

const props = defineProps<{
    union: {
        id: number;
        upazila_id: number;
        name_en: string;
        name_bn: string;
    };
    upazilas: Array<{ id: number; name_en: string; name_bn: string }>;
}>();

const form = useForm({
    _method: 'put',
    upazila_id: props.union.upazila_id,
    name_en: props.union.name_en,
    name_bn: props.union.name_bn,
});

const isEdit = computed(() => !!props.union?.id);

const submit = () => {
    form.post(route('locations.update', props.union.id));
};
</script>

<template>
    <MasterLayout>
        <Head title="Edit Union" />

        <v-container>
            <v-row>
                <v-col cols="12">
                    <v-card>
                        <v-card-title>Edit Union</v-card-title>

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
                                            <v-select
                                                v-model="form.upazila_id"
                                                :items="upazilas"
                                                :item-title="
                                                    (upazila) =>
                                                        `${upazila.name_en} (${upazila.name_bn})`
                                                "
                                                item-value="id"
                                                label="Select Upazila"
                                                placeholder="Choose an Upazila"
                                                clearable
                                                density="compact"
                                                variant="outlined"
                                                :error-messages="
                                                    form.errors.upazila_id
                                                "
                                                hide-details="auto"
                                            />
                                        </v-col>
                                        <v-col cols="12" md="6">
                                            <v-text-field
                                                v-model="form.name_en"
                                                :label="`Name (English) - Current: ${union.name_en}`"
                                                placeholder="Name (English)"
                                                :error-messages="
                                                    form.errors.name_en
                                                "
                                                required
                                                density="compact"
                                                variant="outlined"
                                                hide-details="auto"
                                            />
                                        </v-col>

                                        <v-col cols="12" md="6">
                                            <v-text-field
                                                v-model="form.name_bn"
                                                :label="`Name (Bangla) - Current: ${union.name_bn}`"
                                                placeholder="Name (Bangla)"
                                                :error-messages="
                                                    form.errors.name_bn
                                                "
                                                density="compact"
                                                variant="outlined"
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

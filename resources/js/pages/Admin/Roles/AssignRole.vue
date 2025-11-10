<script setup lang="ts">
import { useForm } from '@inertiajs/vue3'
import MasterLayout from '@/layouts/MasterLayout.vue';
import { VButton } from '@/components/ui/button';
import { watch } from 'vue';

const props = defineProps({
    users: Array,
    roles: Array,
})

const form = useForm({
    user_id: null,
    roles: [],
})

const submit = () => {
    form.post(route('admin.roles.assign.store'))
}

watch(() => form.user_id, (newVal) => {
    if (newVal) {
        const user = props.users.find(u => u.id === newVal)
        if (user) {
            form.roles = user.roles.map(r => r.name)
        }
    } else {
        form.roles = []
    }
})
</script>

<template>
    <MasterLayout>
        <v-container>
            <v-row>
                <v-col cols="12">
                    <v-card>
                        <v-card-title class="d-flex align-center">
                            <v-icon class="mr-3" color="primary">mdi-account-key</v-icon>
                            Assign Roles
                        </v-card-title>
                        <v-divider></v-divider>
                        <v-card-text class="mt-5">
                            <v-form @submit.prevent="submit">
                                <v-row>
                                    <v-col cols="12" md="6">
                                        <v-select
                                            v-model="form.user_id"
                                            :items="users"
                                            item-title="name"
                                            item-value="id"
                                            label="Select User"
                                            variant="outlined"
                                            density="compact"
                                            :error-messages="form.errors.user_id"
                                        ></v-select>
                                    </v-col>
                                    <v-col cols="12" md="6">
                                        <v-select
                                            v-model="form.roles"
                                            :items="roles"
                                            item-title="name"
                                            item-value="name"
                                            label="Select Roles"
                                            multiple
                                            chips
                                            variant="outlined"
                                            density="compact"
                                            :error-messages="form.errors.roles"
                                        ></v-select>
                                    </v-col>
                                </v-row>
                                <div class="d-flex gap-3 mt-5">
                                    <VButton type="submit" :disabled="form.processing" :loading="form.processing">
                                        <v-icon left>mdi-content-save</v-icon>
                                        Assign Roles
                                    </VButton>
                                </div>
                            </v-form>
                        </v-card-text>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
    </MasterLayout>
</template>

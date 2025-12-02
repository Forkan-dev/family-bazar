<script setup lang="ts">
import { Button } from '@/components/ui/button'
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs'
import VInputField from '@/components/VInputField.vue'
import MasterLayout from '@/layouts/MasterLayout.vue'
import { Head, useForm, Link } from '@inertiajs/vue3'
import { computed } from 'vue'
import { ArrowLeft } from 'lucide-vue-next'

const route = (name: string, params?: any) => {
    return (window as any).route(name, params)
}

const props = defineProps<{
    union?: {
        id: number;
        upazila_id: number;
        name_en: string;
        name_bn: string;
    };
    upazilas: Array<{ id: number; name_en: string; name_bn: string }>;
}>();

const form = useForm({
    upazila_id: props.union?.upazila_id || null,
    name_en: props.union?.name_en || '',
    name_bn: props.union?.name_bn || '',
});

const upazilaOptions = computed(() =>
    props.upazilas.map(upazila => ({
        value: upazila.id,
        label: `${upazila.name_en} (${upazila.name_bn})`
    }))
)

const submit = () => {
    if (props.union) {
        form.put(route('locations.update', props.union.id));
    } else {
        form.post(route('locations.store'));
    }
};
</script>

<template>
    <MasterLayout>

        <Head :title="union ? `Edit ${union.name_en}` : 'Create Union'" />

        <!-- Header -->
        <div class="mb-8">
            <Link :href="route('locations.index')">
            <Button variant="ghost" size="sm">
                <ArrowLeft class="h-4 w-4 mr-2" />
                Back to Locations
            </Button>
            </Link>
        </div>

        <!-- Form -->
        <div class="max-w-4xl mx-auto">
            <div class="bg-card border rounded-lg">
                <div class="p-6 border-b">
                    <h1 class="text-2xl font-bold">
                        {{ union ? 'Edit Union' : 'Create Union' }}
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
                                <VInputField v-model="form.name_en" label="Union Name EN" placeholder="Enter union name"
                                    :error-messages="form.errors.name_en" required />
                            </TabsContent>

                            <TabsContent value="bn" class="space-y-4">
                                <VInputField v-model="form.name_bn" label="Union Name BN"
                                    placeholder="Enter union name in Bengali" :error-messages="form.errors.name_bn" />
                            </TabsContent>
                        </Tabs>

                        <!-- Upazila Selection -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium mb-2">
                                    Select Upazila
                                    <span class="text-red-500 ml-1">*</span>
                                </label>
                                <select v-model="form.upazila_id"
                                    class="w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    :class="{ 'border-red-500': form.errors.upazila_id }">
                                    <option value="">Choose an Upazila</option>
                                    <option v-for="upazila in upazilas" :key="upazila.id" :value="upazila.id">
                                        {{ upazila.name_en }} ({{ upazila.name_bn }})
                                    </option>
                                </select>
                                <div v-if="form.errors.upazila_id" class="mt-1">
                                    <p class="text-sm text-red-600">{{ form.errors.upazila_id }}</p>
                                </div>
                            </div>
                        </div>



                        <!-- Form Actions -->
                        <div class="flex justify-end space-x-3 pt-6 border-t">
                            <Link :href="route('locations.index')">
                            <Button type="button" variant="outline">
                                Cancel
                            </Button>
                            </Link>
                            <Button type="submit" :disabled="form.processing">
                                {{ form.processing
                                    ? (union ? 'Updating...' : 'Creating...')
                                    : (union ? 'Update Union' : 'Create Union')
                                }}
                            </Button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </MasterLayout>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import MasterLayout from '@/layouts/MasterLayout.vue'
import { Head, useForm, Link } from '@inertiajs/vue3'
import { Button } from '@/components/ui/button'
import SearchableSelect from '@/components/ui/SearchableSelect.vue'
import VInputField from '@/components/VInputField.vue'
// no remote fetch for now; we'll add dummy targets locally
import { ArrowLeft, Trash2 } from 'lucide-vue-next'

const route = (name: string, params?: any) => (window as any).route(name, params)

const form = useForm({
	name: '',
	name_bn: '',
	start_at: '',
	end_at: '',
	discount_type: 'percentage',
	flat_amount: null,
	
	percentage: null,
	type: 'product',
	targets: [] as Array<any>,
})

const selectedTarget = ref<number | null>(null)
const selectedOption = ref<{ value: any; label: string } | null>(null)



const searchUrlForType = computed(() => {
	return form.type === 'product' ? 'http://127.0.0.1:8000/api/products/dropdown' : '/api/categories/dropdown'
})


// list of items added as targets for the offer - products or categories
const targets = ref<Array<{ id: number; label: string; type: string }>>([])



const addTarget = () => {

	//check if all data added is same type
	if (targets.value.length > 0 && targets.value[0].type !== form.type) {
		alert('All targets must be of the same type. Please remove existing targets to add a different type.')
		return
	}

	// require a selected option
	if (!selectedOption.value) return

	const id = selectedOption.value.value ?? Date.now()
	const label = selectedOption.value.label ?? (form.type === 'product' ? `Product ${id}` : `Category ${id}`)

	// Prevent duplicates
	if (targets.value.find(t => t.id === id && t.type === form.type)) {
		selectedTarget.value = null
		selectedOption.value = null
		return
	}

	targets.value.push({ id, label, type: form.type })
	selectedTarget.value = null
	selectedOption.value = null
}

const removeTarget = (index: number) => {
	targets.value.splice(index, 1)
}

const submit = () => {
	// Build payload targets as simple array
	form.targets = targets.value.map(t => ({ id: t.id, type: t.type }))
	form.post(route('admin.offers.store'))
}

const handleType = () => {
	// Clear selected target and option when type changes
	selectedTarget.value = null
	selectedOption.value = null
}
console.log(form.errors.targets);

</script>

<template>
	<MasterLayout>
		<Head title="Create Offer" />

		<div class="mb-6">
			<Link :href="route('admin.offers.index')">
				<Button variant="ghost" size="sm">
					<ArrowLeft class="h-4 w-4 mr-2" />
					Back to Offers
				</Button>
			</Link>
		</div>

		<div class="max-w-4xl mx-auto">
			<div class="bg-card border rounded-lg">
				<div class="p-6 border-b">
					<h1 class="text-2xl font-bold">Create Offer</h1>
				</div>

				<form @submit.prevent="submit" class="p-6">
					<div class="space-y-6">
						<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
							<VInputField v-model="form.name" label="Offer Name (EN)" :error-messages="form.errors.name" required />
							<VInputField v-model="form.name_bn" label="Offer Name (BN)" :error-messages="form.errors.name_bn" />
						</div>

						<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
							<VInputField v-model="form.start_at" label="Start Date" type="datetime-local" :error-messages="form.errors.start_at" />
							<VInputField v-model="form.end_at" label="End Date" type="datetime-local" :error-messages="form.errors.end_at" />
						</div>

						<!-- Discount settings -->
						<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
							<div>
								<label class="block text-sm font-medium mb-2">Discount Type</label>
								<select v-model="form.discount_type" class="w-full rounded border px-3 py-2">
									<option value="percentage">Percentage</option>
									<option value="flat">Flat amount</option>
								</select>
							</div>
							<div v-if="form.discount_type === 'flat'">
								<VInputField v-model="form.flat_amount" label="Flat Discount Amount" type="number" :error-messages="form.errors.flat_amount" />
							</div>
							<div v-if="form.discount_type === 'percentage'">
								<VInputField v-model="form.percentage" label="Discount Percentage" type="number" :error-messages="form.errors.percentage" />
							</div>
						</div>

						<div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
							<div>
								<label class="block text-sm font-medium mb-2">Target Type</label>
								<select v-model="form.type" @change="handleType" class="w-full rounded border px-3 py-2">
									<option value="product">Product</option>
									<option value="category">Category</option>
								</select>
							</div>

							<div class="md:col-span-1">
								<label class="block text-sm font-medium mb-2">Select {{ form.type === 'product' ? 'Product' : 'Category' }}</label>
								<div class="flex gap-3 items-center">
									<SearchableSelect v-model="selectedTarget" :search-url="searchUrlForType" @select-option="(opt) => selectedOption = opt" placeholder="Search and select..." />
									
								</div>
							</div>
							<div class="justify-center"><Button type="button" @click="addTarget">Add</Button></div>

						</div>

						<!-- Targets table -->
						<div>
							<label class="block text-sm font-medium mb-2">Selected Targets</label>
							<div v-if="targets.length === 0" class="text-sm text-muted-foreground">No targets added yet.</div>
							
							<table v-else class="w-full text-left border-collapse">
								<thead>
									<tr class="border-b">
										<th class="py-2">#</th>
										<th class="py-2">Type</th>
										<th class="py-2">Name</th>
										<th class="py-2">Actions</th>
									</tr>
								</thead>
								<tbody>
									<tr v-for="(t, i) in targets" :key="t.type + '-' + t.id" class="border-b">
										<td class="py-2">{{ i + 1 }}</td>
										<td class="py-2">{{ t.type }}</td>
										<td class="py-2">{{ t.label }}</td>
										<td class="py-2">
											<Button variant="ghost" size="sm" @click="removeTarget(i)">
												<Trash2 class="h-4 w-4" />
											</Button>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
						<div ><small class="text-red-600">{{form.errors.targets}}</small></div>

						<div class="flex justify-end space-x-3 pt-6 border-t">
							<Link :href="route('admin.offers.index')">
								<Button type="button" variant="outline">Cancel</Button>
							</Link>
							<Button type="submit" :disabled="form.processing">{{ form.processing ? 'Creating...' : 'Create Offer' }}</Button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</MasterLayout>
</template>


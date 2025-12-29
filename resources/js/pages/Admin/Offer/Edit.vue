<script setup lang="ts">
import { ref, computed } from 'vue'
import MasterLayout from '@/layouts/MasterLayout.vue'
import { Head, useForm, Link, usePage } from '@inertiajs/vue3'
import { Button } from '@/components/ui/button'
import SearchableSelect from '@/components/ui/SearchableSelect.vue'
import VInputField from '@/components/VInputField.vue'
import { ArrowLeft, Trash2 } from 'lucide-vue-next'

const route = (name: string, params?: any) => (window as any).route(name, params)

const page = usePage()
const offer = (page.props as any).offer ?? {}
const offerTargets = (page.props as any).targets ?? []


const form = useForm({
	name: offer.name ?? '',
	name_bn: offer.name_bn ?? '',
	start_at: offer.start_at ? offer.start_at : '',
	end_at: offer.end_at ? offer.end_at : '',
	discount_type: offer.discount_type ?? 'percentage',
	flat_amount: offer.discount_type === 'flat' ? offer.value : null,
	percentage: offer.discount_type === 'percentage' ? offer.value : null,
	type: (offer.offerTargets && offer.offerTargets.length > 0) ? offer.offerTargets[0].target_type : 'product',
	targets:  offerTargets as Array<any>,
})

console.log(offer);



const selectedTarget = ref<number | null>(null)
const selectedOption = ref<{ value: any; label: string } | null>(null)


const searchUrlForType = computed(() => {
	return form.type === 'product' ? 'http://127.0.0.1:8000/api/products/dropdown' : '/api/categories/dropdown'
})



// initialize targets from incoming offer prop
if (form.targets) {
	form.targets = form.targets.map((t: any) => ({
		id: t.target_id ?? t.id,
        label : t.name,
		type: t.type,
	}))
}



const addTarget = () => {
	console.log(form.targets[0].type,form.type);
	
	if (form.targets.length > 0 && form.targets[0].type !== form.type) {
		alert('All targets must be of the same type. Please remove existing targets to add a different type.')
		return
	}

	if (!selectedOption.value) return

	const id = selectedOption.value.value ?? Date.now()
	const label = selectedOption.value.label ?? (form.type === 'product' ? `Product ${id}` : `Category ${id}`)

	if (form.targets.find(t => t.id === id && t.type === form.type)) {
		selectedTarget.value = null
		selectedOption.value = null
		return
	}

	form.targets.push({ id, label, type: form.type })
	selectedTarget.value = null
	selectedOption.value = null
}

const removeTarget = (index: number) => {
	form.targets.splice(index, 1)
}

const submit = () => {
	form.targets = form.targets.map(t => ({ id: t.id, type: t.type }))
	form.put(route('admin.offers.update', offer.id))
}

const handleType = () => {
	selectedTarget.value = null
	selectedOption.value = null
}




</script>

<template>
	<MasterLayout>
		<Head title="Edit Offer" />

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
					<h1 class="text-2xl font-bold">Edit Offer</h1>
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
						<div ><small class="text-red-600">{{form.errors.targets}}</small></div>

						<div>
							<label class="block text-sm font-medium mb-2">Selected Targets</label>
							<div v-if="form.targets.length === 0" class="text-sm text-muted-foreground">No targets added yet.</div>

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
									<tr v-for="(t, i) in form.targets" :key="t.type + '-' + t.id" class="border-b">
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

						<div class="flex justify-end space-x-3 pt-6 border-t">
							<Link :href="route('admin.offers.index')">
								<Button type="button" variant="outline">Cancel</Button>
							</Link>
							<Button type="submit" :disabled="form.processing">{{ form.processing ? 'Updating...' : 'Update Offer' }}</Button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</MasterLayout>
</template>


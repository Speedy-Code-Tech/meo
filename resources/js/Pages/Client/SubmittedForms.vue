<script setup>
import { useForm } from "@inertiajs/inertia-vue3";
import { useToast } from 'vue-toastification'
import Pagination from '../../Components/Pagination.vue';
const toast = useToast();

import ClientLayout from "@/Shared/ClientLayout.vue";
defineOptions({layout: ClientLayout});

defineProps({
	records: [Object, Array],
});

const formData = useForm({
    id: null,
});

function getType(type) {
	var text = '';
	switch (type) {
		case "1":
			text = "Business Permit (New)";
			break;
		case "2":
			text = "Building Permit";
			break;
		default:
			text = "Business Permit (Renewal)";
			break;
	}
	return text;
}

function getRecord(id) {
	formData.id = id;
	formData.get(route('my-application-forms-record'), {
		preserveState: true,
	});
}

function checkedBy(checkedBy) {
	if (checkedBy != null) {
		return checkedBy.lname + ', ' + checkedBy.fname + ' ' +  checkedBy.mname
	}

	return '';
}
</script>

<template>
	<Head title="My Application forms" />
	<h1 class="text-2xl font-bold mt-5 mb-2 text-center">Submitted Applications</h1>
	<hr class="border-gray-300 mb-6" />
	<div class="w-full px-4 max-w-6xl mx-auto py-6 card mt-3">
		<table class="w-full text-sm text-left">
			<thead class="text-md text-gray-700 uppercase">
				<tr>
					<th>Project Title</th>
					<th>Category</th>
					<th>Document Type</th>
					<th>Checked By</th>
					<th>Status</th>
					<th>Remarks</th>
					<th>View</th>
				</tr>
			</thead>
			<tbody>
				<template v-for="(item, index) in records.data" :key="index">
					<tr class="border-y text-sm text-gray-900">
						<td class="!py-2">{{ (item.project_title).toUpperCase() }}</td>
						<td class="!py-2 uppercase">{{ item.category }}</td>
						<td class="!py-2">{{ getType(item.type) }}</td>
						<td class="!py-2">{{checkedBy(item.checked_by)}}</td>
						<td class="!py-2 whitespace-nowrap">
							<span class="p-2 rounded"
								:class="{
									'bg-green-100': item.status == 'Approved',
									'bg-red-100': item.status == 'Rejected',
									'bg-yellow-100': item.status == 'Pending',
									'bg-orange-200': item.status == 'Returned',
								}"
							>
								<i class="mr-2"
								:class="{
									'fas fa-check-circle text-green-500': item.status == 'Approved',
									'fas fa-times-circle text-red-500': item.status == 'Rejected',
									'fas fa-hourglass-half text-yellow-500': item.status == 'Pending',
									'fas fa-arrow-rotate-left text-orange-500': item.status == 'Returned',
								}"
								></i>
								{{ item.status }}
							</span>
						</td>
						<td class="!py-2">{{ item.remarks }}</td>
						<td class="!py-2">
							<button @click="getRecord(item.id)"
								class="p-2 text-gray-600 hover:text-gray-900 relative">
								<i class="fas fa-eye"></i>
							</button>
						</td>
					</tr>
				</template>
			</tbody>
		</table>
		<div
        v-if="records.data.length < 1"
        class="w-full bg-gray-100 text-center text-sm p-5"
      >
        No data available
      </div>
	  <div v-if="records.data.length > 0">
		
	  </div>
	</div>
</template>
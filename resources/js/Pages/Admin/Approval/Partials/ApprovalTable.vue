<script setup>
import { ref, onMounted } from 'vue';
import moment from 'moment';
import { useForm } from "@inertiajs/inertia-vue3";
import Pagination from '@/Components/Pagination.vue';
import TextInput from '../../../Components/TextInput.vue';

const form = useForm({
	page: 1,
});

const routeName = 'admin.approval.index';

const changePage = (page) => {
	form.page = page;
};

// onMounted(() => {
// 	form.page = props.records.currentPage ?? 1
// });

function formatDate(date) {
	return moment(date).format('MMMM DD, YYYY h:mm a');
}

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

const props = defineProps({
	queue: [Object, Array],
});

const openMenuIndex = ref(null);

const toggleMenu = (index) => {
	openMenuIndex.value = openMenuIndex.value === index ? null : index;
};

const formData = useForm({
	id: null,
	status: null,
	type: null,
	isNew: null,
	clientId: null,
	remarks: null,
	data:null
});

function changeStatus(id, status, type, isSubmit = true, remarks = null,data) {
	formData.id = id;
	formData.status = status;
	formData.type = type;
	formData.remarks = remarks;
	formData.data = data;

	if (isSubmit) {
		submitForm();
	}
}

function submitForm() {
	formData.post(route('admin.approval.changestatus'));
}

function getRecord(id, type, clientId) {
	formData.id = id;
	formData.type = type;
	formData.clientId = clientId;

	formData.post('/admin/approval/getRecord');
}

const showRejectModal = ref(false);

function toggleRejectModal() {
	showRejectModal.value = !showRejectModal.value;
}

function checkedBy(checkedBy) {
	if (checkedBy != null) {
		return checkedBy.lname + ', ' + checkedBy.fname + ' ' +  checkedBy.mname
	}

	return '';
}
</script>

<template>
	<div>
		<table class="w-full text-sm text-left">
			<thead class="text-md text-gray-700 uppercase">
				<tr>
					<th class="w-[170px]">Type of Permit</th>
					<th>Name of Owner</th>
					<th>Project Title</th>
					<th>Application Date</th>
					<th>Remarks</th>
					<th>Status</th>
					<th>Checked By</th>
					<th>View</th>
				</tr>
			</thead>
			<tbody>
				<template v-for="(item, index) in queue.data" :key="index">
					<tr class="border-y text-sm text-gray-900">
						<td class="!py-2">{{ getType(item.type) }}</td>
						<td class="!py-2">{{ item.client.lname }}, {{ item.client.fname }} {{ item.client.mname}}</td>
						<td class="!py-2">{{ item.project_title }}</td>
						<td class="!py-2">{{ formatDate(item.application_date) }}</td>
						<td class="!py-2">{{ item.remarks }}</td>
						<td class="!py-2 whitespace-nowrap">
							<span class="p-2 rounded"
								:class="{
									'bg-green-100': item.status == 'Approved',
									'bg-red-100': item.status == 'Rejected',
									'bg-yellow-100': item.status == 'Pending',
									'bg-orange-100': item.status == 'Returned',
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
						<td class="!py-2">{{checkedBy(item.checked_by)}}</td>
						<td class="!py-2">
							<button @click="getRecord(item.id, item.type, item.client_id)"
								class="p-2 text-gray-600 hover:text-gray-900 relative">
								<i class="fas fa-eye"></i>
							</button>
						</td>
					</tr>
				</template>
			</tbody>
		</table>
		<div v-if="queue.total < 1" class="w-full bg-gray-100 text-center text-sm p-5">
			No data available
		</div>

		<Pagination v-if="queue.total > 1 && queue.last_page > 1"
			:currentPage="queue.current_page"
			:lastPage="queue.last_page"
			@page-changed="changePage(queue.current_page)"
			:url="routeName"
			:previousPageUrl="queue.prev_page_url"
			:nextPageUrl="queue.next_page_url"
		/>

		<!-- Reject modal -->
		<div v-if="showRejectModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-50">
			<div class="bg-white rounded-lg shadow-lg w-full max-w-xl">
				<form @submit.prevent="submitForm(), toggleRejectModal()">
					<div class="flex justify-between items-center p-4 border-b">
						<h3 class="text-lg font-semibold">Reject Application Form</h3>
						<button @click="toggleRejectModal" class="text-gray-500 hover:text-gray-700">
							<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
								xmlns="http://www.w3.org/2000/svg">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
								</path>
							</svg>
						</button>
					</div>
					<div class="p-4 h-[20vh]">
						
						<TextInput name="Remarks" v-model:modelValue="formData.remarks"></TextInput>
						<div class="mt-5">
							<button class="primary-btn">Submit</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</template>
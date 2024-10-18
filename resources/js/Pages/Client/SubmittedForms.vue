<script setup>
import { useForm } from "@inertiajs/inertia-vue3";
import Pagination from './Pagination.vue'; // Import the Pagination component

defineProps({
    records: [Object, Array], // Paginated records from backend
});

const formData = useForm({
    id: null,
});

function getRecord(id) {
    formData.id = id;
    formData.get(route('my-application-forms-record'), {
        preserveState: true,
    });
}

// Function to handle page changes
function changePage(page) {
    const url = new URL(route('client.submitted-forms')); // Replace with the correct route
    url.searchParams.set('page', page); // Set the page parameter in the URL

    formData.get(url.toString(), { preserveState: true });
}
</script>

<template>
    <div>
        <!-- Table displaying records -->
        <table class="w-full text-sm text-left">
            <thead>
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
                        <td>{{ item.project_title.toUpperCase() }}</td>
                        <td class="uppercase">{{ item.category }}</td>
                        <td>{{ getType(item.type) }}</td>
                        <td>{{ checkedBy(item.checked_by) }}</td>
                        <td>
                            <span class="p-2 rounded"
                                :class="{
                                    'bg-green-100': item.status == 'Approved',
                                    'bg-red-100': item.status == 'Rejected',
                                    'bg-yellow-100': item.status == 'Pending',
                                    'bg-orange-200': item.status == 'Returned',
                                }">
                                <i class="mr-2"
                                    :class="{
                                        'fas fa-check-circle text-green-500': item.status == 'Approved',
                                        'fas fa-times-circle text-red-500': item.status == 'Rejected',
                                        'fas fa-hourglass-half text-yellow-500': item.status == 'Pending',
                                        'fas fa-arrow-rotate-left text-orange-500': item.status == 'Returned',
                                    }"></i>
                                {{ item.status }}
                            </span>
                        </td>
                        <td>{{ item.remarks }}</td>
                        <td>
                            <button @click="getRecord(item.id)" class="p-2 text-gray-600 hover:text-gray-900 relative">
                                <i class="fas fa-eye"></i>
                            </button>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>

        <!-- No data message -->
        <div v-if="records.data.length < 1" class="text-center">
            No data available
        </div>

        <!-- Pagination component -->
        <Pagination
            v-if="records.last_page > 1"
            :currentPage="records.current_page"
            :lastPage="records.last_page"
            :previousPageUrl="records.prev_page_url"
            :nextPageUrl="records.next_page_url"
            @page-changed="changePage"
        />
    </div>
</template>

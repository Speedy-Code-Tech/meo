<script setup>
import { useForm } from "@inertiajs/inertia-vue3";
import { ref } from 'vue';
import TextInput from './TextInput.vue';
const props = defineProps({
    label: {
        type: String,
        required: true
    },
    showDownloadButton: {
        type: Boolean,
        default: true
    },
    showUploadButton: {
        type: Boolean,
        default: true
    },
    hasFile: {
        type: null,
        default: null
    },
    hasUploadedFile: {
        type: null,
        default: false
    },
    documentId: {
        type: null,
        default: null
    },
    documentRemarks: {
        type: String,
        default: true
    },
    downloadableFile: {
        type: null,
        default: null
    },
    inputId: {
        type: Number,
        default: 'upload-file'
    }
});
const hasUpload = ref(false);
const fileUploadText = ref('Upload');

const emit = defineEmits(['file-selected', 'pdf-url', 'download-url', 'label', 'documentRemarks','title']);

const handleFileChange = (event) => {
    console.log('wew');
    const file = event.target.files[0];
    console.log(file)
    if (file) {
        emit('file-selected', { file, inputId: props.inputId }); 
        hasUpload.value = true;
        fileUploadText.value = 'File Uploaded';
    }
    console.log(hasUpload.value);
};

const handleDownload = (data) => {
    emit('download-url', { downloadableFile: props.downloadableFile }); 
    emit('title-url', { title: data }); 
    localStorage.setItem('title', data);
};

const handlePdfUrl = () => {
    emit('pdf-url', { hasFile: props.hasFile }); 
}

const handlePdfTitle = () => {
    emit('label', { label: props.label }); 
}

const handlePdfRemarks = () => {
    emit('documentRemarks', { documentRemarks: props.documentRemarks }); 
}

function getUploadText() {
    if (props.hasUploadedFile == true) {
        return "File Uploaded";
    }

    return "Upload";
}

const removePdfUrl = (inputId) => {
    const file = null;
    hasUpload.value = false;
    emit('file-selected', { file, inputId: inputId }); 
    fileUploadText.value = 'Upload';
    const fileInput = document.getElementById(inputId);
    fileInput.value = null;
    console.log(inputId);
}

// remarks
const formData = useForm({
	id: null,
	remarks: null,
});
const showRemarksModal = ref(false);
const addRemarks = () => {
    formData.id = props.documentId;
	formData.post(route('admin.approval.addDocumentRemarks'));
}
function toggleRemarksModal() {
	showRemarksModal.value = !showRemarksModal.value;
}

</script>
<template>
    <div class="flex items-center justify-between mb-3">
        
        <div class="flex-1">
            {{ label }}
        </div>
        <div class="flex-shrink-0 flex space-x-3">
            <!-- Download Button -->

            <div v-if="showDownloadButton"
                @click="handleDownload(label)" class="text-white px-3 py-1 rounded cursor-pointer"
                :class="[
                    !downloadableFile ? 'bg-gray-100 cursor-not-allowed pointer-events-none' : 'bg-red-400',
                ]"
            >
                View 
            </div>

            <!-- Upload Button -->
            <label v-if="showUploadButton" :for="inputId"
                class="bg-blue-500 text-white px-3 py-1 rounded cursor-pointer">
                {{getUploadText()}}
            </label>
            <input :id="inputId" type="file" class="hidden" @change="handleFileChange" accept="application/pdf"/>
            <div v-if="documentId != null" class="flex items-center justify-center w-6 h-6 bg-orange-500 rounded-full cursor-pointer">
                <i class="fa-solid fa-inbox text-white " @click="toggleRemarksModal"></i>
            </div>
            <div v-if="hasFile != null" class="flex items-center justify-center w-6 h-6 bg-yellow-500 rounded-full cursor-pointer">
                <i class="fa-solid fa-eye text-white " @click="handlePdfUrl(), handlePdfTitle(), handlePdfRemarks()"></i>
            </div>
             <div v-if="hasFile != null" class="flex items-center justify-center w-6 h-6 bg-green-500 rounded-full">
                <i class="fa-solid fa-check text-white text-1xl"></i>
            </div>

            <div v-if="!showUploadButton && hasFile == null"
            
            class="flex items-center justify-center w-6 h-6 bg-red-500 rounded-full">
                <i class="fa-solid fa-x text-white" ></i>
            </div>
            <div v-if="hasUploadedFile" @click="removePdfUrl(inputId)"
            
            class="flex items-center justify-center w-6 h-6 bg-yellow-500 rounded-full cursor-pointer">
                <i class="fas fa-x text-white" ></i>
            </div>
        </div>
    </div>
    <div v-if="showRemarksModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-50">
			<div class="bg-white rounded-lg shadow-lg w-full max-w-xl">
				<form @submit.prevent="addRemarks(), toggleRemarksModal()">
					<div class="flex justify-between items-center p-4 border-b">
						<h3 class="text-lg font-semibold">Add Remarks</h3>
						<button @click="toggleRemarksModal" class="text-gray-500 hover:text-gray-700">
							<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
								xmlns="http://www.w3.org/2000/svg">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
								</path>
							</svg>
						</button>
					</div>
					<div class="p-4 h-[20vh]">
						
						<!-- <TextInput name="Remarks" ></TextInput> -->
                        <textarea name="Remarks" class="w-full h-15 border border-gray-300 rounded p-2" v-model="formData.remarks"></textarea >
						<div class="mt-5">
							<button class="primary-btn">Submit</button>
						</div>
					</div>
				</form>
			</div>
		</div>
</template>

<style scoped>
/* Custom styles */
.bg-blue-500 {
    background-color: #3b82f6;
}

.bg-green-500 {
    background-color: #10b981;
}

.text-white {
    color: #ffffff;
}

.px-3 {
    padding-left: 0.75rem;
    padding-right: 0.75rem;
}

.py-1 {
    padding-top: 0.25rem;
    padding-bottom: 0.25rem;
}

.rounded {
    border-radius: 0.375rem;
}

.cursor-pointer {
    cursor: pointer;
}
</style>
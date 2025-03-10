<template>
    <div class="file-upload-section">
        <h3>Upload Files</h3>

        <!-- File upload section -->
        <div class="file-upload">
            <label for="formFileMultiple" class="form-label">Select files(pdf, docx, txt, json)</label>
            <div class="mb-3 file-upload-row">
                <input class="form-control" type="file" id="formFileMultiple" multiple @change="handleFileUpload">
            </div>
            <p v-if="fileUploadError" class="file-upload-error">{{ fileUploadError }}</p>
            <!-- Display the uploaded files list -->
            <div class="chat-agent-box">
                <ul v-if="!loadingFiles && selectedFiles.length > 0">
                    <!-- All select checkbox -->
                    <div class="checkbox-all-row">
                        <input type="checkbox" id="selectAll" @change="selectAllFiles" v-model="selectAll">
                        <label for="selectAll">Select All</label>
                    </div>

                    <!-- File items with individual checkboxes -->
                    <li v-for="(file, index) in selectedFiles" :key="index" class="file-item">
                        <input type="checkbox" :value="file.id" v-model="selectedFileIds">
                        <p class="file-name">{{ file.file_name }}</p>
                        <div class="button-container d-flex">
                            <button class="download-btn" @click="downloadFile(file.assistantId, file.file_name)">
                                <i class="fa fa-download"></i>
                            </button>
                            <button class="removeFile" @click="removeFile(file.assistantId, file.id, file.file_name)">x</button>
                        </div>

                    </li>
                </ul>

                <!-- Show "Delete Selected Files" button when any checkbox is clicked -->
                <button v-if="selectedFileIds.length > 0" class="btn btn-danger" @click="deleteSelectedFiles">Delete Selected Files</button>

                <!-- Show a loader while files are being fetched -->
                <p v-else-if="loadingFiles" class="loading-files">Loading files...<i class="fa fa-spinner fa-spin"></i></p>

                <!-- Show a message if no files are available and not loading -->
                <p v-if="!loadingFiles && selectedFiles.length === 0" class="no-files-message">No files available</p>
            </div>
        </div>
    </div>
</template>


<script>
import axios from "axios";
import Swal from "sweetalert2";
import {useClinicStore} from "../../stores/clinic";
import {useAuthStore} from "../../stores/auth";

export default {
    setup() {
        const clinicStore = useClinicStore();
        const authStore = useAuthStore();

        return { clinicStore, authStore };
    },
    data() {
        return {
            selectedFiles: [],
            // assistantData: {},
            assistantId: '',
            loading: true,
            loadingUpload: false,
            loadingFiles: true,
            fileId: '',
            selectedFileIds: [],
            selectAll: false,
            fileUploadError: '',
        };
    },
    created() {
        this.assistantId = localStorage.getItem('assistant_id');
        // this.fetchAssistantData();
        this.fetchUploadedFiles();
    },
    methods: {
        downloadFile(assistantId, fileName) {
            const clinicId = localStorage.getItem('clinic_id');
            const downloadUrl = `/api/v1/clinic/${clinicId}/assistant/${this.assistantId}/files/${fileName}/download`;

            axios({
                url: downloadUrl,
                method: 'GET',
                responseType: 'blob'
            })
                .then(response => {
                    // Create a download link and trigger the download
                    const url = window.URL.createObjectURL(new Blob([response.data]));
                    const link = document.createElement('a');
                    link.href = url;
                    link.setAttribute('download', fileName); // Specify the file name
                    document.body.appendChild(link);
                    link.click();
                })
                .catch(error => {
                    console.error('Error downloading file:', error);
                    Swal.fire('Error!', 'Failed to download the file.', 'error');
                });
        },
        fetchUploadedFiles() {
            this.loadingFiles = true;
            axios.get(`/api/v1/assistant/${this.assistantId}/files`)
                .then(response => {
                    this.selectedFiles = Array.isArray(response.data) ? response.data : [];
                    this.loadingFiles = false;
                    // window.location.reload();
                })
                .catch(error => {
                    console.error('Error fetching uploaded files:', error);
                    this.loadingFiles = false;
                });
        },

        handleFileUpload(event) {
            const files = Array.from(event.target.files);
            const supportedExtensions = ['pdf', 'docx', 'txt', 'json'];
            const maxSizeInBytes = 2 * 1024 * 1024;

            const validFiles = files.filter(file => {
                const fileExtension = file.name.split('.').pop().toLowerCase();
                const isSupportedExtension = supportedExtensions.includes(fileExtension);

                if (!isSupportedExtension) {
                    this.fileUploadError = `Invalid file type for ${file.name}. Please upload files with valid extensions: pdf, docx, txt, or json.`;
                    return false;
                }

                if (file.size > maxSizeInBytes) {
                    this.fileUploadError = `File ${file.name} exceeds the maximum size of 2MB. Please upload a smaller file.`;
                    return false;
                }

                return true;
            });

            if (validFiles.length === 0) {
                // Swal.fire('Error', 'Invalid file type. Please upload files with valid extensions: pdf, docx, txt, or json.', 'error');
                // return;
                // this.fileUploadError = 'Invalid file type. Please upload files with valid extensions: pdf, docx, txt, or json.';
                return;
            }

            this.fileUploadError = '';
            const uploadPromises = validFiles.map(file => this.uploadFile(file));

            Promise.all(uploadPromises)
                .then(() => {
                    this.fetchUploadedFiles();
                })
                .catch(error => {
                    console.error('Error uploading files:', error);
                });
             },

        uploadFile(file) {
            const formData = new FormData();
            formData.append('file', file);
            formData.append('assistantId', this.assistantId);
            const clinicId = localStorage.getItem('clinic_id');
            formData.append('clinicId', clinicId);

            return axios.post(`/api/v1/assistant/upload-file`, formData, {
                headers: { 'Content-Type': 'multipart/form-data' }
            }).then(response => {
                this.selectedFiles.push({
                    file_name: response.data.file_name,
                    id: response.data.id,
                    assistantId: this.assistantId
                });
                Swal.fire({
                    title: 'Success!',
                    text: 'File uploaded successfully.',
                    icon: 'success',
                    confirmButtonText: 'OK'
                });
            }).catch(error => {
                Swal.fire({
                    title: 'Error!',
                    text: 'There was an error uploading the file.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            });
        },

        removeFile(assistantId, fileId, fileName) {
            const clinicId = localStorage.getItem('clinic_id');
            Swal.fire({
                title: `Delete ${fileName}?`,
                text: "This action cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then(result => {
                if (result.isConfirmed) {
                    axios.delete(`/api/v1/clinic/${clinicId}/delete-file/assistant/${this.assistantId}/files/${fileId}/${fileName}`)
                        .then(response => {
                            if (response.data.success) {
                                Swal.fire('Deleted!', response.data.message, 'success');
                                window.location.reload();
                                this.fetchUploadedFiles();
                            }
                        })
                        .catch(error => {
                            Swal.fire('Error!', error.response?.data?.error || 'Failed to delete the file.', 'error');
                        });
                }
            });
        },

        // Select All/Unselect All files
        selectAllFiles() {
            if (this.selectAll) {
                this.selectedFileIds = this.selectedFiles.map(file => file.id);
            } else {
                this.selectedFileIds = [];
            }
        },

        // Delete selected files
        deleteSelectedFiles() {
            Swal.fire({
                title: 'Delete selected files?',
                text: "This action cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete them!',
                cancelButtonText: 'Cancel'
            }).then(result => {
                if (result.isConfirmed) {
                    const deletePromises = this.selectedFileIds.map(fileId => {
                        const fileToDelete = this.selectedFiles.find(file => file.id === fileId);
                        const fileName = fileToDelete ? fileToDelete.file_name : '';
                        const clinicId = localStorage.getItem('clinic_id');

                        return axios.delete(`/api/v1/clinic/${clinicId}/delete-file/assistant/${this.assistantId}/files/${fileId}/${fileName}`);
                    });
                    Promise.all(deletePromises)
                        .then(() => {
                            Swal.fire('Deleted!', 'Selected files have been deleted.', 'success');
                            this.fetchUploadedFiles();
                            this.selectedFileIds = [];
                            this.selectAll = false;
                        })
                        .catch(error => {
                            Swal.fire('Error!', error.response?.data?.error || 'Failed to delete files.', 'error');
                        });
                }
            });
        }

    }
};
</script>

<style scoped>
.file-upload-section {
    margin: 0 0 20px;
    padding: 20px;
    border: 1px solid #ffffff;
    border-radius: 8px;
    background-color: #ffffff;
}

.file-upload-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.file-upload input[type="file"] {
    margin-right: 10px;
    flex: 1;
}

ul {
    list-style-type: none;
    padding-left: 0;
}

.file-item {
    display: flex;
    justify-content: space-between;
    margin-bottom: 8px;
}

button.removeFile {
    background: red;
    color: white;
    border: none;
    padding: 5px 9px;
    border-radius: 5px;
    cursor: pointer;
}

button.removeFile:hover {
    background-color: #ea082dde;
}

.no-files-message {
    color: #888;
    font-style: italic;
}
p.file-upload-error {
    color: red;
}
.chat-agent-box ul li { border-top: 1px solid #DEDEDE; padding: 0.5rem 1rem; margin: 0 !important; }
.chat-agent-box ul li.file-item { justify-content: start; align-items: center; font-size: 0.9rem; font-weight: 500; }
.chat-agent-box ul li input[type="checkbox"] { margin-right: 0.6rem; }
.chat-agent-box ul li .removeFile { height: 30px; width: 30px; margin-left: auto; position: relative; font-size: 0; background: #F00D0D; }
.chat-agent-box ul li .removeFile:before, .chat-agent-box ul li .removeFile:after { content: ""; position: absolute; height: 2px; width: 16px; background: #fff; left: 0; right: 0; top: 0; bottom: 0; margin: auto; }
.chat-agent-box ul li .removeFile::before { transform: rotate(45deg); }
.chat-agent-box ul li .removeFile::after { transform: rotate(-45deg); }
.chat-agent-box .checkbox-all-row { font-size: 0.9rem; font-weight: 500; padding: 0.5rem 1rem; color: #444; }
.chat-agent-box .checkbox-all-row input[type="checkbox"] { margin-right: 0.6rem; }
button.download-btn {
    background-color: #3498db;
    color: white;
    border: none;
    padding: 5px 9px;
    border-radius: 5px;
    cursor: pointer;
}

button.download-btn:hover {
    background-color: #2980b9;
}

button.download-btn i {
    margin-right: 5px;
}

.file-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 5px;
}

.button-container {
    display: flex;
    gap: 10px;
}

.download-btn,
.removeFile {
    padding: 5px 10px;
    cursor: pointer;
}

.download-btn i {
    margin-right: 5px;
}

p.file-name {
    margin-right: auto;
}
p {
     margin-top: 0;
     margin-bottom: 0;
}
</style>

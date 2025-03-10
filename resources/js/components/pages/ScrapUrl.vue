<template>
     <div class="card-body file-upload-section">
            <div class="links-section my-3">
                <h3>Crawl webpages</h3>
                <p>Add individual links</p>
                <input v-model="newLink" type="text" class="form-control" placeholder="https://example.com/blog/page-one" />
                <button @click="uploadLink" class="btn btn-primary my-2">Submit</button>

                <div v-if="links.length > 0">
                    <h4 class="mt-4">List of Links</h4>
                    <div class="checkbox-all-row">
                        <input type="checkbox" v-model="selectAll" @change="toggleSelectAll" /> Select All
                    </div>
                    <div class="chat-agent-box">
                    <ul>
                        <li v-for="(link, index) in links" :key="index" class="file-item">
                            <input type="checkbox" v-model="selectedLinkIds" :value="link.id" />
                            <p>
                            <a :href="link.url" target="_blank">{{ link.url }}</a>
                            </p>
                            <div class="button-container d-flex">
                            <button class="download-btn" @click="downloadFile(link.id)">
                                <i class="fa fa-download"></i>
                            </button>
                            <button @click="confirmRemoveLink(link.id)" class="removeFile">x</button>
                            </div>
                        </li>
                    </ul>
                    </div>
                    <button v-if="selectedLinkIds.length > 0" @click="confirmDeleteSelectedLinks" class="btn btn-danger mt-3">
                        Delete Selected
                    </button>
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
            newLink: '',
            links: [],
            selectedLinkIds: [],
            selectAll: false,
        };
    },
    created() {
        this.fetchLinks();
    },
    methods: {
        fetchLinks() {
            const clinicId = localStorage.getItem('clinic_id');
            axios.get('/api/v1/list-urls', { params: { clinic_id: clinicId } })
                .then(response => {
                    this.links = response.data;
                })
                .catch(error => {
                    console.error('Error fetching links:', error);
                });
        },
        uploadLink() {
            const clinicId = localStorage.getItem('clinic_id');
            const assistantId = localStorage.getItem('assistant_id');

            axios.post('/api/v1/submit-url', {
                url: this.newLink,
                clinicId: clinicId,
                assistant_id: assistantId
            })
                .then(() => {
                    this.newLink = '';
                    Swal.fire('Success', 'Link uploaded successfully!', 'success');
                    this.fetchLinks();
                })
                .catch(error => {
                    Swal.fire('Error', 'Failed to upload link.', 'error');
                    console.error('Error uploading link:', error);
                });
        },
        downloadFile(id) {
            window.location.href = `/api/v1/download-file/${id}`;
            Swal.fire('Success', 'File downloaded successfully!', 'success');
        },
        // downloadFile(id) {
        //     console.log('download file')
        //     axios.get(`/api/v1/download-file/${id}`, { responseType: 'blob' })
        //         .then(response => {
        //             const url = window.URL.createObjectURL(new Blob([response.data]));
        //             const link = document.createElement('a');
        //             link.href = url;
        //             link.setAttribute('download', `scraped_content_${id}.pdf`);
        //             document.body.appendChild(link);
        //             link.click();
        //             document.body.removeChild(link);
        //
        //             // Revoke the Blob URL to free up memory
        //             window.URL.revokeObjectURL(url);
        //             Swal.fire('Success', 'File downloaded successfully!', 'success');
        //         })
        //         .catch(error => {
        //             Swal.fire('Error', 'Failed to download file.', 'error');
        //             console.error('Error downloading file:', error);
        //         });
        // },
        toggleSelectAll() {
            if (this.selectAll) {
                this.selectedLinkIds = this.links.map(link => link.id);
            } else {
                this.selectedLinkIds = [];
            }
        },
        confirmRemoveLink(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.removeLink(id);
                }
            });
        },
        removeLink(id) {
            axios.delete(`/api/v1/delete-urls`, { data: { ids: [id] } })
                .then(() => {
                    Swal.fire('Deleted!', 'Your link has been deleted.', 'success');
                    this.fetchLinks();
                })
                .catch(error => {
                    Swal.fire('Error', 'Failed to delete the link.', 'error');
                    console.error('Error removing link:', error);
                });
        },
        confirmDeleteSelectedLinks() {
            if (this.selectedLinkIds.length === 0) {
                Swal.fire('Error', 'No links selected.', 'error');
                return;
            }

            Swal.fire({
                title: 'Are you sure?',
                text: "You are about to delete the selected links.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete them!'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.deleteSelectedLinks();
                }
            });
        },
        deleteSelectedLinks() {
            axios.delete(`/api/v1/delete-urls`, { data: { ids: this.selectedLinkIds } })
                .then(() => {
                    Swal.fire('Deleted!', 'Selected links have been deleted.', 'success');
                    this.selectedLinkIds = [];
                    this.fetchLinks();
                })
                .catch(error => {
                    Swal.fire('Error', 'Failed to delete selected links.', 'error');
                    console.error('Error deleting selected links:', error);
                });
        }
    }
};
</script>

<style scoped>
.card {
    margin: 20px 0;
    padding: 20px;
    border: 1px solid #dddddd;
    border-radius: 8px;
    background-color: #ffffff;
}

.checkbox-all-row {
    font-size: 0.9rem;
    font-weight: 500;
    padding: 0.5rem 1rem;
    color: #444;
}

.download-btn {
    background-color: #3498db;
    color: white;
    border: none;
    padding: 5px 9px;
    border-radius: 5px;
    cursor: pointer;
}

.download-btn:hover {
    background-color: #2980b9;
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

.list-group-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
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
.chat-agent-box ul li { border-top: 1px solid #DEDEDE; padding: 0 1rem; margin: 0 !important; }
.chat-agent-box ul li.file-item { justify-content: start; align-items: center; font-size: 0.9rem; font-weight: 500; }
.chat-agent-box ul li input[type="checkbox"] { margin-right: 0.6rem; }
.chat-agent-box ul li .removeFile { height: 30px; width: 30px; margin-left: auto; position: relative; font-size: 0; background: #F00D0D; }
.chat-agent-box ul li .removeFile:before, .chat-agent-box ul li .removeFile:after { content: ""; position: absolute; height: 2px; width: 16px; background: #fff; left: 0; right: 0; top: 0; bottom: 0; margin: auto; }
.chat-agent-box ul li .removeFile::before { transform: rotate(45deg); }
.chat-agent-box ul li .removeFile::after { transform: rotate(-45deg); }
.chat-agent-box .checkbox-all-row { font-size: 0.9rem; font-weight: 500; padding: 0.5rem 1rem; color: #444; }
.chat-agent-box .checkbox-all-row input[type="checkbox"] { margin-right: 0.6rem; }
.chat-agent-box ul {
    margin: 0;
    padding: 0;
    list-style: none;
}

.chat-agent-box ul li.file-item {
    display: flex;
}

.chat-agent-box ul li.file-item p {
    margin: 1rem 0;
}

.chat-agent-box ul li .button-container {
    margin-left: auto;
}

.chat-agent-box ul li .button-container button {
    margin: 0.5rem;
}
.card-body.file-upload-section {
    margin: 0 0 20px;
    padding: 20px;
    border: 1px solid #ffffff;
    border-radius: 8px;
    background-color: #ffffff;
}
</style>

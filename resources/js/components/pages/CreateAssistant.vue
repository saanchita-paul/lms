<template>

    <div>
        <h2 class="my-2">CRTX Chat Agent</h2>
       
        <button
        v-if="assistantId?.length <= 4"
        @click="createAssistant"
        class="btn px-4 my-2"
        :class="assistantId?.length > 4 ? 'btn-success' : 'btn-primary'"
        :disabled="assistantId?.length > 4 || clinicStore.clinic.chat_api_key === null"
    >
        {{ assistantId?.length > 4 ? 'Active' : 'Activate' }}
    </button>
    </div>


    <div v-if="assistantId?.length > 4" class="my-3">
        <div class="bg-white border-0 shadow-sm p-3 rounded-3">
            <div class="text-end mb-3 chat-buttons">
                <button class="btn btn-outline-primary my-1 px-4" @click="showChatbot = true">Chat with Agent</button>
                <!-- Chatbot Popup Modal -->
                <div v-if="showChatbot" class="modal-overlay" @click.self="showChatbot = false">
                    <div class="modal-content">
                        <button class="close-btn" @click="closeChatbot">×</button>
                        <Chatbot @close="showChatbot = false" />
                    </div>
                </div>
        
            </div>
            <div class="card rounded-3">
                <div class="card-header px-4 py-3 bg-light-gray">
                    <div class="d-md-flex">
                    <div class="mb-3 mb-md-0">
                        <h4 class="fw-bold">An easy-to-use WordPress plugin</h4>
                        <p class="mb-0 text-secondary"> Download and activate the plugin through the 'Plugins' screen in WordPress. </p>
                    </div>
                    <div class="ps-md-3 ms-auto">
                        <a href="javascript:void(0);" @click="downloadChatbotdPlugin" class="btn btn-primary text-nowrap d-inline-flex align-items-center px-4">
                        <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M19.25 13.75V17.4167C19.25 17.9029 19.0568 18.3692 18.713 18.713C18.3692 19.0568 17.9029 19.25 17.4167 19.25H4.58333C4.0971 19.25 3.63079 19.0568 3.28697 18.713C2.94315 18.3692 2.75 17.9029 2.75 17.4167V13.75M6.41667 9.16667L11 13.75M11 13.75L15.5833 9.16667M11 13.75V2.75" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                        <span class="ps-2">Download Plugin</span>
                        </a>
                    </div>
                    </div>
                </div>
                <div class="card-body fs-6 border-bottom">
                    <p class="mb-1">
                    <strong> After installing:</strong>
                    </p>
                    <ul class="mb-0">
                    <li>You'll see a new item on the left-hand menu called 'CRTX Chatbot'.</li>
                    <li>Find "API Key" in Api/model Tab and save settings </li>                    
                    </ul>
                </div>
                <div class="card-body fs-6 border-bottom">
                    <div class="d-md-flex align-items-center">
                        <p class="text-nowrap d-sm-flex mb-3 mb-md-0 w-100">
                            API Key:
                            <input
                                :type="showApiKey ? 'text' : 'password'"
                                :value="clinicStore.clinic.chat_api_key"
                                class="licence-key-input ms-2 p-0 border-0 fw-bold"
                            >
                        </p>
                        <div class="d-flex ps-md-3 ms-auto">
                            <!-- Clickable Container for the Icon -->
                            <div @click="toggleApiKeyVisibility" 
                 class="btn text-nowrap d-inline-flex align-items-center btn-primary px-3 me-3"
                 style="cursor: pointer;">
                 <svg 
                    width="22" 
                    height="22" 
                    viewBox="0 0 22 22" 
                    fill="none" 
                    xmlns="http://www.w3.org/2000/svg"
                    :data-testid="showApiKey ? 'VisibilityOutlinedIcon' : 'VisibilityOffOutlinedIcon'">
                    <g>
                        <path d="M0.916504 11.0003C0.916504 11.0003 4.58317 3.66699 10.9998 3.66699C17.4165 3.66699 21.0832 11.0003 21.0832 11.0003C21.0832 11.0003 17.4165 18.3337 10.9998 18.3337C4.58317 18.3337 0.916504 11.0003 0.916504 11.0003Z" 
                              :stroke="showApiKey ? 'white' : 'white'" 
                              stroke-width="2" 
                              stroke-linecap="round" 
                              stroke-linejoin="round"></path>
                        <path d="M10.9998 13.7503C12.5186 13.7503 13.7498 12.5191 13.7498 11.0003C13.7498 9.48154 12.5186 8.25033 10.9998 8.25033C9.48105 8.25033 8.24984 9.48154 8.24984 11.0003C8.24984 12.5191 9.48105 13.7503 10.9998 13.7503Z" 
                              :stroke="showApiKey ? 'white' : 'white'" 
                              stroke-width="2" 
                              stroke-linecap="round" 
                              stroke-linejoin="round"></path>
                    </g>
                </svg>
            </div>
                            <a href="javascript:void(0);" @click="copyApiKey" 
                            class="btn text-nowrap d-inline-flex align-items-center btn-outline-primary px-4">
                            Copy API Key
                            </a>
                        </div>
                    </div>
                </div>


                <div class="card-body fs-6">
                    <div class="d-md-flex align-items-center">
                    <p class="mb-3 mb-md-0">Assistant ID: <strong>{{ assistantId }}</strong>
                    </p>
                    <div class="ps-md-3 ms-auto">
                        <button @click="copyAssistantId" class="btn btn-outline-primary my-1 px-4"> {{ copied ? 'Copied!' : 'Copy Assistant ID' }}</button>
                    </div>
                   
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div v-if="copyMessage" class="alert alert-success my-3">
        {{ copyMessage }}
    </div>
    <assistant-settings v-if="assistantId?.length > 4"></assistant-settings>
<!--            <upload-file v-if="assistantId?.length > 4"></upload-file>-->
    <!-- Toggle buttons for Auto Reply Settings -->
    <assistant-email-sms-settings v-if="assistantId?.length > 4"></assistant-email-sms-settings>

    <div class="new-tabs-ui" v-if="assistantId?.length > 4">
        <!-- Tabs for Links and Files -->
        <ul class="nav nav-tabs" id="assistantTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" id="files-tab" data-bs-toggle="tab" href="#files" role="tab" aria-controls="files" aria-selected="false" @click="fetchFilesOnTabSwitch('files')">
                    <i class="fa fa-regular fa-file"></i>
                    Files
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="links-tab" data-bs-toggle="tab" href="#links" role="tab" aria-controls="links" aria-selected="true" @click="fetchFilesOnTabSwitch('links')">
                    <i class="fa fa-regular fa-link"></i>
                    Links
                </a>
            </li>
        </ul>
        <div class="tab-content" id="assistantTabsContent">
            <!-- Files tab -->
            <div class="tab-pane fade show active" id="files" role="tabpanel" aria-labelledby="files-tab">
                <upload-file></upload-file>
            </div>
            <!-- Links tab -->
            <div class="tab-pane fade" id="links" role="tabpanel" aria-labelledby="links-tab">
                <scrap-url></scrap-url>
            </div>
        </div>
    </div>

<!--    <chat-history v-if="assistantId?.length > 4"></chat-history>-->

</template>

<script>
import { useClinicStore } from "../../stores/clinic";
import { useAuthStore } from "../../stores/auth";
import { useAssistantStore } from '../../stores/assistantStore';
import UploadFile from "./UploadFile.vue";
import AssistantSettings from "./AssistantSettings.vue";
import ScrapUrl from "./ScrapUrl.vue";
import axios from "axios";
import Chatbot from "./Chatbot.vue";
import ChatHistory from "./ChatHistory.vue";
import AssistantEmailSmsSettings from "./AssistantEmailSmsSettings.vue";

export default {
    components: {
        UploadFile,
        AssistantSettings,
        ScrapUrl,
        Chatbot,
        // ChatHistory
        AssistantEmailSmsSettings
    },

    setup() {
        const clinicStore = useClinicStore();
        const authStore = useAuthStore();
        const assistantStore = useAssistantStore();

        clinicStore.find();

        return { clinicStore, authStore, assistantStore };
    },

    data() {        
        return {
            assistantName: this.authStore.clinic_id,
            assistantId: localStorage.getItem('assistant_id') || null,
            copyMessage: null,
            copied: false,
            loadingFiles: true,
            selectedFiles: [],
            showChatbot: false,
            apiKey: null,
            showApiKey: false, // Controls visibility of the API key
        };
    },
    watch: {
        // Watch for changes in clinicStore.clinic
        'clinicStore.clinic': {
            handler(newClinic) {
                if (newClinic && newClinic.chat_api_key) {
                    this.apiKey = newClinic.chat_api_key;
                    console.log('API Key updated:', this.apiKey);
                }
            },
            immediate: true, // Trigger the watcher immediately if the data is already available
            deep: true, // Watch for deep changes in objects
        },
    },
    methods: {
        closeChatbot() {
            this.showChatbot = false;
            // window.location.reload();
        },
        async fetchFilesOnTabSwitch(tab) {
            if (tab === 'files') {
                window.location.reload();
                this.fetchFiles();
            }
        },
        async fetchFiles() {
            
            try {
                const response = await axios.get(`/api/v1/assistant/${this.assistantId}/files`);
                this.files = response.data;
            } catch (error) {
                console.error('Error fetching files:', error);
            }
        },
        async createAssistant() {
            const response = await this.assistantStore.createAssistant(this.assistantName);

            if (response) {
                localStorage.setItem('assistant_id', response);
                this.assistantId = response;
                console.log('Assistant created successfully, ID:', response);
            } else {
                console.error('Failed to create assistant');
            }
        },
        copyAssistantId() {
            navigator.clipboard.writeText(this.assistantId)
                .then(() => {
                    this.copied = true;
                    this.copyMessage = 'Assistant ID copied to clipboard!';
                    setTimeout(() => {
                        this.copied = false;
                        this.copyMessage = null;
                    }, 3000);
                })
                .catch(err => {
                    console.error('Failed to copy Assistant ID:', err);
                });
        },
        copyApiKey() {
            
            navigator.clipboard.writeText(this.apiKey)
                .then(() => {
                    this.copied = true;
                    this.copyMessage = 'apiKey copied to clipboard!';
                    setTimeout(() => {
                        this.copied = false;
                        this.copyMessage = null;
                    }, 3000);
                })
                .catch(err => {
                    console.error('Failed to copy apiKey:', err);
                });
        },
        toggleApiKeyVisibility() {
            this.showApiKey = !this.showApiKey;
            console.log('API Key visibility toggled:', this.showApiKey);
        },
        downloadChatbotdPlugin() {
            window.open('https://agent.mycrtx.com/wp-content/uploads/2024/12/crtx-chatbot.zip', '_blank');
        },

    }
};
</script>

<style scoped>
.btn-primary {
    background-color: #007bff;
    color: white;
}

.btn-success {
    background-color: #28a745;
    color: white;
}
.chat-agent-box .file-upload-section { border: 0; border-radius: 8px; background: #FFF; padding: 1.5rem; box-shadow: 0 0 20px rgba(0, 0, 30, 0.07); margin: 0; }

.assistant-id-box {
    border: 1px solid #ddd;
    padding: 10px;
    border-radius: 5px;
    display: flex;
    justify-content: flex-start;
    align-items: center;
}

.assistant-id-box p {
    margin-bottom: 0;
    margin-right: 10px;
}

.form-check .form-check-input {
    opacity: 1;
}
.new-tabs-ui .tab-content { margin: 0; }
.new-tabs-ui .tab-content .file-upload-section { border-top-left-radius: 0; }
.new-tabs-ui .nav-tabs .nav-item { font-size: 1rem; }
.new-tabs-ui .nav-tabs .nav-link { margin: 0; border: 0; color: #8287A2; padding: 0.6rem 1.5rem; }
.new-tabs-ui .nav-tabs { border: 0; }
.new-tabs-ui .nav-tabs .nav-link.active { color: #425BCF; }
.new-tabs-ui .nav-tabs .nav-link svg { margin: 0 0.3rem 0 0; }
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
}

.modal-content {
    background-color: white;
    padding: 20px;
    border-radius: 8px;
    width: 90%;
    max-width: 600px;
}
.close-btn {
    position: absolute;
    top: 10px;
    right: 10px;
    background: none;
    border: none;
    font-size: 24px;
    cursor: pointer;
}
.chat-buttons {
    gap: 10px;
}
.licence-key-input { white-space: nowrap; overflow: hidden; text-overflow: ellipsis; width: calc(100% - 120px);border: 0 !important;
    outline: none;  box-shadow: none; }

</style>

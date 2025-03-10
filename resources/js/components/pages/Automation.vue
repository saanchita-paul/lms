<template>
    <div id="main">
        <div class="container-fluid p-0">
            <div class="page-title my-4">
                <h3>Automation Template</h3>
            </div>
            <div>
                <div class="page-back-btn me-auto my-4">
                    <router-link class="btn px-4" to="/crtx/manage-practice/automation" role="button">
                        <svg
                            width="20"
                            height="14"
                            viewBox="0 0 20 14"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg" class="me-2"
                        >
                            <path
                                fill-rule="evenodd"
                                clip-rule="evenodd"
                                d="M19.4411 6.37497H2.04859L7.25172 1.19185C7.49609 0.947473 7.49609 0.551848 7.25172 0.308098C7.00734 0.0637231 6.61172 0.0637231 6.36797 0.308098L0.180469 6.49497C-0.0601562 6.7356 -0.0601562 7.1381 0.180469 7.37872L6.36797 13.5662C6.61234 13.8106 7.00797 13.8106 7.25172 13.5662C7.49609 13.3218 7.49609 12.9262 7.25172 12.6825L2.04859 7.62497H19.4411C19.7861 7.62497 20.0661 7.34497 20.0661 6.99997C20.0661 6.65497 19.7861 6.37497 19.4411 6.37497Z"
                                fill="#514F5F"
                            />
                        </svg>
                        <span>Back to Automation</span>
                    </router-link>
                </div>
                <div
                    class="tab-main-box"
                    :class="nurturing_template_editing ? 'tab-edit-active tab-main-active' : 'tab-disabled-active'"
                >
                    <div class="tab-title-box d-flex align-items-center">
                        <div class="tab-title" @click.prevent="nurturing_template_editing = true">
                            <h3 v-if="clinicStore.automationTemplates.length > 0">
                                Manage {{ clinicStore.automationTemplates[0].status_name }} Templates
                            </h3>
                        </div>
                    </div>
                    <div class="tab-sub-box">
                        <div class="d-lg-none d-flex align-items-center">
                            <a role="button" class="back-btn" @click="resetActiveTabs()">
                                <svg
                                    width="20"
                                    height="14"
                                    viewBox="0 0 20 14"
                                    fill="none"
                                    xmlns="http://www.w3.org/2000/svg"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        clip-rule="evenodd"
                                        d="M19.4411 6.37497H2.04859L7.25172 1.19185C7.49609 0.947473 7.49609 0.551848 7.25172 0.308098C7.00734 0.0637231 6.61172 0.0637231 6.36797 0.308098L0.180469 6.49497C-0.0601562 6.7356 -0.0601562 7.1381 0.180469 7.37872L6.36797 13.5662C6.61234 13.8106 7.00797 13.8106 7.25172 13.5662C7.49609 13.3218 7.49609 12.9262 7.25172 12.6825L2.04859 7.62497H19.4411C19.7861 7.62497 20.0661 7.34497 20.0661 6.99997C20.0661 6.65497 19.7861 6.37497 19.4411 6.37497Z"
                                        fill="#514F5F"
                                    />
                                </svg>
                            </a>
                        </div>
                        <div class="d-lg-none mobile-tab-title">
                            <h3 v-if="clinicStore.automationTemplates.length > 0">
                                Manage {{ clinicStore.automationTemplates[0].status_name }} Templates
                            </h3>
                        </div>
                        <div class="row gx-3">
                            <div class="col-md-12 col-lg-12">
                                <div class="dashboard-box">

                                    <div class="dashboard-sub-box ">
                                        <table
                                            class="nurturing-table table table-dark table-striped table-bordered"
                                            style="border-color:#f0f4f8;">
                                            <thead>
                                            <tr>
                                                <th scope="col">Day</th>
                                                <th scope="col" width="40%">Text Template</th>
                                                <th scope="col">Email Subject</th>
                                                <th scope="col" style="width:40%">Email Template</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr v-if="clinicStore.automationTemplates.length>0"
                                                v-for="template in clinicStore.automationTemplates">

                                                <th scope="row">{{ template.dayinterval }}</th>
                                                <td v-on:click="editNurturing('Text Template', template.text_template, template.dayinterval)">
                                                    <div class="nurturing-text-container"><span
                                                        class="nurture-edit-icon"><svg
                                                        class="feather feather-edit" fill="none" height="20"
                                                        stroke="#425BCF" stroke-linecap="round"
                                                        stroke-linejoin="round" stroke-width="2"
                                                        viewBox="0 0 24 24" width="24"
                                                        xmlns="http://www.w3.org/2000/svg"><path
                                                        d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path
                                                        d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></span>
                                                        <div v-html="htmlEntities(template.text_template)"></div>
                                                    </div>
                                                </td>
                                                <td v-on:click="editNurturing('Email Subject', template.email_subject, template.dayinterval)">
                                                    <div class="nurturing-text-container"><span
                                                        class="nurture-edit-icon"><svg
                                                        class="feather feather-edit" fill="none" height="20"
                                                        stroke="#425BCF" stroke-linecap="round"
                                                        stroke-linejoin="round" stroke-width="2"
                                                        viewBox="0 0 24 24" width="24"
                                                        xmlns="http://www.w3.org/2000/svg"><path
                                                        d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path
                                                        d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></span><div v-html="htmlEntities(template.email_subject)"></div>
                                                    </div>
                                                </td>
                                                <td v-on:click="editNurturing('Email Template', template.email_template, template.dayinterval)">
                                                    <div class="nurturing-text-container"><span
                                                        class="nurture-edit-icon"><svg
                                                        class="feather feather-edit" fill="none" height="20"
                                                        stroke="#425BCF" stroke-linecap="round"
                                                        stroke-linejoin="round" stroke-width="2"
                                                        viewBox="0 0 24 24" width="24"
                                                        xmlns="http://www.w3.org/2000/svg"><path
                                                        d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path
                                                        d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></span><div v-html="htmlEntities(template.email_template)"></div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr v-else>There are no nurturing templates to display!</tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="editNurturingModal" tabindex="-1" aria-labelledby="editNurturingModal"
         aria-hidden="true">
        <div class="modal-dialog add-lead-model modal-dialog-centered modal-lg">
            <div class="modal-content p-md-3 bg-light-gray">
                <div class="modal-header">
                    <h3 class="modal-title" id="staticBackdropLabel">{{ editTitle }}</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <svg width="20" height="14" viewBox="0 0 20 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                  d="M19.4411 6.37503H2.04859L7.25172 1.19191C7.49609 0.947534 7.49609 0.551909 7.25172 0.308159C7.00734 0.0637842 6.61172 0.0637842 6.36797 0.308159L0.180469 6.49503C-0.0601562 6.73566 -0.0601562 7.13816 0.180469 7.37878L6.36797 13.5663C6.61234 13.8107 7.00797 13.8107 7.25172 13.5663C7.49609 13.3219 7.49609 12.9263 7.25172 12.6825L2.04859 7.62503H19.4411C19.7861 7.62503 20.0661 7.34503 20.0661 7.00003C20.0661 6.65503 19.7861 6.37503 19.4411 6.37503Z"
                                  fill="#514F5F"></path>
                        </svg>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="modal-form">
                        <ckeditor :editor="editor" v-model="editContent" :config="editorConfig" style="white-space:pre-line; height:100%;"></ckeditor>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" @click="updateNurturingTemplate">Save</button>
                </div>
            </div>
        </div>
    </div>


</template>
<script>
import {useAlertStore} from "../../stores/alert";
import {useClinicStore} from "../../stores/clinic";
import {useAuthStore} from "../../stores/auth";
import CKEditor from '@ckeditor/ckeditor5-vue';
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
import htmlEntities from 'he';
import UploadAdapter from '../../stores/UploadAdapter';

export default {
    setup() {
        const clinicStore = useClinicStore();

        const alertStore = useAlertStore();

        const authStore = useAuthStore();

        return {clinicStore, alertStore, authStore};
    },
    data() {
        return {
            general_details_editing: false,
            office_details_editing: false,
            staff_access_editing: false,
            media_content_editing: false,
            marketing_budget_editing: false,
            nurturing_template_editing: false,
            text_template_editing:false,
            email_template_editing:false,
            editTitle: '',
            editContent: '',
            dayInterval: '',
            editType: '',
            toggleStatus: false,
            editor: ClassicEditor,
            editorConfig: {
                extraPlugins: [this.uploader],
            },
            automationCampaign: [],
            statusNames: []
        };
    },
    components:{ckeditor: CKEditor.component},
    mounted() {
        let _self = this;
        const statusId = this.$route.params.id;
        this.clinicStore.find();
        this.clinicStore.getAutomationTemplates(statusId);
        this.clinicStore.getAutomationCampaign();
        this.statusNames = this.clinicStore.automationTemplates.map(template => template.status_name);
    },
    methods: {
        editNurturing(type, template, dayinterval) {
            this.editTitle = 'Day ' + dayinterval + ': ' + type;
            this.dayInterval = dayinterval;
            this.editContent = template;
            let arr = type.split(' ');
            this.editType = arr[0].toLowerCase() + '_' + arr[1].toLowerCase();
            $('#editNurturingModal').modal('show');
        },
        editEmailTextTemplate() {
            this.clinicStore.templateErrors = [];
            this.clinicStore.templateAction = 'edit';
            if (this.clinicStore.templateType == 'email') {
                this.clinicStore.template.template_name = this.clinicStore.selectedEmailTemplate.template_name;
                this.clinicStore.template.subject = this.clinicStore.selectedEmailTemplate.subject;
                this.clinicStore.template.body = this.clinicStore.selectedEmailTemplate.body;
            } else {
                this.clinicStore.template.template_name = this.clinicStore.selectedTextTemplate.template_name;
                this.clinicStore.template.subject = '';
                this.clinicStore.template.body = this.clinicStore.selectedTextTemplate.body;
            }
            $('#templateModal').modal('show');
        },
        addEmailTextTemplate() {
            this.clinicStore.templateErrors = [];
            this.clinicStore.templateAction = 'add';
            this.clinicStore.template = {template_name: '', subject: '', body: ''};
            $('#templateModal').modal('show');
        },
        updateNurturingTemplate() {
            let _self = this;
            const statusId = this.$route.params.id;

            let data = {
                content: this.editContent,
                type: this.editType,
                interval: this.dayInterval,
            };

            // Add statusId to the data object if it exists
            if (statusId) {
                data.statusId = statusId;
            }

            this.clinicStore.saveNurturingTemplate(data).then(function (response) {
                $('#editNurturingModal').modal('hide');
            });
        },
        updateEmailTextTemplate(type) {
            this.clinicStore.updateTemplate();
        },
        deleteTemplate() {
            this.clinicStore.deleteTemplate();
        },
        emailTabClicked(){
            this.clinicStore.templateType = 'email';
            this.clinicStore.getTemplates('email');
        },
        textTabClicked(){
            this.clinicStore.templateType = 'text';
            this.clinicStore.getTemplates('text');
        },
        handleToggleChange(value) {
            const clinicId = this.authStore.clinic_id;
            this.clinicStore.updateToggles(clinicId, value.id);
        },
        viewStatusAutomation(id){
			localStorage.setItem('last_path', '/crtx/automation/'+id);
			window.open('/crtx/automation/'+id, '_blank');
		},
        updateDayIntervalTemplate(template) {
            let _self = this;
            const statusId = this.$route.params.id;
            this.clinicStore.saveAutomationTemplate(template.id,template.text_template,template.email_subject,template.email_template, template.dayinterval,statusId || null).then(function (response) {

            });
        },
        htmlEntities(input) {
            return htmlEntities.decode(input);
        },
        uploader(editor)
        {
            editor.plugins.get( 'FileRepository' ).createUploadAdapter = ( loader ) => {
                return new UploadAdapter( loader );
            };
        },
        resetActiveTabs() {
            this.nurturing_template_editing = false;
        }

    },

};
</script>

import {defineStore} from 'pinia'
import axios from 'axios'
import router from '../routes'
import {useAuthStore} from '../stores/auth';
import {useAlertStore} from '../stores/alert';

export const useClinicStore = defineStore({
    id: 'clinic',
    state: () => ({
        errors: [],
        clinic: {},
        staff_emails: [],
        authStore: useAuthStore(),
        website_information_accurate: 'No',
        multiple_doctors: 'No',
        staff_error: false,
        resendMessage: '',
        alertStore: useAlertStore(),
        selectedClinic: null,
        allClinics: [],
        nurturingTemplates: [],
        automationTemplates:[],
        templateSaved: false,
        marketingdashboardurl: '',
        schedulemeetingurl: '',
        salestrainingurl: '',
        template: {clinic_id: null, user_id: null, type: '', template_name: '', subject: '', body: ''},
        templateType: '',
        templateAction: '',
        emailTemplates: [],
        textTemplates: [],
        appointmentTemplates: [],
        reminderEmailTemplates: [],
        selectedEmailTemplate: null,
        selectedTextTemplate: null,
        selectedAppointmentTemplate: null,
        selectedReminderEmailTemplate:null,
        selectedReminderTextTemplate:null,
        templateErrors: [],
        logoData:new FormData(),
        automationCampaign: [],
        immediateTemplates: [],
        immediateTemplatesToggle: {
            email: {
                isEnabled: false
            },
            sms: {
              isEnabled: false
            }
          },
        dns_records: [],
        multiple_clinics:localStorage.getItem('multiple_clinics'),
        whereby_link:localStorage.getItem('whereby_link'),
        assistant_id:localStorage.getItem('assistant_id'),
    }),
    getters: {
        //doubleCount: (state) => state.counter * 2
    },
    actions: {
        getConfig() {
            return {
                headers: {
                    headers: {
                        Accept: 'application/json',
                        Authorization: this.authStore.token_type + ' ' + this.authStore.token
                    }
                },
            }
        },
        find() {
            let _self = this;
            const config = this.getConfig();
            axios.post('/api/v1/clinic/list', {clinic_list: this.authStore.clinic_id}, config)
                .then(function (response) {
                    if (response.data.success) {
                        _self.clinic = response.data.data[0];
                        _self.website_information_accurate = (_self.clinic.website_information_detail == null) ? 'Yes' : 'No';
                        _self.multiple_doctors = (_self.clinic.multiple_doctors == null) ? 'No' : 'Yes';
                        _self.clinic.is_location_canada = 'No';
                        if (!_self.clinic.multiple_localtion_details) {
                            _self.clinic.locations = [];
                        } else {
                            _self.clinic.locations = _self.clinic.multiple_localtion_details;
                        }
                    } else {
                        //console.log(_self.errors);
                    }
                })
                .catch(function (error) {
                    console.log('error', error);
                });
        },
        save(section) {
            let _self = this;
            const config = this.getConfig();

            let data = {};

            if (section == 1) {

                data = {
                    section_name: 'general_details',
                    industry: this.clinic.industry,
                    website_url: this.clinic.website_url,
                    practice_name: this.clinic.practice_name,
                    practice_legal_name: this.clinic.practice_legal_name,
                    practice_email: this.clinic.practice_email,
                    phone: this.clinic.phone,
                    practice_specialty: this.clinic.practice_specialty,
                    practice_specialty_other: this.clinic.practice_specialty_other,
                    primary_services: this.clinic.primary_services,
                    primary_services_other: this.clinic.primary_services_other,
                    practice_management_system: this.clinic.practice_management_system,
                    practice_different: this.clinic.practice_different,
                    website_information_detail: (this.website_information_accurate == 'yes') ? '' : this.clinic.website_information_detail,
                    primary_doctor_firstname: this.clinic.primary_doctor_firstname,
                    primary_doctor_lastname: this.clinic.primary_doctor_lastname,
                    primary_doctor_email: this.clinic.primary_doctor_email,
                    primary_doctor_phone: this.clinic.primary_doctor_phone,
                    tracking_phone: this.clinic.tracking_phone,
                   // clinic_logo: this.clinic.clinic_logo, // Add this line
                    clinic_logo_name:this.clinic.clinic_logo_name,
                    nurturing_display_name: this.clinic.nurture_automation == 'Yes' ? this.clinic.nurturing_display_name : '',
                    scheduling_link: this.clinic.nurture_automation == 'Yes' ? this.clinic.scheduling_link : '',
                    link1: this.clinic.nurture_automation == 'Yes' ? this.clinic.link1 : '',
                    link2: this.clinic.nurture_automation == 'Yes' ? this.clinic.link2 : '',
                    link3: this.clinic.nurture_automation == 'Yes' ? this.clinic.link3 : '',
                }
            } else if (section == 2) {
                data = {
                    section_name: 'office_details',
                    office_name: this.clinic.office_name,
                    address: this.clinic.address,
                    town: this.clinic.town,
                    state: this.clinic.state,
                    zip_code: this.clinic.zip_code,
                    office_hours: this.clinic.office_hours,
                    holidays: this.clinic.holidays,
                    locations: JSON.stringify(this.clinic.locations),
                    multiple_doctors: (this.multiple_doctors == 'no') ? '' : this.clinic.multiple_doctors
                }
            } else if (section == 3) {
                data = {
                    section_name: 'staff_member_access',
                    new_staff_emails: this.staff_emails.toString()
                }
            } else if (section == 4) {
                data = {
                    section_name: 'media_content',
                    ai_website_url: this.clinic.ai_website_url,
                    professional_photography: this.clinic.professional_photography,
                    professional_video: this.clinic.professional_video,
                    quality_patient_after_photos: this.clinic.quality_patient_after_photos,
                    patient_tesimonial_videos: this.clinic.patient_testimonial_videos,
                    testimonial_link: this.clinic.testimonial_link,
                    google_my_business_review: this.clinic.google_my_business_review,
                    nurture_automation: this.clinic.nurture_automation,
                    doctors_biography: this.clinic.doctors_biography,
                    doctors_biography_description: this.clinic.doctors_biography == 'No' ? '' : this.clinic.doctors_biography_description,
                    doctors_biography_summary: this.clinic.doctors_biography_summary,
                    doctors_biography_summary_description: this.clinic.doctors_biography_summary == 'No' ? '' : this.clinic.doctors_biography_summary_description,
                    marketing_message: this.clinic.marketing_message,
                    marketing_message_description: this.clinic.marketing_message == 'No' ? '' : this.clinic.marketing_message_description,
                }
            } else if (section == 5) {
                if(!this.clinic.technology.includes('Other')){
                    this.clinic.technology_desc = ''
                }
                data = {

                    section_name: 'marketing_and_budget',
                    separate_marketing_company: this.clinic.separate_marketing_company,
                    paid_media: this.clinic.paid_media,
                    monthly_media_budget: this.clinic.monthly_media_budget,
                    primary_selling_message: this.clinic.primary_selling_message,
                    promotional_specials: this.clinic.promotional_specials,
                    promotional_specials_desc: this.clinic.promotional_specials_desc,
                    technology: this.clinic.technology,
                    listtechnology: this.clinic.technology_desc,
                    financing_options: this.clinic.financing_options,
                    financing_company_other: this.clinic.other_finance_company,
                    work_with_finance_company: this.clinic.work_with_finance_company,
                }
            }
            else {
                data = {
                    section_name: 'callrail_installtion_editing',
                    callrail_installation_script: this.clinic.callrail_installation_script,
                }
            }

            data.clinic_id = this.authStore.clinic_id;
            data.tracking_phone = this.clinic.tracking_phone;

            axios.post('/api/v1/clinic/update', data, config)
                .then(function (response) {
                    if (response.data.success) {

                        if (response.data.data && response.data.data.exist_emails.length > 0) {
                            _self.alertStore.success = false;
                            _self.alertStore.message = 'Provided email already exist!';
                            return false;
                        }
                        _self.staff_emails = [];
                        _self.find();
                        _self.alertStore.success = true;
                        _self.alertStore.message = response.data.message;
                    } else {
                        if (data.section_name == 'staff_member_access') {
                            _self.staff_error = true;
                        }
                        _self.errors = response.data.errors;
                        _self.alertStore.success = false;
                        _self.alertStore.message = response.data.errors;
                    }
                })
                .catch(function (error) {
                    if (data.section_name == 'staff_member_access') {
                        _self.staff_error = true;
                    }
                    console.log('error', error);
                });

            if(section == 1){
                let configMP =  {
                    headers: {
                        headers: {
                            Accept: 'application/json',
                            Authorization: this.authStore.token_type + ' ' + this.authStore.token,
                            ContentType: 'multipart/form-data'
                        }
                    },
                };
                axios.post('/api/v1/upload-clinic-logo', this.logoData, configMP)
                    .then(function (response) {
                        if (response.data.success) {
                            _self.find();
                        }
                    })
                    .catch(function (error) {
                        console.log('error', error);
                    });
            }

        },
        resendVerification(email) {
            let _self = this;
            const config = this.getConfig();
            axios
                .post("/api/v1/clinic/resend-token", {email: email, clinic_id: this.authStore.clinic_id}, config)
                .then(function (response) {
                    if (response.data.success) {
                        _self.alertStore.success = true;
                        _self.alertStore.message = response.data.message;

                        setTimeout(function () {
                            _self.resendMessage = '';
                        }, 5000)
                    } else {
                        //console.log(_self.errors);
                        _self.alertStore.success = false;
                        _self.alertStore.message = response.data.message;
                    }
                })
                .catch(function (error) {
                    console.log("error", error);
                });
        },
        deleteStaffMember(email) {
            let _self = this;
            const config = this.getConfig();
            axios
                .post("/api/v1/delete/staff", {email: email, clinic_id: this.authStore.clinic_id}, config)
                .then(function (response) {
                    if (response.data.success) {
                        _self.alertStore.success = true;
                        _self.alertStore.message = response.data.message;

                        setTimeout(function () {
                            _self.resendMessage = '';
                        }, 5000)
                    } else {
                        //console.log(_self.errors);
                        _self.alertStore.success = false;
                        _self.alertStore.message = response.data.message;
                    }
                })
                .catch(function (error) {
                    console.log("error", error);
                });
        },
        search(term = '', loading = false) {
            let _self = this;
            const config = this.getConfig();
            config.params = {loading: loading};
            axios
                .post("/api/v1/clinic/allList", {search: term}, config)
                .then(function (response) {
                    if (response.data.success) {
                        _self.allClinics = response.data.data;
                        localStorage.setItem('multiple_clinics', response.data.multiple_clinic? 1 : 0);

                        let localClinic = localStorage.getItem('clinic_id');
                        _self.allClinics.forEach(function (clinic) {
                            clinic.label = clinic.practice_name + ' - ' + clinic.dr_name;
                            if (localClinic == clinic.id) {
                                _self.selectedClinic = clinic;
                            }
                        });
                    } else {

                    }
                })
                .catch(function (error) {
                    console.log("error", error);
                });
        },
        getNurturingTemplates() {
            let _self = this;
            const config = this.getConfig();
            axios
                .post("/api/v1/get_nurturing_template", {clinic_id: this.authStore.clinic_id}, config)
                .then(function (response) {
                    if (response.data.success) {
                        _self.nurturingTemplates = response.data.nurturing_templates;
                    } else {

                    }
                })
                .catch(function (error) {
                    console.log("error", error);
                });
        },
        async saveNurturingTemplate(data) {
            let _self = this;
            const config = this.getConfig();

            let postData = {
                clinicid: this.authStore.clinic_id,
                dayinterval: data.interval,
                statusId: data.statusId,
                type: data.type,
                content: data.content
            };

            if (data.email_subject) {
                postData.email_subject = data.email_subject;
            }

            axios
                .post("/api/v1/managetemplate", postData, config)
                .then(function (response) {
                    if (response.data.success) {
                        _self.templateSaved = true;
                        _self.alertStore.success = true;
                        _self.alertStore.message = response.data.message;
                        if(postData.statusId)
                        {
                            _self.getAutomationTemplates(postData.statusId);
                        }else{
                            if(postData.dayinterval == 0){
                                _self.getImmediateTemplates();
                            }else{
                                _self.getNurturingTemplates();
                            }

                        }

                    } else {
                        _self.templateSaved = false;
                    }
                })
                .catch(function (error) {
                    _self.templateSaved = false;
                });
        },
        async getTemplates(type) {
            let _self = this;
            const config = this.getConfig();
            axios
                .get("/api/v1/templates", {params: {clinic_id: this.authStore.clinic_id, type: type}}, config)
                .then(function (response) {
                    if (response.data.success) {
                        if (type === 'email') {
                            _self.emailTemplates = response.data.templates;
                            if (!_self.emailTemplates.find(template => template.id === _self.selectedEmailTemplate?.id)) {
                                _self.selectedEmailTemplate = _self.emailTemplates.length > 0 ? _self.emailTemplates[0] : null;
                            }
                        } else if (type === 'text') {
                            _self.textTemplates = response.data.templates;
                            if (!_self.textTemplates.find(template => template.id === _self.selectedTextTemplate?.id)) {
                                _self.selectedTextTemplate = _self.textTemplates.length > 0 ? _self.textTemplates[0] : null;
                            }
                        } else if (type === 'appointment') {
                            _self.appointmentTemplates = response.data.templates;
                            if (!_self.appointmentTemplates.find(template => template.id === _self.selectedAppointmentTemplate?.id)) {
                                _self.selectedAppointmentTemplate = _self.appointmentTemplates.length > 0 ? _self.appointmentTemplates[0] : null;
                            }
                        }else if (type === 'reminder-email') {
                            _self.reminderEmailTemplates = response.data.templates;
                            if (!_self.reminderEmailTemplates.find(template => template.id === _self.selectedReminderEmailTemplate?.id)) {
                                _self.selectedReminderEmailTemplate = _self.reminderEmailTemplates.length > 0 ? _self.reminderEmailTemplates[0] : null;
                            }
                        }else if (type === 'reminder-text') {
                            _self.reminderTextTemplates = response.data.templates;
                            if (!_self.reminderTextTemplates.find(template => template.id === _self.selectedReminderTextTemplate?.id)) {
                                _self.selectedReminderTextTemplate = _self.reminderTextTemplates.length > 0 ? _self.reminderTextTemplates[0] : null;
                            }
                        }
                    } else {
                        // Handle the case where response.data.success is false
                    }
                })
                .catch(function (error) {
                    console.log("error", error);
                });
        },
        updateTemplate() {
            let _self = this;
            const config = this.getConfig();
            let url = '';
            if (_self.templateAction == 'add') {
                url = '/api/v1/templates/store';
            } else {
                if (this.templateType === 'email') {
                    url = '/api/v1/templates/update/' + this.selectedEmailTemplate.id;
                } else if (this.templateType === 'text') {
                    url = '/api/v1/templates/update/' + this.selectedTextTemplate.id;
                } else if (this.templateType === 'appointment') {
                    url = '/api/v1/templates/update/' + this.selectedAppointmentTemplate.id;
                } else if(this.templateType == 'reminder-email'){
                    url = '/api/v1/templates/update/' + this.selectedReminderEmailTemplate.id;
                }
            }
            this.template.clinic_id = this.authStore.clinic_id;
            this.template.user_id = this.authStore.user.id;
            this.template.type = this.templateType;
            if (this.templateType === 'email') {
                this.template.body = this.template.body.split("\n").join("<br />");
            }

            axios
                .post(url, this.template, config)
                .then(function (response) {
                    if (response.data.success) {
                        _self.alertStore.success = true;
                        _self.alertStore.message = response.data.message;
                        _self.getTemplates(_self.templateType);
                        $('#templateModal').modal('hide');

                        // if(_self.templateType == 'email'){
                        //     _self.selectedEmailTemplate.template_name = response.data.template.template_name;
                        //     _self.selectedEmailTemplate.subject = response.data.template.subject;
                        //     _self.selectedEmailTemplate.body = response.data.template.body;
                        // }else{
                        //     _self.selectedTextTemplate.template_name = response.data.template.template_name;
                        //     _self.selectedTextTemplate.body = response.data.template.body;
                        // }
                        // Set the selected template to the newly added or updated template
                        if (_self.templateAction == 'add') {
                            if (_self.templateType === 'email') {
                                _self.selectedEmailTemplate = response.data.template;
                                 // Delayed click event on the radio button associated with the new template
                                setTimeout(() => {
                                    let radioButtonId = 'emailTemplate' + response.data.template.id;
                                    let radioButton = document.getElementById(radioButtonId);
                                    if (radioButton) {
                                        radioButton.click();
                                    }
                                }, 1000);
                            } else {
                                _self.selectedTextTemplate = response.data.template;
                                setTimeout(() => {
                                    let radioButtonId = 'textTemplate' + response.data.template.id;
                                    let radioButton = document.getElementById(radioButtonId);
                                    if (radioButton) {
                                        radioButton.click();
                                    }
                                }, 1000);
                            }
                        }
                    } else {
                        _self.alertStore.success = false;
                        _self.templateErrors = response.data.errors;
                    }
                })
                .catch(function (error) {
                    console.log("error", error);
                });
        },
        updateappointmentEmailTemplate(data) {
            let _self = this;
            const config = this.getConfig();
            let url = '';

            // Determine API URL based on the action and template type
            if (data.id == null) { // Check for both null and undefined
                url = '/api/v1/templates/store'; // If data.id is null or undefined
            } else {
                url = '/api/v1/templates/update/' + data.id; // If data.id exists
            }

            // Prepare the template payload dynamically from the data parameter
            let templatePayload = {
                clinic_id: this.authStore.clinic_id,
                user_id: this.authStore.user.id,
                type: data.type,
                template_name: data.template_name,
                subject: data.subject || '', // Use an empty string if 'subject' is undefined
                body: data.body.split("\n").join("<br />"), // Convert newlines to <br />
            };

            axios
                .post(url, templatePayload, config)
                .then(function (response) {
                    if (response.data.success) {
                        _self.alertStore.success = true;
                        _self.alertStore.message = response.data.message;
                        _self.getTemplates(data.type);
                        //$('#templateModal').modal('hide');

                        // Update selected template based on type
                        if (data.type === 'appointment') {
                            _self.selectedAppointmentTemplate = response.data.template;
                        }else if(data.type === 'reminder-email'){
                            _self.selectedReminderEmailTemplate = response.data.template;
                        }else if(data.type === 'reminder-text'){
                            _self.selectedReminderTextTemplate = response.data.template;
                        }
                    } else {
                        _self.alertStore.success = false;
                        _self.templateErrors = response.data.errors;
                    }
                })
                .catch(function (error) {
                    console.log("Error saving template:", error);
                });
        },
        deleteTemplate(){
            let _self = this;
            const config = this.getConfig();
            let url = '';
            if (this.templateType == 'email')
                url = '/api/v1/templates/delete/' + this.selectedEmailTemplate.id;
            else
                url = '/api/v1/templates/delete/' + this.selectedTextTemplate.id;

            axios
                .post(url, {}, config)
                .then(function (response) {
                    if (response.data.success) {
                        _self.alertStore.success = true;
                        _self.alertStore.message = response.data.message;
                        _self.getTemplates(_self.templateType);
                        $('#DeleteTemplateModal').modal('hide');
                    } else {
                        _self.alertStore.success = false;
                        _self.templateErrors = response.data.errors;
                    }
                })
                .catch(function (error) {
                    console.log("error", error);
                });
        },
        getAutomationCampaign() {
            let _self = this;
            const config = this.getConfig();
            axios
                .post("/api/v1/get-automation-campaign", { clinic_id: this.authStore.clinic_id }, config)
                .then(function(response) {
                    if (response.data.statuses) {
                        // Assuming the updated automation_campaign data is stored in response.data.data
                        _self.automationCampaign = response.data.statuses;

                    } else {
                        // Handle error or other scenarios
                        _self.alertStore.success = false;
                       // _self.templateErrors = response.data.errors;
                    }
                })
                .catch(function(error) {
                    console.log("error", error);
                });
        },
        updateToggles(clinicId, key) {
            let _self = this;
            // Make an API call to update the toggle based on clinicId and key
            const config = this.getConfig();
            axios
                .post("/api/v1/update-toggles", { clinic_id: clinicId, key: key }, config)
                .then(function(response) {
                    if (response.data.success) {
                        // Assuming the updated automation_campaign data is stored in response.data.data
                        // You can update the local data accordingly
                        _self.automationCampaign = response.data.data;
                        // Optionally, you can show a success message or perform any other actions
                    } else {
                        // Handle error or other scenarios
                        _self.alertStore.success = false;
                        // _self.templateErrors = response.data.errors;
                    }
                })
                .catch(function(error) {
                    console.log("error", error);
                });
        },
        getAutomationTemplates(statusId) {
            let _self = this;
            const config = this.getConfig();
            axios
                .post("/api/v1/get_automation_template", {clinicid: this.authStore.clinic_id,status_id:statusId}, config)
                .then(function (response) {
                    if (response.data.success) {
                        _self.automationTemplates = response.data.nurturing_templates;
                    } else {
                        _self.alertStore.success = false;
                    }
                })
                .catch(function (error) {
                    console.log("error", error);
                });
        },
        getImmediateTemplates(statusId) {
            let _self = this;
            const config = this.getConfig();
            axios
                .post("/api/v1/get_immediate_template", {clinicid: this.authStore.clinic_id,status_id:statusId}, config)
                .then(function (response) {
                    if (response.data.success) {
                        _self.immediateTemplates = response.data.nurturing_templates;
                    } else {
                        _self.alertStore.success = false;
                    }
                })
                .catch(function (error) {
                    console.log("error", error);
                });
        },
        async saveAutomationTemplate(id,text_template, email_subject, email_template, interval,statusId) {
            let _self = this;
            const config = this.getConfig();
            axios
                .post("/api/v1/manageautomationTemplate", {
                    clinicid: this.authStore.clinic_id,
                    dayinterval: interval,
                    status_id:statusId,
                    text_template: text_template,
                    email_subject: email_subject,
                    email_template: email_template,
                    id:id
                }, config)
                .then(function (response) {
                    if (response.data.success) {
                        _self.templateSaved = true;
                        _self.alertStore.success = true;
                        _self.alertStore.message = response.data.message;
                        if(statusId)
                        {
                            _self.getAutomationTemplates(statusId);
                        }else{
                            _self.getNurturingTemplates();
                        }

                    } else {
                        _self.templateSaved = false;
                    }
                })
                .catch(function (error) {
                    _self.templateSaved = false;
                    console.log("error", error);
                });
        },
        getToggleValues() {
            let _self = this;
            const config = this.getConfig();
            // Make a GET request to your API endpoint to fetch toggle values
            axios
                .get(`/api/v1/getToggleValues?clinic_id=${this.authStore.clinic_id}`, config)
                .then(function(response) {
                    if (response.data) {
                        // Assuming the toggle values are stored in response.data
                        const { immediate_sms, immediate_mail } = response.data;
                        _self.immediateTemplatesToggle.email.isEnabled = parseInt(immediate_mail);
                        _self.immediateTemplatesToggle.sms.isEnabled = parseInt(immediate_sms);
                        // Optionally, you can show a success message or perform any other actions
                    } else {
                        // Handle error or other scenarios
                        _self.alertStore.success = false;
                        // _self.templateErrors = response.data.errors;
                    }
                })
                .catch(function(error) {
                    console.error('Error fetching toggle values:', error);
                });
        },
        toggleTemplateStatus(templateType) {
            let _self = this;
            const config = this.getConfig();
            let isActive;

            if (templateType === 'email') {
                isActive = this.immediateTemplatesToggle.email.isEnabled;
              } else if (templateType === 'sms') {
                isActive = this.immediateTemplatesToggle.sms.isEnabled;
              }

            // Call your API to update the template status here
            axios
                .post('/api/v1/toggleimmediateTemplateStatus', {
                    id: this.authStore.clinic_id,
                    isActive: isActive,
                    templateType: templateType
                }, config)
                .then(function(response) {
                    if (response.data.success) {
                        // Optionally update local data or show a success message
                        _self.alertStore.success = true;
                    } else {
                        // Handle error or other scenarios
                        _self.alertStore.success = false;
                        // _self.templateErrors = response.data.errors;
                    }
                })
                .catch(function(error) {
                    console.error('Error toggling template status:', error);
                });
        },
        async fetchDomainVerificationStatus() {
            try {
                const id = this.authStore.clinic_id;
                if (id) {
                    const response = await axios.get(`/api/v1/clinics/verify/${id}`);
                    return response.data.domain_verification;
                } else {
                    console.error('Clinic ID is not defined');
                }
            } catch (error) {
                console.error(error);
            }
        },

    }
})

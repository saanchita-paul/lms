import { defineStore } from "pinia";
import axios from "axios";
import router from "../routes";
import {useAlertStore} from "./alert";
import {toRaw} from "vue";

export const useAuthStore = defineStore({
    id: "auth",
    state: () => ({
        alertStore: useAlertStore(),
        errors: [],
        setupErrors: [],
        changePasswordErrors: [],
        serverError: false,
        setupMessage: "",
		forgotPWMessage: "",
        forgotPWError : false,
        resetPWMessage: "",
        resetPWError : false,
        registrationEmailSent: false,
        profileUpdateSuccess : false,
        profileUpdateMessage : false,
        profileData:new FormData(),
        form: {
            user_id:null,
            first_name: null,
            last_name: null,
            admin_phone: null,
            degree_abbreviation: null,
            degree_abbreviation_other: null,
            email: null,
            password: null,
            confirm_password: null,
            industry: 'Dental',
            website_url: null,
            ai_website_url: null,
            practice_name: '',
            practice_legal_name: '',
            practice_email: null,
            office_name: null,
            address: null,
            town: null,
            state: "",
            zip_code: null,
            multiple_locations: null,
            multiple_location_details:[],
            is_location_canada:'No',
            phone: null,
            office_hours: null,
            // holidays: null,
            multiple_doctors: null,
            multiple_doctors_details: null,
            practice_speciality: [],
            practice_specialty_other: '',
            primary_services: [],
            primary_services_other: null,
            staff_emails: null,
            website_information_accurate: null,
            // website_information_detail: null,
            practice_management_system: null,
            practice_different: null,
            website_type: null,
            microsite_url: null,
            professional_photography: null,
            professional_video: null,
            quality_patient: null,
            testimonial_links: null,
            testimonial_video_urls: null,
            google_business_page: null,
            marketing_company: null,
            paid_media: [],
            media_budget: 0,
            primary_selling_message: null,
            pricing_promotional: null,
            pricing_promotional_details: null,
            technologies: [],
            technologies_other: null,
            work_financing_company: null,
            financing_company: [],
            financing_company_other: null,

            dr_fullname: null,
            doctors_biography: 'No',
            doctors_biography_description: null,
            doctors_biography_summary : 'No',
            doctors_biography_summary_description : null,
            marketing_message: 'No',
            marketing_message_description:null,
        },
        setupForm: {
            first_name: null,
            last_name: null,
            email: null,
            password: null,
            password_confirmation: null,
            term: 0,
        },
        changePassword : '',
        changeConfirmPassword : '',
        fullname: "",
        ai_shown: false,
        sidebarVisible: window.matchMedia("(min-width: 991px)").matches,
        searchVisible: window.matchMedia("(min-width: 991px)").matches,
        clinic_id: localStorage.getItem("clinic_id"),
        inbox_id: localStorage.getItem("inbox_id") ? JSON.parse(localStorage.getItem("inbox_id")) : null,
        clinic_name: JSON.parse(localStorage.getItem("clinic_name")),
        user: JSON.parse(localStorage.getItem("user")),
        profileUser:JSON.parse(localStorage.getItem("user")),
        role:localStorage.getItem("role"),
        multiple_clinics:localStorage.getItem('multiple_clinics'),
        token: localStorage.getItem("token"),
        token_type: localStorage.getItem("token_type"),
        requires2FA: false,
        twoFactorType: null,
        tempToken: null,
        verificationError: null,
        settings: JSON.parse(localStorage.getItem("settings")),
        whereby_link: localStorage.getItem("whereby_link") !== "undefined" ? localStorage.getItem("whereby_link") : null,
        assistant_id: localStorage.getItem("assistant_id") !== "undefined" ? localStorage.getItem("assistant_id") : null,
    }),
    getters: {},
    actions: {
        signup() {
            let _self = this;
            _self.errors = [];

            if(this.user)
                this.form.user_id = this.user.id;

            axios
                .post("/api/v1/clinic/store", this.form)
                .then(function (response) {
                    //console.log(response);
                    if (response.data.success) {

                        _self.fullname = response.data.data.fullname;
                        localStorage.setItem('new_clinic_id', response.data.data.id);

                        const newClinicId = localStorage.getItem('new_clinic_id');

                        if(newClinicId){
                            router.push('/crtx/ai/learn');
                        }else{
                            if(!_self.user){
                                router.push('/crtx/ai/learn');
                            }else{
                                if(response.data.data.ai_complete){
                                    router.push("/crtx/ai/finish")
                                }else {
                                    _self.alertStore.success = true;
                                    _self.alertStore.message = 'Practice added successfully!';
                                    router.push("/crtx/manage-practice");
                                }
                            }
                        }



                        Object.keys(_self.form).map((key) => {
                            if (_self.form[key] instanceof Array)
                                _self.form[key] = [];
                            else _self.form[key] = undefined;
                        });

                        _self.form.industry = 'Dental';
                        _self.form.doctors_biography = 'No';
                        _self.form.doctors_biography_summary = 'No';
                        _self.form.marketing_message = 'No';
                    } else {
                        //_self.errors = response.data.errors
                        _self.errors = Object.entries(response.data.errors).map(
                            (arr) => arr[1]
                        );

                        router.push("/crtx/account/step-1");
                    }
                })
                .catch(function (error) {
                    _self.serverError = true;
                    console.log("error", error);
                });
        },

        async login(form) {
            let _self = this;
            _self.errors = [];

            try {
                const response = await axios.post("/api/v1/login", form);

                if (response.data.success) {
                    // Set common user data regardless of 2FA
                    if(response.data.user && response.data.user.phone) {
                        let phone = response.data.user.phone.replace('+1', '');
                        response.data.user.phone = phone;
                    }

                    _self.user = response.data.user;
                    _self.profileUser = Object.assign({}, response.data.user);
                    _self.clinic_id = response.data.clinicId;
                    _self.whereby_link = response.data.whereby_link;
                    _self.assistant_id = response.data.assistant_id;
                    _self.clinic_name = response.data.clinic_data.clinic_name;
                    _self.inbox_id = response.data.clinic_data.inbox_id;
                    _self.role = response.data.role;
                    _self.multiple_clinics = response.data.multiple_clinic ? 1 : 0;

                    // Store in localStorage
                    localStorage.setItem("user", JSON.stringify(response.data.user));
                    localStorage.setItem("parent_id", JSON.stringify(response.data.user.parent_id));
                    localStorage.setItem("clinic_id", response.data.clinicId);
                    localStorage.setItem("whereby_link", response.data.whereby_link);
                    localStorage.setItem("assistant_id", response.data.assistant_id);
                    localStorage.setItem("inbox_id", JSON.stringify(response.data.clinic_data.inbox_id));
                    localStorage.setItem("clinic_name", JSON.stringify(response.data.clinic_data.clinic_name));
                    localStorage.setItem("token", response.data.token);
                    localStorage.setItem("token_type", response.data.token_type);
                    localStorage.setItem('role', response.data.role);
                    localStorage.setItem('multiple_clinics', response.data.multiple_clinic ? 1 : 0);

                    // Handle settings
                    if(response.data.settings) {
                        _self.settings = {followUpEmailNotification: response.data.settings.follow_up_email_notification, leadReconnectingEmailNotification: response.data.settings.lead_reconnecting_email_notification, followUpTextNotification: response.data.settings.follow_up_text_notification,
                            leadReconnectingTextNotification: response.data.settings.lead_reconnecting_text_notification, followUpBrowserNotification: response.data.settings.follow_up_browser_notification, leadReconnectingBrowserNotification: response.data.settings.lead_reconnecting_browser_notification, appointmentEmailNotification: response.data.settings.appointment_email_notification,
                            dailySummaryEmailNotification: response.data.settings.daily_summary_email_notification, weeklySummaryEmailNotification: response.data.settings.weekly_summary_email_notification,
                            appointmentTextNotification: response.data.settings.appointment_text_notification, appointmentBrowserNotification: response.data.settings.appointment_browser_notification,

                            wherebyEmailNotification: response.data.settings.whereby_email_notification,
                            wherebyTextNotification: response.data.settings.whereby_text_notification,
                            wherebyBrowserNotification: response.data.settings.whereby_browser_notification,

                            doNotDisturb: response.data.settings.do_not_disturb};
                        localStorage.setItem('settings', JSON.stringify(_self.settings));
                    } else {
                        // If setting is null
                        _self.settings = JSON.parse(localStorage.getItem('settings'));
                        if(!_self.settings){
                            _self.settings = {followUpEmailNotification: 1, leadReconnectingEmailNotification: 1, followUpTextNotification: 1, leadReconnectingTextNotification: 1, followUpBrowserNotification: 1, leadReconnectingBrowserNotification: 1,
                                appointmentEmailNotification: 1, appointmentTextNotification: 1, appointmentBrowserNotification: 1, wherebyEmailNotification: 1,
                                wherebyTextNotification: 1,
                                wherebyBrowserNotification: 1,
                                dailySummaryEmailNotification: 1, weeklySummaryEmailNotification: 1,
                                doNotDisturb: 0};
                            localStorage.setItem('settings', JSON.stringify(_self.settings));
                        }
                    }

                    // Check for 2FA after setting user data
                    if (response.data.requires_2fa) {
                        _self.requires2FA = true;
                        _self.twoFactorType = response.data.two_factor_type;
                        _self.tempToken = response.data.token;
                        return response.data;
                    }

                    // Handle routing only if not requiring 2FA
                    if(response.data.clinic_data.ai_complete == 0) {
                        _self.ai_shown = true;
                        _self.form.clinic_id = response.data.clinicId;
                        router.push("/crtx/ai/step-1");
                    } else {
                        router.push("/crtx/dashboard?view=list&page=sms");
                    }
                } else {
                    // Handle unsuccessful login response
                    _self.errors.push(response.data.message || "Login was unsuccessful");
                }
            } catch (error) {
                console.log("error", error);

                if (error.response) {
                    // Handle different error responses
                    const { status, data } = error.response;

                    switch (status) {
                        case 422: // Validation errors
                            if (data.errors) {
                                Object.values(data.errors).forEach(errorMessages => {
                                    _self.errors.push(...errorMessages);
                                });
                            } else {
                                _self.errors.push(data.message || "Validation failed");
                            }
                            break;

                        case 401: // Unauthorized
                            _self.errors.push("Invalid credentials");
                            break;

                        case 429: // Too many attempts
                            _self.errors.push("Too many login attempts. Please try again later.");
                            break;

                        case 503: // Service unavailable
                            _self.errors.push("Service is temporarily unavailable. Please try again later.");
                            break;

                        default:
                            _self.errors.push(data.message || "An unexpected error occurred");
                    }
                } else if (error.request) {
                    // Network error (request made but no response)
                    _self.errors.push("Network error. Please check your connection.");
                } else {
                    // Something else went wrong
                    _self.errors.push("An error occurred during login");
                }
            }

            // Return the errors so the component can handle them
            return {
                success: false,
                errors: _self.errors
            };
        },

        async verify2FA(code) {
            try {
                const response = await axios.post("/api/v1/2fa/verify",
                    { code },
                    {
                        headers: {
                            Authorization: `Bearer ${this.tempToken}`
                        }
                    }
                );

                if (response.data.success) {
                    this.completeLogin(response.data);
                    return true;
                } else {
                    this.verificationError = response.data.message;
                    return false;
                }
            } catch (error) {
                console.error("2FA Verification Error:", error.response?.data || error);
                this.verificationError = error.response?.data?.message || "Verification failed";
                return false;
            }
        },

        // New method to handle login completion
        completeLogin(data) {
            this.user = data.user;
            this.clinic_id = data.clinicId;
            this.clinic_name = data.clinic_data.clinic_name;
            this.inbox_id = data.clinic_data.inbox_id;
            this.role = data.role;
            this.multiple_clinics = data.multiple_clinic ? 1 : 0;

            localStorage.setItem("user", JSON.stringify(data.user));
            localStorage.setItem("clinic_id", data.clinicId);
            localStorage.setItem("inbox_id", JSON.stringify(data.clinic_data.inbox_id));
            localStorage.setItem("clinic_name", JSON.stringify(data.clinic_data.clinic_name));
            localStorage.setItem("token", data.token);
            localStorage.setItem("token_type", data.token_type);
            localStorage.setItem('role', data.role);
            localStorage.setItem('multiple_clinics', data.multiple_clinic ? 1 : 0);

            // Reset 2FA state
            this.requires2FA = false;
            this.tempToken = null;
            this.verificationError = null;

            if (data.clinic_data.ai_complete == 0) {
                this.ai_shown = true;
                this.form.clinic_id = data.clinicId;
                router.push("/crtx/ai/step-1");
            } else {
                router.push("/crtx/dashboard?view=list&page=sms");
            }
        },

        async resend2FACode() {
            try {
                const response = await axios.post("/api/v1/2fa/generate", {}, {
                    headers: {
                        Authorization: `Bearer ${this.tempToken}`
                    }
                });
                return response.data.success;
            } catch (error) {
                this.verificationError = "Failed to resend code";
                return false;
            }
        },

        logout() {
            let _self = this;
            const config = {
                headers: {
                    headers: {
                        Accept: "application/json",
                        Authorization: this.token_type + " " + this.token,
                    },
                },
            };

            axios
                .get("/api/v1/logout", {}, config)
                .then(function (response) {
                    //console.log(response);
                    _self.$reset();
                    localStorage.clear();
                    location.reload();
                })
                .catch(function (error) {
                    _self.$reset();
                    localStorage.clear();
                    location.reload();
                });
        },

        setup() {
            let _self = this;
            _self.errors = [];
            this.setupForm.staff_emails = this.setupForm.email;
            axios
                .post("/api/v1/store/staff", this.setupForm)
                .then(function (response) {
                    //console.log(response);
                    if (response.data.success) {
                        _self.fullname =
                            _self.setupForm.first_name +
                            " " +
                            _self.setupForm.last_name;

                        Object.keys(_self.setupForm).map((key) => {
                            if (_self.form[key] instanceof Array)
                                _self.form[key] = [];
                            else _self.form[key] = undefined;
                        });

                        router.push("/crtx/ai/finish");
                    } else {
                        //_self.errors = response.data.errors
                        // _self.setupErrors = Object.entries(response.data.errors).map((arr) => arr[1]);
                        //  _self.errors = Object.entries(response.data.errors).map((arr) => ({
                        //     fieldName: arr[0],
                        //     message: arr[1],
                        //   }));

                        _self.setupMessage = response.data.message;

                        for (const [key, value] of Object.entries(
                            response.data.errors
                        )) {
                            _self.setupErrors[key] = value;
                        }
                        console.log(_self.setupErrors);
                    }
                })
                .catch(function (error) {
                    _self.serverError = true;
                    _self.setupMessage = error.data.message;
                    console.log("error", error);
                });
        },

        forgotpw(form) {
            let _self = this;
            axios
                .post("/api/v1/forgotPassword", form)
                .then(function (response) {
                    _self.forgotPWMessage = response.data.message;
                    _self.forgotPWError = !response.data.success;
                })
                .catch(function (error) {
                    console.log(error.code);
                    console.log('error', error);
                });
        },

        resetpw(form) {
            let _self = this;
            axios
                .post("/api/v1/resetPassword", form)
                .then(function (response) {
                    if(response.data) {
                        _self.resetPWMessage = response.data.message;
                        _self.resetPWError = !response.data.success;
                    }
                    if(response.data.success){
                        $('.filled').val('');
                        setTimeout(function(){
                            router.push('/crtx/account/signin');
                        },2000);
                    }
                })
                .catch(function (error) {
                    console.log('error', error);
                });
        },

        sendRegistrationDetails(){
            let _self = this;
            axios
                .post("/api/v1/clinic/registrationEmail", this.form)
                .then(function (response) {
                    _self.registrationEmailSent = response.data.success;
                })
                .catch(function (error) {
                    console.log(error.code);
                    console.log('error', error);
                });
        },

        sendAIDetails(){
            let _self = this;
            this.form.new_clinic_id = localStorage.getItem('new_clinic_id');
            axios
                .post("/api/v1/clinic/ai-setup-email", this.form)
                .then(function (response) {
                    console.log(response);
                    // Do nothing
                })
                .catch(function (error) {
                    console.log('error', error);
                });
        },

        updateProfile(section) {
            let _self = this;
            const config = {
                headers: {
                    headers: {
                        Accept: "application/json",
                        Authorization: this.token_type + " " + this.token,
                        ContentType: 'multipart/form-data'
                    },
                },
            };
            if(section == 1){
                axios
                .post("/api/v1/updateprofile", this.profileData, config)
                .then(function (response) {
                    _self.alertStore.success = response.data.success;
                    _self.alertStore.message = response.data.message;
                    if(response.data.success){
                        if(response.data.user.phone){
                            let phone = response.data.user.phone.replace('+1', '');
                            response.data.user.phone = phone
                        }
                        _self.user = response.data.user;
                        _self.profileUser = Object.assign({}, response.data.user);
                        localStorage.setItem('user', JSON.stringify(response.data.user));
                    }
                })
                .catch(function (error) {
                    console.log('error', error);
                });
            }else if(section == 2){
                axios
                .post("/api/v1/changepassword", {password:this.changePassword, password_confirmation:this.changeConfirmPassword}, config)
                .then(function (response) {
                    _self.alertStore.success = response.data.success;
                    _self.alertStore.message = response.data.message;
                    if(response.data.success){
                        let user = JSON.parse(localStorage.getItem('user'));
                        user.name = _self.user.name;
                        localStorage.setItem('user', JSON.stringify(user));
                        _self.changePassword = '';
                        _self.changeConfirmPassword = '';
                    }
                })
                .catch(function (error) {
                    console.log('error', error);
                });
            }else{
                axios
                    .post("/api/v1/updatesettings", this.settings, config)
                    .then(function (response) {
                        _self.alertStore.success = response.data.success;
                        _self.alertStore.message = response.data.message;
                        if(response.data.success){
                            _self.settings = {followUpEmailNotification: response.data.settings.follow_up_email_notification, leadReconnectingEmailNotification: response.data.settings.lead_reconnecting_email_notification, followUpTextNotification: response.data.settings.follow_up_text_notification,
                                leadReconnectingTextNotification: response.data.settings.lead_reconnecting_text_notification, followUpBrowserNotification: response.data.settings.follow_up_browser_notification, leadReconnectingBrowserNotification: response.data.settings.lead_reconnecting_browser_notification, appointmentEmailNotification: response.data.settings.appointment_email_notification,
                                dailySummaryEmailNotification: response.data.settings.daily_summary_email_notification, weeklySummaryEmailNotification: response.data.settings.weekly_summary_email_notification,
                                appointmentTextNotification: response.data.settings.appointment_text_notification, appointmentBrowserNotification: response.data.settings.appointment_browser_notification,

                                wherebyEmailNotification: response.data.settings.whereby_email_notification,
                                wherebyTextNotification: response.data.settings.whereby_text_notification,
                                wherebyBrowserNotification: response.data.settings.whereby_browser_notification,

                                doNotDisturb: response.data.settings.do_not_disturb};
                            localStorage.setItem('settings', JSON.stringify(_self.settings));

                            setTimeout(function(){
                                localStorage.setItem('last_path', '/crtx/user-profile/notifications')
                                location.reload();
                            }, 1000);
                        }
                    })
                    .catch(function (error) {
                        console.log('error', error);
                    });
            }
        },

        titleCase(str) {
            var splitStr = str.toLowerCase().split('_');
            for (var i = 0; i < splitStr.length; i++) {
                // You do not need to check if i is larger than splitStr length, as your for does that for you
                // Assign it back to the array
                splitStr[i] = splitStr[i].charAt(0).toUpperCase() + splitStr[i].substring(1);
            }
            // Directly return the joined string
            return splitStr.join(' ');
         },
    },
});

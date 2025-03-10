import {defineStore} from 'pinia'
import axios from 'axios'
import router from '../routes'
import {useAuthStore} from '../stores/auth';
import {useAlertStore} from '../stores/alert';
import {useAppointmentStore} from "./appointment";
import {useDashboardStore} from "./dashboard";

export const useConsultationStore = defineStore({
    id: 'consultation',
    state: () => ({
        quickConsultationBooked: false,
        errors: [],
        errorMessage: '',
        leads: [],
        authStore: useAuthStore(),
        alertStore: useAlertStore(),
        appointmentStore: useAppointmentStore(),
        dashboardStore: useDashboardStore(),
        deletedLeads: false,
        lead: {
            status_id: null,
            source_id: null,
            phone_form: null,
            first_name: null,
            last_name: null,
            phone: null,
            email: null,
            dob: null,
            city: null,
            description: null,
            phone_verified: 0,
            email_verified: 0,
            badge: null,
            clinic_id: null
        },
        last_page: 0,
        page: 1,
        perPage: 10,
        query: '',
        status_id: 12,
        stageCounts: [],
        sort_by: 'Created At',
        sort_order: 'desc',
        hover_on: '',
        categories: [
            {name: 'Full Name', checked: true},
            {name: 'Email', checked: true},
            {name: 'Source',checked: true},
            {name: 'Lead Type', checked: true},
            {name: 'Phone', checked: true},
            {name: 'Treatment Amount',checked: true},
            {name: 'Date of Birth', checked: false},
            {name: 'Consultation Booked Date',checked: true},
            {name: 'Lead Score', checked: true},
            {name: 'Tags', checked: false},
            { name: "Landing Page", checked: false }
        ]
    }),
    getters: {},
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
        count() {
            let _self = this;
            const config = this.getConfig();

            axios.get('/api/v1/getStageCount', {
                params: {
                    clinic_id: this.authStore.clinic_id,
                    deleted_leads: this.deletedLeads ? 'yes' : 'no'
                }
            }, config)
                .then(function (response) {
                    //console.log('stage response', response);
                    if (response.data.success) {
                        _self.stageCounts = response.data.data;
                        //console.log('stage counts', _self.stageCounts);
                    }
                })
                .catch(function (error) {
                    console.log('error', error);
                });
        },
        list(loading = true) {
            let _self = this;
            const config = this.getConfig();

            axios.get('/api/v1/consult', {
                params: {
                    search: this.query,
                    status_id: this.status_id,
                    recordsPerPage: this.perPage,
                    clinic_id: this.authStore.clinic_id,
                    sort_by: this.sort_by,
                    sort_order: this.sort_order,
                    page: this.page,
                    loading: loading,
                    deleted_leads: this.deletedLeads ? 'yes' : 'no'
                }
            }, config)
                .then(function (response) {
                    //console.log(response);
                    if (response.data.success) {
                        if (response.data.data) {
                            _self.leads = response.data.data;
                            _self.last_page = response.data.meta.last_page;
                        } else {
                            _self.leads = []
                            //console.log(_self.leads);
                        }
                    }
                })
                .catch(function (error) {
                    console.log('error', error);
                });
        },
        async move(status_id, leadid) {
            let _self = this;
            const config = this.getConfig();

            return axios.post('/api/v1/lead/movestage', {status_id: status_id, leadid: leadid}, config)
                .then(function (response) {
                    //console.log(response);
                    if (response.data.success) {
                        _self.stageMoved = true;
                        _self.list();
                        _self.alertStore.success = true;
                        _self.alertStore.message = response.data.message;
                    } else {
                        _self.alertStore.success = false;
                        _self.alertStore.message = response.data.message;
                        //console.log(_self.errors);
                    }
                })
                .catch(function (error) {
                    console.log('error', error);
                });
        },
        async updateConsultationBookedDate(leadid, date) {
            let _self = this;
            const config = this.getConfig();

            return axios.post('/api/v1/addquickconsultbook', {leadid: leadid, consultation_booked_date: date}, config)
                .then(function (response) {
                    //console.log(response);
                    if (response.data.success) {
                        _self.quickConsultationBooked = true;
                        _self.list();
                        _self.alertStore.success = true;
                        _self.alertStore.message = response.data.message;
                    } else {
                        _self.alertStore.success = false;
                        _self.alertStore.message = response.data.message;
                        //console.log(_self.errors);
                    }
                })
                .catch(function (error) {
                    console.log('error', error);
                });
        },
        async restore(leadId) {
            let _self = this;
            const config = this.getConfig();

            return axios.post('/api/v1/restore-lead', {id: leadId}, config)
                .then(function (response) {
                    console.log(response);
                    if (response.data.success) {
                        _self.list();
                        _self.count();
                        router.push("/crtx/book-consultation");
                    }
                    _self.alertStore.success = response.data.success;
                    _self.alertStore.message = response.data.message;
                })
                .catch(function (error) {
                    console.log('error', error);
                });
        },
        quickupdatesidebar(leadId) {
            let _self = this;
            const config = this.getConfig();

            if (leadId.consultation_booked_date) {
                let bookedDate = leadId.consultation_booked_date.split(" ");
                leadId.consultation_booked_date = bookedDate[0] + ' ' + bookedDate[1] + ":00 " + bookedDate[2];
                leadId.availability = this.appointmentStore.appointmentAvailability;
            }

            leadId.clinic_id = this.authStore.clinic_id;
            leadId.quick_update_type = 'consultation';

            // First axios call
            axios
                .post("/api/v1/updatePatientProfile", leadId, config)
                .then(function (response) {
                    if (response.data.success) {
                        _self.profileUpdated = true;
                        _self.alertStore.success = true;
                        _self.alertStore.message = 'Successfully Consultation Leads Updated!';
                        _self.list();
                        _self.count();
                        _self.dashboardStore.getRecentLeadsCount();
                        _self.dashboardStore.getRecentLeads()

                        if(leadId.consultation_booked_date){
                            _self.appointmentStore.getAvailableTimes(moment(leadId.consultation_booked_date).format('MM-DD-YYYY'));
                        }

                        // Second axios call inside the first then block
                        if (leadId.note && leadId.note.trim() !== "") {
                            axios
                                .post(
                                    "/api/v1/addNotes",
                                    {
                                        note: leadId.note,  // Assuming this.newNote is available in the scope
                                        customer_id: leadId.id,  // Assuming leadId has an id property
                                        user_id: _self.authStore.user.id,
                                    },
                                    config
                                )
                                .then(function (response) {
                                    if (response.data.success) {
                                        _self.newNote = "";
                                        _self.noteSaved = true;
                                        _self.find(leadId.id);  // Assuming you want to find based on leadId.id
                                    } else {
                                        // Handle errors for the second axios call if needed
                                    }
                                })
                                .catch(function (error) {
                                    console.log("error", error);
                                });
                        }
                    } else {
                        // Handle errors for the first axios call if needed
                    }
                    _self.alertStore.success = response.data.success;
                })
                .catch(function (error) {
                    console.log("error", error);
                });
        }
    }
})

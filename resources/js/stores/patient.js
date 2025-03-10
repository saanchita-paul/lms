import { defineStore } from "pinia";
import axios from "axios";
import router from "../routes";
import { useAuthStore } from "../stores/auth";
import { useLeadStore } from "../stores/lead";
import { useAlertStore } from './alert';
import { forEach } from "lodash";
import { useAuditLogStore } from '../stores/auditLog';
import {useAppointmentStore} from "./appointment";

export const usePatientStore = defineStore({
    id: "patient",
    state: () => ({
        errors: [],
        patient: {},
        authStore: useAuthStore(),
        leadStore:useLeadStore(),
        alertStore: useAlertStore(),
        auditLogStore: useAuditLogStore(),
        appointmentStore: useAppointmentStore(),
        selectedId: null,
        newNote: "",
        formData: "",
        callrailFormData:null,
        formArray: [],
        trustfullDetails: "",
        trustfullDetailsArray: [],
        callrailDetails: "",
        callrailDetailsArray: [],
        conversationalTranscript: [],
        scoreStatus: 'LOW',
        phoneScoreStatus: 'LOW',
        nameScoreStatus: 'LOW',
        emailScoreStatus: 'LOW',
        priorCalls:null,
        possibleSpam:null,
        deliverableStatus: null,
        dataBreaches:null,
        callDetails:null
    }),
    getters: {
        //doubleCount: (state) => state.counter * 2
    },
    actions: {
        getConfig() {
            return {
                headers: {
                    headers: {
                        Accept: "application/json",
                        Authorization:
                            this.authStore.token_type +
                            " " +
                            this.authStore.token,
                        ContentType: "application/x-www-form-urlencoded"
                    },
                },
            };
        },
        find(customerId) {
            let _self = this;
            const config = this.getConfig();
            axios
                .post(
                    "/api/v1/getPatientProfile",
                    { customer_id: customerId },
                    config
                )
                .then(function (response) {
                    //  console.log('response', response.data.getPatientProfile[0]);
                    if (response.data.success) {
                        _self.patient = response.data.getPatientProfile[0];
                        if(_self.patient.phone_form == 'Phone Call'){
                            _self.callDetails = response.data.getPatientProfile[0].callrail_details;
                        }
                        _self.patient.three_plus_attempts = _self.patient
                        .three_plus_attempts
                        ? _self.patient.three_plus_attempts
                        : 0;
                        if(_self.patient.won_lost_date){
                            _self.patient.won_lost_date = _self.patient.won_lost_date.split(" ")[0]
                        }

                        if(response.data.getPatientProfile[0].phone_form == 'Web Form'){
                            if(response.data.getPatientProfile[0].callrail_details) {
                                let result = response.data.getPatientProfile[0].callrail_details.trim();
                                result = result.replaceAll('callrail', 'mycrtx');
                                try {
                                    _self.callrailDetails = JSON.parse(result);
                                } catch (e) {
                                    console.log(e);
                                }
                                _self.callrailDetailsArray = Object.keys(_self.callrailDetails).map((key) => [key, _self.callrailDetails[key]]);
                                if (Object.keys(_self.callrailDetailsArray).length > 0) {
                                    _self.callrailDetailsArray.forEach(function (item, index) {
                                        if (item[0] == 'source') {
                                            _self.source = item[1];
                                        }
                                        if (item[0] == 'medium') {
                                            _self.medium = item[1];
                                        }
                                        if (item[0] == 'keywords') {
                                            _self.keywords = item[1];
                                        }
                                        if (item[0] == 'campaign') {
                                            _self.campaign = item[1];
                                        }
                                        if (item[0] == 'landing_page_url') {
                                            _self.landing_page_url = item[1].split('?')[0];
                                        }
                                        if (item[0] == 'form_url') {
                                            _self.form_url = item[1].split('?')[0];
                                        }
                                        if (item[0] == 'conversational_transcript' && item[1]) {
                                            _self.conversationalTranscript = item[1];
                                        }
                                        if (item[0] == 'prior_calls') {
                                            _self.priorCalls = item[1];
                                        }
                                        if (item[0] == 'spam') {
                                            _self.possibleSpam = item[1];
                                        }
                                        if (item[0] == 'recording_redirect') {
                                            _self.recording_redirect = item[1];
                                        }
                                        if (item[0] == 'form_data') {
                                            _self.callrailFormData = item[1];
                                        }
                                    });
                                }
                            }
                        }else{
                            _self.callDetails.forEach(function(callDetail) {
                                if(callDetail.callrail_details){
                                    let result = callDetail.callrail_details.trim();
                                    result = result.replaceAll('callrail', 'mycrtx');
                                    try {
                                        _self.callrailDetails = JSON.parse(result);
                                    } catch (e) {
                                        console.log(e);
                                    }
                                    _self.callrailDetailsArray = Object.keys(_self.callrailDetails).map((key) => [key, _self.callrailDetails[key]]);
                                    if(Object.keys(_self.callrailDetailsArray).length>0){
                                        _self.callrailDetailsArray.forEach(function(item, index){
                                            if(item[0]=='source'){
                                                callDetail.source = item[1];
                                            }
                                            if(item[0]=='medium'){
                                                callDetail.medium = item[1];
                                            }
                                            if(item[0]=='keywords'){
                                                callDetail.keywords = item[1];
                                            }
                                            if(item[0]=='campaign'){
                                                callDetail.campaign = item[1];
                                            }
                                            if(item[0]=='landing_page_url'){
                                                callDetail.landing_page_url = item[1].split('?')[0];
                                            }
                                            if(item[0]=='form_url'){
                                                callDetail.form_url = item[1].split('?')[0];
                                            }
                                            if(item[0]=='conversational_transcript' && item[1]){
                                                callDetail.conversationalTranscript =  item[1];
                                            }
                                            if(item[0]=='prior_calls'){
                                                callDetail.priorCalls = item[1];
                                            }
                                            if(item[0]=='spam'){
                                                callDetail.possibleSpam = item[1];
                                            }
                                            if(item[0]=='recording_redirect'){
                                                callDetail.recording_redirect = item[1];
                                            }
                                            if(item[0]=='form_data'){
                                                callDetail.callrailFormData = item[1];
                                            }
                                            callDetail.showFullTranscript = false;
                                        });
                                    }
                                }
                            });
                        }

                        // If formData is null on patient get it from callrail_details

                        if(response.data.getPatientProfile[0].form_data){
                            _self.formData = response.data.getPatientProfile[0].form_data;

                            let result =  _self.formData.trim();
                            result = result.replace("FormData:", '');
                            result = result.replace("<b style='color:red'>Invalid Email Address received from Callrail</b>", '');
                            result = result.replaceAll('n  ', '');
                            result = result.replaceAll(',n', ',');
                            result = result.replaceAll('"n', '"');


                            try {
                                _self.formData = JSON.parse(result);
                            } catch (e) {
                                console.log(e);
                            }
                        }else if(_self.callrailFormData){
                            _self.formData = _self.callrailFormData;
                        }

                        if( _self.formData){

                            // _self.formArray = Object.keys(_self.formData).map((key) => [key, _self.formData[key]]);
                            _self.formArray = Object.keys(_self.formData).map((key) => {
                                // Check if the key contains the text 'input'
                                if (key.includes('input')) {
                                    // Replace the entire key with 'Answer is:' and keep the original value
                                    return ['Answer is:', _self.formData[key]];
                                } else {
                                    return [key, _self.formData[key]];
                                }
                            });

                            if(Object.keys(_self.formArray).length>0) {
                                _self.formArray.forEach(function (itemEmail, indexEmail) {
                                    if(itemEmail[0]=='ZIP Code'){
                                        _self.zip_code = itemEmail[1];
                                    } if(itemEmail[0]=='Credit Score'){
                                        _self.credit_score = itemEmail[1];
                                    } if(itemEmail[0]=='Dental Condition'){
                                        _self.dental_condition = itemEmail[1];
                                    } if(itemEmail[0]=='Stopped You Previously'){
                                        _self.stopped_you_previously = itemEmail[1];
                                    } if(_self.authStore.clinic_id == _self.patient.microsite_health_clinic_id && _self.patient.badge == null && itemEmail[0] == 'Practice Name'){
                                        _self.patient.badge = itemEmail[1];
                                    }
                                });
                            }
                        }

                        if (_self.patient.lead_score >= 900) {
                            _self.scoreStatus = 'HIGH';
                        } else if (_self.patient.lead_score >= 700) {
                            _self.scoreStatus = 'GOOD';
                        } else if (_self.patient.lead_score >= 500) {
                            _self.scoreStatus = 'MEDIUM';
                        } else {
                            _self.scoreStatus = 'LOW';
                        }


                        if (_self.patient.phone_score >= 900) {
                            _self.phoneScoreStatus = 'HIGH';
                        } else if (_self.patient.phone_score >= 700) {
                            _self.phoneScoreStatus = 'GOOD';
                        } else if (_self.patient.phone_score >= 500) {
                            _self.phoneScoreStatus = 'MEDIUM';
                        } else {
                            _self.phoneScoreStatus = 'LOW';
                        }

                        if (_self.patient.name_score >= 900) {
                            _self.nameScoreStatus = 'HIGH';
                        } else if (_self.patient.name_score >= 700) {
                            _self.nameScoreStatus = 'GOOD';
                        } else if (_self.patient.name_score >= 500) {
                            _self.nameScoreStatus = 'MEDIUM';
                        } else {
                            _self.nameScoreStatus = 'LOW';
                        }

                        if (_self.patient.email_score >= 900) {
                            _self.emailScoreStatus = 'HIGH';
                        } else if (_self.patient.email_score >= 700) {
                            _self.emailScoreStatus = 'GOOD';
                        } else if (_self.patient.email_score >= 500) {
                            _self.emailScoreStatus = 'MEDIUM';
                        } else {
                            _self.emailScoreStatus = 'LOW';
                        }

                        if(response.data.getPatientProfile[0].trustfull_details){
                            let result = response.data.getPatientProfile[0].trustfull_details.trim();
                            result = result.replaceAll('callrail', 'mycrtx');
                            try {
                                _self.trustfullDetails = JSON.parse(result);
                            } catch (e) {
                                console.log(e);
                            }
                            _self.trustfullDetailsArray = Object.keys(_self.trustfullDetails).map((key) => [key, _self.trustfullDetails[key]]);

                            if(Object.keys(_self.trustfullDetailsArray).length>0){
                                _self.trustfullDetailsArray.forEach(function(item, index){
                                    if(item[0]=='email'){
                                        let emailDetails = Object.keys(item[1]).map((key) => [key, item[1][key]]);
                                        if(Object.keys(emailDetails).length>0){
                                            emailDetails.forEach(function(itemEmail, indexEmail){
                                                if(itemEmail[0]=='status'){
                                                    _self.deliverableStatus = itemEmail[1];
                                                }
                                                if(itemEmail[0]=='data_breaches_count'){
                                                    _self.dataBreaches = itemEmail[1];
                                                }
                                            });
                                        }
                                       }
                                });
                            }
                        }
                    } else {
                        router.push('/crtx/not-found');
                    }
                })
                .catch(function (error) {
                    console.log("error", error);
                });
        },
        update() {
            let _self = this;
            const config = this.getConfig();
            if (this.patient.consultation_booked_date) {
                let bookedDate = this.patient.consultation_booked_date.split(" ");
                this.patient.consultation_booked_date = bookedDate[0]+ ' ' +bookedDate[1]+":00 " +  bookedDate[2];
                this.patient.availability = this.appointmentStore.appointmentAvailability;
            }
            this.patient.clinic_id = this.authStore.clinic_id;
            axios
                .post("/api/v1/updatePatientProfile", this.patient, config)
                .then(function (response) {
                    if (response.data.success) {
                        _self.alertStore.success = true;
                        _self.alertStore.message = response.data.message;
                        _self.find(_self.patient.id);
                        _self.auditLogStore.fetchAuditLogs(_self.patient.id);
                        _self.appointmentStore.getAvailableTimes(moment(_self.patient.consultation_booked_date).format('MM-DD-YYYY'));
                    } else {
                        //console.log(_self.errors);
                    }
                })
                .catch(function (error) {
                    console.log("error", error);
                });
        },
        async delete(){
            let _self = this;
            const config = this.getConfig();

            return axios.post('/api/v1/delete-lead', {id:this.patient.id}, config)
            .then(function (response) {
                console.log(response);
                if(response.data.success){
                    _self.leadStore.list();
                    _self.leadStore.count();
                    router.push("/crtx/leads");
                }
                _self.alertStore.success = response.data.success;
                _self.alertStore.message = response.data.message;
            })
            .catch(function (error) {
              console.log('error', error);
            });
          },
        updateNote(note) {
            let _self = this;
            const config = this.getConfig();

            axios
                .post(
                    "/api/v1/updateNotes",
                    {
                        id: note.id,
                        note: note.note,
                        customer_id: this.patient.id,
                        user_id: this.authStore.user.id,
                    },
                    config
                )
                .then(function (response) {
                    //  console.log('response', response);
                    if (response.data.success) {
                        _self.newNote = "";
                        _self.alertStore.success = true;
                        _self.alertStore.message = response.data.message;
                        _self.find(_self.patient.id);
                    } else {
                        //console.log(_self.errors);
                    }
                })
                .catch(function (error) {
                    console.log("error", error);
                });
        },
        saveNote(customerId) {
            let _self = this;
            const config = this.getConfig();

            axios
                .post(
                    "/api/v1/addNotes",
                    {
                        note: this.newNote,
                        customer_id: customerId,
                        user_id: this.authStore.user.id,
                    },
                    config
                )
                .then(function (response) {
                    //  console.log('response', response);
                    if (response.data.success) {
                        _self.newNote = "";
                        _self.alertStore.success = true;
                        _self.alertStore.message = response.data.message;
                        _self.find(customerId);
                    } else {
                        //console.log(_self.errors);
                    }
                })
                .catch(function (error) {
                    console.log("error", error);
                });
        },
        deleteNote(noteId) {
            let _self = this;
            const config = this.getConfig();

            axios
                .post("/api/v1/deleteNotes", { id: noteId }, config)
                .then(function (response) {
                    // console.log('response', response);
                    if (response.data.success) {
                        _self.alertStore.success = true;
                        _self.alertStore.message = response.data.message;
                        _self.find(_self.patient.id);
                    } else {
                        //console.log(_self.errors);
                    }
                })
                .catch(function (error) {
                    console.log("error", error);
                });
        },
        isJsonString(str) {
            try {
                JSON.parse(str);
            } catch (e) {
                return false;
            }
            return true;
        }
    },
});

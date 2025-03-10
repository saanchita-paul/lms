import { defineStore } from 'pinia'
import axios from 'axios'
import router from '../routes'
import { useAuthStore } from './auth';
import {useClinicStore} from "./clinic";
import {useAlertStore} from "./alert";
import {usePatientStore} from "./patient";

export const useAppointmentStore = defineStore({
    id: 'appointment',
    state: () => ({
        authStore: useAuthStore(),
        clinicStore: useClinicStore(),
        alertStore: useAlertStore(),
        patientStore: usePatientStore(),
        calendar_data:{
            clinic_id:null,
            section:'',
            patient_types:[],
            service_types:[],
            calendar_type:'',
            duration:15,
            repeat_type:'Repeat weekly',
            timezone:'America/New_York',
            availabilities:{
                sun_start:null,
                sun_end:null,
                mon_start:['09:00am'],
                mon_end:['07:00pm'],
                tue_start:['09:00am'],
                tue_end:['07:00pm'],
                wed_start:['09:00am'],
                wed_end:['07:00pm'],
                thu_start:['09:00am'],
                thu_end:['07:00pm'],
                fri_start:['09:00am'],
                fri_end:['07:00pm'],
                sat_start:null,
                sat_end:null
            },
            fixed_appointment_dates:[],
            unavailable_appointment_dates:[],
            emails_for_scheduling: '',
            reminders: []
        },
        appointments:null,
        service_types: null,
        errors:[],
        reminderEmailErrors:[],
        reminderTextErrors:[],
        editingAppointment:null,
        availability:null,
        allowedDates: [],
        allowedDateTimes: [],
        selectedDate:'',
        appointmentAvailability:null
    }),
    getters: {

    },
    actions: {
        getConfig(){
            return  {
                headers: {
                    headers: {
                        Accept: 'application/json',
                        Authorization: this.authStore.token_type + ' ' + this.authStore.token
                    }
                },
            }
        },
        find(){
            let _self = this;
            const config = this.getConfig();

            axios.post('/api/v1/appointments/find', {'clinic_id':this.authStore.clinic_id}, config)
                .then(function (response) {
                    //console.log('response', response);
                    if (response.data.success) {
                        if(response.data.data){
                            _self.calendar_data.clinic_id = response.data.data.clinic_id;
                            if(response.data.data.patient_types)
                                _self.calendar_data.patient_types =  response.data.data.patient_types.split(',');
                            if(response.data.data.calendar_type)
                                _self.calendar_data.calendar_type =  response.data.data.calendar_type;
                            if(response.data.data.service_types)
                                _self.calendar_data.service_types = response.data.data.service_types.split(',');
                            if(response.data.data.duration)
                                _self.calendar_data.duration = response.data.data.duration;

                            // If custom duration? add a dropdown option for it.
                            let optionsArr = [];
                            let options = document.getElementById('duration-select').options;
                            for (let i = 0; i < options.length-1; i++) {
                                optionsArr[i] = parseInt(options[i].value);
                            }

                            if(!optionsArr.includes(_self.calendar_data.duration))
                                $("#duration-select option").eq(options.length-1).before("<option value="+_self.calendar_data.duration+">"+_self.calendar_data.duration +" Minutes"+"</option>");

                            if(response.data.data.repeat_type)
                                _self.calendar_data.repeat_type = response.data.data.repeat_type;
                            if(response.data.data.timezone)
                                _self.calendar_data.timezone = response.data.data.timezone;

                            _self.appointments = response.data.data.appointments;

                            if(response.data.data.repeating_appointment_availability){
                                let day_arr = ['sun', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat'];
                                for(let day=0;day<day_arr.length;day++){
                                    let start_times = [];
                                    let end_times = [];
                                    if(response.data.data.repeating_appointment_availability[day_arr[day]] != ''){
                                        let times_arr = response.data.data.repeating_appointment_availability[day_arr[day]].split(',');
                                        times_arr.forEach(function(time, index){
                                            start_times[index] = time.split('-')[0];
                                            end_times[index] = time.split('-')[1];
                                        });
                                    }else{
                                        start_times = null;
                                        end_times = null;
                                    }

                                    _self.calendar_data.availabilities[day_arr[day]+'_start'] = start_times;
                                    _self.calendar_data.availabilities[day_arr[day]+'_end'] = end_times;
                                }
                            }

                            if(response.data.data.appointment_unavailabilities){
                                _self.calendar_data.unavailable_appointment_dates = [];
                                response.data.data.appointment_unavailabilities.forEach(function(item, i){
                                    _self.calendar_data.unavailable_appointment_dates[i] = {id:response.data.data.appointment_unavailabilities[i].id, date:moment(response.data.data.appointment_unavailabilities[i].date).format('MM/DD/YYYY')};
                                });
                            }

                            if(response.data.data.fixed_appointment_availabilities){
                                _self.calendar_data.fixed_appointment_dates = [];
                                response.data.data.fixed_appointment_availabilities.forEach(function(item, i){
                                    let start_times = [];
                                    let end_times = [];
                                    let times_arr = response.data.data.fixed_appointment_availabilities[i].times.split(',');
                                    times_arr.forEach(function(time, index){
                                        start_times[index] = time.split('-')[0];
                                        end_times[index] = time.split('-')[1];
                                    });

                                    _self.calendar_data.fixed_appointment_dates[i] = {id:response.data.data.fixed_appointment_availabilities[i].id, date:response.data.data.fixed_appointment_availabilities[i].date, start_times:start_times, end_times:end_times};
                                });
                            }

                            // Appointment Reminders
                            if(response.data.data.appointment_reminders){
                                _self.calendar_data.reminders = [];
                                response.data.data.appointment_reminders.forEach(function(reminder, i){
                                    _self.calendar_data.reminders.push({id:reminder.id, type:reminder.type, interval:reminder.interval, unit:reminder.unit});
                                })
                            }
                        }

                    }else{
                        _self.errors = response.data.errors;
                    }
                })
                .catch(function (error) {
                    console.log('error', error);
                });
        },
        save(section){
            let _self = this;
            const config = this.getConfig();

            // Access emails_for_scheduling from clinicStore
            const emails_for_scheduling = this.clinicStore.clinic.emails_for_scheduling;

            this.calendar_data.clinic_id = this.authStore.clinic_id
            this.calendar_data.section = section;
            this.calendar_data.emails_for_scheduling = emails_for_scheduling;

            axios.post('/api/v1/appointments/update', this.calendar_data, config)
                .then(function (response) {
                    if (response.data.success) {
                        _self.alertStore.success = true;
                        _self.alertStore.message = response.data.message;

                        _self.find();
                    }else{
                        _self.errors = response.data.errors;
                    }
                })
                .catch(function (error) {
                    console.log('error', error);
                });
        },
        getServicesList(){
            const config = this.getConfig();
            const clinicId = localStorage.getItem("clinic_id");
            axios
                .get("/getServicesList", {
                    ...config,
                    params: { selected_clinic_id: clinicId },
                })
                .then((response) => {
                    if (response.data.success) {
                        this.service_types = response.data.data;
                    } else {
                        console.log("Error fetching services:", response.data.error);
                    }
                })
                .catch((error) => {
                    console.log("Error:", error);
                });
        },
        getAvailableTimes(date) {
            let _self = this;
            const config = this.getConfig();
            const clinicId = localStorage.getItem('clinic_id');

            axios
                .get("/getAvailableTimes/"+clinicId+"/"+date+'?days=30', config)
                .then((response) => {
                    if (response.data.success) {
                        _self.appointmentAvailability = response.data.availability;
                        _self.getAllowedDateTimes(response.data);
                        _self.selectedDate = moment(_self.patientStore.patient.consultation_booked_date).format('MM/DD/YYYY') ?? moment().add(1, 'days').format('MM/DD/YYYY');

                        $('#bookDate, #book-datetimepicker').datetimepicker({
                            allowDates: _self.allowedDates,
                            formatDate:'MM/DD/YYYY',
                            allowTimes: _self.allowedDateTimes[_self.selectedDate],
                            formatTime:'hh:mm A',
                        });

                        $('.patient-profile #bookDate, .patient-profile #book-datetimepicker, .dashboard #bookDate').datetimepicker({
                            timepicker: _self.patientStore.patient.consultation_booked_date != null
                        });
                    } else {
                        console.log("Error fetching Available Times:", response.data.error);
                    }
                })
                .catch((error) => {
                    console.log("Error:", error);
                });
        },
        getAllowedDateTimes(data){
            let _self = this;
            let allowedDateTimes = []
            for(var key in data.data){
                if(data.data[key].length>0){
                    _self.allowedDates.push(moment(key).format('MM/DD/YYYY'));
                }
                let obj = {[moment(key).format('MM/DD/YYYY')]: data.data[key]};
                allowedDateTimes.push(obj);
            }

            allowedDateTimes.forEach(function(dateTime, i){
                let times = [];
                for(var key in dateTime){
                    dateTime[key].forEach(function(arr){
                        times.push(arr[0]);
                    });
                    _self.allowedDateTimes[key] = times;
                }
            });
        }
    }
});

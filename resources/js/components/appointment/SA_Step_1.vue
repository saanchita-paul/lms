<template>
    <div class="staging-alert" v-if="env=='staging'">
        <span>Hold on! You are in the staging environment!</span>
    </div>
    <h2 class="mb-md-4 my-5">Appointment Details</h2>
    <form @submit.prevent="submit">
        <div class="row gx-3">
            <div class="col-md-12 col-lg-12">
                <div class="form-group">
                    <div class="check-group d-flex flex-wrap">
                        <div class="btn-checkbox mb-2 me-2" v-for="types in appointmentTypes">
                            <input type="radio" class="btn-check" :id="types.id" :value="types.name"  @change="patientTypeChange" autocomplete="off" v-model="patientType">
                            <label class="btn btn-outline-primary sa-steps d-flex text-start" :for="types.id" :class="{'unfilled' :errors.patientType, 'filled':patientType}">
<!--                                <span class="radio-round"></span>-->
                                <div>
                                    {{ types.name }}
                                </div>
                            </label>
                        </div>
                    </div>
                    <span class="d-block text-danger mt-1" v-text="errors.patientType"></span>
                </div>
            </div>
            <div class="col-md-12 col-lg-12 mt-2">
                <h5 class="mb-2">Select Service</h5>
                <div class="form-group">
                    <div class="check-group d-flex flex-wrap">
                        <div class="btn-checkbox mb-2 me-2" v-for="service in services">
                            <input type="radio" class="btn-check" :id="'services-'+service.id" :value="service.name" autocomplete="off" v-model="selected_service" @change="selectedServiceChange">
                            <label class="btn btn-outline-primary  sa-steps" :class="{'unfilled' :errors.selected_service, 'filled':selected_service}" :for="'services-'+service.id">{{ service.name }}</label><br>
                        </div>
                    </div>
                    <span class="d-block text-danger mt-1" v-text="errors.selected_service"></span>
                </div>
            </div>
        </div>
        <div class="row gx-3">
            <div class="col-md-6 col-lg-5">
                <h5 class="mb-2">Select Date</h5>
                <div class="form-group">                
                    <div class="date-pick-input">
                        <div class="mt-2 select-date-dropdown" style="width: 100%;">
                            <div class="dropdown">
                                <input type="text" id="reportrange" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row gx-3">
            <div class="col-md-10 col-lg-8">
                <div class="row g-2">
                    <h5 class="mb-2 col-md-12">Select Time</h5>
                    <div  v-for="(slots, date) in times" :key="date" class="col-12 col-md">
                        <div class="form-group mb-md-0">
                            <h5 class="mb-2 text-md-center">{{ formatDate(date) }}</h5>
                            <div class="check-group d-flex flex-md-column justify-content-start time-check-list">
                                <template v-if="slots.length > 0">
                                    <div v-for="slot in slots" :key="slot" class="btn-checkbox mb-2 me-2 me-md-0 d-flex">
                                        <input type="radio" class="btn-check" :id="slot[1]" :value="slot[0]+'|'+date+'|'+slot[1]" v-model="selected_time" autocomplete="off" @change="selectedTimeChange">
                                        <label class="btn btn-outline-primary sa-steps d-block px-1 w-100" :for="slot[1]" :class="{'unfilled' :errors.selected_time, 'filled':selected_time}">{{ slot[0] }}</label>
                                    </div>
                                </template>
                                <template v-else>
                                    <div class="btn-checkbox mb-2 me-2 me-md-0 text-md-center">
                                        <label>----</label>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                    <span class="d-block text-danger mt-1" v-text="errors.selected_time"></span>
                </div>
            </div>
        </div>
        <div class="bottom-btn-box d-flex">
            <button type="submit" class="btn btn-primary submit-btn ms-auto">Continue</button>
        </div>
    </form>
</template>
<script>
import router from '../../routes'
import { useAuthStore } from '../../stores/auth';
import axios from "axios";
import {useAppointmentStore} from "../../stores/appointment";

export default {
    setup (){
        const authStore = useAuthStore();
        const appointmentStore = useAppointmentStore();
        return { authStore, appointmentStore };
    },
    props:['env','id'],
    data() {
        return {
            errors: [],
            appointmentTypes: {},
            services: {},
            times: {},
            times_array: {},
            patientType: null,
            password_visible: false,
            confirm_password_visible: false,
            selected_service: null,
            selected_time: null,
            minDate:moment().format('MM/DD/YYYY'),
            maxDate:moment().add(6, 'days').format('MM/DD/YYYY'),
            startDate:moment().add(1, 'day').format('MM/DD/YYYY'),
            endDate:moment().format('MM/DD/YYYY'),
            tomorrow: moment().add(1, 'day').format('MM-DD-YYYY'),
        }
    },
    mounted(){
        const vm = this;
        const inputs = document.querySelectorAll('input.form-control, textarea.form-control, select.form-control')

        inputs.forEach(input => {
            input.addEventListener('change', this.inputFilled)
        })

        // Get today's date and add 1 day to set tomorrow as the minimum date
        const today = moment();
        const tomorrow = today.add(1, 'days');

        $('#reportrange').daterangepicker({
            singleDatePicker: true,
            alwaysShowCalendars: true,
            showDropdowns: true,
            autoApply: true,
            startDate: vm.startDate,
            endDate: vm.endDate,
            minDate: tomorrow, // Restrict past dates including today
            locale: {
                format: 'MM/DD/YYYY'
            },
        },function(start, end) {
            vm.times_array = {};
            vm.times = {};
            vm.getAvailableTimes(end.format('MM-DD-YYYY'));
        });
        vm.getAppoinmentTypes();
        vm.getServicesList();
        vm.getAvailableTimes(vm.tomorrow);

        if(this.$route.query.id){
            vm.getAppointmentById();
        }
    },
    methods: {
        submit(){
            this.errors = [];
            if (!this.patientType) {
                this.errors['patientType'] = 'Patient Type is required.';
            }
            if (!this.selected_service) {
                this.errors['selected_service'] = 'Service is required.';
            }
            if (!this.selected_time) {
                this.errors['selected_time'] = 'Time is required.';
            }
            if(Object.keys(this.errors).length>0){
                return null;
            }
            const step1value = {
                patient_type: this.patientType,
                selected_service: this.selected_service,
                selected_time: this.selected_time,
            };
            this.$emit('form-submitted', step1value);
            router.push( {name:'sa_step_2',params:{ data:this.selected_time }, query:this.$route.query } );
        },
        inputFilled(event){
            event.target.classList.add("filled");
        },
        getConfig() {
            return {
                headers: {
                    Accept: "application/json",
                },
            };
        },
        getAppoinmentTypes() {
            const config = this.getConfig();
            const id = this.$route.params.id;
            axios
                .get("/getNextHealthAppointmentTypes/"+id, config)
                .then((response) => {
                    if (response.data.success) {
                        this.appointmentTypes = response.data.data;
                    } else {
                        console.log("Error fetching appointment types:", response.data.error);
                    }
                })
                .catch((error) => {
                    console.log("Error:", error);
                });
        },
        getServicesList() {
            const config = this.getConfig();
            const id = this.$route.params.id;
            axios
                .get("/getServicesList", {params:{clinic_id:id}}, config)
                .then((response) => {
                    if (response.data.success) {
                        this.services = response.data.data;
                    } else {
                        console.log("Error fetching services:", response.data.error);
                    }
                })
                .catch((error) => {
                    console.log("Error:", error);
                });
        },
        getAvailableTimes(date) {
            const config = this.getConfig();
            const id = this.$route.params.id;

            axios
                .get("/getAvailableTimes/"+id+"/"+date+(this.$route.query.id ? "?aid="+this.$route.query.id : ''), config)
                .then((response) => {
                    if (response.data.success) {
                        this.times = response.data.data;
                        this.appointmentStore.availability = response.data.availability;
                    } else {
                        console.log("Error fetching Available Times:", response.data.error);
                    }
                })
                .catch((error) => {
                    console.log("Error:", error);
                });
        },
        async getAppointmentById(){
            let _self = this;
            const config = this.getConfig();
            axios
                .post("/getAppointmentById", {appointment_id:this.$route.query.id, clinic_id:this.$route.params.id}, config)
                .then((response) => {
                    if (response.data.success) {
                        _self.appointmentStore.editingAppointment = response.data.data;
                        _self.patientType = response.data.data.patient_type;
                        _self.selected_service = response.data.data.services.name;
                        _self.startDate = _self.endDate = moment(response.data.data.date).format('MM/DD/YYYY');
                        $('#reportrange').data('daterangepicker').setStartDate(_self.startDate);
                        $('#reportrange').data('daterangepicker').setEndDate(_self.endDate);
                        _self.getAvailableTimes(moment(response.data.data.date).format('MM-DD-YYYY'));
                        _self.selected_time = moment(response.data.data.time, 'hh:mm:ss').format('hh:mm A') + "|" + moment(response.data.data.date).format('YYYY-MM-DD') + "|" + moment(response.data.data.date).format('MM/DD/YYYY') + " " + moment(response.data.data.time, 'hh:mm:ss').format('hh:mm A');
                    } else {
                        console.log("Error fetching appointment:", response.data.message);
                        if(this.authStore.user != null)
                            router.push('/crtx/not-found');
                    }
                    return response;
                })
                .catch((error) => {
                    console.log("Error:", error);
                    return error;
                });
        },
        formatDate(date) {
            const formattedDate = new Date(date);
            const options = {  day: '2-digit', weekday: 'short', timeZone: 'UTC' };
            return formattedDate.toLocaleDateString('en-US', options);
        },
        patientTypeChange(){
            if(this.patientType != ''){
                this.errors['patientType'] = '';
            }
        },
        selectedServiceChange(){
            if(this.selected_service != ''){
                this.errors['selected_service'] = '';
            }
        },
        selectedTimeChange(){
            if(this.selected_time != ''){
                this.errors['selected_time'] = '';
            }
        },
    },
}
</script>

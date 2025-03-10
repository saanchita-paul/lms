<template>
    <h3 v-if="appointment_error" style="color: red">Error: {{this.appointment_error_message}}</h3>
    <h3 v-else>Appointment Confirmed!</h3>
    <h5 class="lead m-0 text-secondary fw-light">
        Your appointment has been successfully scheduled. Please review the details below to ensure everything is correct. You will receive an email confirmation shortly. <b>It is essential you reply to this email to confirm your appointment.</b> Please check your inbox and spam folder if you do not see it soon.
    </h5>
    <div class="mt-3 mt-md-4 mt-lg-5 fs-5 mb-3">Appointment Summary</div>
    <div class="confirm-appointment-date">
        <i class="fas fa-calendar-alt"></i>
        <div class="lead">{{dataForSAStep2.first_name}} {{dataForSAStep2.last_name}}</div>
        <div class="fs-6 mb-1">{{dateFormat(dataForSAStep2.selected_time)}}</div>
        <div><h2 class="fw-light mb-1">{{timeFormat(dataForSAStep2.selected_time)}}</h2></div>
        <div>{{ dataForSAStep2.selected_service }}</div>
    </div>

    <h5 class="pt-md-1 mt-4 h5 fw-bolder">Practice Details</h5>
    <div>{{this.practise_details.dr_name}}</div>
    <div class="row pt-2 mb-3">
        <div class="col-lg-3 col-md-5 mt-4 mt-md-3">
            <h5>Practice Address</h5>
            <div>{{this.practise_details.practice_name}} </div>
            <div>{{this.practise_details.address}}<br/>{{this.practise_details.town ? this.practise_details.town+', ':''}}{{this.practise_details.state ? this.practise_details.state + ', ' : ''}}{{this.practise_details.zip ? this.practise_details.zip + ',' : ''}}</div>
        </div>
        <div class="col-lg-4 col-md-5 mt-4 mt-md-3">
            <h5>Hours</h5>
            <div>{{this.practise_details.office_hours}}</div>
        </div>
    </div>
    <div>
        <ul>
            <li>
                You will receive reminders via email and text message as your appointment date approaches. These reminders are to help ensure that you do not miss your scheduled time.
            </li>
            <li>
                Please make every effort to attend your appointment. Missing an appointment can delay your treatment and affect your dental health. If you need to reschedule, please contact us at least 24 hours in advance to avoid any cancellation fees and to help us provide timely care to other patients.
            </li>
        </ul>
    </div>
    <div class="bottom-btn-box d-flex flex-md-row justify-content-between align-items-center">
        <div>
            <a target="_blank" :href="getCalendarUrlWithParams('Google')" class="btn btn-outline-primary m-2 d-block d-md-inline-block"><svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="28" height="28" viewBox="0 0 48 48">
                <rect width="22" height="22" x="13" y="13" fill="#fff"></rect><polygon fill="#1e88e5" points="25.68,20.92 26.688,22.36 28.272,21.208 28.272,29.56 30,29.56 30,18.616 28.56,18.616"></polygon><path fill="#1e88e5" d="M22.943,23.745c0.625-0.574,1.013-1.37,1.013-2.249c0-1.747-1.533-3.168-3.417-3.168 c-1.602,0-2.972,1.009-3.33,2.453l1.657,0.421c0.165-0.664,0.868-1.146,1.673-1.146c0.942,0,1.709,0.646,1.709,1.44 c0,0.794-0.767,1.44-1.709,1.44h-0.997v1.728h0.997c1.081,0,1.993,0.751,1.993,1.64c0,0.904-0.866,1.64-1.931,1.64 c-0.962,0-1.784-0.61-1.914-1.418L17,26.802c0.262,1.636,1.81,2.87,3.6,2.87c2.007,0,3.64-1.511,3.64-3.368 C24.24,25.281,23.736,24.363,22.943,23.745z"></path><polygon fill="#fbc02d" points="34,42 14,42 13,38 14,34 34,34 35,38"></polygon><polygon fill="#4caf50" points="38,35 42,34 42,14 38,13 34,14 34,34"></polygon><path fill="#1e88e5" d="M34,14l1-4l-1-4H9C7.343,6,6,7.343,6,9v25l4,1l4-1V14H34z"></path><polygon fill="#e53935" points="34,34 34,42 42,34"></polygon><path fill="#1565c0" d="M39,6h-5v8h8V9C42,7.343,40.657,6,39,6z"></path><path fill="#1565c0" d="M9,42h5v-8H6v5C6,40.657,7.343,42,9,42z"></path>
            </svg> Add to Google</a>
            <a target="_blank" :href="getCalendarUrlWithParams('Outlook')" class="btn btn-outline-primary m-2 d-block d-md-inline-block"><svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="28" height="28" viewBox="0 0 48 48">
                <path fill="#103262" d="M43.255,23.547l-6.81-3.967v11.594H44v-6.331C44,24.309,43.716,23.816,43.255,23.547z"></path><path fill="#0084d7" d="M13,10h10v9H13V10z"></path><path fill="#33afec" d="M23,10h10v9H23V10z"></path><path fill="#54daff" d="M33,10h10v9H33V10z"></path><path fill="#027ad4" d="M23,19h10v9H23V19z"></path><path fill="#0553a4" d="M23,28h10v9H23V28z"></path><path fill="#25a2e5" d="M33,19h10v9H33V19z"></path><path fill="#0262b8" d="M33,28h10v9H33V28z"></path><polygon points="13,37 43,37 43,24.238 28.99,32.238 13,24.238" opacity=".019"></polygon><polygon points="13,37 43,37 43,24.476 28.99,32.476 13,24.476" opacity=".038"></polygon><polygon points="13,37 43,37 43,24.714 28.99,32.714 13,24.714" opacity=".057"></polygon><polygon points="13,37 43,37 43,24.952 28.99,32.952 13,24.952" opacity=".076"></polygon><polygon points="13,37 43,37 43,25.19 28.99,33.19 13,25.19" opacity=".095"></polygon><polygon points="13,37 43,37 43,25.429 28.99,33.429 13,25.429" opacity=".114"></polygon><polygon points="13,37 43,37 43,25.667 28.99,33.667 13,25.667" opacity=".133"></polygon><polygon points="13,37 43,37 43,25.905 28.99,33.905 13,25.905" opacity=".152"></polygon><polygon points="13,37 43,37 43,26.143 28.99,34.143 13,26.143" opacity=".171"></polygon><polygon points="13,37 43,37 43,26.381 28.99,34.381 13,26.381" opacity=".191"></polygon><polygon points="13,37 43,37 43,26.619 28.99,34.619 13,26.619" opacity=".209"></polygon><polygon points="13,37 43,37 43,26.857 28.99,34.857 13,26.857" opacity=".229"></polygon><polygon points="13,37 43,37 43,27.095 28.99,35.095 13,27.095" opacity=".248"></polygon><polygon points="13,37 43,37 43,27.333 28.99,35.333 13,27.333" opacity=".267"></polygon><polygon points="13,37 43,37 43,27.571 28.99,35.571 13,27.571" opacity=".286"></polygon><polygon points="13,37 43,37 43,27.81 28.99,35.81 13,27.81" opacity=".305"></polygon><polygon points="13,37 43,37 43,28.048 28.99,36.048 13,28.048" opacity=".324"></polygon><polygon points="13,37 43,37 43,28.286 28.99,36.286 13,28.286" opacity=".343"></polygon><polygon points="13,37 43,37 43,28.524 28.99,36.524 13,28.524" opacity=".362"></polygon><polygon points="13,37 43,37 43,28.762 28.99,36.762 13,28.762" opacity=".381"></polygon><polygon points="13,37 43,37 43,29 28.99,37 13,29" opacity=".4"></polygon><linearGradient id="Qf7015RosYe_HpjKeG0QTa_ut6gQeo5pNqf_gr1" x1="38.925" x2="32.286" y1="24.557" y2="36.024" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#31abec"></stop><stop offset="1" stop-color="#1582d5"></stop></linearGradient><path fill="url(#Qf7015RosYe_HpjKeG0QTa_ut6gQeo5pNqf_gr1)" d="M15.441,42h26.563c1.104,0,1.999-0.889,2-1.994C44.007,35.485,44,24.843,44,24.843	s-0.007,0.222-1.751,1.212S14.744,41.566,14.744,41.566S14.978,42,15.441,42z"></path><linearGradient id="Qf7015RosYe_HpjKeG0QTb_ut6gQeo5pNqf_gr2" x1="13.665" x2="41.285" y1="6.992" y2="9.074" gradientUnits="userSpaceOnUse"><stop offset=".042" stop-color="#076db4"></stop><stop offset=".85" stop-color="#0461af"></stop></linearGradient><path fill="url(#Qf7015RosYe_HpjKeG0QTb_ut6gQeo5pNqf_gr2)" d="M43,10H13V8c0-1.105,0.895-2,2-2h26c1.105,0,2,0.895,2,2V10z"></path><linearGradient id="Qf7015RosYe_HpjKeG0QTc_ut6gQeo5pNqf_gr3" x1="28.153" x2="23.638" y1="33.218" y2="41.1" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#33acee"></stop><stop offset="1" stop-color="#1b8edf"></stop></linearGradient><path fill="url(#Qf7015RosYe_HpjKeG0QTc_ut6gQeo5pNqf_gr3)" d="M13,25v15c0,1.105,0.895,2,2,2h15h12.004c0.462,0,0.883-0.162,1.221-0.425L13,25z"></path><path d="M21.319,13H13v24h8.319C23.352,37,25,35.352,25,33.319V16.681C25,14.648,23.352,13,21.319,13z" opacity=".05"></path><path d="M21.213,36H13V13.333h8.213c1.724,0,3.121,1.397,3.121,3.121v16.425	C24.333,34.603,22.936,36,21.213,36z" opacity=".07"></path><path d="M21.106,35H13V13.667h8.106c1.414,0,2.56,1.146,2.56,2.56V32.44C23.667,33.854,22.52,35,21.106,35z" opacity=".09"></path><linearGradient id="Qf7015RosYe_HpjKeG0QTd_ut6gQeo5pNqf_gr4" x1="3.53" x2="22.41" y1="14.53" y2="33.41" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#1784d8"></stop><stop offset="1" stop-color="#0864c5"></stop></linearGradient><path fill="url(#Qf7015RosYe_HpjKeG0QTd_ut6gQeo5pNqf_gr4)" d="M21,34H5c-1.105,0-2-0.895-2-2V16c0-1.105,0.895-2,2-2h16c1.105,0,2,0.895,2,2v16	C23,33.105,22.105,34,21,34z"></path><path fill="#fff" d="M13,18.691c-3.111,0-4.985,2.377-4.985,5.309S9.882,29.309,13,29.309	c3.119,0,4.985-2.377,4.985-5.308C17.985,21.068,16.111,18.691,13,18.691z M13,27.517c-1.765,0-2.82-1.574-2.82-3.516	s1.06-3.516,2.82-3.516s2.821,1.575,2.821,3.516S14.764,27.517,13,27.517z"></path>
            </svg> Add to Outlook</a>
            <a :href="getCalendarUrlWithParams('ICS')" class="btn btn-outline-primary m-2 d-block d-md-inline-block"><svg height="28px" width="28px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 473.935 473.935" xml:space="preserve" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <circle style="fill:#D7DABA;" cx="236.967" cy="236.967" r="236.967"></circle> <path style="fill:#A14443;" d="M144.392,93.788c-8.819,0-15.981,7.154-15.981,15.981v255.759c0,8.827,7.162,15.981,15.981,15.981 h183.83c8.827,0,15.981-7.154,15.981-15.981V167.812l-77.047-74.024H144.392z"></path> <g> <path style="fill:#883637;" d="M344.207,167.812h-61.066c-8.827,0-15.981-7.154-15.981-15.981V93.788L344.207,167.812z"></path> <path style="fill:#883637;" d="M263.968,271.77c0,10.189-8.258,18.447-18.447,18.447H110.877c-10.189,0-18.447-8.258-18.447-18.447 v-56.351c0-10.189,8.258-18.44,18.447-18.44h134.645c10.189,0,18.447,8.251,18.447,18.44V271.77z"></path> </g> <g> <path style="fill:#FFFFFF;" d="M136.601,260.283v-29.545c0-1.534,0.352-2.687,1.048-3.454c0.696-0.767,1.605-1.152,2.713-1.152 c1.145,0,2.069,0.382,2.776,1.138c0.703,0.76,1.059,1.916,1.059,3.465v29.545c0,1.553-0.355,2.713-1.059,3.48 c-0.707,0.767-1.635,1.152-2.776,1.152c-1.093,0-1.991-0.389-2.698-1.164C136.953,262.973,136.601,261.817,136.601,260.283z"></path> <path style="fill:#FFFFFF;" d="M184.639,252.428c0,1.19-0.296,2.485-0.879,3.873c-0.591,1.392-1.515,2.754-2.776,4.094 s-2.874,2.425-4.834,3.263c-1.961,0.838-4.247,1.254-6.855,1.254c-1.976,0-3.779-0.191-5.396-0.561 c-1.616-0.37-3.091-0.958-4.415-1.755c-1.321-0.789-2.537-1.833-3.645-3.132c-0.992-1.175-1.833-2.496-2.533-3.951 c-0.7-1.463-1.224-3.02-1.572-4.67c-0.352-1.65-0.528-3.413-0.528-5.268c0-3.02,0.438-5.721,1.317-8.108s2.137-4.43,3.775-6.125 c1.639-1.695,3.555-2.99,5.755-3.877c2.2-0.887,4.546-1.328,7.035-1.328c3.038,0,5.736,0.606,8.108,1.815s4.187,2.709,5.448,4.49 s1.893,3.465,1.893,5.051c0,0.868-0.307,1.639-0.92,2.301c-0.614,0.662-1.355,0.999-2.226,0.999c-0.973,0-1.703-0.232-2.185-0.692 c-0.49-0.46-1.029-1.253-1.628-2.38c-0.992-1.86-2.152-3.248-3.491-4.168s-2.99-1.381-4.95-1.381c-3.121,0-5.605,1.186-7.457,3.555 c-1.848,2.369-2.773,5.74-2.773,10.103c0,2.915,0.408,5.343,1.227,7.278c0.819,1.934,1.976,3.375,3.48,4.333 c1.504,0.958,3.255,1.433,5.268,1.433c2.181,0,4.03-0.543,5.534-1.624c1.512-1.085,2.649-2.675,3.416-4.771 c0.326-0.988,0.722-1.792,1.201-2.417c0.479-0.621,1.246-0.932,2.301-0.932c0.902,0,1.68,0.318,2.327,0.947 C184.309,250.703,184.639,251.493,184.639,252.428z"></path> <path style="fill:#FFFFFF;" d="M219.961,253.016c0,2.268-0.584,4.307-1.755,6.114c-1.167,1.804-2.877,3.222-5.126,4.247 s-4.917,1.534-8.007,1.534c-3.701,0-6.754-0.703-9.156-2.099c-1.706-1.01-3.091-2.35-4.157-4.026 c-1.063-1.68-1.598-3.315-1.598-4.902c0-0.92,0.318-1.71,0.962-2.365c0.636-0.659,1.452-0.988,2.44-0.988 c0.801,0,1.482,0.254,2.032,0.767c0.554,0.513,1.029,1.272,1.422,2.275c0.479,1.194,0.995,2.189,1.545,2.993 c0.554,0.804,1.336,1.463,2.342,1.979c1.007,0.524,2.327,0.782,3.966,0.782c2.253,0,4.079-0.528,5.489-1.572 c1.403-1.051,2.107-2.361,2.107-3.929c0-1.246-0.382-2.256-1.137-3.031c-0.76-0.778-1.74-1.369-2.945-1.781 c-1.197-0.408-2.806-0.846-4.819-1.306c-2.694-0.629-4.95-1.366-6.765-2.215c-1.819-0.842-3.259-1.994-4.326-3.45 c-1.063-1.463-1.598-3.274-1.598-5.437c0-2.062,0.561-3.899,1.688-5.5c1.126-1.602,2.754-2.836,4.887-3.697 s4.636-1.291,7.521-1.291c2.301,0,4.292,0.284,5.972,0.857s3.076,1.328,4.183,2.275s1.92,1.942,2.428,2.978 c0.509,1.036,0.767,2.054,0.767,3.042c0,0.906-0.322,1.717-0.958,2.443c-0.644,0.726-1.441,1.089-2.395,1.089 c-0.868,0-1.53-0.217-1.983-0.651c-0.449-0.434-0.939-1.149-1.471-2.137c-0.685-1.414-1.5-2.518-2.455-3.311 c-0.954-0.793-2.488-1.19-4.602-1.19c-1.961,0-3.543,0.43-4.745,1.291c-1.205,0.861-1.804,1.897-1.804,3.109 c0,0.752,0.206,1.399,0.614,1.946c0.408,0.546,0.973,1.014,1.688,1.407c0.715,0.393,1.441,0.7,2.174,0.921 c0.733,0.221,1.946,0.546,3.633,0.973c2.114,0.498,4.03,1.036,5.74,1.639c1.717,0.599,3.173,1.325,4.378,2.174 c1.197,0.853,2.137,1.934,2.814,3.233C219.624,249.529,219.961,251.126,219.961,253.016z"></path> </g> </g></svg> Download ICS</a>
        </div>
        <a v-if="!$route.query.hidelogo" :href="'/crtx/schedule-appointment/'+$route.params.id+'/sa-step-1'" class="btn btn-primary submit-btn d-block d-md-inline-block my-2">Schedule Another Appointment</a>
    </div>
</template>
<script>
import router from '../../routes';
import { useAuthStore } from '../../stores/auth';
import { useAppointmentStore } from '../../stores/appointment';
import axios from "axios";

export default {
    setup (){
        const authStore = useAuthStore();

        const appointmentStore = useAppointmentStore();

        return { authStore, appointmentStore };
    },
    props:{
        dataForSAStep2: {
            type:Object,
            required: true,
        }
    },
    data() {
        return {
            errors: [],
            first_name:'',
            last_name:'',
            email_address:'',
            phone_number:'',
            form_data:this.form,
            id: '',
            practise_details:{},
            dateTime:'',
            patient_details:{},
            appointment_error:false,
            appointment_error_message:'',
        }
    },
    mounted(){
        this.getPractiseDetails();
        this.create_patient();
    },
    methods: {
        submit() {
            this.errors = [];
            if(Object.keys(this.errors).length>0){
                return null;
            }
            const step2value = {
                first_name: this.first_name,
                last_name: this.last_name,
                email_address: this.email_address,
                phone_number: this.phone_number,
            };
            this.$emit('form-submitted', step2value);
            router.push( {name:'sa_step_2',params: step2value} );
        },
        back(){
            this.$emit('step-back');
            router.push('/crtx/schedule-appointment/sa-step-1');
        },
        inputFilled(event){
            event.target.classList.add("filled");
        },
        dateFormat(time_date){
            if(time_date){
                const [time, date] = time_date.split('|');

                // Format date
                const formattedDateObject = new Date(date);
                const options = { weekday: 'long', month: 'long', day: 'numeric', timeZone: 'UTC' };
                this.formattedDate = formattedDateObject.toLocaleDateString('en-US', options);
                return this.formattedDate;
            }
            return ''
        },
        timeFormat(time_date){
            if(time_date){
                const [time, date, dateTime] = time_date.split('|');
                this.formattedTime = time;
                this.dateTime = dateTime;
                return time;
            }
            return ''
        },
        getCalendarUrlWithParams(type){
            const date = this.dataForSAStep2.selected_time.split('|')[1];
            const time = this.dataForSAStep2.selected_time.split('|')[0];
            const text =  this.practise_details.practice_name+' | Appointment';
            let dateTimeFrom = '';
            let dateTimeTo = '';
            const details = 'Service :- ' + this.dataForSAStep2.selected_service;
            const location = (this.practise_details.address ? this.practise_details.address + ', ' : '') + (this.practise_details.town ? this.practise_details.town+', ':'') + (this.practise_details.state? this.practise_details.state + ', ' : '') + (this.practise_details.zip ? this.practise_details.zip : '');

            if(type=='Google'){
                dateTimeFrom = moment(date).format('YYYYMMDD') + 'T' + moment(time, 'hh:mm A').format('HHmmss');
                dateTimeTo = moment(date).format('YYYYMMDD') + 'T' + moment(time, 'hh:mm A').add(this.appointmentStore.availability.duration, 'minutes').format('HHmmss');
                return 'https://calendar.google.com/calendar/r/eventedit?text='
                    + text + '&dates=' + dateTimeFrom + '/' + dateTimeTo + '&details=' + details + '&location=' + location;
            } else if(type=='Outlook'){
                dateTimeFrom = moment(date).format('YYYY-MM-DD') + 'T' + moment(time, 'hh:mm A').format('HH:mm:ss');
                dateTimeTo = moment(date).format('YYYY-MM-DD') + 'T' + moment(time, 'hh:mm A').add(this.appointmentStore.availability.duration, 'minutes').format('HH:mm:ss');
                return 'https://outlook.live.com/calendar/0/deeplink/compose?path=path=/calendar/action/compose&rru=addevent&subject='
                    + text + '&body=' + details + '&location=' + location + '&startdt=' + dateTimeFrom
                    + '&enddt=' + dateTimeTo;
            } else if(type=='ICS'){
                dateTimeFrom = moment(date).format('YYYYMMDD') + 'T' + moment(time, 'hh:mm A').format('HHmmss');
                dateTimeTo = moment(date).format('YYYYMMDD') + 'T' + moment(time, 'hh:mm A').add(this.appointmentStore.availability.duration, 'minutes').format('HHmmss');
                return "data:text/calendar;charset=utf-8,BEGIN:VCALENDAR%0D%0AVERSION:2.0%0D%0ABEGIN:VEVENT%0D%0ADTSTAMP:"+dateTimeFrom+"%0D%0ADTSTART:"+dateTimeFrom+"%0D%0ADTEND:"+dateTimeTo+"%0D%0ADESCRIPTION:"+details+"%0D%0ASUMMARY:"+text+"%0D%0ALOCATION:"+location+"%0D%0ASTATUS:CONFIRMED%0D%0ASEQUENCE:0%0D%0AEND:VEVENT%0D%0AEND:VCALENDAR";
            }
        },
        getConfig() {
            return {
                headers: {
                    Accept: "application/json",
                },
            };
        },
        getPractiseDetails() {
            let _self = this;
            const config = this.getConfig();
            const id = this.$route.params.id;
            axios
                .post("/getPractiseInfo",{id:id}, config)
                .then((response) => {
                    if (response.data.success) {
                        _self.practise_details = response.data.data;
                    } else {
                        console.log("Error fetching appointment types:", response.data.error);
                    }
                })
                .catch((error) => {
                    console.log("Error:", error);
                });
        },
        create_patient(){
            const config = this.getConfig();
            const id = this.$route.params.id;
            const appointment_id = this.$route.query.id;
            axios
                .post("/create-patient",{
                    id:id,
                    appointment_id:appointment_id,
                    date_time:this.dateTime,
                    first_name:this.dataForSAStep2.first_name,
                    last_name:this.dataForSAStep2.last_name,
                    email_address:this.dataForSAStep2.email_address,
                    phone_number:this.dataForSAStep2.phone_number,
                    service_type: this.dataForSAStep2.selected_service,
                    patient_type: this.dataForSAStep2.patient_type,
                    payment_type: this.dataForSAStep2.paymentType,
                    comments:this.dataForSAStep2.comments,
                    dob:this.dataForSAStep2.dob,
                    consent:this.dataForSAStep2.consent,
                    source: this.dataForSAStep2.source,
                    medium: this.dataForSAStep2.medium,
                    campaign: this.dataForSAStep2.campaign,
                    content: this.dataForSAStep2.content,
                }, config)
                .then((response) => {
                    localStorage.removeItem('last_path');
                    if (response) {
                        if(response.data.data && response.data.data.appointment_data.original.data.error[0] !== undefined){
                            this.appointment_error = true;
                            this.appointment_error_message = response.data.data.appointment_data.original.data.error[0];
                        }
                        console.log("Success:", response.data);
                    } else {
                        console.log("Error:", response.data);
                    }
                })
                .catch((error) => {
                    localStorage.removeItem('last_path');
                });
        }
    },
}
</script>

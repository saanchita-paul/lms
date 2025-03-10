<template>
    <h4 class="mt-2 mt-md-3">Your {{calendarType}} appointment is for:</h4>
    <h3>{{dateTimeFormat(dataForSAStep2.selected_time)}}</h3>
    <div class="alert alert-secondary mt-3 mt-md-3 text-dark">To ensure the appointment time you selected is guaranteed, we're holding it for 5 minutes.</div>
    <h2 class="mt-3 mt-md-4 mb-3">Patient Details</h2>
    <form @submit.prevent="submit">
        <div class="row gx-3">
            <div class="col-md-8 col-lg-6">
                <p class="mb-2">Personal Details</p>
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="text" name="first_name" class="form-control" placeholder="" @input="first_nameInput" v-model="first_name" :class="{'unfilled' :errors.first_name, 'filled':first_name}">
                                <label>First Name</label>
                                <span class="d-block text-danger mt-1" v-text="errors.first_name"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="text" name="last_name" class="form-control" placeholder="" @input="last_nameInput" v-model="last_name" :class="{'unfilled' :errors.last_name, 'filled':last_name}">
                                <label>Last Name</label>
                                <span class="d-block text-danger mt-1" v-text="errors.last_name"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <input type="text" id="dob" class="form-control" v-model="dob" readonly :class="{'unfilled' :errors.dob, 'filled':dob}">
                                <label>Date of Birth</label>
                                <span class="d-block text-danger mt-1" v-text="errors.dob"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <p class="mb-2">Contact Information</p>
                <div class="col-md-12">
                    <div class="form-group">
                        <input type="text" name="email_address" class="form-control" placeholder="" @input="email_addressInput" v-model="email_address" :class="{'unfilled' :errors.email_address, 'filled':email_address}" :readonly="$route.query.id" :disabled="$route.query.id">
                        <label>Email Address</label>
                        <span class="d-block text-danger mt-1" v-text="errors.email_address"></span>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <input type="text" name="phone_number" class="form-control" placeholder="" @input="phone_numberInput" v-model="phone_number" :class="{'unfilled' :errors.phone_number, 'filled':phone_number}" :readonly="$route.query.id" :disabled="$route.query.id">
                        <label>Phone Number</label>
                        <span class="d-block text-danger mt-1" v-text="errors.phone_number"></span>
                    </div>
                </div>
                <p class="mb-2">Additional Details</p>
                <div class="col-md-12">
                    <div class="form-group">
                        <textarea class="form-control" placeholder="" v-model="comments" :class="{'unfilled' :errors.comments, 'filled':comments}"></textarea>
                        <label>Comments/Requests</label>
                        <span class="d-block text-danger mt-1" v-text="errors.comments"></span>
                    </div>
                </div>
                <p class="mb-2">Payment Method</p>
                <div class="col-md-12 col-lg-12">
                    <div class="form-group">
                        <div class="check-group d-flex flex-wrap">
                            <div class="btn-checkbox mb-2 me-2">
                                <input type="radio" class="btn-check" id="insurance" value="insurance" v-model="paymentType">
                                <label class="btn btn-outline-primary sa-steps d-flex text-start" for="insurance">
                                    <div>
                                        I will be <strong class="d-block">using insurance to pay.</strong>
                                    </div>
                                </label>
                            </div>
                            <div class="btn-checkbox mb-2 me-2">
                                <input type="radio" class="btn-check" id="cash" value="cash" v-model="paymentType">
                                <label class="btn btn-outline-primary sa-steps d-flex text-start" for="cash">
                                    <div>
                                        I will be <strong class="d-block">paying without insurance (cash).</strong>
                                    </div>
                                </label>
                            </div>
                        </div>
                        <span class="d-block text-danger mt-1" v-text="errors.paymentType"></span>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" v-model="consent" value="1" id="consent" style="opacity: 1">
                        <label class="form-check-label" for="consent">
                            I agree to be contacted about dental implants by this practice and its affiliates.
                        </label>
                    </div>
                    <div class="border border-1 p-2 m-3">
                        By submitting this form, I authorize this practice and its affiliates to contact me via SMS for appointment reminders and practice information. I understand that message/data rates may apply under my cell phone plan. I may opt out of receiving these communications at any time by responding STOP to the text message. I also consent to receive reminder emails from this practice and its affiliates. I understand I can revoke my consent to receive emails at any time by using the Unsubscribe link found at the bottom of every email.
                    </div>
                    <span class="d-block text-danger mt-1" v-text="errors.consent"></span>
                </div>
            </div>
        </div>
        <div class="bottom-btn-box d-flex flex-column-reverse flex-md-row justify-content-between">
            <button class="btn btn-outline-primary me-2 d-block d-md-inline-block" @click="back">Back</button>
            <button type="submit" class="btn btn-primary submit-btn d-block d-md-inline-block mb-2 mb-md-0">{{($route.query.id ? "Reschedule" : "Schedule")}} Appointment</button>
        </div>
    </form>
</template>
<script>
import router from '../../routes';
import { useAuthStore } from '../../stores/auth';
import { useAppointmentStore } from '../../stores/appointment';

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
        },
    },
    data() {
        return {
            errors: [],
            first_name:'',
            last_name:'',
            email_address:'',
            dob:moment().format('MM/DD/YYYY'),
            phone_number:'',
            comments:'',
            paymentType:'',
            consent: null
        }
    },
    mounted(){
        let _self = this;

        $('#dob').daterangepicker({
            singleDatePicker: true,
            alwaysShowCalendars: true,
            showDropdowns: true,
            autoApply: true,
            maxDate: moment().subtract(1, 'year'),
            locale: {
                format: 'MM/DD/YYYY'
            },
        },function(start, end) {
            _self.dob = end.format('MM/DD/YYYY');
        });

        if(this.$route.query.id){
            this.first_name = this.appointmentStore.editingAppointment.crm_customer.first_name;
            this.last_name = this.appointmentStore.editingAppointment.crm_customer.last_name;
            this.dob = moment(this.appointmentStore.editingAppointment.crm_customer.dob).format('MM/DD/YYYY');
            $('#dob').data('daterangepicker').setStartDate(this.dob);
            $('#dob').data('daterangepicker').setEndDate(this.dob);
            this.email_address = this.appointmentStore.editingAppointment.crm_customer.email;
            this.phone_number = this.appointmentStore.editingAppointment.crm_customer.phone;
            this.comments = this.appointmentStore.editingAppointment.comments;
            this.paymentType = this.appointmentStore.editingAppointment.payment_type;
        }else{
            this.first_name = this.$route.query.first_name;
            this.last_name = this.$route.query.last_name;
            let date = moment(this.$route.query.dob);
            if(date.isValid()){
                this.dob = date.format('MM/DD/YYYY');
                $('#dob').data('daterangepicker').setStartDate(this.dob);
                $('#dob').data('daterangepicker').setEndDate(this.dob);
            }
            this.email_address = this.$route.query.email;
            this.phone_number = this.$route.query.phone;
            this.comments = this.$route.query.comments;
        }
    },
    methods: {
        submit() {
            this.errors = [];
            if (!this.first_name) {
                this.errors['first_name'] = 'First Name is required.';
            }
            if (!this.last_name) {
                this.errors['last_name'] = 'Last Name is required.';
            }
            if (!this.email_address) {
                this.errors['email_address'] = 'Email is required.';
            }
            if (!this.phone_number) {
                this.errors['phone_number'] = 'Phone Number is required.';
            }
            if(!this.dob){
                this.errors['dob'] = 'Date of Birth is required.';
            }
            if(!this.paymentType){
                this.errors['paymentType'] = 'Payment method is required.';
            }
            if(!this.consent){
                this.errors['consent'] = 'Consent is required.';
            }
            if(Object.keys(this.errors).length>0){
                return null;
            }
            const step2value = {
                first_name: this.first_name,
                last_name: this.last_name,
                email_address: this.email_address,
                phone_number: this.phone_number,
                dob:this.dob,
                selected_time: this.dataForSAStep2.selected_time,
                paymentType: this.paymentType,
                comments:this.comments,
                consent: this.consent,
            };
            this.$emit('form-submitted', step2value);
            router.push( {name:'sa_step_3',params: step2value, query:{id:this.$route.query.id, hidelogo:this.$route.query.hidelogo}} );
        },
        back(){
            this.$emit('step-back');
            router.push('/crtx/schedule-appointment/'+this.$route.params.id+'/sa-step-1' + (this.$route.query.id? '?id=' + this.$route.query.id : ''));
        },
        inputFilled(event){
            event.target.classList.add("filled");
        },
        dateTimeFormat(time_date){
            if(time_date){
                const [time, date] = time_date.split('|');

                // Format time
                this.formattedTime = time;

                // const [hours, minutes, seconds] = time.split(':');
                // let period = 'AM';
                //
                // let formattedHours = parseInt(hours, 10);
                // if (formattedHours >= 12) {
                //     period = 'PM';
                //     if (formattedHours > 12) {
                //         formattedHours -= 12;
                //     }
                // }
                //
                // // Format date
                const formattedDateObject = new Date(date);
                const options = { weekday: 'long', month: 'long', day: 'numeric', timeZone: 'UTC' };
                this.formattedDate = formattedDateObject.toLocaleDateString('en-US', options);
                // return this.formattedDate+' at '+`${formattedHours}:${minutes} ${period}`;
                return this.formattedDate+' at '+this.formattedTime;
            }
            return ''
        },
        first_nameInput(){
            if(this.first_name !== ''){
                this.errors['first_name'] = '';
            }
        },
        last_nameInput(){
            if(this.patientType !== ''){
                this.errors['last_name'] = '';
            }
        },
        email_addressInput(){
            if(this.email_address !== ''){
                this.errors['email_address'] = '';
            }
        },
        phone_numberInput(){
            if(this.phone_number !== ''){
                this.errors['phone_number'] = '';
            }
        }
    },
}
</script>

<template>
    <form @submit.prevent="submit">
        <div class="row gx-3">
            <div class="col-md-12">
<!--                <div class="row flex-column">
                    <div class="col-md-6">
                        <p class="mb-3">Is all information on your current website accurate? For example, bios, team members, office hours, phone number, address, technology, treatments offered? If not please describe in detail what needs to be updated.</p>
                        <div class="form-group mb-1 d-flex">
                            <div class="form-check me-3">
                                <input class="form-check-input" type="radio" name="locationsradio" id="Yes1" value="Yes" v-model="authStore.form.website_information_accurate">
                                <label class="form-check-label" for="Yes1">
                                    Yes
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="locationsradio" id="No1" value="No" v-model="authStore.form.website_information_accurate">
                                <label class="form-check-label" for="No1">
                                    No
                                </label>
                            </div>
                        </div>
                        <span class="d-block text-danger mb-3" v-text="errors.website_information_accurate"></span>
                    </div>
                    <div v-if="authStore.form.website_information_accurate=='No'" id="showOne">
                        <div class="row">
                            <div class="col-lg-8">
                                <div class="form-group">
                                    <textarea class="form-control" :class="{'unfilled' : errors.website_information_detail, 'filled':authStore.form.website_information_detail}"  v-model="authStore.form.website_information_detail"></textarea>
                                    <label>Please describe in detail.</label>
                                    <span class="d-block text-danger mt-1" v-text="errors.website_information_detail"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>-->
                <h2 class="mt-3 mb-3">Practice Management Software</h2>
                <p class="mt-3">What practice management system do you use?</p>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-2 inline-check-group d-flex flex-md-wrap">
                            <div class="btn-checkbox mb-2 me-2">
                                <input type="radio" class="btn-check" id="Eaglesoft" value="Eaglesoft" autocomplete="off" v-model="authStore.form.practice_management_system">
                                <label class="btn btn-outline-primary" for="Eaglesoft">Eaglesoft</label><br>
                            </div>
                            <div class="btn-checkbox mb-2 me-2">
                                <input type="radio" class="btn-check" id="Dentrix" value="Dentrix" autocomplete="off" v-model="authStore.form.practice_management_system">
                                <label class="btn btn-outline-primary" for="Dentrix">Dentrix</label><br>
                            </div>
                            <div class="btn-checkbox mb-2 me-2">
                                <input type="radio" class="btn-check" id="OpenDental" value="Open Dental" autocomplete="off" v-model="authStore.form.practice_management_system">
                                <label class="btn btn-outline-primary" for="OpenDental">Open Dental</label><br>
                            </div>
                            <div class="btn-checkbox mb-2 me-2">
                                <input type="radio" class="btn-check" id="None" value="None" autocomplete="off" v-model="authStore.form.practice_management_system">
                                <label class="btn btn-outline-primary" for="None">None</label><br>
                            </div>
                            <div class="btn-checkbox mb-2 me-2">
                                <input type="radio" class="btn-check" value="Other"  id="Other" autocomplete="off" v-model="authStore.form.practice_management_system">
                                <label class="btn btn-outline-primary" for="Other">Other</label><br>
                            </div>
                        </div>
<!--                        <span class="d-block text-danger mt-1" v-text="errors.practice_management_system"></span>-->
                    </div>
                    <div class="row" v-if="authStore.form.practice_management_system == 'Other'">
                        <div  class="col-md-6">
                            <div id="showOther">
                                <div class="form-group">
                                    <textarea class="form-control small-textarea" :class="{/*'unfilled' : errors.practice_different,*/ 'filled':authStore.form.practice_different}"  v-model="authStore.form.practice_different"></textarea>
                                    <label>Please describe management system.</label>
<!--                                    <span class="d-block text-danger mt-1" v-text="errors.practice_different"></span>-->
                                </div><!--/.form-group-->
                            </div>
                        </div>
                    </div>
                    <h2 class="mb-3">Invite Staff Members</h2>
                    <div class="col-md-8">
                        <p class="mb-3">To invite staff members to join your account, add their emails below. Commas can be used to separate multiple emails.</p>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <textarea class="form-control small-textarea" :class="{'unfilled' : errors.staff_emails, 'filled':authStore.form.staff_emails}"  v-model="authStore.form.staff_emails"></textarea>
                            <label>Email Addresses</label>
                            <span class="d-block text-danger mt-1" v-text="errors.staff_emails"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="bottom-btn-box">
            <a class="btn btn-outline-primary me-2" @click="back">Go Back</a>
            <button type="submit" class="btn btn-primary submit-btn px-4" v-if="!authStore.user">Complete Registration</button>
            <button type="submit" class="btn btn-primary submit-btn px-4" v-else>Continue</button>
        </div><!--/.form-group-->
    </form>
</template>
<script>
import router from '../../routes';
import { useAuthStore } from '../../stores/auth';

export default {
    setup (){
        const authStore = useAuthStore();

        return { authStore };
    },
    data() {
        return {
            errors: [],
        }
    },
    mounted(){
        // Get all input elements with class="CLASS_NAME"
        const inputs = document.querySelectorAll('input.form-control, textarea.form-control, select.form-control')

        // Iterate over elements and add each one of them a `focus` listener
        inputs.forEach(input => {
            input.addEventListener('change', this.inputFilled)
        })
    },
    methods: {
        submit() {

            this.errors = [];
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            // if(!this.authStore.form.staff_emails){
            //     this.errors['staff_emails'] = 'Email Address is required.';
            // }
            // if(this.authStore.form.staff_emails.includes(',')){
            //     console.log("more than one");
            //     const email_array = this.authStore.form.staff_emails.split(',');
            //     if (email_array.length > 0) {
            //         let valid_email = true;
            //         for (let i = 0; i < email_array.length; i++) {
            //             console.log(email_array[i]);
            //             if(!emailRegex.test(email_array[i])){
            //                 valid_email = false;
            //                 this.errors['staff_emails'] = 'Email Address is not valid.';
            //                 console.log("invalid");
            //             }
            //         }
            //     }
            // }else{
            //     if (!emailRegex.test(this.authStore.form.staff_emails)) {
            //         this.errors['staff_emails'] = 'Email Address is not valid.';
            //     }
            // }

            // if (!emailRegex.test(this.authStore.form.staff_emails)) {
            //     this.errors['staff_emails'] = 'Email Address is not valid.';
            // }
            if(Object.keys(this.errors).length>0){
                return null;
            }
            // if(!this.authStore.form.staff_emails){
            //     this.errors['staff_emails'] = 'Staff emails field is required';
            // }

            // if(!this.authStore.form.website_information_accurate){
            //     this.errors['website_information_accurate'] = 'Website information accurate field is required';
            // }

            // if(this.authStore.form.website_information_accurate === 'No' && !this.authStore.form.website_information_detail){
            //     this.errors['website_information_detail'] = 'Website information details field is required';
            // }

           /* if(!this.authStore.form.practice_management_system){
                this.errors['practice_management_system'] = 'Practice management system field is required';
            }

            if(this.authStore.form.practice_management_system === 'Other' && !this.authStore.form.practice_different){
                this.errors['practice_different'] = 'Practice management system field is required';
            }

            var self = this;
            if(this.authStore.form.staff_emails){
                var emails = this.authStore.form.staff_emails.split(',');
                emails.every(function(email){
                    if(!/^[^@]+@\w+(\.\w+)+\w$/.test(email)){
                        self.errors['staff_emails'] = 'Staff emails contains invalid emails!';
                        return false;
                    }
                    return true;
                });
            }


            if(Object.keys(this.errors).length>0){
                return null;
            }*/

            this.authStore.sendRegistrationDetails();

           // if(!this.authStore.user) {
                this.authStore.signup();
           // }else{
            //    router.push('/crtx/ai/step-1');
           // }

            this.$emit('form-submitted', this.form);
        },
        back(){
            this.$emit('step-back');
            router.push('/crtx/account/step-4');
        },
        inputFilled(event){
            event.target.classList.add("filled");
        },
    },
}
</script>

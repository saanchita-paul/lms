<template>
    <h2 class="mt-3 mt-md-5 mb-3">Doctor’s Biography: Manual or AI?</h2>
    <form @submit.prevent="submit">
        <div class="row gx-3">
            <div class="col-md-10 col-lg-8 row">
                <div class="form-group d-block col-md-4">
                    <p class="mb-2 mb-md-3 d-block">Doctor’s full name</p>
                    <input type="text" class="form-control" v-model="authStore.form.dr_fullname">
                </div>
                <div class="d-block my-2">
                    <p class="my-3 mb-md-0 d-block">Would you like to add the doctor’s biography here to be used on landing pages and other marketing material, or do you wish AI to create a compelling message based on online information that you can review, edit, and approve?</p>
                    <div class="ms-md-auto ps-0 d-block form-group my-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="doctor_biography" value="No" id="No1" v-model="authStore.form.doctors_biography">
                            <label class="form-check-label" for="No1">
                                Use AI to create the doctor’s biography.
                            </label>
                        </div>
                        <div class="form-check mb-1">
                            <input class="form-check-input" type="radio" name="doctors_biography" value="Yes" id="Yes1" v-model="authStore.form.doctors_biography">
                            <label class="form-check-label" for="Yes1">
                                I’ll provide the doctor’s biography.
                            </label>
                        </div>
                    </div>
                    <textarea v-if="authStore.form.doctors_biography=='Yes'" placeholder="Add text here or let AI fill it in." class="form-control mb-2" :class="{/*'unfilled' : errors.doctors_biography_description,*/ 'filled':authStore.form.doctors_biography_description}" v-model="authStore.form.doctors_biography_description"></textarea>
                </div>

                <div class="d-block my-2">
                    <p class="my-3 mb-md-0 d-block">Would you like to provide a brief bullet-point summary of the doctor’s bio or let AI do it?</p>
                    <div class="ms-md-auto ps-0 d-block form-group my-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="doctor_summary" value="No" id="doctorsummaryno" v-model="authStore.form.doctors_biography_summary">
                            <label class="form-check-label" for="doctorsummaryno">
                                Use AI to create a summary of the doctor’s bio.
                            </label>
                        </div>
                        <div class="form-check mb-1">
                            <input class="form-check-input" type="radio" name="doctor_summary" value="Yes" id="doctorsummaryyes" v-model="authStore.form.doctors_biography_summary">
                            <label class="form-check-label" for="doctorsummaryyes">
                                I’ll provide a summary of the doctor’s bio.
                            </label>
                        </div>
                    </div>
                    <textarea v-if="authStore.form.doctors_biography_summary=='Yes'" placeholder="Add text here or let AI fill it in." class="form-control mb-2" :class="{/*'unfilled' : errors.doctors_biography_summary_description,*/ 'filled':authStore.form.doctors_biography_summary}" v-model="authStore.form.doctors_biography_summary_description"></textarea>
                </div>

<!--                <div class="d-flex flex-md-row mb-1 flex-column align-items-start">
                    <p class="mb-2 mb-md-0">Do you have an update-to-date Google My Business page with 20+ patient reviews?</p>
                    <div class="ms-md-auto ps-md-3 ps-0 d-flex form-group mb-0">
                        <div class="form-check me-3">
                            <input class="form-check-input" type="radio" name="practicewebsite" value="Yes" id="Yes1" v-model="authStore.form.google_business_page">
                            <label class="form-check-label" for="Yes1">
                                Yes
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="practicewebsite" value="No" id="No1" v-model="authStore.form.google_business_page">
                            <label class="form-check-label" for="No1">
                                No
                            </label>
                        </div>
                    </div>
                </div>-->
                <!-- <span class="d-block text-danger mb-3" v-text="errors.google_business_page"></span> -->

<!--                <div class="d-flex flex-md-row mb-1 flex-column align-items-start">
                    <p class="mb-2 mb-md-0">Are you using a separate marketing company?</p>
                    <div class="ms-md-auto ps-md-3 ps-0 d-flex form-group mb-0">
                        <div class="form-check me-3">
                            <input class="form-check-input" type="radio" name="photography" id="Yes2" value="Yes" v-model="authStore.form.marketing_company">
                            <label class="form-check-label" for="Yes2">
                                Yes
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="photography" id="No2" value="No" v-model="authStore.form.marketing_company">
                            <label class="form-check-label" for="No2">
                                No
                            </label>
                        </div>
                    </div>
                </div>
                &lt;!&ndash; <span class="d-block text-danger mb-3" v-text="errors.marketing_company"></span> &ndash;&gt;

                <p class="mb-2">What paid media would you like to use? Select all that apply.</p>
                <div class="check-group d-flex flex-wrap mb-1">
                    <div class="btn-checkbox mb-2 me-2">
                        <input type="checkbox" class="btn-check" id="GoogleAdWords" value="Google AdWords" autocomplete="off" v-model="authStore.form.paid_media">
                        <label class="btn btn-outline-primary" for="GoogleAdWords">Google AdWords</label><br>
                    </div>
                    <div class="btn-checkbox mb-2 me-2">
                        <input type="checkbox" class="btn-check" id="Facebook" value="Facebook" autocomplete="off" v-model="authStore.form.paid_media">
                        <label class="btn btn-outline-primary" for="Facebook">Facebook</label><br>
                    </div>
                    <div class="btn-checkbox mb-2 me-2">
                        <input type="checkbox" class="btn-check" id="Instagram" value="Instagram" autocomplete="off" v-model="authStore.form.paid_media">
                        <label class="btn btn-outline-primary" for="Instagram">Instagram</label><br>
                    </div>
                    <div class="btn-checkbox mb-2 me-2">
                        <input type="checkbox" class="btn-check" id="YouTube" value="YouTube" autocomplete="off" v-model="authStore.form.paid_media">
                        <label class="btn btn-outline-primary" for="YouTube">YouTube</label><br>
                    </div>
                    <div class="btn-checkbox mb-2 me-2">
                        <input type="checkbox" class="btn-check" id="Other" value="Other" autocomplete="off" v-model="authStore.form.paid_media">
                        <label class="btn btn-outline-primary" for="Other">Other</label><br>
                    </div>
                </div>
                &lt;!&ndash; <span class="d-block text-danger mb-3" v-text="errors.paid_media"></span> &ndash;&gt;

                <div class="form-group budget-input">
                    <p class="mb-2 mb-md-3">What is your monthly media budget?</p>
                    <div class="input-group mb-1">
                        <span class="input-group-text">$</span>
                        <input type="text" class="form-control" :class="errors.media_budget? 'unfilled' : ''" v-model="authStore.form.media_budget">
                    </div>
                </div>
                &lt;!&ndash; <span class="d-block text-danger mb-3" v-text="errors.media_budget"></span> &ndash;&gt;
                <p class="my-2">What are your primary selling messages, i.e., what should be emphasized in your marketing?</p>
                <div class="form-group">
                    <textarea class="form-control" :class="{'unfilled' : errors.primary_selling_message, 'filled':authStore.form.primary_selling_message}" v-model="authStore.form.primary_selling_message"></textarea>
                    <label>Please describe in detail.</label>
                    &lt;!&ndash; <span class="d-block text-danger mt-1" v-text="errors.primary_selling_message"></span> &ndash;&gt;
                </div>-->
            </div>
        </div>
        <div class="bottom-btn-box d-flex align-items-center justify-content-end">
<!--            <a href="#" class="me-3 mt-2" @click="later">Set Up Later</a>-->
            <div>
                <button class="btn btn-outline-primary me-2" @click="back">Go Back</button>
                <button type="submit" class="btn btn-primary submit-btn">Continue</button>
            </div>
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

            // if(!this.authStore.form.marketing_company){
            //     this.errors['marketing_company'] = 'Marketing Company field is required.';
            // }

            // if(this.authStore.form.paid_media.length == 0){
            //     this.errors['paid_media'] = 'Paid Media field is required.';
            // }

            // if(!this.authStore.form.media_budget){
            //     this.errors['media_budget'] = 'Media Budget field is required.';
            // }

            // if(!this.authStore.form.primary_selling_message){
            //     this.errors['primary_selling_message'] = 'Primary Selling Message field is required.';
            // }

            if(Object.keys(this.errors).length>0){
                return null;
            }

            this.$emit('form-submitted', this.form);

            router.push('/crtx/ai/step-4');
        },
        later() {
            this.authStore.signup();
        },
        back(){
            this.$emit('step-back');
            router.push('/crtx/ai/step-2');
        },
        inputFilled(event){
            event.target.classList.add("filled");
        }
    },
}
</script>

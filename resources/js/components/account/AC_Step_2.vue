<template>
    <form @submit.prevent="submit">
<!--        <h2 class="mt-3 mt-md-5 mb-3">Doctor Details</h2>
        <div class="row gx-3">
            <div class="col-md-8 col-lg-6">
                <p class="mb-2">Primary Doctor Name</p>
                <div class="row gx-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <input
                                type="text" name="fname" class="form-control"
                                :class="{'unfilled' :errors.primary_doctor_firstname,
                                'filled':authStore.form.primary_doctor_firstname}"
                                placeholder="" v-model="authStore.form.primary_doctor_firstname"
                            >
                            <label>Primary Doctor first Name</label>
                            <span class="d-block text-danger mt-1" v-text="errors.primary_doctor_firstname"></span>
                        </div>&lt;!&ndash;/.form-group&ndash;&gt;
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <input
                                type="text" name="fname" class="form-control"
                                :class="{'unfilled' :errors.primary_doctor_lastname,
                                'filled':authStore.form.primary_doctor_lastname}"
                                placeholder="" v-model="authStore.form.primary_doctor_lastname"
                            >
                            <label>Primary Doctor last Name</label>
                            <span class="d-block text-danger mt-1" v-text="errors.primary_doctor_lastname"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row gx-3">
            <div class="col-md-8 col-lg-6">
                <p class="mb-2">Primary Doctor Email (update if different from primary account holder)</p>
                <div class="row gx-3">
                    <div class="col-md-12">
                        <div class="form-group">
                            <input
                                type="text" name="fname" class="form-control"
                                :class="{'unfilled' :errors.primary_doctor_email,
                                'filled':authStore.form.primary_doctor_email}"
                                placeholder="" v-model="authStore.form.primary_doctor_email"
                            >
                            <label>Primary Doctor Email</label>
                            <span class="d-block text-danger mt-1" v-text="errors.primary_doctor_email"></span>
                        </div>&lt;!&ndash;/.form-group&ndash;&gt;
                    </div>
                </div>
            </div>
        </div>
        <div class="row gx-3">
            <div class="col-md-8 col-lg-6">
                <p class="mb-2">Primary Doctor Mobile #</p>
                <div class="row gx-3">
                    <div class="col-md-12">
                        <div class="form-group">
                            <input
                                type="text" id="phone" name="fname" class="form-control"
                                :class="{'unfilled' :errors.primary_doctor_phone,
                                'filled':authStore.form.primary_doctor_phone}"
                                placeholder="" v-model="authStore.form.primary_doctor_phone"
                            >
                            <label>Primary Doctor Mobile #</label>
                            <span class="d-block text-danger mt-1" v-text="errors.primary_doctor_phone"></span>
                        </div>&lt;!&ndash;/.form-group&ndash;&gt;
                    </div>
                </div>
            </div>
        </div>-->
        <h2 class="mt-3 mt-md-5 mb-3">Set Up Your Practice</h2>
        <div class="row gx-3">
            <div class="col-md-8 col-lg-6">
<!--                <p class="mb-2">What Industry is Your Practice?</p>
                <div class="form-group">
                    <div class="check-group d-flex flex-wrap">
                        <div class="btn-radio-lg mb-2 me-2">
                            <input type="radio" class="btn-check" name="IndustryPractice" id="Dental" autocomplete="off" value="Dental" v-model="authStore.form.industry">
                            <label class="btn btn-outline-primary" for="Dental">Dental
                                <span class="check-ico"><svg width="10" height="9" viewBox="0 0 10 9" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M8.45208 0.833332L3.75 5.65292L1.5475 3.565L0 5.11333L3.75 8.75L10 2.38125L8.45208 0.833332Z" fill="white"/>
                                </svg></span>
                            </label>
                        </div>
                        <div class="btn-radio-lg mb-2 me-2">
                            <input type="radio" class="btn-check" name="IndustryPractice" id="Medical" autocomplete="off" value="Medical" v-model="authStore.form.industry">
                            <label class="btn btn-outline-primary" for="Medical">Medical
                                <span class="check-ico"><svg width="10" height="9" viewBox="0 0 10 9" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M8.45208 0.833332L3.75 5.65292L1.5475 3.565L0 5.11333L3.75 8.75L10 2.38125L8.45208 0.833332Z" fill="white"/>
                                </svg></span>
                            </label>
                        </div>
                    </div>
                    <span class="d-block text-danger mt-1" v-text="errors.industry"></span>
                </div>-->
                <p class="mb-2">Practice Details</p>
                <div class="form-group">
                    <input type="text" name="url" class="form-control" :class="{/*'unfilled' : errors.website_url,*/ 'filled':authStore.form.website_url}" placeholder="" v-model="authStore.form.website_url">
                    <label>Website URL</label>
<!--                    <span class="d-block text-danger mt-1" v-text="errors.website_url"></span>-->
                </div><!--/.form-group-->
                <div class="form-group">
                    <input type="text" name="pname" class="form-control" :class="{'unfilled' : errors.practice_name, 'filled':authStore.form.practice_name}" placeholder="" v-model="authStore.form.practice_name">
                    <label>Practice Name</label>
                    <span class="d-block text-danger mt-1" v-text="errors.practice_name"></span>
                </div><!--/.form-group-->
<!--                <div class="form-group">
                    <input type="text" name="plname" class="form-control" :class="{'unfilled' : errors.practice_legal_name, 'filled':authStore.form.practice_legal_name}" placeholder="" v-model="authStore.form.practice_legal_name">
                    <label>Practice Legal Name</label>
                    <span class="d-block text-danger mt-1" v-text="errors.practice_legal_name"></span>
                </div>--><!--/.form-group-->
                <div class="form-group">
                    <input type="text" name="pemail" class="form-control" :class="{/*'unfilled' : errors.practice_email,*/ 'filled':authStore.form.practice_email}" placeholder="" v-model="authStore.form.practice_email">
                    <label>Practice Email</label>
<!--                    <span class="d-block text-danger mt-1" v-text="errors.practice_email"></span>-->
                </div><!--/.form-group-->
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <input  type="text" name="Phone" id="phone" class="form-control" :class="{/*'unfilled' : errors.phone,*/ 'filled':authStore.form.phone}" placeholder="" v-model="authStore.form.phone">
                            <label>Practice Phone</label>
                            <!--                            <span class="d-block text-danger mt-1" v-text="errors.phone"></span>-->
                        </div><!--/.form-group-->
                    </div>
                    <!--                    <div class="col-md-6">
                                            <div class="form-group">
                                                <textarea  class="form-control" :class="{/*'unfilled' : errors.office_hours,*/ 'filled':authStore.form.office_hours}" name="OfficeHours"  placeholder="" v-model="authStore.form.office_hours"></textarea >
                                                <label>Office Hours</label>
                    &lt;!&ndash;                            <span class="d-block text-danger mt-1" v-text="errors.office_hours"></span>&ndash;&gt;
                                            </div>&lt;!&ndash;/.form-group&ndash;&gt;
                                        </div>-->
                </div>
            </div>
        </div>
        <div class="row">
            <div class="d-flex my-3 form-group">
                <p class="m-0 me-md-3 me-auto">Is the practice located in Canada?</p>
                <div class="form-check me-3">
                    <input class="form-check-input" type="radio" value="Yes" name="canadaradio" id="canada_yes" v-model="authStore.form.is_location_canada">
                    <label class="form-check-label" for="canada_yes">
                        Yes
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" value="No" name="canadaradio" id="canada_no" v-model="authStore.form.is_location_canada">
                    <label class="form-check-label" for="canada_no">
                        No
                    </label>
                </div>
            </div>
        </div>
        <div class="row gx-3 my-3">
            <div class="col-md-8 col-lg-8">
                <p class="mb-2">Practice Address</p>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <input  type="text" name="office_name" class="form-control" :class="{/*'unfilled' : errors.address,*/ 'filled':authStore.form.office_name}" placeholder="" v-model="authStore.form.office_name">
                            <label>Office Name</label>
                            <!--                            <span class="d-block text-danger mt-1" v-text="errors.address"></span>-->
                        </div>
                        <div class="form-group">
                            <input  type="text" name="address" class="form-control" :class="{/*'unfilled' : errors.address,*/ 'filled':authStore.form.address}" placeholder="" v-model="authStore.form.address">
                            <label>Street Address</label>
                            <!--                            <span class="d-block text-danger mt-1" v-text="errors.address"></span>-->
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <input  type="text" name="town" class="form-control" :class="{/*'unfilled' : errors.town,*/ 'filled':authStore.form.town}" placeholder="" v-model="authStore.form.town">
                            <label>City</label>
                            <!--                            <span class="d-block text-danger mt-1" v-text="errors.town"></span>-->
                        </div><!--/.form-group-->
                    </div>
                    <div class="col-md-4 col-6">
                        <div class="form-group">
                            <select v-if="authStore.form.is_location_canada == 'No'" class="form-control form-select" :class="{/*'unfilled' : errors.state,*/ 'filled':authStore.form.state}" aria-label="Default select example" v-model="authStore.form.state">
                                <option selected value=""></option>
                                <option value="Alabama">Alabama</option>
                                <option value="Alaska">Alaska</option>
                                <option value="Arizona">Arizona</option>
                                <option value="Arkansas">Arkansas</option>
                                <option value="California">California</option>
                                <option value="Colorado">Colorado</option>
                                <option value="Connecticut">Connecticut</option>
                                <option value="Delaware">Delaware</option>
                                <option value="District Of Columbia">District Of Columbia</option>
                                <option value="Florida">Florida</option>
                                <option value="Georgia">Georgia</option>
                                <option value="Hawaii">Hawaii</option>
                                <option value="Idaho">Idaho</option>
                                <option value="Illinois">Illinois</option>
                                <option value="Indiana">Indiana</option>
                                <option value="Iowa">Iowa</option>
                                <option value="Kansas">Kansas</option>
                                <option value="Kentucky">Kentucky</option>
                                <option value="Louisiana">Louisiana</option>
                                <option value="Maine">Maine</option>
                                <option value="Maryland">Maryland</option>
                                <option value="Massachusetts">Massachusetts</option>
                                <option value="Michigan">Michigan</option>
                                <option value="Minnesota">Minnesota</option>
                                <option value="Mississippi">Mississippi</option>
                                <option value="Missouri">Missouri</option>
                                <option value="Montana">Montana</option>
                                <option value="Nebraska">Nebraska</option>
                                <option value="Nevada">Nevada</option>
                                <option value="New Hampshire">New Hampshire</option>
                                <option value="New Jersey">New Jersey</option>
                                <option value="New Mexico">New Mexico</option>
                                <option value="New York">New York</option>
                                <option value="North Carolina">North Carolina</option>
                                <option value="North Dakota">North Dakota</option>
                                <option value="Ohio">Ohio</option>
                                <option value="Oklahoma">Oklahoma</option>
                                <option value="Oregon">Oregon</option>
                                <option value="Pennsylvania">Pennsylvania</option>
                                <option value="Rhode Island">Rhode Island</option>
                                <option value="South Carolina">South Carolina</option>
                                <option value="South Dakota">South Dakota</option>
                                <option value="Tennessee">Tennessee</option>
                                <option value="Texas">Texas</option>
                                <option value="Utah">Utah</option>
                                <option value="Vermont">Vermont</option>
                                <option value="Virginia">Virginia</option>
                                <option value="Washington">Washington</option>
                                <option value="West Virginia">West Virginia</option>
                                <option value="Wisconsin">Wisconsin</option>
                                <option value="Wyoming">Wyoming</option>
                            </select>
                            <select v-else class="form-control form-select" :class="{/*'unfilled' : errors.state,*/ 'filled':authStore.form.state}" aria-label="Default select example" v-model="authStore.form.state">
                                <option selected value=""></option>
                                <option value="Alberta">Alberta</option>
                                <option value="British Columbia">British Columbia</option>
                                <option value="Manitoba">Manitoba</option>
                                <option value="New Brunswick">New Brunswick</option>
                                <option value="Newfoundland and Labrador">Newfoundland and Labrador</option>
                                <option value="Northwest Territories">Northwest Territories</option>
                                <option value="Nova Scotia">Nova Scotia</option>
                                <option value="Nunavut">Nunavut</option>
                                <option value="Ontario">Ontario</option>
                                <option value="Prince Edward Island">Prince Edward Island</option>
                                <option value="Quebec">Quebec</option>
                                <option value="Saskatchewan">Saskatchewan</option>
                                <option value="Yukon Territory">Yukon Territory</option>
                            </select>
                            <label>{{ authStore.form.is_location_canada == 'Yes' ? 'Province' : 'State'  }}</label>
                            <!--                            <span class="d-block text-danger mt-1" v-text="errors.state"></span>-->
                        </div><!--/.form-group-->
                    </div>
                    <div class="col-md-4 col-6">
                        <div class="form-group">
                            <input  type="text" name="zip" class="form-control" maxlength="5" minlength="5" :class="{/*'unfilled' : errors.zip_code,*/ 'filled':authStore.form.zip_code}" placeholder="" v-model="authStore.form.zip_code">
                            <label>{{ authStore.form.is_location_canada == 'Yes' ? 'Postal Code' : 'Zip Code'  }}</label>
                            <!--                            <span class="d-block text-danger mt-1" v-text="errors.zip_code"></span>-->
                        </div><!--/.form-group-->
                    </div>
                </div>
            </div>
        </div>
        <div class="bottom-btn-box">
            <button class="btn btn-outline-primary me-2" @click="back">Go Back</button>
            <button type="submit" class="btn btn-primary submit-btn">Complete Setup</button>
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
            input.addEventListener('keydown', this.inputFilled)
        });

        IMask(document.getElementById('phone'), {
            mask: '(000) 000-0000'
        });

    },
    methods: {
        submit() {

            this.errors = [];

          /*  if(!this.authStore.form.industry){
                this.errors['industry'] = 'Industry practice field is required.';
            }*/

            // if(!this.authStore.form.website_url){
            //     this.errors['website_url'] = 'Website URL field is required.';
            // }
            //
             if(!this.authStore.form.practice_name){
                 this.errors['practice_name'] = 'Practice name field is required.';
             }

            /* if(!this.authStore.form.primary_doctor_firstname){
                 this.errors['primary_doctor_firstname'] = 'Primary Doctor First name field is required.';
             }

             if(!this.authStore.form.primary_doctor_lastname){
                 this.errors['primary_doctor_lastname'] = 'Primary Doctor Last name field is required.';
             }

             if(!this.authStore.form.primary_doctor_email){
                 this.errors['primary_doctor_email'] = 'Primary Doctor Email field is required.';
             }*/

            // if(!this.authStore.form.practice_legal_name){
            //     this.errors['practice_legal_name'] = 'Practice legal name field is required.';
            // }

            // if (!this.authStore.form.practice_email) {
            //     this.errors['practice_email'] = 'Practice Email field is required.';
            // }else if (!/^[^@]+@\w+(\.\w+)+\w$/.test(this.authStore.form.practice_email)) {
            //     this.errors['practice_email'] = 'Invalid practice email address entered.';
            // }

            if(Object.keys(this.errors).length>0){
                return null;
            }

            this.authStore.sendRegistrationDetails();

            this.authStore.signup();

            this.$emit('form-submitted', this.form);
        },
        back(){
            this.$emit('step-back');
            router.push('/crtx/account/step-1');
        },
        inputFilled(event){
            event.target.classList.add("filled");
        }
    },
}
</script>

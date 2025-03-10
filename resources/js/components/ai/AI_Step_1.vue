<template>
    <h2 class="mt-3 mt-md-5 mb-3">Practice Service Details</h2>
    <form @submit.prevent="submit">
        <input type="hidden" id="new_clinic_id" name="new_clinic_id" v-model="new_clinic_id">
        <div class="row gx-3">
            <div class="col-md-12">
                <div class="d-flex align-items-end flex-row mb-3 flex-md-column  align-items-md-start">
                    <p class="mb-2">Should the marketing promote multiple doctors?</p>
                    <div class="ps-md-0 ps-3 d-flex form-group mb-0">
                        <div class="form-check me-3">
                            <input class="form-check-input" value="Yes" type="radio" name="locationsradio" id="Yes1" v-model="authStore.form.multiple_doctors">
                            <label class="form-check-label" for="Yes1">
                                Yes
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" value="No" type="radio" name="locationsradio" id="No1" v-model="authStore.form.multiple_doctors">
                            <label class="form-check-label" for="No1">
                                No
                            </label>
                        </div>
                    </div>
<!--                    <span class="d-block text-danger mt-1" v-text="errors.multiple_doctors"></span>-->
                </div>
                <div v-if="authStore.form.multiple_doctors=='Yes'" id="showOne">
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="form-group">
                                <textarea class="form-control" :class="{/*'unfilled' : errors.multiple_doctors_details, */'filled':authStore.form.multiple_doctors_details}" v-model="authStore.form.multiple_doctors_details"></textarea>
                                <label>If yes, what are the other doctors’ names?</label>
<!--                                <span class="d-block text-danger mt-1" v-text="errors.multiple_doctors_details"></span>-->
                            </div>
                        </div>
                    </div>
                </div>
<!--                <div class="form-group check-list-group">-->
<!--                    <div class="d-flex mb-2">-->
<!--                        <p class="mb-0 me-auto">Practice Specialty</p>-->
<!--                        <span class="selectallcheck">Select all that apply.</span>-->
<!--                    </div>-->
<!--                    <div class="check-group d-flex flex-wrap">-->
<!--                        <div class="btn-checkbox mb-2 me-2">-->
<!--                            <input type="checkbox" class="btn-check" id="GeneralPractitioner" value="General Dentist" autocomplete="off" v-model="authStore.form.practice_speciality">-->
<!--                            <label class="btn btn-outline-primary" for="GeneralPractitioner">General Dentist</label><br>-->
<!--                        </div>-->
<!--                        <div class="btn-checkbox mb-2 me-2">-->
<!--                            <input type="checkbox" class="btn-check" id="CosmeticDentist" value="Cosmetic Dentist" autocomplete="off" v-model="authStore.form.practice_speciality">-->
<!--                            <label class="btn btn-outline-primary" for="CosmeticDentist">Cosmetic Dentist</label><br>-->
<!--                        </div>-->
<!--                        <div class="btn-checkbox mb-2 me-2">-->
<!--                            <input type="checkbox" class="btn-check" id="OralSurgeon" value="Oral Surgeon" autocomplete="off" v-model="authStore.form.practice_speciality">-->
<!--                            <label class="btn btn-outline-primary" for="OralSurgeon">Oral Surgeon</label><br>-->
<!--                        </div>-->
<!--                        <div class="btn-checkbox mb-2 me-2">-->
<!--                            <input type="checkbox" class="btn-check" id="Periodontist" value="Periodontist" autocomplete="off" v-model="authStore.form.practice_speciality">-->
<!--                            <label class="btn btn-outline-primary" for="Periodontist">Periodontist</label><br>-->
<!--                        </div>-->
<!--                        <div class="btn-checkbox mb-2 me-2">-->
<!--                            <input type="checkbox" class="btn-check" id="Prosthodontist" value="Prosthodontist" autocomplete="off" v-model="authStore.form.practice_speciality">-->
<!--                            <label class="btn btn-outline-primary" for="Prosthodontist">Prosthodontist</label><br>-->
<!--                        </div>-->
<!--                        <div class="btn-checkbox mb-2 me-2">-->
<!--                            <input type="checkbox" class="btn-check" id="MultiSpecialty" value="Multi-Speciality" autocomplete="off" v-model="authStore.form.practice_speciality">-->
<!--                            <label class="btn btn-outline-primary" for="MultiSpecialty">Multi-Specialty</label><br>-->
<!--                        </div>-->
<!--                    </div>-->
<!--&lt;!&ndash;                    <span class="d-block text-danger mt-1" v-text="errors.practice_speciality"></span>&ndash;&gt;-->
<!--                </div>-->
<!--                <div v-if="authStore.form.practice_speciality.includes('Multi-Speciality')" id="showOne">-->
<!--                    <div class="row">-->
<!--                        <div class="col-lg-8">-->
<!--                            <div class="form-group">-->
<!--                                <textarea class="form-control" :class="{/*'unfilled' : errors.practice_speciality_other,*/ 'filled':authStore.form.practice_specialty_other}" v-model="authStore.form.practice_specialty_other"></textarea>-->
<!--                                <label>Please enter multi-speciality.</label>-->
<!--&lt;!&ndash;                                <span class="d-block text-danger mt-1" v-text="errors.practice_speciality_other"></span>&ndash;&gt;-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
<!--                <div class="form-group check-list-group">-->
<!--                    <div class="d-flex mb-2">-->
<!--                        <p class="mb-0 me-auto">Primary Services</p>-->
<!--                        <span class="selectallcheck">Select all that apply.</span>-->
<!--                    </div>-->
<!--                    <div class="check-group d-flex flex-wrap">-->
<!--                        <div class="btn-checkbox mb-2 me-2">-->
<!--                            <input type="checkbox" class="btn-check" id="PrimaryServices1" value="Dental Implants (includes Full Arch)" autocomplete="off" v-model="authStore.form.primary_services">-->
<!--                            <label class="btn btn-outline-primary" for="PrimaryServices1">Dental Implants (includes Full Arch)</label><br>-->
<!--                        </div>-->
<!--                        <div class="btn-checkbox mb-2 me-2">-->
<!--                            <input type="checkbox" class="btn-check" id="PrimaryServices2" value="Cosmetic Dentistry (includes Veneers)" autocomplete="off" v-model="authStore.form.primary_services">-->
<!--                            <label class="btn btn-outline-primary" for="PrimaryServices2">Cosmetic Dentistry (includes Veneers)</label><br>-->
<!--                        </div>-->
<!--                        <div class="btn-checkbox mb-2 me-2">-->
<!--                            <input type="checkbox" class="btn-check" id="PrimaryServices3" value="Traditional OMS services" autocomplete="off" v-model="authStore.form.primary_services">-->
<!--                            <label class="btn btn-outline-primary" for="PrimaryServices3">Traditional OMS services</label><br>-->
<!--                        </div>-->
<!--                        <div class="btn-checkbox mb-2 me-2">-->
<!--                            <input type="checkbox" class="btn-check" id="PrimaryServices4" value="New Patient/Emergency" autocomplete="off" v-model="authStore.form.primary_services">-->
<!--                            <label class="btn btn-outline-primary" for="PrimaryServices4">New Patient/Emergency</label><br>-->
<!--                        </div>-->
<!--                        <div class="btn-checkbox mb-2 me-2">-->
<!--                            <input type="checkbox" class="btn-check" id="PrimaryServices5" value="Traditional GP services (includes Hygiene)" autocomplete="off" v-model="authStore.form.primary_services">-->
<!--                            <label class="btn btn-outline-primary" for="PrimaryServices5">Traditional GP services (includes Hygiene)</label><br>-->
<!--                        </div>-->
<!--                        <div class="btn-checkbox mb-2 me-2">-->
<!--                            <input type="checkbox" class="btn-check" id="PrimaryServices6" value="Orthodontics and/or Clear Braces" autocomplete="off" v-model="authStore.form.primary_services">-->
<!--                            <label class="btn btn-outline-primary" for="PrimaryServices6">Orthodontics and/or Clear Braces</label><br>-->
<!--                        </div>-->
<!--                        <div class="btn-checkbox mb-2 me-2">-->
<!--                            <input type="checkbox" class="btn-check" id="PrimaryServices7" value="Traditional Perio services" autocomplete="off" v-model="authStore.form.primary_services">-->
<!--                            <label class="btn btn-outline-primary" for="PrimaryServices7">Traditional Perio services</label><br>-->
<!--                        </div>-->
<!--                        <div class="btn-checkbox mb-2 me-2">-->
<!--                            <input type="checkbox" class="btn-check" id="PrimaryServices8" value="Other" autocomplete="off" v-model="authStore.form.primary_services">-->
<!--                            <label class="btn btn-outline-primary" for="PrimaryServices8">Other</label><br>-->
<!--                        </div>-->
<!--                    </div>-->
<!--&lt;!&ndash;                    <span class="d-block text-danger mt-1" v-text="errors.primary_services"></span>&ndash;&gt;-->
<!--                </div>-->
<!--                <div v-if="authStore.form.primary_services.includes('Other')" id="showOne">-->
<!--                    <div class="row">-->
<!--                        <div class="col-lg-8">-->
<!--                            <div class="form-group">-->
<!--                                <textarea class="form-control" :class="{/*'unfilled' : errors.primary_services_other,*/ 'filled':authStore.form.primary_services_other}" v-model="authStore.form.primary_services_other"></textarea>-->
<!--                                <label>Please describe services.</label>-->
<!--&lt;!&ndash;                                <span class="d-block text-danger mt-1" v-text="errors.primary_services_other"></span>&ndash;&gt;-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
            </div>
        </div>
        <h2 class="mt-3 mb-3">About Your Practice</h2>
        <div class="row gx-3">
            <div class="col-md-10 col-lg-8">
                <span>
                    CRTX will use the current information available on your website (including doctor bio(s), photography, technology, etc.) to build your landing pages and marketing messaging. If there are any inaccuracies present, you MUST upload replacement documents here:
                    [example of replacement documents: Doctor bio, updated photos/before and afters, list of available technology, office hours, etc.]
                </span>
                <div class="d-flex flex-md-row my-3 flex-column align-items-start">
                    <p class="mb-2 mb-md-0">Should we use your website for your practice information, including photography and doctor biography? If yes, we will scrape all the photos and text from your website.</p>
                    <div class="ms-md-auto ps-md-3 ps-0 d-flex form-group mb-0">
                        <div class="form-check me-3">
                            <input class="form-check-input" type="radio" name="photography" id="Yes2" value="Yes" v-model="authStore.form.website_information_accurate">
                            <label class="form-check-label" for="Yes2">
                                Yes
                            </label>
                        </div>
                        <div class="form-check" style="width:200px;">
                            <input class="form-check-input" type="radio" name="photography" id="No2" value="No" v-model="authStore.form.website_information_accurate">
                            <label class="form-check-label" for="No2">
                                No, contact me instead.
                            </label>
                        </div>
                    </div>
                </div>
                <div class="d-flex flex-md-row my-3 flex-column align-items-start">
                    <p class="mb-2 mb-md-0">Do you have practice and patient videos hosted on YouTube to use on your website? If yes, provide the link below.</p>
                    <div class="ms-md-auto ps-md-3 ps-0 d-flex form-group mb-0">
                        <div class="form-check me-3">
                            <input class="form-check-input" type="radio" name="video" id="Yes3" value="Yes" v-model="authStore.form.testimonial_links">
                            <label class="form-check-label" for="Yes3">
                                Yes
                            </label>
                        </div>
                        <div class="form-check" style="width:200px;">
                            <input class="form-check-input" type="radio" name="video" id="No3" value="No" v-model="authStore.form.testimonial_links">
                            <label class="form-check-label" for="No3">
                                No, contact me instead.
                            </label>
                        </div>
                    </div>
                </div>

                <div v-if="authStore.form.testimonial_links == 'Yes'" id="showtestimonials">
                    <div class="form-group">
                        <textarea class="form-control small-textarea" :class="{/*'unfilled' : errors.testimonial_video_urls,*/ 'filled':authStore.form.testimonial_video_urls}"  v-model="authStore.form.testimonial_video_urls"></textarea>
                        <label>Provide a link to your YouTube page</label>
                    </div>
                </div>

                <div class="d-flex flex-md-row my-2 flex-column align-items-start">
                    <p class="mb-2 mb-md-0">Do you work with a financing company?</p>
                    <div class="ms-md-auto ps-md-3 ps-0 d-flex form-group mb-0">
                        <div class="form-check me-3">
                            <input class="form-check-input" value="Yes" type="radio" id="YesFinanceCompany1" v-model="authStore.form.work_financing_company">
                            <label class="form-check-label" for="YesFinanceCompany1">
                                Yes
                            </label>
                        </div>
                        <div class="form-check" style="width:200px;" >
                            <input class="form-check-input" value="No" type="radio"  id="YesFinanceCompany2" v-model="authStore.form.work_financing_company">
                            <label class="form-check-label" for="YesFinanceCompany2">
                                No
                            </label>
                        </div>
                    </div>
                    <!-- <span class="d-block text-danger mb-3" v-text="errors.work_financing_company"></span> -->
                </div>
                <div id="showfinancingCompanyyes"  v-if="authStore.form.work_financing_company=='Yes'">
                    <div class="check-group d-flex flex-wrap mb-1">
                        <div class="btn-checkbox mb-2 me-2">
                            <input type="checkbox" class="btn-check" value="Proceed" id="Proceed" autocomplete="off" v-model="authStore.form.financing_company">
                            <label class="btn btn-outline-primary" for="Proceed">Proceed</label><br>
                        </div>
                        <div class="btn-checkbox mb-2 me-2">
                            <input type="checkbox" class="btn-check" value="CareCredit" id="CareCredit" autocomplete="off" v-model="authStore.form.financing_company">
                            <label class="btn btn-outline-primary" for="CareCredit">Care Credit</label><br>
                        </div>
                        <div class="btn-checkbox mb-2 me-2">
                            <input type="checkbox" class="btn-check" value="Alpheon" id="Alpheon" autocomplete="off" v-model="authStore.form.financing_company">
                            <label class="btn btn-outline-primary" for="Alpheon">Alpheon</label><br>
                        </div>
                        <div class="btn-checkbox mb-2 me-2">
                            <input type="checkbox" class="btn-check" value="Sunbit" id="Sunbit" autocomplete="off" v-model="authStore.form.financing_company">
                            <label class="btn btn-outline-primary" for="Sunbit">Sunbit</label><br>
                        </div>
                        <div class="btn-checkbox mb-2 me-2">
                            <input type="checkbox" class="btn-check" value="PatientFi" id="PatientFi" autocomplete="off" v-model="authStore.form.financing_company">
                            <label class="btn btn-outline-primary" for="PatientFi">PatientFi</label><br>
                        </div>
                        <div class="btn-checkbox mb-2 me-2">
                            <input type="checkbox" class="btn-check" value="Ally" id="ally" autocomplete="off" v-model="authStore.form.financing_company">
                            <label class="btn btn-outline-primary" for="ally">Ally</label><br>
                        </div>
                        <div class="btn-checkbox mb-2 me-2">
                            <input type="checkbox" class="btn-check" value="FinancingInHouse" id="FinancingInHouse" autocomplete="off" v-model="authStore.form.financing_company">
                            <label class="btn btn-outline-primary" for="FinancingInHouse">Financing in-house</label><br>
                        </div>
                        <div class="btn-checkbox mb-2 me-2">
                            <input type="checkbox" class="btn-check" value="Other" id="other" autocomplete="off" v-model="authStore.form.financing_company">
                            <label class="btn btn-outline-primary" for="other">Other</label><br>
                        </div>
                    </div>
<!--                    <div v-if="authStore.form.financing_company == 'Other'" id="showOtherCompany">-->
<!--                        <div class="form-group">-->
<!--                            <textarea class="form-control small-textarea"  v-model="authStore.form.financing_company_other"></textarea>-->
<!--                            <label>Other Company</label>-->
<!--                        </div>-->
<!--                    </div>-->
                    <!--                    <span class="d-block text-danger mb-3" v-text="errors.financing_company"></span>-->
                    <div id="showOther2" class="col-xxl w-100" v-if="authStore.form.financing_company.includes('Other')">
                        <div class="form-group">
                            <textarea class="form-control" :class="{/*'unfilled' : errors.financing_company_other,*/ 'filled':authStore.form.financing_company_other}" v-model="authStore.form.financing_company_other"></textarea>
                            <label>Please list other companies.</label>
                        </div><!--/.form-group-->
                        <!--                        <span class="d-block text-danger mb-3" v-text="errors.financing_company_other"></span>-->
                    </div>
                </div>
            </div>
        </div>
        <div class="bottom-btn-box">
<!--            <button @click="back" class="btn btn-outline-primary me-2">Go Back</button>-->
            <button type="submit" class="btn btn-primary submit-btn">Continue</button>
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
           /* errors: [],*/
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
            /*this.errors = [];*/

         /*   if(!this.authStore.form.multiple_doctors){
                this.errors['multiple_doctors'] = 'Multiple Doctors field is required.';
            }

            if(this.authStore.form.multiple_doctors == 'Yes' && !this.authStore.form.multiple_doctors_details){
                this.errors['multiple_doctors_details'] = 'Multiple Doctor Details field is required.';
            }

            if(!this.authStore.form.practice_speciality){
                this.errors['practice_speciality'] = 'Practice Speciality field is required.';
            }else if(this.authStore.form.practice_speciality.includes('Multi-Speciality') && !this.authStore.form.practice_specialty_other){
                this.errors['practice_speciality_other'] = 'Multi-Speciality field is required.';
            }

            if(this.authStore.form.primary_services.length==0){
                this.errors['primary_services'] = 'Primary Services field is required.';
            }*/

          /*  if(Object.keys(this.errors).length>0){
                return null;
            }*/

            this.$emit('form-submitted', this.form);

            router.push('/crtx/ai/step-2');
        },
        inputFilled(event){
            event.target.classList.add("filled");
        }
    },
}
</script>

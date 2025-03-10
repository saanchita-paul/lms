<template>
    <h2 class="mt-3 mt-md-5 mb-3">About Your Practice</h2>
    <form @submit.prevent="submit">
        <div class="row gx-3">
            <div class="col-md-10 col-lg-8">
<!--                <p class="mb-2">Do you want to use your Website or a separate Microsite?</p>-->
<!--                <div class="row flex-md-row flex-column">-->
<!--                    <div class="col">-->
<!--                        <div class="form-group radio-group d-flex">-->
<!--                            <div class="btn-checkbox mb-1 me-2">-->
<!--                                <input type="radio" class="btn-check" value="Main Website" name="Website1" id="CurrentWebsite" v-model="authStore.form.website_type">-->
<!--                                <label class="btn btn-outline-primary" for="CurrentWebsite">Current Website</label><br>-->
<!--                            </div>-->
<!--                            <div class="btn-checkbox mb-1 me-2">-->
<!--                                <input type="radio" class="btn-check" value="Microsite" name="Website2" id="SeparateMicrosite" v-model="authStore.form.website_type">-->
<!--                                <label class="btn btn-outline-primary" for="SeparateMicrosite">Separate Microsite</label><br>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                    &lt;!&ndash; <span class="d-block text-danger mb-3" v-text="errors.use_microsite"></span> &ndash;&gt;-->
<!--                </div>-->

<!--                <div class="row">-->
<!--                    <div class="col-md-8">-->
<!--                        <div id="showSeparateMicrosite">-->
<!--                            <div class="form-group" >-->
<!--                                <input type="text" name="url" class="form-control" :class="{/*'unfilled' : errors.ai_website_url,*/ 'filled':authStore.form.ai_website_url}" placeholder="" v-model="authStore.form.ai_website_url">-->
<!--                                <label>Provide website address</label>-->
<!--&lt;!&ndash;                                <span class="d-block text-danger mt-1" v-text="errors.ai_website_url"></span>&ndash;&gt;-->
<!--                            </div>&lt;!&ndash;/.form-group&ndash;&gt;-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
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

<!--
                <div class="d-flex flex-md-row mb-1 flex-column align-items-start">
                    <p class="mb-2 mb-md-0">Do you have professional photography of the doctor and office that can be used for marketing?</p>
                    <div class="ms-md-auto ps-md-3 ps-0 d-flex form-group mb-0">
                        <div class="form-check me-3">
                            <input class="form-check-input" type="radio" name="photography" id="Yes2" value="Yes" v-model="authStore.form.professional_photography">
                            <label class="form-check-label" for="Yes2">
                                Yes
                            </label>
                        </div>
                        <div class="form-check" style="width:200px;">
                            <input class="form-check-input" type="radio" name="photography" id="No2" value="No" v-model="authStore.form.professional_photography">
                            <label class="form-check-label" for="No2">
                                No
                            </label>
                        </div>
                    </div>
                </div>
                 <span class="d-block text-danger mb-3" v-text="errors.professional_photography"></span>
-->
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
<!--                        <span class="d-block text-danger mt-1" v-text="errors.testimonial_video_urls"></span>-->
                    </div>
                </div>

                <!-- <span class="d-block text-danger mb-3" v-text="errors.professional_video"></span> -->

<!--                <div class="d-flex flex-md-row mb-1 flex-column align-items-start">
                    <p class="mb-2 mb-md-0">Do you have quality patient before and after photos?</p>
                    <div class="ms-md-auto ps-md-3 ps-0 d-flex form-group mb-0">
                        <div class="form-check me-3">
                            <input class="form-check-input" type="radio" name="patient" value="Yes" id="Yes4" v-model="authStore.form.quality_patient">
                            <label class="form-check-label" for="Yes4">
                                Yes
                            </label>
                        </div>
                        <div class="form-check" style="width:200px;">
                            <input class="form-check-input" type="radio" name="patient" value="No" id="No4" v-model="authStore.form.quality_patient">
                            <label class="form-check-label" for="No4">
                                No
                            </label>
                        </div>
                    </div>
                </div>-->
                <!-- <span class="d-block text-danger mb-3" v-text="errors.quality_patient"></span> -->

<!--                <div class="d-flex flex-md-row mb-1 flex-column align-items-start">
                    <p class="mb-2 mb-md-0">Do you have practice and patient videos hosted on YouTube to use on your website? If yes, provide the link below.</p>
                    <div class="ms-md-auto ps-md-3 ps-0 d-flex form-group mb-0">
                        <div class="form-check me-3">
                            <input class="form-check-input" value="Yes" type="radio" name="testimonial" id="Yes5" v-model="authStore.form.testimonial_links">
                            <label class="form-check-label" for="Yes5">
                                Yes
                            </label>
                        </div>
                        <div class="form-check" style="width:200px;">
                            <input class="form-check-input" type="radio" value="No" name="testimonial" id="No5" v-model="authStore.form.testimonial_links">
                            <label class="form-check-label" for="No5">
                                No, contact me instead.
                            </label>
                        </div>
                    </div>
                </div>
                 <span class="d-block text-danger mb-3" v-text="errors.testimonial_videos"></span> -->

<!--                <p class="my-1">Do you have the following technology? Select all that apply.</p>-->
<!--                <div class="check-group d-flex flex-wrap">-->
<!--                    <div class="btn-checkbox mb-2 me-2">-->
<!--                        <input type="checkbox" class="btn-check" id="IntraoralScanner" value="Intraoral Scanner" autocomplete="off" v-model="authStore.form.technologies">-->
<!--                        <label class="btn btn-outline-primary" for="IntraoralScanner">Intraoral Scanner</label><br>-->
<!--                    </div>-->
<!--                    <div class="btn-checkbox mb-2 me-2">-->
<!--                        <input type="checkbox" class="btn-check" id="AnalysisSoftware" value="Digital Smile Analysis Software" autocomplete="off" v-model="authStore.form.technologies">-->
<!--                        <label class="btn btn-outline-primary" for="AnalysisSoftware">Digital Smile Analysis Software</label><br>-->
<!--                    </div>-->
<!--                    <div class="btn-checkbox mb-2 me-2">-->
<!--                        <input type="checkbox" class="btn-check" id="HouseMill" value="In-House Mill" autocomplete="off" v-model="authStore.form.technologies">-->
<!--                        <label class="btn btn-outline-primary" for="HouseMill">In-House Mill</label><br>-->
<!--                    </div>-->
<!--                    <div class="btn-checkbox mb-2 me-2">-->
<!--                        <input type="checkbox" class="btn-check" id="Photogrammetry" value="Photogrammetry" autocomplete="off" v-model="authStore.form.technologies">-->
<!--                        <label class="btn btn-outline-primary" for="Photogrammetry">Photogrammetry</label><br>-->
<!--                    </div>-->
<!--                    <div class="btn-checkbox mb-2 me-2">-->
<!--                        <input type="checkbox" class="btn-check" id="Printing" value="Printing" autocomplete="off" v-model="authStore.form.technologies">-->
<!--                        <label class="btn btn-outline-primary" for="Printing">Printing</label><br>-->
<!--                    </div>-->
<!--                    <div class="btn-checkbox mb-2 me-2">-->
<!--                        <input type="checkbox" class="btn-check" id="GuidedSurgery" value="Guided Surgery" autocomplete="off" v-model="authStore.form.technologies">-->
<!--                        <label class="btn btn-outline-primary" for="GuidedSurgery">Guided Surgery</label><br>-->
<!--                    </div>-->
<!--                    <div class="btn-checkbox mb-2 me-2">-->
<!--                        <input type="checkbox" class="btn-check" value="Other"  id="Other" autocomplete="off" v-model="authStore.form.technologies">-->
<!--                        <label class="btn btn-outline-primary" for="Other">Other</label><br>-->
<!--                    </div>-->
<!--                </div>-->
                <!-- <span class="d-block text-danger mb-3" v-text="errors.technologies"></span> -->

<!--                <div id="showOther1" class="col-xxl w-100" v-if="authStore.form.technologies.includes('Other')">-->
<!--                    <div class="form-group">-->
<!--                        <textarea class="form-control" :class="{/*'unfilled' : errors.technologies_other,*/ 'filled':authStore.form.technologies_other}" v-model="authStore.form.technologies_other"></textarea>-->
<!--                        <label>Please describe technology.</label>-->
<!--&lt;!&ndash;                        <span class="d-block text-danger mb-3" v-text="errors.technologies_other"></span>&ndash;&gt;-->
<!--                    </div>&lt;!&ndash;/.form-group&ndash;&gt;-->
<!--                </div>-->

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
                            <input type="checkbox" class="btn-check" value="None" id="None" autocomplete="off" v-model="authStore.form.financing_company">
                            <label class="btn btn-outline-primary" for="None">None</label><br>
                        </div>
                    </div>
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

        <div class="bottom-btn-box d-flex align-items-center justify-content-end">
<!--            <a href="#" class="me-3 mt-2" @click="later">Set Up Later</a>-->
            <div>
                <button class="btn btn-outline-primary me-2" @click="back">Go Back</button>
                <button type="submit" class="btn btn-primary submit-btn px-4">Continue</button>
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
            /*errors: [],*/
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

           /* if(!this.authStore.form.ai_website_url){
                this.errors['ai_website_url'] = 'Microsite Url field is required.';
            }*/

           /* if(this.authStore.form.testimonial_links == 'Yes' && !this.authStore.form.testimonial_video_urls){
                this.errors['testimonial_video_urls'] = 'Testimonial Video urls field is required.';
            }

            if(this.authStore.form.technologies.includes('Other') && !this.authStore.form.technologies_other){
                this.errors['technologies_other'] = 'Technology Other field is required.';
            }

            if(this.authStore.form.work_financing_company == 'Yes' && !this.authStore.form.financing_company){
                this.errors['financing_company'] = 'Financing Company field is required';
            }

            if(this.authStore.form.financing_company.includes('Other') && !this.authStore.form.financing_company_other){
                this.errors['financing_company_other'] = 'Financing Company Other field is required';
            }

            if(Object.keys(this.errors).length>0){
                return null;
            }*/

            this.$emit('form-submitted', this.form);

            router.push('/crtx/ai/step-3');
        },
        back(){
            this.$emit('step-back');
            router.push('/crtx/ai/step-1');
        },
        later() {
            this.authStore.signup();
        },
        inputFilled(event){
            event.target.classList.add("filled");
        }
    },
}
</script>

<template>
    <h2 class="mt-3 mt-md-5 mb-3">Marketing Messaging: Manual or AI?</h2>
    <div v-if="authStore.serverError" class="alert alert-danger alert-dismissible fade show" role="alert">
        We enccountered a server error processing your request. Please try again later.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" @click="authStore.serverError = false"></button>
    </div>
    <form @submit.prevent="submit">
        <div class="row gx-3">
            <div class="col-md-10 col-lg-8 col-xl-7">
<!--                <div class="d-block my-2">-->
<!--                    <p class="my-3 mb-md-0 d-block">Would you like to provide a brief marketing message that promotes the practice to be used on landing pages and in other marketing material, or do you wish AI to create a compelling message based on information available online that you can review, edit, and approve?</p>-->
<!--                    <div class="ms-md-auto ps-0 d-block form-group my-3">-->
<!--                        <div class="form-check">-->
<!--                            <input class="form-check-input" type="radio" name="marketing_message" value="No" id="marketingmessageno" v-model="authStore.form.marketing_message">-->
<!--                            <label class="form-check-label" for="marketingmessageno">-->
<!--                                Use AI to create the marketing message.-->
<!--                            </label>-->
<!--                        </div>-->
<!--                        <div class="form-check mb-1">-->
<!--                            <input class="form-check-input" type="radio" name="marketing_message" value="Yes" id="marketingmessageyes" v-model="authStore.form.marketing_message">-->
<!--                            <label class="form-check-label" for="marketingmessageyes">-->
<!--                                I’ll provide a brief marketing message.-->
<!--                            </label>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                    <textarea v-if="authStore.form.marketing_message=='Yes'" placeholder="Add text here or let AI fill it in." class="form-control mb-2" :class="{/*'unfilled' : errors.marketing_message_description,*/ 'filled':authStore.form.marketing_message_description}" v-model="authStore.form.marketing_message_description"></textarea>-->
<!--                </div>-->

                <div class="d-block my-2">
                    <p class="my-3 mb-md-0 d-block">What special offers are available to your patients? [example: complimentary consultation, full arch dental implants starting at $19999, single implant starting at $3495, 10% off each veneer when you purchase 6 or more, $149 new patient special, etc.]</p>
                    <textarea  placeholder="Add text here or let AI fill it in." class="form-control my-2" :class="{/*'unfilled' : errors.special_pricing, */'filled':authStore.form.special_pricing}" v-model="authStore.form.special_pricing"></textarea>
                </div>

<!--                <div class="d-flex flex-md-row mb-3 flex-column align-items-start">
                    <p class="mb-2 mb-md-0">Do you wish to promote any pricing or other promotional specials?</p>
                    <div class="ms-md-auto ps-md-3 ps-0 d-flex form-group mb-0">
                        <div class="form-check me-3">
                            <input class="form-check-input" value="Yes" type="radio" name="promotionalSpecials" id="Yes1" v-model="authStore.form.pricing_promotional">
                            <label class="form-check-label" for="Yes1">
                                Yes
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" value="No" type="radio" name="promotionalSpecials" id="No1" v-model="authStore.form.pricing_promotional">
                            <label class="form-check-label" for="No1">
                                No
                            </label>
                        </div>
                    </div>
                </div>-->
                <!-- <span class="d-block text-danger mb-3" v-text="errors.pricing_promotional"></span> -->

<!--                <div id="showpromotionalSpecialsyes" v-if="authStore.form.pricing_promotional == 'Yes'">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <textarea class="form-control small-textarea" :class="{'unfilled' : errors.pricing_promotional_details, 'filled':authStore.form.pricing_promotional_details}" v-model="authStore.form.pricing_promotional_details"></textarea>
                                <label>Please describe in detail.</label>
                                <span class="d-block text-danger mt-1" v-text="errors.pricing_promotional_details"></span>
                            </div>
                        </div>
                    </div>
                </div>-->
            </div>
            <!-- <span class="d-block text-danger mb-3" v-text="errors.financing_company"></span> -->
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

            /*this.errors = [];

            if(this.authStore.form.pricing_promotional == 'Yes' && !this.authStore.form.pricing_promotional_details){
                this.errors['pricing_promotional_details'] = 'Pricing Promotional Details field is required.';
            }

            if(this.authStore.form.technologies == 'Other' && !this.authStore.form.technologies_other){
                this.errors['technologies_other'] = 'Technologies Other field is required.';
            }

            if(this.authStore.form.financing_company == 'Other' && !this.authStore.form.financing_company_other){
                this.errors['financing_company_other'] = 'Finanicing Company Other field is required.';
            }

            if(Object.keys(this.errors).length>0){
                return null;
            }*/

            this.$emit('form-submitted', this.form);

            router.push('/crtx/ai/step-4');
        },
        later() {
            this.authStore.signup();
        },
        back(){
            this.$emit('step-back');
            router.push('/crtx/ai/step-3');
        },
        inputFilled(event){
            event.target.classList.add("filled");
        }
    },
}
</script>

<template>

    <div v-if="authStore.serverError" class="alert alert-danger alert-dismissible fade show" role="alert">
        We encountered a server error processing your request. Please try again later.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" @click="authStore.serverError = false"></button>
    </div>
    <form @submit.prevent="submit">
        <h2 class="mt-3 mt-md-5 mb-3">Marketing Messaging: Manual or AI?</h2>
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
        <h2 class="mt-3 mb-3">Marketing Messaging and Media</h2>
        <div class="row gx-3">
<!--            <div class="col-md-10 col-lg-8 col-xl-7">-->
<!--                <div class="d-block my-2">-->
<!--                    <p class="my-3 mb-md-0 d-block">What are your primary selling messages, i.e., what should be emphasized in your marketing.</p>-->
<!--                    <textarea  placeholder="Please describe in details." class="form-control my-2" :class="{/*'unfilled' : errors.primary_selling_message, */'filled':authStore.form.primary_selling_message}" v-model="authStore.form.primary_selling_message"></textarea>-->
<!--                </div>-->
<!--            </div>-->
            <!-- <span class="d-block text-danger mb-3" v-text="errors.financing_company"></span> -->

            <div class="col-md-8 d-flex flex-md-row my-3 flex-column align-items-start">
                <p class="mb-2 mb-md-0">Are you using a separate marketing company?</p>
                <div class="ms-md-auto ps-md-3 ps-0 d-flex form-group mb-0">
                    <div class="form-check me-3">
                        <input class="form-check-input" type="radio" name="marketing_company" id="marketing_company_yes" value="Yes" v-model="authStore.form.marketing_company">
                        <label class="form-check-label" for="marketing_company_yes">
                            Yes
                        </label>
                    </div>
                    <div class="form-check" style="width:200px;">
                        <input class="form-check-input" type="radio" name="marketing_company" id="marketing_company_no" value="No" v-model="authStore.form.marketing_company">
                        <label class="form-check-label" for="marketing_company_no">
                            No
                        </label>
                    </div>
                </div>
            </div>

<!--            <p class="my-1">What paid media would you like to use? Select all that apply.</p>-->
<!--            <div class="check-group d-flex flex-wrap mb-1">-->
<!--                <div class="btn-checkbox mb-2 me-2">-->
<!--                    <input type="checkbox" class="btn-check" id="GoogleAdWords" value="Google AdWords" autocomplete="off" v-model="authStore.form.paid_media">-->
<!--                    <label class="btn btn-outline-primary" for="GoogleAdWords">Google AdWords</label><br>-->
<!--                </div>-->
<!--                <div class="btn-checkbox mb-2 me-2">-->
<!--                    <input type="checkbox" class="btn-check" id="Facebook" value="Facebook" autocomplete="off" v-model="authStore.form.paid_media">-->
<!--                    <label class="btn btn-outline-primary" for="Facebook">Facebook</label><br>-->
<!--                </div>-->
<!--                <div class="btn-checkbox mb-2 me-2">-->
<!--                    <input type="checkbox" class="btn-check" id="Instagram" value="Instagram" autocomplete="off" v-model="authStore.form.paid_media">-->
<!--                    <label class="btn btn-outline-primary" for="Instagram">Instagram</label><br>-->
<!--                </div>-->
<!--                <div class="btn-checkbox mb-2 me-2">-->
<!--                    <input type="checkbox" class="btn-check" id="YouTube" value="YouTube" autocomplete="off" v-model="authStore.form.paid_media">-->
<!--                    <label class="btn btn-outline-primary" for="YouTube">YouTube</label><br>-->
<!--                </div>-->
<!--                <div class="btn-checkbox mb-2 me-2">-->
<!--                    <input type="checkbox" class="btn-check" id="Other" value="Other" autocomplete="off" v-model="authStore.form.paid_media">-->
<!--                    <label class="btn btn-outline-primary" for="Other">Other</label><br>-->
<!--                </div>-->
<!--            </div>-->
<!--            <span class="d-block text-danger mb-3" v-text="errors.paid_media"></span>-->

            <div class="form-group budget-input d-block">
                <p class="mb-1 mb-md-3 d-block ">Please set your monthly ad budget. This is <b>separate</b> from your monthly management fee.</p>
                <div class="input-group mb-1 width-10">
                    <span class="input-group-text">$</span>
                    <input type="text" class="form-control" v-model="authStore.form.media_budget" @input="formatAmount">
                </div>
            </div>

            <div class="form-group">
                <b>Finalizing Your Advertising Setup</b>
                <p>
                    A crucial step to activating your advertising on Google Ads involves Google Verification. This process,
                    mandated by Google, is essential for advertising your practice on their platform. You'll be guided to a
                    separate form where you'll provide the necessary documentation for verification. This includes official
                    paperwork that verifies the existence of your business, along with a copy of the driver's license of the
                    person conducting the onboarding. Please note that Google has set this requirement to maintain the
                    integrity and security of its advertising services.
                </p>

                <div class="d-flex align-items-center justify-content-start">
                    <a href="https://wkf.ms/3PhGLC7" target="_blank" class="btn btn-primary submit-btn px-4 mt-3 mb-5" >Google Verification</a>
                </div>
                <b>Payment Information for Advertising</b>
                <p>
                    Additionally, to commence advertising on both Google and Facebook, you'll be directed to another
                    page to submit your payment information. The credit card details you provide will be used directly
                    by Google and Facebook to cover your advertising costs. This step is pivotal in enabling us to
                    launch and manage your advertising campaigns across these platforms efficiently.
                </p>
                <div class="d-flex align-items-center justify-content-start">
                    <a href="https://microsite.com/pay"  target="_blank" class="btn btn-primary submit-btn px-4 mt-3">Pay</a>
                </div>
            </div>

<!--            <div class="col-md-10 col-lg-8 col-xl-7">
                <div class="d-block my-1">
                    <p class="my-1 mb-md-0 d-block">When marketing material, such as landing pages, are completed by CRTX, you can find links to them below.</p>
                    <textarea  placeholder="Add text here or let AI fill it in." class="form-control my-2" :class="{'unfilled' : errors.marketing_material, 'filled':authStore.form.marketing_material}" v-model="authStore.form.marketing_material"></textarea>
                </div>
            </div>-->
        </div>
        <div class="bottom-btn-box d-flex align-items-center justify-content-end">
<!--            <a href="#" class="me-3 mt-2" @click="later">Set Up Later</a>-->
            <div>
                <button class="btn btn-outline-primary me-2" @click="back">Go Back</button>
                <button type="submit" class="btn btn-primary submit-btn px-4">Complete Set Up</button>
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

          /*  this.errors = [];

            if(Object.keys(this.errors).length>0){
                return null;
            }*/

            this.authStore.sendAIDetails();

            // this.$emit('form-submitted', this.form);

            router.push('/crtx/ai/finish');
        },
        later() {
            this.authStore.signup();
        },
        back(){
            this.$emit('step-back');
            router.push('/crtx/ai/step-1');
        },
        inputFilled(event){
            event.target.classList.add("filled");
        },
        formatAmount() {
            let formattedValue = this.authStore.form.media_budget.replace(/\D/g, '');
            formattedValue = formattedValue.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            this.authStore.form.media_budget = formattedValue;
        },
    },
}
</script>


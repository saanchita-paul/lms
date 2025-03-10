<template>
    <div class="staging-alert" v-if="env=='staging'">
        <span>Hold on! You are in the staging environment!</span>
    </div>
    <h2 class="mt-3 mt-md-5 mb-3">Account Owner Information</h2>
    <form @submit.prevent="submit">
        <div class="row gx-3">
            <div class="col-md-8 col-lg-6">
                <p class="mb-2">Full Name</p>
                <div class="row gx-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <input type="text" name="fname" class="form-control" :class="{'unfilled' :errors.first_name, 'filled':authStore.form.first_name}"  placeholder="" v-model="authStore.form.first_name">
                            <label>First Name</label>
                            <span class="d-block text-danger mt-1" v-text="errors.first_name"></span>
                        </div><!--/.form-group-->
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <input type="text" name="fname" class="form-control" :class="{'unfilled' :errors.last_name, 'filled':authStore.form.last_name}"  placeholder="" v-model="authStore.form.last_name">
                            <label>Last Name</label>
                            <span class="d-block text-danger mt-1" v-text="errors.last_name"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row gx-3">
                <div class="col-md-8 col-lg-6">
                    <p class="mb-2">Phone Number</p>
                    <div class="row gx-3">
                        <div class="col-md-12">
                            <div class="form-group">
                                <input
                                    type="text" id="phone" class="form-control"
                                    :class="{'unfilled' :errors.admin_phone,
                                    'filled':authStore.form.admin_phone}"
                                    placeholder="" v-model="authStore.form.admin_phone"
                                >
                                <label>Phone Number</label>
                                <span class="d-block text-danger mt-1" v-text="errors.admin_phone"></span>
                            </div><!--/.form-group-->
                        </div>
                    </div>
                </div>
            </div>
<!--            <div class="col-md-8 col-lg-6">
                <p class="mb-2">Degree Abbreviation</p>
                <div class="form-group">
                    <div class="check-group d-flex flex-wrap">
                        <div class="btn-checkbox mb-2 me-2">
                            <input type="radio" class="btn-check" id="DMD" value="DMD" autocomplete="off" v-model="authStore.form.degree_abbreviation">
                            <label class="btn btn-outline-primary" for="DMD">DMD</label><br>
                        </div>
                        <div class="btn-checkbox mb-2 me-2">
                            <input type="radio" class="btn-check" id="DDS" value="DDS" autocomplete="off" v-model="authStore.form.degree_abbreviation">
                            <label class="btn btn-outline-primary" for="DDS">DDS</label><br>
                        </div>
                        <div class="btn-checkbox mb-2 me-2">
                            <input type="radio" class="btn-check" id="MD" value="MD" autocomplete="off" v-model="authStore.form.degree_abbreviation">
                            <label class="btn btn-outline-primary" for="MD">MD</label><br>
                        </div>
                        <div class="btn-checkbox mb-2 me-2">
                            <input type="radio" class="btn-check" value="Other" id="Other" autocomplete="off" v-model="authStore.form.degree_abbreviation">
                            <label class="btn btn-outline-primary" for="Other">Other</label><br>
                        </div>
                        <div class="btn-checkbox mb-2 me-2">
                            <input type="radio" class="btn-check" value="None" id="None" autocomplete="off" v-model="authStore.form.degree_abbreviation">
                            <label class="btn btn-outline-primary" for="None">None</label><br>
                        </div>
                    </div>
                    <span class="d-block text-danger mt-1" v-text="errors.degree_abbreviation"></span>
                </div>
            </div>-->
<!--            <div class="row gx-3" v-if="authStore.form.degree_abbreviation=='Other'">
                <div class="col-md-8 col-lg-6">
                    <div class="form-group">
                        <input type="text" name="abbreviation_other" class="form-control" :class="{'unfilled' :errors.degree_abbreviation_other, 'filled':authStore.form.degree_abbreviation_other}" placeholder="" v-model="authStore.form.degree_abbreviation_other">
                        <label>Please provide abbreviation</label>
                        <span class="d-block text-danger mt-1" v-text="errors.degree_abbreviation_other"></span>
                    </div>&lt;!&ndash;/.form-group&ndash;&gt;
                </div>
            </div>-->
        </div>
        <div v-if="!authStore.user">
            <div class="row gx-3">
                <div class="col-md-8 col-lg-6">
                    <p class="mb-2">Create Your Account (use the primary administrator's email address)</p>
                    <div class="form-group">
                        <input type="email" name="email" class="form-control" :class="{'unfilled' :errors.email, 'filled':authStore.form.email}" placeholder="" v-model="authStore.form.email">
                        <label>Email Address</label>
                        <span class="d-block text-danger mt-1" v-text="errors.email"></span>
                    </div><!--/.form-group-->
                </div>
            </div>
            <div class="row gx-3">
                <div class="col-md-8 col-lg-6">
                    <div class="form-group">
                        <input v-show="password_visible" type="text" name="password" class="form-control" :class="{'unfilled' :errors.password, 'filled':authStore.form.password}"  placeholder="" v-model="authStore.form.password">
                        <input v-show="!password_visible" type="password" name="password" class="form-control" :class="{'unfilled' :errors.password, 'filled':authStore.form.password}"  placeholder="" v-model="authStore.form.password">
                        <a @click="togglePassword">
                        <span class="showpass">
                            <figure v-show="!password_visible">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 20 16" style="enable-background:new 0 0 20 16;" xml:space="preserve">
                                    <path d="M15.1,2.5l2.7-2.4C17.9,0.1,18.1,0,18.2,0C18.7,0,19,0.3,19,0.8c0,0.2-0.1,0.4-0.3,0.6L2.2,15.8C2.1,15.9,1.9,16,1.8,16C1.3,16,1,15.7,1,15.2c0-0.2,0.1-0.4,0.3-0.6l2.4-2.1c-1.4-1.1-2.6-2.6-3.5-4C0,8.4,0,8.2,0,8s0-0.4,0.1-0.5C2.3,4.1,5.9,1,10,1C11.8,1,13.5,1.6,15.1,2.5z M17.4,4.4c1,0.9,1.8,2,2.5,3.1C20,7.6,20,7.8,20,8s0,0.4-0.1,0.5C17.8,11.9,14.1,15,10,15c-1.3,0-2.6-0.3-3.7-0.8l1.2-1.1c0.8,0.3,1.6,0.4,2.5,0.4c3.5,0,6.6-2.6,8.4-5.5c-0.6-0.9-1.3-1.8-2.1-2.6L17.4,4.4z M14,7.4c0,0.2,0,0.4,0,0.6c0,2.2-1.8,4-4,4c-0.4,0-0.8-0.1-1.1-0.2l1.6-1.4c0.9-0.2,1.6-0.8,1.9-1.7L14,7.4z M13.9,3.6c-1.2-0.7-2.5-1.1-3.9-1.1C6.5,2.5,3.5,5.1,1.6,8c0.9,1.3,2,2.6,3.2,3.5L6.6,10C6.2,9.4,6,8.7,6,8c0-2.2,1.8-4,4-4c0.9,0,1.8,0.3,2.5,0.8L13.9,3.6z M11.3,5.9c-0.4-0.2-0.8-0.4-1.3-0.4C8.6,5.5,7.5,6.6,7.5,8c0,0.4,0.1,0.7,0.2,1L11.3,5.9z"/>
                                </svg>
                            </figure>
                            <figure v-show="password_visible">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 20 16" style="enable-background:new 0 0 20 16;" xml:space="preserve">
                                    <path d="M10,1.1c-4.1,0-7.8,2.6-9.9,6.4C0,7.8,0,8.2,0.1,8.5c2.1,3.9,5.7,6.4,9.8,6.4c4.1,0,7.8-2.6,9.9-6.4c0.2-0.3,0.2-0.7,0-1C17.8,3.7,14.1,1.1,10,1.1z M18.4,8c-2,3.3-5,5.4-8.4,5.4S3.5,11.3,1.6,8h0c2-3.3,5-5.4,8.4-5.4S16.4,4.7,18.4,8L18.4,8z"/>
                                    <path d="M10,4C7.8,4,6,5.8,6,8s1.8,4,4,4s4-1.8,4-4S12.2,4,10,4z M10,10.5c-1.4,0-2.5-1.1-2.5-2.5c0-1.4,1.1-2.5,2.5-2.5s2.5,1.1,2.5,2.5C12.5,9.4,11.4,10.5,10,10.5z"/>
                                </svg>
                            </figure>
                        </span>
                        </a>
                        <label>Password</label>
                        <span class="d-block text-danger mt-1" v-text="errors.password"></span>
                    </div><!--/.form-group-->
                </div>
            </div>
            <div class="row gx-3">
                <div class="col-md-8 col-lg-6">
                    <div class="form-group">
                        <input type="text" v-show="confirm_password_visible" name="cpassword" class="form-control" :class="{'unfilled' :errors.confirm_password, 'filled':authStore.form.confirm_password}" placeholder="" v-model="authStore.form.confirm_password">
                        <input type="password" v-show="!confirm_password_visible" name="cpassword" class="form-control" :class="{'unfilled' :errors.confirm_password, 'filled':authStore.form.confirm_password}"  placeholder="" v-model="authStore.form.confirm_password">
                        <a @click="toggleConfirmPassword">
                        <span class="showpass">
                            <figure v-show="!confirm_password_visible">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 20 16" style="enable-background:new 0 0 20 16;" xml:space="preserve">
                                    <path d="M15.1,2.5l2.7-2.4C17.9,0.1,18.1,0,18.2,0C18.7,0,19,0.3,19,0.8c0,0.2-0.1,0.4-0.3,0.6L2.2,15.8C2.1,15.9,1.9,16,1.8,16C1.3,16,1,15.7,1,15.2c0-0.2,0.1-0.4,0.3-0.6l2.4-2.1c-1.4-1.1-2.6-2.6-3.5-4C0,8.4,0,8.2,0,8s0-0.4,0.1-0.5C2.3,4.1,5.9,1,10,1C11.8,1,13.5,1.6,15.1,2.5z M17.4,4.4c1,0.9,1.8,2,2.5,3.1C20,7.6,20,7.8,20,8s0,0.4-0.1,0.5C17.8,11.9,14.1,15,10,15c-1.3,0-2.6-0.3-3.7-0.8l1.2-1.1c0.8,0.3,1.6,0.4,2.5,0.4c3.5,0,6.6-2.6,8.4-5.5c-0.6-0.9-1.3-1.8-2.1-2.6L17.4,4.4z M14,7.4c0,0.2,0,0.4,0,0.6c0,2.2-1.8,4-4,4c-0.4,0-0.8-0.1-1.1-0.2l1.6-1.4c0.9-0.2,1.6-0.8,1.9-1.7L14,7.4z M13.9,3.6c-1.2-0.7-2.5-1.1-3.9-1.1C6.5,2.5,3.5,5.1,1.6,8c0.9,1.3,2,2.6,3.2,3.5L6.6,10C6.2,9.4,6,8.7,6,8c0-2.2,1.8-4,4-4c0.9,0,1.8,0.3,2.5,0.8L13.9,3.6z M11.3,5.9c-0.4-0.2-0.8-0.4-1.3-0.4C8.6,5.5,7.5,6.6,7.5,8c0,0.4,0.1,0.7,0.2,1L11.3,5.9z"/>
                                </svg>
                            </figure>

                            <figure v-show="confirm_password_visible">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 20 16" style="enable-background:new 0 0 20 16;" xml:space="preserve">
                                    <path d="M10,1.1c-4.1,0-7.8,2.6-9.9,6.4C0,7.8,0,8.2,0.1,8.5c2.1,3.9,5.7,6.4,9.8,6.4c4.1,0,7.8-2.6,9.9-6.4c0.2-0.3,0.2-0.7,0-1C17.8,3.7,14.1,1.1,10,1.1z M18.4,8c-2,3.3-5,5.4-8.4,5.4S3.5,11.3,1.6,8h0c2-3.3,5-5.4,8.4-5.4S16.4,4.7,18.4,8L18.4,8z"/>
                                    <path d="M10,4C7.8,4,6,5.8,6,8s1.8,4,4,4s4-1.8,4-4S12.2,4,10,4z M10,10.5c-1.4,0-2.5-1.1-2.5-2.5c0-1.4,1.1-2.5,2.5-2.5s2.5,1.1,2.5,2.5C12.5,9.4,11.4,10.5,10,10.5z"/>
                                </svg>
                            </figure>
                        </span>
                        </a>
                        <label>Confirm Password</label>
                        <span class="d-block text-danger mt-1" v-text="errors.confirm_password"></span>
                    </div><!--/.form-group-->
                </div>
            </div>
        </div>
        <div class="bottom-btn-box">
            <button type="submit" class="btn btn-primary submit-btn">Continue</button>
        </div><!--/.form-group-->
    </form>
</template>
<script>
import router from '../../routes'
import { useAuthStore } from '../../stores/auth';

export default {
    setup (){
        const authStore = useAuthStore();

        return { authStore };
    },
    props:['env'],
    data() {
        return {
            errors: [],
            password_visible: false,
            confirm_password_visible: false,
        }
    },
    mounted(){

        // Get all input elements with class="CLASS_NAME"
        const inputs = document.querySelectorAll('input.form-control, textarea.form-control, select.form-control')

        // Iterate over elements and add each one of them a `focus` listener
        inputs.forEach(input => {
            input.addEventListener('change', this.inputFilled)
        });

        IMask(document.getElementById('phone'), {
            mask: '(000) 000-0000'
        });
    },
    methods: {
        submit(){

            this.errors = [];

            if (!this.authStore.form.first_name) {
                this.errors['first_name'] = 'First Name field is required.';
            }

            if (!this.authStore.form.last_name) {
                this.errors['last_name'] = 'Last Name field is required.';
            }

            if(!this.authStore.form.admin_phone){
                this.errors['admin_phone'] = 'Phone number field is required.';
            }

          /*  if (!this.authStore.form.degree_abbreviation) {
                this.errors['degree_abbreviation'] = 'Degree Abbreviation field is required.';
            }

            if (this.authStore.form.degree_abbreviation=='Other' && !this.authStore.form.degree_abbreviation_other) {
                this.errors['degree_abbreviation_other'] = 'Degree Abbreviation Other field is required.';
            }*/

            if(!this.authStore.user){
                if (!this.authStore.form.email) {
                    this.errors['email'] = 'Email field is required.';
                }else if (!/^[^@]+@\w+(\.\w+)+\w$/.test(this.authStore.form.email)) {
                    this.errors['email'] = 'Invalid email address entered.';
                }

                if (!this.authStore.form.password || this.authStore.form.password.length < 8) {
                    this.errors['password'] = 'Password field is required & must be at least 8 characters long.';
                }

                if (!this.authStore.form.confirm_password ) {
                    this.errors['confirm_password'] = 'Confirm Password field is required.';
                }

                if(this.authStore.form.password !== this.authStore.form.confirm_password){
                    this.errors['password'] = 'Passwords do not match.';
                }
            }

            if(Object.keys(this.errors).length>0){
                return null;
            }

            this.$emit('form-submitted', this.form);

            router.push('/crtx/account/step-2');
        },
        togglePassword(){
            this.password_visible = ! this.password_visible;
        },
        toggleConfirmPassword(){
            this.confirm_password_visible = ! this.confirm_password_visible;
        },
        inputFilled(event){
            event.target.classList.add("filled");
        }
    },
}
</script>

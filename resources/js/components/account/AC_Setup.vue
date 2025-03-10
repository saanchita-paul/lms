<template>
    <div class="account-main-box py-5 px-2 p-md-5">
		<div class="container">
			<div class="logo-box">
				<figure><svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 923 190" style="enable-background:new 0 0 923 190;">
					<g>
						<path d="M34.28,59.34v68.31c0,10.11,8.23,18.35,18.35,18.35h170.66v21H52.63c-21.69,0-39.35-17.64-39.35-39.35V59.34c0-21.71,17.66-39.35,39.35-39.35h170.66v21H52.63C42.51,41,34.28,49.23,34.28,59.34z"/>
						<path d="M415.64,41h-147v126h-21v-147h168.01c23.17,0,42,18.85,42,42c0,23.15-18.83,42-42,42V83c11.58,0,21-9.43,21-21S427.22,41,415.64,41z M356.22,83l101.42,78.02V167h-26.68l-81.9-63h-38.42V83H356.22z"/>
						<path d="M680.87,19.99v21h-94.59v126h-21V41h-94.42v-21H680.87z"/>
						<path d="M900.62,29.74L836.86,93.5l63.76,63.76V167h-21v-1.04L817.66,104h-44.17l-61.96,61.96V167h-21v-9.74l63.76-63.76l-63.76-63.76v-9.74h21v1.04L773.49,83h44.17l61.96-61.96v-1.04h21V29.74z"/>
					</g>
				</svg></figure>
			</div>
            <h2 class="mt-5 mt-md-5 mb-3">Set Up Your CRTX Account</h2>
            <p style="font-size: 14px; width:50%;">You have been invited to join CRTX. Get Started by entering details below.</p>
            <form @submit.prevent="submit">
                <div v-if="authStore.serverError" class="alert alert-danger alert-dismissible fade show mt-5" role="alert">
                    {{ authStore.setupMessage }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" @click="authStore.serverError = false"></button>
                </div>
                <div class="errors mt-2" v-if="authStore.setupErrors.length>0">
                    <div v-for="error in authStore.setupErrors" class="alert alert-danger" role="alert">
                        {{ error }}
                    </div>
                </div>
                <div class="row gx-3">
                    <div class="col-md-8 col-lg-6">
                        <p class="mb-2">Name</p>
                        <div class="row gx-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" name="fname" class="form-control" :class="{'unfilled' :authStore.setupErrors.first_name, 'filled':authStore.setupForm.first_name}"  placeholder="" v-model="authStore.setupForm.first_name">
                                    <label>First Name</label>
                                    <span class="d-block text-danger mt-1" v-text="authStore.setupErrors.first_name"></span>
                                </div><!--/.form-group-->
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" name="lname" class="form-control" :class="{'unfilled' :authStore.setupErrors.last_name, 'filled':authStore.setupForm.last_name}" placeholder="" v-model="authStore.setupForm.last_name">
                                    <label>Last Name</label>
                                    <span class="d-block text-danger mt-1" v-text="authStore.setupErrors.last_name"></span>
                                </div><!--/.form-group-->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row gx-3">
                    <div class="col-md-8 col-lg-6">
                        <p class="mb-2">Email Address</p>
                        <div class="form-group">
                            <input type="email" name="email" class="form-control" :class="{'unfilled' :authStore.setupErrors.email, 'filled':authStore.setupForm.email}" placeholder="" v-model="authStore.setupForm.email">
                            <label>Email Address</label>
                            <span class="d-block text-danger mt-1" v-text="authStore.setupErrors.email"></span>
                            <span class="d-block text-danger mt-1" v-text="authStore.setupErrors.staff_emails"></span>
                        </div><!--/.form-group-->
                    </div>
                </div>
                <div class="row gx-3">
                    <div class="col-md-8 col-lg-6">
                        <p class="mb-2">Password</p>
                        <div class="form-group">
                            <input v-show="password_visible" type="text" name="password" class="form-control" :class="{'unfilled' :authStore.setupErrors.password, 'filled':authStore.setupForm.password}"  placeholder="" v-model="authStore.setupForm.password">
                            <input v-show="!password_visible" type="password" name="password" class="form-control" :class="{'unfilled' :authStore.setupErrors.password, 'filled':authStore.setupForm.password}"  placeholder="" v-model="authStore.setupForm.password">
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
                            <span class="d-block text-danger mt-1" v-text="authStore.setupErrors.password"></span>
                        </div><!--/.form-group-->
                    </div>
                </div>
                <div class="row gx-3">
                    <div class="col-md-8 col-lg-6">
                        <div class="form-group">
                            <input type="text" v-show="confirm_password_visible" name="cpassword" class="form-control" :class="{'unfilled' :authStore.setupErrors.password_confirmation, 'filled':authStore.setupForm.password_confirmation}" placeholder="" v-model="authStore.setupForm.password_confirmation">
                            <input type="password" v-show="!confirm_password_visible" name="cpassword" class="form-control" :class="{'unfilled' :authStore.setupErrors.password_confirmation, 'filled':authStore.setupForm.password_confirmation}"  placeholder="" v-model="authStore.setupForm.password_confirmation">
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
                            <span class="d-block text-danger mt-1" v-text="authStore.setupErrors.password_confirmation"></span>
                        </div><!--/.form-group-->
                    </div>
                </div>
                <div class="d-flex mb-1 form-group">
                    <div class="form-check me-3" style="padding-left:0px; font-size: 14px;">
                        <input type="checkbox" class="pr-3" value="1" name="locationsradio" id="Yes1" v-model="authStore.setupForm.term" style="width:30px;height:16px;">
                        <label class="form-check-label pl-3" for="Yes1">
                                <b>I agree to the <a style="color:rgb(0, 64, 255);">CRTX terms and conditions.</a></b>
                        </label>
                        <span class="d-block text-danger mt-1" v-text="authStore.setupErrors.term"></span>
                    </div>
                </div>
                <div class="bottom-btn-box mt-3">
                    <button type="submit" class="btn btn-primary submit-btn">Create Account</button>
                </div><!--/.form-group-->
            </form>
        </div>
    </div>
</template>
<script>

import { useAuthStore } from '../../stores/auth';

export default {
    setup (){
        const authStore = useAuthStore();

        return { authStore };
    },
    data() {
        return {
            errors: [],
            password_visible: false,
            confirm_password_visible: false
        }
    },
    mounted(){
        // Get all input elements with class="CLASS_NAME"
        const inputs = document.querySelectorAll('input.form-control, textarea.form-control, select.form-control')

        // Iterate over elements and add each one of them a `focus` listener
        inputs.forEach(input => {
            input.addEventListener('change', this.inputFilled)
        });

        const urlParams = new URLSearchParams(window.location.search);
        const email = urlParams.get('email');
        this.authStore.setupForm.email = email;
    },
    methods: {
        submit(){
            
            this.authStore.setupErrors = [];

            if (!this.authStore.setupForm.first_name) {
                this.authStore.setupErrors['first_name'] = 'First Name field is required.';
            }

            if (!this.authStore.setupForm.last_name) {
                this.authStore.setupErrors['last_name'] = 'Last Name field is required.';
            }


            if (!this.authStore.setupForm.email) {
                this.authStore.setupErrors['email'] = 'Email field is required.';
            }else if (!/^[^@]+@\w+(\.\w+)+\w$/.test(this.authStore.setupForm.email)) {
                this.authStore.setupErrors['email'] = 'Invalid email address entered.';
            }

            if (!this.authStore.setupForm.password || this.authStore.setupForm.password.length < 8) {
                this.authStore.setupErrors['password'] = 'Password field is required & must be at least 8 characters long.';
            }

            if (!this.authStore.setupForm.password_confirmation ) {
                this.authStore.setupErrors['password_confirmation'] = 'Confirm Password field is required.';
            }

            if(this.authStore.setupForm.password !== this.authStore.setupForm.password_confirmation){
                this.authStore.setupErrors['password'] = 'Passwords do not match.';
            }

            if(!this.authStore.setupForm.term){
                this.authStore.setupErrors['term'] = 'Terms and condition must be checked.';
            }

            if(Object.keys(this.authStore.setupErrors).length>0){
                return null;
            }

            this.authStore.setup();
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
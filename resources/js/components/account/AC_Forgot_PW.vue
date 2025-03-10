<template>
    <div class="welcome-box p-5">
		<div class="welcome-box-info">
			<h1 class="d-inline-block align-top">Forgot password</h1>
            <figure class="d-inline-block">
                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 923 190" style="enable-background:new 0 0 923 190;">
                    <g>
                        <path d="M34.28,59.34v68.31c0,10.11,8.23,18.35,18.35,18.35h170.66v21H52.63c-21.69,0-39.35-17.64-39.35-39.35V59.34c0-21.71,17.66-39.35,39.35-39.35h170.66v21H52.63C42.51,41,34.28,49.23,34.28,59.34z"/>
                        <path d="M415.64,41h-147v126h-21v-147h168.01c23.17,0,42,18.85,42,42c0,23.15-18.83,42-42,42V83c11.58,0,21-9.43,21-21S427.22,41,415.64,41z M356.22,83l101.42,78.02V167h-26.68l-81.9-63h-38.42V83H356.22z"/>
                        <path d="M680.87,19.99v21h-94.59v126h-21V41h-94.42v-21H680.87z"/>
                        <path d="M900.62,29.74L836.86,93.5l63.76,63.76V167h-21v-1.04L817.66,104h-44.17l-61.96,61.96V167h-21v-9.74l63.76-63.76l-63.76-63.76v-9.74h21v1.04L773.49,83h44.17l61.96-61.96v-1.04h21V29.74z"/>
                    </g>
                </svg>
            </figure>
            <p>Enter your email address and we'll send you a link to get back into your account.</p>
            <form action="#" @submit.prevent="submit">
                <div class="errors mt-2 px-5" v-if="authStore.forgotPWMessage">
                    <div class="alert" :class="authStore.forgotPWError ? 'alert-danger' : 'alert-success'" role="alert">
                        {{ authStore.forgotPWMessage }}
                    </div>
                </div>
                <div class="row gx-3">
                    <div class="col-md-10 col-lg-8 offset-md-1 offset-lg-2">
                        <div class="form-group">
                            <input type="email" class="form-control" :class="{'filled':form.email}" placeholder="" v-model="form.email" required>
                            <label>Email Address</label>
                        </div><!--/.form-group-->
                    </div>
                </div>
                <div class="mt-2 row gx-3 justify-content-center">
                    <div class="col-md-10 col-lg-8">
                        <div class="row">
                            <div class="mt-3 col-sm-6">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                            <div class="mt-3 col-sm-6">
                                <button type="button" @click="signin" class="btn btn-light">Sign In</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
		</div>
	</div>
</template>
<script>
import router from '../../routes'
import { useAuthStore } from '../../stores/auth';

export default {
    setup (){
        const authStore = useAuthStore();

        return { authStore };
    },
    data() {
        return {
            errors: [],
            form : {
                email: ""
            }
        }
    },
    mounted(){
        // Get all input elements with class="CLASS_NAME"
        const inputs = document.querySelectorAll('input.form-control')

        // Iterate over elements and add each one of them a `focus` listener
        inputs.forEach(input => {
            input.addEventListener('change', this.inputFilled)
        });

        if(this.authStore.user && this.authStore.user.id){
            router.push('/crtx/dashboard?view=list&page=sms');
        }
    },
    methods: {
        submit(){
            this.errors = [];

            if (!this.form.email) {
                this.errors.push('Email Address field is required.');
            }else if (!/^[^@]+@\w+(\.\w+)+\w$/.test(this.form.email)) {
                this.errors['email'] = 'Invalid email address entered.';
            }

            this.authStore.forgotpw(this.form)
        },
        signin(){
            router.push('/crtx/account/signin');
        },

    },
    inputFilled(event){
        event.target.classList.add("filled");
    }
}
</script>

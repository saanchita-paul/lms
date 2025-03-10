<template>
    <div class="welcome-box p-5">
        <div class="welcome-box-info">
            <div class="staging-alert" v-if="env=='staging'">
                <span>Hold on! You are in the staging environment!</span>
            </div>
            <h1 class="d-inline-block align-top">Sign In To</h1>
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

            <p>Fill the form below to sign into your profile and view your dashboard.</p>
            <!-- Login Form -->
            <form v-if="!authStore.requires2FA" action="#" @submit.prevent="submit">
                <div class="errors mt-2 px-5" v-if="authStore.errors.length>0">
                    <div v-for="error in authStore.errors" class="alert alert-danger" role="alert">
                        {{ error }}
                    </div>
                </div>
                <div class="row gx-3 justify-content-center">
                    <div class="col-md-10 col-lg-8">
                        <div class="form-group">
                            <input type="email" class="form-control" :class="{'filled':form.email}"
                                   required placeholder="" v-model="form.email">
                            <label>Email Address</label>
                        </div>
                    </div>
                </div>
                <div class="row gx-3 justify-content-center">
                    <div class="col-md-10 col-lg-8">
                        <div class="form-group">
                            <input type="password" class="form-control" :class="{'filled':form.password}"
                                   required placeholder="" v-model="form.password">
                            <label>Password</label>
                        </div>
                    </div>
                </div>
                <div class="row gx-3 justify-content-center">
                    <div class="col-md-10 col-lg-8 text-end">
                        <router-link to="/crtx/account/forgot-pw" style="font-size: 14px; text-decoration: underline;">
                            Forgot your password?
                        </router-link>
                    </div>
                </div>
                <div class="mt-2 row gx-3 justify-content-center">
                    <div class="col-md-10 col-lg-8">
                        <div class="row">
                            <div class="mt-3 col-sm-6">
                                <button type="submit" class="btn btn-primary w-100">Sign In</button>
                            </div>
                            <div class="mt-3 col-sm-6">
                                <button type="button" @click="signup" class="btn btn-light w-100">Sign Up</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <!-- 2FA Form -->
            <form v-else action="#" @submit.prevent="verify2FA">
                <p class="text-center mb-4">
                    Enter the verification code sent to your
                    {{ authStore.twoFactorType === 'both' ? 'phone and email' : authStore.twoFactorType }}
                </p>

                <div v-if="authStore.verificationError" class="errors mt-2 px-5">
                    <div class="alert alert-danger" role="alert">
                        {{ authStore.verificationError }}
                    </div>
                </div>

                <div class="row gx-3 justify-content-center">
                    <div class="col-md-10 col-lg-8">
                        <div class="form-group">
                            <input type="text" class="form-control" :class="{'filled': verificationCode}"
                                   required maxlength="6" placeholder=""
                                   v-model="verificationCode"
                                   @input="onCodeInput">
                            <label>Verification Code</label>
                        </div>
                    </div>
                </div>

                <div class="mt-2 row gx-3 justify-content-center">
                    <div class="col-md-10 col-lg-8">
                        <div class="row">
                            <div class="mt-3 col-sm-6">
                                <button type="submit" class="btn btn-primary w-100"
                                        :disabled="verificationCode.length !== 6">
                                    Verify Code
                                </button>
                            </div>
                            <div class="mt-3 col-sm-6">
                                <button type="button" @click="resendCode"
                                        class="btn btn-light w-100"
                                        :disabled="resendCooldown > 0">
                                    {{ resendCooldown > 0 ? `Resend Code (${resendCooldown}s)` : 'Resend Code' }}
                                </button>
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
import { useAuthStore } from '../../stores/auth'

export default {
    setup() {
        const authStore = useAuthStore()
        return { authStore }
    },
    props: ['env'],
    data() {
        return {
            form: {
                email: "",
                password: ""
            },
            verificationCode: "",
            resendCooldown: 0,
            resendTimer: null
        }
    },
    methods: {
        async submit() {
            if (!this.form.email || !this.form.password) {
                if (!this.form.email) {
                    this.authStore.errors.push('Email Address field is required.');
                }
                if (!this.form.password) {
                    this.authStore.errors.push('Password field is required.');
                }
                return;
            }

            await this.authStore.login(this.form);
        },

        async verify2FA() {
            if (this.verificationCode.length !== 6) return;

            try {
                const success = await this.authStore.verify2FA(this.verificationCode);
                if (!success) {
                    this.verificationCode = ""; // Clear invalid code
                }
            } catch (error) {
                console.error("2FA verification error:", error);
                this.verificationCode = "";
            }
        },

        async resendCode() {
            if (this.resendCooldown > 0) return;

            const success = await this.authStore.resend2FACode();
            if (success) {
                this.startResendCooldown();
            }
        },

        startResendCooldown() {
            this.resendCooldown = 30;
            if (this.resendTimer) clearInterval(this.resendTimer);

            this.resendTimer = setInterval(() => {
                if (this.resendCooldown > 0) {
                    this.resendCooldown--;
                } else {
                    clearInterval(this.resendTimer);
                }
            }, 1000);
        },

        onCodeInput(event) {
            this.verificationCode = event.target.value.replace(/[^0-9]/g, '');
        },

        signup() {
            router.push('/crtx/account/start');
        }
    },
    beforeDestroy() {
        if (this.resendTimer) {
            clearInterval(this.resendTimer);
        }
    }
}
</script>

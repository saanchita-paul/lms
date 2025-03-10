<template>
    <div id="wrapper" :class="(authStore.sidebarVisible && authStore.user) ? 'slide-menu-active' : ''">
        <Header v-if="authStore.user"></Header>
        <div class="account-main-box report-box" :class="(authStore.user) ? 'mt-4' : ''">
            <div class="container">
                <div class="logo-box" v-if="!authStore.user">
                    <figure><svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 923 190" style="enable-background:new 0 0 923 190;">
                        <g>
                            <path d="M34.28,59.34v68.31c0,10.11,8.23,18.35,18.35,18.35h170.66v21H52.63c-21.69,0-39.35-17.64-39.35-39.35V59.34c0-21.71,17.66-39.35,39.35-39.35h170.66v21H52.63C42.51,41,34.28,49.23,34.28,59.34z"/>
                            <path d="M415.64,41h-147v126h-21v-147h168.01c23.17,0,42,18.85,42,42c0,23.15-18.83,42-42,42V83c11.58,0,21-9.43,21-21S427.22,41,415.64,41z M356.22,83l101.42,78.02V167h-26.68l-81.9-63h-38.42V83H356.22z"/>
                            <path d="M680.87,19.99v21h-94.59v126h-21V41h-94.42v-21H680.87z"/>
                            <path d="M900.62,29.74L836.86,93.5l63.76,63.76V167h-21v-1.04L817.66,104h-44.17l-61.96,61.96V167h-21v-9.74l63.76-63.76l-63.76-63.76v-9.74h21v1.04L773.49,83h44.17l61.96-61.96v-1.04h21V29.74z"/>
                        </g>
                    </svg></figure>
                </div>
                <div class="step-box d-flex flex-md-column flex-row align-items-center align-items-md-start justify-content-between">
                    <div class="step-list mb-md-3">
                        <ul>
                            <li :class="step == 1 ? 'current' : ''"></li>
                            <li :class="step == 2 ? 'current' : ''"></li>
    <!--						<li :class="step == 3 ? 'current' : ''"></li>-->
    <!--                        <li :class="step == 4 ? 'current' : ''"></li>-->
    <!--                        <li :class="step == 5 ? 'current' : ''"></li>-->
                        </ul>
                    </div>
                    <div class="step-count">
                        {{step}} of 2
                    </div>
                </div>
                <router-view v-slot="{ Component }" v-on:form-submitted="formSubmitted" v-on:step-back="stepBack">
                    <keep-alive>
                        <component :is="Component" />
                    </keep-alive>
                </router-view>
            </div>
        </div>
    </div>
</template>
<script>
import router from '../../routes'

import { useAuthStore } from '../../stores/auth';

import Step_1 from './AI_Step_1.vue';
import Step_2 from './AI_Step_2.vue';
// import Step_3 from './AI_Step_3.vue';
// import Step_4 from './AI_Step_4.vue';
// import Step_5 from './AI_Step_5.vue';
import Header from "../Header.vue";

export default {
    setup (){
        const authStore = useAuthStore();

        return { authStore };
    },
    data() {
        return {
            form: {},
            step: 1
        }
    },
    components: {
        Header,
        Step_1,
        Step_2,
        // Step_3,
        // Step_4,
        // Step_5
    },
    methods: {
        formSubmitted(form){
            Object.assign(this.form, form);
            Object.assign(this.authStore.form, this.form)

            if(this.authStore.user && this.step == 4){
                this.authStore.signup();
                return null;
            }

            if(this.step==4){
                this.authStore.signup();
            }

            if(this.step==4){
                this.step = 1;
            }else{
                this.step++;
            }
        },
        stepBack()
        {
            this.step--;
        }
    },
}
</script>

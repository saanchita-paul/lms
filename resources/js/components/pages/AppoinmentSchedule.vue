<template>
    <div class="account-main-box py-5 px-2 p-md-5">
        <div class="container">
            <div class="image-container" v-if="!hideHeaderFooter">
                <img :src="clinic_logo" alt="Your Image Alt Text" class="clinic_logo">
            </div>
            <div class="card p-3 p-md-4 border-0 shadow-sm">
                <h2 v-if="step < 3">Schedule Your {{calendar_type ? calendar_type : 'Appointment'}}</h2>
                <div v-if="step < 3" class="step-box d-flex flex-md-column flex-row align-items-center align-items-md-start justify-content-between mt-0">
                    <div class="step-list mb-md-3">
                        <ul>
                            <li :class="step === 1 ? 'current' : ''"></li>
                            <li :class="step === 2 ? 'current' : ''"></li>
                        </ul>
                    </div>
                    <div class="step-count">
                        {{step}} of 2
                    </div>
                </div>
                <div v-if="Object.keys(this.authStore.errors).length>0" class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                    <p>Please fix following error(s)!</p>
                    <ul>
                        <li v-for="error in authStore.errors">{{ error }}</li>
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" @click="clinicStore.updated = false"></button>
                </div>

                <router-view v-slot="{ Component }" v-on:form-submitted="formSubmitted" v-on:step-back="stepBack" :env="env" :id="$route.params.id">
                    <keep-alive>
                        <component :is="Component" :dataForSAStep2="dataForSAStep2" />
                    </keep-alive>
                </router-view>
            </div>
                <div v-if="!hideHeaderFooter" class="logo-box mt-4 mt-md-3 rounded rounded-end shadow">
                    <figure>
                        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 923 190" style="enable-background:new 0 0 923 190;">
                            <g>
                                <path d="M34.28,59.34v68.31c0,10.11,8.23,18.35,18.35,18.35h170.66v21H52.63c-21.69,0-39.35-17.64-39.35-39.35V59.34c0-21.71,17.66-39.35,39.35-39.35h170.66v21H52.63C42.51,41,34.28,49.23,34.28,59.34z"/>
                                <path d="M415.64,41h-147v126h-21v-147h168.01c23.17,0,42,18.85,42,42c0,23.15-18.83,42-42,42V83c11.58,0,21-9.43,21-21S427.22,41,415.64,41z M356.22,83l101.42,78.02V167h-26.68l-81.9-63h-38.42V83H356.22z"/>
                                <path d="M680.87,19.99v21h-94.59v126h-21V41h-94.42v-21H680.87z"/>
                                <path d="M900.62,29.74L836.86,93.5l63.76,63.76V167h-21v-1.04L817.66,104h-44.17l-61.96,61.96V167h-21v-9.74l63.76-63.76l-63.76-63.76v-9.74h21v1.04L773.49,83h44.17l61.96-61.96v-1.04h21V29.74z"/>
                            </g>
                        </svg>
                    </figure>
                    <span class="poweredby">Powered by <a href="https://microsite.com" target="_blank">Microsite Health</a></span>
                </div>
        </div>
    </div>
</template>
<script>
import router from '../../routes'
import axios from 'axios'

import { useAuthStore } from '../../stores/auth';

import Step_1 from '../appointment/SA_Step_1.vue';
import Step_2 from '../appointment/SA_Step_2.vue';
import Step_3 from '../appointment/SA_Step_3.vue';
import Header from "../Header.vue";


export default {
    setup () {
        const authStore = useAuthStore();

        return { authStore };
    },
    props:['env','id'],
    data() {
        return {
            form: {},
            step: 1,
            formattedTime: '',
            formattedDate: '',
            dataForSAStep2:'',
            clinic_logo:'',
            calendar_type:'',
            hideHeaderFooter:false,
        }
    },
    mounted(){

        let _self = this;

        // Get all input elements with class="CLASS_NAME"
        const inputs = document.querySelectorAll('input.form-control')

        // Iterate over elements and add each one of them a `focus` listener
        inputs.forEach(input => {
            input.addEventListener('change', this.inputFilled)
        })
        this.getPractiseDetails();

        this.form.source = _self.$route.query.utm_source;
        this.form.medium = _self.$route.query.utm_medium;
        this.form.campaign = _self.$route.query.utm_campaign;
        this.form.content = _self.$route.query.utm_content;

        this.hideHeaderFooter = _self.$route.query.hidelogo==1 ? true : false;
    },
    components: {
        Header,
        Step_1,
        Step_2,
        Step_3,
    },
    methods: {
        formSubmitted(form){
            Object.assign(this.form, form);
            Object.assign(this.authStore.form, this.form)
            this.dataForSAStep2 = this.form;
            if(this.step == 5){
                //    this.authStore.signup(this.form);
            }
            if(this.step==5){
                this.step = 1;
            }else{
                this.step++;
            }
            //router.push('/crtx/ai/learn')
        },
        stepBack(){
            this.step--;
        },
        inputFilled(event){
            event.target.classList.add("filled");
        },
        getConfig() {
            return {
                headers: {
                    Accept: "application/json",
                },
            };
        },
        getPractiseDetails() {
            let _self = this;
            const config = this.getConfig();
            const id = this.$route.params.id;
            axios
                .post("/getPractiseInfo",{id:id}, config)
                .then((response) => {
                    if (response.data.success) {
                        _self.clinic_logo = response.data.data.clinic_logo;
                        _self.calendar_type = response.data.data.calendar_type;
                    } else {
                        console.log("Error fetching appointment types:", response.data.error);
                    }
                })
                .catch((error) => {
                    console.log("Error:", error);
                });
        }
    },
}
</script>

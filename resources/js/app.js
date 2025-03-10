require('./bootstrap');

import { createApp } from 'vue';
import { createPinia } from 'pinia'
import router from './routes'
import { useAuthStore } from './stores/auth';

import Get_Started from './components/account/Get_Started';
import AC_Step from './components/account/AC_Step.vue';
import AI_Learning from './components/ai/AI_Learning';
import AI_Step from './components/ai/AI_Step.vue';
import AI_Finish from './components/ai/AI_Finish';
import AC_Sign_In from './components/account/AC_Sign_In';
import Container from './components/Container';
import AC_Setup from './components/account/AC_Setup.vue';
import SaveVapi from './components/pages/SaveVapi.vue';
import PrimeVue from 'primevue/config';

const pinia = createPinia();

console.warn = function() {};
// Or, to conditionally disable based on environment
if (process.env.NODE_ENV === 'development') {
    console.warn = function() {};
}

const app = createApp({
    data() {
        return {
            authStore: useAuthStore(),
            isLoading:false,
            requestCount: 0,
            patientProfilePath:'',
            logoutReload: true,
            isAppointmentPage:false,
        }
    },

    components: {
        Get_Started,
        AC_Step,
        AI_Learning,
        AI_Step,
        AI_Finish,
        AC_Sign_In,
        Container,
        AC_Setup,
        SaveVapi
    },
    watch:{
        $route (to, from){
            let user = localStorage.getItem('user');
            let path = localStorage.getItem('last_path');
                if(user==null && path && !path.includes('/crtx/account') && !path.includes('/crtx/ai') && !path.includes('/crtx/schedule-appointment')){
                    localStorage.clear();
                    location.reload();
                }else{
                if(user==null && (to.fullPath.includes('/crtx/patient-profile') || to.fullPath.includes('/crtx/save-voice-agent'))){
                    localStorage.setItem('last_path', '/crtx/account/signin');
                    location.href = '/crtx/account/signin';
                }else{
                    localStorage.setItem('last_path', to.fullPath);
                }
            }
        }
    },
    created(){
        let path = localStorage.getItem('last_path');
        let user = localStorage.getItem('user');
        if(path && !path.includes('/crtx/account') && !path.includes('/crtx/ai') && !path.includes('/crtx/schedule-appointment')){
            if(user !=null && (window.location.pathname.includes('/crtx/patient-profile') ||
                window.location.pathname.includes('/crtx/schedule-appointment') ||
                window.location.pathname.includes('/crtx/save-voice-agent'))){
                router.push(window.location.pathname+window.location.search);
            }else{
                router.push(path);
            }
        }

        if (process.env.NODE_ENV === "production") {
            console.log = () => { };
        }
    },
    mounted(){
        let _self = this;

        // Add a request interceptor
        axios.interceptors.request.use(function(config){
            _self.requestCount++;
            // Do something before request is sent
            if((config.params && !config.params.loading)){
                // Don't show loading indicator
                _self.isLoading = false;
            }else{
                // Show Loading indicator
                _self.isLoading = true;
            }

            return config;
        }, function (error) {
            // Do something with request error
            _self.isLoading = false;
            return Promise.reject(error);
        });

        // Add a response interceptor to intercept 401 Unauthorized error and logout the user.
        axios.interceptors.response.use(function (response) {
            // Any status code that lie within the range of 2xx cause this function to trigger
            // Do something with response data
            _self.requestCount--;
            if(_self.requestCount==0)
                _self.isLoading = false;
            return response;
        }, function (error) {
            // Any status codes that falls outside the range of 2xx cause this function to trigger
            // Do something with response error
            _self.requestCount--;
            if(_self.requestCount==0)
                _self.isLoading = false;
            if(error.response.status==401){
                localStorage.clear();
                location.reload();
            }
            return Promise.reject(error);
        });

        if(window.location.pathname.includes('/crtx/schedule-appointment')){
            this.isAppointmentPage = true;
        }
    },
});

app.use(pinia);

app.use(PrimeVue);

app.use(router).mount('#wrapperContainer');

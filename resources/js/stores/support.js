import { defineStore } from 'pinia'
import axios from 'axios'
import router from '../routes'
import { useAuthStore } from '../stores/auth';
import { useAlertStore } from './alert';
import { useClinicStore } from './clinic';

export const useSupportStore = defineStore({
    id: 'support',
    state: () => ({
        errors:[],
        ticket:{email:'', subject:'', description:''},
        formData: new FormData(),
        fileName: '',
        authStore: useAuthStore(),
        alertStore: useAlertStore(),
        clinicStore: useClinicStore()
    }),
    getters: {

    },
    actions: {
        getConfig(){
            return  {
                headers: {
                    headers: {
                        Accept: 'application/json',
                        Authorization: this.authStore.token_type + ' ' + this.authStore.token,
                }
                },
            }
        },
        submitTicket(){
            let _self = this;
            const config = {
                headers: {
                    headers: {
                        Accept: "application/json",
                        Authorization: this.token_type + " " + this.token,
                        ContentType: 'multipart/form-data'
                    },
                },
            };

            return axios.post('/api/v1/submit-ticket', this.formData, config)
            .then(function (response) {
              //console.log(response);
              if(response.data.success){
                _self.alertStore.success = true;
                _self.alertStore.message = response.data.message;
               _self.ticket = {email:'', subject:'', description:'', practice_name:''};
                _self.formData = new FormData();
                $('#support_file').val('');
                _self.fileName = '';
              }else{
                _self.errors = response.data.errors;
                _self.alertStore.success = false;
                _self.alertStore.message = response.data.message;
              }
            })
            .catch(function (error) {
              console.log('error', error);
            });
        }
    }
});

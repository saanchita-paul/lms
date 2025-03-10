import { defineStore } from 'pinia'
import axios from 'axios'
import router from '../routes'
import { useAuthStore } from '../stores/auth';

export const useResourceStore = defineStore({
  id: 'resource',
  state: () => ({
    sources : [],
    statuses : [],
    authStore: useAuthStore(),
  }),
  getters: {
    //doubleCount: (state) => state.counter * 2
  },
  actions: {
    getSources(){
        let _self = this;
        const config = {
          headers: {
            headers: {
              Accept: 'application/json',
              Authorization: this.authStore.token_type + ' ' + this.authStore.token
            }
          },
        }
        axios.get('/api/v1/getSources', {}, config)
        .then(function (response) {
         // console.log('stage response', response);
          if(response.data.success){
            _self.sources = response.data.getSources;
          }
        })
        .catch(function (error) {
          console.log('error', error);
        });
    },
    getStatuses(){
      let _self = this;
        const config = {
          headers: {
            headers: {
              Accept: 'application/json',
              Authorization: this.authStore.token_type + ' ' + this.authStore.token
            }
          },
        }
        axios.get('/api/v1/getAllStatus', {}, config)
        .then(function (response) {
         // console.log('status response', response);
          if(response.data.success){
            _self.statuses = response.data.data;
          }
        })
        .catch(function (error) {
          console.log('error', error);
        });
    }
  }
});
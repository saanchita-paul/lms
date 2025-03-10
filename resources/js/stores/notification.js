import { defineStore } from 'pinia'
import axios from 'axios'
import router from '../routes'
import { useAuthStore } from './auth';
import {useAlertStore} from "./alert";

export const useNotificationStore = defineStore({
    id: 'notification',
    state: () => ({
        authStore: useAuthStore(),
        alertStore: useAlertStore(),
        isSidebarVisible:false,
        notifications: [],
        current_page:0,
        unreadCount:0,
        selectedType:'all',
        listFetching : false,
    }),
    getters: {

    },
    actions: {
        getConfig(){
            return  {
                headers: {
                    headers: {
                        Accept: 'application/json',
                        Authorization: this.authStore.token_type + ' ' + this.authStore.token
                    }
                },
            }
        },
        list(page, type, loading = true) {
            let _self = this;
            const config = this.getConfig();

            this.listFetching = true;

            axios.post('/api/v1/notifications/list', {
                page: page,
                type: type,
            }, config)
                .then(function (response) {
                    //console.log(response);
                    _self.listFetching = false;
                    if (response.data.success) {
                        if (response.data.data) {
                            if(page==1){
                                _self.notifications = [];
                            }
                            let list = JSON.parse(JSON.stringify(response.data.data.data));
                            if(list.length>0){
                                _self.notifications = _self.notifications.concat(list);
                            }
                            _self.current_page = response.data.data.current_page;
                        } else {
                            _self.notifications = [];
                        }
                    }
                })
                .catch(function (error) {
                    console.log('error', error);
                });
        },
        read(notification_id, type){
            let _self = this;
            const config = this.getConfig();

            axios.post('/api/v1/notifications/read', {
                notification_id: notification_id,
            }, config)
                .then(function (response) {
                    _self.alertStore.success = response.data.success;
                    _self.alertStore.message = response.data.message;
                    if (response.data.success) {
                        _self.notifications.map(function(notification, index){
                            if(notification.id == notification_id){
                                if(notification.read_at == null)
                                    notification.read_at = '';
                                else
                                    notification.read_at = null;
                            }
                        });
                        _self.count();
                    }
                })
                .catch(function (error) {
                    console.log('error', error);
                });
        },
        readAll(){
            let _self = this;
            const config = this.getConfig();

            axios.post('/api/v1/notifications/readAll', {

            }, config)
                .then(function (response) {
                    _self.alertStore.success = response.data.success;
                    _self.alertStore.message = response.data.message;
                    if (response.data.success) {
                        _self.list(1, _self.selectedType)
                        _self.count();
                    }
                })
                .catch(function (error) {
                    console.log('error', error);
                });
        },
        count(){
            let _self = this;
            const config = this.getConfig();

            axios.get('/api/v1/notifications/count', config)
                .then(function (response) {
                    if (response.data.success) {
                        _self.unreadCount = response.data.data;
                    }
                })
                .catch(function (error) {
                    console.log('error', error);
                });
        },
        delete(notification_id, type){
            let _self = this;
            const config = this.getConfig();

            axios.post('/api/v1/notifications/delete', {
                notification_id: notification_id
            }, config)
                .then(function (response) {
                    _self.alertStore.success = response.data.success;
                    _self.alertStore.message = response.data.message;
                    if (response.data.success) {
                        _self.notifications.map(function(notification, index){
                            if(notification.id == notification_id){
                                _self.notifications.splice(index, 1);
                            }
                        });
                        _self.count();
                    }
                })
                .catch(function (error) {
                    console.log('error', error);
                });
        },
        deleteAll(){
            let _self = this;
            const config = this.getConfig();

            axios.post('/api/v1/notifications/deleteAll', {
            }, config)
                .then(function (response) {
                    _self.alertStore.success = response.data.success;
                    _self.alertStore.message = response.data.message;
                    if (response.data.success) {
                        _self.list(1, _self.selectedType);
                        _self.count();
                    }
                })
                .catch(function (error) {
                    console.log('error', error);
                });
        },
    }
});

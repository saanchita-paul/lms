<style>
.v-enter-from {
    opacity: 0;
    translate: 200px 0;
}
.v-enter-to {
    opacity: 1;
    translate: 0 0;
}
.v-enter-active,
.v-leave-active {
    transition: all 0.5s;
}
.v-leave-from { opacity: 1; }
.v-leave-to { opacity: 0; }
</style>
<template>
    <div id="wrapper" :class="authStore.sidebarVisible ? 'slide-menu-active' : ''">
        <Header></Header>
        <Notifications></Notifications>
        <router-view  v-slot="{ Component, route }" :env="env">
            <div :key="route.fullPath" :class="(authStore.multiple_clinics==1 || authStore.role == 'Admin') ? '' : ''">
                <component :is="Component" />
            </div>
        </router-view>
        <div v-if="alertStore.message != ''" class="alert alert-fixed d-flex justify-content-between" :class="alertStore.success? 'alert-success' : 'alert-danger'" role="alert">
            <svg v-if="!alertStore.success" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-exclamation-triangle-fill flex-shrink-0 me-2" viewBox="0 0 16 16" role="img" aria-label="Warning:">
                <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
            </svg>
            <svg v-if="alertStore.success" width="24px" height="24px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="ffffff"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path fill-rule="evenodd" clip-rule="evenodd" d="M22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12ZM16.0303 8.96967C16.3232 9.26256 16.3232 9.73744 16.0303 10.0303L11.0303 15.0303C10.7374 15.3232 10.2626 15.3232 9.96967 15.0303L7.96967 13.0303C7.67678 12.7374 7.67678 12.2626 7.96967 11.9697C8.26256 11.6768 8.73744 11.6768 9.03033 11.9697L10.5 13.4393L12.7348 11.2045L14.9697 8.96967C15.2626 8.67678 15.7374 8.67678 16.0303 8.96967Z" fill="#237d21"></path> </g></svg>
            {{ alertStore.message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" @click="alertStore.message = ''"></button>
        </div>
        <div class="toast-container position-absolute top-0 end-0 p-3" style="z-index:1001; margin-top:90px;">
            <TransitionGroup tag="div">
            <div v-for="notification in toastNotifications" :id="notification.id" :key="notification.id" class="toast show align-items-center text-white border-0 my-2" :class="getNotificationClass(notification.type)" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="false">
                <div class="d-flex">
                    <div role="button" class="toast-body"  @click="notification.lead ? viewProfile(notification.lead.id, notification.id ?? '') : viewProfile(null, notification.id ?? '')">
                        {{ notification.message }}
                    </div>
                    <button type="button" class="btn-nclose btn-close-white me-2 m-auto" @click="closeToastNotification(notification.id)" aria-label="Close"></button>
                </div>
            </div>
            </TransitionGroup>
        </div>
    </div>
</template>
<script>
import router from '../routes'
import axios from 'axios'
import { useInboxStore } from '../stores/inbox';
import { useLeadStore } from '../stores/lead';
import { useConsultationStore } from '../stores/consultation';

import Header from './Header.vue';
import Notifications from './pages/Notifications.vue';
import { useAlertStore } from '../stores/alert';
import { useClinicStore } from '../stores/clinic';
import { useAuthStore } from '../stores/auth';
import { useNotificationStore } from '../stores/notification';
import Echo from 'laravel-echo';

export default {
    setup (){
        let _self = this;
        const inboxStore = useInboxStore();
        const leadStore = useLeadStore();
        const alertStore = useAlertStore();
        const clinicStore = useClinicStore()
        const consultationStore = useConsultationStore();
        const authStore = useAuthStore();
        const notificationStore = useNotificationStore();

        alertStore.$subscribe(callback, { detached: true });

        function callback(data){
            if(data.events.newValue != ''){
                setTimeout(function(){
                    alertStore.success = false;
                    alertStore.message = '';
                }, 5000);
            }
        }

        return { inboxStore, leadStore, consultationStore, alertStore, clinicStore, authStore, notificationStore };
    },
    props:['env'],
    data() {
        return {
            timer: null,
            ai_shown:false,
            toastNotifications: [],
        }
    },
    mounted(){
        let _self = this;

        if(!this.timer){
            this.timer = setInterval(function(){
                _self.inboxStore.getNewSmsCount(false);
              //  _self.leadStore.list(false);
               // _self.consultationStore.list(false);
            }, 60000);
        }

        let isStaff = false;
        if(this.authStore.user.roles && this.authStore.user.roles.length>0){
            this.authStore.user.roles.map(function(role, index){
                if(role.id == 5){
                    isStaff = true;
                }
            });
        }

        if(isStaff && this.authStore.settings.doNotDisturb == 0 && (this.authStore.settings.followUpBrowserNotification == 1 || this.authStore.settings.appointmentBrowserNotification == 1 || this.authStore.settings.leadReconnectingBrowserNotification == 1 || this.authStore.settings.wherebyBrowserNotification == 1)){
            console.log('Listening on...' + 'App.Models.User.'+this.authStore.user.id);

            window.io = require('socket.io-client');

            let host =  window.location.hostname + ((_self.env === 'local')? ':6001' : '');

            window.Echo = new Echo({
                broadcaster: 'socket.io',
                host: host,
            });

            window.Echo.private('App.Models.User.'+this.authStore.user.id)
                .notification(e => {
                    console.log('channel notification', e);
                    _self.notificationStore.count();
                    if(_self.toastNotifications.length==6){
                        _self.closeToastNotification(_self.toastNotifications[5]);
                    }
                    _self.toastNotifications.unshift(e);

                    // Browser and Desktop notifications
                    if (!("Notification" in window)) {
                        // Check if the browser supports notifications
                        console.log("This browser does not support desktop notification");
                    } else if (Notification.permission === "granted") {
                        // Check whether notification permissions have already been granted;
                        // if so, create a notification
                        switch(e.type){
                            case 'App\\Notifications\\LeadMovedToFollowUp' :
                                new Notification('Lead moved to Follow Up Alert', {body: e.message, icon: '/images/logo/microsite-logo-image.png'});
                            break;
                            case 'App\\Notifications\\AppointmentScheduled' :
                                new Notification('New Patient Appointment! ', {body: e.message, icon: '/images/logo/microsite-logo-image.png'});
                            break;
                            case 'App\\Notifications\\LeadReconnecting' :
                                new Notification('Lead Reconnecting! ', {body: e.message, icon: '/images/logo/microsite-logo-image.png'});
                            break;
                            case 'App\\Notifications\\WherebyKnockNotification' :
                                new Notification('Video Consultation Room Knock! ', {body: e.message, icon: '/images/logo/microsite-logo-image.png'});
                            break;
                        }

                    } else if (Notification.permission !== "denied") {
                        // We need to ask the user for permission
                        Notification.requestPermission().then((permission) => {
                            // If the user accepts, let's create a notification
                            switch(e.type){
                                case 'App\\Notifications\\LeadMovedToFollowUp' :
                                    new Notification('Green Lead Alert', {body: e.message, icon: '/images/logo/microsite-logo-image.png'});
                                    break;
                                case 'App\\Notifications\\AppointmentScheduled' :
                                    new Notification('New Patient Appointment! ', {body: e.message, icon: '/images/logo/microsite-logo-image.png'});
                                    break;
                                case 'App\\Notifications\\LeadReconnecting' :
                                    new Notification('Lead Reconnecting! ', {body: e.message, icon: '/images/logo/microsite-logo-image.png'});
                                    break;
                                case 'App\\Notifications\\WherebyKnockNotification' :
                                    new Notification('Video Consultation Room Knock! ', {body: e.message, icon: '/images/logo/microsite-logo-image.png'});
                                    break;
                            }
                        });
                    }
                });
        }
    },
    components: {
        Header,
        Notifications,
    },
    updated() {
        let _self = this;

        if(!_self.authStore.ai_shown && _self.clinicStore.selectedClinic.ai_complete == 0){
            _self.authStore.form.clinic_id = _self.clinicStore.selectedClinic.id;
            router.push('/crtx/ai/step-1');
            _self.authStore.ai_shown = true;
        }
    },
    methods:{
        closeToastNotification(id){
            var index = this.toastNotifications.map(x => {
                return x.id;
            }).indexOf(id);

            this.toastNotifications.splice(index, 1);
        },
        viewProfile(profile_id, notification_id){
            console.log('view profile', profile_id)
            this.closeToastNotification(notification_id);
            if (profile_id) {
                localStorage.setItem('last_path', '/crtx/patient-profile/' + profile_id);
                window.open('/crtx/patient-profile/' + profile_id, '_blank');
            } else {
                localStorage.setItem('last_path', '/crtx/video-consultations');
                window.open('/crtx/video-consultations', '_blank');
            }
        },
        getNotificationClass(type){
            let status = '';
            switch(type){
                case 'App\\Notifications\\LeadMovedToFollowUp' :
                    status = 'bg-success';
                    break;
                case 'App\\Notifications\\AppointmentScheduled' :
                    status = 'bg-primary';
                    break;
                case 'App\\Notifications\\LeadReconnecting' :
                    status = 'bg-secondary';
                    break;
                case 'App\\Notifications\\WherebyKnockNotification' :
                    status = 'bg-info';
                    break;
            }

            return status;
        }
    }
}
</script>

import { createRouter, createWebHistory } from 'vue-router'

import Get_Started from './components/account/Get_Started.vue'
import AC_Step from './components/account/AC_Step.vue'
import AC_Step_1 from './components/account/AC_Step_1.vue'
import AC_Step_2 from './components/account/AC_Step_2.vue'
import SA_Step_1 from './components/appointment/SA_Step_1.vue'
import SA_Step_2 from './components/appointment/SA_Step_2.vue'
import SA_Step_3 from "./components/appointment/SA_Step_3";
import AC_Step_3 from './components/account/AC_Step_3.vue'
import AC_Step_4 from './components/account/AC_Step_4.vue'
import AC_Sign_In from './components/account/AC_Sign_In.vue'
import AC_Forgot_PW from './components/account/AC_Forgot_PW'
import AC_Reset_PW from './components/account/AC_Reset_PW'
import AC_Setup from './components/account/AC_Setup.vue'
import AI_Learning from './components/ai/AI_Learning.vue'
import AI_Step from './components/ai/AI_Step.vue'
import AI_Step_1 from './components/ai/AI_Step_1.vue'
import AI_Step_2 from './components/ai/AI_Step_2.vue'
import AI_Finish from './components/ai/AI_Finish.vue'
import Container from './components/Container.vue'
import Dashboard from './components/pages/Dashboard.vue'
import AppointmentSchedule from './components/pages/AppoinmentSchedule'
import ManagePractice from "./components/pages/ManagePractice.vue";
import Leads from './components/pages/Leads.vue'
import PatientProfile from './components/pages/PatientProfile.vue'
import Inbox from './components/pages/Inbox.vue'
import Reports from './components/pages/Reports.vue'
import BookConsultation from './components/pages/BookConsultation'
import AccountSettings from './components/pages/AccountSettings'
import Help from './components/pages/Help'
import NotFound from './components/pages/NotFound'
import Automation from './components/pages/Automation.vue'
import Telehealth from "./components/pages/Telehealth.vue";
import CreateAssistant from "./components/pages/CreateAssistant.vue";
import UploadFile from "./components/pages/UploadFile.vue";
import EmailStatistics from "./components/pages/EmailStatistics.vue";
import ChatHistory from "./components/pages/ChatHistory.vue";
import SaveVapi from "./components/pages/SaveVapi.vue";

const routes = [
    {
        path: '/crtx/account/start',
        name: 'start',
        meta: { title: 'Get Started' },
        component: Get_Started,
    },
    {
        path: '/crtx/schedule-appointment/:id',
        name: 'schedule-appointment',
        component: AppointmentSchedule,
        children:[
            {
                path: 'sa-step-1',
                name: 'sa_step_1',
                meta: { title: 'Schedule Appointment Step 1' },
                component: SA_Step_1,
            },
            {
                path: 'sa-step-2',
                name: 'sa_step_2',
                meta: { title: 'Schedule Appointment Step 2' },
                component: SA_Step_2,
            },
            {
                path: 'sa-step-3',
                name: 'sa_step_3',
                meta: { title: 'Schedule Appointment Step 3' },
                component: SA_Step_3,
            }
        ]
    },
    {
        path: '/crtx/account/setup',
        name: 'setup',
        meta: { title: 'Setup your Account' },
        component: AC_Setup,
    },
    {
        path: '/crtx/account/signin',
        name: 'signin',
        meta: { title: 'Sign In' },
        component: AC_Sign_In,
    },
    {
        path: '/crtx/account/forgot-pw',
        name: 'forgot-pw',
        meta: { title: 'Forgot Password' },
        component: AC_Forgot_PW,
    },
    {
        path: '/crtx/account/reset-pw/:token',
        name: 'reset-pw',
        meta: { title: 'Reset Password' },
        component: AC_Reset_PW,
    },
    {
        path: '/crtx/account',
        name: 'ac_step',
        component: AC_Step,
            children:[
            {
                path: 'step-1',
                name: 'ac_step_1',
                meta: { title: 'Step-1' },
                component: AC_Step_1,
            },{
                path: 'step-2',
                name: 'ac_step_2',
                meta: { title: 'Step-2' },
                component: AC_Step_2,
            },{
                path: 'step-3',
                name: 'ac_step_3',
                meta: { title: 'Step-3' },
                component: AC_Step_3,
            },{
                path: 'step-4',
                name: 'ac_step_4',
                meta: { title: 'Step-4' },
                component: AC_Step_4,
            }
        ]
    },
    {
        path: '/crtx/ai/learn',
        name: 'learn',
        meta: { title: 'AI Learning' },
        component: AI_Learning,
    },
    {
        path: '/crtx/ai',
        name: 'ai_step',
        component: AI_Step,
            children:[
            {
                path: 'step-1',
                name: 'ai_step_1',
                meta: { title: 'Step-1' },
                component: AI_Step_1,
            },{
                path: 'step-2',
                name: 'ai_step_2',
                meta: { title: 'Step-2' },
                component: AI_Step_2,
            }
            // ,{
            //     path: 'step-3',
            //     name: 'ai_step_3',
            //     meta: { title: 'Step-3' },
            //     component: AI_Step_3,
            // }
            // ,{
            //     path: 'step-4',
            //     name: 'ai_step_4',
            //     meta: { title: 'Step-4' },
            //     component: AI_Step_4,
            // }
            // ,{
            //         path: 'step-5',
            //         name: 'ai_step_5',
            //         meta: { title: 'Step-5' },
            //         component: AI_Step_5,
            //     }
        ]
    },
    {
        path: '/crtx/ai/finish',
        name: 'finish',
        meta: { title: 'Finish' },
        component: AI_Finish,
    },
    {
        path: '/crtx',
        name: 'index',
        component: Container,
            children: [
            {
                path: 'dashboard/:id?',
                name: 'dashboard',
                meta: { title: 'Dashboard' },
                component: Dashboard,
            },
            {
                path: 'manage-practice/:page?',
                name: 'manage-practice',
                meta: { title: 'Manage Practice' },
                component: ManagePractice,
            },
            {
                path: 'leads',
                name: 'leads',
                meta: { title: 'Leads' },
                component: Leads,
            },
            {
                path: 'patient-profile/:id',
                name: 'patient-profile',
                meta: { title: 'Patient Profile' },
                component: PatientProfile,
            },
            {
                path: 'save-voice-agent',
                name: 'Save Voice Agent',
                meta: { title: 'Save Voice Agent' },
                component: SaveVapi,
            },
            {
                path: 'inbox/:id?',
                name: 'inbox',
                meta: { title: 'Inbox' },
                component: Inbox,
            },
            {
                path: 'report',
                name: 'report',
                meta: { title: 'Reports' },
                component: Reports,
            },
            {
                path: 'reports/email-statistics',
                name: 'email-statistics',
                meta: { title: 'EmailStatistics' },
                component: EmailStatistics,
            },
            {
                path: 'consultations',
                name: 'consultations',
                meta: { title: 'Consultations' },
                component: BookConsultation,
            },
            {
                path: 'user-profile/:page?',
                name: 'account-settings',
                meta: { title: 'Account Settings' },
                component: AccountSettings,
            },
            {
                path: 'help',
                name: 'help',
                meta: { title: 'Help' },
                component: Help,
            },
            {
                path: 'not-found',
                name: 'not-found',
                meta: { title: '404 Not Found' },
                component: NotFound,
            },
            {
                path: 'automation/:id',
                name: 'automation',
                meta: { title: 'Automation' },
                component: Automation,
            },
            {
                path: 'video-consultations',
                name: 'video-consultations',
                meta: { title: 'Video Consultations' },
                component: Telehealth,
            },
            {
                path: 'chat-agent',
                name: 'Chat Agent',
                meta: { title: 'Chat Agent' },
                component: CreateAssistant,
            },
            {
                path: 'chat-history',
                name: 'Chat History',
                meta: { title: 'Chat History' },
                component: ChatHistory,
            },


        ]
    },
    // {
    //     path: '/crtx/patient-profile/:id',
    //     name: 'patient-profile',
    //     meta: { title: 'Patient Profile' },
    //     component: PatientProfile,
    // },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
    linkActiveClass: "active",
});

router.beforeEach((to, from, next) => {
    document.title = 'CRTX | ' + to.meta.title;
    next();
  });

export default router

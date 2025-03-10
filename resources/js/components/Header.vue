<template>
    <header id="header">
        <div class="header-left">
            <div class="logo-box d-none d-lg-block">
                <router-link role="button" to="/crtx/dashboard?view=list&page=sms" @click="this.showManagePracticeDropdown = false; activeMenu = 'dashboard';">
                    <div class="logo mx-auto">
                        <figure>
                            <svg version="1.1" x="0px" y="0px" viewBox="0 0 923 190"
                                 style="enable-background:new 0 0 923 190;">
                                <g>
                                    <path
                                        d="M34.28,59.34v68.31c0,10.11,8.23,18.35,18.35,18.35h170.66v21H52.63c-21.69,0-39.35-17.64-39.35-39.35V59.34c0-21.71,17.66-39.35,39.35-39.35h170.66v21H52.63C42.51,41,34.28,49.23,34.28,59.34z"/>
                                    <path
                                        d="M415.64,41h-147v126h-21v-147h168.01c23.17,0,42,18.85,42,42c0,23.15-18.83,42-42,42V83c11.58,0,21-9.43,21-21S427.22,41,415.64,41z M356.22,83l101.42,78.02V167h-26.68l-81.9-63h-38.42V83H356.22z"/>
                                    <path d="M680.87,19.99v21h-94.59v126h-21V41h-94.42v-21H680.87z"/>
                                    <path
                                        d="M900.62,29.74L836.86,93.5l63.76,63.76V167h-21v-1.04L817.66,104h-44.17l-61.96,61.96V167h-21v-9.74l63.76-63.76l-63.76-63.76v-9.74h21v1.04L773.49,83h44.17l61.96-61.96v-1.04h21V29.74z"/>
                                </g>
                            </svg>
                        </figure>
                    </div>
                </router-link>
            </div>
            <span class="menu-trigger" @click="authStore.sidebarVisible = !authStore.sidebarVisible">
				<span>Menu</span>
				<figure class="desk-menu-ico">
					<svg class="menu-back feather feather-chevrons-left" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="11 17 6 12 11 7"></polyline><polyline points="18 17 13 12 18 7"></polyline></svg>
				</figure>
			</span>
            <div class="header-menu">
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <router-link to="/crtx/dashboard?view=list&page=sms" class="nav-link" :class="activeMenu == 'dashboard' ? 'active show' : ''" aria-current="page" id="navbarDropdown" role="button" @click="menuClicked('dashboard')">
                                <figure>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-layout"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="3" y1="9" x2="21" y2="9"></line><line x1="9" y1="21" x2="9" y2="9"></line></svg>
                                </figure>
                                <span>Dashboard</span>
                            </router-link>
                        </li>
                        <li class="nav-item">
                            <router-link class="nav-link" to="/crtx/leads" :class="activeMenu == 'leads' ? 'active show' : ''" @click="menuClicked('leads')">
                                <figure>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-list"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></svg>
                                </figure>
                                <span>Leads</span>
                            </router-link>
                        </li>
                        <li class="nav-item">
                            <router-link class="nav-link" to="/crtx/consultations" :class="activeMenu == 'consultations' ? 'active show' : ''" @click="menuClicked('consultations')">
                                <figure>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                                </figure>
                                <span>Consultations</span>
                            </router-link>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link" aria-current="page" id="navbarDropdown1" role="button"
                               :class="showReportsDropdown ? 'active show' : ''" @click="menuClicked('reportsdd')">
                                <figure>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                                </figure>
                                <span>Reports</span>
                            </a>
                            <ul v-if="showReportsDropdown" class="dropdown-menu" :class="activeMenu == 'reportsdd' ? 'show' : ''">
                                <li><router-link class="dropdown-item" to="/crtx/report" @click="closeSidebar()">Reports</router-link></li>
                                <li><router-link class="dropdown-item" to="/crtx/reports/email-statistics" @click="closeSidebar()">Email Statistics </router-link></li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link" aria-current="page" id="navbarDropdown2" role="button"
                               :class="showManagePracticeDropdown ? 'active show' : ''" @click="menuClicked('manage-practice')">
                                <figure>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-layers"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 12 17 22 12"></polyline></svg>
                                </figure>
                                <span>Manage your practice</span>
                            </a>
                            <ul v-if="showManagePracticeDropdown" class="dropdown-menu" :class="activeMenu == 'manage-practice' ? 'show' : ''">
                                <li><router-link class="dropdown-item" to="/crtx/manage-practice/practice-information" @click="closeSidebar()">Practice Information</router-link></li>
<!--                                <li><router-link class="dropdown-item" to="/crtx/manage-practice/marketing" @click="closeSidebar()">Marketing </router-link></li>-->
<!--                                <li><router-link class="dropdown-item" to="/crtx/manage-practice/nurturing-templates">Nurturing Templates</router-link></li>-->
                                <li><router-link class="dropdown-item" to="/crtx/manage-practice/email-templates" @click="closeSidebar()">Email Templates</router-link></li>
                                <li><router-link class="dropdown-item" to="/crtx/manage-practice/text-templates" @click="closeSidebar()">Text Templates</router-link></li>
                                <li><router-link class="dropdown-item" to="/crtx/manage-practice/appointment-calendar" @click="closeSidebar()">Appointment Calendar</router-link></li>
                                <li><router-link id="automation-link" class="dropdown-item" to="/crtx/manage-practice/automation" @click="closeSidebar()">Automation</router-link></li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <router-link class="nav-link" to="/crtx/video-consultations" :class="activeMenu == 'video-consultations' ? 'active show' : ''" @click="menuClicked('video-consultations')">
                                <figure>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-video">
                                        <path d="M23 7l-7 5 7 5V7z"></path>
                                        <rect x="1" y="5" width="15" height="14" rx="2" ry="2"></rect>
                                    </svg>
                                </figure>
                                <span>Video Consultations</span>
                            </router-link>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link" aria-current="page" id="navbarDropdown1" role="button"
                               :class="showChatAgentDropdown ? 'active show' : ''" @click="menuClicked('crtx-agent')">
                                <figure>
                                    <svg version="1.1" x="0px" y="0px" viewBox="0 0 24 24" style="enable-background:new 0 0 24 24; fill: currentColor;" xml:space="preserve">
                                    <g>
                                        <path d="M16,22c-0.6,0-1-0.4-1-1v-2c0-1.7-1.3-3-3-3H5c-1.7,0-3,1.3-3,3v2c0,0.6-0.4,1-1,1s-1-0.4-1-1v-2c0-2.8,2.2-5,5-5h7c2.8,0,5,2.2,5,5v2C17,21.6,16.6,22,16,22z"></path>
                                    </g>
                                        <g>
                                        <path d="M8.5,12c-2.8,0-5-2.2-5-5s2.2-5,5-5s5,2.2,5,5S11.3,12,8.5,12z M8.5,4c-1.7,0-3,1.3-3,3s1.3,3,3,3s3-1.3,3-3S10.2,4,8.5,4z"></path>
                                    </g>
                                        <g>
                                        <path d="M16.5,13c-0.1,0-0.3-0.1-0.4-0.2C16,12.7,16,12.5,16,12.3l0.7-2c-0.2-0.5-0.3-1-0.3-1.5c0-1.4,0.8-2.8,2.1-3.4C19,5.1,19.6,5,20.2,5c0,0,0,0,0,0h0.2c2,0.1,3.5,1.6,3.6,3.6l0,0.2c0,0.6-0.1,1.2-0.4,1.7c-0.6,1.3-1.9,2.1-3.4,2.1c0,0,0,0,0,0c-0.5,0-1-0.1-1.5-0.3l-2,0.7C16.6,13,16.6,13,16.5,13z M20.2,6.1c-0.4,0-0.8,0.1-1.2,0.3c-0.9,0.5-1.5,1.4-1.5,2.5c0,0.4,0.1,0.8,0.3,1.2c0.1,0.1,0.1,0.3,0,0.4l-0.4,1.2l1.2-0.4c0.1,0,0.3,0,0.4,0c0.4,0.2,0.8,0.3,1.2,0.3c0,0,0,0,0,0c1.1,0,2-0.6,2.5-1.5c0.2-0.4,0.3-0.8,0.3-1.2c0,0,0,0,0,0V8.6c-0.1-1.4-1.2-2.5-2.6-2.6L20.2,6.1C20.2,6.1,20.2,6.1,20.2,6.1zM24,8.8L24,8.8L24,8.8z"></path>
                                    </g>
                                    </svg>
                                </figure>
                                <span>CRTX Agent
                                <span class="new-added">New</span>
                                </span>
                            </a>
                            <ul v-if="showChatAgentDropdown" class="dropdown-menu" :class="activeMenu == 'crtx-agent' ? 'show' : ''">
                                <li><router-link class="dropdown-item" to="/crtx/chat-agent" @click="closeSidebar()">SetUp Chat Agent</router-link></li>
                                <li><router-link class="dropdown-item" to="/crtx/chat-history" @click="closeSidebar()">Chat History</router-link></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="header-top">
            <div class="container-fluid p-0">
                <div class="d-lg-flex align-items-center">
                    <div class="header-top-logo-box d-flex flex-lg-row-reverse align-items-center">
                        <router-link role="button" to="/crtx/dashboard?view=list&page=sms">
                            <div class="logo-box ms-lg-3 me-xl-5 me-lg-3">
                                <div class="logo me-auto me-md-3">
                                    <figure>
                                        <svg version="1.1" x="0px" y="0px" viewBox="0 0 923 190"

                                             style="enable-background:new 0 0 923 190;">
                                            <g>
                                                <path
                                                    d="M34.28,59.34v68.31c0,10.11,8.23,18.35,18.35,18.35h170.66v21H52.63c-21.69,0-39.35-17.64-39.35-39.35V59.34c0-21.71,17.66-39.35,39.35-39.35h170.66v21H52.63C42.51,41,34.28,49.23,34.28,59.34z"/>
                                                <path
                                                    d="M415.64,41h-147v126h-21v-147h168.01c23.17,0,42,18.85,42,42c0,23.15-18.83,42-42,42V83c11.58,0,21-9.43,21-21S427.22,41,415.64,41z M356.22,83l101.42,78.02V167h-26.68l-81.9-63h-38.42V83H356.22z"/>
                                                <path d="M680.87,19.99v21h-94.59v126h-21V41h-94.42v-21H680.87z"/>
                                                <path
                                                    d="M900.62,29.74L836.86,93.5l63.76,63.76V167h-21v-1.04L817.66,104h-44.17l-61.96,61.96V167h-21v-9.74l63.76-63.76l-63.76-63.76v-9.74h21v1.04L773.49,83h44.17l61.96-61.96v-1.04h21V29.74z"/>
                                            </g>
                                        </svg>
                                    </figure>
                                </div>
                            </div>
                        </router-link>
                        <span class="toggle-search" @click="authStore.searchVisible = !authStore.searchVisible">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <g>
                                    <path
                                        d="M11 19C15.4183 19 19 15.4183 19 11C19 6.58172 15.4183 3 11 3C6.58172 3 3 6.58172 3 11C3 15.4183 6.58172 19 11 19Z"
                                        stroke="#8287A2" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"/>
                                    <path d="M21 21L16.65 16.65" stroke="#8287A2" stroke-width="2"
                                          stroke-linecap="round" stroke-linejoin="round"/>
                                </g>
                            </svg>
                        </span>
                        <span class="menu-trigger" @click="authStore.sidebarVisible = !authStore.sidebarVisible"><span>Menu</span></span>
                    </div>
                    <div class="lead-search w-100" v-show="authStore.searchVisible">
<!--                        <span class="search-input-ico" v-show="authStore.searchVisible">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <g opacity="0.5">
                                    <path
                                        d="M11 19C15.4183 19 19 15.4183 19 11C19 6.58172 15.4183 3 11 3C6.58172 3 3 6.58172 3 11C3 15.4183 6.58172 19 11 19Z"
                                        stroke="#8287A2" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"/>
                                    <path d="M21 21L16.65 16.65" stroke="#8287A2" stroke-width="2"
                                          stroke-linecap="round" stroke-linejoin="round"/>
                                </g>
                            </svg>
                        </span>-->
                        <vue-multiselect
                            v-model="leadStore.selectedLead"
                            :options="leadStore.searchedLeads"
                            selectLabel=""
                            :close-on-select="true"
                            placeholder="Search..."
                            :custom-label="getLeadSearchLabel"
                            track-by="id"
                            :internal-search="false"
                            @search-change="searchGlobal"
                            @select="openLeadsProfile"
                            @close="leadStore.searchedLeads = []"
                            class="form-select form-control p-0"
                        >
                            <template #option="props">
                                <h5><i class="fa fa-user"></i>&nbsp;{{props.option.full_name}}</h5><span class="d-block mb-2"><i class="fa fa-envelope"></i>&nbsp;{{ props.option.email }}</span><span class="d-block"><i class="fa fa-mobile"></i>&nbsp;&nbsp;{{ props.option.phone }}</span>
                            </template>
                            <template #noResult>
                                <span>No results found.</span>
                            </template>
                            <template #noOptions>
                                <span>No results found.</span>
                            </template>
                            <template #caret>
                            </template>
                        </vue-multiselect>
<!--                        <input :type="authStore.searchVisible ? 'text' : 'hidden'" class="form-control" placeholder="Search..." id="globalSearch" autocomplete="off" v-model="query" @keyup="searchGlobal">-->
                    </div>
                    <div class="top-select" v-show="authStore.searchVisible">
                        <div class="d-flex justify-content-center clinics-search" v-if="authStore.multiple_clinics==1 || authStore.role == 'Admin'">
                            <VueMultiselect
                                v-model="clinicStore.selectedClinic"
                                :options="clinicStore.allClinics"
                                :close-on-select="false"
                                placeholder="Search Clinics"
                                label="label"
                                track-by="id"
                                @search-change="searchClinics"
                                @select="setSelectedClinics"
                                class="form-select form-control p-0"
                            />
                        </div>
                    </div>
                    <div class="header-right-menu d-flex align-items-center ms-auto">
                        <div class="home-ico d-lg-none">
                            <router-link to="/crtx/dashboard?view=list&page=sms" href="dashboard.html" class="active">
                                <svg width="25" height="24" viewBox="0 0 25 24" fill="none"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M2.34375 22H8.20312V13.6667H16.7969V22H22.6562V9L12.5 2.5L2.34375 9V22ZM0 24V8L12.5 0L25 8V24H14.4531V15.6667H10.5469V24H0Z"
                                        fill="white"/>
                                </svg>
                            </router-link>
                        </div>
                        <div class="header-mail-ico tooltip-ico" data-title="Need Help?">
                            <router-link to="/crtx/help" class="">
                                <figure>
                                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M10 18.75C7.67936 18.75 5.45376 17.8281 3.81282 16.1872C2.17187 14.5462 1.25 12.3206 1.25 10C1.25 7.67936 2.17187 5.45376 3.81282 3.81282C5.45376 2.17187 7.67936 1.25 10 1.25C12.3206 1.25 14.5462 2.17187 16.1872 3.81282C17.8281 5.45376 18.75 7.67936 18.75 10C18.75 12.3206 17.8281 14.5462 16.1872 16.1872C14.5462 17.8281 12.3206 18.75 10 18.75ZM10 20C12.6522 20 15.1957 18.9464 17.0711 17.0711C18.9464 15.1957 20 12.6522 20 10C20 7.34784 18.9464 4.8043 17.0711 2.92893C15.1957 1.05357 12.6522 0 10 0C7.34784 0 4.8043 1.05357 2.92893 2.92893C1.05357 4.8043 0 7.34784 0 10C0 12.6522 1.05357 15.1957 2.92893 17.0711C4.8043 18.9464 7.34784 20 10 20Z"
                                            fill="black"/>
                                        <path
                                            d="M6.56869 7.2325C6.56698 7.27287 6.57354 7.31316 6.58798 7.3509C6.60241 7.38863 6.62441 7.42302 6.65263 7.45194C6.68085 7.48085 6.71468 7.5037 6.75205 7.51906C6.78942 7.53442 6.82954 7.54197 6.86994 7.54125H7.90119C8.07369 7.54125 8.21119 7.4 8.23369 7.22875C8.34619 6.40875 8.90869 5.81125 9.91119 5.81125C10.7687 5.81125 11.5537 6.24 11.5537 7.27125C11.5537 8.065 11.0862 8.43 10.3474 8.985C9.50619 9.59625 8.83994 10.31 8.88744 11.4688L8.89119 11.74C8.8925 11.822 8.926 11.9002 8.98446 11.9577C9.04293 12.0153 9.12167 12.0475 9.20369 12.0475H10.2174C10.3003 12.0475 10.3798 12.0146 10.4384 11.956C10.497 11.8974 10.5299 11.8179 10.5299 11.735V11.6038C10.5299 10.7063 10.8712 10.445 11.7924 9.74625C12.5537 9.1675 13.3474 8.525 13.3474 7.17625C13.3474 5.2875 11.7524 4.375 10.0062 4.375C8.42244 4.375 6.68744 5.1125 6.56869 7.2325ZM8.51494 14.4362C8.51494 15.1025 9.04619 15.595 9.77744 15.595C10.5387 15.595 11.0624 15.1025 11.0624 14.4362C11.0624 13.7463 10.5374 13.2613 9.77619 13.2613C9.04619 13.2613 8.51494 13.7463 8.51494 14.4362Z"
                                            fill="black"/>
                                    </svg>
                                </figure>
                            </router-link>
                        </div>
                        <div class="header-mail-ico tooltip-ico" data-title="Notifications">
                            <a role="button" id="notificationLink" @click.prevent="notificationClicked()">
                                <figure>
                                    <svg width="64px" height="64px" viewBox="0 0 24 24" fill="none"
                                         xmlns="http://www.w3.org/2000/svg" stroke="#ffffff">
                                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round"
                                           stroke-linejoin="round"></g>
                                        <g id="SVGRepo_iconCarrier">
                                            <path
                                                d="M9.00195 17H5.60636C4.34793 17 3.71872 17 3.58633 16.9023C3.4376 16.7925 3.40126 16.7277 3.38515 16.5436C3.37082 16.3797 3.75646 15.7486 4.52776 14.4866C5.32411 13.1835 6.00031 11.2862 6.00031 8.6C6.00031 7.11479 6.63245 5.69041 7.75766 4.6402C8.88288 3.59 10.409 3 12.0003 3C13.5916 3 15.1177 3.59 16.2429 4.6402C17.3682 5.69041 18.0003 7.11479 18.0003 8.6C18.0003 11.2862 18.6765 13.1835 19.4729 14.4866C20.2441 15.7486 20.6298 16.3797 20.6155 16.5436C20.5994 16.7277 20.563 16.7925 20.4143 16.9023C20.2819 17 19.6527 17 18.3943 17H15.0003M9.00195 17L9.00031 18C9.00031 19.6569 10.3435 21 12.0003 21C13.6572 21 15.0003 19.6569 15.0003 18V17M9.00195 17H15.0003"
                                                stroke="#ffffff" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round"></path>
                                        </g>
                                    </svg>
                                    <span class="mail-count">{{ notificationStore.unreadCount }}</span>
                                </figure>
                            </a>
                        </div>
                        <div class="header-mail-ico tooltip-ico" data-title="Inbox">
                            <router-link to="/crtx/inbox?view=list&amp;page=sms" class="" id="mailLink" @click="menuClicked('mail')">
                                <figure>
                                    <svg width="28" height="23" viewBox="0 0 28 23" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M2.1 22.1562C1.54 22.1562 1.05 21.95 0.63 21.5375C0.21 21.125 0 20.6438 0 20.0938V2.21875C0 1.66875 0.21 1.1875 0.63 0.775C1.05 0.3625 1.54 0.15625 2.1 0.15625H25.9C26.46 0.15625 26.95 0.3625 27.37 0.775C27.79 1.1875 28 1.66875 28 2.21875V20.0938C28 20.6438 27.79 21.125 27.37 21.5375C26.95 21.95 26.46 22.1562 25.9 22.1562H2.1ZM14 11.775L2.1 4.10938V20.0938H25.9V4.10938L14 11.775ZM14 9.7125L25.76 2.21875H2.275L14 9.7125ZM2.1 4.10938V2.21875V20.0938V4.10938Z"
                                            fill="#F5F5F5"></path>
                                    </svg>
                                    <span class="mail-count">{{ inboxStore.newSmsCount }}</span>
                                </figure>
                            </router-link>
                        </div>
                        <div class="header-profile-box">
                            <div class="dropdown">
                                <div class="dropdown-toggle" type="button" id="profileLink" :class="activeMenu == 'profile' ? 'show' : ''" @click="menuClicked('profile')">
                                    <div v-if="authStore.user" class="header-profile 123 d-none d-lg-flex">
                                        <figure>
                                            <img
                                                v-if="authStore.user.profile_pic!=null" :src="authStore.user.profile_pic.preview" alt="">
                                            <span v-else>{{userInitials}}</span>
                                        </figure>
                                    </div>
                                    <div class="header-user-ico d-lg-none">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M12 11.5942C10.35 11.5942 9 11.0531 7.95 9.97101C6.9 8.88889 6.375 7.49758 6.375 5.7971C6.375 4.09662 6.9 2.70531 7.95 1.62319C9 0.541063 10.35 0 12 0C13.65 0 15 0.541063 16.05 1.62319C17.1 2.70531 17.625 4.09662 17.625 5.7971C17.625 7.49758 17.1 8.88889 16.05 9.97101C15 11.0531 13.65 11.5942 12 11.5942ZM0 24V20.3671C0 19.3881 0.2375 18.5507 0.7125 17.8551C1.1875 17.1594 1.8 16.6312 2.55 16.2705C4.225 15.4976 5.83125 14.9179 7.36875 14.5314C8.90625 14.1449 10.45 13.9517 12 13.9517C13.55 13.9517 15.0875 14.1514 16.6125 14.5507C18.1375 14.9501 19.7365 15.5258 21.4095 16.278C22.1921 16.642 22.8194 17.1697 23.2917 17.8613C23.7639 18.5528 24 19.3881 24 20.3671V24H0ZM2.25 21.6812H21.75V20.3671C21.75 19.9549 21.6313 19.562 21.3938 19.1884C21.1562 18.8148 20.8625 18.5378 20.5125 18.3575C18.9125 17.5588 17.45 17.0113 16.125 16.715C14.8 16.4187 13.425 16.2705 12 16.2705C10.575 16.2705 9.1875 16.4187 7.8375 16.715C6.4875 17.0113 5.025 17.5588 3.45 18.3575C3.1 18.5378 2.8125 18.8148 2.5875 19.1884C2.3625 19.562 2.25 19.9549 2.25 20.3671V21.6812ZM12 9.27536C12.975 9.27536 13.7813 8.94686 14.4188 8.28986C15.0563 7.63285 15.375 6.80193 15.375 5.7971C15.375 4.79227 15.0563 3.96135 14.4188 3.30435C13.7813 2.64734 12.975 2.31884 12 2.31884C11.025 2.31884 10.2188 2.64734 9.58125 3.30435C8.94375 3.96135 8.625 4.79227 8.625 5.7971C8.625 6.80193 8.94375 7.63285 9.58125 8.28986C10.2188 8.94686 11.025 9.27536 12 9.27536Z"
                                                fill="#959595"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="dropdown-menu" v-if="showProfileDropdown" aria-labelledby="dropdownMenuButton1">
                                    <div class="header-profile-dropdown">
                                        <div class="logout-link d-none d-lg-block">
                                            <a href="#" role="button" @click="logout(); showProfileDropdown=false;">Log Out</a>
                                        </div>
                                        <span class="profile-close d-block d-lg-none" @click="showProfileDropdown = false;">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                        </span>
                                        <h3 class="d-block d-lg-none">My Profile</h3>
                                        <div class="header-profile 456">
                                            <figure>
                                                <img v-if="authStore.user.profile_pic" :src="authStore.user.profile_pic.preview"/>
                                                <span v-else>{{ getInitials(authStore.user.name) }}</span>
                                            </figure>
                                            <div class="header-profile-info">
                                                <h5>{{authStore.user.name}}</h5>
                                                <p class="mb-0 me-0 ms-0 mt-1">{{authStore.user.email}}</p>
                                            </div>
                                        </div>
                                        <ul>
                                            <li @click="showProfileDropdown=false"><router-link to="/crtx/user-profile">Account Settings</router-link></li>
                                            <li @click="showProfileDropdown=false"><router-link to="/crtx/inbox?view=list&page=sms">Inbox</router-link></li>
                                            <li @click="showProfileDropdown=false"><router-link to="/crtx/help">Need Assistance?</router-link></li>
                                            <li class="d-block d-lg-none sign-out-link"><a href="#" role="button" @click="logout(); showProfileDropdown=false;">Sign Out</a></li>
                                        </ul>
                                        <div class="copyrights d-block d-lg-none">
                                            <p>© 2024 Microsite All rights reserved.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
</template>
<script>
import router from '../routes'
import {useAuthStore} from '../stores/auth';
import {useInboxStore} from '../stores/inbox';
import {useLeadStore} from '../stores/lead';
import {useClinicStore} from '../stores/clinic';
import {useNotificationStore} from '../stores/notification';
import VueMultiselect from 'vue-multiselect'
import 'vue-multiselect/dist/vue-multiselect.css'
import Notifications from "./pages/Notifications.vue";

export default {
    setup() {
        const authStore = useAuthStore();

        const leadStore = useLeadStore();

        const inboxStore = useInboxStore();

        const notificationStore = useNotificationStore();

        const clinicStore = useClinicStore();

        return {authStore, leadStore, inboxStore, notificationStore, clinicStore};
    },
    data() {
        return {
            showProfileDropdown: false,
            query: '',
            selectedLead: null,
            activeMenu: '',
            showManagePracticeDropdown: false,
            showReportsDropdown: false,
            userInitials: '',
            timout:null,
            wherebyLink: '',
            showChatAgentDropdown: false,
        }
    },
    components: {
        VueMultiselect,
    },
    created(){
        this.clinicStore.search();
        this.wherebyLink = localStorage.getItem('whereby_link')?.length > 5
    },
    mounted() {
        let _self = this;

        let nameArr = this.authStore.user ? this.authStore.user.name.split(' ') : '';
        if (nameArr.length >= 2) {
            this.userInitials = nameArr[0].charAt(0).toUpperCase() + nameArr[1].charAt(0).toUpperCase();
        } else {
            this.userInitials = nameArr[0].charAt(0).toUpperCase() + nameArr[0].charAt(1).toUpperCase();
        }

        if(location.href.includes('manage-practice')){
            this.showManagePracticeDropdown = true;
        }
        if(this.$route.params.id && location.href.includes('automation')){
            this.showManagePracticeDropdown = true;
        }

        $(document).mouseup(function (e) {
            var headerProfileBox = $(".header-profile-box");

            // if the target of the click isn't the container nor a descendant of the container
            if (!headerProfileBox.is(e.target) && headerProfileBox.has(e.target).length === 0) {
                _self.showProfileDropdown = false;
            }

            var headerLeft = $(".header-left");

            // if the target of the click isn't the container nor a descendant of the container
            if (!window.matchMedia("(min-width: 991px)").matches && !headerLeft.is(e.target) && headerLeft.has(e.target).length === 0) {
                _self.authStore.sidebarVisible = false;
            }

            var globalSearch = $(".lead-search");
            var clinicSearch = $(".top-select");
            var header = $(".header-top-logo-box");

            // if the target of the click isn't the container nor a descendant of the container
            if (!window.matchMedia("(min-width: 991px)").matches && !globalSearch.is(e.target) && !clinicSearch.is(e.target) && !header.is(e.target) && globalSearch.has(e.target).length === 0 && clinicSearch.has(e.target).length === 0 && header.has(e.target).length === 0) {
                _self.authStore.searchVisible = false;
            }
        });

        $(".nav-item:not(.dropdown)").on('click', function(){
            if (!window.matchMedia("(min-width: 991px)").matches){
                _self.authStore.sidebarVisible = false;
            }
        });

        window.addEventListener('resize', this.checkWindowSize);

        this.inboxStore.getNewSmsCount();
        this.notificationStore.count();
    },
    methods: {
        logout() {
            this.authStore.logout();
        },
        searchGlobal(term) {
            if(term.length>=3){
                this.leadStore.searchLeads(term);
            } else {
                this.leadStore.searchedLeads = [];
            }
        },
        openLeadsProfile() {
            router.push('/crtx/patient-profile/' + this.leadStore.selectedLead.id);
            this.leadStore.selectedLead = null;
        },
        getLeadSearchLabel(lead){
            return lead.full_name + ' - ' + lead.email + ' - ' +  lead.phone;
        },
        menuClicked(menu) {
            this.activeMenu = menu;

            if(this.activeMenu === 'manage-practice'){
                this.showManagePracticeDropdown = !this.showManagePracticeDropdown;
                this.showReportsDropdown = false;
            }

            if(this.activeMenu === 'reportsdd'){
                this.showManagePracticeDropdown = false;
                this.showReportsDropdown = !this.showReportsDropdown;
            }

            if(this.activeMenu === 'crtx-agent'){
                // this.showChatAgentDropdown = false;
                this.showChatAgentDropdown = !this.showChatAgentDropdown;

            }

            if(this.activeMenu !== 'manage-practice' && this.activeMenu !== 'reportsdd' && this.activeMenu !== 'profile' && this.activeMenu !== 'crtx-agent') {
                this.showManagePracticeDropdown = false;
                this.showReportsDropdown = false;
                this.showChatAgentDropdown = false;
            }

            if (menu == 'profile') {
                this.showProfileDropdown = true;
                $('#homeLink').removeClass('active');
                $('#mailLink').removeClass('active');
            } else {
                this.showProfileDropdown = false;
            }
        },
        getInitials(name) {
            // Split the name into words
            const words = name.split(' ');

            if (words.length === 1) {
                // If there's only one word, return the first two characters
                return words[0].substring(0, 2).toUpperCase();
            } else if (words.length > 1) {
                // If there are multiple words, take the first character of each word
                const initials = words.map(word => word.charAt(0)).join('');
                return initials.toUpperCase();
            } else {
                // Handle other cases as needed
                return '';
            }
        },
        notificationClicked() {
            this.notificationStore.isSidebarVisible = true;

            this.notificationStore.list(1, 'all');

            this.notificationStore.count();
        },
        setSelectedClinics(){
            localStorage.setItem('clinic_id', this.clinicStore.selectedClinic.id);
            localStorage.setItem('clinic_name', JSON.stringify(this.clinicStore.selectedClinic.practice_name));
            localStorage.setItem('inbox_id', JSON.stringify(this.clinicStore.selectedClinic.inbox_id));
            localStorage.setItem('whereby_link', this.clinicStore.selectedClinic.whereby_link);
            localStorage.setItem('assistant_id', this.clinicStore.selectedClinic.assistant_id);
            const currentPath = window.location.pathname;
            console.log(this.clinicStore.selectedClinic?.whereby_link?.length)
            if(currentPath === '/crtx/video-consultations' && !this.clinicStore?.selectedClinic?.whereby_link){
                this.$router.push('/crtx/dashboard?view=list&page=sms');
            }
            // console.log('whereby_link local storage after selected clinic', localStorage.getItem('whereby_link'));
            this.authStore.ai_shown = false;
            location.reload();
        },
        searchClinics(term){
            this.clinicStore.search(term);
        },
        checkWindowSize() {
            this.authStore.searchVisible = this.authStore.sidebarVisible = window.matchMedia("(min-width: 991px)").matches;
        },
        closeSidebar(){
            if (!window.matchMedia("(min-width: 991px)").matches){
                this.authStore.sidebarVisible = false;
            }
        },
    },
}
</script>

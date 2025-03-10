<template>
    <!-- Sidebar -->
    <div id="notification" :class="['sidebar', notificationStore.isSidebarVisible ? 'visible' : '']" aria-hidden="true">
        <!-- Edit link to toggle container -->
        <!-- Container part inside the sidebar -->
        <div class="sidebar__content" v-show="notificationStore.isSidebarVisible">
            <!-- Dropdown -->
            <div class="sidebar-top-sticky">
                <div id="container" class="pt-1 float-end me-3">
                    <div id="menu-wrap">
                        <input type="checkbox" class="toggler tooltip-ico" data-title="Options"/>
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" >
                            <path d="M12 13C12.5523 13 13 12.5523 13 12C13 11.4477 12.5523 11 12 11C11.4477 11 11 11.4477 11 12C11 12.5523 11.4477 13 12 13Z" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M12 6C12.5523 6 13 5.55228 13 5C13 4.44772 12.5523 4 12 4C11.4477 4 11 4.44772 11 5C11 5.55228 11.4477 6 12 6Z" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M12 20C12.5523 20 13 19.5523 13 19C13 18.4477 12.5523 18 12 18C11.4477 18 11 18.4477 11 19C11 19.5523 11.4477 20 12 20Z" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <div class="menu">
                            <div>
                                <ul>
                                    <li>
                                        <a role="button" class="link" @click="markAllRead()">
                                        <svg width="20px" height="20px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <circle cx="12" cy="12" r="10" stroke="#1C274C" stroke-width="1.5"></circle> <path d="M8.5 12.5L10.5 14.5L15.5 9.5"  stroke="#1C274C" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>
                                        &nbsp;Mark all as read</a>
                                    </li>
                                    <li>
                                        <a role="button" class="link"  data-bs-toggle="modal"
                                           data-bs-target="#DeleteNotificationModal">
                                        <svg width="20px" height="24px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M7.69231 8.70833H5V8.16667H9.84615M7.69231 8.70833V19H16.3077V8.70833M7.69231 8.70833H16.3077M16.3077 8.70833H19V8.16667H14.1538M9.84615 8.16667V6H14.1538V8.16667M9.84615 8.16667H14.1538" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M10 11V17" stroke="#000000" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M12 11V17" stroke="#000000" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M14 11V17" stroke="#000000" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>
                                        &nbsp;Delete all</a>
                                    </li>
                                    <li><a role="button" class="link" @click="viewSettings()">
                                        <svg width="20px" height="20px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <circle cx="12" cy="12" r="3" stroke="#1C274C" stroke-width="1.5"></circle> <path d="M13.7654 2.15224C13.3978 2 12.9319 2 12 2C11.0681 2 10.6022 2 10.2346 2.15224C9.74457 2.35523 9.35522 2.74458 9.15223 3.23463C9.05957 3.45834 9.0233 3.7185 9.00911 4.09799C8.98826 4.65568 8.70226 5.17189 8.21894 5.45093C7.73564 5.72996 7.14559 5.71954 6.65219 5.45876C6.31645 5.2813 6.07301 5.18262 5.83294 5.15102C5.30704 5.08178 4.77518 5.22429 4.35436 5.5472C4.03874 5.78938 3.80577 6.1929 3.33983 6.99993C2.87389 7.80697 2.64092 8.21048 2.58899 8.60491C2.51976 9.1308 2.66227 9.66266 2.98518 10.0835C3.13256 10.2756 3.3397 10.437 3.66119 10.639C4.1338 10.936 4.43789 11.4419 4.43786 12C4.43783 12.5581 4.13375 13.0639 3.66118 13.3608C3.33965 13.5629 3.13248 13.7244 2.98508 13.9165C2.66217 14.3373 2.51966 14.8691 2.5889 15.395C2.64082 15.7894 2.87379 16.193 3.33973 17C3.80568 17.807 4.03865 18.2106 4.35426 18.4527C4.77508 18.7756 5.30694 18.9181 5.83284 18.8489C6.07289 18.8173 6.31632 18.7186 6.65204 18.5412C7.14547 18.2804 7.73556 18.27 8.2189 18.549C8.70224 18.8281 8.98826 19.3443 9.00911 19.9021C9.02331 20.2815 9.05957 20.5417 9.15223 20.7654C9.35522 21.2554 9.74457 21.6448 10.2346 21.8478C10.6022 22 11.0681 22 12 22C12.9319 22 13.3978 22 13.7654 21.8478C14.2554 21.6448 14.6448 21.2554 14.8477 20.7654C14.9404 20.5417 14.9767 20.2815 14.9909 19.902C15.0117 19.3443 15.2977 18.8281 15.781 18.549C16.2643 18.2699 16.8544 18.2804 17.3479 18.5412C17.6836 18.7186 17.927 18.8172 18.167 18.8488C18.6929 18.9181 19.2248 18.7756 19.6456 18.4527C19.9612 18.2105 20.1942 17.807 20.6601 16.9999C21.1261 16.1929 21.3591 15.7894 21.411 15.395C21.4802 14.8691 21.3377 14.3372 21.0148 13.9164C20.8674 13.7243 20.6602 13.5628 20.3387 13.3608C19.8662 13.0639 19.5621 12.558 19.5621 11.9999C19.5621 11.4418 19.8662 10.9361 20.3387 10.6392C20.6603 10.4371 20.8675 10.2757 21.0149 10.0835C21.3378 9.66273 21.4803 9.13087 21.4111 8.60497C21.3592 8.21055 21.1262 7.80703 20.6602 7C20.1943 6.19297 19.9613 5.78945 19.6457 5.54727C19.2249 5.22436 18.693 5.08185 18.1671 5.15109C17.9271 5.18269 17.6837 5.28136 17.3479 5.4588C16.8545 5.71959 16.2644 5.73002 15.7811 5.45096C15.2977 5.17191 15.0117 4.65566 14.9909 4.09794C14.9767 3.71848 14.9404 3.45833 14.8477 3.23463C14.6448 2.74458 14.2554 2.35523 13.7654 2.15224Z" stroke="#1C274C" stroke-width="1.5"></path> </g></svg>
                                        &nbsp;Settings</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Close Button -->
                <button class="close-btn tooltip-ico" data-title="Close" id="notificationClose" @click="notificationStore.isSidebarVisible = false">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M18 6L6 18" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M6 6L18 18" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
                <h3>Notifications</h3>
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a
                            class="nav-link active"
                            id="All-tab"
                            data-bs-toggle="tab"
                            data-bs-target="#All"
                            type="button"
                            role="tab"
                            aria-controls="All"
                            aria-selected="true"
                            @click="list('all')"
                        >All</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a
                            class="nav-link"
                            id="Unread-tab"
                            data-bs-toggle="tab"
                            data-bs-target="#Unread"
                            type="button"
                            role="tab"
                            aria-controls="Unread"
                            aria-selected="true"
                            @click="list('unread')"
                        >Unread</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a
                            class="nav-link"
                            id="Read-tab"
                            data-bs-toggle="tab"
                            data-bs-target="#Read"
                            type="button"
                            role="tab"
                            aria-controls="Read"
                            aria-selected="true"
                            @click="list('read')"
                        >Read</a>
                    </li>
                </ul>
            </div>
            <div class="tab-data">
                <div class="tab-content" id="myTabContent">
                    <div
                        class="tab-pane fade show active"
                        id="All"
                        role="tabpanel"
                        aria-labelledby="All-tab"
                    >
                        <ol role="button" class="notification-list-group w-100" v-if="notificationStore.selectedType == 'all' && notificationStore.notifications.length>0">
                            <li v-for="notification in notificationStore.notifications"
                                class="notification-list-box d-flex justify-content-between align-items-start"
                                :class="notification.read_at != null ? 'notification-read' : 'notification-unread'">
                                <div @click="read(notification, notification.type)">
                                    <div>{{ notification.data.message }}</div>
<!--                                    <em><small>{{
                                            new Date(notification.created_at).toLocaleDateString('en-us', {
                                                year: "numeric",
                                                month: "short",
                                                day: "numeric"
                                            })
                                        }} {{
                                            new Date(notification.created_at).toLocaleTimeString('en-us', {
                                                hour: '2-digit',
                                                minute: '2-digit'
                                            })
                                        }}</small></em>-->
                                    <em><small>{{notification.created_at_formatted}}</small></em>
                                </div>
                                <!--<button class="close-btn top-0 p-0 m-0" @click="remove(notification, 'all')">
                                    <svg width="30px" height="30px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M7.69231 8.70833H5V8.16667H9.84615M7.69231 8.70833V19H16.3077V8.70833M7.69231 8.70833H16.3077M16.3077 8.70833H19V8.16667H14.1538M9.84615 8.16667V6H14.1538V8.16667M9.84615 8.16667H14.1538" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M10 11V17" stroke="#000000" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M12 11V17" stroke="#000000" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M14 11V17" stroke="#000000" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>
                                </button>-->
                                <div class="notification-list-icons">
                                    <span class="mark-read-ico tooltip-ico" :data-title="notification.read_at == null ? 'Read' : 'Unread'">
                                        <svg class="circle" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" @click="toggleRead(notification, 'all')">
                                            <g>
                                                <path d="M10 18.3333C14.6024 18.3333 18.3333 14.6023 18.3333 9.99996C18.3333 5.39759 14.6024 1.66663 10 1.66663C5.39762 1.66663 1.66666 5.39759 1.66666 9.99996C1.66666 14.6023 5.39762 18.3333 10 18.3333Z" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            </g>
                                        </svg>
                                        <svg class="circle-fill" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" @click="toggleRead(notification, 'all')">
                                            <g>
                                                <path d="M10 18.3333C14.6024 18.3333 18.3333 14.6023 18.3333 9.99996C18.3333 5.39759 14.6024 1.66663 10 1.66663C5.39762 1.66663 1.66666 5.39759 1.66666 9.99996C1.66666 14.6023 5.39762 18.3333 10 18.3333Z" fill="#425BCF" stroke="#425BCF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            </g>
                                        </svg>
                                    </span>
                                    <span class="delete-ico tooltip-ico" data-title="Delete" @click="remove(notification, 'all')">
                                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M2.5 5H4.16667H17.5" stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M15.8333 4.99996V16.6666C15.8333 17.1087 15.6577 17.5326 15.3452 17.8451C15.0326 18.1577 14.6087 18.3333 14.1667 18.3333H5.83333C5.39131 18.3333 4.96738 18.1577 4.65482 17.8451C4.34226 17.5326 4.16667 17.1087 4.16667 16.6666V4.99996M6.66667 4.99996V3.33329C6.66667 2.89127 6.84226 2.46734 7.15482 2.15478C7.46738 1.84222 7.89131 1.66663 8.33333 1.66663H11.6667C12.1087 1.66663 12.5326 1.84222 12.8452 2.15478C13.1577 2.46734 13.3333 2.89127 13.3333 3.33329V4.99996" stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M8.33333 9.16663V14.1666" stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M11.6667 9.16663V14.1666" stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </span>
                                </div>
                            </li>
                        </ol>
                        <h6 class="text-center my-2" v-if="notificationStore.selectedType == 'all' && notificationStore.notifications.length == 0">Currently there are no notifications!</h6>
                    </div>
                    <div
                        class="tab-pane fade"
                        id="Unread"
                        role="tabpanel"
                        aria-labelledby="Unread-tab">
                        <ol role="button" class="notification-list-group w-100" v-if="notificationStore.selectedType == 'unread' && notificationStore.notifications.length>0">
                            <li v-for="notification in notificationStore.notifications"
                                class="notification-list-box d-flex justify-content-between align-items-start" :class="notification.read_at != null ? 'notification-read' : 'notification-unread'">
                                <div @click="read(notification)">
                                    <div>{{ notification.data.message }}</div>
<!--                                    <em><small>{{
                                            new Date(notification.created_at).toLocaleDateString('en-us', {
                                                year: "numeric",
                                                month: "short",
                                                day: "numeric"
                                            })
                                        }} {{
                                            new Date(notification.created_at).toLocaleTimeString('en-us', {
                                                hour: '2-digit',
                                                minute: '2-digit'
                                            })
                                        }}
                                        </small>
                                    </em>-->
                                    <em><small>{{notification.created_at_formatted}}</small></em>
                                </div>
                                <!--<button class="close-btn top-0 p-0 m-0" @click="remove(notification, 'unread')">
                                    <svg width="30px" height="30px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M7.69231 8.70833H5V8.16667H9.84615M7.69231 8.70833V19H16.3077V8.70833M7.69231 8.70833H16.3077M16.3077 8.70833H19V8.16667H14.1538M9.84615 8.16667V6H14.1538V8.16667M9.84615 8.16667H14.1538" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M10 11V17" stroke="#000000" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M12 11V17" stroke="#000000" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M14 11V17" stroke="#000000" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>
                                </button>-->
                                <div class="notification-list-icons">
                                    <span class="mark-read-ico tooltip-ico" :data-title="notification.read_at == null ? 'Read' : 'Unread'" >
                                        <svg class="circle" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" @click="toggleRead(notification, 'unread')">
                                            <g>
                                                <path d="M10 18.3333C14.6024 18.3333 18.3333 14.6023 18.3333 9.99996C18.3333 5.39759 14.6024 1.66663 10 1.66663C5.39762 1.66663 1.66666 5.39759 1.66666 9.99996C1.66666 14.6023 5.39762 18.3333 10 18.3333Z" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            </g>
                                        </svg>
                                        <svg class="circle-fill" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" @click="toggleRead(notification, 'all')">
                                            <g>
                                                <path d="M10 18.3333C14.6024 18.3333 18.3333 14.6023 18.3333 9.99996C18.3333 5.39759 14.6024 1.66663 10 1.66663C5.39762 1.66663 1.66666 5.39759 1.66666 9.99996C1.66666 14.6023 5.39762 18.3333 10 18.3333Z" fill="#425BCF" stroke="#425BCF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            </g>
                                        </svg>
                                    </span>
                                    <span class="delete-ico tooltip-ico" data-title="Delete" @click="remove(notification, 'unread')">
                                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M2.5 5H4.16667H17.5" stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M15.8333 4.99996V16.6666C15.8333 17.1087 15.6577 17.5326 15.3452 17.8451C15.0326 18.1577 14.6087 18.3333 14.1667 18.3333H5.83333C5.39131 18.3333 4.96738 18.1577 4.65482 17.8451C4.34226 17.5326 4.16667 17.1087 4.16667 16.6666V4.99996M6.66667 4.99996V3.33329C6.66667 2.89127 6.84226 2.46734 7.15482 2.15478C7.46738 1.84222 7.89131 1.66663 8.33333 1.66663H11.6667C12.1087 1.66663 12.5326 1.84222 12.8452 2.15478C13.1577 2.46734 13.3333 2.89127 13.3333 3.33329V4.99996" stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M8.33333 9.16663V14.1666" stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M11.6667 9.16663V14.1666" stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </span>
                                </div>
                            </li>
                        </ol>
                        <h6 class="text-center my-2" v-if="notificationStore.selectedType == 'unread' && notificationStore.notifications.length == 0">Currently there are no unread notifications!</h6>
                    </div>
                    <div
                        class="tab-pane fade"
                        id="Read"
                        role="tabpanel"
                        aria-labelledby="Read-tab">
                        <ol role="button" class="notification-list-group w-100" v-if="notificationStore.selectedType == 'read' && notificationStore.notifications.length>0">
                            <li v-for="notification in notificationStore.notifications"
                                class="notification-list-box d-flex justify-content-between align-items-start" :class="notification.read_at != null ? 'notification-read' : 'notification-unread'">
                                <div>
                                    <div>{{ notification.data.message }}</div>
<!--                                    <em><small>{{
                                            new Date(notification.created_at).toLocaleDateString('en-us', {
                                                year: "numeric",
                                                month: "short",
                                                day: "numeric"
                                            })
                                        }} {{
                                            new Date(notification.created_at).toLocaleTimeString('en-us', {
                                                hour: '2-digit',
                                                minute: '2-digit'
                                            })
                                        }}
                                    </small></em>-->
                                    <em><small>{{notification.created_at_formatted}}</small></em>
                                </div>
                                <!--<button class="close-btn top-0 right-0 p-0 m-0" @click="remove(notification, 'read')">
                                    <svg width="30px" height="30px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M7.69231 8.70833H5V8.16667H9.84615M7.69231 8.70833V19H16.3077V8.70833M7.69231 8.70833H16.3077M16.3077 8.70833H19V8.16667H14.1538M9.84615 8.16667V6H14.1538V8.16667M9.84615 8.16667H14.1538" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M10 11V17" stroke="#000000" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M12 11V17" stroke="#000000" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M14 11V17" stroke="#000000" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>
                                </button>-->
                                <div class="notification-list-icons">
                                    <span class="mark-read-ico tooltip-ico" :data-title="notification.read_at == null ? 'Read' : 'Unread'">
                                        <svg class="circle" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" @click="toggleRead(notification, 'read')">
                                            <g>
                                                <path d="M10 18.3333C14.6024 18.3333 18.3333 14.6023 18.3333 9.99996C18.3333 5.39759 14.6024 1.66663 10 1.66663C5.39762 1.66663 1.66666 5.39759 1.66666 9.99996C1.66666 14.6023 5.39762 18.3333 10 18.3333Z" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            </g>
                                        </svg>
                                        <svg class="circle-fill" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" @click="toggleRead(notification, 'all')">
                                            <g>
                                                <path d="M10 18.3333C14.6024 18.3333 18.3333 14.6023 18.3333 9.99996C18.3333 5.39759 14.6024 1.66663 10 1.66663C5.39762 1.66663 1.66666 5.39759 1.66666 9.99996C1.66666 14.6023 5.39762 18.3333 10 18.3333Z" fill="#425BCF" stroke="#425BCF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            </g>
                                        </svg>
                                    </span>
                                    <span class="delete-ico tooltip-ico" data-title="Delete" @click="remove(notification, 'read')">
                                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M2.5 5H4.16667H17.5" stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M15.8333 4.99996V16.6666C15.8333 17.1087 15.6577 17.5326 15.3452 17.8451C15.0326 18.1577 14.6087 18.3333 14.1667 18.3333H5.83333C5.39131 18.3333 4.96738 18.1577 4.65482 17.8451C4.34226 17.5326 4.16667 17.1087 4.16667 16.6666V4.99996M6.66667 4.99996V3.33329C6.66667 2.89127 6.84226 2.46734 7.15482 2.15478C7.46738 1.84222 7.89131 1.66663 8.33333 1.66663H11.6667C12.1087 1.66663 12.5326 1.84222 12.8452 2.15478C13.1577 2.46734 13.3333 2.89127 13.3333 3.33329V4.99996" stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M8.33333 9.16663V14.1666" stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M11.6667 9.16663V14.1666" stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </span>
                                </div>
                            </li>
                        </ol>
                        <h6 class="text-center my-2" v-if="notificationStore.selectedType == 'read' && notificationStore.notifications.length == 0">Currently there are no read notifications!</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="DeleteNotificationModal" tabindex="-1" aria-labelledby="DeleteModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content p-3">
                <div class="modal-body text-md-center p-3 mb-3">
                    <h3>Are you sure you want to delete all Notifications?</h3>
                    <p class="m-0">This will delete all notifications permanently. you cannot undo this action.</p>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-outline-primary px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary px-4" @click="deleteAll()">Delete</button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import router from '../../routes'
import {useNotificationStore} from './../../stores/notification';

export default {
    setup() {
        const notificationStore = useNotificationStore();

        return {notificationStore};
    },
    data() {
        return {}
    },
    mounted() {
        let _self = this;
        const notificationDiv = document.querySelector('#yourNotificationDivId'); // Use correct selector

        if (notificationDiv) {
            notificationDiv.onscroll = (e) => {
                if (notificationDiv.offsetHeight + notificationDiv.scrollTop + 10 >= notificationDiv.scrollHeight && !_self.notificationStore.listFetching) {
                    _self.notificationStore.current_page++;
                    _self.notificationStore.list(_self.notificationStore.current_page, _self.notificationStore.selectedType);
                }
            };
        } else {
            console.warn('Notification div not found');
        }

        $(document).mouseup(function(e)
        {
            var checked = $('.toggler').prop('checked');
            var container = $("#notification #container");
            var notification = $("#notification");

            // if the target of the click isn't the container nor a descendant of the container
            if (!container.is(e.target) && checked && container.has(e.target).length === 0 )
            {
                $('.toggler').prop('checked', false);
            }

            if (!notification.is(e.target) && notification.has(e.target).length === 0 )
            {
                _self.notificationStore.isSidebarVisible = false
            }
        });
    },
    methods: {
        moment: function () {
            return moment();
        },
        list(type) {
            this.notificationStore.selectedType = type;
            this.notificationStore.current_page = 1;
            this.notificationStore.notifications = [];
            this.notificationStore.list(this.notificationStore.current_page, type);
        },

        read(notification, type) {
            if (notification.type == 'App\\Notifications\\GreenLeadCreated') {
                if (notification.data.lead) {
                    localStorage.setItem('last_path', '/crtx/patient-profile/' + notification.data.lead.id);
                    window.open('/crtx/patient-profile/' + notification.data.lead.id, '_blank');
                }else {
                    // Redirect to video consultations if no lead is present
                    localStorage.setItem('last_path', '/crtx/video-consultations');
                    window.open('/crtx/video-consultations', '_blank');
                }
            } else {
                if (notification.data.lead) {
                    localStorage.setItem('last_path', '/crtx/patient-profile/' + notification.data.lead.id);
                    window.open('/crtx/patient-profile/' + notification.data.lead.id, '_blank');
                }else {
                    // Redirect to video consultations if no lead is present
                    localStorage.setItem('last_path', '/crtx/video-consultations');
                    window.open('/crtx/video-consultations', '_blank');
                }
            }

            this.notificationStore.read(notification.id, type);
        },
        toggleRead(notification, type){
            this.notificationStore.read(notification.id, type);
        },
        remove(notification, type){
            this.notificationStore.delete(notification.id, type)
        },
        markAllRead(){
            this.notificationStore.readAll();
            $('.toggler').prop('checked', false);
        },
        deleteAll(){
            this.notificationStore.deleteAll();
            $('.toggler').prop('checked', false);
            $('#DeleteNotificationModal').modal('hide');
        },
        viewSettings(){
            router.push('/crtx/user-profile/notifications');
            $('.toggler').prop('checked', false);
            $('#notificationClose').trigger('click');
        }
    },
}
</script>

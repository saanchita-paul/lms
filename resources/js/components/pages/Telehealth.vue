<template>
    <div class="card card-body mt-4 border-0 shadow-sm">
        <div>
            <whereby-embed v-if="wherebyLink" :room="wherebyLink" style="height: 700px; width: 100%"></whereby-embed>
            <div class="bg-primary-light card card-body border-0">
                <div class="container h-auto">
                    <div class="row g-3 align-items-center flex-row-reverse">
                        <div class="col-md-6">
                            <figure class="m-0 w-100">
                                <img :src="'/crtxassets/images/video-consultation/video-landing-01.jpg'" alt="" class="w-100 rounded-3 h-auto">
                            </figure>
                        </div>
                        <div class="col-md-6">
                            <h2>Integrate customizable video calls effortlessly</h2>
                            <p>Effortlessly integrate video calling into your business operations, ideal for Virtual Consultations. Experience seamless, browser-based video calls with no downloads or guest logins needed.</p>
                            <p v-if="!isActivated">To activate the video consultation, please click the button below.</p>
                            <div>
                                <div v-if="!isActivated">
                                    <button @click="activateClinic" class="btn btn-primary">
                                        Activate
                                    </button>
                                </div>
                                <div v-else>
                                    <h5>Embed room</h5>
                                    <p>Copy and paste the generated code into your website or app to allow people to join the room.</p>
                                    <div class="embed-container">
                                        <div class="embed-code-wrapper">
                                        <pre class="embed-code pe-5">&lt;iframe src="{{ participantLink }}" allow="camera; microphone; fullscreen; speaker; display-capture; compute-pressure" style="height: 700px; width: 100%"&gt;&lt;/iframe&gt;
                                        </pre>
                                            <button @click="copyEmbedCode" class="copy-button">
                                                <i class="fas fa-copy"></i>
                                            </button>
                                            <span v-if="copyMessage" class="copy-message">Copied!</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--                        <p class="p-2" v-if="isActivated">Activation email sent! Please wait for confirmation.</p>-->
                        </div>
                    </div>
                </div>
            </div>

            <div class="scalable-solution-row my-4 my-md-5">
                <div class="text-center mb-3">
                    <h2>A flexible and scalable solution</h2>
                </div>
                <div class="container h-auto">
                    <div class="row g-3 justify-content-center">
                        <div class="col-lg-11 col-xl-8">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="h-100 card p-2">
                                        <figure class="m-0 w-100">
                                            <img :src="'/crtxassets/images/video-consultation/video-landing-02.jpg'" alt="" class="w-100 rounded-3 h-auto">
                                        </figure>
                                        <div class="my-1 p-2 pb-0">
                                            <h5 class="m-0">Group Video Consultations</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="h-100 card p-2">
                                        <figure class="m-0 w-100">
                                            <img :src="'/crtxassets/images/video-consultation/video-landing-03.jpg'" alt="" class="w-100 rounded-3 h-auto">
                                        </figure>
                                        <div class="my-1 p-2 pb-0">
                                            <h5 class="m-0">1 on 1 Video Consultations</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card card-body bg-primary-light border-0 py-4 py-md-5">
                <div class="text-center mb-3">
                    <h2>Lots of built in features including:</h2>
                </div>
                <div class="row g-3 justify-content-center">
                    <div class="col-lg-11 col-xl-9">
                        <div class="features-btn-group">
              <span class="features-label">
                <figure>
                  <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M16.442 2H7.558C6.614 2 5.844 2 5.22 2.051C4.574 2.104 3.996 2.216 3.457 2.491C2.61035 2.92195 1.92181 3.60979 1.49 4.456C1.216 4.995 1.104 5.573 1.051 6.219C1 6.844 1 7.614 1 8.558V11.442C1 12.386 1 13.156 1.051 13.78C1.104 14.426 1.216 15.004 1.491 15.543C1.92238 16.3894 2.61057 17.0776 3.457 17.509C3.996 17.784 4.574 17.896 5.22 17.949C5.844 18 6.614 18 7.558 18H9.2L6.91 20.863C6.82775 20.9659 6.77621 21.09 6.76132 21.2209C6.74643 21.3518 6.76879 21.4842 6.82582 21.603C6.88286 21.7218 6.97225 21.8221 7.08373 21.8923C7.19521 21.9625 7.32425 21.9998 7.456 22H16.544C16.6758 21.9998 16.8048 21.9625 16.9163 21.8923C17.0277 21.8221 17.1171 21.7218 17.1742 21.603C17.2312 21.4842 17.2536 21.3518 17.2387 21.2209C17.2238 21.09 17.1723 20.9659 17.09 20.863L14.8 18H16.442C17.387 18 18.156 18 18.78 17.949C19.426 17.896 20.004 17.784 20.543 17.509C21.3894 17.0776 22.0776 16.3894 22.509 15.543C22.784 15.004 22.896 14.426 22.949 13.78C23 13.156 23 12.387 23 11.442V8.558C23 7.614 23 6.844 22.949 6.22C22.896 5.574 22.784 4.996 22.509 4.457C22.0778 3.6102 21.3896 2.92164 20.543 2.49C20.004 2.216 19.426 2.104 18.78 2.051C18.156 2 17.387 2 16.442 2ZM4.365 4.272C4.575 4.166 4.861 4.087 5.383 4.044C5.916 4.001 6.603 4 7.6 4H16.4C17.397 4 18.084 4 18.617 4.044C19.139 4.087 19.425 4.166 19.635 4.272C20.1053 4.51189 20.4875 4.89451 20.727 5.365C20.834 5.575 20.913 5.861 20.956 6.383C20.999 6.916 21 7.603 21 8.6V11.4C21 12.397 21 13.084 20.956 13.617C20.913 14.139 20.834 14.425 20.727 14.635C20.4874 15.1051 20.1051 15.4874 19.635 15.727C19.425 15.834 19.139 15.913 18.617 15.956C18.084 15.999 17.397 16 16.4 16H7.6C6.603 16 5.916 16 5.383 15.956C4.861 15.913 4.575 15.835 4.365 15.727C3.89451 15.4875 3.5119 15.1053 3.272 14.635C3.166 14.425 3.087 14.139 3.044 13.617C3.001 13.084 3 12.397 3 11.4V8.6C3 7.603 3 6.916 3.044 6.383C3.087 5.861 3.166 5.575 3.272 5.365C3.51173 4.89435 3.89435 4.51172 4.365 4.272Z" fill="#425BCF"></path>
                  </svg>
                </figure>
                <span>Get Started Now</span>
              </span>
                <span class="features-label">
                <figure>
                  <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M14.665 1H9.335C7.633 1 6.591 1 5.705 1.218C4.37703 1.54535 3.16385 2.2296 2.19672 3.19672C1.2296 4.16385 0.545346 5.37703 0.218 6.705C0 7.591 0 8.633 0 10.335V10.665C0 12.367 0 13.409 0.218 14.295C0.545346 15.623 1.2296 16.8362 2.19672 17.8033C3.16385 18.7704 4.37703 19.4547 5.705 19.782C6.591 20 7.633 20 9.335 20H12.333L17 23.5C17.824 24.118 19 23.53 19 22.5V19.571C20.1718 19.1567 21.2223 18.4576 22.0571 17.5367C22.8918 16.6158 23.4845 15.5018 23.782 14.295C24 13.409 24 12.367 24 10.665V10.335C24 8.633 24 7.591 23.782 6.705C23.4547 5.37703 22.7704 4.16385 21.8033 3.19672C20.8361 2.2296 19.623 1.54535 18.295 1.218C17.409 1 16.367 1 14.665 1ZM14.5 3C16.419 3 17.198 3.007 17.816 3.16C18.7899 3.40004 19.6796 3.90184 20.3889 4.6111C21.0982 5.32037 21.6 6.21009 21.84 7.184C21.993 7.802 22 8.581 22 10.5C22 12.419 21.993 13.198 21.84 13.816C21.6 14.7899 21.0982 15.6796 20.3889 16.3889C19.6796 17.0982 18.7899 17.6 17.816 17.84C17.198 17.993 16.419 18 14.5 18H9.5C7.581 18 6.802 17.993 6.184 17.84C5.21009 17.6 4.32037 17.0982 3.6111 16.3889C2.90184 15.6796 2.40004 14.7899 2.16 13.816C2.007 13.198 2 12.42 2 10.5C2 8.581 2.007 7.802 2.16 7.184C2.40004 6.21009 2.90184 5.32037 3.6111 4.6111C4.32037 3.90184 5.21009 3.40004 6.184 3.16C6.802 3.007 7.58 3 9.5 3H14.5ZM9.397 9.557C9.33933 9.43844 9.25878 9.33245 9.15999 9.24513C9.0612 9.15782 8.94612 9.0909 8.82137 9.04823C8.69662 9.00557 8.56466 8.98799 8.43309 8.99651C8.30152 9.00504 8.17294 9.03949 8.05473 9.0979C7.93653 9.15631 7.83105 9.23752 7.74435 9.33685C7.65765 9.43618 7.59145 9.55168 7.54956 9.67669C7.50767 9.8017 7.49091 9.93377 7.50026 10.0653C7.5096 10.1968 7.54486 10.3252 7.604 10.443C8.01654 11.2782 8.65422 11.9816 9.44512 12.4738C10.236 12.966 11.1487 13.2275 12.0803 13.2287C13.0118 13.23 13.9252 12.971 14.7174 12.4809C15.5097 11.9908 16.1492 11.2891 16.564 10.455C16.6725 10.2188 16.6848 9.94973 16.5982 9.70468C16.5117 9.45962 16.3332 9.25788 16.1005 9.14217C15.8678 9.02647 15.5992 9.00591 15.3516 9.08483C15.104 9.16376 14.8968 9.33597 14.774 9.565C14.5252 10.0657 14.1413 10.4869 13.6659 10.7811C13.1904 11.0752 12.6421 11.2307 12.083 11.2299C11.5239 11.229 10.9761 11.0719 10.5015 10.7764C10.0269 10.4808 9.64435 10.0584 9.397 9.557Z" fill="#425BCF"></path>
                  </svg>
                </figure>
                <span>Live chat</span>
              </span>
               <span class="features-label">
                <figure>
                  <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M9.8 9L10.655 13.27C10.6965 13.4758 10.8079 13.6609 10.9703 13.7939C11.1327 13.927 11.3361 13.9998 11.546 14H15.301" stroke="#425BCF" stroke-width="1.886" stroke-linecap="round"></path>
                    <path d="M18 4H6C3.79086 4 2 5.79086 2 8V16C2 18.2091 3.79086 20 6 20H18C20.2091 20 22 18.2091 22 16V8C22 5.79086 20.2091 4 18 4Z" stroke="#425BCF" stroke-width="2"></path>
                  </svg>
                </figure>
                <span>Waiting room</span>
              </span>
                            <span class="features-label">
                            <figure>
                              <svg width="200" height="200" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200">
                                  <defs>
                                    <radialGradient id="bg-gradient" cx="50%" cy="50%" r="50%" fx="50%" fy="50%">
                                      <stop offset="0%" style="stop-color: #425BCF; stop-opacity: 1" />
                                      <stop offset="100%" style="stop-color: #6A9DFF; stop-opacity: 0.8" />
                                    </radialGradient>
                                  </defs>
                                  <rect width="200" height="200" fill="url(#bg-gradient)" />
                                  <circle cx="60" cy="60" r="50" fill="rgba(255,255,255,0.3)" />
                                  <circle cx="140" cy="140" r="30" fill="rgba(255,255,255,0.3)" />
                                  <circle cx="100" cy="160" r="20" fill="rgba(255,255,255,0.5)" />
                                  <circle cx="170" cy="60" r="15" fill="rgba(255,255,255,0.2)" />
                                </svg>


                            </figure>
                            <span>Background effects</span>
                          </span>
                            <span class="features-label">
                            <figure>
                             <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <path d="M9 3H3V9H5V5H9V3ZM21 9H19V5H15V3H21V9ZM5 15H3V21H9V19H5V15ZM19 19V21H15V19H19V15H21V19H19Z" fill="#425BCF"></path>
                            </svg>

                            </figure>
                            <span>Fullscreen</span>
                          </span>
                            <span class="features-label">
                            <figure>
                              <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.477 2 2 6.477 2 12C2 17.523 6.477 22 12 22C17.523 22 22 17.523 22 12C22 6.477 17.523 2 12 2ZM12 20C7.589 20 4 16.411 4 12C4 7.589 7.589 4 12 4C16.411 4 20 7.589 20 12C20 16.411 16.411 20 12 20ZM11 7H13V13H7V11H11V7Z" fill="#425BCF"></path>
                            </svg>
                            </figure>
                            <span>Meeting Timer</span>
                          </span>
                        </div>
                    </div>
                </div>
            </div>

<!--            <div class="scalable-solution-row my-4 my-md-5">-->
<!--                <div class="text-center mb-3">-->
<!--                    <h2>Why Video Consultations?</h2>-->
<!--                </div>-->
<!--                <div class="row g-3 justify-content-center">-->
<!--                    <div class="col-lg-11 col-xl-8">-->
<!--                        <div class="row g-3">-->
<!--                            <div class="col-md-6">-->
<!--                                <div class="h-100 card p-2">-->
<!--                                    <figure class="m-0 w-100">-->
<!--                                        <img :src="'/crtxassets/images/video-consultation/video-landing-04.jpg'" alt="" class="w-100 rounded-3 h-auto">-->
<!--                                    </figure>-->
<!--                                    <div class="my-1 p-2 pb-0">-->
<!--                                        <h5 class="mb-1">Reliable and scalable</h5>-->
<!--                                        <p class="mb-0">Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                            <div class="col-md-6">-->
<!--                                <div class="h-100 card p-2">-->
<!--                                    <figure class="m-0 w-100">-->
<!--                                        <img :src="'/crtxassets/images/video-consultation/video-landing-05.jpg'" alt="" class="w-100 rounded-3 h-auto">-->
<!--                                    </figure>-->
<!--                                    <div class="my-1 p-2 pb-0">-->
<!--                                        <h5 class="mb-1">A team that cares</h5>-->
<!--                                        <p class="mb-0">Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
        </div>
<!--        <div v-else>-->
<!--            <whereby-embed :room="wherebyLink" style="height: 700px; width: 100%"></whereby-embed>-->
<!--        </div>-->
    </div>
</template>

<script>
import { useClinicStore } from "../../stores/clinic";
import { useAuthStore } from "../../stores/auth";
import axios from 'axios';
import Swal from "sweetalert2";

export default {
    name: "Video Consultations",
    setup() {
        const clinicStore = useClinicStore();
        const authStore = useAuthStore();

        return { clinicStore, authStore };
    },
    data() {
        return {
            wherebyLink: this.getWherebyLink(),
            isActivated: false,
            activationStatus: '',
            participantLink: '',
            copyMessage: false,
        };
    },
    mounted() {
        this.fetchActivationState();
        this.wherebyLink = this.getWherebyLink();
        const script = document.createElement("script");
        script.src = "https://cdn.srv.whereby.com/embed/v2-embed.js";
        script.type = "module";
        document.head.appendChild(script);
        const wherebyLink = localStorage.getItem("whereby_link");
        if (wherebyLink) {
            this.participantLink = wherebyLink.split("?")[0];
        }
    },
    methods: {
        copyEmbedCode() {
            const embedCode = `<iframe src="${this.participantLink}" allow="camera; microphone; fullscreen; speaker; display-capture; compute-pressure" style="height: 700px; width: 100%"></iframe>`;
            navigator.clipboard.writeText(embedCode).then(() => {
                this.copyMessage = true;
                setTimeout(() => {
                    this.copyMessage = false;
                }, 2000);
            });
        },
        getWherebyLink() {
            const link = localStorage.getItem('whereby_link');
            return link && link.length > 5 ? link : null;
        },
        async activateClinic() {
            try {
                // Get clinic and user details
                const clinicId = localStorage.getItem('clinic_id');
                const clinicName = localStorage.getItem('clinic_name');
                const userData = localStorage.getItem('user');

                const user = userData ? JSON.parse(userData) : null;

                const userId = user ? user.id : null;
                const userName = user ? user.name : null;
                const userEmail = user ? user.email : null;

                // Send activation request to the backend
                const response = await axios.post('/api/v1/activate-video-consultation', {
                    clinic_id: clinicId,
                    clinic_name: clinicName,
                    user_id: userId,
                    user_name: userName,
                    user_email: userEmail,
                });

                this.isActivated = true;
                this.wherebyLink = response.data.hostRoomUrl;
                this.participantLink = this.wherebyLink.split("?")[0];

                localStorage.setItem("whereby_link", response.data.hostRoomUrl);
                if (response.data.success) {
                    // Update the state instead of reloading the page
                    this.wherebyLink = response.data.wherebyLink;
                    Swal.fire({
                        title: 'Success!',
                        text: 'Video consultation activated successfully!',
                        icon: 'success',
                        confirmButtonText: 'OK',
                    });
                }
            } catch (error) {
                console.error("Error activating clinic:", error);
            }
        },
        async fetchActivationState() {
            try {
                const clinicId = localStorage.getItem('clinic_id');
                const response = await axios.get(`/api/v1/clinic/${clinicId}/activation`);

                this.activationStatus = response.data.whereby_activation_status;

                // const link = response.data.whereby_link;
                //
                // if (link) {
                //     this.wherebyLink = link;
                //     localStorage.setItem("whereby_link", link);
                // }

                if (response.data.whereby_activation_status === 'pending') {
                    this.isActivated = true;
                } else if (response.data.whereby_activation_status === 'activated') {
                    this.isActivated = true;
                    this.wherebyLink = response.data.whereby_link;
                }
            } catch (error) {
                console.error("Error fetching activation state:", error);
            }
        },
    }
};
</script>

<style scoped>
.container {
    padding-top: 50px;
    width: 100%;
    height: 700px;
}
.bg-primary-light { background: rgba(53, 90, 221,0.15); }
.scalable-solution-row h5 { font-size: 1.2rem; font-weight: 700; }
.features-label { background: #fff; display: inline-flex; align-items: center; padding: 0.8rem 1.7rem; color: #425BCF; font-weight: 500; border-radius: 100px; }
.features-label figure { max-width: 24px; min-width: 24px; margin: 0 0.5rem 0 0; }
.features-label figure svg { width: 100%; height: auto; }
.features-btn-group { display: flex; justify-content: center; flex-direction: row; flex-wrap: wrap; gap: 1rem; }
.embed-container {
    background-color: #1d164b;
    padding: 15px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    position: relative;
}

.embed-code-wrapper {
    position: relative;
    width: 100%;
}

.embed-code {
    color: #fff;
    font-family: "Courier New", Courier, monospace;
    font-size: 14px;
    white-space: pre-wrap;
    word-wrap: break-word;
    background-color: #1d164b;
    border: none;
    outline: none;
    width: 100%;
    margin: 0;
    padding: 10px;
    border-radius: 8px;
    overflow-x: auto;
}

.copy-button {
    position: absolute;
    top: 10px;
    right: 10px;
    background: transparent;
    border: none;
    color: #fff;
    cursor: pointer;
    font-size: 18px;
    outline: none;
}

.copy-button:hover {
    color: #ddd;
}
.copy-message {
    color: #00ff00;
    font-size: 14px;
    font-weight: bold;
}
</style>

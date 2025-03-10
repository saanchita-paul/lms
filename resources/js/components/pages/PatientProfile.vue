<template>
    <div id="main" class="bg-light-gray patient-profile">
        <div class="container-fluid p-0">
            <div class="px-0 py-0 mt-4">
                <div class="patient-profile-main-box">
                    <div class="tab-main-box" :class="!profile_editing ? 'tab-disabled-active' : 'tab-edit-active'">
                        <form @submit.prevent="updateProfile" id="updateProfileForm">
                            <div class="patient-profile-top-box">
                                <h2 class="mb-3">Patient Profile</h2>
                                <div class="d-flex align-items-center mb-3">
                                    <div class="back-page-btn me-auto">
                                        <router-link to="/crtx/leads">
                                            <svg width="20" height="14" viewBox="0 0 20 14" fill="none"
                                                 xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                      d="M19.4411 6.37503H2.04859L7.25172 1.19191C7.49609 0.947534 7.49609 0.551909 7.25172 0.308159C7.00734 0.0637842 6.61172 0.0637842 6.36797 0.308159L0.180469 6.49503C-0.0601562 6.73566 -0.0601562 7.13816 0.180469 7.37878L6.36797 13.5663C6.61234 13.8107 7.00797 13.8107 7.25172 13.5663C7.49609 13.3219 7.49609 12.9263 7.25172 12.6825L2.04859 7.62503H19.4411C19.7861 7.62503 20.0661 7.34503 20.0661 7.00003C20.0661 6.65503 19.7861 6.37503 19.4411 6.37503Z"
                                                      fill="#514F5F"/>
                                            </svg>
                                            <span>Back to Leads</span>
                                        </router-link>
                                    </div>
                                    <div class="edit-option d-flex">
                                        <a href="#" class="edit-input manage-edit" v-show="!profile_editing"
                                           @click="profile_editing=true">
                                            <svg width="18" height="18" viewBox="0 0 18 18"
                                                 xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M16 17.25C16 16.848 15.644 16.5 15.25 16.5C12.689 16.5 3.311 16.5 0.75 16.5C0.356 16.5 0 16.848 0 17.25C0 17.652 0.356 18 0.75 18H15.25C15.644 18 16 17.652 16 17.25ZM8.597 13.852L17.721 4.727C17.892 4.556 18 4.304 18 4.043C18 3.814 17.917 3.577 17.72 3.381L14.605 0.277C14.42 0.0919999 14.176 0 13.933 0C13.69 0 13.447 0.0919999 13.261 0.277L4.118 9.38C3.549 11.143 2.563 14.203 2.492 14.461C2.472 14.536 2.463 14.611 2.463 14.685C2.463 15.146 2.812 15.533 3.228 15.533C3.739 15.533 4.219 15.344 8.597 13.852ZM5.327 10.51L7.464 12.647L4.296 13.693L5.327 10.51ZM6.282 9.344L13.933 1.728L16.268 4.055L8.631 11.693L6.282 9.344Z"></path>
                                            </svg>
                                            Edit<span class="ps-1">Profile</span>
                                        </a>
                                        <a href="#" class="save-input manage-cancel" v-show="profile_editing"
                                           @click="profile_editing=false">Cancel</a>
                                        <button type="button" class="save-input manage-save" v-show="profile_editing"
                                                @click="updateProfile">Save
                                        </button>
                                        <button type="button" data-title="Delete Lead" data-bs-toggle="modal"
                                                data-bs-target="#DeleteLeadModal" class="delete-lead tooltip-ico">
                                            <svg fill="#6c757d" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="18px" height="18px" viewBox="0 0 485 485" xml:space="preserve" stroke="#FFFFFF"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g
                                                id="SVGRepo_tracerCarrier" stroke-linecap="round"
                                                stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g> <g> <rect
                                                x="67.224" width="350.535" height="71.81"></rect>
                                                <path
                                                    d="M417.776,92.829H67.237V485h350.537V92.829H417.776z M165.402,431.447h-28.362V146.383h28.362V431.447z M256.689,431.447 h-28.363V146.383h28.363V431.447z M347.97,431.447h-28.361V146.383h28.361V431.447z"></path> </g> </g> </g></svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="row g-4">
<!--                                <div v-if="showLostReasonError" class="alert alert-danger alert-dismissible fade show"-->
<!--                                     role="alert">-->
<!--                                    Please select lost reason!-->
<!--                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"-->
<!--                                            @click="showLostReasonError = false"></button>-->
<!--                                </div>-->
                                <div class="col-lg-8 col-xl-4 mx-auto">
                                    <div class="patient-profile-left profile-card-box">
                                        <div class="patient-profile-name" v-if="patientStore.patient.first_name">
                                            <h6>
                                                {{ patientStore.patient.first_name + ' ' + (patientStore.patient.last_name != null ? patientStore.patient.last_name : '') }}</h6>
                                            <div class="ms-auto new-note-btn-box my-3">
                                                <a role="button"
                                                   class="btn btn-primary add-note-btn px-3 mx-xxl-5 align-items-center"
                                                   data-title="View Profile Details" data-bs-toggle="modal"
                                                   data-bs-target="#LeadFullDetailsModal">
                                                    <span class="text-center">View Profile Details</span>
                                                </a>
                                            </div>
                                            <h3 v-if="patientStore.patient.lead_score">
                                                {{ patientStore.patient.lead_score }}</h3>
                                        </div>
                                        <div class="progress-box text-center" v-if="patientStore.patient.lead_score">
                                            <div class="progress">
                                                <div class="rangebar left">0</div>
                                                <div
                                                    :class="'progress-amount ' + patientStore.scoreStatus.toLowerCase()"
                                                    :style="'width: '+patientStore.patient.lead_score/10+'%;'">
													<span>
														<svg width="32" height="32" viewBox="0 0 32 32"
                                                             xmlns="http://www.w3.org/2000/svg" :fill="getFillColor">
															<path
                                                                d="M28.262 12.2664C28.262 5.49244 22.771 0.00244141 15.9975 0.00244141C9.31951 0.00244141 3.89751 5.34394 3.74701 11.9864C3.74251 11.9844 3.73701 11.9844 3.73301 11.9844C3.73001 12.0444 3.73951 12.1049 3.73801 12.1654C3.73751 12.1989 3.73301 12.2329 3.73301 12.2664C3.73301 12.3884 3.74801 12.5059 3.75151 12.6264C3.88951 20.5954 12.897 29.0044 15.9975 31.9974C24.705 24.9379 27.24 18.3894 27.9725 14.8979C27.999 14.7754 28.0225 14.6534 28.045 14.5309C28.1155 14.1549 28.1645 13.8179 28.1985 13.5289C28.1985 13.5254 28.1995 13.5219 28.1995 13.5184C28.291 12.7114 28.262 12.2664 28.262 12.2664Z"/>
														</svg>
													</span>
                                                </div>
                                                <div class="rangebar right">1000</div>
                                            </div>
                                            <div class="progress-label mb-3"
                                                 :class="patientStore.scoreStatus.toLowerCase()">
                                                <span>{{ patientStore.scoreStatus }}</span>
                                            </div>
                                            <a target="_blank" :href="patientStore.patient.score_url"
                                               class="patient-profile-link">
                                                <svg height="48" viewBox="0 0 48 48" width="48"
                                                     xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M0 0h48v48h-48z" fill="none"/>
                                                    <path
                                                        d="M22 34h4v-12h-4v12zm2-30c-11.05 0-20 8.95-20 20s8.95 20 20 20 20-8.95 20-20-8.95-20-20-20zm0 36c-8.82 0-16-7.18-16-16s7.18-16 16-16 16 7.18 16 16-7.18 16-16 16zm-2-22h4v-4h-4v4z"/>
                                                </svg>
                                                Tell me about this score!
                                                <!--                                                <svg version="1.1" x="0px" y="0px" viewBox="0 0 20 20">
                                                                                                    <g>
                                                                                                        <path class="st0" d="M0.6,9.4H18l-5.2-5.2c-0.2-0.2-0.2-0.6,0-0.9c0.2-0.2,0.6-0.2,0.9,0l6.2,6.2c0.2,0.2,0.2,0.6,0,0.9l-6.2,6.2c-0.2,0.2-0.6,0.2-0.9,0c-0.2-0.2-0.2-0.6,0-0.9l5.2-5.1H0.6c-0.3,0-0.6-0.3-0.6-0.6C0,9.7,0.2,9.4,0.6,9.4z"/>
                                                                                                    </g>
                                                                                                </svg>-->
                                            </a>
                                        </div>

                                        <div class="created-time">
                                            <p>Created at: {{
                                                    new Date(patientStore.patient.created_at).toLocaleDateString('en-US', {
                                                        month: 'short',
                                                        day: '2-digit',
                                                        year: 'numeric'
                                                    }).replace(/(\d)(st|nd|rd|th)/, '$1, $2')
                                                }} </p>
                                        </div>

                                        <div class="patient-form-box">
                                            <div class="form-group">
                                                <label>First Name</label>
                                                <input type="text" class="form-control"
                                                       v-model="patientStore.patient.first_name">
                                            </div>
                                            <div class="form-group">
                                                <label>Last Name</label>
                                                <input type="text" class="form-control"
                                                       v-model="patientStore.patient.last_name">
                                            </div>
                                            <div class="form-group">
                                                <label
                                                    v-if="authStore.clinic_id == patientStore.patient.microsite_health_clinic_id">Practice
                                                    Name</label>
                                                <label v-else>Quick Note</label>
                                                <input type="text" class="form-control"
                                                       v-model="patientStore.patient.badge">
                                            </div>
                                            <div class="form-group verify-group">
												<span class="verified"
                                                      :class="patientStore.patient.phone_verified? '' : 'not-verified'"
                                                      @click="patientStore.patient.phone_verified = !patientStore.patient.phone_verified">
													Verified?
													<span class="verified-check">
														<span class="verified-check-ico">
															<svg version="1.1" x="0px" y="0px" viewBox="0 0 10 10"
                                                                 style="enable-background:new 0 0 10 10;">
																<path
                                                                    d="M3.3,8.6C3.2,8.6,3,8.6,2.9,8.4L0.2,5.7c-0.2-0.2-0.2-0.6,0-0.8s0.6-0.2,0.8,0l2.4,2.4l5.7-5.7c0.2-0.2,0.6-0.2,0.8,0c0.2,0.2,0.2,0.6,0,0.8L3.7,8.4C3.6,8.6,3.5,8.6,3.3,8.6z"/>
															</svg>
														</span>
													</span>
												</span>
                                                <label>Phone Number</label>
                                                <a v-bind:href="'tel:' + patientStore.patient.phone" class="profile-number-link">{{patientStore.patient.phone}}</a>
                                                <input type="text" id="phone" class="profile-number form-control"
                                                       v-model="patientStore.patient.phone">
                                            </div>
                                            <div class="form-group verify-group">
												<span class="verified"
                                                      :class="patientStore.patient.email_verified? '' : 'not-verified'"
                                                      @click="patientStore.patient.email_verified = !patientStore.patient.email_verified">
													Verified?
													<span class="verified-check">
														<span class="verified-check-ico">
															<svg version="1.1" x="0px" y="0px" viewBox="0 0 10 10"
                                                                 style="enable-background:new 0 0 10 10;">
																<path
                                                                    d="M3.3,8.6C3.2,8.6,3,8.6,2.9,8.4L0.2,5.7c-0.2-0.2-0.2-0.6,0-0.8s0.6-0.2,0.8,0l2.4,2.4l5.7-5.7c0.2-0.2,0.6-0.2,0.8,0c0.2,0.2,0.2,0.6,0,0.8L3.7,8.4C3.6,8.6,3.5,8.6,3.3,8.6z"/>
															</svg>
														</span>
													</span>
												</span>
                                                <label>Email Address</label>
                                                <input type="text" class="form-control"
                                                       v-model="patientStore.patient.email">
                                            </div>
                                            <div class="other-show-box">
                                                <div class="other-show-data" v-show="showMoreProfile">
                                                    <div class="form-group">
                                                        <label>Date of Birth</label>
                                                        <div class="datepicker-box">
                                                            <input type="text" class="datepicker form-control" id="dob"
                                                                   placeholder="MM/DD/YYYY"
                                                                   v-model="patientStore.patient.dob">
                                                            <span class="date-ico">
																<svg version="1.1" x="0px" y="0px" viewBox="0 0 24 24"
                                                                     style="enable-background:new 0 0 24 24;">
																	<path
                                                                        d="M19,3h-2V2c0-0.6-0.4-1-1-1s-1,0.4-1,1v1H9V2c0-0.6-0.4-1-1-1S7,1.4,7,2v1H5C3.3,3,2,4.3,2,6v14c0,1.7,1.3,3,3,3h14c1.7,0,3-1.3,3-3V6C22,4.3,20.7,3,19,3z M5,5h2v1c0,0.6,0.4,1,1,1s1-0.4,1-1V5h6v1c0,0.6,0.4,1,1,1s1-0.4,1-1V5h2c0.6,0,1,0.4,1,1v3H4V6C4,5.4,4.4,5,5,5z M19,21H5c-0.6,0-1-0.4-1-1v-9h16v9C20,20.6,19.6,21,19,21z"/>
																</svg>
															</span>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-6">
                                                            <div class="form-group">
                                                                <label>City</label>
                                                                <input type="text" class="form-control"
                                                                       v-model="patientStore.patient.city">
                                                            </div>
                                                        </div>
                                                        <div class="col-6">
                                                            <div class="form-group">
                                                                <label>State</label>
                                                                <select class="form-control form-select"
                                                                        v-model="patientStore.patient.state"
                                                                        :class="profile_editing? '' : 'hide-arrow'">
                                                                    <option disabled readonly></option>
                                                                    <option value="Alabama">Alabama</option>
                                                                    <option value="Alaska">Alaska</option>
                                                                    <option value="Arizona">Arizona</option>
                                                                    <option value="Arkansas">Arkansas</option>
                                                                    <option value="California">California</option>
                                                                    <option value="Colorado">Colorado</option>
                                                                    <option value="Connecticut">Connecticut</option>
                                                                    <option value="Delaware">Delaware</option>
                                                                    <option value="District Of Columbia">District Of
                                                                        Columbia
                                                                    </option>
                                                                    <option value="Florida">Florida</option>
                                                                    <option value="Georgia">Georgia</option>
                                                                    <option value="Hawaii">Hawaii</option>
                                                                    <option value="Idaho">Idaho</option>
                                                                    <option value="Illinois">Illinois</option>
                                                                    <option value="Indiana">Indiana</option>
                                                                    <option value="Iowa">Iowa</option>
                                                                    <option value="Kansas">Kansas</option>
                                                                    <option value="Kentucky">Kentucky</option>
                                                                    <option value="Louisiana">Louisiana</option>
                                                                    <option value="Maine">Maine</option>
                                                                    <option value="Maryland">Maryland</option>
                                                                    <option value="Massachusetts">Massachusetts</option>
                                                                    <option value="Michigan">Michigan</option>
                                                                    <option value="Minnesota">Minnesota</option>
                                                                    <option value="Mississippi">Mississippi</option>
                                                                    <option value="Missouri">Missouri</option>
                                                                    <option value="Montana">Montana</option>
                                                                    <option value="Nebraska">Nebraska</option>
                                                                    <option value="Nevada">Nevada</option>
                                                                    <option value="New Hampshire">New Hampshire</option>
                                                                    <option value="New Jersey">New Jersey</option>
                                                                    <option value="New Mexico">New Mexico</option>
                                                                    <option value="New York">New York</option>
                                                                    <option value="North Carolina">North Carolina
                                                                    </option>
                                                                    <option value="North Dakota">North Dakota</option>
                                                                    <option value="Ohio">Ohio</option>
                                                                    <option value="Oklahoma">Oklahoma</option>
                                                                    <option value="Oregon">Oregon</option>
                                                                    <option value="Pennsylvania">Pennsylvania</option>
                                                                    <option value="Rhode Island">Rhode Island</option>
                                                                    <option value="South Carolina">South Carolina
                                                                    </option>
                                                                    <option value="South Dakota">South Dakota</option>
                                                                    <option value="Tennessee">Tennessee</option>
                                                                    <option value="Texas">Texas</option>
                                                                    <option value="Utah">Utah</option>
                                                                    <option value="Vermont">Vermont</option>
                                                                    <option value="Virginia">Virginia</option>
                                                                    <option value="Washington">Washington</option>
                                                                    <option value="West Virginia">West Virginia</option>
                                                                    <option value="Wisconsin">Wisconsin</option>
                                                                    <option value="Wyoming">Wyoming</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Lost Reason</label>
                                                        <select id="lost-reason" class="form-control form-select"
                                                                v-model="patientStore.patient.reason"
                                                                :class="profile_editing? '' : 'hide-arrow'">
                                                            <option value="" selected disabled>Please Select</option>
                                                            <option value="Price shopping">Pricing Shopping</option>
                                                            <option value="Office is too far">Too Far</option>
                                                            <option value="Medicaid or Medicare patient">Medicare /
                                                                Medicaid
                                                            </option>
                                                            <option value="Too expensive/couldn't afford it">Too
                                                                Expensive
                                                            </option>
                                                            <option value="Call disconnected, hung up">Spam / Wrong
                                                                Number / Hung Up
                                                            </option>
                                                            <option value="Current Patient">Current Patient</option>
                                                            <option value="Spanish Speaking">Spanish Speaking</option>
                                                            <option value="Insurance / Senior Grants">Insurance / Senior
                                                                Grants
                                                            </option>
                                                            <option value="No Credit Card">No Credit Card</option>
                                                            <option value="Duplicate Lead" selected="">Duplicate Lead
                                                            </option>
                                                            <option value="STOP">STOP</option>
                                                            <option value="General Dentistry">General Dentistry</option>
                                                            <option value="Does not provide service">Does not provide
                                                                service
                                                            </option>
                                                            <option value="No Reason">No Reason</option>
                                                            <option value="Think about it">Think about it</option>
                                                            <option value="Stated Not Interested">Stated Not
                                                                Interested
                                                            </option>
                                                            <option value="Credit Challenged">Credit Challenged</option>
                                                            <option value="Refuse to give CC">Refuse to give CC</option>
                                                            <option value="No cosigner">No cosigner</option>
                                                            <option value="Veneers/Braces/Cosmetic">
                                                                Veneers/Braces/Cosmetic
                                                            </option>
                                                            <option value="Language Barrier">Language Barrier</option>
                                                            <option value="Spam">Spam</option>
                                                            <option value="Wrong Number">Wrong Number</option>
                                                            <option value="Hung Up">Hung Up</option>
                                                            <option value="No Way to Contact">No Way to Contact</option>
                                                            <option value="Fax Number">Fax Number</option>
                                                            <option value="Vendor">Vendor</option>
                                                            <option value="Another Dentist Office">Another Dentist
                                                                Office
                                                            </option>
                                                            <option value="Robo Call">Robo Call</option>
                                                            <option value="False Advertising">False Advertising</option>
                                                            <option value="Senior Grants">Senior Grants</option>
                                                            <option value="Insurance">Insurance</option>
                                                            <option value="No Appointment Available">No Appointment
                                                                Available
                                                            </option>
                                                            <option value="Other">Other</option>
                                                        </select>
<!--                                                        <span-->
<!--                                                            class="d-block text-danger mt-1"-->
<!--                                                            v-if="showLostReasonError"-->
<!--                                                        >Please select Lost Reason!</span>-->
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Consultation Book Date</label>
                                                        <div class="datepicker-box">
                                                            <input type="text" id="bookDate" class="form-control"
                                                                   v-model="patientStore.patient.consultation_booked_date">
                                                            <span class="date-ico">
																<svg version="1.1" x="0px" y="0px" viewBox="0 0 24 24"
                                                                     style="enable-background:new 0 0 24 24;">
																	<path
                                                                        d="M19,3h-2V2c0-0.6-0.4-1-1-1s-1,0.4-1,1v1H9V2c0-0.6-0.4-1-1-1S7,1.4,7,2v1H5C3.3,3,2,4.3,2,6v14c0,1.7,1.3,3,3,3h14c1.7,0,3-1.3,3-3V6C22,4.3,20.7,3,19,3z M5,5h2v1c0,0.6,0.4,1,1,1s1-0.4,1-1V5h6v1c0,0.6,0.4,1,1,1s1-0.4,1-1V5h2c0.6,0,1,0.4,1,1v3H4V6C4,5.4,4.4,5,5,5z M19,21H5c-0.6,0-1-0.4-1-1v-9h16v9C20,20.6,19.6,21,19,21z"/>
																</svg>
															</span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group"
                                                         v-if="patientStore.patient.consultation_booked_date!=null">
                                                        <label>Consultation Follow Up</label>
                                                        <select class="form-control form-select"
                                                                v-model="patientStore.patient.deal_status"
                                                                :class="profile_editing? '' : 'hide-arrow'">
                                                            <option value="" selected disabled>Please Select</option>
                                                            <option value="Needs Financing or Gathering Money">Needs
                                                                Financing or Gathering Money
                                                            </option>
                                                            <option value="Talk to Spouse, Partner or Family Member">
                                                                Talk to Spouse, Partner or Family Member
                                                            </option>
                                                            <option value="Wants to Compare Prices">Wants to Compare
                                                                Prices
                                                            </option>
                                                            <option value="Other">Other</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Treatments Sold?</label>
                                                        <select class="form-control form-select" id="treatment_sold"
                                                                v-model="patientStore.patient.won_lost"
                                                                :class="profile_editing? '' : 'hide-arrow'"
                                                                @change="treatmentSoldChanged()">
                                                            <option value="" selected disabled>Please Select</option>
                                                            <option :selected="patientStore.patient.won_lost == 'Won'"
                                                                    value="Won">Yes
                                                            </option>
                                                            <option value="Lost"
                                                                    :selected="patientStore.patient.won_lost == 'Lost'">
                                                                Declined
                                                            </option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group"
                                                         v-show="patientStore.patient.won_lost=='Won'">
                                                        <label>Sold on Date</label>
                                                        <div class="datepicker-box">
                                                            <input type="text" class="datepicker form-control"
                                                                   id="treatment_sold_date" placeholder="MM/DD/YYYY"
                                                                   v-model="patientStore.patient.won_lost_date" readonly
                                                                   aria-readonly="true">
                                                            <span class="date-ico">
																<svg version="1.1" x="0px" y="0px" viewBox="0 0 24 24"
                                                                     style="enable-background:new 0 0 24 24;">
																	<path
                                                                        d="M19,3h-2V2c0-0.6-0.4-1-1-1s-1,0.4-1,1v1H9V2c0-0.6-0.4-1-1-1S7,1.4,7,2v1H5C3.3,3,2,4.3,2,6v14c0,1.7,1.3,3,3,3h14c1.7,0,3-1.3,3-3V6C22,4.3,20.7,3,19,3z M5,5h2v1c0,0.6,0.4,1,1,1s1-0.4,1-1V5h6v1c0,0.6,0.4,1,1,1s1-0.4,1-1V5h2c0.6,0,1,0.4,1,1v3H4V6C4,5.4,4.4,5,5,5z M19,21H5c-0.6,0-1-0.4-1-1v-9h16v9C20,20.6,19.6,21,19,21z"/>
																</svg>
															</span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Treatment Cost</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"
                                                                  v-show="patientStore.patient.value">$</span>
                                                            <input type="text" class="form-control"
                                                                   v-model="patientStore.patient.value">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Verbal Confirmation</label>
                                                        <div class="check-value">{{
                                                                patientStore.patient.verbal_confirmation == 'yes' ? 'Yes' : 'No'
                                                            }}
                                                        </div>
                                                        <div class="patient-check-box">
                                                            <p class="mb-1">The Doctor is setting aside time for you for
                                                                this appointment, is there any reason that you wouldn’t
                                                                be able to make it?</p>
                                                            <div class="d-flex align-items-center">
                                                                <div class="form-check me-3">
                                                                    <input class="form-check-input" type="radio"
                                                                           name="VerbalConfirmation" id="Yes1"
                                                                           value="yes"
                                                                           v-model="patientStore.patient.verbal_confirmation">
                                                                    <label class="form-check-label" for="Yes1">
                                                                        Yes
                                                                    </label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio"
                                                                           name="VerbalConfirmation" id="No1" value="no"
                                                                           v-model="patientStore.patient.verbal_confirmation">
                                                                    <label class="form-check-label" for="No1">
                                                                        No
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Informed of Consult Fee</label>
                                                        <div class="check-value">{{
                                                                patientStore.patient.informed_consult_fee == 'yes' ? 'Yes' : 'No'
                                                            }}
                                                        </div>
                                                        <div class="patient-check-box">
                                                            <p class="mb-1">Has the patient been informed of the
                                                                consultation fee if there is one?</p>
                                                            <div class="d-flex align-items-center">
                                                                <div class="form-check me-3">
                                                                    <input class="form-check-input" type="radio"
                                                                           name="ConsultFee" id="Yes2" value="yes"
                                                                           v-model="patientStore.patient.informed_consult_fee">
                                                                    <label class="form-check-label" for="Yes2">
                                                                        Yes
                                                                    </label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio"
                                                                           name="ConsultFee" id="No2" value="no"
                                                                           v-model="patientStore.patient.informed_consult_fee">
                                                                    <label class="form-check-label" for="No2">
                                                                        No
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Description</label>
                                                        <textarea class="form-control"
                                                                  v-model="patientStore.patient.description"></textarea>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Budget</label>
                                                        <textarea class="form-control"
                                                                  v-model="patientStore.patient.budget"></textarea>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Credit Score Above 650?</label>
                                                        <div class="check-value">
                                                            {{ patientStore.patient.credit_score_above_650 != null ? patientStore.patient.credit_score_above_650 == 1 ? 'Yes' : 'No' : 'Not Available' }}
                                                        </div>
                                                        <div class="patient-check-box">
                                                            <p class="mb-1">Does the patient have credit score above
                                                                650?</p>
                                                            <div class="d-flex align-items-center">
                                                                <div class="form-check me-3">
                                                                    <input class="form-check-input" type="radio"
                                                                           id="creditScoreYes" value="1"
                                                                           v-model="patientStore.patient.credit_score_above_650">
                                                                    <label class="form-check-label"
                                                                           for="creditScoreYes">
                                                                        Yes
                                                                    </label>
                                                                </div>
                                                                <div class="form-check me-3">
                                                                    <input class="form-check-input" type="radio"
                                                                           id="creditScoreNo" value="0"
                                                                           v-model="patientStore.patient.credit_score_above_650">
                                                                    <label class="form-check-label" for="creditScoreNo">
                                                                        No
                                                                    </label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio"
                                                                           id="creditScoreNotAvailable" :value="null"
                                                                           v-model="patientStore.patient.credit_score_above_650">
                                                                    <label class="form-check-label"
                                                                           for="creditScoreNotAvailable">
                                                                        Not Available
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <span class="show-more-link mt-2" @click="showMoreProfile=!showMoreProfile">
												<svg width="15" height="9" viewBox="0 0 15 9" v-show="showMoreProfile"
                                                     style="transform: rotate(180deg);" fill="none"
                                                     xmlns="http://www.w3.org/2000/svg">
													<path fill-rule="evenodd" clip-rule="evenodd"
                                                          d="M6.93766 6.16894L1.75453 0.965811C1.51016 0.721436 1.11453 0.721436 0.870781 0.965811C0.626406 1.21019 0.626406 1.60581 0.870781 1.84956L7.05766 8.03706C7.29828 8.27769 7.70078 8.27769 7.94141 8.03706L14.1289 1.84956C14.3733 1.60519 14.3733 1.20956 14.1289 0.965811C13.8845 0.721436 13.4889 0.721436 13.2452 0.965811L8.18766 6.16894L7.56265 6.71753L6.93766 6.16894Z"/>
												</svg>
												{{ !showMoreProfile ? 'Show More' : 'Show Less' }}
												<svg width="15" height="9" viewBox="0 0 15 9" v-show="!showMoreProfile"
                                                     fill="none" xmlns="http://www.w3.org/2000/svg">
													<path fill-rule="evenodd" clip-rule="evenodd"
                                                          d="M6.93766 6.16894L1.75453 0.965811C1.51016 0.721436 1.11453 0.721436 0.870781 0.965811C0.626406 1.21019 0.626406 1.60581 0.870781 1.84956L7.05766 8.03706C7.29828 8.27769 7.70078 8.27769 7.94141 8.03706L14.1289 1.84956C14.3733 1.60519 14.3733 1.20956 14.1289 0.965811C13.8845 0.721436 13.4889 0.721436 13.2452 0.965811L8.18766 6.16894L7.56265 6.71753L6.93766 6.16894Z"/>
												</svg>
											</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-8">
                                    <div class="bonsultation-btn-box d-lg-none mb-4">
                                        <div class="row">
                                            <!-- <div class="col-6">
                                                <a class="w-100 text-center btn btn-white">Send Email</a>
                                            </div> -->
                                            <div class="col-4">
                                                <router-link
                                                    :to="'/crtx/inbox/'+patientStore.patient.id+'?rightView=chat&page=sms'"
                                                    class="w-100 text-center btn btn-primary">Send Text
                                                </router-link>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="profile-card-box patient-profile-right-box">
                                        <div class="patient-right-top">
                                            <div class="row">
                                                <div class="col-lg-4 col-md-12">
                                                    <div class="form-group patient-stage-select mb-lg-0">
                                                        <label>Stage</label>
                                                       
                                                        <select class="form-control form-select"
                                                                v-model="patientStore.patient.status_id" @change="stageChanged">
                                                            <option selected disabled value="null">Select Stage</option>
                                                            <option
                                                                v-if="patientStore.patient.status_id"
                                                                value="1">New Lead
                                                            </option>
                                                            <option
                                                                v-if="patientStore.patient.status_id"
                                                                value="5">In Discussion
                                                            </option>
                                                            <option
                                                                v-if="!patientStore.patient.consultation_booked_date"
                                                                value="2">Attempt 1
                                                            </option>
                                                            <option
                                                                v-if="!patientStore.patient.consultation_booked_date"
                                                                value="3">Attempt 2
                                                            </option>
                                                            <option
                                                                v-if="!patientStore.patient.consultation_booked_date"
                                                                value="4">Attempt 3 Plus
                                                            </option>
                                                            <option
                                                                v-if="patientStore.patient.status_id"
                                                                value="17">Nurturing
                                                            </option>
                                                            <option
                                                                v-if="patientStore.patient.status_id"
                                                                value="6">Practice Follow-Up
                                                            </option>
                                                            <option
                                                                v-if="!patientStore.patient.consultation_booked_date"
                                                                value="9">Not Interested
                                                            </option>
                                                            <option
                                                                v-if="!patientStore.patient.consultation_booked_date"
                                                                value="16">Existing Patient
                                                            </option>

                                                            <option v-if="patientStore.patient.consultation_booked_date"
                                                                    value="12">Consultation Booked
                                                            </option>
                                                            <option v-if="patientStore.patient.consultation_booked_date"
                                                                    value="13">No Showed
                                                            </option>
                                                            <option v-if="patientStore.patient.consultation_booked_date"
                                                                    value="14">Cancellation
                                                            </option>
                                                            <option v-if="patientStore.patient.consultation_booked_date"
                                                                    value="15">Pending Acceptance
                                                            </option>
                                                            <option v-if="patientStore.patient.consultation_booked_date"
                                                                    value="">Treatment Sold
                                                            </option>
                                                            <option v-if="patientStore.patient.consultation_booked_date"
                                                                    value="18">Treatment Completed
                                                            </option>
                                                            <!-- <option v-if="!patientStore.patient.consultation_booked_date" value="9">Not Interested</option>
                                                            <option v-if="!patientStore.patient.consultation_booked_date" value="16">Existing Patient</option> -->
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3 col-md-4">
                                                    <div class="form-group mb-lg-0">
                                                        <label>Lead Type</label>
                                                        <select class="form-control form-select"
                                                                v-model="patientStore.patient.phone_form">
                                                            <option selected disabled value="null">Select Lead Type
                                                            </option>
                                                            <option value="Web Form">Web Form</option>
                                                            <option value="Phone Call">Phone Call</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3 col-md-4">
                                                    <div class="form-group mb-lg-0">
                                                        <label>Source</label>
                                                        <select class="form-control form-select"
                                                                v-model="patientStore.patient.source_id">
                                                            <option selected disabled value="null">Select Source
                                                            </option>
                                                            <option v-for="source in resourceStore.sources"
                                                                    :value="source.id">{{ source.source_name }}
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <input type="hidden" v-model="patientStore.patient.quicksource">
                                                <div class="col-lg-2 col-md-4">
                                                    <div class="form-group mb-lg-0">
                                                        <label>Attempts</label>
                                                        <input type="text" class="form-control"
                                                               v-model="patientStore.patient.three_plus_attempts">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="bonsultation-btn-box mb-4">
                                        <div class="row">
                                            <div class="col-lg-4 "
                                                 :class="authStore.inbox_id ? 'col-lg-4' : 'col-lg-6'">
                                                <a role="button"
                                                   class="w-100 text-center btn btn-blue d-flex align-items-center justify-content-center"
                                                   @click="hideBookConsultation()">{{
                                                        patientStore.patient.consultation_booked_date ? 'Reschedule' : 'Book'
                                                    }} Consultation</a>
                                            </div>
                                            <div class="d-none d-lg-block"
                                                 :class="authStore.inbox_id ? 'col-lg-4' : 'col-lg-6'">
                                                <router-link
                                                    :to="'/crtx/inbox/'+patientStore.patient.id+'?view=new&page=sms'"
                                                    class="w-100 text-center btn btn-primary d-flex align-items-center justify-content-center">
                                                    Send Text
                                                </router-link>
                                            </div>
                                            <div class="d-none d-lg-block"
                                                 :class="authStore.inbox_id ? 'col-lg-4' : 'col-lg-6'"
                                                 v-if="authStore.inbox_id">
                                                <router-link
                                                    :to="'/crtx/inbox/'+patientStore.patient.id+'?view=new&page=email&email='+patientStore.patient.email"
                                                    class="w-100 text-center btn btn-white d-flex align-items-center justify-content-center">
                                                    Send Email
                                                </router-link>
                                            </div>
                                        </div>
                                        <div class="consultation-container">
                                            <input type="text" id="book-datetimepicker"
                                                   v-model="patientStore.patient.consultation_booked_date"
                                                   style="position:absolute; padding:10px;"
                                                   v-show="showBookConsultation"/>
                                        </div>
                                    </div>
                                    <div v-if="patientStore.callDetails!=null && patientStore.callDetails.length>0 && shouldDisplayTitle()">
                                        <div class="profile-card-box" >
                                            <div class="profile-note-list">
                                                <div class="profile-note-title d-flex align-items-center">
                                                    <h3 class="m-0">Call Details</h3>
                                                </div>
                                                <div v-for="callDetail in patientStore.callDetails">
                                                    <div v-if="callDetail.audio_file_url || callDetail.call_summary || callDetail.callrail_time || (callDetail.hasOwnProperty('conversationalTranscript') && callDetail.conversationalTranscript.length>0)">
                                                        <div class="profile-note" v-if="callDetail.showFullTranscript">
                                                            <div class="profile-note-box my-2">
                                                                <div class="d-flex justify-content-between profile-note-add-top-title mx-4 mt-4" >
                                                                    <span class="badge bg-dark" style="font-size:16px; font-weight:normal;">FULL TRANSCRIPT</span>
                                                                    <button type="button" class="btn-close" @click="callDetail.showFullTranscript = false">
                                                                        <svg width="20" height="14" viewBox="0 0 20 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M19.4411 6.37503H2.04859L7.25172 1.19191C7.49609 0.947534 7.49609 0.551909 7.25172 0.308159C7.00734 0.0637842 6.61172 0.0637842 6.36797 0.308159L0.180469 6.49503C-0.0601562 6.73566 -0.0601562 7.13816 0.180469 7.37878L6.36797 13.5663C6.61234 13.8107 7.00797 13.8107 7.25172 13.5663C7.49609 13.3219 7.49609 12.9263 7.25172 12.6825L2.04859 7.62503H19.4411C19.7861 7.62503 20.0661 7.34503 20.0661 7.00003C20.0661 6.65503 19.7861 6.37503 19.4411 6.37503Z" fill="#514F5F"></path>
                                                                        </svg>
                                                                    </button>
                                                                </div>
                                                                <div class="profile-note-top">
                                                                    <div style="width:100%;" id="editor-container">
                                                                        <!-- <p>{{ patientStore.patient.full_transcript }}</p> -->
                                                                        <div v-if="callDetail.hasOwnProperty('conversationalTranscript') && callDetail.conversationalTranscript.length>0">
                                                                            <table class="table table-bordered">
                                                                                <thead>
                                                                                <tr><th>Speaker</th><th>Phrase</th><th>Start</th></tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                <tr v-for="conversation in callDetail.conversationalTranscript">
                                                                                    <td>{{ conversation.speaker }}</td>
                                                                                    <td>{{ conversation.phrase }}</td>
                                                                                    <td>{{ conversation.start }}</td>
                                                                                </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="profile-note">
                                                            <div class="profile-note-box my-2" >
                                                                <div class="profile-note-add-top-title" style="display:block; margin-left:1.5rem; margin-top:1.5rem; margin-bottom: 1.5rem" v-if="callDetail.audio_file_url || callDetail.recording_redirect">
                                                                    <audio :src="callDetail.audio_file_url" controls></audio>
                                                                    <a :href="callDetail.recording_redirect" target="_blank" title="Download call audio">
                                                                <span class="badge bg-white" style="font-size:16px; font-weight:normal; float: right; padding-right: 4%;">
                                                                  <svg version="1.0" xmlns="http://www.w3.org/2000/svg"
                                                                       style="position: relative;font-weight: normal;width: 30px;height: 30px;float: left;"
                                                                       width="512.000000pt" height="512.000000pt" viewBox="0 0 512.000000 512.000000"
                                                                       preserveAspectRatio="xMidYMid meet">
                                                                        <g transform="translate(0.000000,512.000000) scale(0.100000,-0.100000)" fill="#425BCF" stroke="none"><path d="M880 5026 c-80 -17 -141 -50 -199 -108 -59 -58 -94 -128 -110 -212 -15 -84 -15 -4208 0 -4292 31 -167 156 -292 323 -323 41 -8 434 -11 1260 -11 l1201 0 -80 80 -80 80 -1145 0 c-1131 0 -1146 1 -1190 21 -60 27 -88 54 -116 114 l-24 50 0 2135 0 2135 24 50 c28 60 56 87 116 114 44 20 61 21 915 21 852 0 871 -1 915 -21 65 -29 1649 -1612 1685 -1683 l25 -50 0 -461 0 -460 80 -80 80 -80 0 510 c0 303 -4 536 -10 574 -21 128 3 101 -893 998 -892 893 -877 879 -1001 902 -87 16 -1702 14 -1776 -3z"/><path d="M2656 3041 c-421 -112 -450 -121 -482 -152 -19 -18 -39 -49 -44 -69 -6 -21 -10 -237 -10 -532 0 -274 -2 -498 -4 -498 -2 0 -19 7 -39 15 -65 27 -170 38 -249 25 -279 -43 -396 -283 -217 -444 182 -165 535 -121 642 78 22 41 22 47 27 681 l5 639 65 17 c36 9 128 33 205 54 440 115 554 145 559 145 3 0 6 -218 6 -485 0 -267 -2 -485 -3 -485 -2 0 -30 9 -63 21 -84 30 -217 31 -304 1 -189 -64 -276 -236 -193 -383 130 -232 556 -231 687 1 l31 55 0 660 c0 642 -1 661 -20 693 -24 40 -67 68 -115 76 -26 4 -154 -26 -484 -113z"/><path d="M4049 1910 c-393 -49 -715 -332 -815 -715 -22 -84 -26 -122 -26 -235 0 -113 4 -151 26 -235 89 -340 351 -602 691 -691 122 -32 314 -37 431 -11 359 80 637 347 730 702 22 84 26 122 26 235 0 113 -4 151 -26 235 -92 353 -377 627 -725 700 -89 18 -231 25 -312 15z m211 -125 c52 -27 60 -58 60 -220 l0 -145 111 0 111 0 34 -34 c29 -29 34 -41 34 -83 0 -48 -5 -56 -179 -299 -99 -137 -193 -257 -208 -266 -15 -10 -44 -18 -63 -18 -19 0 -48 8 -63 18 -15 9 -109 129 -207 267 -174 242 -180 251 -180 298 0 42 5 54 34 83 l34 34 111 0 111 0 0 145 c0 161 8 193 58 219 37 21 164 21 202 1z"/></g>
                                                                  </svg>
                                                                </span>
                                                                    </a>
                                                                </div>
                                                                <div class="profile-note-add-top-title" style="display:block; margin-left:1.5rem; margin-top:1.5rem;" v-if="callDetail.call_summary">
                                                                    <span class="badge bg-dark" style="font-size:16px; font-weight:normal;">CALL SUMMARY</span>
                                                                </div>
                                                                <div class="profile-note-top" v-if="callDetail.call_summary">
                                                                    <div style="width:100%;" id="editor-container">
                                                                        <p>{{ callDetail.call_summary }}</p>
                                                                    </div>
                                                                </div>
                                                                <div class="profile-note-bottom" v-if="callDetail.callrail_time || (callDetail.hasOwnProperty('conversationalTranscript') && callDetail.conversationalTranscript.length>0)">
                                                                    <div class="profile-note-bottom-left" v-if="callDetail.callrail_time">
                                                                        <div class="note-post-time">
                                                                            <p>{{ new Date(callDetail.callrail_time).toLocaleTimeString('en-US', {day:"numeric", year:"numeric", month:"short", hour: "2-digit", minute: "2-digit"}) }}</p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="profile-note-bottom-right" v-if="callDetail.hasOwnProperty('conversationalTranscript') && callDetail.conversationalTranscript.length>0">
                                                                        <button type="button" class="btn btn-link" style="border: none; padding:0.6rem 0" @click="callDetail.showFullTranscript = !callDetail.showFullTranscript">
                                                                            {{ callDetail.showFullTranscript? 'Hide' : 'View' }} Full Transcript
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="profile-card-box">
                                        <div class="profile-note-list">
                                            <div class="profile-note-title d-flex align-items-center">
                                                <h3 class="m-0">Tags</h3>
                                                <div class="ms-auto new-note-btn-box">
                                                    <!-- Add Tags button -->
                                                    <a role="button" v-if="!showTagsInput"
                                                       class="btn btn-primary add-note-btn px-3 align-items-center"
                                                       @click="toggleTagsInput">
															<span class="notes-plus-ico d-lg-none">
																<svg version="1.1" x="0px" y="0px" viewBox="0 0 12 12"
                                                                     style="enable-background:new 0 0 12 12;">
																	<polygon
                                                                        points="11.6,4.8 7.2,4.8 7.2,0.4 5.2,0.4 5.2,4.8 0.4,4.8 0.4,6.8 5.2,6.8 5.2,11.6 7.2,11.6 7.2,6.8 11.6,6.8 "/>
																</svg>
															</span>
                                                        <span class="d-none d-lg-block">Add Tags</span>
                                                    </a>
                                                    <!-- Save Tags button -->
                                                    <a role="button" v-if="showTagsInput"
                                                       class="btn btn-primary add-note-btn px-3 align-items-center"
                                                       @click="saveTags(authStore.clinic_id,this.$route.params.id)">
															<span class="notes-plus-ico d-lg-none">
																<svg version="1.1" x="0px" y="0px" viewBox="0 0 12 12"
                                                                     style="enable-background:new 0 0 12 12;">
																	<polygon
                                                                        points="11.6,4.8 7.2,4.8 7.2,0.4 5.2,0.4 5.2,4.8 0.4,4.8 0.4,6.8 5.2,6.8 5.2,11.6 7.2,11.6 7.2,6.8 11.6,6.8 "/>
																</svg>
															</span>
                                                        <span class="d-none d-lg-block">Save Tags</span>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Conditionally render the tags input section -->
                                        <div v-if="showTagsInput" class="tag-input mb-3">

                                            <!-- Assuming you have a component named `vue3-tags-input` for handling tags input -->
                                            <vue3-tags-input
                                                    :tags="tags"
                                                    :duplicate-select-item="true"
                                                    placeholder="Enter new tags separated by Comma or Enter"
                                                    :add-tag-on-keys="[13, 188]"
                                                    @on-tags-changed="handleTagsUpdate"
                                                    @input="handleInput"
                                                    :select-items="selectItems"
                                                    @keydown.native.enter="handleEnterKey"
                                                    @on-focus="handleFocus"
                                                    ref="tagsInput"
                                                    class="form-control"
                                                />
                                            <ul v-if="showAutocompleteList" class="autocomplete-list"
                                                :class="{ open: tagStore.autocompleteTags && tagStore.autocompleteTags.length > 0 }">
                                                <li v-for="suggestion in tagStore.autocompleteTags" :key="suggestion"
                                                    @click="selectAutocompleteSuggestion(suggestion)">
                                                    {{ suggestion }}
                                                </li>
                                            </ul>
                                        </div>

                                        <!-- Render saved tags -->
                                        <div v-if="tagStore.savedTags.length > 0" class="tags-box">
                                            <div class="tag-list d-flex align-items-center flex-wrap">
													<span v-for="(tag, index) in tagStore.savedTags" :key="index">
														{{ tag.tagName }}
                                                        <!-- Assuming tagName is the property that holds the tag name -->
                                                        <!-- <button class="close-ico" @click="deleteTag(tag.id,this.$route.params.id)">&times;</button> -->
														<button class="close-ico"
                                                                @click="showConfirmModal(tag.id, $route.params.id)">&times;</button>
													</span>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="profile-card-box">
                                        <div class="profile-note-list">
                                            <div class="profile-note-title d-flex align-items-center">
                                                <h3 class="m-0">Timeline</h3>
                                                <div class="ms-auto new-note-btn-box">
                                                    <a role="button"
                                                       class="btn btn-primary add-note-btn px-3 align-items-center">
														<span class="notes-plus-ico d-lg-none"
                                                              @click="newNote=!newNote">
															<svg version="1.1" x="0px" y="0px" viewBox="0 0 12 12"
                                                                 style="enable-background:new 0 0 12 12;">
																<polygon
                                                                    points="11.6,4.8 7.2,4.8 7.2,0.4 5.2,0.4 5.2,4.8 0.4,4.8 0.4,6.8 5.2,6.8 5.2,11.6 7.2,11.6 7.2,6.8 11.6,6.8 "/>
															</svg>
														</span>
                                                        <span class="d-none d-lg-block" @click="newNote=!newNote">New Note</span>
                                                    </a>
                                                    <a role="button"
                                                       class="btn btn-primary-outline done-note-btn px-3 align-items-center">
                                                        Done
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="note-add-box" v-if="newNote">
                                                <div class="note-add-top-title">
                                                    <div class="d-flex">
                                                        <a role="button" class="card-mobile-back"
                                                           @click="newNote = false">
                                                            <svg width="20" height="14" viewBox="0 0 20 14" fill="none"
                                                                 xmlns="http://www.w3.org/2000/svg">
                                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                                      d="M19.4411 6.37503H2.04859L7.25172 1.19191C7.49609 0.947534 7.49609 0.551909 7.25172 0.308159C7.00734 0.0637842 6.61172 0.0637842 6.36797 0.308159L0.180469 6.49503C-0.0601562 6.73566 -0.0601562 7.13816 0.180469 7.37878L6.36797 13.5663C6.61234 13.8107 7.00797 13.8107 7.25172 13.5663C7.49609 13.3219 7.49609 12.9263 7.25172 12.6825L2.04859 7.62503H19.4411C19.7861 7.62503 20.0661 7.34503 20.0661 7.00003C20.0661 6.65503 19.7861 6.37503 19.4411 6.37503Z"
                                                                      fill="#514F5F"></path>
                                                            </svg>
                                                        </a>
                                                        <a role="button" class="edit-note-save ms-auto"
                                                           @click="saveNote">
                                                            Save
                                                        </a>
                                                    </div>

                                                    <h3>New Note</h3>

                                                </div>
                                                <div class="note-add">
                                                    <!-- <textarea class="form-control" v-model="patientStore.newNote"></textarea> -->
                                                    <ckeditor :editor="editor" v-model="patientStore.newNote"
                                                              :config="editorConfig"></ckeditor>
                                                    <div class="note-save-btn text-end p-3">
                                                        <button type="button" class="btn btn-primary px-3"
                                                                @click="saveNote">Save
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="profile-note"
                                                 v-if="patientStore.patient.Notes && patientStore.patient.Notes.length>0">
                                                <div v-for="note in patientStore.patient.Notes.slice(0, 5)"
                                                     :key="note.id">
                                                    <div class="profile-note-box my-2"
                                                         :class="editNoteId == note.id ? 'note-editable' : ''">
                                                        <div class="profile-note-add-top-title">
                                                            <div class="d-flex">
                                                                <!--																<div class="note-save text-end" v-show="editNoteId==note.id">
                                                                                                                                    <button type="button" class="btn btn-primary-rounded px-4" @click="editNoteId=0">Cancel</button>
                                                                                                                                </div>-->
                                                                <a role="button" class="edit-mobile-back tooltip-ico"
                                                                   @click="editNoteId=0" data-title="Edit">
                                                                    <svg width="20" height="14" viewBox="0 0 20 14"
                                                                         fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                                                              d="M19.4411 6.37503H2.04859L7.25172 1.19191C7.49609 0.947534 7.49609 0.551909 7.25172 0.308159C7.00734 0.0637842 6.61172 0.0637842 6.36797 0.308159L0.180469 6.49503C-0.0601562 6.73566 -0.0601562 7.13816 0.180469 7.37878L6.36797 13.5663C6.61234 13.8107 7.00797 13.8107 7.25172 13.5663C7.49609 13.3219 7.49609 12.9263 7.25172 12.6825L2.04859 7.62503H19.4411C19.7861 7.62503 20.0661 7.34503 20.0661 7.00003C20.0661 6.65503 19.7861 6.37503 19.4411 6.37503Z"
                                                                              fill="#514F5F"></path>
                                                                    </svg>
                                                                </a>
                                                                <a role="button" class="edit-note-save ms-auto"
                                                                   @click="updateNote(note)">
                                                                    Save
                                                                </a>
                                                            </div>
                                                            <h3>Edit Note</h3>
                                                        </div>
                                                        <div class="profile-note-top">
                                                            <div class="note-date">
                                                                <span>{{
                                                                        new Date(note.created_at).toLocaleDateString('en-GB', {day: "numeric"})
                                                                    }}</span>
                                                                {{
                                                                    new Date(note.created_at).toLocaleDateString('en-GB', {
                                                                        year: "numeric",
                                                                        month: "short"
                                                                    })
                                                                }}
                                                            </div>
                                                            <div style="width:100%;" id="editor-container"
                                                                 v-if="editNoteId==note.id">
                                                                <ckeditor :editor="editor" v-model="note.note"
                                                                          :config="editorConfig"
                                                                          :isReadonly="editNoteId == note.id"></ckeditor>
                                                            </div>

                                                            <div style="width:100%;" v-html="note.note" v-else>

                                                            </div>

                                                            <!-- <div class="note-text">
                                                                <textarea class="form-control" v-model="note.note"></textarea>
                                                            </div> -->
                                                        </div>
                                                        <div class="profile-note-bottom">
                                                            <div class="profile-note-bottom-left">
                                                                <div class="note-post-name">
                                                                    <svg version="1.1" x="0px" y="0px"
                                                                         viewBox="0 0 20 20"
                                                                         style="enable-background:new 0 0 20 20;">
                                                                        <g>
                                                                            <path class="st0"
                                                                                  d="M10,10c0.9,0,1.7-0.3,2.4-0.7c0.7-0.5,1.3-1.2,1.6-2c0.3-0.8,0.4-1.7,0.2-2.5s-0.6-1.6-1.2-2.2c-0.6-0.6-1.4-1-2.2-1.2C10,1.2,9.1,1.3,8.3,1.6c-0.8,0.3-1.5,0.9-2,1.6C5.9,3.9,5.6,4.8,5.6,5.6c0,1.2,0.5,2.3,1.3,3.1C7.7,9.5,8.8,10,10,10z M7.8,3.4C8.4,2.8,9.2,2.5,10,2.5c0.6,0,1.2,0.2,1.7,0.5s0.9,0.8,1.2,1.4c0.2,0.6,0.3,1.2,0.2,1.8c-0.1,0.6-0.4,1.2-0.9,1.6c-0.4,0.4-1,0.7-1.6,0.9C10,8.8,9.4,8.7,8.8,8.5C8.2,8.3,7.7,7.9,7.4,7.4C7.1,6.8,6.9,6.2,6.9,5.6C6.9,4.8,7.2,4,7.8,3.4z"/>
                                                                            <path class="st0"
                                                                                  d="M15.5,13.3c-1.3-1.3-3-2-4.9-2H9.4c-1.8,0-3.6,0.7-4.9,2c-1.3,1.3-2,3-2,4.9c0,0.2,0.1,0.3,0.2,0.4c0.1,0.1,0.3,0.2,0.4,0.2h13.8c0.2,0,0.3-0.1,0.4-0.2c0.1-0.1,0.2-0.3,0.2-0.4C17.5,16.3,16.8,14.6,15.5,13.3z M3.8,17.5c0.2-1.4,0.8-2.6,1.8-3.6c1-0.9,2.4-1.4,3.7-1.4h1.2c1.4,0,2.7,0.5,3.7,1.4c1,0.9,1.7,2.2,1.8,3.6H3.8z"/>
                                                                        </g>
                                                                    </svg>
                                                                    <span>{{ note.user_name }}</span>
                                                                </div>
                                                                <div class="note-post-time">
                                                                    <p>{{
                                                                            new Date(note.created_at).toLocaleTimeString('en-US', {
                                                                                hour: "2-digit",
                                                                                minute: "2-digit"
                                                                            })
                                                                        }}</p>
                                                                </div>
                                                            </div>
                                                           
                                                            <div  class="profile-note-bottom-right">
                                                                <div class="note-save text-end"
                                                                     v-show="editNoteId==note.id && note.type == 'note'" >
                                                                    <button type="button"
                                                                            class="btn btn-primary-rounded px-4"
                                                                            @click="editNoteId=0">Cancel
                                                                    </button>
                                                                </div>
                                                                <a role="button" style="cursor:pointer;"
                                                                   class="note-edit" @click="editNote(note.id)" v-show="note.type == 'note'">
                                                                    <svg width="23" height="22" viewBox="0 0 23 22"
                                                                         fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                        <path
                                                                            d="M20.0556 21.0833C20.0556 20.592 19.6204 20.1667 19.1389 20.1667C16.0088 20.1667 4.54678 20.1667 1.41667 20.1667C0.935111 20.1667 0.5 20.592 0.5 21.0833C0.5 21.5747 0.935111 22 1.41667 22H19.1389C19.6204 22 20.0556 21.5747 20.0556 21.0833ZM11.0074 16.9302L22.159 5.77744C22.368 5.56844 22.5 5.26044 22.5 4.94144C22.5 4.66156 22.3986 4.37189 22.1578 4.13233L18.3506 0.338555C18.1244 0.112444 17.8262 0 17.5292 0C17.2322 0 16.9352 0.112444 16.7079 0.338555L5.53311 11.4644C4.83767 13.6192 3.63256 17.3592 3.54578 17.6746C3.52133 17.7662 3.51033 17.8579 3.51033 17.9483C3.51033 18.5118 3.93689 18.9848 4.44533 18.9848C5.06989 18.9848 5.65656 18.7538 11.0074 16.9302ZM7.01078 12.8456L9.62267 15.4574L5.75067 16.7359L7.01078 12.8456ZM8.178 11.4204L17.5292 2.112L20.3831 4.95611L11.049 14.2914L8.178 11.4204Z"/>
                                                                    </svg>
                                                                </a>
                                                                <a role="button" style="cursor:pointer;" class="note-edit" v-show="note.type == 'chat'">
    <!-- Outgoing Message SVG -->
    <span v-if="note.inbound === 0" title="Outgoing  Text">
        
        <svg version="1.1" x="0px" y="0px" viewBox="0 0 50 50" class="w-5 h-5 text-green-500" xml:space="preserve">
            <g>
                <path d="M40.2,9.8H17.4c-2.5,0-4.5,2-4.5,4.5v13.6c0,2.3,1.8,4.2,4,4.5v4.3l5.2-4.3h18.1c2.5,0,4.5-2,4.5-4.5V14.2C44.6,11.7,42.7,9.8,40.2,9.8z M39.5,20.9l-6.7,5.9c-0.3,0.2-0.7,0.1-0.7-0.3v-2.6H20c-0.3,0-0.6-0.2-0.6-0.6v-5.4c0-0.3,0.2-0.6,0.6-0.6l12.1-0.1v-2.6c0-0.4,0.4-0.6,0.7-0.4l6.7,5.9C39.6,20.3,39.6,20.7,39.5,20.9z"/>
                <path d="M30.9,41.6H8.5V3.5h22.4v4h3.3v-4c0-2-1.6-3.5-3.5-3.5H8.8c-2,0-3.5,1.6-3.5,3.5v43c0,2,1.6,3.5,3.5,3.5h21.9c2,0,3.5-1.6,3.5-3.5V34.7h-3.3V41.6z M19.8,47.7c-1,0-2-0.8-2-1.9c0-1,0.9-1.9,2-1.9s1.9,0.9,1.9,1.9C21.7,46.9,20.8,47.7,19.8,47.7z"/>
            </g>
        </svg>
    </span>

    <!-- Incoming Message SVG -->
    <span v-else-if="note.inbound === 1" title="Incoming Text">
        <svg version="1.1" x="0px" y="0px" viewBox="0 0 50 50" class="w-5 h-5 text-blue-500" xml:space="preserve">
            <g>
                <path d="M31.1,41.6H8.7V3.5h22.4v4h3.4v-4c0-2-1.6-3.5-3.5-3.5H9C7,0,5.4,1.6,5.4,3.5v43C5.4,48.5,7,50,9,50h21.9c2,0,3.5-1.6,3.5-3.5V34.7h-3.3V41.6z M19.9,47.7c-1.1,0-1.9-0.9-1.9-1.9c0-1,0.9-1.9,1.9-1.9c1.1,0,1.9,0.9,1.9,1.9C21.8,46.8,20.9,47.7,19.9,47.7z"/>
                <path d="M40.1,9.8H17.3c-2.5,0-4.5,2-4.5,4.5v13.6c0,2.5,2,4.5,4.5,4.5h18.6l5.2,4.3v-4.3c2-0.4,3.5-2.2,3.5-4.4V14.2C44.6,11.7,42.6,9.8,40.1,9.8z M38.3,23.3c0,0.3-0.2,0.6-0.6,0.6l-12.1-0.1v2.6c0,0.4-0.4,0.6-0.7,0.3l-6.7-5.9c-0.2-0.1-0.2-0.5,0-0.7l6.7-5.9c0.3-0.2,0.7-0.1,0.7,0.4v2.6h12.2c0.3,0,0.6,0.2,0.6,0.6L38.3,23.3z"/>
            </g>
        </svg>
    </span>
</a>



                                                                <div class="note-save text-end">
                                                                    <button type="button" class="btn btn-primary px-4"
                                                                            @click="updateNote(note)" v-show="note.type == 'note'">Save
                                                                    </button>
                                                                </div>
                                                                <a role="button" data-bs-toggle="modal"
                                                                   data-bs-target="#DeleteModal"
                                                                   @click="showDeleteConfirmation(note.id)" v-show="note.type == 'note'">
                                                                    <svg version="1.1" x="0px" y="0px"
                                                                         viewBox="0 0 24 24"
                                                                         style="enable-background:new 0 0 24 24;">
                                                                        <g>
                                                                            <path
                                                                                d="M23,3.6h-3.3H4.8H1.5c-0.6,0-1,0.4-1,1s0.4,1,1,1h2.3v17.2c0,0.6,0.4,1,1,1h14.9c0.6,0,1-0.4,1-1V5.6H23c0.6,0,1-0.4,1-1S23.6,3.6,23,3.6z M18.7,21.8H5.8V5.6h12.9V21.8z"/>
                                                                            <path
                                                                                d="M9.8,2.2h5c0.6,0,1-0.4,1-1s-0.4-1-1-1h-5c-0.6,0-1,0.4-1,1S9.2,2.2,9.8,2.2z"/>
                                                                            <path
                                                                                d="M9.8,18c0.6,0,1-0.4,1-1V8.7c0-0.6-0.4-1-1-1s-1,0.4-1,1V17C8.8,17.5,9.2,18,9.8,18z"/>
                                                                            <path
                                                                                d="M14.7,18c0.6,0,1-0.4,1-1V8.7c0-0.6-0.4-1-1-1s-1,0.4-1,1V17C13.7,17.5,14.2,18,14.7,18z"/>
                                                                        </g>
                                                                    </svg>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="other-show-box" v-if="patientStore.patient.Notes.length>5">
                                                    <div class="other-show-data"
                                                         v-if="showMoreNotes && patientStore.patient.Notes">
                                                        <div v-for="note in patientStore.patient.Notes.slice(5)">
                                                            <div class="profile-note-box my-2"
                                                                 :class="editNoteId == note.id ? 'note-editable' : ''">
                                                                <div class="profile-note-add-top-title">
                                                                    <div class="d-flex">
                                                                        <!--																		<div class="note-save text-end"  v-show="editNoteId==note.id">
                                                                                                                                                    <button type="button" class="btn btn-primary-rounded px-4" @click="editNoteId=0">Cancel</button>
                                                                                                                                                </div>-->
                                                                        <a role="button" class="edit-mobile-back"
                                                                           @click="editNoteId=0">
                                                                            <svg width="20" height="14"
                                                                                 viewBox="0 0 20 14" fill="none"
                                                                                 xmlns="http://www.w3.org/2000/svg">
                                                                                <path fill-rule="evenodd"
                                                                                      clip-rule="evenodd"
                                                                                      d="M19.4411 6.37503H2.04859L7.25172 1.19191C7.49609 0.947534 7.49609 0.551909 7.25172 0.308159C7.00734 0.0637842 6.61172 0.0637842 6.36797 0.308159L0.180469 6.49503C-0.0601562 6.73566 -0.0601562 7.13816 0.180469 7.37878L6.36797 13.5663C6.61234 13.8107 7.00797 13.8107 7.25172 13.5663C7.49609 13.3219 7.49609 12.9263 7.25172 12.6825L2.04859 7.62503H19.4411C19.7861 7.62503 20.0661 7.34503 20.0661 7.00003C20.0661 6.65503 19.7861 6.37503 19.4411 6.37503Z"
                                                                                      fill="#514F5F"></path>
                                                                            </svg>
                                                                        </a>
                                                                        <a role="button" class="edit-note-save ms-auto"
                                                                           @click="updateNote(note)">
                                                                            Save
                                                                        </a>
                                                                    </div>
                                                                    <h3>Edit Note</h3>
                                                                </div>
                                                                <div class="profile-note-top">
                                                                    <div class="note-date">
                                                                        <span>{{
                                                                                new Date(note.updated_at).toLocaleDateString('en-GB', {day: "numeric"})
                                                                            }}</span>
                                                                        {{
                                                                            new Date(note.updated_at).toLocaleDateString('en-GB', {
                                                                                year: "numeric",
                                                                                month: "short"
                                                                            })
                                                                        }}
                                                                    </div>
                                                                    <div style="width:100%;" id="editor-container"
                                                                         v-if="editNoteId==note.id">
                                                                        <ckeditor :editor="editor" v-model="note.note"
                                                                                  :config="editorConfig"
                                                                                  :isReadonly="editNoteId == note.id"></ckeditor>
                                                                    </div>

                                                                    <div style="width:100%;" v-html="note.note" v-else>

                                                                    </div>
                                                                </div>
                                                                <div class="profile-note-bottom">
                                                                    <div class="profile-note-bottom-left">
                                                                        <div class="note-post-name">
                                                                            <svg version="1.1" x="0px" y="0px"
                                                                                 viewBox="0 0 20 20"
                                                                                 style="enable-background:new 0 0 20 20;">
                                                                                <g>
                                                                                    <path class="st0"
                                                                                          d="M10,10c0.9,0,1.7-0.3,2.4-0.7c0.7-0.5,1.3-1.2,1.6-2c0.3-0.8,0.4-1.7,0.2-2.5s-0.6-1.6-1.2-2.2c-0.6-0.6-1.4-1-2.2-1.2C10,1.2,9.1,1.3,8.3,1.6c-0.8,0.3-1.5,0.9-2,1.6C5.9,3.9,5.6,4.8,5.6,5.6c0,1.2,0.5,2.3,1.3,3.1C7.7,9.5,8.8,10,10,10z M7.8,3.4C8.4,2.8,9.2,2.5,10,2.5c0.6,0,1.2,0.2,1.7,0.5s0.9,0.8,1.2,1.4c0.2,0.6,0.3,1.2,0.2,1.8c-0.1,0.6-0.4,1.2-0.9,1.6c-0.4,0.4-1,0.7-1.6,0.9C10,8.8,9.4,8.7,8.8,8.5C8.2,8.3,7.7,7.9,7.4,7.4C7.1,6.8,6.9,6.2,6.9,5.6C6.9,4.8,7.2,4,7.8,3.4z"/>
                                                                                    <path class="st0"
                                                                                          d="M15.5,13.3c-1.3-1.3-3-2-4.9-2H9.4c-1.8,0-3.6,0.7-4.9,2c-1.3,1.3-2,3-2,4.9c0,0.2,0.1,0.3,0.2,0.4c0.1,0.1,0.3,0.2,0.4,0.2h13.8c0.2,0,0.3-0.1,0.4-0.2c0.1-0.1,0.2-0.3,0.2-0.4C17.5,16.3,16.8,14.6,15.5,13.3z M3.8,17.5c0.2-1.4,0.8-2.6,1.8-3.6c1-0.9,2.4-1.4,3.7-1.4h1.2c1.4,0,2.7,0.5,3.7,1.4c1,0.9,1.7,2.2,1.8,3.6H3.8z"/>
                                                                                </g>
                                                                            </svg>
                                                                            <span>{{ note.user_name }}</span>
                                                                        </div>
                                                                        <div class="note-post-time">
                                                                            <p>{{
                                                                                    new Date(note.updated_at).toLocaleTimeString('en-US', {
                                                                                        hour: "2-digit",
                                                                                        minute: "2-digit"
                                                                                    })
                                                                                }}</p>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div class="profile-note-bottom-right">
                                                                        <div class="note-save text-end"
                                                                             v-show="editNoteId==note.id">
                                                                            <button type="button"
                                                                                    class="btn btn-primary-rounded px-4"
                                                                                    @click="editNoteId=0">Cancel
                                                                            </button>
                                                                        </div>
                                                                        <a class="note-edit" style="cursor:pointer;"
                                                                           @click="editNote(note.id)">
                                                                            <svg width="23" height="22"
                                                                                 viewBox="0 0 23 22" fill="none"
                                                                                 xmlns="http://www.w3.org/2000/svg">
                                                                                <path
                                                                                    d="M20.0556 21.0833C20.0556 20.592 19.6204 20.1667 19.1389 20.1667C16.0088 20.1667 4.54678 20.1667 1.41667 20.1667C0.935111 20.1667 0.5 20.592 0.5 21.0833C0.5 21.5747 0.935111 22 1.41667 22H19.1389C19.6204 22 20.0556 21.5747 20.0556 21.0833ZM11.0074 16.9302L22.159 5.77744C22.368 5.56844 22.5 5.26044 22.5 4.94144C22.5 4.66156 22.3986 4.37189 22.1578 4.13233L18.3506 0.338555C18.1244 0.112444 17.8262 0 17.5292 0C17.2322 0 16.9352 0.112444 16.7079 0.338555L5.53311 11.4644C4.83767 13.6192 3.63256 17.3592 3.54578 17.6746C3.52133 17.7662 3.51033 17.8579 3.51033 17.9483C3.51033 18.5118 3.93689 18.9848 4.44533 18.9848C5.06989 18.9848 5.65656 18.7538 11.0074 16.9302ZM7.01078 12.8456L9.62267 15.4574L5.75067 16.7359L7.01078 12.8456ZM8.178 11.4204L17.5292 2.112L20.3831 4.95611L11.049 14.2914L8.178 11.4204Z"/>
                                                                            </svg>
                                                                        </a>
                                                                        <div class="note-save text-end">
                                                                            <button type="button"
                                                                                    class="btn btn-primary px-4"
                                                                                    @click="updateNote(note)">Save
                                                                            </button>
                                                                        </div>
                                                                        <a href="#" data-bs-toggle="modal"
                                                                           data-bs-target="#DeleteModal"
                                                                           @click="showDeleteConfirmation(note.id)">
                                                                            <svg version="1.1" x="0px" y="0px"
                                                                                 viewBox="0 0 24 24"
                                                                                 style="enable-background:new 0 0 24 24;">
                                                                                <g>
                                                                                    <path
                                                                                        d="M23,3.6h-3.3H4.8H1.5c-0.6,0-1,0.4-1,1s0.4,1,1,1h2.3v17.2c0,0.6,0.4,1,1,1h14.9c0.6,0,1-0.4,1-1V5.6H23c0.6,0,1-0.4,1-1S23.6,3.6,23,3.6z M18.7,21.8H5.8V5.6h12.9V21.8z"/>
                                                                                    <path
                                                                                        d="M9.8,2.2h5c0.6,0,1-0.4,1-1s-0.4-1-1-1h-5c-0.6,0-1,0.4-1,1S9.2,2.2,9.8,2.2z"/>
                                                                                    <path
                                                                                        d="M9.8,18c0.6,0,1-0.4,1-1V8.7c0-0.6-0.4-1-1-1s-1,0.4-1,1V17C8.8,17.5,9.2,18,9.8,18z"/>
                                                                                    <path
                                                                                        d="M14.7,18c0.6,0,1-0.4,1-1V8.7c0-0.6-0.4-1-1-1s-1,0.4-1,1V17C13.7,17.5,14.2,18,14.7,18z"/>
                                                                                </g>
                                                                            </svg>
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <span class="show-more-link" @click="showMoreNotes=!showMoreNotes"
                                                      v-if="patientStore.patient.Notes.length>5">
													<svg width="15" height="9" v-show="showMoreNotes"
                                                         style="transform: rotate(180deg);" viewBox="0 0 15 9"
                                                         fill="none" xmlns="http://www.w3.org/2000/svg">
														<path fill-rule="evenodd" clip-rule="evenodd"
                                                              d="M6.93766 6.16894L1.75453 0.965811C1.51016 0.721436 1.11453 0.721436 0.870781 0.965811C0.626406 1.21019 0.626406 1.60581 0.870781 1.84956L7.05766 8.03706C7.29828 8.27769 7.70078 8.27769 7.94141 8.03706L14.1289 1.84956C14.3733 1.60519 14.3733 1.20956 14.1289 0.965811C13.8845 0.721436 13.4889 0.721436 13.2452 0.965811L8.18766 6.16894L7.56265 6.71753L6.93766 6.16894Z"></path>
													</svg>
													{{ !showMoreNotes ? 'Show More' : 'Show Less' }}
													<svg width="15" height="9" v-show="!showMoreNotes"
                                                         viewBox="0 0 15 9" fill="none"
                                                         xmlns="http://www.w3.org/2000/svg">
														<path fill-rule="evenodd" clip-rule="evenodd"
                                                              d="M6.93766 6.16894L1.75453 0.965811C1.51016 0.721436 1.11453 0.721436 0.870781 0.965811C0.626406 1.21019 0.626406 1.60581 0.870781 1.84956L7.05766 8.03706C7.29828 8.27769 7.70078 8.27769 7.94141 8.03706L14.1289 1.84956C14.3733 1.60519 14.3733 1.20956 14.1289 0.965811C13.8845 0.721436 13.4889 0.721436 13.2452 0.965811L8.18766 6.16894L7.56265 6.71753L6.93766 6.16894Z"></path>
													</svg>
												</span>
                                            </div>
                                            <div v-else>
                                                <h6>Sorry there are no notes to display!</h6>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="profile-card-box"
                                         v-if="authStore.role === 'Admin' && auditLogStore.auditLogs && auditLogStore.auditLogs.length">
                                        <div class="profile-note-list">
                                            <div class="profile-note-title d-flex align-items-center">
                                                <h3 class="m-0">User Activity</h3>
                                            </div>

                                            <div class="audit-log">
                                                <div v-for="(field, index) in auditLogStore.auditLogs" :key="index"
                                                     :style="{ backgroundColor: getColor(index) }" class="log-entry">
                                                    <div class="log-content">
                                                        <div class="log-field">
                                                            <strong>{{ field.field }}:</strong>
                                                        </div>
                                                        <div class="log-values">
                                                            <span class="old-value">Old: {{ field.old_value }}</span>
                                                            <span class="new-value">New: {{ field.new_value }}</span>
                                                            <span class="updated-by">Updated by: {{
                                                                    field.updated_by
                                                                }}</span>
                                                        </div>
                                                        <span class="log-date">{{ formatDate(field.updated_at) }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="DeleteModal" tabindex="-1" aria-labelledby="DeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content p-3">
                <div class="modal-body text-md-center p-3 mb-3">
                    <h3>Are you sure you want to delete this note?</h3>
                    <p class="m-0">This will delete this note permanently. you cannot undo this action.</p>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-outline-primary px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary px-4" @click="deleteNote()">Delete</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="DeleteLeadModal" tabindex="-1" aria-labelledby="DeleteLeadModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content p-3">
                <div class="modal-body text-md-center p-3 mb-3">
                    <h3>Are you sure you want to delete this lead?</h3>
                    <p class="m-0">You can restore the lead from deleted lead list.</p>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-outline-primary px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary px-4" @click="deleteLead()">Delete</button>
                </div>
            </div>
        </div>
    </div>
    <WebFormDetails></WebFormDetails>
    <LeadFullDetails></LeadFullDetails>
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog"
         aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content p-3">
                <div class="modal-body text-md-center p-3 mb-3">
                    Are you sure you want to delete this tag?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" @click="cancelDelete">Cancel
                    </button>
                    <button type="button" class="btn btn-primary" @click="confirmDelete">Delete</button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import {storeToRefs} from 'pinia'
import {watch} from 'vue'
import {usePatientStore} from '../../stores/patient';
import {useResourceStore} from '../../stores/resource';
import WebFormDetails from './utils/WebFormDetails.vue';
import CKEditor from '@ckeditor/ckeditor5-vue';
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
import {useLeadStore} from '../../stores/lead';
import LeadFullDetails from './utils/LeadFullDetails.vue';
import {useAuthStore} from "../../stores/auth";
import {defineComponent} from 'vue';
import { ref, onMounted } from 'vue';
import Vue3TagsInput from 'vue3-tags-input';
import {useTagStore} from '../../stores/tag';
import {useRouter} from 'vue-router'; // Import useRouter from Vue Router
import UploadAdapter from '../../stores/UploadAdapter';
import {useAuditLogStore} from '../../stores/auditLog';
import {useAppointmentStore} from "../../stores/appointment";



export default {
    setup() {
        const patientStore = usePatientStore();

        const resourceStore = useResourceStore();

        const authStore = useAuthStore();

        const tagStore = useTagStore();

        const appointmentStore = useAppointmentStore();

        const router = useRouter();// Access the router instance

        //const patientId = router.params.id;

    


        // Define a const for savedTags
        const savedTags = tagStore.savedTags;

        const auditLogStore = useAuditLogStore();
        //auditLogStore.fetchAuditLogs(2502); // Fetch logs for subject_id 2502

        const formatDate = (dateString) => {
            const options = {year: 'numeric', month: '2-digit', day: '2-digit'};
            return new Date(dateString).toLocaleDateString('en-US', options);
        };

       
            


        return {patientStore, resourceStore, authStore, tagStore, appointmentStore, savedTags, auditLogStore, formatDate};
    },
    
    computed: {
        getFillColor() {
            const scoreStatus = this.patientStore.scoreStatus.toLowerCase();

            switch (scoreStatus) {
                case 'low':
                    return '#FF0F0F'; // Red
                case 'medium':
                    return '#FFCC00'; // Yellow
                case 'good':
                    return '#4EAB52'; // Good
                case 'high':
                    return '#008421'; // Green (default)
                default:
                    return '#008421'; // Default to green
            }
        },
    },
    data() {
        return {
            showLostReasonError: false,
            profile_editing: false,
            showMoreProfile: false,
            showMoreNotes: false,
            showFullTranscript: false,
            editNoteId: 0,
            newNote: false,
            deletingNote: 0,
            showBookConsultation: false,
            editor: ClassicEditor,
            editorConfig: {
                extraPlugins: [this.uploader],
            },
            showTagsInput: false,
            tags: [],
            debug: true, // Set to false to hide debug output
            autocompleteSuggestions: [],
            showAutocompleteList: false,
        }
    },
    components: {
        WebFormDetails,
        ckeditor: CKEditor.component,
        LeadFullDetails,
        Vue3TagsInput        
    },
    watch: {
        'patientStore.patient.source_id': {
            handler(newSourceId) {
                // Update patientStore.patient.quicksource when patientStore.patient.source_id changes
                this.updateQuickSource(newSourceId);
            },
            immediate: true, // Trigger the handler immediately on component mount
        },
    },
    updated() {
        $('#book-datetimepicker').datetimepicker({value: this.patientStore.patient.consultation_booked_date});
    }, 
    mounted() {

        let _self = this;
        $('#mailLink').removeClass('active');
        $('#profileLink').removeClass('show');
        $('#homeLink').addClass('active');

        this.resourceStore.getSources();

        this.auditLogStore.fetchAuditLogs(this.$route.params.id);

        this.patientStore.find(this.$route.params.id);

        this.tagStore.getTags(this.authStore.clinic_id, this.$route.params.id);

        this.appointmentStore.getAvailableTimes(moment().add(1, 'day').format('MM-DD-YYYY'));

        watch(() => this.authStore.clinic_id, (newValue, oldValue) => {
            if (newValue !== oldValue) {
                this.tagStore.getTags(this.authStore.clinic_id, this.$route.params.id);
            }
        });

        IMask(document.getElementById('phone'), {
            mask: '(000) 000-0000'
        });

        $.datetimepicker.setDateFormatter('moment');

        document.addEventListener('click', this.handleOutsideClick);

        $('#dob').datetimepicker({
            format: 'MM/DD/YYYY',
            timepicker: false,
            maxDate: '+1970/01/01',
            onSelectDate: function (ct, $i) {
                _self.patientStore.patient.dob = $('#dob').val();
            }
        });

        $('#treatment_sold_date').datetimepicker({
            format: 'MM/DD/YYYY',
            timepicker: false,
            onSelectDate: function (ct, $i) {
                _self.patientStore.patient.won_lost_date = $('#treatment_sold_date').val();
            }
        });

        $('#bookDate').datetimepicker({
            value: _self.patientStore.patient.consultation_booked_date ?? null,
            format: 'MM/DD/YYYY hh:mm A',
            onChangeDateTime: function (ct, $i) {
                _self.patientStore.patient.consultation_booked_date = $('#bookDate').val();
                _self.patientStore.patient.status_id = 12;
                $('#book-datetimepicker').datetimepicker({value: _self.patientStore.patient.consultation_booked_date});
            },
            onSelectDate:function(ct, $i){
                let selectedDate = moment(ct).format('MM/DD/YYYY');
                $('#bookDate').datetimepicker({
                    allowTimes: _self.appointmentStore.allowedDateTimes[selectedDate],
                    formatTime:'hh:mm A',
                    timepicker:true,
                });

                setTimeout(function(){
                    $(".xdsoft_time_variant").css({"margin-top": 0});
                }, 10);
            },
            onChangeMonth:function(ct, $i){
                let startDate = moment(ct).format('MM-01-YYYY');
                if(moment(startDate).isAfter(moment())){
                    _self.appointmentStore.getAvailableTimes(startDate);
                }
            },
            defaultSelect:false,
            hours12: false,
            ampm: true, // FOR AM/PM FORMAT
        });

        $('#book-datetimepicker').datetimepicker({
            value: _self.patientStore.patient.consultation_booked_date ?? null,
            format: 'MM/DD/YYYY hh:mm A',
            onChangeDateTime: function (ct, $i) {
                _self.patientStore.patient.consultation_booked_date = $('#book-datetimepicker').val();
                _self.patientStore.patient.status_id = 12;
                $('#bookDate').datetimepicker({value: _self.patientStore.patient.consultation_booked_date});
            },
            onSelectDate:function(ct,$i){
                let selectedDate = moment(ct).format('MM/DD/YYYY');
                $('#book-datetimepicker').datetimepicker({
                    allowTimes: _self.appointmentStore.allowedDateTimes[selectedDate],
                    formatTime:'hh:mm A',
                    timepicker:true,
                });

                setTimeout(function(){
                    $(".xdsoft_time_variant").css({"margin-top": 0});
                }, 10);
            },
            onChangeMonth:function(ct, $i){
                let startDate = moment(ct).format('MM-01-YYYY');
                if(moment(startDate).isAfter(moment())){
                    _self.appointmentStore.getAvailableTimes(startDate);
                }
            },
            defaultSelect:false,
            inline: true,
            hours12: false,
            ampm: true, // FOR AM/PM FORMAT
        });

        $('.consultation-container > .xdsoft_datetimepicker').hide();

        $(document).mouseup(function (e) {
            var container = $(".consultation-container > .xdsoft_datetimepicker");

            // if the target of the click isn't the container nor a descendant of the container
            if (!container.is(e.target) && container.has(e.target).length === 0) {
                $('.consultation-container > .xdsoft_datetimepicker').hide();
                _self.toggleDatePicker(false);
            }
        });
    },
    beforeUnmount() {
        // Remove the global click listener
        document.removeEventListener('click', this.handleOutsideClick);
    },
    methods: {
        updateQuickSource(newSourceId) {
            // Update patientStore.patient.quicksource based on newSourceId
            this.patientStore.patient.quicksource = newSourceId;
            // You can modify the logic here to set quicksource based on specific conditions if needed
        },
        updateProfile() {
            if (this.patientStore.patient.status_id == 9 && !this.patientStore.patient.reason) {
                this.showLostReasonError = true;
                this.profile_editing = true;
                this.showMoreProfile = true;
                const element = document.getElementById("lost-reason");
                element.focus();
                element.scrollIntoView();
                // return null;
            }

            this.patientStore.update();
            this.profile_editing = false;
        },
        editNote(id) {
            this.editNoteId = id;
        },
        saveNote() {
            this.patientStore.saveNote(this.patientStore.patient.id);
            this.newNote = false;
        },
        updateNote(note) {
            this.patientStore.updateNote(note);
            this.editNoteId = 0;
        },
        showDeleteConfirmation(noteId) {
            this.deletingNote = noteId;
        },
        deleteNote() {
            this.patientStore.deleteNote(this.deletingNote);
            $('#DeleteModal').modal('hide');
        },
        deleteLead() {
            this.patientStore.delete();
            $('#DeleteLeadModal').modal('hide');
        },
        hideBookConsultation() {
            let _self = this;
            if (this.showBookConsultation) {
                $('.consultation-container > .xdsoft_datetimepicker').hide();
                _self.showBookConsultation = false;
            } else {
                $('.consultation-container > .xdsoft_datetimepicker').show();
                _self.showBookConsultation = true;
                _self.showMoreProfile = true;
                _self.profile_editing = true;
            }

        },
        toggleDatePicker(bool) {
            this.showBookConsultation = bool;
        },
        toggleTagsInput() {
            this.showTagsInput = !this.showTagsInput;
        },
        handleTagsUpdate(tags, event) {
            this.tags = tags;
        },
        handleFocus() {
            // Access the input element within vue3-tags-input
            this.$nextTick(() => {
                const input = this.$refs.tagsInput?.$el.querySelector('input.v3ti-new-tag');
                if (input) input.value = ''; // Clear the input field value
            });
        },
        handleOutsideClick(event) {
    const tagsInputEl = this.$refs.tagsInput?.$el;

    if (tagsInputEl && !tagsInputEl.contains(event.target)) {
        // If the click is outside the component, clear the input without adding a tag
        this.showAutocompleteList = false;

        this.$nextTick(() => {
            const input = tagsInputEl.querySelector('input');
            if (input) {
                input.value = ''; // Clear the input to avoid treating it as a tag
                input.blur(); // Optionally blur the input field to indicate focus loss
            }
        });
    }
},
        saveTags(clinicId, leadId) {
            // Get the current input value from the vue3-tags-input if any
            const inputElement = this.$refs.tagsInput.$el.querySelector('input'); // Access input element from vue3-tags-input

            const inputText = inputElement ? inputElement.value.trim() : ''; // Get the input value

            console.log('Tags:', this.tags);
            console.log('Input Text:', inputText);
            // Prevent saving both selected suggestion and typed text as tags
            if (this.suggestionSelected) {
                // Reset the flag and do not add the input text as a tag
                this.suggestionSelected = false;
            } else if (inputText) {
                // If no suggestion was selected and there's text in the input, save it as a tag
                this.tags.push(inputText);
                inputElement.value = ''; // Clear the input field
            }

            // Proceed with saving the tags
            if (this.tags.length > 0) {
                // Use the tagStore to save tags (or however you're saving tags)
                this.tagStore.saveTag(this.tags, clinicId, leadId);

                // Reset the tags and hide input
                this.tags = [];
                this.showTagsInput = false;

                console.log('Tags saved:', this.tags);
            } else {
                console.error('No tags to save');
            }
        },
        showConfirmModal(index, leadId) {
            // Store the index and leadId to delete later
            this.deleteIndex = index;
            this.deleteLeadId = leadId;
            // Show the Bootstrap modal
            $('#confirmDeleteModal').modal('show');
        },
        confirmDelete() {
            // Proceed with deletion
            this.tagStore.deleteTag(this.deleteIndex, this.deleteLeadId);
            // Hide the Bootstrap modal
            $('#confirmDeleteModal').modal('hide');
        },
        cancelDelete() {
            // Hide the Bootstrap modal
            $('#confirmDeleteModal').modal('hide');
        },
        treatmentSoldChanged() {
            if ($('#treatment_sold').val() == 'Won') {
                this.patientStore.patient.won_lost_date = moment().format('MM/DD/YYYY');
            } else {
                this.patientStore.patient.won_lost_date = null;
            }
        },
        handleInput(event) {
            const inputText = event.target.value.trim();  // Get the trimmed input text

            if (inputText.length >= 2) {
                // Fetch autocomplete suggestions when input length is at least 2
                this.tagStore.getAutocompleteTags(this.authStore.clinic_id, inputText);
                this.showAutocompleteList = true;  // Show autocomplete list
            } else {
                // Clear autocomplete suggestions and hide the list if input is less than 2 characters
                this.autocompleteSuggestions = [];
                this.showAutocompleteList = false;
            }

            // Check if Enter (13) or Comma (188) key is pressed
            if (event.keyCode === 13 || event.keyCode === 188) {
                if (inputText) {
                    // Add the input text as a tag
                    this.tags.push(inputText);

                    // Clear the input field
                    event.target.value = '';

                    // Hide autocomplete list
                    this.showAutocompleteList = false;

                    // Clear the input text variable
                    this.inputText = '';
                }
            }
        },
        selectAutocompleteSuggestion(suggestion) {
            console.log(this.inputText);
            this.tags.push(suggestion);
            this.autocompleteSuggestions = [];
            this.showAutocompleteList = false;
            this.$nextTick(() => {
                const input = this.$refs.tagsInput.$el.querySelector('input');
                if (input) {
                    input.value = ''; // Clear the input
                    input.blur(); // Blur the input after clearing
                }
            });
            // Mark that a suggestion was selected
            this.suggestionSelected = true; // Add this flag
        },
        handleEnterKey(event) {
            // Check if the Enter key is pressed
            if (event.keyCode === 13) {
                this.showAutocompleteList = false; // Hide autocomplete list
            }
        },
        uploader(editor) {
            editor.plugins.get('FileRepository').createUploadAdapter = (loader) => {
                return new UploadAdapter(loader);
            };
        },
        getColor(index) {
            const colors = ['#f8d7da', '#d1ecf1', '#d4edda', '#fff3cd']; // Array of colors
            return colors[index % colors.length]; // Cycle through the colors
        },
        shouldDisplayTitle(){
            let shouldDisplay = false;
            if(this.patientStore.callDetails && this.patientStore.callDetails.length>0){
                this.patientStore.callDetails.forEach(detail => {
                    if((detail.call_summary != '' && detail.call_summary != null) || detail.audio_file_url != '' || (detail.hasOwnProperty('conversationalTranscript') && detail.conversationalTranscript.length>0)){
                        shouldDisplay = true;
                    }else{
                        shouldDisplay = false;
                    }
                })
            }else{
                shouldDisplay = false;
            }
            return shouldDisplay;
        },
        stageChanged() {
            if (this.patientStore.patient.status_id === "") { // Treatment Sold
                this.patientStore.patient.won_lost = "Won";
                this.patientStore.patient.won_lost_date = moment().format('MM/DD/YYYY'); // Sets today's date (YYYY-MM-DD)
            } else {
                this.patientStore.patient.won_lost = "";
                this.patientStore.patient.won_lost_date = "";
            }
        },
    }
}
</script>
<style scoped>
.audit-log .log-entry {
    padding: 10px;
    margin: 5px 0;
    border-radius: 5px;
    display: flex;
    flex-direction: column;
}

.audit-log .log-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.audit-log .log-field {
    flex: 1;
}

.audit-log .log-values {
    flex: 2;
    display: flex;
    flex-direction: column;
}

.audit-log .old-value,
.audit-log .new-value,
.audit-log .updated-by {
    margin: 2px 0;
}

.audit-log .log-date {
    margin-left: auto;
    padding-left: 10px;
}
</style>

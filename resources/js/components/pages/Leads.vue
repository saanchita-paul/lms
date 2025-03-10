<template>

    <!-- Sidebar -->
    <div :class="['sidebar edit-lead', isSidebarVisible ? 'visible' : '']" aria-hidden="true">
        <!-- Edit link to toggle container -->
        <!-- Container part inside the sidebar -->
        <div class="sidebar__content" v-show="isSidebarVisible">
            <!-- Close Button -->
            <button class="close-btn" @click="closeSidebar">&times;</button>
            <h3>Edit Patient Profile</h3>
            <div class="patient-form-box" v-show="selectedLead">
                <div class="form-group">
                    <label>First Name</label>
                    <input type="text" class="form-control" v-model="selectedLead.first_name">
                </div>
                <div class="form-group">
                    <label>Last Name</label>
                    <input type="text" class="form-control" v-model="selectedLead.last_name">
                </div>

                <div class="form-group">
                    <label>Consultation Book Date</label>
                    <div class="datepicker-box">
                        <input type="text" id="bookDate" class="form-control"
                               v-model="selectedLead.consultation_booked_date">
                        <span class="date-ico">
						<svg version="1.1" x="0px" y="0px" viewBox="0 0 24 24" style="enable-background:new 0 0 24 24;">
							<path
                                d="M19,3h-2V2c0-0.6-0.4-1-1-1s-1,0.4-1,1v1H9V2c0-0.6-0.4-1-1-1S7,1.4,7,2v1H5C3.3,3,2,4.3,2,6v14c0,1.7,1.3,3,3,3h14c1.7,0,3-1.3,3-3V6C22,4.3,20.7,3,19,3z M5,5h2v1c0,0.6,0.4,1,1,1s1-0.4,1-1V5h6v1c0,0.6,0.4,1,1,1s1-0.4,1-1V5h2c0.6,0,1,0.4,1,1v3H4V6C4,5.4,4.4,5,5,5z M19,21H5c-0.6,0-1-0.4-1-1v-9h16v9C20,20.6,19.6,21,19,21z"/>
						</svg>
					</span>
                    </div>
                </div>
                <div class="form-group">
                    <label>Stage</label>
                    <select class="js-example-basic-single form-select" v-model="selectedLead.status_id">
                        <option disabled selected value="null">Select Stage</option>
                        <option :selected="selectedLead.status_id==1" value="1">New Lead</option>
                        <option :selected="selectedLead.status_id==5" value="5">In Discussion</option>
                        <option :selected="selectedLead.status_id==2" value="2">Attempt 1</option>
                        <option :selected="selectedLead.status_id==3" value="3">Attempt 2</option>
                        <option :selected="selectedLead.status_id==4" value="4">Attempt 3 Plus</option>
                        <option :selected="selectedLead.status_id==17" value="17">Nurturing</option>
                        <option :selected="selectedLead.status_id==6" value="6">Practice Follow-Up</option>
                        <option :selected="selectedLead.status_id==9" value="9">Not Interested</option>
                        <option :selected="selectedLead.status_id==16" value="16">Existing Patient</option>
                    </select>
                </div>

                <div v-show="selectedLead.status_id==9 && showLostReasonIndex==index">
                    <h6>Lost Reason</h6>
                    <div class="form-group">
                        <select class="js-example-basic-single  form-select" v-model="selectedLead.reason">
                            <option disabled selected value="null">Select lost reason</option>
                            <option value="Price shopping">Pricing Shopping</option>
                            <option value="Office is too far">Too Far</option>
                            <option value="Medicaid or Medicare patient">Medicare / Medicaid</option>
                            <option value="Too expensive/couldn't afford it">Too Expensive</option>
                            <option value="Call disconnected, hung up">Spam / Wrong Number / Hung Up</option>
                            <option value="Current Patient">Current Patient</option>
                            <option value="Spanish Speaking">Spanish Speaking</option>
                            <option value="Insurance / Senior Grants">Insurance / Senior Grants</option>
                            <option value="No Credit Card">No Credit Card</option>
                            <option value="Duplicate Lead" selected="">Duplicate Lead</option>
                            <option value="STOP">STOP</option>
                            <option value="General Dentistry">General Dentistry</option>
                            <option value="Does not provide service">Does not provide service</option>
                            <option value="No Reason">No Reason</option>
                            <option value="Think about it">Think about it</option>
                            <option value="Stated Not Interested">Stated Not Interested</option>
                            <option value="Credit Challenged">Credit Challenged</option>
                            <option value="Refuse to give CC">Refuse to give CC</option>
                            <option value="No cosigner">No cosigner</option>
                            <option value="Veneers/Braces/Cosmetic">Veneers/Braces/Cosmetic</option>
                            <option value="Language Barrier">Language Barrier</option>
                            <option value="Spam">Spam</option>
                            <option value="Wrong Number">Wrong Number</option>
                            <option value="Hung Up">Hung Up</option>
                            <option value="No Way to Contact">No Way to Contact</option>
                            <option value="Fax Number">Fax Number</option>
                            <option value="Vendor">Vendor</option>
                            <option value="Another Dentist Office">Another Dentist Office</option>
                            <option value="Robo Call">Robo Call</option>
                            <option value="False Advertising">False Advertising</option>
                            <option value="Senior Grants">Senior Grants</option>
                            <option value="Insurance">Insurance</option>
                            <option value="No Appointment Available">No Appointment Available</option>
                            <option value="Other">Other</option>
                        </select>
<!--                        <span class="d-block text-danger mt-1" v-if="selectedLead.status_id == 9 && !selectedLead.reason">Please select Lost Reason!</span>-->
                    </div>
                </div>
                <div class="form-group">
                    <label>Note</label>
                    <ckeditor :editor="editor" v-model="selectedLead.note" :config="editorConfig"></ckeditor>
                </div>
                <button type="button" class="btn btn-primary px-4"
                        @click="quickupdate(selectedLead.id)">Save Changes
                </button>
            </div>
        </div>
    </div>

    <div id="main" class="bg-light-gray">
        <div class="container-fluid p-0">
            <div class="px-0 py-0 mt-4">
                <div class="leads-step">
                    <ul v-if="leadStore.stageCounts.length>0">
                        <li>
                            <a href="#" class="leads-step-box" :class="[leadStore.status_id==1 ? 'step-active' : '']"
                               @click="filterByStage(1)">
                                <span>{{ leadStore.stageCounts[0].TotalCount }}</span>
                                <p>New Lead</p>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="leads-step-box" :class="[leadStore.status_id==5 ? 'step-active' : '']"
                               @click="filterByStage(5)">
                                <span>{{ leadStore.stageCounts[1].TotalCount }}</span>
                                <p>In Discussion</p>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="leads-step-box" :class="[leadStore.status_id==2 ? 'step-active' : '']"
                               @click="filterByStage(2)">
                                <span>{{ leadStore.stageCounts[2].TotalCount }}</span>
                                <p>ATTEMPT ONE</p>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="leads-step-box" :class="[leadStore.status_id==3 ? 'step-active' : '']"
                               @click="filterByStage(3)">
                                <span>{{ leadStore.stageCounts[3].TotalCount }}</span>
                                <p>ATTEMPT TWO</p>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="leads-step-box" :class="[leadStore.status_id==4 ? 'step-active' : '']"
                               @click="filterByStage(4)">
                                <span>{{ leadStore.stageCounts[4].TotalCount }}</span>
                                <p>ATTEMPT THREE</p>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="leads-step-box" :class="[leadStore.status_id==17 ? 'step-active' : '']"
                               @click="filterByStage(17)">
                                <span>{{ leadStore.stageCounts[6].TotalCount }}</span>
                                <p>Nurturing</p>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="leads-step-box" :class="[leadStore.status_id==6 ? 'step-active' : '']"
                               @click="filterByStage(6)">
                                <span>{{ leadStore.stageCounts[5].TotalCount }}</span>
                                <p>Practice Follow-Up</p>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="my-4 py-lg-4 my-lg-0 d-flex align-items-center lead-filter-serch-box">
                    <div class="plus-icons">
                        <a href="#" class="tooltip-ico" data-title="Add Lead" data-bs-toggle="modal"
                           data-bs-target="#AddLeadModal" @click="leadStore.leadCreated=false">
                            <svg width="21" height="21" viewBox="0 0 21 21" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M12.4763 0.836293V9.03629H20.2563V12.6963H12.4763V20.9363H8.47631V12.6963H0.736306V9.03629H8.47631V0.836293H12.4763Z"/>
                            </svg>
                        </a>
                    </div>
                    <div class="lead-search-box">
						<span class="lead-search-ico d-lg-none">
							<svg width="25" height="25" viewBox="0 0 25 25" xmlns="http://www.w3.org/2000/svg">
								<path
                                    d="M22.6661 24.0588L13.8995 15.2922C13.2328 15.8699 12.4555 16.3199 11.5675 16.6422C10.6795 16.9644 9.73464 17.1255 8.73281 17.1255C6.32921 17.1255 4.29498 16.2922 2.63011 14.6255C0.965246 12.9588 0.132812 10.9477 0.132812 8.59216C0.132812 6.2366 0.966146 4.22549 2.63281 2.55882C4.29948 0.892156 6.31615 0.0588226 8.68281 0.0588226C11.0495 0.0588226 13.0606 0.892156 14.7161 2.55882C16.3717 4.22549 17.1995 6.23827 17.1995 8.59716C17.1995 9.54938 17.0439 10.4699 16.7328 11.3588C16.4217 12.2477 15.955 13.081 15.3328 13.8588L24.1328 22.5922L22.6661 24.0588ZM8.69948 15.1255C10.505 15.1255 12.0398 14.4866 13.3036 13.2088C14.5675 11.931 15.1995 10.3922 15.1995 8.59216C15.1995 6.79216 14.5675 5.25327 13.3036 3.97549C12.0398 2.69771 10.505 2.05882 8.69948 2.05882C6.87541 2.05882 5.32495 2.69771 4.04808 3.97549C2.77123 5.25327 2.13281 6.79216 2.13281 8.59216C2.13281 10.3922 2.77123 11.931 4.04808 13.2088C5.32495 14.4866 6.87541 15.1255 8.69948 15.1255Z"/>
							</svg>
						</span>
                        <div class="lead-search">
							<span class="search-input-ico">
								<svg width="25" height="25" viewBox="0 0 25 25" xmlns="http://www.w3.org/2000/svg">
									<path
                                        d="M22.6661 24.0588L13.8995 15.2922C13.2328 15.8699 12.4555 16.3199 11.5675 16.6422C10.6795 16.9644 9.73464 17.1255 8.73281 17.1255C6.32921 17.1255 4.29498 16.2922 2.63011 14.6255C0.965246 12.9588 0.132812 10.9477 0.132812 8.59216C0.132812 6.2366 0.966146 4.22549 2.63281 2.55882C4.29948 0.892156 6.31615 0.0588226 8.68281 0.0588226C11.0495 0.0588226 13.0606 0.892156 14.7161 2.55882C16.3717 4.22549 17.1995 6.23827 17.1995 8.59716C17.1995 9.54938 17.0439 10.4699 16.7328 11.3588C16.4217 12.2477 15.955 13.081 15.3328 13.8588L24.1328 22.5922L22.6661 24.0588ZM8.69948 15.1255C10.505 15.1255 12.0398 14.4866 13.3036 13.2088C14.5675 11.931 15.1995 10.3922 15.1995 8.59216C15.1995 6.79216 14.5675 5.25327 13.3036 3.97549C12.0398 2.69771 10.505 2.05882 8.69948 2.05882C6.87541 2.05882 5.32495 2.69771 4.04808 3.97549C2.77123 5.25327 2.13281 6.79216 2.13281 8.59216C2.13281 10.3922 2.77123 11.931 4.04808 13.2088C5.32495 14.4866 6.87541 15.1255 8.69948 15.1255Z"/>
								</svg>
							</span>
                            <input type="text" class="form-control" placeholder="Search Leads" v-model="leadStore.query"
                                   v-on:keyup="searchLeads">
                        </div>
                    </div>
                    <div class="filter-icons d-flex justify-content-end">
                        <div class="dashboard-select ms-auto d-none d-lg-block mr-5">
                            <select class="form-select exportSelect" v-model="leadStore.perPage"
                                    v-on:change="recordsPerPageChange()">
                                <option disabled selected value="">Per Page</option>
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                        </div>
                        <div style="width:10px;"></div>
                        <a href="#" class="filter-ico tooltip-ico px-3 btn border-0 bg-white" data-title="Column Preference"
                           v-if="showCategoryFilter">
                            <span class="ico-title text-nowrap">Edit Columns</span>
                            <svg width="18" height="18" viewBox="0 0 18 18" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M7.675 18V12.375H9.175V14.45H18V15.95H9.175V18H7.675ZM0 15.95V14.45H6.175V15.95H0ZM4.675 11.8V9.75H0V8.25H4.675V6.15H6.175V11.8H4.675ZM7.675 9.75V8.25H18V9.75H7.675ZM11.825 5.625V0H13.325V2.05H18V3.55H13.325V5.625H11.825ZM0 3.55V2.05H10.325V3.55H0Z"/>
                            </svg>
                            <!-- <span>Filter</span> -->
                        </a>
                        <div class="lead-filterBy-box">
                            <div class="lead-filterBy-top d-flex align-items-center">
                                <div class="filterBy-back">
                                    <a href="#" class="filterBy-back-btn">
                                        <svg width="20" height="14" viewBox="0 0 20 14" fill="none"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                  d="M19.4411 6.37503H2.04859L7.25172 1.19191C7.49609 0.947534 7.49609 0.551909 7.25172 0.308159C7.00734 0.0637842 6.61172 0.0637842 6.36797 0.308159L0.180469 6.49503C-0.0601562 6.73566 -0.0601562 7.13816 0.180469 7.37878L6.36797 13.5663C6.61234 13.8107 7.00797 13.8107 7.25172 13.5663C7.49609 13.3219 7.49609 12.9263 7.25172 12.6825L2.04859 7.62503H19.4411C19.7861 7.62503 20.0661 7.34503 20.0661 7.00003C20.0661 6.65503 19.7861 6.37503 19.4411 6.37503Z"
                                                  fill="#514F5F"></path>
                                        </svg>
                                    </a>
                                </div>
                                <div class="filterBy-title mx-lg-auto">
                                    <h5>Categories</h5>
                                </div>
                                <div class="filterBy-save ms-auto ms-lg-0">
                                    <a href="#" class="filterBy-save-btn" @click="saveCategories()">
                                        Save
                                    </a>
                                    <a href="#" class="filterBy-viewall-save" @click="saveCategories()">
                                        Save
                                    </a>
                                    <a href="#" class="filterBy-viewall-btn">
                                        View all
                                        <svg width="9" height="15" viewBox="0 0 9 15" fill="none"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                  d="M6.20141 8.06186L0.998281 13.245C0.753906 13.4894 0.753906 13.885 0.998281 14.1287C1.24266 14.3731 1.63828 14.3731 1.88203 14.1287L8.06953 7.94186C8.31016 7.70123 8.31016 7.29873 8.06953 7.05811L1.88203 0.870606C1.63766 0.626231 1.24203 0.626231 0.998281 0.870606C0.753906 1.11498 0.753906 1.51061 0.998281 1.75436L6.20141 6.81186L6.75 7.43686L6.20141 8.06186Z"
                                                  fill="#514F5F"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                            <div class="filterBy-cate-list">
                                <span v-for="category in checkedCategories">{{ category }}</span>
                            </div>
                            <div class="SortByTitle d-lg-none">
                                <h4>Sort By</h4>
                            </div>
                            <div class="lead-filterBy-middle">
                                <div class="form-check" v-for="(category, index) in leadStore.categories">
                                    <input class="form-check-input" type="checkbox" :id="category.name" value="1"
                                           v-model="category.checked" @change="filterCheckedWidth">
                                    <label class="form-check-label" :for="category.name">{{ category.name }}</label>
                                </div>
                            </div>
                            <!--							<div class="apply-btn d-lg-none text-end mt-3">
                                                            <button type="button" class="btn btn-primary" @click="getLeads">Apply</button>
                                                        </div>-->
                        </div>
                        <div style="width:10px;"></div>
                        <div class="deletedLeadsSwitch  tooltip-ico" style="cursor:pointer !important;"
                             :data-title="leadStore.deletedLeads? 'View Active Leads' : 'View Deleted Leads'">
                            <input type="checkbox" class="btn-check" name="options-outlined" id="danger-outlined"
                                   v-model="leadStore.deletedLeads" autocomplete="off" @change="deletedLeadsToggle()">
                            <label class="delete-lead btn btn-outline-secondary" for="danger-outlined">
                                <svg fill="#6c757d" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 485 485" xml:space="preserve" stroke="#FFFFFF"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g
                                    id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g
                                    id="SVGRepo_iconCarrier"> <g> <g> <rect x="67.224" width="350.535"
                                                                            height="71.81"></rect>
                                    <path
                                        d="M417.776,92.829H67.237V485h350.537V92.829H417.776z M165.402,431.447h-28.362V146.383h28.362V431.447z M256.689,431.447 h-28.363V146.383h28.363V431.447z M347.97,431.447h-28.361V146.383h28.361V431.447z"></path> </g> </g> </g></svg>
                            </label>
                        </div>
                    </div>
                </div>


                <div class="lead-table-box">
                    <table class="table">
                        <thead>
                        <tr>
                            <th @mouseenter="leadStore.hover_on = 'Full Name'" @mouseleave="leadStore.hover_on = ''"
                                v-show="leadStore.categories[0].checked" @click="sortByField('Full Name')">Full Name
                                <svg style="vertical-align:top;"
                                     v-show="leadStore.sort_by=='Full Name' && leadStore.sort_order=='asc'" width="20px"
                                     height="20px" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                    <g id="SVGRepo_iconCarrier">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                              d="M12.0809 7.28641L12.4345 6.93286L12.788 7.28641L17 11.4984L16.2929 12.2055L12.9345 8.84707V16.9999H11.9345V8.84707L8.57605 12.2055L7.86895 11.4984L12.0809 7.28641Z"
                                              fill="#000000"></path>
                                    </g>
                                </svg>
                                <svg style="vertical-align:top;"
                                     v-show="leadStore.sort_by=='Full Name' && leadStore.sort_order=='desc'"
                                     width="20px" height="20px" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                    <g id="SVGRepo_iconCarrier">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                              d="M11.9191 16.7136L11.5655 17.0671L11.212 16.7136L7 12.5016L7.70711 11.7945L11.0655 15.1529V7.00009H12.0655V15.1529L15.4239 11.7945L16.1311 12.5016L11.9191 16.7136Z"
                                              fill="#000000"></path>
                                    </g>
                                </svg>
                                <svg viewBox="0 0 24 24" style="vertical-align:top;"
                                     v-show="leadStore.sort_by!='Full Name' && leadStore.hover_on == 'Full Name'"
                                     width="20px" height="20px" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                    <g id="SVGRepo_iconCarrier">
                                        <path
                                            d="M12.29 20.69C12.3792 20.6521 12.4606 20.5978 12.53 20.53L16.53 16.53C16.6625 16.3878 16.7346 16.1998 16.7312 16.0055C16.7277 15.8112 16.649 15.6258 16.5116 15.4884C16.3742 15.351 16.1888 15.2723 15.9945 15.2688C15.8002 15.2654 15.6122 15.3375 15.47 15.47L12.75 18.19V5.81001L15.47 8.53001C15.6122 8.66249 15.8002 8.73462 15.9945 8.73119C16.1888 8.72776 16.3742 8.64905 16.5116 8.51163C16.649 8.37422 16.7277 8.18884 16.7312 7.99454C16.7346 7.80024 16.6625 7.61219 16.53 7.47001L12.53 3.47001C12.4606 3.40222 12.3792 3.34796 12.29 3.31001C12.1984 3.27038 12.0998 3.24994 12 3.24994C11.9002 3.24994 11.8015 3.27038 11.71 3.31001C11.6207 3.34796 11.5394 3.40222 11.47 3.47001L7.47 7.47001C7.32955 7.61064 7.25066 7.80126 7.25066 8.00001C7.25066 8.19876 7.32955 8.38939 7.47 8.53001C7.61062 8.67046 7.80125 8.74935 8 8.74935C8.19875 8.74935 8.38937 8.67046 8.53 8.53001L11.25 5.81001V18.19L8.53 15.47C8.38937 15.3296 8.19875 15.2507 8 15.2507C7.80125 15.2507 7.61062 15.3296 7.47 15.47C7.32955 15.6106 7.25066 15.8013 7.25066 16C7.25066 16.1988 7.32955 16.3894 7.47 16.53L11.47 20.53C11.5394 20.5978 11.6207 20.6521 11.71 20.69C11.8015 20.7296 11.9002 20.7501 12 20.7501C12.0998 20.7501 12.1984 20.7296 12.29 20.69Z"
                                            fill="#000000"></path>
                                    </g>
                                </svg>
                            </th>
                            <th @mouseenter="leadStore.hover_on = 'Email'" @mouseleave="leadStore.hover_on = ''"
                                v-show="leadStore.categories[1].checked" @click="sortByField('Email')">Email
                                <svg style="vertical-align:top;"
                                     v-show="leadStore.sort_by=='Email' && leadStore.sort_order=='asc'" width="20px"
                                     height="20px" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                    <g id="SVGRepo_iconCarrier">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                              d="M12.0809 7.28641L12.4345 6.93286L12.788 7.28641L17 11.4984L16.2929 12.2055L12.9345 8.84707V16.9999H11.9345V8.84707L8.57605 12.2055L7.86895 11.4984L12.0809 7.28641Z"
                                              fill="#000000"></path>
                                    </g>
                                </svg>
                                <svg style="vertical-align:top;"
                                     v-show="leadStore.sort_by=='Email' && leadStore.sort_order=='desc'" width="20px"
                                     height="20px" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                    <g id="SVGRepo_iconCarrier">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                              d="M11.9191 16.7136L11.5655 17.0671L11.212 16.7136L7 12.5016L7.70711 11.7945L11.0655 15.1529V7.00009H12.0655V15.1529L15.4239 11.7945L16.1311 12.5016L11.9191 16.7136Z"
                                              fill="#000000"></path>
                                    </g>
                                </svg>
                                <svg viewBox="0 0 24 24" style="vertical-align:top;"
                                     v-show="leadStore.sort_by!='Email' && leadStore.hover_on == 'Email'" width="20px"
                                     height="20px" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                    <g id="SVGRepo_iconCarrier">
                                        <path
                                            d="M12.29 20.69C12.3792 20.6521 12.4606 20.5978 12.53 20.53L16.53 16.53C16.6625 16.3878 16.7346 16.1998 16.7312 16.0055C16.7277 15.8112 16.649 15.6258 16.5116 15.4884C16.3742 15.351 16.1888 15.2723 15.9945 15.2688C15.8002 15.2654 15.6122 15.3375 15.47 15.47L12.75 18.19V5.81001L15.47 8.53001C15.6122 8.66249 15.8002 8.73462 15.9945 8.73119C16.1888 8.72776 16.3742 8.64905 16.5116 8.51163C16.649 8.37422 16.7277 8.18884 16.7312 7.99454C16.7346 7.80024 16.6625 7.61219 16.53 7.47001L12.53 3.47001C12.4606 3.40222 12.3792 3.34796 12.29 3.31001C12.1984 3.27038 12.0998 3.24994 12 3.24994C11.9002 3.24994 11.8015 3.27038 11.71 3.31001C11.6207 3.34796 11.5394 3.40222 11.47 3.47001L7.47 7.47001C7.32955 7.61064 7.25066 7.80126 7.25066 8.00001C7.25066 8.19876 7.32955 8.38939 7.47 8.53001C7.61062 8.67046 7.80125 8.74935 8 8.74935C8.19875 8.74935 8.38937 8.67046 8.53 8.53001L11.25 5.81001V18.19L8.53 15.47C8.38937 15.3296 8.19875 15.2507 8 15.2507C7.80125 15.2507 7.61062 15.3296 7.47 15.47C7.32955 15.6106 7.25066 15.8013 7.25066 16C7.25066 16.1988 7.32955 16.3894 7.47 16.53L11.47 20.53C11.5394 20.5978 11.6207 20.6521 11.71 20.69C11.8015 20.7296 11.9002 20.7501 12 20.7501C12.0998 20.7501 12.1984 20.7296 12.29 20.69Z"
                                            fill="#000000"></path>
                                    </g>
                                </svg>
                            </th>
                            <th @mouseenter="leadStore.hover_on = 'Source'" @mouseleave="leadStore.hover_on = ''"
                                v-show="leadStore.categories[2].checked" @click="sortByField('Source')">Source
                                <svg style="vertical-align:top;"
                                     v-show="leadStore.sort_by=='Source' && leadStore.sort_order=='asc'" width="20px"
                                     height="20px" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                    <g id="SVGRepo_iconCarrier">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                              d="M12.0809 7.28641L12.4345 6.93286L12.788 7.28641L17 11.4984L16.2929 12.2055L12.9345 8.84707V16.9999H11.9345V8.84707L8.57605 12.2055L7.86895 11.4984L12.0809 7.28641Z"
                                              fill="#000000"></path>
                                    </g>
                                </svg>
                                <svg style="vertical-align:top;"
                                     v-show="leadStore.sort_by=='Source' && leadStore.sort_order=='desc'" width="20px"
                                     height="20px" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                    <g id="SVGRepo_iconCarrier">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                              d="M11.9191 16.7136L11.5655 17.0671L11.212 16.7136L7 12.5016L7.70711 11.7945L11.0655 15.1529V7.00009H12.0655V15.1529L15.4239 11.7945L16.1311 12.5016L11.9191 16.7136Z"
                                              fill="#000000"></path>
                                    </g>
                                </svg>
                                <svg viewBox="0 0 24 24" style="vertical-align:top;"
                                     v-show="leadStore.sort_by!='Source' && leadStore.hover_on == 'Source'" width="20px"
                                     height="20px" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                    <g id="SVGRepo_iconCarrier">
                                        <path
                                            d="M12.29 20.69C12.3792 20.6521 12.4606 20.5978 12.53 20.53L16.53 16.53C16.6625 16.3878 16.7346 16.1998 16.7312 16.0055C16.7277 15.8112 16.649 15.6258 16.5116 15.4884C16.3742 15.351 16.1888 15.2723 15.9945 15.2688C15.8002 15.2654 15.6122 15.3375 15.47 15.47L12.75 18.19V5.81001L15.47 8.53001C15.6122 8.66249 15.8002 8.73462 15.9945 8.73119C16.1888 8.72776 16.3742 8.64905 16.5116 8.51163C16.649 8.37422 16.7277 8.18884 16.7312 7.99454C16.7346 7.80024 16.6625 7.61219 16.53 7.47001L12.53 3.47001C12.4606 3.40222 12.3792 3.34796 12.29 3.31001C12.1984 3.27038 12.0998 3.24994 12 3.24994C11.9002 3.24994 11.8015 3.27038 11.71 3.31001C11.6207 3.34796 11.5394 3.40222 11.47 3.47001L7.47 7.47001C7.32955 7.61064 7.25066 7.80126 7.25066 8.00001C7.25066 8.19876 7.32955 8.38939 7.47 8.53001C7.61062 8.67046 7.80125 8.74935 8 8.74935C8.19875 8.74935 8.38937 8.67046 8.53 8.53001L11.25 5.81001V18.19L8.53 15.47C8.38937 15.3296 8.19875 15.2507 8 15.2507C7.80125 15.2507 7.61062 15.3296 7.47 15.47C7.32955 15.6106 7.25066 15.8013 7.25066 16C7.25066 16.1988 7.32955 16.3894 7.47 16.53L11.47 20.53C11.5394 20.5978 11.6207 20.6521 11.71 20.69C11.8015 20.7296 11.9002 20.7501 12 20.7501C12.0998 20.7501 12.1984 20.7296 12.29 20.69Z"
                                            fill="#000000"></path>
                                    </g>
                                </svg>
                            </th>
                            <th @mouseenter="leadStore.hover_on = 'Phone/Form Lead'"
                                @mouseleave="leadStore.hover_on = ''" v-show="leadStore.categories[3].checked"
                                @click="sortByField('Phone/Form Lead')">Lead Type
                                <svg style="vertical-align:top;"
                                     v-show="leadStore.sort_by=='Phone/Form Lead' && leadStore.sort_order=='asc'"
                                     width="20px" height="20px" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                    <g id="SVGRepo_iconCarrier">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                              d="M12.0809 7.28641L12.4345 6.93286L12.788 7.28641L17 11.4984L16.2929 12.2055L12.9345 8.84707V16.9999H11.9345V8.84707L8.57605 12.2055L7.86895 11.4984L12.0809 7.28641Z"
                                              fill="#000000"></path>
                                    </g>
                                </svg>
                                <svg style="vertical-align:top;"
                                     v-show="leadStore.sort_by=='Phone/Form Lead' && leadStore.sort_order=='desc'"
                                     width="20px" height="20px" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                    <g id="SVGRepo_iconCarrier">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                              d="M11.9191 16.7136L11.5655 17.0671L11.212 16.7136L7 12.5016L7.70711 11.7945L11.0655 15.1529V7.00009H12.0655V15.1529L15.4239 11.7945L16.1311 12.5016L11.9191 16.7136Z"
                                              fill="#000000"></path>
                                    </g>
                                </svg>
                                <svg viewBox="0 0 24 24" style="vertical-align:top;"
                                     v-show="leadStore.sort_by!='Phone/Form Lead' && leadStore.hover_on == 'Phone/Form Lead'"
                                     width="20px" height="20px" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                    <g id="SVGRepo_iconCarrier">
                                        <path
                                            d="M12.29 20.69C12.3792 20.6521 12.4606 20.5978 12.53 20.53L16.53 16.53C16.6625 16.3878 16.7346 16.1998 16.7312 16.0055C16.7277 15.8112 16.649 15.6258 16.5116 15.4884C16.3742 15.351 16.1888 15.2723 15.9945 15.2688C15.8002 15.2654 15.6122 15.3375 15.47 15.47L12.75 18.19V5.81001L15.47 8.53001C15.6122 8.66249 15.8002 8.73462 15.9945 8.73119C16.1888 8.72776 16.3742 8.64905 16.5116 8.51163C16.649 8.37422 16.7277 8.18884 16.7312 7.99454C16.7346 7.80024 16.6625 7.61219 16.53 7.47001L12.53 3.47001C12.4606 3.40222 12.3792 3.34796 12.29 3.31001C12.1984 3.27038 12.0998 3.24994 12 3.24994C11.9002 3.24994 11.8015 3.27038 11.71 3.31001C11.6207 3.34796 11.5394 3.40222 11.47 3.47001L7.47 7.47001C7.32955 7.61064 7.25066 7.80126 7.25066 8.00001C7.25066 8.19876 7.32955 8.38939 7.47 8.53001C7.61062 8.67046 7.80125 8.74935 8 8.74935C8.19875 8.74935 8.38937 8.67046 8.53 8.53001L11.25 5.81001V18.19L8.53 15.47C8.38937 15.3296 8.19875 15.2507 8 15.2507C7.80125 15.2507 7.61062 15.3296 7.47 15.47C7.32955 15.6106 7.25066 15.8013 7.25066 16C7.25066 16.1988 7.32955 16.3894 7.47 16.53L11.47 20.53C11.5394 20.5978 11.6207 20.6521 11.71 20.69C11.8015 20.7296 11.9002 20.7501 12 20.7501C12.0998 20.7501 12.1984 20.7296 12.29 20.69Z"
                                            fill="#000000"></path>
                                    </g>
                                </svg>
                            </th>
                            <th @mouseenter="leadStore.hover_on = 'Phone'" @mouseleave="leadStore.hover_on = ''"
                                v-show="leadStore.categories[4].checked" @click="sortByField('Phone')">Phone
                                <svg style="vertical-align:top;"
                                     v-show="leadStore.sort_by=='Phone' && leadStore.sort_order=='asc'" width="20px"
                                     height="20px" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                    <g id="SVGRepo_iconCarrier">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                              d="M12.0809 7.28641L12.4345 6.93286L12.788 7.28641L17 11.4984L16.2929 12.2055L12.9345 8.84707V16.9999H11.9345V8.84707L8.57605 12.2055L7.86895 11.4984L12.0809 7.28641Z"
                                              fill="#000000"></path>
                                    </g>
                                </svg>
                                <svg style="vertical-align:top;"
                                     v-show="leadStore.sort_by=='Phone' && leadStore.sort_order=='desc'" width="20px"
                                     height="20px" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                    <g id="SVGRepo_iconCarrier">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                              d="M11.9191 16.7136L11.5655 17.0671L11.212 16.7136L7 12.5016L7.70711 11.7945L11.0655 15.1529V7.00009H12.0655V15.1529L15.4239 11.7945L16.1311 12.5016L11.9191 16.7136Z"
                                              fill="#000000"></path>
                                    </g>
                                </svg>
                                <svg viewBox="0 0 24 24" style="vertical-align:top;"
                                     v-show="leadStore.sort_by!='Phone' && leadStore.hover_on == 'Phone'" width="20px"
                                     height="20px" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                    <g id="SVGRepo_iconCarrier">
                                        <path
                                            d="M12.29 20.69C12.3792 20.6521 12.4606 20.5978 12.53 20.53L16.53 16.53C16.6625 16.3878 16.7346 16.1998 16.7312 16.0055C16.7277 15.8112 16.649 15.6258 16.5116 15.4884C16.3742 15.351 16.1888 15.2723 15.9945 15.2688C15.8002 15.2654 15.6122 15.3375 15.47 15.47L12.75 18.19V5.81001L15.47 8.53001C15.6122 8.66249 15.8002 8.73462 15.9945 8.73119C16.1888 8.72776 16.3742 8.64905 16.5116 8.51163C16.649 8.37422 16.7277 8.18884 16.7312 7.99454C16.7346 7.80024 16.6625 7.61219 16.53 7.47001L12.53 3.47001C12.4606 3.40222 12.3792 3.34796 12.29 3.31001C12.1984 3.27038 12.0998 3.24994 12 3.24994C11.9002 3.24994 11.8015 3.27038 11.71 3.31001C11.6207 3.34796 11.5394 3.40222 11.47 3.47001L7.47 7.47001C7.32955 7.61064 7.25066 7.80126 7.25066 8.00001C7.25066 8.19876 7.32955 8.38939 7.47 8.53001C7.61062 8.67046 7.80125 8.74935 8 8.74935C8.19875 8.74935 8.38937 8.67046 8.53 8.53001L11.25 5.81001V18.19L8.53 15.47C8.38937 15.3296 8.19875 15.2507 8 15.2507C7.80125 15.2507 7.61062 15.3296 7.47 15.47C7.32955 15.6106 7.25066 15.8013 7.25066 16C7.25066 16.1988 7.32955 16.3894 7.47 16.53L11.47 20.53C11.5394 20.5978 11.6207 20.6521 11.71 20.69C11.8015 20.7296 11.9002 20.7501 12 20.7501C12.0998 20.7501 12.1984 20.7296 12.29 20.69Z"
                                            fill="#000000"></path>
                                    </g>
                                </svg>
                            </th>
                            <th @mouseenter="leadStore.hover_on = 'Date of Birth'" @mouseleave="leadStore.hover_on = ''"
                                v-show="leadStore.categories[5].checked" @click="sortByField('Date of Birth')">Date of
                                Birth
                                <svg style="vertical-align:top;"
                                     v-show="leadStore.sort_by=='Date of Birth' && leadStore.sort_order=='asc'"
                                     width="20px" height="20px" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                    <g id="SVGRepo_iconCarrier">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                              d="M12.0809 7.28641L12.4345 6.93286L12.788 7.28641L17 11.4984L16.2929 12.2055L12.9345 8.84707V16.9999H11.9345V8.84707L8.57605 12.2055L7.86895 11.4984L12.0809 7.28641Z"
                                              fill="#000000"></path>
                                    </g>
                                </svg>
                                <svg style="vertical-align:top;"
                                     v-show="leadStore.sort_by=='Date of Birth' && leadStore.sort_order=='desc'"
                                     width="20px" height="20px" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                    <g id="SVGRepo_iconCarrier">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                              d="M11.9191 16.7136L11.5655 17.0671L11.212 16.7136L7 12.5016L7.70711 11.7945L11.0655 15.1529V7.00009H12.0655V15.1529L15.4239 11.7945L16.1311 12.5016L11.9191 16.7136Z"
                                              fill="#000000"></path>
                                    </g>
                                </svg>
                                <svg viewBox="0 0 24 24" style="vertical-align:top;"
                                     v-show="leadStore.sort_by!='Date of Birth' && leadStore.hover_on == 'Date of Birth'"
                                     width="20px" height="20px" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                    <g id="SVGRepo_iconCarrier">
                                        <path
                                            d="M12.29 20.69C12.3792 20.6521 12.4606 20.5978 12.53 20.53L16.53 16.53C16.6625 16.3878 16.7346 16.1998 16.7312 16.0055C16.7277 15.8112 16.649 15.6258 16.5116 15.4884C16.3742 15.351 16.1888 15.2723 15.9945 15.2688C15.8002 15.2654 15.6122 15.3375 15.47 15.47L12.75 18.19V5.81001L15.47 8.53001C15.6122 8.66249 15.8002 8.73462 15.9945 8.73119C16.1888 8.72776 16.3742 8.64905 16.5116 8.51163C16.649 8.37422 16.7277 8.18884 16.7312 7.99454C16.7346 7.80024 16.6625 7.61219 16.53 7.47001L12.53 3.47001C12.4606 3.40222 12.3792 3.34796 12.29 3.31001C12.1984 3.27038 12.0998 3.24994 12 3.24994C11.9002 3.24994 11.8015 3.27038 11.71 3.31001C11.6207 3.34796 11.5394 3.40222 11.47 3.47001L7.47 7.47001C7.32955 7.61064 7.25066 7.80126 7.25066 8.00001C7.25066 8.19876 7.32955 8.38939 7.47 8.53001C7.61062 8.67046 7.80125 8.74935 8 8.74935C8.19875 8.74935 8.38937 8.67046 8.53 8.53001L11.25 5.81001V18.19L8.53 15.47C8.38937 15.3296 8.19875 15.2507 8 15.2507C7.80125 15.2507 7.61062 15.3296 7.47 15.47C7.32955 15.6106 7.25066 15.8013 7.25066 16C7.25066 16.1988 7.32955 16.3894 7.47 16.53L11.47 20.53C11.5394 20.5978 11.6207 20.6521 11.71 20.69C11.8015 20.7296 11.9002 20.7501 12 20.7501C12.0998 20.7501 12.1984 20.7296 12.29 20.69Z"
                                            fill="#000000"></path>
                                    </g>
                                </svg>
                            </th>
                            <th @mouseenter="leadStore.hover_on = 'Created At'" @mouseleave="leadStore.hover_on = ''"
                                v-show="leadStore.categories[6].checked" @click="sortByField('Created At')">Created At
                                <svg style="vertical-align:top;"
                                     v-show="leadStore.sort_by=='Created At' && leadStore.sort_order=='asc'"
                                     width="20px" height="20px" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                    <g id="SVGRepo_iconCarrier">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                              d="M12.0809 7.28641L12.4345 6.93286L12.788 7.28641L17 11.4984L16.2929 12.2055L12.9345 8.84707V16.9999H11.9345V8.84707L8.57605 12.2055L7.86895 11.4984L12.0809 7.28641Z"
                                              fill="#000000"></path>
                                    </g>
                                </svg>
                                <svg style="vertical-align:top;"
                                     v-show="leadStore.sort_by=='Created At' && leadStore.sort_order=='desc'"
                                     width="20px" height="20px" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                    <g id="SVGRepo_iconCarrier">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                              d="M11.9191 16.7136L11.5655 17.0671L11.212 16.7136L7 12.5016L7.70711 11.7945L11.0655 15.1529V7.00009H12.0655V15.1529L15.4239 11.7945L16.1311 12.5016L11.9191 16.7136Z"
                                              fill="#000000"></path>
                                    </g>
                                </svg>
                                <svg viewBox="0 0 24 24" style="vertical-align:top;"
                                     v-show="leadStore.sort_by!='Created At' && leadStore.hover_on == 'Created At'"
                                     width="20px" height="20px" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                    <g id="SVGRepo_iconCarrier">
                                        <path
                                            d="M12.29 20.69C12.3792 20.6521 12.4606 20.5978 12.53 20.53L16.53 16.53C16.6625 16.3878 16.7346 16.1998 16.7312 16.0055C16.7277 15.8112 16.649 15.6258 16.5116 15.4884C16.3742 15.351 16.1888 15.2723 15.9945 15.2688C15.8002 15.2654 15.6122 15.3375 15.47 15.47L12.75 18.19V5.81001L15.47 8.53001C15.6122 8.66249 15.8002 8.73462 15.9945 8.73119C16.1888 8.72776 16.3742 8.64905 16.5116 8.51163C16.649 8.37422 16.7277 8.18884 16.7312 7.99454C16.7346 7.80024 16.6625 7.61219 16.53 7.47001L12.53 3.47001C12.4606 3.40222 12.3792 3.34796 12.29 3.31001C12.1984 3.27038 12.0998 3.24994 12 3.24994C11.9002 3.24994 11.8015 3.27038 11.71 3.31001C11.6207 3.34796 11.5394 3.40222 11.47 3.47001L7.47 7.47001C7.32955 7.61064 7.25066 7.80126 7.25066 8.00001C7.25066 8.19876 7.32955 8.38939 7.47 8.53001C7.61062 8.67046 7.80125 8.74935 8 8.74935C8.19875 8.74935 8.38937 8.67046 8.53 8.53001L11.25 5.81001V18.19L8.53 15.47C8.38937 15.3296 8.19875 15.2507 8 15.2507C7.80125 15.2507 7.61062 15.3296 7.47 15.47C7.32955 15.6106 7.25066 15.8013 7.25066 16C7.25066 16.1988 7.32955 16.3894 7.47 16.53L11.47 20.53C11.5394 20.5978 11.6207 20.6521 11.71 20.69C11.8015 20.7296 11.9002 20.7501 12 20.7501C12.0998 20.7501 12.1984 20.7296 12.29 20.69Z"
                                            fill="#000000"></path>
                                    </g>
                                </svg>
                            </th>
                            <th @mouseenter="leadStore.hover_on = 'Lead Score'" @mouseleave="leadStore.hover_on = ''"
                                v-show="leadStore.categories[7].checked" @click="sortByField('Lead Score')">Lead Score
                                <svg style="vertical-align:top;"
                                     v-show="leadStore.sort_by=='Lead Score' && leadStore.sort_order=='asc'"
                                     width="20px" height="20px" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                    <g id="SVGRepo_iconCarrier">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                              d="M12.0809 7.28641L12.4345 6.93286L12.788 7.28641L17 11.4984L16.2929 12.2055L12.9345 8.84707V16.9999H11.9345V8.84707L8.57605 12.2055L7.86895 11.4984L12.0809 7.28641Z"
                                              fill="#000000"></path>
                                    </g>
                                </svg>
                                <svg style="vertical-align:top;"
                                     v-show="leadStore.sort_by=='Lead Score' && leadStore.sort_order=='desc'"
                                     width="20px" height="20px" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                    <g id="SVGRepo_iconCarrier">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                              d="M11.9191 16.7136L11.5655 17.0671L11.212 16.7136L7 12.5016L7.70711 11.7945L11.0655 15.1529V7.00009H12.0655V15.1529L15.4239 11.7945L16.1311 12.5016L11.9191 16.7136Z"
                                              fill="#000000"></path>
                                    </g>
                                </svg>
                                <svg viewBox="0 0 24 24" style="vertical-align:top;"
                                     v-show="leadStore.sort_by!='Lead Score' && leadStore.hover_on == 'Lead Score'"
                                     width="20px" height="20px" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                    <g id="SVGRepo_iconCarrier">
                                        <path
                                            d="M12.29 20.69C12.3792 20.6521 12.4606 20.5978 12.53 20.53L16.53 16.53C16.6625 16.3878 16.7346 16.1998 16.7312 16.0055C16.7277 15.8112 16.649 15.6258 16.5116 15.4884C16.3742 15.351 16.1888 15.2723 15.9945 15.2688C15.8002 15.2654 15.6122 15.3375 15.47 15.47L12.75 18.19V5.81001L15.47 8.53001C15.6122 8.66249 15.8002 8.73462 15.9945 8.73119C16.1888 8.72776 16.3742 8.64905 16.5116 8.51163C16.649 8.37422 16.7277 8.18884 16.7312 7.99454C16.7346 7.80024 16.6625 7.61219 16.53 7.47001L12.53 3.47001C12.4606 3.40222 12.3792 3.34796 12.29 3.31001C12.1984 3.27038 12.0998 3.24994 12 3.24994C11.9002 3.24994 11.8015 3.27038 11.71 3.31001C11.6207 3.34796 11.5394 3.40222 11.47 3.47001L7.47 7.47001C7.32955 7.61064 7.25066 7.80126 7.25066 8.00001C7.25066 8.19876 7.32955 8.38939 7.47 8.53001C7.61062 8.67046 7.80125 8.74935 8 8.74935C8.19875 8.74935 8.38937 8.67046 8.53 8.53001L11.25 5.81001V18.19L8.53 15.47C8.38937 15.3296 8.19875 15.2507 8 15.2507C7.80125 15.2507 7.61062 15.3296 7.47 15.47C7.32955 15.6106 7.25066 15.8013 7.25066 16C7.25066 16.1988 7.32955 16.3894 7.47 16.53L11.47 20.53C11.5394 20.5978 11.6207 20.6521 11.71 20.69C11.8015 20.7296 11.9002 20.7501 12 20.7501C12.0998 20.7501 12.1984 20.7296 12.29 20.69Z"
                                            fill="#000000"></path>
                                    </g>
                                </svg>
                            </th>
                            <th @mouseenter="leadStore.hover_on = 'Tags'" @mouseleave="leadStore.hover_on = ''"
                                v-show="leadStore.categories[8].checked" @click="sortByField('Tags')">Tags
                                <svg style="vertical-align:top;"
                                     v-show="leadStore.sort_by=='Tags' && leadStore.sort_order=='asc'" width="20px"
                                     height="20px" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                    <g id="SVGRepo_iconCarrier">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                              d="M12.0809 7.28641L12.4345 6.93286L12.788 7.28641L17 11.4984L16.2929 12.2055L12.9345 8.84707V16.9999H11.9345V8.84707L8.57605 12.2055L7.86895 11.4984L12.0809 7.28641Z"
                                              fill="#000000"></path>
                                    </g>
                                </svg>
                                <svg style="vertical-align:top;"
                                     v-show="leadStore.sort_by=='Tags' && leadStore.sort_order=='desc'" width="20px"
                                     height="20px" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                    <g id="SVGRepo_iconCarrier">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                              d="M11.9191 16.7136L11.5655 17.0671L11.212 16.7136L7 12.5016L7.70711 11.7945L11.0655 15.1529V7.00009H12.0655V15.1529L15.4239 11.7945L16.1311 12.5016L11.9191 16.7136Z"
                                              fill="#000000"></path>
                                    </g>
                                </svg>
                                <svg viewBox="0 0 24 24" style="vertical-align:top;"
                                     v-show="leadStore.sort_by!='Tags' && leadStore.hover_on == 'Tags'" width="20px"
                                     height="20px" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                    <g id="SVGRepo_iconCarrier">
                                        <path
                                            d="M12.29 20.69C12.3792 20.6521 12.4606 20.5978 12.53 20.53L16.53 16.53C16.6625 16.3878 16.7346 16.1998 16.7312 16.0055C16.7277 15.8112 16.649 15.6258 16.5116 15.4884C16.3742 15.351 16.1888 15.2723 15.9945 15.2688C15.8002 15.2654 15.6122 15.3375 15.47 15.47L12.75 18.19V5.81001L15.47 8.53001C15.6122 8.66249 15.8002 8.73462 15.9945 8.73119C16.1888 8.72776 16.3742 8.64905 16.5116 8.51163C16.649 8.37422 16.7277 8.18884 16.7312 7.99454C16.7346 7.80024 16.6625 7.61219 16.53 7.47001L12.53 3.47001C12.4606 3.40222 12.3792 3.34796 12.29 3.31001C12.1984 3.27038 12.0998 3.24994 12 3.24994C11.9002 3.24994 11.8015 3.27038 11.71 3.31001C11.6207 3.34796 11.5394 3.40222 11.47 3.47001L7.47 7.47001C7.32955 7.61064 7.25066 7.80126 7.25066 8.00001C7.25066 8.19876 7.32955 8.38939 7.47 8.53001C7.61062 8.67046 7.80125 8.74935 8 8.74935C8.19875 8.74935 8.38937 8.67046 8.53 8.53001L11.25 5.81001V18.19L8.53 15.47C8.38937 15.3296 8.19875 15.2507 8 15.2507C7.80125 15.2507 7.61062 15.3296 7.47 15.47C7.32955 15.6106 7.25066 15.8013 7.25066 16C7.25066 16.1988 7.32955 16.3894 7.47 16.53L11.47 20.53C11.5394 20.5978 11.6207 20.6521 11.71 20.69C11.8015 20.7296 11.9002 20.7501 12 20.7501C12.0998 20.7501 12.1984 20.7296 12.29 20.69Z"
                                            fill="#000000"></path>
                                    </g>
                                </svg>
                            </th>
                            <th @mouseenter="leadStore.hover_on = 'Landing Page'" @mouseleave="leadStore.hover_on = ''"
                                v-show="leadStore.categories[9].checked" @click="sortByField('Landing Page')">Landing
                                Page
                                <svg style="vertical-align:top;"
                                     v-show="leadStore.sort_by=='Landing Page' && leadStore.sort_order=='asc'"
                                     width="20px" height="20px" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                    <g id="SVGRepo_iconCarrier">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                              d="M12.0809 7.28641L12.4345 6.93286L12.788 7.28641L17 11.4984L16.2929 12.2055L12.9345 8.84707V16.9999H11.9345V8.84707L8.57605 12.2055L7.86895 11.4984L12.0809 7.28641Z"
                                              fill="#000000"></path>
                                    </g>
                                </svg>
                                <svg style="vertical-align:top;"
                                     v-show="leadStore.sort_by=='Landing Page' && leadStore.sort_order=='desc'"
                                     width="20px" height="20px" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                    <g id="SVGRepo_iconCarrier">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                              d="M11.9191 16.7136L11.5655 17.0671L11.212 16.7136L7 12.5016L7.70711 11.7945L11.0655 15.1529V7.00009H12.0655V15.1529L15.4239 11.7945L16.1311 12.5016L11.9191 16.7136Z"
                                              fill="#000000"></path>
                                    </g>
                                </svg>
                                <svg viewBox="0 0 24 24" style="vertical-align:top;"
                                     v-show="leadStore.sort_by!='Landing Page' && leadStore.hover_on == 'Landing Page'"
                                     width="20px" height="20px" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                    <g id="SVGRepo_iconCarrier">
                                        <path
                                            d="M12.29 20.69C12.3792 20.6521 12.4606 20.5978 12.53 20.53L16.53 16.53C16.6625 16.3878 16.7346 16.1998 16.7312 16.0055C16.7277 15.8112 16.649 15.6258 16.5116 15.4884C16.3742 15.351 16.1888 15.2723 15.9945 15.2688C15.8002 15.2654 15.6122 15.3375 15.47 15.47L12.75 18.19V5.81001L15.47 8.53001C15.6122 8.66249 15.8002 8.73462 15.9945 8.73119C16.1888 8.72776 16.3742 8.64905 16.5116 8.51163C16.649 8.37422 16.7277 8.18884 16.7312 7.99454C16.7346 7.80024 16.6625 7.61219 16.53 7.47001L12.53 3.47001C12.4606 3.40222 12.3792 3.34796 12.29 3.31001C12.1984 3.27038 12.0998 3.24994 12 3.24994C11.9002 3.24994 11.8015 3.27038 11.71 3.31001C11.6207 3.34796 11.5394 3.40222 11.47 3.47001L7.47 7.47001C7.32955 7.61064 7.25066 7.80126 7.25066 8.00001C7.25066 8.19876 7.32955 8.38939 7.47 8.53001C7.61062 8.67046 7.80125 8.74935 8 8.74935C8.19875 8.74935 8.38937 8.67046 8.53 8.53001L11.25 5.81001V18.19L8.53 15.47C8.38937 15.3296 8.19875 15.2507 8 15.2507C7.80125 15.2507 7.61062 15.3296 7.47 15.47C7.32955 15.6106 7.25066 15.8013 7.25066 16C7.25066 16.1988 7.32955 16.3894 7.47 16.53L11.47 20.53C11.5394 20.5978 11.6207 20.6521 11.71 20.69C11.8015 20.7296 11.9002 20.7501 12 20.7501C12.0998 20.7501 12.1984 20.7296 12.29 20.69Z"
                                            fill="#000000"></path>
                                    </g>
                                </svg>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <div v-if="leadStore.leads.length>0">
                            <tr :key="lead.id" v-for="(lead, index) in leadStore.leads">
                                <td @click="viewProfile(lead.id)" v-if="leadStore.categories[0].checked"
                                    class="d-none d-lg-block"><span
                                    style="text-wrap:balance">{{ lead.first_name + ' ' + (lead.last_name != null ? lead.last_name : '') }}</span>
                                </td>
                                <td @click="viewProfile(lead.id)" v-if="leadStore.categories[1].checked"
                                    class="d-none d-lg-block"><span>{{ (lead.email) ? lead.email : '' }}</span></td>
                                <td @click="viewProfile(lead.id)" v-if="leadStore.categories[2].checked"
                                    class="source-label"><span>{{ (lead.source_id) ? lead.source_id.name : '' }}</span>
                                </td>
                                <td @click="viewProfile(lead.id)" v-if="leadStore.categories[3].checked"
                                    class="phone-label"><span>{{ lead.phone_form }}</span></td>
                                <td @click="viewProfile(lead.id)" v-if="leadStore.categories[4].checked"
                                    class="d-none d-lg-block"><span>{{ lead.phone }}</span></td>
                                <td @click="viewProfile(lead.id)" v-if="leadStore.categories[5].checked"
                                    class="d-none d-lg-block"><span>{{ lead.dob }}</span></td>
                                <td @click="viewProfile(lead.id)" v-if="leadStore.categories[6].checked"
                                    style="padding: 15px;"><span><span class="d-lg-none"></span>{{ lead.created_at }}</span></td>
                                <td @click="viewProfile(lead.id)"
                                    v-if="leadStore.categories[7].checked"
                                    class="d-none d-lg-block"
                                    :style="{
											'border': lead.lead_score ? '1px solid ' + getBackgroundColor(lead.lead_score) : null,
        									'background': lead.lead_score ? getBackgroundColor(lead.lead_score) : null,
											'width': '65px',
											'border-radius': '50px',
											'text-align': 'center',
											'margin':'10px',
											'height':'35px',
											'padding':'4px',
											'color': (lead.lead_score === null || lead.lead_score === '') ? 'red' : 'black', // Change text color to red when lead_score is empty or null
										}">
                                    <span :style="{'color':'#FFF'}">{{ lead.lead_score }}</span>
                                </td>
                                <td @click="viewProfile(lead.id)" v-if="leadStore.categories[8].checked"
                                    class="d-none d-lg-block"><span>{{ lead.tagName }}</span></td>
                                <td @click="viewProfile(lead.id)" v-if="leadStore.categories[9].checked"
                                    class="d-none d-lg-block"><span>{{ lead.landing_page_url }}</span></td>
                                <td @click="viewProfile(lead.id)" class="d-lg-none lead-name">
                                    <span>{{ lead.first_name + ' ' + lead.last_name }}</span></td>
                                <td class="hover-td">
                                    <!-- <div class="move-box" v-if="lead.deleted_at==null">
                                        <a class="move-ico" data-title="Move" @click="toggleShowMoveIndex(index)">
                                            <svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M14 28C21.732 28 28 21.732 28 14C28 6.26801 21.732 0 14 0C6.26801 0 0 6.26801 0 14C0 21.732 6.26801 28 14 28ZM14 26.5263C7.08191 26.5263 1.47368 20.9181 1.47368 14C1.47368 7.08191 7.08191 1.47368 14 1.47368C20.9181 1.47368 26.5263 7.08191 26.5263 14C26.5263 20.9181 20.9181 26.5263 14 26.5263ZM19.5895 13.2632H6.63158C6.22463 13.2632 5.89474 13.5931 5.89474 14C5.89474 14.4069 6.22463 14.7368 6.63158 14.7368H19.5895L14.9527 19.3737C14.6649 19.6615 14.6649 20.128 14.9527 20.4158C15.2404 20.7035 15.707 20.7035 15.9947 20.4158L21.8894 14.521C22.1772 14.2333 22.1772 13.7667 21.8894 13.479L15.9947 7.58424C15.707 7.29648 15.2404 7.29648 14.9527 7.58424C14.6649 7.87199 14.6649 8.33854 14.9527 8.62629L19.5895 13.2632Z"/>
                                            </svg>
                                        </a>
                                        <div class="move-stage-box"  v-show="showMoveIndex==index">
                                            <h6>Move Stage</h6>
                                            <p>Select a stage to move lead:</p>
                                            <div class="form-group">
                                                <select class="js-example-basic-single move-stage form-select" v-model="status_id">
                                                    <option disabled selected value="null">Select Stage</option>
                                                    <option :selected="lead.status_id.id==1" value="1">New Lead</option>
                                                    <option :selected="lead.status_id.id==5" value="5">In Discussion</option>
                                                    <option :selected="lead.status_id.id==2" value="2">Attempt 1</option>
                                                    <option :selected="lead.status_id.id==3" value="3">Attempt 2</option>
                                                    <option :selected="lead.status_id.id==4" value="4">Attempt 3 Plus</option>
                                                    <option :selected="lead.status_id.id==17" value="17">Nurturing</option>
                                                    <option :selected="lead.status_id.id==6" value="6">Practice Follow-Up</option>
                                                    <option :selected="lead.status_id.id==9" value="9">Not Interested</option>
                                                    <option :selected="lead.status_id.id==16" value="16">Existing Prient</option>
                                                </select>
                                            </div>
                                            <div class="d-none d-lg-block text-end">
                                                <button class="btn" @click="moveLead(lead.id)">Move</button>
                                            </div>
                                        </div>
                                        <div class="move-stage-box" v-show="status_id==9 && showLostReasonIndex==index">
                                            <h6>Lost Reason</h6>
                                            <p>Select a lost reason:</p>
                                            <div class="form-group">
                                                <select class="js-example-basic-single move-stage form-select" v-model="reason">
                                                    <option disabled selected value="null">Select lost reason</option>
                                                    <option value="Price shopping">Pricing Shopping</option>
                                                    <option value="Office is too far">Too Far</option>
                                                    <option value="Medicaid or Medicare patient">Medicare / Medicaid</option>
                                                    <option value="Too expensive/couldn't afford it">Too Expensive</option>
                                                    <option value="Call disconnected, hung up">Spam / Wrong Number / Hung Up</option>
                                                    <option value="Current Patient">Current Patient</option>
                                                    <option value="Spanish Speaking">Spanish Speaking</option>
                                                    <option value="Insurance / Senior Grants">Insurance / Senior Grants</option>
                                                    <option value="No Credit Card">No Credit Card</option>
                                                    <option value="Duplicate Lead" selected="">Duplicate Lead</option>
                                                    <option value="STOP">STOP</option>
                                                    <option value="General Dentistry">General Dentistry</option>
                                                    <option value="Does not provide service">Does not provide service</option>
                                                    <option value="No Reason">No Reason</option>
                                                    <option value="Think about it">Think about it</option>
                                                    <option value="Stated Not Interested">Stated Not Interested</option>
                                                    <option value="Credit Challenged">Credit Challenged</option>
                                                    <option value="Refuse to give CC">Refuse to give CC</option>
                                                    <option value="No cosigner">No cosigner</option>
                                                    <option value="Veneers/Braces/Cosmetic">Veneers/Braces/Cosmetic</option>
                                                    <option value="Language Barrier">Language Barrier</option>
                                                    <option value="Spam">Spam</option>
                                                    <option value="Wrong Number">Wrong Number</option>
                                                    <option value="Hung Up">Hung Up</option>
                                                    <option value="No Way to Contact">No Way to Contact</option>
                                                    <option value="Fax Number">Fax Number</option>
                                                    <option value="Vendor">Vendor</option>
                                                    <option value="Another Dentist Office">Another Dentist Office</option>
                                                    <option value="Robo Call">Robo Call</option>
                                                    <option value="False Advertising">False Advertising</option>
                                                    <option value="Senior Grants">Senior Grants</option>
                                                    <option value="Insurance">Insurance</option>
                                                    <option value="No Appointment Available">No Appointment Available</option>
                                                    <option value="Other">Other</option>
                                                </select>
                                            </div>
                                            <div class="d-none d-lg-block text-end">
                                                <button class="btn" @click="moveLead(lead.id)">Move</button>
                                            </div>
                                        </div>
                                    </div> -->
                                    <!-- <a role="button" class="folder-ico move-ico" data-title="View Profile" v-if="lead.deleted_at==null" @click="viewProfile(lead.id)">
                                        <svg version="1.1" x="0px" y="0px" viewBox="0 0 25 25" style="enable-background:new 0 0 25 25;">
                                            <g>
                                                <path d="M12.5,11.5c1.1,0,2.1-0.4,2.8-1.1c0.7-0.7,1.1-1.7,1.1-2.8c0-1.1-0.4-2.1-1.1-2.8s-1.7-1.1-2.8-1.1S10.4,4,9.7,4.8C9,5.5,8.6,6.4,8.6,7.6c0,1.1,0.4,2.1,1.1,2.8C10.4,11.1,11.4,11.5,12.5,11.5z M10.5,5.5c0.6-0.6,1.2-0.8,2-0.8s1.5,0.3,2,0.8c0.6,0.6,0.8,1.2,0.8,2c0,0.8-0.3,1.5-0.8,2c-0.6,0.6-1.2,0.8-2,0.8s-1.5-0.3-2-0.8c-0.6-0.6-0.8-1.2-0.8-2C9.6,6.8,9.9,6.1,10.5,5.5z"/>
                                                <path d="M21.5,17.8c-0.3-0.5-0.8-0.8-1.4-1.1c-1.3-0.6-2.6-1-3.9-1.4c-1.3-0.3-2.5-0.5-3.7-0.5c-1.2,0-2.5,0.2-3.7,0.5c-1.3,0.3-2.6,0.8-3.9,1.3c-0.6,0.3-1,0.6-1.3,1.1S3,18.8,3,19.4v2h19v-2C22,18.8,21.8,18.2,21.5,17.8z M21,20.3H4v-0.9c0-0.3,0.1-0.7,0.3-1c0.2-0.3,0.5-0.6,1-0.8c1.2-0.6,2.4-1,3.6-1.3c1.2-0.3,2.4-0.4,3.6-0.4s2.4,0.1,3.6,0.4c1.2,0.3,2.4,0.7,3.6,1.3c0.4,0.2,0.7,0.5,1,0.8c0.2,0.3,0.3,0.7,0.3,1V20.3z"/>
                                            </g>
                                        </svg>
                                    </a> -->
                                    <!-- <a href="#" class="folder-ico move-ico" data-title="Book Consultation" v-if="lead.deleted_at==null"  :data-bs-lead-id="lead.id" :data-bs-index="index" data-bs-toggle="modal" data-bs-target="#BookConsultationModalLead">
                                        <svg version="1.1" x="0px" y="0px" viewBox="0 0 24 24" style="enable-background:new 0 0 24 24;">
                                            <path d="M19,3h-2V2c0-0.6-0.4-1-1-1s-1,0.4-1,1v1H9V2c0-0.6-0.4-1-1-1S7,1.4,7,2v1H5C3.3,3,2,4.3,2,6v14c0,1.7,1.3,3,3,3h14c1.7,0,3-1.3,3-3V6C22,4.3,20.7,3,19,3z M5,5h2v1c0,0.6,0.4,1,1,1s1-0.4,1-1V5h6v1c0,0.6,0.4,1,1,1s1-0.4,1-1V5h2c0.6,0,1,0.4,1,1v3H4V6C4,5.4,4.4,5,5,5z M19,21H5c-0.6,0-1-0.4-1-1v-9h16v9C20,20.6,19.6,21,19,21z"/>
                                        </svg>
                                    </a> -->
                                    <a href="#" class="folder-ico move-ico" data-title="Restore Lead"
                                       @click="restoreLead(lead.id)" v-if="lead.deleted_at!=null">
                                        <svg height="40" viewBox="0 0 50 50" width="40"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <path d="M0 0h48v48h-48z" fill="none"/>
                                            <path
                                                d="M25.99 6c-9.95 0-17.99 8.06-17.99 18h-6l7.79 7.79.14.29 8.07-8.08h-6c0-7.73 6.27-14 14-14s14 6.27 14 14-6.27 14-14 14c-3.87 0-7.36-1.58-9.89-4.11l-2.83 2.83c3.25 3.26 7.74 5.28 12.71 5.28 9.95 0 18.01-8.06 18.01-18s-8.06-18-18.01-18zm-1.99 10v10l8.56 5.08 1.44-2.43-7-4.15v-8.5h-3z"/>
                                        </svg>
                                    </a>
                                    <a href="#" class="folder-ico move-ico" data-title="Quick Edit"
                                       @click="toggleSidebar(lead.id)">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="#228BE6" viewBox="0 0 30 30"
                                             width="30px" height="30px">
                                            <path
                                                d="M 22.828125 3 C 22.316375 3 21.804562 3.1954375 21.414062 3.5859375 L 19 6 L 24 11 L 26.414062 8.5859375 C 27.195062 7.8049375 27.195062 6.5388125 26.414062 5.7578125 L 24.242188 3.5859375 C 23.851688 3.1954375 23.339875 3 22.828125 3 z M 17 8 L 5.2597656 19.740234 C 5.2597656 19.740234 6.1775313 19.658 6.5195312 20 C 6.8615312 20.342 6.58 22.58 7 23 C 7.42 23.42 9.6438906 23.124359 9.9628906 23.443359 C 10.281891 23.762359 10.259766 24.740234 10.259766 24.740234 L 22 13 L 17 8 z M 4 23 L 3.0566406 25.671875 A 1 1 0 0 0 3 26 A 1 1 0 0 0 4 27 A 1 1 0 0 0 4.328125 26.943359 A 1 1 0 0 0 4.3378906 26.939453 L 4.3632812 26.931641 A 1 1 0 0 0 4.3691406 26.927734 L 7 26 L 5.5 24.5 L 4 23 z"/>
                                        </svg>
                                    </a>

                                </td>
                            </tr>
                        </div>
                        <div v-else>
                            <h6 class="text-center">Currently there are no Leads to display!</h6>
                        </div>
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-between pt-3">
                        <a v-if="leadStore.page > 1" href="#" class="btn btn-primary" @click.prevent="prev">Prev</a>
                        <div class="text-center" style="width:100%" v-if="leadStore.leads.length>0"><span
                            class="badge bg-primary" style="font-size: 16px; margin-top:10px;">{{
                                leadStore.page + '/' + leadStore.last_page
                            }}</span></div>
                        <a v-if="leadStore.page<leadStore.last_page" href="#" class="btn btn-primary"
                           @click.prevent="next">Next</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="BookConsultationModalLead" tabindex="-1" aria-labelledby="BookConsultationModalLead"
         aria-hidden="true">
        <div class="modal-dialog add-lead-model modal-dialog-centered">
            <div class="modal-content p-md-3 bg-light-gray">
                <div class="modal-header">
                    <h3 class="modal-title" id="staticBackdropLabel">Book Consultation</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <svg width="20" height="14" viewBox="0 0 20 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                  d="M19.4411 6.37503H2.04859L7.25172 1.19191C7.49609 0.947534 7.49609 0.551909 7.25172 0.308159C7.00734 0.0637842 6.61172 0.0637842 6.36797 0.308159L0.180469 6.49503C-0.0601562 6.73566 -0.0601562 7.13816 0.180469 7.37878L6.36797 13.5663C6.61234 13.8107 7.00797 13.8107 7.25172 13.5663C7.49609 13.3219 7.49609 12.9263 7.25172 12.6825L2.04859 7.62503H19.4411C19.7861 7.62503 20.0661 7.34503 20.0661 7.00003C20.0661 6.65503 19.7861 6.37503 19.4411 6.37503Z"
                                  fill="#514F5F"></path>
                        </svg>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="modal-form">
                        <div class="consultation-container" style="min-height: 220px; left: 70px; position: relative;">
                            <input type="hidden" id="book-datetimepicker-lead" v-model="consultation_booked_date"
                                   style="position:absolute;"/>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" @click="update">Save</button>
                </div>
            </div>
        </div>
    </div>
    <LeadForm></LeadForm>
</template>
<script>

import {useAlertStore} from '../../stores/alert';
import {useLeadStore} from '../../stores/lead';
import LeadForm from './utils/LeadForm.vue';
import CKEditor from '@ckeditor/ckeditor5-vue';
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
import UploadAdapter from '../../stores/UploadAdapter';
import {useAppointmentStore} from "../../stores/appointment";

export default {
    setup() {
        const leadStore = useLeadStore();

        const alertStore = useAlertStore();

        const appointmentStore = useAppointmentStore()

        return {leadStore, alertStore, appointmentStore};
    },
    data() {
        return {
            showMoveIndex: null,
            showLostReasonIndex: null,
            status_id: null,
            reason: null,
            consultation_booked_date: null,
            leadId: null,
            showCategoryFilter: true,
            showLostReasonError: false,
            editor: ClassicEditor,
            editorConfig: {
                extraPlugins: [this.uploader],
            },
            isSidebarVisible: false, // Initially set to false
            selectedLead: {first_name: '', last_name: '', consultation_booked_date: null, status_id: null, Notes: []},
            latestNote: '' // Variable to store the latest note
        }
    },
    components: {
        LeadForm,
        ckeditor: CKEditor.component,
    },
    computed: {
        checkedCategories: function () {
            let cats = [];
            this.leadStore.categories.forEach(function (category) {
                if (category.checked) {
                    cats.push(category.name);
                }
            });
            return cats;
        },
        latestNoteContent() {
            if (this.selectedLead.Notes && this.selectedLead.Notes.length > 0) {
                // Sort the Notes array by date in descending order
                const sortedNotes = this.selectedLead.Notes.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));

                // Get the latest note content
                this.selectedLead.note = sortedNotes[0].note; // Assuming 'note' is the field containing the note content

                // Remove <p> tags from the latest note content
                this.selectedLead.note = this.selectedLead.note.replace(/<p>/g, '').replace(/<\/p>/g, '');

                return this.selectedLead.note;
            }
            return '';
        }
    },
    updated() {
        this.filterCheckedWidth();
    },
    watch: {
        'selectedLead.consultation_booked_date': function (newVal, oldVal) {
            if (newVal !== oldVal) {
                this.updateDateTimePickerValue(newVal);
            }
        },
        'selectedLead.Notes': {
            handler() {
                this.selectedLead.note = this.latestNoteContent;
            },
            deep: true,
            immediate: true
        }
    },
    mounted() {
        let _self = this;

        $('#mailLink').removeClass('active');
        $('#profileLink').removeClass('show');
        $('#homeLink').addClass('active');

        this.getStageCounts();
        this.getLeads();
        this.appointmentStore.getAvailableTimes(moment().add(1, 'day').format('MM-DD-YYYY'));

        if (localStorage.getItem('categories') != null) {
            this.leadStore.categories = JSON.parse(localStorage.getItem('categories'));
        }

        this.filterCheckedWidth();

        // Search

        $(window).on('resize', function () {
            if (_self.screencheck(991)) {
                $(document).on('click touchstart', function (e) {
                    if ($(e.target).parent('.lead-search').length === 0) {
                        $('.lead-search-box .lead-search').slideUp(200);
                        $('.lead-search-box').removeClass('search-open');
                        $('#wrapper').removeClass('sub-popup-active');
                    }
                });
            }
        }).resize();

        $('.lead-search-ico').click(function () {
            $('.lead-search-box .lead-search').not($(this).next('.lead-search')).slideUp(200);
            $(this).next('.lead-search').slideToggle(200);
            $(this).parents('.lead-search-box').toggleClass('search-open');
            $(this).parents('#wrapper').toggleClass('sub-popup-active');
            return false;
        });
        $('.lead-search').on('click touchstart', function (event) {
            event.stopPropagation();
        });

        // Filter

        $('.filter-ico').click(function () {
            $('.filter-icons .lead-filterBy-box').not($(this).next('.lead-filterBy-box')).slideUp(200);
            $(this).next('.lead-filterBy-box').slideToggle(200);
            $(this).parents('.filter-icons').toggleClass('filter-open');
            $(this).parents('#wrapper').toggleClass('sub-popup-active');
            return false;
        });
        $('.lead-filterBy-box').on('click touchstart', function (event) {
            event.stopPropagation();
        });
        $(document).on('click touchstart', function (e) {
            if ($(e.target).parent('.lead-filterBy-box').length === 0) {
                $('.lead-filterBy-box').slideUp(200);
                $('.filter-icons').removeClass('filter-open');
            }
        });
        $('.filterBy-back-btn, .filterBy-save-btn').click(function () {
            $('.lead-filterBy-box').slideUp(200);
            $('.filter-icons').removeClass('filter-open');
            return false;
        });

        $('.filterBy-viewall-btn').click(function () {
            $(this).parents('.lead-filterBy-box').addClass('filter-viewall');
            return false;
        });
        $('.filterBy-viewall-save').click(function () {
            $(this).parents('.lead-filterBy-box').removeClass('filter-viewall');
            return false;
        });

        let allowedTimes = [
            'AM 07:00',
            'AM 08:00',
            'AM 09:00',
            'AM 10:00',
            'AM 11:00',
            'PM 12:00',
            'PM 13:00',
            'PM 14:00',
            'PM 15:00',
            'PM 16:00',
            'PM 17:00',
            'PM 18:00',
            'PM 19:00',
            'PM 20:00'
        ];

        var bookConsultationModal = document.getElementById('BookConsultationModalLead');

        bookConsultationModal.addEventListener('show.bs.modal', function (event) {

            var button = event.relatedTarget;

            _self.leadId = button.getAttribute('data-bs-lead-id');
            var index = button.getAttribute('data-bs-index');

            $('#consultation-container').empty();

            _self.consultation_booked_date = (_self.leadStore.leads[index] && _self.leadStore.leads[index].consultation_booked_date) ?? null;

            $.datetimepicker.setDateFormatter('moment');

            var priorDate = moment().subtract(30, "days");

            $('#book-datetimepicker-lead').datetimepicker({
                value: _self.consultation_booked_date ?? null,
                format: 'MM/DD/YYYY hh:mm A',
                onChangeDateTime: function (ct, $i) {
                    _self.consultation_booked_date = $('#book-datetimepicker-lead').val();
                    let _arr = _self.consultation_booked_date.split(' ');
                    _arr[1] = _arr[1] + ':00';
                    _self.consultation_booked_date = _arr[0] + ' ' + _arr[1] + ' ' + _arr[2];
                },
                inline: true,
                hours12: false,
                ampm: true, // FOR AM/PM FORMAT
                formatTime: 'A hh:mm',
                allowTimes: allowedTimes,
            });
        });

        this.initDateTimePicker();
        this.latestNote = this.latestNoteContent;


    },
    methods: {
        getStageCounts() {
            this.leadStore.count();
        },
        searchLeads() {
            if (this.leadStore.query.length >= 3 || this.leadStore.query.length == 0) {
                this.leadStore.list();
            }
        },
        getLeads() {
            this.leadStore.list();
        },
        toggleDeletedLeads() {
            this.leadStore.deletedLeads = !this.leadStore.deletedLeads;
        },
        toggleShowMoveIndex(index) {
            this.showMoveIndex = index;
            this.showLostReasonIndex = index;
        },
        getStageCount(stage) {
            if (stage.id == 1) {
                return {name: 'New Lead', TotalCount: stage.TotalCount}
            }
        },
        filterByStage(status_id) {
            this.leadStore.status_id = status_id;
            this.leadStore.page = 1;
            this.leadStore.list();
        },
        moveLead(leadid) {
            if (this.status_id != null) {
                if (this.status_id == 9 && !this.reason) {
                    return null;
                }
                this.leadStore.move(this.status_id, leadid, this.reason).then((response) => {
                    $(".move-stage").modal('hide');
                    this.leadStore.count();
                    this.leadStore.list();
                    this.status_id = null;
                });
            }
        },
        filterCheckedWidth() {
            let catCount = this.checkedCategories.length;
            $('.lead-table-box table tr').css('grid-template-columns', 'repeat(' + catCount + ', 1fr)');
        },
        saveCategories() {
            localStorage.setItem('categories', JSON.stringify(this.leadStore.categories));
            $('.lead-filterBy-box').hide();
            this.alertStore.success = true;
            this.alertStore.message = 'Column preferences have been saved successfully!';
        },
        viewProfile(id) {
            localStorage.setItem('last_path', '/crtx/patient-profile/' + id);
            window.open('/crtx/patient-profile/' + id, '_blank');
        },
        update() {
            this.leadStore.updateConsultationBookedDate(this.leadId, this.consultation_booked_date);
            $("#BookConsultationModalLead").modal('hide');
            this.consultation_booked_date = null;
        },
        sortByField(field) {
            if (this.leadStore.sort_by == field) {
                if (this.leadStore.sort_order == 'asc') {
                    this.leadStore.sort_order = 'desc';
                } else {
                    this.leadStore.sort_order = 'asc';
                }
            } else {
                this.leadStore.sort_by = field;
                this.leadStore.sort_order = 'desc';
            }
            this.leadStore.list();
        },
        prev() {
            if (this.leadStore.page > 1) {
                this.leadStore.page--;
                this.leadStore.list();
            }
        },
        next() {
            this.leadStore.page++;
            this.leadStore.list();
        },
        screencheck(mediasize) {
            if (typeof window.matchMedia !== "undefined") {
                var screensize = window.matchMedia("(max-width:" + mediasize + "px)");
                if (screensize.matches) {
                    return true;
                } else {
                    return false;
                }
            } else { // for IE9 and lower browser
                if ($winW() <= mediasize) {
                    return true;
                } else {
                    return false;
                }
            }
        },
        getBackgroundColor(leadScore) {
            if (leadScore >= 900) {
                return '#008421'; // High
            } else if (leadScore >= 700) {
                return '#4EAB52'; // Good
            } else if (leadScore >= 500) {
                return '#FFCC00'; // Medium
            } else {
                return '#FF0F0F'; // Low
            }
        },
        recordsPerPageChange() {
            this.leadStore.list();
        },
        deletedLeadsToggle() {
            this.leadStore.count();
            this.leadStore.list();
        },
        restoreLead(leadId) {
            this.leadStore.restore(leadId)
        },
        toggleSidebar(leadId) {
            //this.isSidebarVisible = !this.isSidebarVisible; // Toggle sidebar visibility
            this.selectedLeadId = leadId;
            this.selectedLead = this.leadStore.leads.find(lead => lead.id === leadId);
            this.isSidebarVisible = true;
        },
        closeSidebar() {
            this.isSidebarVisible = false; // Close the sidebar
        },
        quickupdate(leadId) {
            if (this.leadStore.leads.status_id == 9 && !this.leadStore.leads.reason) {
                this.showLostReasonError = true;
                this.profile_editing = true;
                this.showMoreProfile = true;
                const element = document.getElementById("lost-reason");
                element.focus();
                element.scrollIntoView();
                return null;
            }

            let foundLead = this.leadStore.leads.find(lead => lead.id === leadId);

            if (foundLead) {
                // Pass the found lead to the quickupdatesidebar function
                this.leadStore.quickupdatesidebar(foundLead, this.leadStore.leads);
            }
            //this.profile_editing = false;
            this.isSidebarVisible = false; // Close the sidebar
        },
        initDateTimePicker() {
            let _self = this;

            $.datetimepicker.setDateFormatter('moment');
            var priorDate = moment().subtract(30, "days");
            let allowedTimes = [
                'AM 07:00',
                'AM 08:00',
                'AM 09:00',
                'AM 10:00',
                'AM 11:00',
                'PM 12:00',
                'PM 13:00',
                'PM 14:00',
                'PM 15:00',
                'PM 16:00',
                'PM 17:00',
                'PM 18:00',
                'PM 19:00',
                'PM 20:00'
            ];


            $('#bookDate').datetimepicker({
                value: this.selectedLead.consultation_booked_date || null,
                format: 'MM/DD/YYYY hh:mm A',
                onChangeDateTime: (ct, $i) => {
                    this.selectedLead.consultation_booked_date = $('#bookDate').val();
                },
                onSelectDate:function(ct, $i){
                    let selectedDate = moment(ct).format('MM/DD/YYYY');
                    $('#bookDate').datetimepicker({
                        allowTimes: _self.appointmentStore.allowedDateTimes[selectedDate],
                        formatTime:'hh:mm A',
                        timepicker:true,
                    });
                },
                onChangeMonth:function(ct, $i){
                    let startDate = moment(ct).format('MM-01-YYYY');
                    if(moment(startDate).isAfter(moment())){
                        _self.appointmentStore.getAvailableTimes(startDate);
                    }
                },
                timepicker: false,
                defaultSelect:false,
                hours12: false,
                ampm: true,
            });
        },
        updateDateTimePickerValue(newValue) {
            $('#bookDate').datetimepicker({
                value: newValue
            });
        },
        uploader(editor) {
            editor.plugins.get('FileRepository').createUploadAdapter = (loader) => {
                return new UploadAdapter(loader);
            };
        },
    }
}
</script>

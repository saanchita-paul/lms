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
                        <span class="d-block text-danger mt-1"
                              v-if="selectedLead.status_id == 12 && !selectedLead.consultation_booked_date">Please add Consultation Booked Date!</span>
                    </div>
                </div>
                <div class="form-group">
                    <label>Stage</label>
                    <select class="js-example-basic-single form-select" v-model="selectedLead.status_id" @change="stageChanged">
                        <option disabled selected value="null">Select Stage</option>
                        <option :selected="selectedLead.status_id==1" value="1">New Lead</option>
                        <option :selected="selectedLead.status_id==5" value="5">In Discussion</option>
                        <option :selected="selectedLead.status_id==17" value="17">Nurturing</option>
                        <option :selected="selectedLead.status_id==6" value="6">Practice Follow-Up</option>
                        <option :selected="selectedLead.status_id==12" value="12">Consultation Booked</option>
                        <option :selected="selectedLead.status_id==13" value="13">No Showed</option>
                        <option :selected="selectedLead.status_id==14" value="14">Cancellation</option>
                        <option :selected="selectedLead.status_id==15" value="15">Pending Acceptance</option>
                        <option :selected="selectedLead.status_id==''" value="">Treatment Sold</option>
                        <option :selected="selectedLead.status_id==18" value="18">Treatment Completed</option>
                    </select>
                </div>
                <div class="form-group" v-if="selectedLead.consultation_booked_date!=null">
                    <label>Consultation Follow Up</label>
                    <select class="form-control form-select" v-model="selectedLead.deal_status">
                        <option value="" selected disabled>Please Select</option>
                        <option value="Needs Financing or Gathering Money">Needs Financing or Gathering Money</option>
                        <option value="Talk to Spouse, Partner or Family Member">Talk to Spouse, Partner or Family
                            Member
                        </option>
                        <option value="Wants to Compare Prices">Wants to Compare Prices</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Treatments Sold?</label>
                    <select class="form-control form-select" id="treatment_sold" v-model="selectedLead.won_lost"
                            @change="treatmentSoldChanged()">
                        <option value="" selected disabled>Please Select</option>
                        <option :selected="selectedLead.won_lost == 'Won'" value="Won">Yes</option>
                        <option value="Lost" :selected="selectedLead.won_lost == 'Lost'">No</option>
                    </select>
                </div>
                <div class="form-group" v-show="selectedLead.won_lost=='Won'">
                    <label>Sold on Date</label>
                    <div class="datepicker-box">
                        <input type="text" class="datepicker form-control" id="treatment_sold_date"
                               placeholder="MM/DD/YYYY" v-model="selectedLead.won_lost_date" readonly aria-readonly="true">
                        <span class="date-ico">
                        <svg version="1.1" x="0px" y="0px" viewBox="0 0 24 24" style="enable-background:new 0 0 24 24;">
                            <path
                                d="M19,3h-2V2c0-0.6-0.4-1-1-1s-1,0.4-1,1v1H9V2c0-0.6-0.4-1-1-1S7,1.4,7,2v1H5C3.3,3,2,4.3,2,6v14c0,1.7,1.3,3,3,3h14c1.7,0,3-1.3,3-3V6C22,4.3,20.7,3,19,3z M5,5h2v1c0,0.6,0.4,1,1,1s1-0.4,1-1V5h6v1c0,0.6,0.4,1,1,1s1-0.4,1-1V5h2c0.6,0,1,0.4,1,1v3H4V6C4,5.4,4.4,5,5,5z M19,21H5c-0.6,0-1-0.4-1-1v-9h16v9C20,20.6,19.6,21,19,21z"/>
                        </svg>
                    </span>
                    </div>
                </div>
                <div class="form-group">
                    <label>Treatment Cost</label>
                    <div class="input-group">
                        <span class="input-group-text" v-show="selectedLead.value">$</span>
                        <input type="text" class="form-control" v-model="selectedLead.value">
                    </div>
                </div>
                <div class="form-group">
                    <label>Note</label>
                    <ckeditor :editor="editor" v-model="selectedLead.note" :config="editorConfig"></ckeditor>
                </div>
                <button type="button" :disabled="selectedLead.status_id == 12 && !selectedLead.consultation_booked_date"
                        class="btn btn-primary px-4" @click="quickupdate(selectedLead.id)">Save Changes
                </button>
            </div>
        </div>
    </div>

    <div id="main" class="bg-light-gray">
        <div class="container-fluid p-0">
            <div class="px-0 py-0 mt-4">
                <div class="leads-step">
                    <ul v-if="consultationStore.stageCounts.length>0">
                        <li>
                            <a style="width:160px;" href="#" class="leads-step-box"
                               :class="[consultationStore.status_id==12 ? 'step-active' : '']"
                               @click="filterByStage(12)"> <!--step-active -->
                                <span>{{ consultationStore.stageCounts[0].TotalCount }}</span>
                                <p>Consultation Booked</p>
                            </a>
                        </li>
                        <li>
                            <a style="width:160px;" href="#" class="leads-step-box"
                               :class="[consultationStore.status_id==13 ? 'step-active' : '']"
                               @click="filterByStage(13)">
                                <span>{{ consultationStore.stageCounts[1].TotalCount }}</span>
                                <p>No Showed</p>
                            </a>
                        </li>
                        <li>
                            <a style="width:160px;" href="#" class="leads-step-box"
                               :class="[consultationStore.status_id==14 ? 'step-active' : '']"
                               @click="filterByStage(14)">
                                <span>{{ consultationStore.stageCounts[2].TotalCount }}</span>
                                <p>Cancellation</p>
                            </a>
                        </li>
                        <li>
                            <a style="width:160px;" href="#" class="leads-step-box"
                               :class="[consultationStore.status_id==15 ? 'step-active' : '']"
                               @click="filterByStage(15)">
                                <span>{{ consultationStore.stageCounts[3].TotalCount }}</span>
                                <p>Pending Acceptance</p>
                            </a>
                        </li>
                        <li>
                            <a style="width:160px;" href="#" class="leads-step-box"
                               :class="[consultationStore.status_id==0 ? 'step-active' : '']" @click="filterByStage(0)">
                                <span>{{ consultationStore.stageCounts[4].TotalCount }}</span>
                                <p>Treatments Sold</p>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="my-4 py-lg-4 my-lg-0 d-flex align-items-center lead-filter-serch-box">
                    <!-- <div class="plus-icons">
                        <a href="#" class="tooltip-ico" data-title="Add Lead" data-bs-toggle="modal" data-bs-target="#AddLeadModal" @click="consultationStore.leadCreated=false">
                            <svg width="21" height="21" viewBox="0 0 21 21" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12.4763 0.836293V9.03629H20.2563V12.6963H12.4763V20.9363H8.47631V12.6963H0.736306V9.03629H8.47631V0.836293H12.4763Z"/>
                            </svg>
                        </a>
                    </div> -->
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
                            <input type="text" class="form-control" placeholder="Search Consultations"
                                   v-model="consultationStore.query" v-on:keyup="searchLeads">
                        </div>
                    </div>
                    <div class="filter-icons d-flex justify-content-end">
                        <div class="dashboard-select ms-auto d-none d-lg-block mr-5">
                            <select class="form-select exportSelect" v-model="consultationStore.perPage"
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
                                <div class="form-check" v-for="(category, index) in consultationStore.categories">
                                    <input class="form-check-input" type="checkbox" :id="category.name" value="1"
                                           v-model="category.checked" @change="filterCheckedWidth">
                                    <label class="form-check-label" :for="category.name">{{ category.name }}</label>
                                </div>
                            </div>
<!--                            <div class="apply-btn d-lg-none text-end mt-3">
                                <button type="button" class="btn btn-primary" @click="getLeads">Apply</button>
                            </div>-->
                        </div>
                        <div style="width:10px;"></div>
                        <div class="deletedLeadsSwitch tooltip-ico" style="cursor:pointer !important;"
                             :data-title="consultationStore.deletedLeads? 'View Active Leads' : 'View Deleted Leads'">
                            <input type="checkbox" class="btn-check" name="options-outlined" id="danger-outlined"
                                   v-model="consultationStore.deletedLeads" autocomplete="off"
                                   @change="deletedLeadsToggle()">
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
                            <th @mouseenter="consultationStore.hover_on = 'Full Name'" @mouseleave="consultationStore.hover_on = ''" v-show="consultationStore.categories[0].checked" @click="sortByField('Full Name')">Full
                                Name
                                <svg style="vertical-align:top;"
                                     v-show="consultationStore.sort_by=='Full Name' && consultationStore.sort_order=='asc'"
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
                                     v-show="consultationStore.sort_by=='Full Name' && consultationStore.sort_order=='desc'"
                                     width="20px" height="20px" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                    <g id="SVGRepo_iconCarrier">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                              d="M11.9191 16.7136L11.5655 17.0671L11.212 16.7136L7 12.5016L7.70711 11.7945L11.0655 15.1529V7.00009H12.0655V15.1529L15.4239 11.7945L16.1311 12.5016L11.9191 16.7136Z"
                                              fill="#000000"></path>
                                    </g>
                                </svg>
                                <svg viewBox="0 0 24 24" style="vertical-align:top;" v-show="consultationStore.sort_by!='Full Name' && consultationStore.hover_on == 'Full Name'" width="20px" height="20px" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M12.29 20.69C12.3792 20.6521 12.4606 20.5978 12.53 20.53L16.53 16.53C16.6625 16.3878 16.7346 16.1998 16.7312 16.0055C16.7277 15.8112 16.649 15.6258 16.5116 15.4884C16.3742 15.351 16.1888 15.2723 15.9945 15.2688C15.8002 15.2654 15.6122 15.3375 15.47 15.47L12.75 18.19V5.81001L15.47 8.53001C15.6122 8.66249 15.8002 8.73462 15.9945 8.73119C16.1888 8.72776 16.3742 8.64905 16.5116 8.51163C16.649 8.37422 16.7277 8.18884 16.7312 7.99454C16.7346 7.80024 16.6625 7.61219 16.53 7.47001L12.53 3.47001C12.4606 3.40222 12.3792 3.34796 12.29 3.31001C12.1984 3.27038 12.0998 3.24994 12 3.24994C11.9002 3.24994 11.8015 3.27038 11.71 3.31001C11.6207 3.34796 11.5394 3.40222 11.47 3.47001L7.47 7.47001C7.32955 7.61064 7.25066 7.80126 7.25066 8.00001C7.25066 8.19876 7.32955 8.38939 7.47 8.53001C7.61062 8.67046 7.80125 8.74935 8 8.74935C8.19875 8.74935 8.38937 8.67046 8.53 8.53001L11.25 5.81001V18.19L8.53 15.47C8.38937 15.3296 8.19875 15.2507 8 15.2507C7.80125 15.2507 7.61062 15.3296 7.47 15.47C7.32955 15.6106 7.25066 15.8013 7.25066 16C7.25066 16.1988 7.32955 16.3894 7.47 16.53L11.47 20.53C11.5394 20.5978 11.6207 20.6521 11.71 20.69C11.8015 20.7296 11.9002 20.7501 12 20.7501C12.0998 20.7501 12.1984 20.7296 12.29 20.69Z" fill="#000000"></path> </g></svg>
                            </th>
                            <th @mouseenter="consultationStore.hover_on = 'Email'" @mouseleave="consultationStore.hover_on = ''" v-show="consultationStore.categories[1].checked" @click="sortByField('Email')">Email
                                <svg style="vertical-align:top;"
                                     v-show="consultationStore.sort_by=='Email' && consultationStore.sort_order=='asc'"
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
                                     v-show="consultationStore.sort_by=='Email' && consultationStore.sort_order=='desc'"
                                     width="20px" height="20px" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                    <g id="SVGRepo_iconCarrier">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                              d="M11.9191 16.7136L11.5655 17.0671L11.212 16.7136L7 12.5016L7.70711 11.7945L11.0655 15.1529V7.00009H12.0655V15.1529L15.4239 11.7945L16.1311 12.5016L11.9191 16.7136Z"
                                              fill="#000000"></path>
                                    </g>
                                </svg>
                                <svg viewBox="0 0 24 24" style="vertical-align:top;" v-show="consultationStore.sort_by!='Email' && consultationStore.hover_on == 'Email'" width="20px" height="20px" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M12.29 20.69C12.3792 20.6521 12.4606 20.5978 12.53 20.53L16.53 16.53C16.6625 16.3878 16.7346 16.1998 16.7312 16.0055C16.7277 15.8112 16.649 15.6258 16.5116 15.4884C16.3742 15.351 16.1888 15.2723 15.9945 15.2688C15.8002 15.2654 15.6122 15.3375 15.47 15.47L12.75 18.19V5.81001L15.47 8.53001C15.6122 8.66249 15.8002 8.73462 15.9945 8.73119C16.1888 8.72776 16.3742 8.64905 16.5116 8.51163C16.649 8.37422 16.7277 8.18884 16.7312 7.99454C16.7346 7.80024 16.6625 7.61219 16.53 7.47001L12.53 3.47001C12.4606 3.40222 12.3792 3.34796 12.29 3.31001C12.1984 3.27038 12.0998 3.24994 12 3.24994C11.9002 3.24994 11.8015 3.27038 11.71 3.31001C11.6207 3.34796 11.5394 3.40222 11.47 3.47001L7.47 7.47001C7.32955 7.61064 7.25066 7.80126 7.25066 8.00001C7.25066 8.19876 7.32955 8.38939 7.47 8.53001C7.61062 8.67046 7.80125 8.74935 8 8.74935C8.19875 8.74935 8.38937 8.67046 8.53 8.53001L11.25 5.81001V18.19L8.53 15.47C8.38937 15.3296 8.19875 15.2507 8 15.2507C7.80125 15.2507 7.61062 15.3296 7.47 15.47C7.32955 15.6106 7.25066 15.8013 7.25066 16C7.25066 16.1988 7.32955 16.3894 7.47 16.53L11.47 20.53C11.5394 20.5978 11.6207 20.6521 11.71 20.69C11.8015 20.7296 11.9002 20.7501 12 20.7501C12.0998 20.7501 12.1984 20.7296 12.29 20.69Z" fill="#000000"></path> </g></svg>
                            </th>
                            <th @mouseenter="consultationStore.hover_on = 'Source'" @mouseleave="consultationStore.hover_on = ''" v-show="consultationStore.categories[2].checked" @click="sortByField('Source')">Source
                                <svg style="vertical-align:top;"
                                     v-show="consultationStore.sort_by=='Source' && consultationStore.sort_order=='asc'"
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
                                     v-show="consultationStore.sort_by=='Source' && consultationStore.sort_order=='desc'"
                                     width="20px" height="20px" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                    <g id="SVGRepo_iconCarrier">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                              d="M11.9191 16.7136L11.5655 17.0671L11.212 16.7136L7 12.5016L7.70711 11.7945L11.0655 15.1529V7.00009H12.0655V15.1529L15.4239 11.7945L16.1311 12.5016L11.9191 16.7136Z"
                                              fill="#000000"></path>
                                    </g>
                                </svg>
                                <svg viewBox="0 0 24 24" style="vertical-align:top;" v-show="consultationStore.sort_by!='Source' && consultationStore.hover_on == 'Source'" width="20px" height="20px" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M12.29 20.69C12.3792 20.6521 12.4606 20.5978 12.53 20.53L16.53 16.53C16.6625 16.3878 16.7346 16.1998 16.7312 16.0055C16.7277 15.8112 16.649 15.6258 16.5116 15.4884C16.3742 15.351 16.1888 15.2723 15.9945 15.2688C15.8002 15.2654 15.6122 15.3375 15.47 15.47L12.75 18.19V5.81001L15.47 8.53001C15.6122 8.66249 15.8002 8.73462 15.9945 8.73119C16.1888 8.72776 16.3742 8.64905 16.5116 8.51163C16.649 8.37422 16.7277 8.18884 16.7312 7.99454C16.7346 7.80024 16.6625 7.61219 16.53 7.47001L12.53 3.47001C12.4606 3.40222 12.3792 3.34796 12.29 3.31001C12.1984 3.27038 12.0998 3.24994 12 3.24994C11.9002 3.24994 11.8015 3.27038 11.71 3.31001C11.6207 3.34796 11.5394 3.40222 11.47 3.47001L7.47 7.47001C7.32955 7.61064 7.25066 7.80126 7.25066 8.00001C7.25066 8.19876 7.32955 8.38939 7.47 8.53001C7.61062 8.67046 7.80125 8.74935 8 8.74935C8.19875 8.74935 8.38937 8.67046 8.53 8.53001L11.25 5.81001V18.19L8.53 15.47C8.38937 15.3296 8.19875 15.2507 8 15.2507C7.80125 15.2507 7.61062 15.3296 7.47 15.47C7.32955 15.6106 7.25066 15.8013 7.25066 16C7.25066 16.1988 7.32955 16.3894 7.47 16.53L11.47 20.53C11.5394 20.5978 11.6207 20.6521 11.71 20.69C11.8015 20.7296 11.9002 20.7501 12 20.7501C12.0998 20.7501 12.1984 20.7296 12.29 20.69Z" fill="#000000"></path> </g></svg>
                            </th>
                            <th @mouseenter="consultationStore.hover_on = 'Phone/Form Lead'" @mouseleave="consultationStore.hover_on = ''" v-show="consultationStore.categories[3].checked"
                                @click="sortByField('Phone/Form Lead')">Lead Type
                                <svg style="vertical-align:top;"
                                     v-show="consultationStore.sort_by=='Phone/Form Lead' && consultationStore.sort_order=='asc'"
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
                                     v-show="consultationStore.sort_by=='Phone/Form Lead' && consultationStore.sort_order=='desc'"
                                     width="20px" height="20px" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                    <g id="SVGRepo_iconCarrier">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                              d="M11.9191 16.7136L11.5655 17.0671L11.212 16.7136L7 12.5016L7.70711 11.7945L11.0655 15.1529V7.00009H12.0655V15.1529L15.4239 11.7945L16.1311 12.5016L11.9191 16.7136Z"
                                              fill="#000000"></path>
                                    </g>
                                </svg>
                                <svg viewBox="0 0 24 24" style="vertical-align:top;" v-show="consultationStore.sort_by!='Phone/Form Lead' && consultationStore.hover_on == 'Phone/Form Lead'" width="20px" height="20px" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M12.29 20.69C12.3792 20.6521 12.4606 20.5978 12.53 20.53L16.53 16.53C16.6625 16.3878 16.7346 16.1998 16.7312 16.0055C16.7277 15.8112 16.649 15.6258 16.5116 15.4884C16.3742 15.351 16.1888 15.2723 15.9945 15.2688C15.8002 15.2654 15.6122 15.3375 15.47 15.47L12.75 18.19V5.81001L15.47 8.53001C15.6122 8.66249 15.8002 8.73462 15.9945 8.73119C16.1888 8.72776 16.3742 8.64905 16.5116 8.51163C16.649 8.37422 16.7277 8.18884 16.7312 7.99454C16.7346 7.80024 16.6625 7.61219 16.53 7.47001L12.53 3.47001C12.4606 3.40222 12.3792 3.34796 12.29 3.31001C12.1984 3.27038 12.0998 3.24994 12 3.24994C11.9002 3.24994 11.8015 3.27038 11.71 3.31001C11.6207 3.34796 11.5394 3.40222 11.47 3.47001L7.47 7.47001C7.32955 7.61064 7.25066 7.80126 7.25066 8.00001C7.25066 8.19876 7.32955 8.38939 7.47 8.53001C7.61062 8.67046 7.80125 8.74935 8 8.74935C8.19875 8.74935 8.38937 8.67046 8.53 8.53001L11.25 5.81001V18.19L8.53 15.47C8.38937 15.3296 8.19875 15.2507 8 15.2507C7.80125 15.2507 7.61062 15.3296 7.47 15.47C7.32955 15.6106 7.25066 15.8013 7.25066 16C7.25066 16.1988 7.32955 16.3894 7.47 16.53L11.47 20.53C11.5394 20.5978 11.6207 20.6521 11.71 20.69C11.8015 20.7296 11.9002 20.7501 12 20.7501C12.0998 20.7501 12.1984 20.7296 12.29 20.69Z" fill="#000000"></path> </g></svg>
                            </th>
                            <th @mouseenter="consultationStore.hover_on = 'Phone'" @mouseleave="consultationStore.hover_on = ''" v-show="consultationStore.categories[4].checked" @click="sortByField('Phone')">Phone
                                <svg style="vertical-align:top;"
                                     v-show="consultationStore.sort_by=='Phone' && consultationStore.sort_order=='asc'"
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
                                     v-show="consultationStore.sort_by=='Phone' && consultationStore.sort_order=='desc'"
                                     width="20px" height="20px" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                    <g id="SVGRepo_iconCarrier">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                              d="M11.9191 16.7136L11.5655 17.0671L11.212 16.7136L7 12.5016L7.70711 11.7945L11.0655 15.1529V7.00009H12.0655V15.1529L15.4239 11.7945L16.1311 12.5016L11.9191 16.7136Z"
                                              fill="#000000"></path>
                                    </g>
                                </svg>
                                <svg viewBox="0 0 24 24" style="vertical-align:top;" v-show="consultationStore.sort_by!='Phone' && consultationStore.hover_on == 'Phone'" width="20px" height="20px" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M12.29 20.69C12.3792 20.6521 12.4606 20.5978 12.53 20.53L16.53 16.53C16.6625 16.3878 16.7346 16.1998 16.7312 16.0055C16.7277 15.8112 16.649 15.6258 16.5116 15.4884C16.3742 15.351 16.1888 15.2723 15.9945 15.2688C15.8002 15.2654 15.6122 15.3375 15.47 15.47L12.75 18.19V5.81001L15.47 8.53001C15.6122 8.66249 15.8002 8.73462 15.9945 8.73119C16.1888 8.72776 16.3742 8.64905 16.5116 8.51163C16.649 8.37422 16.7277 8.18884 16.7312 7.99454C16.7346 7.80024 16.6625 7.61219 16.53 7.47001L12.53 3.47001C12.4606 3.40222 12.3792 3.34796 12.29 3.31001C12.1984 3.27038 12.0998 3.24994 12 3.24994C11.9002 3.24994 11.8015 3.27038 11.71 3.31001C11.6207 3.34796 11.5394 3.40222 11.47 3.47001L7.47 7.47001C7.32955 7.61064 7.25066 7.80126 7.25066 8.00001C7.25066 8.19876 7.32955 8.38939 7.47 8.53001C7.61062 8.67046 7.80125 8.74935 8 8.74935C8.19875 8.74935 8.38937 8.67046 8.53 8.53001L11.25 5.81001V18.19L8.53 15.47C8.38937 15.3296 8.19875 15.2507 8 15.2507C7.80125 15.2507 7.61062 15.3296 7.47 15.47C7.32955 15.6106 7.25066 15.8013 7.25066 16C7.25066 16.1988 7.32955 16.3894 7.47 16.53L11.47 20.53C11.5394 20.5978 11.6207 20.6521 11.71 20.69C11.8015 20.7296 11.9002 20.7501 12 20.7501C12.0998 20.7501 12.1984 20.7296 12.29 20.69Z" fill="#000000"></path> </g></svg>
                            </th>
                            <th @mouseenter="consultationStore.hover_on = 'Treatment Amount'" @mouseleave="consultationStore.hover_on = ''" v-show="consultationStore.categories[5].checked"
                                @click="sortByField('Treatment Amount')">Treatment Amount
                                <svg style="vertical-align:top;"
                                     v-show="consultationStore.sort_by=='Treatment Amount' && consultationStore.sort_order=='asc'"
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
                                     v-show="consultationStore.sort_by=='Treatment Amount' && consultationStore.sort_order=='desc'"
                                     width="20px" height="20px" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                    <g id="SVGRepo_iconCarrier">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                              d="M11.9191 16.7136L11.5655 17.0671L11.212 16.7136L7 12.5016L7.70711 11.7945L11.0655 15.1529V7.00009H12.0655V15.1529L15.4239 11.7945L16.1311 12.5016L11.9191 16.7136Z"
                                              fill="#000000"></path>
                                    </g>
                                </svg>
                                <svg viewBox="0 0 24 24" style="vertical-align:top;" v-show="consultationStore.sort_by!='Treatment Amount' && consultationStore.hover_on == 'Treatment Amount'" width="20px" height="20px" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M12.29 20.69C12.3792 20.6521 12.4606 20.5978 12.53 20.53L16.53 16.53C16.6625 16.3878 16.7346 16.1998 16.7312 16.0055C16.7277 15.8112 16.649 15.6258 16.5116 15.4884C16.3742 15.351 16.1888 15.2723 15.9945 15.2688C15.8002 15.2654 15.6122 15.3375 15.47 15.47L12.75 18.19V5.81001L15.47 8.53001C15.6122 8.66249 15.8002 8.73462 15.9945 8.73119C16.1888 8.72776 16.3742 8.64905 16.5116 8.51163C16.649 8.37422 16.7277 8.18884 16.7312 7.99454C16.7346 7.80024 16.6625 7.61219 16.53 7.47001L12.53 3.47001C12.4606 3.40222 12.3792 3.34796 12.29 3.31001C12.1984 3.27038 12.0998 3.24994 12 3.24994C11.9002 3.24994 11.8015 3.27038 11.71 3.31001C11.6207 3.34796 11.5394 3.40222 11.47 3.47001L7.47 7.47001C7.32955 7.61064 7.25066 7.80126 7.25066 8.00001C7.25066 8.19876 7.32955 8.38939 7.47 8.53001C7.61062 8.67046 7.80125 8.74935 8 8.74935C8.19875 8.74935 8.38937 8.67046 8.53 8.53001L11.25 5.81001V18.19L8.53 15.47C8.38937 15.3296 8.19875 15.2507 8 15.2507C7.80125 15.2507 7.61062 15.3296 7.47 15.47C7.32955 15.6106 7.25066 15.8013 7.25066 16C7.25066 16.1988 7.32955 16.3894 7.47 16.53L11.47 20.53C11.5394 20.5978 11.6207 20.6521 11.71 20.69C11.8015 20.7296 11.9002 20.7501 12 20.7501C12.0998 20.7501 12.1984 20.7296 12.29 20.69Z" fill="#000000"></path> </g></svg>
                            </th>
                            <th @mouseenter="consultationStore.hover_on = 'Date of Birth'" @mouseleave="consultationStore.hover_on = ''" v-show="consultationStore.categories[6].checked" @click="sortByField('Date of Birth')">
                                Date of Birth
                                <svg style="vertical-align:top;"
                                     v-show="consultationStore.sort_by=='Date of Birth' && consultationStore.sort_order=='asc'"
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
                                     v-show="consultationStore.sort_by=='Date of Birth' && consultationStore.sort_order=='desc'"
                                     width="20px" height="20px" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                    <g id="SVGRepo_iconCarrier">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                              d="M11.9191 16.7136L11.5655 17.0671L11.212 16.7136L7 12.5016L7.70711 11.7945L11.0655 15.1529V7.00009H12.0655V15.1529L15.4239 11.7945L16.1311 12.5016L11.9191 16.7136Z"
                                              fill="#000000"></path>
                                    </g>
                                </svg>
                                <svg viewBox="0 0 24 24" style="vertical-align:top;" v-show="consultationStore.sort_by!='Date of Birth' && consultationStore.hover_on == 'Date of Birth'" width="20px" height="20px" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M12.29 20.69C12.3792 20.6521 12.4606 20.5978 12.53 20.53L16.53 16.53C16.6625 16.3878 16.7346 16.1998 16.7312 16.0055C16.7277 15.8112 16.649 15.6258 16.5116 15.4884C16.3742 15.351 16.1888 15.2723 15.9945 15.2688C15.8002 15.2654 15.6122 15.3375 15.47 15.47L12.75 18.19V5.81001L15.47 8.53001C15.6122 8.66249 15.8002 8.73462 15.9945 8.73119C16.1888 8.72776 16.3742 8.64905 16.5116 8.51163C16.649 8.37422 16.7277 8.18884 16.7312 7.99454C16.7346 7.80024 16.6625 7.61219 16.53 7.47001L12.53 3.47001C12.4606 3.40222 12.3792 3.34796 12.29 3.31001C12.1984 3.27038 12.0998 3.24994 12 3.24994C11.9002 3.24994 11.8015 3.27038 11.71 3.31001C11.6207 3.34796 11.5394 3.40222 11.47 3.47001L7.47 7.47001C7.32955 7.61064 7.25066 7.80126 7.25066 8.00001C7.25066 8.19876 7.32955 8.38939 7.47 8.53001C7.61062 8.67046 7.80125 8.74935 8 8.74935C8.19875 8.74935 8.38937 8.67046 8.53 8.53001L11.25 5.81001V18.19L8.53 15.47C8.38937 15.3296 8.19875 15.2507 8 15.2507C7.80125 15.2507 7.61062 15.3296 7.47 15.47C7.32955 15.6106 7.25066 15.8013 7.25066 16C7.25066 16.1988 7.32955 16.3894 7.47 16.53L11.47 20.53C11.5394 20.5978 11.6207 20.6521 11.71 20.69C11.8015 20.7296 11.9002 20.7501 12 20.7501C12.0998 20.7501 12.1984 20.7296 12.29 20.69Z" fill="#000000"></path> </g></svg>
                            </th>
                            <th @mouseenter="consultationStore.hover_on = 'Consultation Booked Date'" @mouseleave="consultationStore.hover_on = ''" v-show="consultationStore.categories[7].checked"
                                @click="sortByField('Consultation Booked Date')">Consultation Booked Date
                                <svg style="vertical-align:top;"
                                     v-show="consultationStore.sort_by=='Consultation Booked Date' && consultationStore.sort_order=='asc'"
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
                                     v-show="consultationStore.sort_by=='Consultation Booked Date' && consultationStore.sort_order=='desc'"
                                     width="20px" height="20px" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                    <g id="SVGRepo_iconCarrier">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                              d="M11.9191 16.7136L11.5655 17.0671L11.212 16.7136L7 12.5016L7.70711 11.7945L11.0655 15.1529V7.00009H12.0655V15.1529L15.4239 11.7945L16.1311 12.5016L11.9191 16.7136Z"
                                              fill="#000000"></path>
                                    </g>
                                </svg>
                                <svg viewBox="0 0 24 24" style="vertical-align:top;" v-show="consultationStore.sort_by!='Consultation Booked Date' && consultationStore.hover_on == 'Consultation Booked Date'" width="20px" height="20px" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M12.29 20.69C12.3792 20.6521 12.4606 20.5978 12.53 20.53L16.53 16.53C16.6625 16.3878 16.7346 16.1998 16.7312 16.0055C16.7277 15.8112 16.649 15.6258 16.5116 15.4884C16.3742 15.351 16.1888 15.2723 15.9945 15.2688C15.8002 15.2654 15.6122 15.3375 15.47 15.47L12.75 18.19V5.81001L15.47 8.53001C15.6122 8.66249 15.8002 8.73462 15.9945 8.73119C16.1888 8.72776 16.3742 8.64905 16.5116 8.51163C16.649 8.37422 16.7277 8.18884 16.7312 7.99454C16.7346 7.80024 16.6625 7.61219 16.53 7.47001L12.53 3.47001C12.4606 3.40222 12.3792 3.34796 12.29 3.31001C12.1984 3.27038 12.0998 3.24994 12 3.24994C11.9002 3.24994 11.8015 3.27038 11.71 3.31001C11.6207 3.34796 11.5394 3.40222 11.47 3.47001L7.47 7.47001C7.32955 7.61064 7.25066 7.80126 7.25066 8.00001C7.25066 8.19876 7.32955 8.38939 7.47 8.53001C7.61062 8.67046 7.80125 8.74935 8 8.74935C8.19875 8.74935 8.38937 8.67046 8.53 8.53001L11.25 5.81001V18.19L8.53 15.47C8.38937 15.3296 8.19875 15.2507 8 15.2507C7.80125 15.2507 7.61062 15.3296 7.47 15.47C7.32955 15.6106 7.25066 15.8013 7.25066 16C7.25066 16.1988 7.32955 16.3894 7.47 16.53L11.47 20.53C11.5394 20.5978 11.6207 20.6521 11.71 20.69C11.8015 20.7296 11.9002 20.7501 12 20.7501C12.0998 20.7501 12.1984 20.7296 12.29 20.69Z" fill="#000000"></path> </g></svg>
                            </th>
                            <th @mouseenter="consultationStore.hover_on = 'Lead Score'" @mouseleave="consultationStore.hover_on = ''" v-show="consultationStore.categories[8].checked" @click="sortByField('Lead Score')">Lead
                                Score
                                <svg style="vertical-align:top;"
                                     v-show="consultationStore.sort_by=='Lead Score' && consultationStore.sort_order=='asc'"
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
                                     v-show="consultationStore.sort_by=='Lead Score' && consultationStore.sort_order=='desc'"
                                     width="20px" height="20px" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                    <g id="SVGRepo_iconCarrier">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                              d="M11.9191 16.7136L11.5655 17.0671L11.212 16.7136L7 12.5016L7.70711 11.7945L11.0655 15.1529V7.00009H12.0655V15.1529L15.4239 11.7945L16.1311 12.5016L11.9191 16.7136Z"
                                              fill="#000000"></path>
                                    </g>
                                </svg>
                                <svg viewBox="0 0 24 24" style="vertical-align:top;" v-show="consultationStore.sort_by!='Lead Score' && consultationStore.hover_on == 'Lead Score'" width="20px" height="20px" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M12.29 20.69C12.3792 20.6521 12.4606 20.5978 12.53 20.53L16.53 16.53C16.6625 16.3878 16.7346 16.1998 16.7312 16.0055C16.7277 15.8112 16.649 15.6258 16.5116 15.4884C16.3742 15.351 16.1888 15.2723 15.9945 15.2688C15.8002 15.2654 15.6122 15.3375 15.47 15.47L12.75 18.19V5.81001L15.47 8.53001C15.6122 8.66249 15.8002 8.73462 15.9945 8.73119C16.1888 8.72776 16.3742 8.64905 16.5116 8.51163C16.649 8.37422 16.7277 8.18884 16.7312 7.99454C16.7346 7.80024 16.6625 7.61219 16.53 7.47001L12.53 3.47001C12.4606 3.40222 12.3792 3.34796 12.29 3.31001C12.1984 3.27038 12.0998 3.24994 12 3.24994C11.9002 3.24994 11.8015 3.27038 11.71 3.31001C11.6207 3.34796 11.5394 3.40222 11.47 3.47001L7.47 7.47001C7.32955 7.61064 7.25066 7.80126 7.25066 8.00001C7.25066 8.19876 7.32955 8.38939 7.47 8.53001C7.61062 8.67046 7.80125 8.74935 8 8.74935C8.19875 8.74935 8.38937 8.67046 8.53 8.53001L11.25 5.81001V18.19L8.53 15.47C8.38937 15.3296 8.19875 15.2507 8 15.2507C7.80125 15.2507 7.61062 15.3296 7.47 15.47C7.32955 15.6106 7.25066 15.8013 7.25066 16C7.25066 16.1988 7.32955 16.3894 7.47 16.53L11.47 20.53C11.5394 20.5978 11.6207 20.6521 11.71 20.69C11.8015 20.7296 11.9002 20.7501 12 20.7501C12.0998 20.7501 12.1984 20.7296 12.29 20.69Z" fill="#000000"></path> </g></svg>
                            </th>
                            <th @mouseenter="consultationStore.hover_on = 'Tags'" @mouseleave="consultationStore.hover_on = ''" v-show="consultationStore.categories[9].checked" @click="sortByField('tag')">Tags
                                <svg style="vertical-align:top;"
                                     v-show="consultationStore.sort_by=='tag' && consultationStore.sort_order=='asc'"
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
                                     v-show="consultationStore.sort_by=='tag' && consultationStore.sort_order=='desc'"
                                     width="20px" height="20px" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                    <g id="SVGRepo_iconCarrier">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                              d="M11.9191 16.7136L11.5655 17.0671L11.212 16.7136L7 12.5016L7.70711 11.7945L11.0655 15.1529V7.00009H12.0655V15.1529L15.4239 11.7945L16.1311 12.5016L11.9191 16.7136Z"
                                              fill="#000000"></path>
                                    </g>
                                </svg>
                                <svg viewBox="0 0 24 24" style="vertical-align:top;" v-show="consultationStore.sort_by!='tag' && consultationStore.hover_on == 'Tags'" width="20px" height="20px" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M12.29 20.69C12.3792 20.6521 12.4606 20.5978 12.53 20.53L16.53 16.53C16.6625 16.3878 16.7346 16.1998 16.7312 16.0055C16.7277 15.8112 16.649 15.6258 16.5116 15.4884C16.3742 15.351 16.1888 15.2723 15.9945 15.2688C15.8002 15.2654 15.6122 15.3375 15.47 15.47L12.75 18.19V5.81001L15.47 8.53001C15.6122 8.66249 15.8002 8.73462 15.9945 8.73119C16.1888 8.72776 16.3742 8.64905 16.5116 8.51163C16.649 8.37422 16.7277 8.18884 16.7312 7.99454C16.7346 7.80024 16.6625 7.61219 16.53 7.47001L12.53 3.47001C12.4606 3.40222 12.3792 3.34796 12.29 3.31001C12.1984 3.27038 12.0998 3.24994 12 3.24994C11.9002 3.24994 11.8015 3.27038 11.71 3.31001C11.6207 3.34796 11.5394 3.40222 11.47 3.47001L7.47 7.47001C7.32955 7.61064 7.25066 7.80126 7.25066 8.00001C7.25066 8.19876 7.32955 8.38939 7.47 8.53001C7.61062 8.67046 7.80125 8.74935 8 8.74935C8.19875 8.74935 8.38937 8.67046 8.53 8.53001L11.25 5.81001V18.19L8.53 15.47C8.38937 15.3296 8.19875 15.2507 8 15.2507C7.80125 15.2507 7.61062 15.3296 7.47 15.47C7.32955 15.6106 7.25066 15.8013 7.25066 16C7.25066 16.1988 7.32955 16.3894 7.47 16.53L11.47 20.53C11.5394 20.5978 11.6207 20.6521 11.71 20.69C11.8015 20.7296 11.9002 20.7501 12 20.7501C12.0998 20.7501 12.1984 20.7296 12.29 20.69Z" fill="#000000"></path> </g></svg>
                            </th>
                            <th v-show="consultationStore.categories[10].checked" @click="sortByField('Landing Page')">Landing Page
                                <svg style="vertical-align:top;"
                                     v-show="consultationStore.sort_by=='Landing Page' && consultationStore.sort_order=='asc'"
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
                                     v-show="consultationStore.sort_by=='Landing Page' && consultationStore.sort_order=='desc'"
                                     width="20px" height="20px" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                    <g id="SVGRepo_iconCarrier">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                              d="M11.9191 16.7136L11.5655 17.0671L11.212 16.7136L7 12.5016L7.70711 11.7945L11.0655 15.1529V7.00009H12.0655V15.1529L15.4239 11.7945L16.1311 12.5016L11.9191 16.7136Z"
                                              fill="#000000"></path>
                                    </g>
                                </svg>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <div v-if="consultationStore.leads.length>0">
                            <tr :key="lead.id" v-for="(lead, index) in consultationStore.leads">
                                <td @click="viewProfile(lead.id)" v-if="consultationStore.categories[0].checked"
                                    class="d-none d-lg-block"><span
                                    style="text-wrap:balance">{{ lead.first_name + ' ' + lead.last_name ?? '' }}</span>
                                </td>
                                <td @click="viewProfile(lead.id)" v-if="consultationStore.categories[1].checked"
                                    class="d-none d-lg-block"><span>{{ (lead.email) ? lead.email : '' }}</span></td>
                                <td @click="viewProfile(lead.id)" v-if="consultationStore.categories[2].checked"
                                    class="source-label"><span>{{ (lead.source_id) ? lead.source_id.name : '' }}</span>
                                </td>
                                <td @click="viewProfile(lead.id)" v-if="consultationStore.categories[3].checked"
                                    class="phone-label"><span>{{ lead.phone_form }}</span></td>
                                <td @click="viewProfile(lead.id)" v-if="consultationStore.categories[4].checked"
                                    class="d-none d-lg-block"><span>{{ lead.phone }}</span></td>
                                <td @click="viewProfile(lead.id)" v-if="consultationStore.categories[5].checked"
                                    class="d-none d-lg-block"><span>{{
                                        lead.value ? new Intl.NumberFormat('en-US', {
                                            style: 'currency',
                                            currency: 'USD'
                                        }).format(lead.value) : ''
                                    }}</span></td>
                                <td @click="viewProfile(lead.id)" v-if="consultationStore.categories[6].checked"
                                    class="d-none d-lg-block"><span>{{ lead.dob }}</span></td>
                                <td @click="viewProfile(lead.id)" v-if="consultationStore.categories[7].checked"
                                    style="padding: 15px;"><span>{{
                                        (lead.consultation_booked_date) ? new Date(lead.consultation_booked_date).toLocaleDateString('en-us', {
                                            year: "numeric",
                                            month: "short",
                                            day: "numeric",
                                            hour: "numeric",
                                            minute: "numeric"
                                        }) : ''
                                    }}</span></td>
                                <td @click="viewProfile(lead.id)"
                                    v-if="consultationStore.categories[8].checked"
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
                                <td @click="viewProfile(lead.id)" v-if="consultationStore.categories[9].checked"
                                    class="d-none d-lg-block"><span>{{ lead.tagName }}</span></td>
                                <td @click="viewProfile(lead.id)" v-if="consultationStore.categories[10].checked"
                                    class="d-none d-lg-block"><span>{{ lead.landing_page_url }}</span></td>

                                <td @click="viewProfile(lead.id)" class="d-lg-none lead-name">
                                    <span>{{ lead.first_name + ' ' + lead.last_name }}</span></td>
                                <td class="hover-td">
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
                            <h6 class="text-center">Currently there are no Consultation to display!</h6>
                        </div>
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-between pt-3">
                        <a v-show="consultationStore.page > 1" href="#" class="btn btn-primary" @click.prevent="prev">Prev</a>
                        <div class="text-center" style="width:100%" v-if="consultationStore.leads.length>0"><span
                            class="badge bg-primary" style="font-size: 16px; margin-top:10px;">{{
                                consultationStore.page + '/' + consultationStore.last_page
                            }}</span></div>
                        <a v-show="consultationStore.page<consultationStore.last_page" href="#" class="btn btn-primary"
                           @click.prevent="next">Next</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="BookConsultationModalConsultation" tabindex="-1"
         aria-labelledby="BookConsultationModalConsultation" aria-hidden="true">
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
                            <input type="hidden" id="book-datetimepicker-consultation"
                                   v-model="consultation_booked_date" style="position:absolute;"/>
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
</template>
<script>

import {useAlertStore} from '../../stores/alert';
import {useConsultationStore} from '../../stores/consultation';
import CKEditor from '@ckeditor/ckeditor5-vue';
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
import UploadAdapter from '../../stores/UploadAdapter';
import {useAppointmentStore} from "../../stores/appointment";

export default {
    setup() {
        const consultationStore = useConsultationStore();

        const alertStore = useAlertStore();

        const appointmentStore = useAppointmentStore();

        return {consultationStore, alertStore, appointmentStore};
    },
    data() {
        return {
            showMobileSearch: false,
            showMoveIndex: null,
            status_id: null,
            consultation_booked_date: null,
            leadId: null,
            showCategoryFilter: true,
            editor: ClassicEditor,
            editorConfig: {
                extraPlugins: [this.uploader],
            },
            isSidebarVisible: false, // Initially set to false
            selectedLead: {
                first_name: '',
                last_name: '',
                consultation_booked_date: null,
                status_id: null,
                deal_status: null,
                won_lost: null,
                won_lost_date:null,
                value: '',
                Notes: []
            },
            latestNote: '' // Variable to store the latest note
        }
    },
    components: {
        ckeditor: CKEditor.component
    },
    computed: {
        checkedCategories: function () {
            let cats = [];
            this.consultationStore.categories.forEach(function (category) {
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

        if (localStorage.getItem('consultationCategories') != null) {
            this.consultationStore.categories = JSON.parse(localStorage.getItem('consultationCategories'));
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

        var bookConsultationModal = document.getElementById('BookConsultationModalConsultation');

        bookConsultationModal.addEventListener('show.bs.modal', function (event) {

            var button = event.relatedTarget;

            _self.leadId = button.getAttribute('data-bs-lead-id');
            var index = button.getAttribute('data-bs-index');

            $('#consultation-container').empty();

            _self.consultation_booked_date = (_self.consultationStore.leads[index] && _self.consultationStore.leads[index].consultation_booked_date) ?? null;

            $.datetimepicker.setDateFormatter('moment');

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

            var priorDate = moment().subtract(30, "days");

            $('#book-datetimepicker-consultation').datetimepicker({
                value: _self.consultation_booked_date ?? null,
                format: 'MM/DD/YYYY hh:mm A',
                onChangeDateTime: function (ct, $i) {
                    _self.consultation_booked_date = $('#book-datetimepicker-consultation').val();
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

        $('#treatment_sold_date').datetimepicker({
            format: 'MM/DD/YYYY',
            timepicker: false,
            onSelectDate: function (ct, $i) {
                _self.selectedLead.won_lost_date = $('#treatment_sold_date').val();
            }
        });
    },
    methods: {
        getStageCounts() {
            this.consultationStore.count();
        },
        searchLeads() {
            if (this.consultationStore.query.length >= 3 || this.consultationStore.query.length == 0) {
                this.consultationStore.list();
            }
        },
        getLeads() {
            this.consultationStore.list();
        },
        toggleShowMoveIndex(index) {
            this.showMoveIndex = index;
        },
        getStageCount(stage) {
            if (stage.id == 1) {
                return {name: 'New Lead', TotalCount: stage.TotalCount}
            }
        },
        filterByStage(status_id) {
            this.consultationStore.status_id = status_id;
            this.consultationStore.page = 1;
            this.consultationStore.list();
        },
        moveLead(leadid) {
            if (this.status_id != null) {
                this.consultationStore.move(this.status_id, leadid).then((response) => {
                    $(".move-stage").modal('hide');
                    this.consultationStore.count();
                    this.consultationStore.list();
                    this.status_id = null;
                });
            }

        },
        filterCheckedWidth() {
            let catCount = this.checkedCategories.length;
            $('.lead-table-box table tr').css('grid-template-columns', 'repeat(' + catCount + ', 1fr)')
        },
        saveCategories() {
            localStorage.setItem('consultationCategories', JSON.stringify(this.consultationStore.categories));
            $('.lead-filterBy-box').hide();
            this.alertStore.success = true;
            this.alertStore.message = 'Column preferences have been saved successfully!';
        },
        viewProfile(id) {
            localStorage.setItem('last_path', '/crtx/patient-profile/' + id);
            window.open('/crtx/patient-profile/' + id, '_blank');
        },
        update() {
            this.consultationStore.updateConsultationBookedDate(this.leadId, this.consultation_booked_date);
            $("#BookConsultationModalConsultation").modal('hide');
            this.consultation_booked_date = null;
        },
        sortByField(field) {
            if (this.consultationStore.sort_by == field) {
                if (this.consultationStore.sort_order == 'asc') {
                    this.consultationStore.sort_order = 'desc';
                } else {
                    this.consultationStore.sort_order = 'asc';
                }
            } else {
                this.consultationStore.sort_by = field;
                this.consultationStore.sort_order = 'desc';
            }
            this.consultationStore.list();
        },
        prev() {
            if (this.consultationStore.page > 1) {
                this.consultationStore.page--;
                this.consultationStore.list();
            }
        },
        next() {
            this.consultationStore.page++;
            this.consultationStore.list();
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
            this.consultationStore.list();
        },
        deletedLeadsToggle() {
            this.consultationStore.count();
            this.consultationStore.list();
        },
        restoreLead(leadId) {
            this.consultationStore.restore(leadId);
        },
        toggleSidebar(leadId) {
            let _self = this;
            //this.isSidebarVisible = !this.isSidebarVisible; // Toggle sidebar visibility
            this.selectedLeadId = leadId;
            this.selectedLead = this.consultationStore.leads.find(lead => lead.id === leadId);
            if(this.selectedLead.won_lost_date){
                this.selectedLead.won_lost_date = this.selectedLead.won_lost_date.split(" ")[0]
            }
            this.isSidebarVisible = true;

            $('#bookDate').datetimepicker({
                allowTimes: _self.appointmentStore.allowedDateTimes[moment(_self.selectedLead.consultation_booked_date).format('MM/DD/YYYY')],
                formatTime:'hh:mm A'
            });
        },
        closeSidebar() {
            this.isSidebarVisible = false; // Close the sidebar
        },
        quickupdate(leadId) {

            if (this.consultationStore.leads.status_id == 9 && !this.consultationStore.leads.reason) {
                this.showLostReasonError = true;
                this.profile_editing = true;
                this.showMoreProfile = true;
                const element = document.getElementById("lost-reason");
                element.focus();
                element.scrollIntoView();
                return null;
            }

            let foundLead = this.consultationStore.leads.find(lead => lead.id === leadId);
            if (foundLead) {
                // Pass the found lead to the quickupdatesidebar function
                this.consultationStore.quickupdatesidebar(foundLead, this.consultationStore.leads);
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
                    });
                },
                onChangeMonth:function(ct, $i){
                    let startDate = moment(ct).format('MM-01-YYYY');
                    if(moment(startDate).isAfter(moment())){
                        _self.appointmentStore.getAvailableTimes(startDate);
                    }
                },
                defaultSelect:false,
                hours12: false,
                ampm: true,
            });
        },
        updateDateTimePickerValue(newValue) {
            $('#bookDate').val(newValue); // Set the input value directly
        },
        treatmentSoldChanged() {
            if ($('#treatment_sold').val() == 'Won') {
                this.selectedLead.won_lost_date = moment().format('MM/DD/YYYY');
            } else {
                this.selectedLead.won_lost_date = null;
            }
        },
        stageChanged() {
            if (this.selectedLead.status_id === "") { // Treatment Sold
                this.selectedLead.won_lost = "Won";
                this.selectedLead.won_lost_date = moment().format('MM/DD/YYYY'); // Sets today's date (YYYY-MM-DD)
            } else {
                this.selectedLead.won_lost = "";
                this.selectedLead.won_lost_date = "";
            }
        },
        uploader(editor)
        {
            editor.plugins.get( 'FileRepository' ).createUploadAdapter = ( loader ) => {
                return new UploadAdapter( loader );
            };
        },
    }
}
</script>

<template>
    <!-- Sidebar -->
    <div :class="['sidebar edit-lead dashboard', isSidebarVisible ? 'visible' : '']" aria-hidden="true">
        <!-- Edit link to toggle container -->
        <!-- Container part inside the sidebar -->
        <div class="sidebar__content" v-show="isSidebarVisible">
            <!-- Close Button -->
            <button class="close-btn" @click="isSidebarVisible = false">&times;</button>
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
                <div v-if="selectedTab === 'follow-ups'" class="form-group">
                    <label>Stage</label>
                    <select class="js-example-basic-single form-select" v-model="selectedLead.status_id">
                        <option disabled selected value="null">Select Stage</option>
                        <option :selected="selectedLead.status_id===1" value="1">New Lead</option>
                        <option :selected="selectedLead.status_id===5" value="5">In Discussion</option>
                        <option :selected="selectedLead.status_id===2" value="2">Attempt 1</option>
                        <option :selected="selectedLead.status_id===3" value="3">Attempt 2</option>
                        <option :selected="selectedLead.status_id===4" value="4">Attempt 3 Plus</option>
                        <option :selected="selectedLead.status_id===17" value="17">Nurturing</option>
                        <option :selected="selectedLead.status_id===6" value="6">Practice Follow-Up</option>
                        <option :selected="selectedLead.status_id===9" value="9">Not Interested</option>
                        <option :selected="selectedLead.status_id===16" value="16">Existing Patient</option>
                    </select>
                </div>

                <div v-if="selectedTab === 'scheduled'" class="form-group">
                    <label>Stage</label>
                    <select class="js-example-basic-single form-select" v-model="selectedLead.status_id" @change="stageChanged">
                        <option disabled selected value="null">Select Stage</option>
                        <option :selected="selectedLead.status_id===1" value="1">New Lead</option>
                        <option :selected="selectedLead.status_id===5" value="5">In Discussion</option>
                        <option :selected="selectedLead.status_id===17" value="17">Nurturing</option>
                        <option :selected="selectedLead.status_id===6" value="6">Practice Follow-Up</option>
                        <option :selected="selectedLead.status_id==12" value="12">Consultation Booked</option>
                        <option :selected="selectedLead.status_id==13" value="13">No Showed</option>
                        <option :selected="selectedLead.status_id==14" value="14">Cancellation</option>
                        <option :selected="selectedLead.status_id==15" value="15">Pending Acceptance</option>
                        <option :selected="selectedLead.status_id==''" value="">Treatment Sold</option>
                        <option :selected="selectedLead.status_id==18" value="18">Treatment Completed</option>
                    </select>
                </div>

                <div v-show="selectedLead.status_id===9 && showLostReasonIndex===index">
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
<!--                        <span class="d-block text-danger mt-1" v-if="selectedLead.status_id === 9 && !selectedLead.reason">Please select Lost Reason!</span>-->
                    </div>
                </div>

                <div v-if="selectedTab === 'scheduled'" class="form-group">
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
                </div>


                <div class="form-group">
                    <label>Note</label>
                    <ckeditor :editor="editor" v-model="selectedLead.note" :config="editorConfig"></ckeditor>
                </div>
                <button type="button" :disabled="selectedLead.status_id === 9 && !selectedLead.reason" class="btn btn-primary px-4"
                        @click="quickupdate(selectedLead.id)">Save Changes
                </button>
            </div>
        </div>
    </div>
    <!-- Sidebar Ends -->
    <div id="main" class="bg-light-gray">
        <!-- <figure class="m-0"><img class="dashboard-img" src="images/dashboard-img.jpg" alt=""></figure> -->
        <div class="container-fluid p-0">
            <div class="px-0 py-0 mt-4">
                <!--				<div class="staging-alert" v-if="env=='staging'">

                                </div>-->
                <div v-if="!authStore.user.phone"
                     class="alert alert-primary d-sm-flex align-items-center mb-4 justify-content-center text-center py-1">
                    <p class="d-flex me-sm-3 mb-1 text-start align-items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             style="min-width: 25px;" class="feather feather-smartphone me-2 mt-1">
                            <rect x="5" y="2" width="14" height="20" rx="2" ry="2"></rect>
                            <line x1="12" y1="18" x2="12.01" y2="18"></line>
                        </svg>
                        To receive real-time text alerts about important leads and appointments, you need to add your
                        mobile number to your profile.
                    </p>
                    <router-link to="/crtx/user-profile" role="button"
                                 style="white-space: nowrap;text-decoration: underline;"
                                 class="d-inline-block mx-auto me-sm-0 mt-3 mt-sm-0">Add Mobile Number >>
                    </router-link>
                </div>
                <div class="filter-icons d-flex justify-content-between">
                    <h2>My Dashboard</h2>
                    <div style="width:10px;"></div>
                    <a role="button" class="filter-ico tooltip-ico px-3 btn border-0 bg-white" data-title="Toggle Views" @click="showViews = !showViews">
                        <span class="ico-title text-nowrap">Edit Dashboard</span>
                        <svg class="me-0" width="18" height="18" viewBox="0 0 18 18" xmlns="http://www.w3.org/2000/svg">
                            <path d="M7.675 18V12.375H9.175V14.45H18V15.95H9.175V18H7.675ZM0 15.95V14.45H6.175V15.95H0ZM4.675 11.8V9.75H0V8.25H4.675V6.15H6.175V11.8H4.675ZM7.675 9.75V8.25H18V9.75H7.675ZM11.825 5.625V0H13.325V2.05H18V3.55H13.325V5.625H11.825ZM0 3.55V2.05H10.325V3.55H0Z"/>
                        </svg>
                    </a>
                    <div class="lead-filterBy-box d-inline-block" v-if="showViews">
                        <div class="lead-filterBy-top d-flex align-items-center">
                             <div class="filterBy-back">
                                <a href="#" class="filterBy-back-btn" @click="showViews = false">
                                    <svg width="20" height="14" viewBox="0 0 20 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M19.4411 6.37503H2.04859L7.25172 1.19191C7.49609 0.947534 7.49609 0.551909 7.25172 0.308159C7.00734 0.0637842 6.61172 0.0637842 6.36797 0.308159L0.180469 6.49503C-0.0601562 6.73566 -0.0601562 7.13816 0.180469 7.37878L6.36797 13.5663C6.61234 13.8107 7.00797 13.8107 7.25172 13.5663C7.49609 13.3219 7.49609 12.9263 7.25172 12.6825L2.04859 7.62503H19.4411C19.7861 7.62503 20.0661 7.34503 20.0661 7.00003C20.0661 6.65503 19.7861 6.37503 19.4411 6.37503Z" fill="#514F5F"></path>
                                    </svg>
                                </a>
                            </div>
                            <div class="filterBy-title mx-lg-auto">
                                <h5>Edit Dashboard</h5>
                            </div>
                            <div class="filterBy-save ms-auto ms-lg-0">
                                <a href="#" class="filterBy-save-btn" @click="saveViews()">
                                    Save
                                </a>
                                <a href="#" class="filterBy-viewall-save" @click="saveViews()">
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
                            <span class="text-capitalize" v-for="(view, key) in dashboardStore.views">{{ key.replace(/([a-z])([A-Z])/g, '$1 $2') }}</span>
                        </div>
                        <div class="SortByTitle d-lg-none">
                            <h4>Sort By</h4>
                        </div>
                        <div class="lead-filterBy-middle">
                            <div class="form-check" v-for="(view, key) in dashboardStore.views">
                                <input class="form-check-input" type="checkbox" :id="key" :checked="dashboardStore.views[key]" @click="dashboardStore.views[key] = !dashboardStore.views[key]" />
                                <label class="form-check-label text-capitalize" :for="key">{{key.replace(/([a-z])([A-Z])/g, '$1 $2')}}</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="dashboard-tab" >
                    <div class="tab-content mb-3" id="myTabContent" v-show="dashboardStore.views.clinicStats || dashboardStore.views.salesSummary || dashboardStore.views.patientPipeline || dashboardStore.views.trends || dashboardStore.views.marketingSource || dashboardStore.views.nurturing">
                        <div id="KPIs">
                            <div class="dashboard-box " v-show="dashboardStore.views.clinicStats">
                                <div class="dashboard-box-title">
                                    <h3><strong class="color-light">{{ authStore.clinic_name }}</strong> <strong
                                        class="color-lighter">at a Glance</strong></h3>
                                </div>
                                <div class="dashboard-sub-box">
                                    <div class="row">
                                        <div class="col-xl-3 col-lg-6 col-md-6 col-12 my-2">
                                            <div class="kpi-main-box kpi-current">
                                                <span>ALL TIME</span>
                                                <h2>${{ dashboardStore.kpiTreatmentSoldAllTime }}</h2>
                                                <p>Treatments Sold</p>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-lg-6 col-md-6 col-12 my-2">
                                            <div class="kpi-main-box">
                                                <span>Year to date</span>
                                                <h2>${{ dashboardStore.kpiTreatmentSoldCurrentYear }}</h2>
                                                <p>Treatments Sold</p>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-lg-6 col-md-6 col-12 my-2">
                                            <div class="kpi-main-box">
                                                <span>This month</span>
                                                <h2>${{ dashboardStore.kpiTreatmentSoldCurrentMonth }}</h2>
                                                <p>Treatments Sold</p>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-lg-6 col-md-6 col-12 my-2" role="button"
                                             @click="redirectLink('TreatmentsSold(PMS)',true)">
                                            <div class="kpi-main-box" :class="[{ 'kpi-current': isHovered }]"
                                                 @mouseover="isHovered = true" @mouseleave="isHovered = false">
                                                <span>Office Sync</span>
                                                <h2>${{ dashboardStore.kpiLifeTimeValueSum }}</h2>
                                                <p>Treatments Sold</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="dashboard-box" v-show="dashboardStore.views.salesSummary">
                                <div class="dashboard-box-title d-flex align-items-center">
                                    <h3>Sales Summary</h3>
                                    <div class="dashboard-select ms-auto">
                                        <div class="mt-2 select-date-dropdown">
                                            <div class="dropdown">
                                                <div id="reportrange"
                                                     style="color: white;background: #355ADD; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                                                    <i class="fa fa-calendar"></i>&nbsp;
                                                    <span></span> <i class="fa fa-caret-down"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="dashboard-sub-box">
                                    <div class="row">
                                        <div class="col-md-6 col-12 my-2">
                                            <div class="dashboard-summary-box d-flex align-items-center">
                                                <h5><a href="#" @click="redirectLink('newLeads')">New Leads</a></h5>
                                                <span v-if="dashboardStore.salesSummary">{{
                                                        dashboardStore.salesSummary.newLeadsCount
                                                    }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12 my-2">
                                            <div class="dashboard-summary-box d-flex align-items-center">
                                                <h5><a href="#" @click="redirectLink('TreatmentsPresented')">Treatments
                                                    Presented</a></h5>
                                                <span v-if="dashboardStore.salesSummary">${{
                                                        dashboardStore.salesSummary.treatmentPresentedCount
                                                    }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12 my-2">
                                            <div class="dashboard-summary-box d-flex align-items-center">
                                                <h5><a href="#" @click="redirectLink('consultationBooked')">Consultations
                                                    Booked</a></h5>
                                                <span v-if="dashboardStore.salesSummary">{{
                                                        dashboardStore.salesSummary.consultationBookedCount
                                                    }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12 my-2">
                                            <div class="dashboard-summary-box d-flex align-items-center">
                                                <h5><a href="#" @click="redirectLink('ConsultationsFollowUp')">Consultations
                                                    Follow Up</a></h5>
                                                <span v-if="dashboardStore.salesSummary">{{
                                                        dashboardStore.salesSummary.consultationFollowupCount
                                                    }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12 my-2">
                                            <div class="dashboard-summary-box d-flex align-items-center">
                                                <h5><a href="#" @click="redirectLink('ConsultationsShowed')">Consultations
                                                    Showed</a></h5>
                                                <span v-if="dashboardStore.salesSummary">{{
                                                        dashboardStore.salesSummary.consultationShowedCount
                                                    }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12 my-2">
                                            <div class="dashboard-summary-box d-flex align-items-center">
                                                <h5><a href="#" @click="redirectLink('TreatmentsSold')">Treatments
                                                    Sold</a></h5>
                                                <span v-if="dashboardStore.salesSummary">${{
                                                        dashboardStore.salesSummary.treatmentsSoldValue
                                                    }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="dashboard-box" v-show="dashboardStore.views.patientPipeline">
                                <div class="dashboard-box-title d-flex align-items-center">
                                    <h3>New Patient Pipeline</h3>
                                </div>
                                <div class="dashboard-sub-box">
                                    <div id="custom-message-container">

                                    </div>
                                </div>
                            </div>
                            <div class="dashboard-box" v-show="dashboardStore.views.trends">
                                <div class="dashboard-box-title d-flex align-items-center">
                                    <h3>Trends</h3>
                                </div>
                                <div class="dashboard-sub-box">
                                    <div class="row">
                                        <div class="col-md-6 treatmentSoldChartContainer">
                                            <canvas id="treatmentSoldChart"></canvas>
                                        </div>
                                        <div class="col-md-6 totalLeadsChartContainer">
                                            <canvas id="totalLeadsChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="dashboard-box" v-show="dashboardStore.views.marketingSource || dashboardStore.views.nurturing">
                                <div class="dashboard-sub-box">
                                    <div class="row">
                                        <div class="col-md-6" v-show="dashboardStore.views.marketingSource">
                                            <div id="marketing">
                                                <div class="dashboard-box pt-4">
                                                    <div class="dashboard-box-title d-flex align-items-center">
                                                        <h3>Marketing Source</h3>
                                                        <div class="dashboard-select ms-auto">
                                                            <div class="mt-2 select-date-dropdown">
                                                                <div class="dropdown">
                                                                    <div id="reportrangemarketing"
                                                                         style="color: white;background: #355ADD; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                                                                        <i class="fa fa-calendar"></i>&nbsp;
                                                                        <span></span> <i class="fa fa-caret-down"></i>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="dashboard-sub-box">
                                                        <div class="row">
                                                            <div class="col-md-12 leadsBySourceChartContainer">
                                                                <canvas id="leadsBySourceChart"></canvas>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="dashboard-sub-box">
                                                    <div id="zoho-marketing-dashboard-container">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6" v-show="dashboardStore.views.nurturing">
                                            <div id="nurturing">
                                                <div class="dashboard-box pt-4">
                                                    <div class="dashboard-box-title d-flex align-items-center">
                                                        <h3>Nurturing</h3>
                                                        <div class="dashboard-select ms-auto">
                                                            <div class="mt-2 select-date-dropdown">
                                                                <div class="dropdown">
                                                                    <div id="reportrangenurturing"
                                                                         style="color: white;background: #355ADD; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                                                                        <i class="fa fa-calendar"></i>&nbsp;
                                                                        <span></span> <i class="fa fa-caret-down"></i>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!-- <select class="form-select" v-model="dashboardStore.nurturingDateRange">
                                                                <option disabled selected value="0">Select</option>
                                                                <option value="1">Today</option>
                                                                <option value="2">Yesterday</option>
                                                                <option value="3">This Week</option>
                                                                <option value="4">Last 7 Days</option>
                                                                <option value="5">This Month</option>
                                                                <option value="6">Last 30 Days</option>
                                                                <option value="7">Previous Month</option>
                                                                <option value="8">Last 3 Months</option>
                                                                <option value="9">This Quarter</option>
                                                                <option value="10">Previous Quarter</option>
                                                                <option value="11">This Year</option>
                                                                <option value="12">Previous Year</option>
                                                                <option value="13">Last 14 Days</option>
                                                                <option value="14">Last 6 Month</option>
                                                                <option value="15">Previous 3 Months</option>
                                                            </select> -->
                                                        </div>
                                                    </div>
                                                    <div class="dashboard-sub-box ">
                                                        <table class="table table-dark table-striped">
                                                            <thead>
                                                            <tr>
                                                                <th scope="col">Month</th>
                                                                <th scope="col">Leads Nurtured</th>
                                                                <th scope="col">Consultation Booked</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <tr v-if="dashboardStore.nurturingData && Object.keys(dashboardStore.nurturingData).length>0"
                                                                v-for="(item, index) in dashboardStore.nurturingData">
                                                                <th scope="row">{{ index }}</th>
                                                                <td>{{ dashboardStore.nurturingData[index][1] }}</td>
                                                                <td>{{ dashboardStore.nurturingData[index][2] }}</td>
                                                            </tr>
                                                            <tr v-else>
                                                                <td colspan="4" class="text-center">Currently there is
                                                                    no nurturing data to
                                                                    display!
                                                                </td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="border p-4 border-white shadow-sm" style="border-width: 10px !important;border-radius: 8px; z-index:4; position: relative;" v-show="dashboardStore.views.leadActivity">
                        <div class="page-title d-flex align-items-center justify-content-between">
                            <h3>Recent Lead Activity</h3>
                            <router-link to="/crtx/leads">
                                <span class="text-body">View All Leads</span>
                                <svg fill="#000000" viewBox="0 0 24 24" width="24" height="24" id="right-arrow" data-name="Flat Color" xmlns="http://www.w3.org/2000/svg" class="icon flat-color"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><path id="primary" d="M21.71,11.29l-3-3a1,1,0,0,0-1.42,1.42L18.59,11H3a1,1,0,0,0,0,2H18.59l-1.3,1.29a1,1,0,0,0,0,1.42,1,1,0,0,0,1.42,0l3-3A1,1,0,0,0,21.71,11.29Z" style="fill: #000000;"></path></g></svg>
                            </router-link>
                        </div>
                        <div class="tab-data new-tab-data">
                            <div class="align-items-center d-lg-flex flex-row-reverse justify-content-between">

                                <div class="my-4 py-lg-4 my-lg-0 d-flex align-items-center justify-content-between lead-filter-serch-box">
                                    <div class="lead-search-box m-0 d-block d-lg-none">
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
                                            <input type="text" class="form-control" placeholder="Search Leads" v-model="dashboardStore.query"
                                                   v-on:keyup="dashboardStore.getRecentLeads()">
                                        </div>
                                    </div>
                                    <div class="filter-icons d-flex justify-content-end">
                                        <div class="dashboard-select ms-auto d-none d-lg-block mr-5">
                                            <select class="form-select exportSelect" v-model="dashboardStore.perPage"
                                                    v-on:change="dashboardStore.getRecentLeads();">
                                                <option disabled selected value="">Per Page</option>
                                                <option value="10">10</option>
                                                <option value="25">25</option>
                                                <option value="50">50</option>
                                                <option value="100">100</option>
                                            </select>
                                        </div>
                                        <div style="width:10px;"></div>
                                        <a href="#" class="filter-ico tooltip-ico px-3 btn border-0 bg-white" data-title="Column Preference" @click="showCategoryFilter = !showCategoryFilter"
                                           >
                                            <span class="ico-title text-nowrap">Edit Columns</span>
                                            <svg width="18" height="18" viewBox="0 0 18 18" xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M7.675 18V12.375H9.175V14.45H18V15.95H9.175V18H7.675ZM0 15.95V14.45H6.175V15.95H0ZM4.675 11.8V9.75H0V8.25H4.675V6.15H6.175V11.8H4.675ZM7.675 9.75V8.25H18V9.75H7.675ZM11.825 5.625V0H13.325V2.05H18V3.55H13.325V5.625H11.825ZM0 3.55V2.05H10.325V3.55H0Z"/>
                                            </svg>
                                            <!-- <span>Filter</span> -->
                                        </a>
                                        <div class="lead-filterBy-box " :class="showCategoryFilter? 'd-block' : 'd-none'">
                                            <div class="lead-filterBy-top d-flex align-items-center">
                                                <div class="filterBy-back">
                                                    <a href="#" class="filterBy-back-btn" @click="showCategoryFilter = false">
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
                                                <div class="form-check" v-for="(category, index) in dashboardStore.categories">
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
                                    </div>
                                </div>
<!--                                <div class="lead-search-box mx-auto d-none d-lg-block pe-3" style="max-width: 350px;">
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
                                        <input type="text" class="form-control" placeholder="Search Leads" v-model="dashboardStore.query"
                                               v-on:keyup="dashboardStore.getRecentLeads()">
                                    </div>
                                </div>-->
                                <ul class="m-2 nav nav-tabs" id="myTab" role="tablist">
    <li class="nav-item" role="presentation">
        <a class="nav-link active" id="follow-ups-tab" data-bs-toggle="tab"         data-bs-target="#follow-ups"
           type="button"
           role="tab"
           aria-controls="follow-ups"
           :aria-selected="selectedTab === 'follow-ups'"
           @click="changeTab('follow-ups')">
            Follow Ups
            <span class="ms-1 badge rounded-pill bg-light-gray text-dark">
                {{ dashboardStore.recentLeadsFollowUpCount }}
            </span>
        </a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link" id="scheduled-tab"
           data-bs-toggle="tab"
           data-bs-target="#scheduled"
           type="button"
           role="tab"
           aria-controls="scheduled"
           :aria-selected="selectedTab === 'scheduled'"
           @click="changeTab('scheduled')">
            Scheduled
            <span class="ms-1 badge rounded-pill bg-light-gray text-dark">
                {{ dashboardStore.recentLeadsScheduledCount }}
            </span>
        </a>
    </li>
</ul>
                            </div>
                            <div class="lead-table-box">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th @mouseenter="dashboardStore.hover_on = 'Full Name'" @mouseleave="dashboardStore.hover_on = ''"
                                            v-show="dashboardStore.categories[0].checked" @click="sortByField('Full Name')">Full Name
                                            <svg style="vertical-align:top;"
                                                 v-show="dashboardStore.sort_by=='Full Name' && dashboardStore.sort_order=='asc'" width="20px"
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
                                                 v-show="dashboardStore.sort_by=='Full Name' && dashboardStore.sort_order=='desc'"
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
                                                 v-show="dashboardStore.sort_by!='Full Name' && dashboardStore.hover_on == 'Full Name'"
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
                                        <th @mouseenter="dashboardStore.hover_on = 'Email'" @mouseleave="dashboardStore.hover_on = ''"
                                            v-show="dashboardStore.categories[1].checked" @click="sortByField('Email')">Email
                                            <svg style="vertical-align:top;"
                                                 v-show="dashboardStore.sort_by=='Email' && dashboardStore.sort_order=='asc'" width="20px"
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
                                                 v-show="dashboardStore.sort_by=='Email' && dashboardStore.sort_order=='desc'" width="20px"
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
                                                 v-show="dashboardStore.sort_by!='Email' && dashboardStore.hover_on == 'Email'" width="20px"
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
                                        <th @mouseenter="dashboardStore.hover_on = 'Source'" @mouseleave="dashboardStore.hover_on = ''"
                                            v-show="dashboardStore.categories[2].checked" @click="sortByField('Source')">Source
                                            <svg style="vertical-align:top;"
                                                 v-show="dashboardStore.sort_by=='Source' && dashboardStore.sort_order=='asc'" width="20px"
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
                                                 v-show="dashboardStore.sort_by=='Source' && dashboardStore.sort_order=='desc'" width="20px"
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
                                                 v-show="dashboardStore.sort_by!='Source' && dashboardStore.hover_on == 'Source'" width="20px"
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
                                        <th @mouseenter="dashboardStore.hover_on = 'Phone/Form Lead'"
                                            @mouseleave="dashboardStore.hover_on = ''" v-show="dashboardStore.categories[3].checked"
                                            @click="sortByField('Phone/Form Lead')">Lead Type
                                            <svg style="vertical-align:top;"
                                                 v-show="dashboardStore.sort_by=='Phone/Form Lead' && dashboardStore.sort_order=='asc'"
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
                                                 v-show="dashboardStore.sort_by=='Phone/Form Lead' && dashboardStore.sort_order=='desc'"
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
                                                 v-show="dashboardStore.sort_by!='Phone/Form Lead' && dashboardStore.hover_on == 'Phone/Form Lead'"
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
                                        <th @mouseenter="dashboardStore.hover_on = 'Phone'" @mouseleave="dashboardStore.hover_on = ''"
                                            v-show="dashboardStore.categories[4].checked" @click="sortByField('Phone')">Phone
                                            <svg style="vertical-align:top;"
                                                 v-show="dashboardStore.sort_by=='Phone' && dashboardStore.sort_order=='asc'" width="20px"
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
                                                 v-show="dashboardStore.sort_by=='Phone' && dashboardStore.sort_order=='desc'" width="20px"
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
                                                 v-show="dashboardStore.sort_by!='Phone' && dashboardStore.hover_on == 'Phone'" width="20px"
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
                                        <th @mouseenter="dashboardStore.hover_on = 'Date of Birth'" @mouseleave="dashboardStore.hover_on = ''"
                                            v-show="dashboardStore.categories[5].checked" @click="sortByField('Date of Birth')">Date of
                                            Birth
                                            <svg style="vertical-align:top;"
                                                 v-show="dashboardStore.sort_by=='Date of Birth' && dashboardStore.sort_order=='asc'"
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
                                                 v-show="dashboardStore.sort_by=='Date of Birth' && dashboardStore.sort_order=='desc'"
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
                                                 v-show="dashboardStore.sort_by!='Date of Birth' && dashboardStore.hover_on == 'Date of Birth'"
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
                                        <th @mouseenter="dashboardStore.hover_on = 'Created At'" @mouseleave="dashboardStore.hover_on = ''"
                                            v-show="dashboardStore.categories[6].checked" @click="sortByField('Created At')">Created At
                                            <svg style="vertical-align:top;"
                                                 v-show="dashboardStore.sort_by=='Created At' && dashboardStore.sort_order=='asc'"
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
                                                 v-show="dashboardStore.sort_by=='Created At' && dashboardStore.sort_order=='desc'"
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
                                                 v-show="dashboardStore.sort_by!='Created At' && dashboardStore.hover_on == 'Created At'"
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
                                        <th @mouseenter="dashboardStore.hover_on = 'Lead Score'" @mouseleave="dashboardStore.hover_on = ''"
                                            v-show="dashboardStore.categories[7].checked" @click="sortByField('Lead Score')">Lead Score
                                            <svg style="vertical-align:top;"
                                                 v-show="dashboardStore.sort_by=='Lead Score' && dashboardStore.sort_order=='asc'"
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
                                                 v-show="dashboardStore.sort_by=='Lead Score' && dashboardStore.sort_order=='desc'"
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
                                                 v-show="dashboardStore.sort_by!='Lead Score' && dashboardStore.hover_on == 'Lead Score'"
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
                                        <th @mouseenter="dashboardStore.hover_on = 'Tags'" @mouseleave="dashboardStore.hover_on = ''"
                                            v-show="dashboardStore.categories[8].checked" @click="sortByField('Tags')">Tags
                                            <svg style="vertical-align:top;"
                                                 v-show="dashboardStore.sort_by=='Tags' && dashboardStore.sort_order=='asc'" width="20px"
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
                                                 v-show="dashboardStore.sort_by=='Tags' && dashboardStore.sort_order=='desc'" width="20px"
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
                                                 v-show="dashboardStore.sort_by!='Tags' && dashboardStore.hover_on == 'Tags'" width="20px"
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
                                        <th @mouseenter="dashboardStore.hover_on = 'Landing Page'" @mouseleave="dashboardStore.hover_on = ''"
                                            v-show="dashboardStore.categories[9].checked" @click="sortByField('Landing Page')">Landing
                                            Page
                                            <svg style="vertical-align:top;"
                                                 v-show="dashboardStore.sort_by=='Landing Page' && dashboardStore.sort_order=='asc'"
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
                                                 v-show="dashboardStore.sort_by=='Landing Page' && dashboardStore.sort_order=='desc'"
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
                                                 v-show="dashboardStore.sort_by!='Landing Page' && dashboardStore.hover_on == 'Landing Page'"
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
                                    <div v-if="dashboardStore.recentLeads.length>0">
                                        <tr v-for="(lead, index) in dashboardStore.recentLeads" :key="lead.id">
                                            <td @click="viewProfile(lead.id)" v-if="dashboardStore.categories[0].checked"
                                                class= "d-none d-lg-flex align-items-center justify-content-center"><span
                                                style="text-wrap:balance">{{ lead.first_name + ' ' + (lead.last_name != null ? lead.last_name : '') }}</span>
                                            </td>
                                            <td @click="viewProfile(lead.id)" v-if="dashboardStore.categories[1].checked"
                                                class= "d-none d-lg-flex align-items-center justify-content-center"><span>{{ (lead.email) ? lead.email : '' }}</span></td>
                                            <td @click="viewProfile(lead.id)" v-if="dashboardStore.categories[2].checked"
                                                class="source-label"><span>{{ (lead.source_id) ? lead.source_id.name : '' }}</span>
                                            </td>
                                            <td @click="viewProfile(lead.id)" v-if="dashboardStore.categories[3].checked"
                                                class="phone-label"><span>{{ lead.phone_form }}</span></td>
                                            <td @click="viewProfile(lead.id)" v-if="dashboardStore.categories[4].checked"
                                                class= "d-none d-lg-flex align-items-center justify-content-center"><span>{{ lead.phone }}</span></td>
                                            <td @click="viewProfile(lead.id)" v-if="dashboardStore.categories[5].checked"
                                                class= "d-none d-lg-flex align-items-center justify-content-center"><span>{{ lead.dob }}</span></td>
                                            <td @click="viewProfile(lead.id)" v-if="dashboardStore.categories[6].checked"
                                                style="padding: 15px;"><span><span class="d-lg-none"></span>{{
                                                    (lead.created_at) ? new Date(lead.created_at).toLocaleDateString('en-us', {
                                                        year: "numeric",
                                                        month: "short",
                                                        day: "numeric",
                                                        hour: "numeric",
                                                        minute: "numeric"
                                                    }) : ''
                                                }}</span></td>
                                            <td @click="viewProfile(lead.id)"
                                                v-if="dashboardStore.categories[7].checked"
                                                class= "d-none d-lg-flex align-items-center justify-content-center"
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
                                            <td @click="viewProfile(lead.id)" v-if="dashboardStore.categories[8].checked"
                                                class= "d-none d-lg-flex align-items-center justify-content-center"><span>{{ lead.tagName }}</span></td>
                                            <td @click="viewProfile(lead.id)" v-if="dashboardStore.categories[9].checked"
                                                class= "d-none d-lg-flex align-items-center justify-content-center"><span>{{ lead.landing_page_url }}</span></td>
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
                                        <h6 class="text-center">Currently there are no Leads to display!</h6>
                                    </div>
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-between pt-3">
                                    <a v-if="dashboardStore.recentLeadsPage > 1" href="#" class="btn btn-primary" @click.prevent="prev">Prev</a>
                                    <div class="text-center" style="width:100%" v-if="dashboardStore.recentLeads.length>0"><span
                                        class="badge bg-primary" style="font-size: 16px; margin-top:10px;">{{
                                            dashboardStore.recentLeadsPage + '/' + dashboardStore.recentLeadsLastPage
                                        }}</span></div>
                                    <a v-if="dashboardStore.recentLeadsPage<dashboardStore.recentLeadsLastPage" href="#" class="btn btn-primary"
                                       @click.prevent="next">Next</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-show="dashboardStore.views.inbox">
                    <Inbox></Inbox>
                </div>

            </div>
        </div>
    </div>
</template>
<script>
import {storeToRefs} from 'pinia'
import {watch} from 'vue'
import {useAuthStore} from '../../stores/auth';
import {useDashboardStore} from '../../stores/dashboard';
import router from '../../routes'
import Inbox from './Inbox.vue';
import {useAlertStore} from "../../stores/alert";
import CKEditor from "@ckeditor/ckeditor5-vue";
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
import UploadAdapter from '../../stores/UploadAdapter';
import {useLeadStore} from "../../stores/lead";
import {useConsultationStore} from "../../stores/consultation";
import {useAppointmentStore} from "../../stores/appointment";

export default {
    setup() {
        const authStore = useAuthStore();

        const dashboardStore = useDashboardStore();

        const alertStore = useAlertStore();

        const leadStore = useLeadStore();

        const appointmentStore = useAppointmentStore();

        const consultationStore = useConsultationStore();

        return {authStore, dashboardStore, alertStore, leadStore, appointmentStore, consultationStore}
    },
    components:{Inbox, ckeditor: CKEditor.component,},
    props: ['env'],
    data() {
        return {
            isHovered: false,
            showViews:false,
            showCategoryFilter: false,
            isSidebarVisible: false,
            editor: ClassicEditor,
            selectedTab: "follow-ups",
            editorConfig: {
                extraPlugins: [this.uploader],
            },
            selectedLead: {first_name: '', last_name: '', consultation_booked_date: null, status_id: null, Notes: []},
        }
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
    created() {
        this.dashboardStore.currentTab = 'follow-ups';
    },
    mounted() {

        let _self = this;

        $('#mailLink').removeClass('active');
        $('#profileLink').removeClass('show');
        $('#homeLink').addClass('active');

        this.dashboardStore.getKPITreatmentsSold();

        this.dashboardStore.getMarketingLeadsBySource();

        this.dashboardStore.getNurturing();

        this.updateSalesSummary();

        this.dashboardStore.getRecentLeads('follow-ups')
        this.dashboardStore.getRecentLeadsCount();

        this.appointmentStore.getAvailableTimes(moment().add(1, 'day').format('MM-DD-YYYY'));

        if (localStorage.getItem('dashboard-categories') != null) {
            this.dashboardStore.categories = JSON.parse(localStorage.getItem('dashboard-categories'));
        }

        if (localStorage.getItem('dashboard-views') != null) {
            this.dashboardStore.views = JSON.parse(localStorage.getItem('dashboard-views'));
        }

        const {
            treatmentSoldChart,
            totalLeadsChart,
            customMessage,
            zohomarketingdashboard,
            // salesSummaryDateRange,
            //leadsBySourceDateRange,
            leadsBySourceChart,
            //nurturingDateRange
        } = storeToRefs(this.dashboardStore);

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

        watch(
            () => treatmentSoldChart,
            function () {
                _self.updateTreatmentSoldChart()
            },
            {deep: true}
        );

        watch(
            () => totalLeadsChart,
            function () {
                _self.updateTotalLeadsChart()
            },
            {deep: true}
        );

        watch(
            () => customMessage,
            function () {
                _self.updateCustomMessage()
            },
            {deep: true}
        );

        watch(
            () => zohomarketingdashboard,
            function () {
                _self.updateZohoMarketingDashboard()
            },
            {deep: true}
        );

        // watch(
        // 	() => salesSummaryDateRange,
        // 	function(){
        // 		_self.updateSalesSummary();
        // 	},
        // 	{ deep: true }
        // );

        // watch(
        // 	() => leadsBySourceDateRange,
        // 	function(){
        // 		_self.dashboardStore.getMarketingLeadsBySource()
        // 	},
        // 	{ deep: true }
        // );

        watch(
            () => leadsBySourceChart,
            function () {
                _self.updateLeadsBySourceChart()
            },
            {deep: true}
        );

        // watch(
        // 	() => nurturingDateRange,
        // 	function(){
        // 		_self.dashboardStore.getNurturing()
        // 	},
        // 	{ deep: true }
        // );


        let ranges = {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'This Week': [moment().startOf('week').add(1, 'days'), moment().endOf('week')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 14 Days': [moment().subtract(14, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Previous Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
            'Previous 3 Months': [moment().subtract(3, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
            'Last 3 Month': [moment().subtract(3, 'month'), moment()],
            'Last 6 Months': [moment().subtract(6, 'month'), moment()],
            'This Quarter': [moment().subtract(3, 'month').startOf('month'), moment().endOf('month')],
            'Previous Quarter': [moment().subtract(6, 'month').startOf('month'), moment().subtract(3, 'month').endOf('month')],
            'This Year': [moment().startOf('year'), moment().endOf('year')],
            'Previous Year': [moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year').endOf('year')],
            'All Time': ['01/01/2000', moment().endOf('year')],
        };

        // KPI Dropdown

        function cb(start, end) {
            $('#reportrange span').html(start.format('MM/DD/YYYY') + ' - ' + end.format('MM/DD/YYYY'));
        }

        $('#reportrange').daterangepicker({
            startDate: this.dashboardStore.salesSummaryDateRangeStart,
            endDate: this.dashboardStore.salesSummaryDateRangeEnd,
            locale: {
                format: 'MM/DD/YYYY'
            },
            ranges: ranges
        }, cb);

        $('#reportrange').on('apply.daterangepicker', function (ev, picker) {
            _self.dashboardStore.salesSummaryDateRangeStart = picker.startDate;
            _self.dashboardStore.salesSummaryDateRangeEnd = picker.endDate;
            cb(_self.dashboardStore.salesSummaryDateRangeStart, _self.dashboardStore.salesSummaryDateRangeEnd);
            _self.updateSalesSummary();
        });

        cb(this.dashboardStore.salesSummaryDateRangeStart, this.dashboardStore.salesSummaryDateRangeEnd);

        // Marketing Dropdown

        function cbmarketing(start, end) {
            $('#reportrangemarketing span').html(start.format('MM/DD/YYYY') + ' - ' + end.format('MM/DD/YYYY'));
        }

        $('#reportrangemarketing').daterangepicker({
            startDate: this.dashboardStore.marketingDateRangeStart,
            endDate: this.dashboardStore.marketingDateRangeEnd,
            locale: {
                format: 'MM/DD/YYYY'
            },
            ranges: ranges
        }, cbmarketing);

        $('#reportrangemarketing').on('apply.daterangepicker', function (ev, picker) {
            _self.dashboardStore.marketingDateRangeStart = picker.startDate;
            _self.dashboardStore.marketingDateRangeEnd = picker.endDate;
            cb(_self.dashboardStore.marketingDateRangeStart, _self.dashboardStore.marketingDateRangeEnd);
            _self.dashboardStore.getMarketingLeadsBySource();
        });

        cbmarketing(this.dashboardStore.marketingDateRangeStart, this.dashboardStore.marketingDateRangeEnd);

        // Nurturing

        function cbnurturing(start, end) {
            $('#reportrangenurturing span').html(start.format('MM/DD/YYYY') + ' - ' + end.format('MM/DD/YYYY'));
        }

        $('#reportrangenurturing').daterangepicker({
            startDate: this.dashboardStore.nurturingDateRangeStart,
            endDate: this.dashboardStore.nurturingDateRangeEnd,
            locale: {
                format: 'MM/DD/YYYY'
            },
            ranges: ranges
        }, cbnurturing);

        $('#reportrangenurturing').on('apply.daterangepicker', function (ev, picker) {
            _self.dashboardStore.nurturingDateRangeStart = picker.startDate;
            _self.dashboardStore.nurturingDateRangeEnd = picker.endDate;
            cb(_self.dashboardStore.nurturingDateRangeStart, _self.dashboardStore.nurturingDateRangeEnd);
            _self.dashboardStore.getNurturing();
        });

        cbnurturing(this.dashboardStore.nurturingDateRangeStart, this.dashboardStore.nurturingDateRangeEnd);

        $(document).on('click touchstart', function (e) {
            const categoryBox = $('.lead-filterBy-box');
            const filterIco = $('.filter-ico');
            if (!categoryBox.is(e.target) && categoryBox.has(e.target).length === 0 && !filterIco.is(e.target) && filterIco.has(e.target).length === 0) {
                _self.showCategoryFilter = false;
                _self.showViews = false;
            }
        });

        this.initDateTimePicker();
        this.latestNote = this.latestNoteContent;
    },
    computed : {
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
    methods: {
        moment(...args){
            return moment(...args);
        },
        updateTreatmentSoldChart() {

            let _self = this;

            if (this.dashboardStore.treatmentSoldChart) {

                let ctxTreatmentSold = null;

                $('#treatmentSoldChart').remove();

                $('.treatmentSoldChartContainer').append('<canvas id="treatmentSoldChart"></canvas>');

                let USDollar = new Intl.NumberFormat('en-US', {
                    style: 'currency',
                    currency: 'USD',
                });

                ctxTreatmentSold = document.getElementById('treatmentSoldChart').getContext('2d');
                new Chart(ctxTreatmentSold, {
                    type: 'bar',
                    data: {
                        labels: _self.dashboardStore.treatmentSoldChart.y_data,
                        datasets: [
                            {
                                label: "Treatments Sold",
                                backgroundColor: "#425BCF",
                                data: _self.dashboardStore.treatmentSoldChart.x_data
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        legend: {display: false},
                        title: {
                            display: true,
                            text: 'Treatments Sold'
                        },
                        scales: {
                            y: {
                                ticks: {
                                    // Include a dollar sign in the ticks
                                    callback: function (value, index, ticks) {
                                        return USDollar.format(value);
                                    }
                                }
                            }
                        }
                    }
                });
            }
        },
        updateTotalLeadsChart() {

            let _self = this;

            if (this.dashboardStore.totalLeadsChart) {

                let ctxTotalLeads = null;

                $('#totalLeadsChart').remove();

                $('.totalLeadsChartContainer').append('<canvas id="totalLeadsChart"></canvas>');

                ctxTotalLeads = document.getElementById('totalLeadsChart').getContext('2d');
                new Chart(ctxTotalLeads, {
                    type: 'bar',
                    data: {
                        labels: _self.dashboardStore.totalLeadsChart.y_data,
                        datasets: [
                            {
                                label: "Total Leads",
                                backgroundColor: "#425BCF",
                                data: _self.dashboardStore.totalLeadsChart.x_data
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        legend: {display: false},
                        title: {
                            display: true,
                            text: 'Total Leads'
                        },
                    }
                });
            }
        },
        updateCustomMessage() {
            document.getElementById("custom-message-container").innerHTML = this.dashboardStore.customMessage;
        },
        updateZohoMarketingDashboard() {
            document.getElementById("zoho-marketing-dashboard-container").innerHTML = this.dashboardStore.zohomarketingdashboard;
        },
        updateSalesSummary() {
            this.dashboardStore.getKPISalesSummary(this.dashboardStore.salesSummaryDateRangeStart.format('YYYY-MM-DD'), this.dashboardStore.salesSummaryDateRangeEnd.format('YYYY-MM-DD'));
        },
        updateLeadsBySourceChart() {
            let _self = this;

            if (this.dashboardStore.leadsBySourceChart) {

                let ctxLeadsBySource = null;

                $('#leadsBySourceChart').remove();

                $('.leadsBySourceChartContainer').append('<canvas id="leadsBySourceChart"></canvas>');

                ctxLeadsBySource = document.getElementById('leadsBySourceChart').getContext('2d');
                new Chart(ctxLeadsBySource, {
                    type: 'bar',
                    data: {
                        labels: _self.dashboardStore.leadsBySourceChart.x_data,
                        datasets: [
                            {
                                label: "Marketing Leads By Source",
                                backgroundColor: "#425BCF",
                                data: _self.dashboardStore.leadsBySourceChart.y_data
                            }
                        ]
                    },
                    options: {
                        indexAxis: 'y',
                        responsive: true,
                        legend: {display: false},
                        title: {
                            display: true,
                            text: 'Total Leads'
                        }
                    }
                });
            }
        },
        redirectLink(page, lifeTimeValue = '') {
            if (lifeTimeValue) {
                router.push({
                    name: 'report',
                    query: {
                        data: page,
                        date: '01/01/2000-' + this.dashboardStore.salesSummaryDateRangeEnd.format('MM/DD/YYYY'),
                        type: 'lifetimevalue'
                    }
                });
            } else {
                router.push({
                    name: 'report',
                    query: {
                        data: page,
                        date: this.dashboardStore.salesSummaryDateRangeStart.format('MM/DD/YYYY') + '-' + this.dashboardStore.salesSummaryDateRangeEnd.format('MM/DD/YYYY')
                    }
                });
            }
        },
        getRecentLeads(type){
            this.dashboardStore.currentTab = type;
            this.dashboardStore.recentLeadsPage = 1
            this.dashboardStore.recentLeadsLastPage = 1;
            this.dashboardStore.getRecentLeads();
        },
        changeTab(tab) {
            this.selectedTab = tab;
            this.getRecentLeads(tab); // Call the API function
        },
        prev(){
            this.dashboardStore.recentLeadsPage--;
            this.dashboardStore.getRecentLeads();
        },
        next(){
            this.dashboardStore.recentLeadsPage++;
            this.dashboardStore.getRecentLeads();
        },
        viewProfile(id) {
            localStorage.setItem('last_path', '/crtx/patient-profile/' + id);
            window.open('/crtx/patient-profile/' + id, '_blank');
        },
        saveCategories() {
            localStorage.setItem('dashboard-categories', JSON.stringify(this.dashboardStore.categories));
            this.showCategoryFilter = false;
            this.alertStore.success = true;
            this.alertStore.message = 'Column preferences have been saved successfully!';
        },
        saveViews(){
            localStorage.setItem('dashboard-views', JSON.stringify(this.dashboardStore.views));
            this.showViews = false;
            this.alertStore.success = true;
            this.alertStore.message = 'Dashboard View preferences have been saved successfully!';
        },
        sortByField(field) {
            if (this.dashboardStore.sort_by == field) {
                if (this.dashboardStore.sort_order == 'asc') {
                    this.dashboardStore.sort_order = 'desc';
                } else {
                    this.dashboardStore.sort_order = 'asc';
                }
            } else {
                this.dashboardStore.sort_by = field;
                this.dashboardStore.sort_order = 'desc';
            }
            this.dashboardStore.getRecentLeads();
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
        toggleSidebar(leadId) {
            //this.isSidebarVisible = !this.isSidebarVisible; // Toggle sidebar visibility
            this.selectedLeadId = leadId;
            this.selectedLead = this.dashboardStore.recentLeads.find(lead => lead.id === leadId);
            this.isSidebarVisible = true;
        },
        initDateTimePicker() {
            let _self = this;

            $.datetimepicker.setDateFormatter('moment');

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
                hours12: false,
                ampm: true,
                formatTime: 'hh:mm A',
            });
        },
        updateDateTimePickerValue(newValue) {
            $('#bookDate').datetimepicker({
                value: newValue,
            });
        },
        uploader(editor) {
            editor.plugins.get('FileRepository').createUploadAdapter = (loader) => {
                return new UploadAdapter(loader);
            };
        },
        quickupdate(leadId) {
            if (this.selectedLead.status_id === 9 && !this.selectedLead.reason) {
                this.showLostReasonError = true;
                this.profile_editing = true;
                this.showMoreProfile = true;
                return null;
            }



            let foundLead = this.dashboardStore.recentLeads.find(lead => lead.id === leadId);

            if (foundLead) {
                if (this.selectedTab === 'scheduled') {
                    // Use consultationStore when selectedTab is 'scheduled'
                    this.consultationStore.quickupdatesidebar(foundLead, this.dashboardStore.recentLeads);
                } else {
                    // Use leadStore for other cases
                    this.leadStore.quickupdatesidebar(foundLead, this.dashboardStore.recentLeads);
                }
            }
            //this.profile_editing = false;
            this.isSidebarVisible = false; // Close the sidebar
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
        filterCheckedWidth() {
            let catCount = this.checkedCategories.length;
            $('.lead-table-box table tr').css('grid-template-columns', 'repeat(' + catCount + ', 1fr)');
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
    },
}
</script>

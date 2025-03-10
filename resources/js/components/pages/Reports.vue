<template>
    <div id="main"  class="bg-light-gray reports">
        <div class="container-fluid p-0">
            <div class="px-0 py-0 mt-4">
				<div class="report-box">
					<div class="report-top-box d-flex align-items-center justify-content-between">
						<h4 class="m-0">Reports</h4>
						<div class="dashboard-select ms-auto d-none d-lg-block">
							<select class="form-select exportSelect" v-model="reportStore.format">
								<option disabled selected value="">Export</option>
								<option value="csv">Export as CSV</option>
								<option value="pdf">Export as PDF</option>
								<option value="xlsx">Export as XLSX</option>
							</select>
						</div>
					</div>
					<div class="report-list d-lg-none">
						<ul>
							<li><a role="button" :class="reportStore.tab == 'New Leads' ? 'active' : ''" @click="filterTabStatus('New Leads')">New Leads</a></li>
							<li><a role="button" :class="reportStore.tab == 'Engaged' ? 'active' : ''" @click="filterTabStatus('Engaged')">Engaged</a></li>
							<li><a role="button" :class="reportStore.tab == 'Consultations Booked' ? 'active' : ''" @click="filterTabStatus('Consultations Booked')">Consultations Booked</a></li>
							<!-- <li><a role="button" :class="reportStore.tab == 'Consultations Showed' ? 'active' : ''" @click="filterTabStatus('Consultations Showed')">Consultations Showed</a></li>
							<li><a role="button" :class="reportStore.tab == 'Treatments Sold' ? 'active' : ''" @click="filterTabStatus('Treatments Sold')">Treatments Sold</a></li>
							<li><a role="button" :class="reportStore.tab == 'Treatments Presented' ? 'active' : ''" @click="filterTabStatus('Treatments Presented')">Treatments Presented</a></li> -->
							<li><a role="button" :class="reportStore.tab == 'Consultations Follow Up' ? 'active' : ''" @click="filterTabStatus('Consultations Follow Up')">Consultations Follow Up</a></li>
							<li><a role="button" :class="reportStore.tab == 'Nurturing' ? 'active' : ''" @click="filterTabStatus('Nurturing')">Nurturing</a></li>
                            <!-- <li><a role="button" :class="reportStore.tab == 'Treatments Sold (PMS)' ? 'active' : ''" @click="filterTabStatus('Treatments Sold (PMS)')">Treatments Sold (PMS)</a></li> -->
						</ul>
					</div>
					<div class="report-list-top d-flex align-items-center">
						<div class="report-list d-none d-lg-flex">
							<ul>
							<li><a role="button" :class="reportStore.tab == 'New Leads' ? 'active' : ''" @click="filterTabStatus('New Leads')">All Leads</a></li>
							<li><a role="button" :class="reportStore.tab == 'Engaged' ? 'active' : ''" @click="filterTabStatus('Engaged')">Engaged</a></li>
							<li><a role="button" :class="reportStore.tab == 'Consultations Booked' ? 'active' : ''" @click="filterTabStatus('Consultations Booked')">Scheduled</a></li>
							<!--<li><a role="button" :class="reportStore.tab == 'Consultations Showed' ? 'active' : ''" @click="filterTabStatus('Consultations Showed')">Consultations Showed</a></li>-->
<!--							<li><a role="button" :class="reportStore.tab == 'Treatments Sold' ? 'active' : ''" @click="filterTabStatus('Treatments Sold')">Treatments Sold</a></li>-->
<!--							<li><a role="button" :class="reportStore.tab == 'Treatments Presented' ? 'active' : ''" @click="filterTabStatus('Treatments Presented')">Treatments Presented</a></li>-->
							<li><a role="button" :class="reportStore.tab == 'Consultations Follow Up' ? 'active' : ''" @click="filterTabStatus('Consultations Follow Up')">Follow Up</a></li>
                            <li><a role="button" :class="reportStore.tab == 'Nurturing' ? 'active' : ''" @click="filterTabStatus('Nurturing')">Nurturing</a></li>
<!--                            <li><a role="button" :class="reportStore.tab == 'Treatments Sold (PMS)' ? 'active' : ''" @click="filterTabStatus('Treatments Sold (PMS)')">Treatments Sold (PMS)</a></li>-->
							</ul>
						</div>
						<div class="dashboard-select d-lg-none me-2 w-100">
							<select role="button" class="form-select exportSelect" v-model="reportStore.perPage" v-on:change="recordsPerPageChange()">
								<option disabled selected value="">Records Per Page</option>
								<option value="10">10</option>
								<option value="25">25</option>
								<option value="50">50</option>
								<option value="100">100</option>
							</select>
						</div>
						<div class="dashboard-select d-lg-none me-2 w-100">
							<select role="button" class="form-select exportSelect" v-model="reportStore.format">
								<option disabled selected value="">Export</option>
								<option value="csv">Export as CSV</option>
								<option value="pdf">Export as PDF</option>
								<option value="xlsx">Export as XLSX</option>
							</select>
						</div>
					</div>
                    <div class="report-list-top d-flex align-items-center justify-content-end">
                        <div class="mt-2 select-date-dropdown">
                            <div class="dropdown">
								<div id="reportrange" style="color: white;background: #355ADD; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
									<i class="fa fa-calendar"></i>&nbsp;
									<span></span> <i class="fa fa-caret-down"></i>
								</div>
                            </div>
                        </div>
                    </div>
					<div class="report-middle-box">
						<div id="chartContainer">
							<canvas id="myChart"></canvas>
						</div>
					</div>
					<div class="report-lead-main-box d-lg-flex align-items-center justify-content-between mt-3">
						<div ref="dropdownContainer" @click="closeDropdownsOnOutsideClick" class="d-flex flex-lg-row flex-row-reverse report-lead-left">
							<span class="d-inline-block me-2 pt-2 mt-1">Filters</span>
							<div class="align-items-center d-flex flex-lg-row flex-row-reverse flex-wrap">
                                <div class="dropdown">
                                    <div class="dropdown-toggle" type="button" id="dropdownMenuButton3" @click="toggleDropdown('leadType')">
                                        <span>{{reportStore.status}}</span>
                                    </div>
                                    <div class="dropdown-menu custom-scroll" :class="showLeadTypeFilter? 'show' : ''" aria-labelledby="dropdownMenuButton3" v-show="showLeadTypeFilter">
                                        <ul>
                                            <li @click="filterStatus('Select Stage', 0)"><a role="button">Select Stage</a></li>
                                            <li v-for="status in resourceStore.statuses" @click="filterStatus(status.name, status.id)"><a role="button">{{ status.name }}</a></li>
											<li @click="filterStatus('Treatments Sold', 'treatments_sold')">
												<a role="button">Treatments Sold</a>
											</li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="dropdown">
                                    <div class="dropdown-toggle" type="button" id="dropdownMenuButton4" @click="toggleDropdown('mediaType')">
                                        <span>{{ reportStore.source }}</span>
                                    </div>
                                    <div class="dropdown-menu custom-scroll" :class="showMediaTypeFilter? 'show' : ''" aria-labelledby="dropdownMenuButton4" v-show="showMediaTypeFilter">
                                        <ul>
                                            <li @click="filterSource('Select Source', 0)"><a role="button">Select Source</a></li>
                                            <li v-for="source in resourceStore.sources" @click="filterSource(source.source_name, source.id)"><a role="button">{{ source.source_name }}</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="dropdown">
                                    <div class="dropdown-toggle" type="button" id="dropdownMenuButton5" @click="toggleDropdown('tagType')">
                                        <span>{{ reportStore.selectedTagName }}</span>
                                    </div>
                                    <div class="dropdown-menu custom-scroll" :class="showTagTypeFilter ? 'show' : ''" aria-labelledby="dropdownMenuButton5" v-show="showTagTypeFilter">
                                        <ul>
                                        <li @click="filterTag('Select Tag', 0)"><a role="button">Select Tag</a></li>
                                        <li v-for="tag in reportStore.savedTags" :key="tag.id" @click="filterTag(tag.tagName, tag.id)"><a role="button">{{ tag.tagName }}</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="dropdown">
                                    <div class="dropdown-toggle" type="button" id="dropdownMenuButton6" @click="toggleDropdown('type')">
                                        <span>{{ reportStore.selectedType === null ? 'Select Type' : reportStore.selectedType }}</span>
                                    </div>
                                    <div class="dropdown-menu custom-scroll" :class="showTypeFilter ? 'show' : ''" aria-labelledby="dropdownMenuButton5" v-show="showTypeFilter">
                                        <ul>
                                            <li @click="filterType(null)"><a role="button">Select Type</a></li>
                                            <li v-for="type in reportStore.savedTypes" :key="type" @click="filterType(type)"><a role="button">{{ type }}</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="dropdown">
                                    <div class="dropdown-toggle" type="button" id="dropdownMenuButton7" @click="toggleDropdown('reason')">
                                        <span>{{ reportStore.selectedReason === null ? 'Select Reason' : reportStore.selectedReason.label }}</span>
                                    </div>
                                    <div class="dropdown-menu custom-scroll" :class="showReasonFilter ? 'show' : ''" aria-labelledby="dropdownMenuButton5" v-show="showReasonFilter">
                                        <ul>
                                            <li @click="filterReason(null)"><a role="button">Select Reason</a></li>
                                            <li v-for="reason in reportStore.savedReasons" :key="reason" @click="filterReason(reason)"><a role="button">{{ reason.label }}</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
						</div>
						<div class="ms-1 report-lead-right">
							<figure>
								<svg version="1.1" x="0px" y="0px" viewBox="0 0 42 42" style="enable-background:new 0 0 42 42;">
									<g>
										<path class="st0" d="M12.2,30.8L29,13.9L29,24c0,0.5,0.4,0.9,0.9,0.9c0.5,0,0.9-0.4,0.9-0.9l0-12c0-0.5-0.4-0.9-0.9-0.9l-12,0
											c-0.5,0-0.9,0.4-0.9,0.9c0,0.5,0.4,0.9,0.9,0.9l9.9-0.2L11,29.6c-0.3,0.3-0.3,0.9,0,1.2C11.3,31.1,11.8,31.1,12.2,30.8z"/>
									</g>
								</svg>
							</figure>
							
                            <span v-if="type === 'lifetimevalue'" class="d-flex align-items-center" style="white-space: nowrap;">
								<span>${{ reportStore.total_lifetimevalue }}</span>
								Life Time Value
							</span>
							<span v-else-if="reportStore.status_id == '18' || reportStore.status_id === 'treatments_sold'" class="d-flex align-items-center" style="white-space: nowrap;">
								<span>${{ reportStore.total_amounts }}</span>
								{{ reportStore.tab }}
							</span>
							<span v-else-if="reportStore.tab !== 'Treatments Sold' && reportStore.tab !== 'Treatments Sold (PMS)' && reportStore.tab !== 'Treatments Presented'" class="d-flex align-items-center"  style="white-space: nowrap;">
								<span>{{ reportStore.total_item }}</span>
								{{ reportStore.tab }}
							</span>
							<span v-else class="d-flex align-items-center"  style="white-space: nowrap;">
								<span>${{ reportStore.total_amounts }}</span>
								{{ reportStore.tab }}
							</span>
						</div>
					</div>
				</div>
				<div class="my-3 py-lg-4 d-flex align-items-center report-lead-filter-serch-box">
					<div class="table-data-title me-auto d-lg-none">
						Users
					</div>
                        <div class="filter-icons">
                            <a role="button" class="filter-ico tooltip-ico btn border-0 bg-white text-dar px-4" data-title="Column Preference" v-if="showCategoryFilter">
                                Edit columns<svg class="me-0 ms-2" width="18" height="18" viewBox="0 0 18 18" xmlns="http://www.w3.org/2000/svg">
                                <path d="M7.675 18V12.375H9.175V14.45H18V15.95H9.175V18H7.675ZM0 15.95V14.45H6.175V15.95H0ZM4.675 11.8V9.75H0V8.25H4.675V6.15H6.175V11.8H4.675ZM7.675 9.75V8.25H18V9.75H7.675ZM11.825 5.625V0H13.325V2.05H18V3.55H13.325V5.625H11.825ZM0 3.55V2.05H10.325V3.55H0Z"/>
                                </svg>
                            </a>
                            <div class="lead-filterBy-box start-0">
                                <div class="lead-filterBy-top d-flex align-items-center">
                                    <!-- <div class="filterBy-back">
                                        <a href="#" class="filterBy-back-btn">
                                            <svg width="20" height="14" viewBox="0 0 20 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M19.4411 6.37503H2.04859L7.25172 1.19191C7.49609 0.947534 7.49609 0.551909 7.25172 0.308159C7.00734 0.0637842 6.61172 0.0637842 6.36797 0.308159L0.180469 6.49503C-0.0601562 6.73566 -0.0601562 7.13816 0.180469 7.37878L6.36797 13.5663C6.61234 13.8107 7.00797 13.8107 7.25172 13.5663C7.49609 13.3219 7.49609 12.9263 7.25172 12.6825L2.04859 7.62503H19.4411C19.7861 7.62503 20.0661 7.34503 20.0661 7.00003C20.0661 6.65503 19.7861 6.37503 19.4411 6.37503Z" fill="#514F5F"></path>
                                            </svg>
                                        </a>
                                    </div> -->
                                    <div class="filterBy-title me-lg-auto">
                                        <h5>Categories</h5>
                                    </div>
                                    <div class="filterBy-save ms-auto ms-lg-0">
                                        <a role="button" class="filterBy-save-btn" @click="saveCategories()">
                                            Save
                                        </a>
                                        <a role="button" class="filterBy-viewall-save" @click="saveCategories()">
                                            Save
                                        </a>
                                        <a href="#" class="filterBy-viewall-btn">
                                            View all
                                            <svg width="9" height="15" viewBox="0 0 9 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M6.20141 8.06186L0.998281 13.245C0.753906 13.4894 0.753906 13.885 0.998281 14.1287C1.24266 14.3731 1.63828 14.3731 1.88203 14.1287L8.06953 7.94186C8.31016 7.70123 8.31016 7.29873 8.06953 7.05811L1.88203 0.870606C1.63766 0.626231 1.24203 0.626231 0.998281 0.870606C0.753906 1.11498 0.753906 1.51061 0.998281 1.75436L6.20141 6.81186L6.75 7.43686L6.20141 8.06186Z" fill="#514F5F"/>
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
                                    <div class="form-check" v-for="(category, index) in reportStore.categories">
                                        <input class="form-check-input" type="checkbox" :id="category.name" value="1" v-model="category.checked" @change="filterCheckedWidth">
                                        <label class="form-check-label" :for="category.name">{{category.name}}</label>
                                    </div>
                                </div>
                                <!--							<div class="apply-btn d-lg-none text-end mt-3">
                                                                <button type="button" class="btn btn-primary">Apply</button>
                                                            </div>-->
                            </div>
                        </div>
					<div class="lead-search-box ms-auto">
						<span class="lead-search-ico d-lg-none">
							<svg width="25" height="25" viewBox="0 0 25 25" xmlns="http://www.w3.org/2000/svg">
								<path d="M22.6661 24.0588L13.8995 15.2922C13.2328 15.8699 12.4555 16.3199 11.5675 16.6422C10.6795 16.9644 9.73464 17.1255 8.73281 17.1255C6.32921 17.1255 4.29498 16.2922 2.63011 14.6255C0.965246 12.9588 0.132812 10.9477 0.132812 8.59216C0.132812 6.2366 0.966146 4.22549 2.63281 2.55882C4.29948 0.892156 6.31615 0.0588226 8.68281 0.0588226C11.0495 0.0588226 13.0606 0.892156 14.7161 2.55882C16.3717 4.22549 17.1995 6.23827 17.1995 8.59716C17.1995 9.54938 17.0439 10.4699 16.7328 11.3588C16.4217 12.2477 15.955 13.081 15.3328 13.8588L24.1328 22.5922L22.6661 24.0588ZM8.69948 15.1255C10.505 15.1255 12.0398 14.4866 13.3036 13.2088C14.5675 11.931 15.1995 10.3922 15.1995 8.59216C15.1995 6.79216 14.5675 5.25327 13.3036 3.97549C12.0398 2.69771 10.505 2.05882 8.69948 2.05882C6.87541 2.05882 5.32495 2.69771 4.04808 3.97549C2.77123 5.25327 2.13281 6.79216 2.13281 8.59216C2.13281 10.3922 2.77123 11.931 4.04808 13.2088C5.32495 14.4866 6.87541 15.1255 8.69948 15.1255Z"/>
							</svg>
						</span>
						<div class="lead-search">


							<span class="search-input-ico">
								<svg width="25" height="25" viewBox="0 0 25 25" xmlns="http://www.w3.org/2000/svg">
									<path d="M22.6661 24.0588L13.8995 15.2922C13.2328 15.8699 12.4555 16.3199 11.5675 16.6422C10.6795 16.9644 9.73464 17.1255 8.73281 17.1255C6.32921 17.1255 4.29498 16.2922 2.63011 14.6255C0.965246 12.9588 0.132812 10.9477 0.132812 8.59216C0.132812 6.2366 0.966146 4.22549 2.63281 2.55882C4.29948 0.892156 6.31615 0.0588226 8.68281 0.0588226C11.0495 0.0588226 13.0606 0.892156 14.7161 2.55882C16.3717 4.22549 17.1995 6.23827 17.1995 8.59716C17.1995 9.54938 17.0439 10.4699 16.7328 11.3588C16.4217 12.2477 15.955 13.081 15.3328 13.8588L24.1328 22.5922L22.6661 24.0588ZM8.69948 15.1255C10.505 15.1255 12.0398 14.4866 13.3036 13.2088C14.5675 11.931 15.1995 10.3922 15.1995 8.59216C15.1995 6.79216 14.5675 5.25327 13.3036 3.97549C12.0398 2.69771 10.505 2.05882 8.69948 2.05882C6.87541 2.05882 5.32495 2.69771 4.04808 3.97549C2.77123 5.25327 2.13281 6.79216 2.13281 8.59216C2.13281 10.3922 2.77123 11.931 4.04808 13.2088C5.32495 14.4866 6.87541 15.1255 8.69948 15.1255Z"/>
								</svg>
							</span>
							<input type="text" class="form-control" placeholder="Search" v-model="reportStore.query" v-on:keyup="search">
						</div>
					</div>
					<div class="filter-icons d-flex justify-content-end">
						<div class="dashboard-select ms-auto d-none d-lg-block mr-5">
							<select class="form-select exportSelect" v-model="reportStore.perPage" v-on:change="recordsPerPageChange()">
								<option disabled selected value="">Per Page</option>
								<option value="10">10</option>
								<option value="25">25</option>
								<option value="50">50</option>
								<option value="100">100</option>
							</select>
						</div>
						<div style="width:10px;"></div>
					</div>
				</div>

				<div class="report-table-box">
					<table class="table">
						<thead>
							<tr>
								<!-- <th v-show="reportStore.categories[0].checked" @click="sortByField('Clinic')">Clinic
								<svg style="vertical-align:top;" v-show="reportStore.sort_by=='Clinic' && reportStore.sort_order=='asc'"  width="20px" height="20px" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path fill-rule="evenodd" clip-rule="evenodd" d="M12.0809 7.28641L12.4345 6.93286L12.788 7.28641L17 11.4984L16.2929 12.2055L12.9345 8.84707V16.9999H11.9345V8.84707L8.57605 12.2055L7.86895 11.4984L12.0809 7.28641Z" fill="#000000"></path> </g></svg>
									<svg style="vertical-align:top;" v-show="reportStore.sort_by=='Clinic' && reportStore.sort_order=='desc'"  width="20px" height="20px" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path fill-rule="evenodd" clip-rule="evenodd" d="M11.9191 16.7136L11.5655 17.0671L11.212 16.7136L7 12.5016L7.70711 11.7945L11.0655 15.1529V7.00009H12.0655V15.1529L15.4239 11.7945L16.1311 12.5016L11.9191 16.7136Z" fill="#000000"></path> </g></svg>
								</th> -->
								<th @mouseenter="reportStore.hover_on = 'First Name'" @mouseleave="reportStore.hover_on = ''" v-show="reportStore.categories[0].checked" @click="sortByField('First Name')"><span class="text-truncate">First Name</span>
									<svg style="vertical-align:top;" v-show="reportStore.sort_by=='First Name' && reportStore.sort_order=='asc'"  width="20px" height="20px" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path fill-rule="evenodd" clip-rule="evenodd" d="M12.0809 7.28641L12.4345 6.93286L12.788 7.28641L17 11.4984L16.2929 12.2055L12.9345 8.84707V16.9999H11.9345V8.84707L8.57605 12.2055L7.86895 11.4984L12.0809 7.28641Z" fill="#000000"></path> </g></svg>
									<svg style="vertical-align:top;" v-show="reportStore.sort_by=='First Name' && reportStore.sort_order=='desc'"  width="20px" height="20px" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path fill-rule="evenodd" clip-rule="evenodd" d="M11.9191 16.7136L11.5655 17.0671L11.212 16.7136L7 12.5016L7.70711 11.7945L11.0655 15.1529V7.00009H12.0655V15.1529L15.4239 11.7945L16.1311 12.5016L11.9191 16.7136Z" fill="#000000"></path> </g></svg>
                                    <svg viewBox="0 0 24 24" style="vertical-align:top;" v-show="reportStore.sort_by!='First Name' && reportStore.hover_on == 'First Name'" width="20px" height="20px" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M12.29 20.69C12.3792 20.6521 12.4606 20.5978 12.53 20.53L16.53 16.53C16.6625 16.3878 16.7346 16.1998 16.7312 16.0055C16.7277 15.8112 16.649 15.6258 16.5116 15.4884C16.3742 15.351 16.1888 15.2723 15.9945 15.2688C15.8002 15.2654 15.6122 15.3375 15.47 15.47L12.75 18.19V5.81001L15.47 8.53001C15.6122 8.66249 15.8002 8.73462 15.9945 8.73119C16.1888 8.72776 16.3742 8.64905 16.5116 8.51163C16.649 8.37422 16.7277 8.18884 16.7312 7.99454C16.7346 7.80024 16.6625 7.61219 16.53 7.47001L12.53 3.47001C12.4606 3.40222 12.3792 3.34796 12.29 3.31001C12.1984 3.27038 12.0998 3.24994 12 3.24994C11.9002 3.24994 11.8015 3.27038 11.71 3.31001C11.6207 3.34796 11.5394 3.40222 11.47 3.47001L7.47 7.47001C7.32955 7.61064 7.25066 7.80126 7.25066 8.00001C7.25066 8.19876 7.32955 8.38939 7.47 8.53001C7.61062 8.67046 7.80125 8.74935 8 8.74935C8.19875 8.74935 8.38937 8.67046 8.53 8.53001L11.25 5.81001V18.19L8.53 15.47C8.38937 15.3296 8.19875 15.2507 8 15.2507C7.80125 15.2507 7.61062 15.3296 7.47 15.47C7.32955 15.6106 7.25066 15.8013 7.25066 16C7.25066 16.1988 7.32955 16.3894 7.47 16.53L11.47 20.53C11.5394 20.5978 11.6207 20.6521 11.71 20.69C11.8015 20.7296 11.9002 20.7501 12 20.7501C12.0998 20.7501 12.1984 20.7296 12.29 20.69Z" fill="#000000"></path> </g></svg>
                                </th>
								<th @mouseenter="reportStore.hover_on = 'Last Name'" @mouseleave="reportStore.hover_on = ''" v-show="reportStore.categories[1].checked" @click="sortByField('Last Name')"><span class="text-truncate">Last Name</span>
									<svg style="vertical-align:top;" v-show="reportStore.sort_by=='Last Name' && reportStore.sort_order=='asc'"  width="20px" height="20px" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path fill-rule="evenodd" clip-rule="evenodd" d="M12.0809 7.28641L12.4345 6.93286L12.788 7.28641L17 11.4984L16.2929 12.2055L12.9345 8.84707V16.9999H11.9345V8.84707L8.57605 12.2055L7.86895 11.4984L12.0809 7.28641Z" fill="#000000"></path> </g></svg>
									<svg style="vertical-align:top;" v-show="reportStore.sort_by=='Last Name' && reportStore.sort_order=='desc'"  width="20px" height="20px" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path fill-rule="evenodd" clip-rule="evenodd" d="M11.9191 16.7136L11.5655 17.0671L11.212 16.7136L7 12.5016L7.70711 11.7945L11.0655 15.1529V7.00009H12.0655V15.1529L15.4239 11.7945L16.1311 12.5016L11.9191 16.7136Z" fill="#000000"></path> </g></svg>
                                    <svg viewBox="0 0 24 24" style="vertical-align:top;" v-show="reportStore.sort_by!='Last Name' && reportStore.hover_on == 'Last Name'" width="20px" height="20px" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M12.29 20.69C12.3792 20.6521 12.4606 20.5978 12.53 20.53L16.53 16.53C16.6625 16.3878 16.7346 16.1998 16.7312 16.0055C16.7277 15.8112 16.649 15.6258 16.5116 15.4884C16.3742 15.351 16.1888 15.2723 15.9945 15.2688C15.8002 15.2654 15.6122 15.3375 15.47 15.47L12.75 18.19V5.81001L15.47 8.53001C15.6122 8.66249 15.8002 8.73462 15.9945 8.73119C16.1888 8.72776 16.3742 8.64905 16.5116 8.51163C16.649 8.37422 16.7277 8.18884 16.7312 7.99454C16.7346 7.80024 16.6625 7.61219 16.53 7.47001L12.53 3.47001C12.4606 3.40222 12.3792 3.34796 12.29 3.31001C12.1984 3.27038 12.0998 3.24994 12 3.24994C11.9002 3.24994 11.8015 3.27038 11.71 3.31001C11.6207 3.34796 11.5394 3.40222 11.47 3.47001L7.47 7.47001C7.32955 7.61064 7.25066 7.80126 7.25066 8.00001C7.25066 8.19876 7.32955 8.38939 7.47 8.53001C7.61062 8.67046 7.80125 8.74935 8 8.74935C8.19875 8.74935 8.38937 8.67046 8.53 8.53001L11.25 5.81001V18.19L8.53 15.47C8.38937 15.3296 8.19875 15.2507 8 15.2507C7.80125 15.2507 7.61062 15.3296 7.47 15.47C7.32955 15.6106 7.25066 15.8013 7.25066 16C7.25066 16.1988 7.32955 16.3894 7.47 16.53L11.47 20.53C11.5394 20.5978 11.6207 20.6521 11.71 20.69C11.8015 20.7296 11.9002 20.7501 12 20.7501C12.0998 20.7501 12.1984 20.7296 12.29 20.69Z" fill="#000000"></path> </g></svg>
                                </th>
								<th @mouseenter="reportStore.hover_on = 'Phone / Form Lead'" @mouseleave="reportStore.hover_on = ''" v-show="reportStore.categories[2].checked" @click="sortByField('Phone / Form Lead')">Lead Type
									<svg style="vertical-align:top;" v-show="reportStore.sort_by=='Phone / Form Lead' && reportStore.sort_order=='asc'"  width="20px" height="20px" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path fill-rule="evenodd" clip-rule="evenodd" d="M12.0809 7.28641L12.4345 6.93286L12.788 7.28641L17 11.4984L16.2929 12.2055L12.9345 8.84707V16.9999H11.9345V8.84707L8.57605 12.2055L7.86895 11.4984L12.0809 7.28641Z" fill="#000000"></path> </g></svg>
									<svg style="vertical-align:top;" v-show="reportStore.sort_by=='Phone / Form Lead' && reportStore.sort_order=='desc'"  width="20px" height="20px" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path fill-rule="evenodd" clip-rule="evenodd" d="M11.9191 16.7136L11.5655 17.0671L11.212 16.7136L7 12.5016L7.70711 11.7945L11.0655 15.1529V7.00009H12.0655V15.1529L15.4239 11.7945L16.1311 12.5016L11.9191 16.7136Z" fill="#000000"></path> </g></svg>
                                    <svg viewBox="0 0 24 24" style="vertical-align:top;" v-show="reportStore.sort_by!='Phone / Form Lead' && reportStore.hover_on == 'Phone / Form Lead'" width="20px" height="20px" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M12.29 20.69C12.3792 20.6521 12.4606 20.5978 12.53 20.53L16.53 16.53C16.6625 16.3878 16.7346 16.1998 16.7312 16.0055C16.7277 15.8112 16.649 15.6258 16.5116 15.4884C16.3742 15.351 16.1888 15.2723 15.9945 15.2688C15.8002 15.2654 15.6122 15.3375 15.47 15.47L12.75 18.19V5.81001L15.47 8.53001C15.6122 8.66249 15.8002 8.73462 15.9945 8.73119C16.1888 8.72776 16.3742 8.64905 16.5116 8.51163C16.649 8.37422 16.7277 8.18884 16.7312 7.99454C16.7346 7.80024 16.6625 7.61219 16.53 7.47001L12.53 3.47001C12.4606 3.40222 12.3792 3.34796 12.29 3.31001C12.1984 3.27038 12.0998 3.24994 12 3.24994C11.9002 3.24994 11.8015 3.27038 11.71 3.31001C11.6207 3.34796 11.5394 3.40222 11.47 3.47001L7.47 7.47001C7.32955 7.61064 7.25066 7.80126 7.25066 8.00001C7.25066 8.19876 7.32955 8.38939 7.47 8.53001C7.61062 8.67046 7.80125 8.74935 8 8.74935C8.19875 8.74935 8.38937 8.67046 8.53 8.53001L11.25 5.81001V18.19L8.53 15.47C8.38937 15.3296 8.19875 15.2507 8 15.2507C7.80125 15.2507 7.61062 15.3296 7.47 15.47C7.32955 15.6106 7.25066 15.8013 7.25066 16C7.25066 16.1988 7.32955 16.3894 7.47 16.53L11.47 20.53C11.5394 20.5978 11.6207 20.6521 11.71 20.69C11.8015 20.7296 11.9002 20.7501 12 20.7501C12.0998 20.7501 12.1984 20.7296 12.29 20.69Z" fill="#000000"></path> </g></svg>
                                </th>
								<th @mouseenter="reportStore.hover_on = 'Source'" @mouseleave="reportStore.hover_on = ''" v-show="reportStore.categories[3].checked" @click="sortByField('Source')">source
									<svg style="vertical-align:top;" v-show="reportStore.sort_by=='Source' && reportStore.sort_order=='asc'"  width="20px" height="20px" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path fill-rule="evenodd" clip-rule="evenodd" d="M12.0809 7.28641L12.4345 6.93286L12.788 7.28641L17 11.4984L16.2929 12.2055L12.9345 8.84707V16.9999H11.9345V8.84707L8.57605 12.2055L7.86895 11.4984L12.0809 7.28641Z" fill="#000000"></path> </g></svg>
									<svg style="vertical-align:top;" v-show="reportStore.sort_by=='Source' && reportStore.sort_order=='desc'"  width="20px" height="20px" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path fill-rule="evenodd" clip-rule="evenodd" d="M11.9191 16.7136L11.5655 17.0671L11.212 16.7136L7 12.5016L7.70711 11.7945L11.0655 15.1529V7.00009H12.0655V15.1529L15.4239 11.7945L16.1311 12.5016L11.9191 16.7136Z" fill="#000000"></path> </g></svg>
                                    <svg viewBox="0 0 24 24" style="vertical-align:top;" v-show="reportStore.sort_by!='Source' && reportStore.hover_on == 'Source'" width="20px" height="20px" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M12.29 20.69C12.3792 20.6521 12.4606 20.5978 12.53 20.53L16.53 16.53C16.6625 16.3878 16.7346 16.1998 16.7312 16.0055C16.7277 15.8112 16.649 15.6258 16.5116 15.4884C16.3742 15.351 16.1888 15.2723 15.9945 15.2688C15.8002 15.2654 15.6122 15.3375 15.47 15.47L12.75 18.19V5.81001L15.47 8.53001C15.6122 8.66249 15.8002 8.73462 15.9945 8.73119C16.1888 8.72776 16.3742 8.64905 16.5116 8.51163C16.649 8.37422 16.7277 8.18884 16.7312 7.99454C16.7346 7.80024 16.6625 7.61219 16.53 7.47001L12.53 3.47001C12.4606 3.40222 12.3792 3.34796 12.29 3.31001C12.1984 3.27038 12.0998 3.24994 12 3.24994C11.9002 3.24994 11.8015 3.27038 11.71 3.31001C11.6207 3.34796 11.5394 3.40222 11.47 3.47001L7.47 7.47001C7.32955 7.61064 7.25066 7.80126 7.25066 8.00001C7.25066 8.19876 7.32955 8.38939 7.47 8.53001C7.61062 8.67046 7.80125 8.74935 8 8.74935C8.19875 8.74935 8.38937 8.67046 8.53 8.53001L11.25 5.81001V18.19L8.53 15.47C8.38937 15.3296 8.19875 15.2507 8 15.2507C7.80125 15.2507 7.61062 15.3296 7.47 15.47C7.32955 15.6106 7.25066 15.8013 7.25066 16C7.25066 16.1988 7.32955 16.3894 7.47 16.53L11.47 20.53C11.5394 20.5978 11.6207 20.6521 11.71 20.69C11.8015 20.7296 11.9002 20.7501 12 20.7501C12.0998 20.7501 12.1984 20.7296 12.29 20.69Z" fill="#000000"></path> </g></svg>
                                </th>
								<th @mouseenter="reportStore.hover_on = 'Stage'" @mouseleave="reportStore.hover_on = ''" v-show="reportStore.categories[4].checked" @click="sortByField('Stage')">Stage
									<svg style="vertical-align:top;" v-show="reportStore.sort_by=='Stage' && reportStore.sort_order=='asc'"  width="20px" height="20px" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path fill-rule="evenodd" clip-rule="evenodd" d="M12.0809 7.28641L12.4345 6.93286L12.788 7.28641L17 11.4984L16.2929 12.2055L12.9345 8.84707V16.9999H11.9345V8.84707L8.57605 12.2055L7.86895 11.4984L12.0809 7.28641Z" fill="#000000"></path> </g></svg>
									<svg style="vertical-align:top;" v-show="reportStore.sort_by=='Stage' && reportStore.sort_order=='desc'"  width="20px" height="20px" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path fill-rule="evenodd" clip-rule="evenodd" d="M11.9191 16.7136L11.5655 17.0671L11.212 16.7136L7 12.5016L7.70711 11.7945L11.0655 15.1529V7.00009H12.0655V15.1529L15.4239 11.7945L16.1311 12.5016L11.9191 16.7136Z" fill="#000000"></path> </g></svg>
                                    <svg viewBox="0 0 24 24" style="vertical-align:top;" v-show="reportStore.sort_by!='Stage' && reportStore.hover_on == 'Stage'" width="20px" height="20px" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M12.29 20.69C12.3792 20.6521 12.4606 20.5978 12.53 20.53L16.53 16.53C16.6625 16.3878 16.7346 16.1998 16.7312 16.0055C16.7277 15.8112 16.649 15.6258 16.5116 15.4884C16.3742 15.351 16.1888 15.2723 15.9945 15.2688C15.8002 15.2654 15.6122 15.3375 15.47 15.47L12.75 18.19V5.81001L15.47 8.53001C15.6122 8.66249 15.8002 8.73462 15.9945 8.73119C16.1888 8.72776 16.3742 8.64905 16.5116 8.51163C16.649 8.37422 16.7277 8.18884 16.7312 7.99454C16.7346 7.80024 16.6625 7.61219 16.53 7.47001L12.53 3.47001C12.4606 3.40222 12.3792 3.34796 12.29 3.31001C12.1984 3.27038 12.0998 3.24994 12 3.24994C11.9002 3.24994 11.8015 3.27038 11.71 3.31001C11.6207 3.34796 11.5394 3.40222 11.47 3.47001L7.47 7.47001C7.32955 7.61064 7.25066 7.80126 7.25066 8.00001C7.25066 8.19876 7.32955 8.38939 7.47 8.53001C7.61062 8.67046 7.80125 8.74935 8 8.74935C8.19875 8.74935 8.38937 8.67046 8.53 8.53001L11.25 5.81001V18.19L8.53 15.47C8.38937 15.3296 8.19875 15.2507 8 15.2507C7.80125 15.2507 7.61062 15.3296 7.47 15.47C7.32955 15.6106 7.25066 15.8013 7.25066 16C7.25066 16.1988 7.32955 16.3894 7.47 16.53L11.47 20.53C11.5394 20.5978 11.6207 20.6521 11.71 20.69C11.8015 20.7296 11.9002 20.7501 12 20.7501C12.0998 20.7501 12.1984 20.7296 12.29 20.69Z" fill="#000000"></path> </g></svg>
                                </th>
								<th @mouseenter="reportStore.hover_on = 'Email'" @mouseleave="reportStore.hover_on = ''" v-show="reportStore.categories[5].checked" @click="sortByField('Email')">email
									<svg style="vertical-align:top;" v-show="reportStore.sort_by=='Email' && reportStore.sort_order=='asc'"  width="20px" height="20px" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path fill-rule="evenodd" clip-rule="evenodd" d="M12.0809 7.28641L12.4345 6.93286L12.788 7.28641L17 11.4984L16.2929 12.2055L12.9345 8.84707V16.9999H11.9345V8.84707L8.57605 12.2055L7.86895 11.4984L12.0809 7.28641Z" fill="#000000"></path> </g></svg>
									<svg style="vertical-align:top;" v-show="reportStore.sort_by=='Email' && reportStore.sort_order=='desc'"  width="20px" height="20px" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path fill-rule="evenodd" clip-rule="evenodd" d="M11.9191 16.7136L11.5655 17.0671L11.212 16.7136L7 12.5016L7.70711 11.7945L11.0655 15.1529V7.00009H12.0655V15.1529L15.4239 11.7945L16.1311 12.5016L11.9191 16.7136Z" fill="#000000"></path> </g></svg>
                                    <svg viewBox="0 0 24 24" style="vertical-align:top;" v-show="reportStore.sort_by!='Email' && reportStore.hover_on == 'Email'" width="20px" height="20px" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M12.29 20.69C12.3792 20.6521 12.4606 20.5978 12.53 20.53L16.53 16.53C16.6625 16.3878 16.7346 16.1998 16.7312 16.0055C16.7277 15.8112 16.649 15.6258 16.5116 15.4884C16.3742 15.351 16.1888 15.2723 15.9945 15.2688C15.8002 15.2654 15.6122 15.3375 15.47 15.47L12.75 18.19V5.81001L15.47 8.53001C15.6122 8.66249 15.8002 8.73462 15.9945 8.73119C16.1888 8.72776 16.3742 8.64905 16.5116 8.51163C16.649 8.37422 16.7277 8.18884 16.7312 7.99454C16.7346 7.80024 16.6625 7.61219 16.53 7.47001L12.53 3.47001C12.4606 3.40222 12.3792 3.34796 12.29 3.31001C12.1984 3.27038 12.0998 3.24994 12 3.24994C11.9002 3.24994 11.8015 3.27038 11.71 3.31001C11.6207 3.34796 11.5394 3.40222 11.47 3.47001L7.47 7.47001C7.32955 7.61064 7.25066 7.80126 7.25066 8.00001C7.25066 8.19876 7.32955 8.38939 7.47 8.53001C7.61062 8.67046 7.80125 8.74935 8 8.74935C8.19875 8.74935 8.38937 8.67046 8.53 8.53001L11.25 5.81001V18.19L8.53 15.47C8.38937 15.3296 8.19875 15.2507 8 15.2507C7.80125 15.2507 7.61062 15.3296 7.47 15.47C7.32955 15.6106 7.25066 15.8013 7.25066 16C7.25066 16.1988 7.32955 16.3894 7.47 16.53L11.47 20.53C11.5394 20.5978 11.6207 20.6521 11.71 20.69C11.8015 20.7296 11.9002 20.7501 12 20.7501C12.0998 20.7501 12.1984 20.7296 12.29 20.69Z" fill="#000000"></path> </g></svg>
                                </th>
								<th @mouseenter="reportStore.hover_on = 'Phone'" @mouseleave="reportStore.hover_on = ''" v-show="reportStore.categories[6].checked" @click="sortByField('Phone')">phone
									<svg style="vertical-align:top;" v-show="reportStore.sort_by=='Phone' && reportStore.sort_order=='asc'"  width="20px" height="20px" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path fill-rule="evenodd" clip-rule="evenodd" d="M12.0809 7.28641L12.4345 6.93286L12.788 7.28641L17 11.4984L16.2929 12.2055L12.9345 8.84707V16.9999H11.9345V8.84707L8.57605 12.2055L7.86895 11.4984L12.0809 7.28641Z" fill="#000000"></path> </g></svg>
									<svg style="vertical-align:top;" v-show="reportStore.sort_by=='Phone' && reportStore.sort_order=='desc'"  width="20px" height="20px" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path fill-rule="evenodd" clip-rule="evenodd" d="M11.9191 16.7136L11.5655 17.0671L11.212 16.7136L7 12.5016L7.70711 11.7945L11.0655 15.1529V7.00009H12.0655V15.1529L15.4239 11.7945L16.1311 12.5016L11.9191 16.7136Z" fill="#000000"></path> </g></svg>
                                    <svg viewBox="0 0 24 24" style="vertical-align:top;" v-show="reportStore.sort_by!='Phone' && reportStore.hover_on == 'Phone'" width="20px" height="20px" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M12.29 20.69C12.3792 20.6521 12.4606 20.5978 12.53 20.53L16.53 16.53C16.6625 16.3878 16.7346 16.1998 16.7312 16.0055C16.7277 15.8112 16.649 15.6258 16.5116 15.4884C16.3742 15.351 16.1888 15.2723 15.9945 15.2688C15.8002 15.2654 15.6122 15.3375 15.47 15.47L12.75 18.19V5.81001L15.47 8.53001C15.6122 8.66249 15.8002 8.73462 15.9945 8.73119C16.1888 8.72776 16.3742 8.64905 16.5116 8.51163C16.649 8.37422 16.7277 8.18884 16.7312 7.99454C16.7346 7.80024 16.6625 7.61219 16.53 7.47001L12.53 3.47001C12.4606 3.40222 12.3792 3.34796 12.29 3.31001C12.1984 3.27038 12.0998 3.24994 12 3.24994C11.9002 3.24994 11.8015 3.27038 11.71 3.31001C11.6207 3.34796 11.5394 3.40222 11.47 3.47001L7.47 7.47001C7.32955 7.61064 7.25066 7.80126 7.25066 8.00001C7.25066 8.19876 7.32955 8.38939 7.47 8.53001C7.61062 8.67046 7.80125 8.74935 8 8.74935C8.19875 8.74935 8.38937 8.67046 8.53 8.53001L11.25 5.81001V18.19L8.53 15.47C8.38937 15.3296 8.19875 15.2507 8 15.2507C7.80125 15.2507 7.61062 15.3296 7.47 15.47C7.32955 15.6106 7.25066 15.8013 7.25066 16C7.25066 16.1988 7.32955 16.3894 7.47 16.53L11.47 20.53C11.5394 20.5978 11.6207 20.6521 11.71 20.69C11.8015 20.7296 11.9002 20.7501 12 20.7501C12.0998 20.7501 12.1984 20.7296 12.29 20.69Z" fill="#000000"></path> </g></svg>
                                </th>
								<th @mouseenter="reportStore.hover_on = 'Treatment Amount'" @mouseleave="reportStore.hover_on = ''" v-show="reportStore.categories[7].checked" @click="sortByField('Treatment Amount')">Treatment Amount
									<svg style="vertical-align:top;" v-show="reportStore.sort_by=='Treatment Amount' && reportStore.sort_order=='asc'"  width="20px" height="20px" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path fill-rule="evenodd" clip-rule="evenodd" d="M12.0809 7.28641L12.4345 6.93286L12.788 7.28641L17 11.4984L16.2929 12.2055L12.9345 8.84707V16.9999H11.9345V8.84707L8.57605 12.2055L7.86895 11.4984L12.0809 7.28641Z" fill="#000000"></path> </g></svg>
									<svg style="vertical-align:top;" v-show="reportStore.sort_by=='Treatment Amount' && reportStore.sort_order=='desc'"  width="20px" height="20px" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path fill-rule="evenodd" clip-rule="evenodd" d="M11.9191 16.7136L11.5655 17.0671L11.212 16.7136L7 12.5016L7.70711 11.7945L11.0655 15.1529V7.00009H12.0655V15.1529L15.4239 11.7945L16.1311 12.5016L11.9191 16.7136Z" fill="#000000"></path> </g></svg>
                                    <svg viewBox="0 0 24 24" style="vertical-align:top;" v-show="reportStore.sort_by!='Treatment Amount' && reportStore.hover_on == 'Treatment Amount'" width="20px" height="20px" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M12.29 20.69C12.3792 20.6521 12.4606 20.5978 12.53 20.53L16.53 16.53C16.6625 16.3878 16.7346 16.1998 16.7312 16.0055C16.7277 15.8112 16.649 15.6258 16.5116 15.4884C16.3742 15.351 16.1888 15.2723 15.9945 15.2688C15.8002 15.2654 15.6122 15.3375 15.47 15.47L12.75 18.19V5.81001L15.47 8.53001C15.6122 8.66249 15.8002 8.73462 15.9945 8.73119C16.1888 8.72776 16.3742 8.64905 16.5116 8.51163C16.649 8.37422 16.7277 8.18884 16.7312 7.99454C16.7346 7.80024 16.6625 7.61219 16.53 7.47001L12.53 3.47001C12.4606 3.40222 12.3792 3.34796 12.29 3.31001C12.1984 3.27038 12.0998 3.24994 12 3.24994C11.9002 3.24994 11.8015 3.27038 11.71 3.31001C11.6207 3.34796 11.5394 3.40222 11.47 3.47001L7.47 7.47001C7.32955 7.61064 7.25066 7.80126 7.25066 8.00001C7.25066 8.19876 7.32955 8.38939 7.47 8.53001C7.61062 8.67046 7.80125 8.74935 8 8.74935C8.19875 8.74935 8.38937 8.67046 8.53 8.53001L11.25 5.81001V18.19L8.53 15.47C8.38937 15.3296 8.19875 15.2507 8 15.2507C7.80125 15.2507 7.61062 15.3296 7.47 15.47C7.32955 15.6106 7.25066 15.8013 7.25066 16C7.25066 16.1988 7.32955 16.3894 7.47 16.53L11.47 20.53C11.5394 20.5978 11.6207 20.6521 11.71 20.69C11.8015 20.7296 11.9002 20.7501 12 20.7501C12.0998 20.7501 12.1984 20.7296 12.29 20.69Z" fill="#000000"></path> </g></svg>
                                </th>
                                <th @mouseenter="reportStore.hover_on = 'Reason'" @mouseleave="reportStore.hover_on = ''" v-show="reportStore.categories[8].checked" @click="sortByField('Reason')">Reason
									<svg style="vertical-align:top;" v-show="reportStore.sort_by=='Reason' && reportStore.sort_order=='asc'"  width="20px" height="20px" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path fill-rule="evenodd" clip-rule="evenodd" d="M12.0809 7.28641L12.4345 6.93286L12.788 7.28641L17 11.4984L16.2929 12.2055L12.9345 8.84707V16.9999H11.9345V8.84707L8.57605 12.2055L7.86895 11.4984L12.0809 7.28641Z" fill="#000000"></path> </g></svg>
									<svg style="vertical-align:top;" v-show="reportStore.sort_by=='Reason' && reportStore.sort_order=='desc'"  width="20px" height="20px" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path fill-rule="evenodd" clip-rule="evenodd" d="M11.9191 16.7136L11.5655 17.0671L11.212 16.7136L7 12.5016L7.70711 11.7945L11.0655 15.1529V7.00009H12.0655V15.1529L15.4239 11.7945L16.1311 12.5016L11.9191 16.7136Z" fill="#000000"></path> </g></svg>
                                    <svg viewBox="0 0 24 24" style="vertical-align:top;" v-show="reportStore.sort_by!='Reason' && reportStore.hover_on == 'Reason'" width="20px" height="20px" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M12.29 20.69C12.3792 20.6521 12.4606 20.5978 12.53 20.53L16.53 16.53C16.6625 16.3878 16.7346 16.1998 16.7312 16.0055C16.7277 15.8112 16.649 15.6258 16.5116 15.4884C16.3742 15.351 16.1888 15.2723 15.9945 15.2688C15.8002 15.2654 15.6122 15.3375 15.47 15.47L12.75 18.19V5.81001L15.47 8.53001C15.6122 8.66249 15.8002 8.73462 15.9945 8.73119C16.1888 8.72776 16.3742 8.64905 16.5116 8.51163C16.649 8.37422 16.7277 8.18884 16.7312 7.99454C16.7346 7.80024 16.6625 7.61219 16.53 7.47001L12.53 3.47001C12.4606 3.40222 12.3792 3.34796 12.29 3.31001C12.1984 3.27038 12.0998 3.24994 12 3.24994C11.9002 3.24994 11.8015 3.27038 11.71 3.31001C11.6207 3.34796 11.5394 3.40222 11.47 3.47001L7.47 7.47001C7.32955 7.61064 7.25066 7.80126 7.25066 8.00001C7.25066 8.19876 7.32955 8.38939 7.47 8.53001C7.61062 8.67046 7.80125 8.74935 8 8.74935C8.19875 8.74935 8.38937 8.67046 8.53 8.53001L11.25 5.81001V18.19L8.53 15.47C8.38937 15.3296 8.19875 15.2507 8 15.2507C7.80125 15.2507 7.61062 15.3296 7.47 15.47C7.32955 15.6106 7.25066 15.8013 7.25066 16C7.25066 16.1988 7.32955 16.3894 7.47 16.53L11.47 20.53C11.5394 20.5978 11.6207 20.6521 11.71 20.69C11.8015 20.7296 11.9002 20.7501 12 20.7501C12.0998 20.7501 12.1984 20.7296 12.29 20.69Z" fill="#000000"></path> </g></svg>
                                </th>
                                <th @mouseenter="reportStore.hover_on = 'City'" @mouseleave="reportStore.hover_on = ''" v-show="reportStore.categories[9].checked" @click="sortByField('City')">City
									<svg style="vertical-align:top;" v-show="reportStore.sort_by=='City' && reportStore.sort_order=='asc'"  width="20px" height="20px" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path fill-rule="evenodd" clip-rule="evenodd" d="M12.0809 7.28641L12.4345 6.93286L12.788 7.28641L17 11.4984L16.2929 12.2055L12.9345 8.84707V16.9999H11.9345V8.84707L8.57605 12.2055L7.86895 11.4984L12.0809 7.28641Z" fill="#000000"></path> </g></svg>
									<svg style="vertical-align:top;" v-show="reportStore.sort_by=='City' && reportStore.sort_order=='desc'"  width="20px" height="20px" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path fill-rule="evenodd" clip-rule="evenodd" d="M11.9191 16.7136L11.5655 17.0671L11.212 16.7136L7 12.5016L7.70711 11.7945L11.0655 15.1529V7.00009H12.0655V15.1529L15.4239 11.7945L16.1311 12.5016L11.9191 16.7136Z" fill="#000000"></path> </g></svg>
                                    <svg viewBox="0 0 24 24" style="vertical-align:top;" v-show="reportStore.sort_by!='City' && reportStore.hover_on == 'City'" width="20px" height="20px" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M12.29 20.69C12.3792 20.6521 12.4606 20.5978 12.53 20.53L16.53 16.53C16.6625 16.3878 16.7346 16.1998 16.7312 16.0055C16.7277 15.8112 16.649 15.6258 16.5116 15.4884C16.3742 15.351 16.1888 15.2723 15.9945 15.2688C15.8002 15.2654 15.6122 15.3375 15.47 15.47L12.75 18.19V5.81001L15.47 8.53001C15.6122 8.66249 15.8002 8.73462 15.9945 8.73119C16.1888 8.72776 16.3742 8.64905 16.5116 8.51163C16.649 8.37422 16.7277 8.18884 16.7312 7.99454C16.7346 7.80024 16.6625 7.61219 16.53 7.47001L12.53 3.47001C12.4606 3.40222 12.3792 3.34796 12.29 3.31001C12.1984 3.27038 12.0998 3.24994 12 3.24994C11.9002 3.24994 11.8015 3.27038 11.71 3.31001C11.6207 3.34796 11.5394 3.40222 11.47 3.47001L7.47 7.47001C7.32955 7.61064 7.25066 7.80126 7.25066 8.00001C7.25066 8.19876 7.32955 8.38939 7.47 8.53001C7.61062 8.67046 7.80125 8.74935 8 8.74935C8.19875 8.74935 8.38937 8.67046 8.53 8.53001L11.25 5.81001V18.19L8.53 15.47C8.38937 15.3296 8.19875 15.2507 8 15.2507C7.80125 15.2507 7.61062 15.3296 7.47 15.47C7.32955 15.6106 7.25066 15.8013 7.25066 16C7.25066 16.1988 7.32955 16.3894 7.47 16.53L11.47 20.53C11.5394 20.5978 11.6207 20.6521 11.71 20.69C11.8015 20.7296 11.9002 20.7501 12 20.7501C12.0998 20.7501 12.1984 20.7296 12.29 20.69Z" fill="#000000"></path> </g></svg>
                                </th>
                                <th @mouseenter="reportStore.hover_on = 'Zip Code'" @mouseleave="reportStore.hover_on = ''" v-show="reportStore.categories[10].checked" @click="sortByField('Zip Code')">Zip Code
									<svg style="vertical-align:top;" v-show="reportStore.sort_by=='Zip Code' && reportStore.sort_order=='asc'"  width="20px" height="20px" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path fill-rule="evenodd" clip-rule="evenodd" d="M12.0809 7.28641L12.4345 6.93286L12.788 7.28641L17 11.4984L16.2929 12.2055L12.9345 8.84707V16.9999H11.9345V8.84707L8.57605 12.2055L7.86895 11.4984L12.0809 7.28641Z" fill="#000000"></path> </g></svg>
									<svg style="vertical-align:top;" v-show="reportStore.sort_by=='Zip Code' && reportStore.sort_order=='desc'"  width="20px" height="20px" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path fill-rule="evenodd" clip-rule="evenodd" d="M11.9191 16.7136L11.5655 17.0671L11.212 16.7136L7 12.5016L7.70711 11.7945L11.0655 15.1529V7.00009H12.0655V15.1529L15.4239 11.7945L16.1311 12.5016L11.9191 16.7136Z" fill="#000000"></path> </g></svg>
                                    <svg viewBox="0 0 24 24" style="vertical-align:top;" v-show="reportStore.sort_by!='Zip Code' && reportStore.hover_on == 'Zip Code'" width="20px" height="20px" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M12.29 20.69C12.3792 20.6521 12.4606 20.5978 12.53 20.53L16.53 16.53C16.6625 16.3878 16.7346 16.1998 16.7312 16.0055C16.7277 15.8112 16.649 15.6258 16.5116 15.4884C16.3742 15.351 16.1888 15.2723 15.9945 15.2688C15.8002 15.2654 15.6122 15.3375 15.47 15.47L12.75 18.19V5.81001L15.47 8.53001C15.6122 8.66249 15.8002 8.73462 15.9945 8.73119C16.1888 8.72776 16.3742 8.64905 16.5116 8.51163C16.649 8.37422 16.7277 8.18884 16.7312 7.99454C16.7346 7.80024 16.6625 7.61219 16.53 7.47001L12.53 3.47001C12.4606 3.40222 12.3792 3.34796 12.29 3.31001C12.1984 3.27038 12.0998 3.24994 12 3.24994C11.9002 3.24994 11.8015 3.27038 11.71 3.31001C11.6207 3.34796 11.5394 3.40222 11.47 3.47001L7.47 7.47001C7.32955 7.61064 7.25066 7.80126 7.25066 8.00001C7.25066 8.19876 7.32955 8.38939 7.47 8.53001C7.61062 8.67046 7.80125 8.74935 8 8.74935C8.19875 8.74935 8.38937 8.67046 8.53 8.53001L11.25 5.81001V18.19L8.53 15.47C8.38937 15.3296 8.19875 15.2507 8 15.2507C7.80125 15.2507 7.61062 15.3296 7.47 15.47C7.32955 15.6106 7.25066 15.8013 7.25066 16C7.25066 16.1988 7.32955 16.3894 7.47 16.53L11.47 20.53C11.5394 20.5978 11.6207 20.6521 11.71 20.69C11.8015 20.7296 11.9002 20.7501 12 20.7501C12.0998 20.7501 12.1984 20.7296 12.29 20.69Z" fill="#000000"></path> </g></svg>
                                </th>
                                <th @mouseenter="reportStore.hover_on = 'Life Time Value'" @mouseleave="reportStore.hover_on = ''" v-show="reportStore.categories[11].checked" @click="sortByField('Life Time Value')">Life Time Value
									<svg style="vertical-align:top;" v-show="reportStore.sort_by=='Life Time Value' && reportStore.sort_order=='asc'"  width="20px" height="20px" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path fill-rule="evenodd" clip-rule="evenodd" d="M12.0809 7.28641L12.4345 6.93286L12.788 7.28641L17 11.4984L16.2929 12.2055L12.9345 8.84707V16.9999H11.9345V8.84707L8.57605 12.2055L7.86895 11.4984L12.0809 7.28641Z" fill="#000000"></path> </g></svg>
									<svg style="vertical-align:top;" v-show="reportStore.sort_by=='Life Time Value' && reportStore.sort_order=='desc'"  width="20px" height="20px" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path fill-rule="evenodd" clip-rule="evenodd" d="M11.9191 16.7136L11.5655 17.0671L11.212 16.7136L7 12.5016L7.70711 11.7945L11.0655 15.1529V7.00009H12.0655V15.1529L15.4239 11.7945L16.1311 12.5016L11.9191 16.7136Z" fill="#000000"></path> </g></svg>
                                    <svg viewBox="0 0 24 24" style="vertical-align:top;" v-show="reportStore.sort_by!='Life Time Value' && reportStore.hover_on == 'Life Time Value'" width="20px" height="20px" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M12.29 20.69C12.3792 20.6521 12.4606 20.5978 12.53 20.53L16.53 16.53C16.6625 16.3878 16.7346 16.1998 16.7312 16.0055C16.7277 15.8112 16.649 15.6258 16.5116 15.4884C16.3742 15.351 16.1888 15.2723 15.9945 15.2688C15.8002 15.2654 15.6122 15.3375 15.47 15.47L12.75 18.19V5.81001L15.47 8.53001C15.6122 8.66249 15.8002 8.73462 15.9945 8.73119C16.1888 8.72776 16.3742 8.64905 16.5116 8.51163C16.649 8.37422 16.7277 8.18884 16.7312 7.99454C16.7346 7.80024 16.6625 7.61219 16.53 7.47001L12.53 3.47001C12.4606 3.40222 12.3792 3.34796 12.29 3.31001C12.1984 3.27038 12.0998 3.24994 12 3.24994C11.9002 3.24994 11.8015 3.27038 11.71 3.31001C11.6207 3.34796 11.5394 3.40222 11.47 3.47001L7.47 7.47001C7.32955 7.61064 7.25066 7.80126 7.25066 8.00001C7.25066 8.19876 7.32955 8.38939 7.47 8.53001C7.61062 8.67046 7.80125 8.74935 8 8.74935C8.19875 8.74935 8.38937 8.67046 8.53 8.53001L11.25 5.81001V18.19L8.53 15.47C8.38937 15.3296 8.19875 15.2507 8 15.2507C7.80125 15.2507 7.61062 15.3296 7.47 15.47C7.32955 15.6106 7.25066 15.8013 7.25066 16C7.25066 16.1988 7.32955 16.3894 7.47 16.53L11.47 20.53C11.5394 20.5978 11.6207 20.6521 11.71 20.69C11.8015 20.7296 11.9002 20.7501 12 20.7501C12.0998 20.7501 12.1984 20.7296 12.29 20.69Z" fill="#000000"></path> </g></svg>
                                </th>
                                <th @mouseenter="reportStore.hover_on = 'Consultation Booked Date'" @mouseleave="reportStore.hover_on = ''" v-show="reportStore.categories[12].checked" @click="sortByField('Consultation Booked Date')">Scheduled For
									<svg style="vertical-align:top;" v-show="reportStore.sort_by=='Consultation Booked Date' && reportStore.sort_order=='asc'"  width="20px" height="20px" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path fill-rule="evenodd" clip-rule="evenodd" d="M12.0809 7.28641L12.4345 6.93286L12.788 7.28641L17 11.4984L16.2929 12.2055L12.9345 8.84707V16.9999H11.9345V8.84707L8.57605 12.2055L7.86895 11.4984L12.0809 7.28641Z" fill="#000000"></path> </g></svg>
									<svg style="vertical-align:top;" v-show="reportStore.sort_by=='Consultation Booked Date' && reportStore.sort_order=='desc'"  width="20px" height="20px" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path fill-rule="evenodd" clip-rule="evenodd" d="M11.9191 16.7136L11.5655 17.0671L11.212 16.7136L7 12.5016L7.70711 11.7945L11.0655 15.1529V7.00009H12.0655V15.1529L15.4239 11.7945L16.1311 12.5016L11.9191 16.7136Z" fill="#000000"></path> </g></svg>
                                    <svg viewBox="0 0 24 24" style="vertical-align:top;" v-show="reportStore.sort_by!='Consultation Booked Date' && reportStore.hover_on == 'Consultation Booked Date'" width="20px" height="20px" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M12.29 20.69C12.3792 20.6521 12.4606 20.5978 12.53 20.53L16.53 16.53C16.6625 16.3878 16.7346 16.1998 16.7312 16.0055C16.7277 15.8112 16.649 15.6258 16.5116 15.4884C16.3742 15.351 16.1888 15.2723 15.9945 15.2688C15.8002 15.2654 15.6122 15.3375 15.47 15.47L12.75 18.19V5.81001L15.47 8.53001C15.6122 8.66249 15.8002 8.73462 15.9945 8.73119C16.1888 8.72776 16.3742 8.64905 16.5116 8.51163C16.649 8.37422 16.7277 8.18884 16.7312 7.99454C16.7346 7.80024 16.6625 7.61219 16.53 7.47001L12.53 3.47001C12.4606 3.40222 12.3792 3.34796 12.29 3.31001C12.1984 3.27038 12.0998 3.24994 12 3.24994C11.9002 3.24994 11.8015 3.27038 11.71 3.31001C11.6207 3.34796 11.5394 3.40222 11.47 3.47001L7.47 7.47001C7.32955 7.61064 7.25066 7.80126 7.25066 8.00001C7.25066 8.19876 7.32955 8.38939 7.47 8.53001C7.61062 8.67046 7.80125 8.74935 8 8.74935C8.19875 8.74935 8.38937 8.67046 8.53 8.53001L11.25 5.81001V18.19L8.53 15.47C8.38937 15.3296 8.19875 15.2507 8 15.2507C7.80125 15.2507 7.61062 15.3296 7.47 15.47C7.32955 15.6106 7.25066 15.8013 7.25066 16C7.25066 16.1988 7.32955 16.3894 7.47 16.53L11.47 20.53C11.5394 20.5978 11.6207 20.6521 11.71 20.69C11.8015 20.7296 11.9002 20.7501 12 20.7501C12.0998 20.7501 12.1984 20.7296 12.29 20.69Z" fill="#000000"></path> </g></svg>
                                </th>
                                <th @mouseenter="reportStore.hover_on = 'No Showed Date'" @mouseleave="reportStore.hover_on = ''" v-show="reportStore.categories[13].checked" @click="sortByField('No Showed Date')">No Showed Date
									<svg style="vertical-align:top;" v-show="reportStore.sort_by=='No Showed Date' && reportStore.sort_order=='asc'"  width="20px" height="20px" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path fill-rule="evenodd" clip-rule="evenodd" d="M12.0809 7.28641L12.4345 6.93286L12.788 7.28641L17 11.4984L16.2929 12.2055L12.9345 8.84707V16.9999H11.9345V8.84707L8.57605 12.2055L7.86895 11.4984L12.0809 7.28641Z" fill="#000000"></path> </g></svg>
									<svg style="vertical-align:top;" v-show="reportStore.sort_by=='No Showed Date' && reportStore.sort_order=='desc'"  width="20px" height="20px" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path fill-rule="evenodd" clip-rule="evenodd" d="M11.9191 16.7136L11.5655 17.0671L11.212 16.7136L7 12.5016L7.70711 11.7945L11.0655 15.1529V7.00009H12.0655V15.1529L15.4239 11.7945L16.1311 12.5016L11.9191 16.7136Z" fill="#000000"></path> </g></svg>
                                    <svg viewBox="0 0 24 24" style="vertical-align:top;" v-show="reportStore.sort_by!='No Showed Date' && reportStore.hover_on == 'No Showed Date'" width="20px" height="20px" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M12.29 20.69C12.3792 20.6521 12.4606 20.5978 12.53 20.53L16.53 16.53C16.6625 16.3878 16.7346 16.1998 16.7312 16.0055C16.7277 15.8112 16.649 15.6258 16.5116 15.4884C16.3742 15.351 16.1888 15.2723 15.9945 15.2688C15.8002 15.2654 15.6122 15.3375 15.47 15.47L12.75 18.19V5.81001L15.47 8.53001C15.6122 8.66249 15.8002 8.73462 15.9945 8.73119C16.1888 8.72776 16.3742 8.64905 16.5116 8.51163C16.649 8.37422 16.7277 8.18884 16.7312 7.99454C16.7346 7.80024 16.6625 7.61219 16.53 7.47001L12.53 3.47001C12.4606 3.40222 12.3792 3.34796 12.29 3.31001C12.1984 3.27038 12.0998 3.24994 12 3.24994C11.9002 3.24994 11.8015 3.27038 11.71 3.31001C11.6207 3.34796 11.5394 3.40222 11.47 3.47001L7.47 7.47001C7.32955 7.61064 7.25066 7.80126 7.25066 8.00001C7.25066 8.19876 7.32955 8.38939 7.47 8.53001C7.61062 8.67046 7.80125 8.74935 8 8.74935C8.19875 8.74935 8.38937 8.67046 8.53 8.53001L11.25 5.81001V18.19L8.53 15.47C8.38937 15.3296 8.19875 15.2507 8 15.2507C7.80125 15.2507 7.61062 15.3296 7.47 15.47C7.32955 15.6106 7.25066 15.8013 7.25066 16C7.25066 16.1988 7.32955 16.3894 7.47 16.53L11.47 20.53C11.5394 20.5978 11.6207 20.6521 11.71 20.69C11.8015 20.7296 11.9002 20.7501 12 20.7501C12.0998 20.7501 12.1984 20.7296 12.29 20.69Z" fill="#000000"></path> </g></svg>
                                </th>
                                <th @mouseenter="reportStore.hover_on = 'Convert Deal Date'" @mouseleave="reportStore.hover_on = ''" v-show="reportStore.categories[14].checked" @click="sortByField('Convert Deal Date')">When Booked
									<svg style="vertical-align:top;" v-show="reportStore.sort_by=='Convert Deal Date' && reportStore.sort_order=='asc'"  width="20px" height="20px" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path fill-rule="evenodd" clip-rule="evenodd" d="M12.0809 7.28641L12.4345 6.93286L12.788 7.28641L17 11.4984L16.2929 12.2055L12.9345 8.84707V16.9999H11.9345V8.84707L8.57605 12.2055L7.86895 11.4984L12.0809 7.28641Z" fill="#000000"></path> </g></svg>
									<svg style="vertical-align:top;" v-show="reportStore.sort_by=='Convert Deal Date' && reportStore.sort_order=='desc'"  width="20px" height="20px" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path fill-rule="evenodd" clip-rule="evenodd" d="M11.9191 16.7136L11.5655 17.0671L11.212 16.7136L7 12.5016L7.70711 11.7945L11.0655 15.1529V7.00009H12.0655V15.1529L15.4239 11.7945L16.1311 12.5016L11.9191 16.7136Z" fill="#000000"></path> </g></svg>
                                    <svg viewBox="0 0 24 24" style="vertical-align:top;" v-show="reportStore.sort_by!='Convert Deal Date' && reportStore.hover_on == 'Convert Deal Date'" width="20px" height="20px" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M12.29 20.69C12.3792 20.6521 12.4606 20.5978 12.53 20.53L16.53 16.53C16.6625 16.3878 16.7346 16.1998 16.7312 16.0055C16.7277 15.8112 16.649 15.6258 16.5116 15.4884C16.3742 15.351 16.1888 15.2723 15.9945 15.2688C15.8002 15.2654 15.6122 15.3375 15.47 15.47L12.75 18.19V5.81001L15.47 8.53001C15.6122 8.66249 15.8002 8.73462 15.9945 8.73119C16.1888 8.72776 16.3742 8.64905 16.5116 8.51163C16.649 8.37422 16.7277 8.18884 16.7312 7.99454C16.7346 7.80024 16.6625 7.61219 16.53 7.47001L12.53 3.47001C12.4606 3.40222 12.3792 3.34796 12.29 3.31001C12.1984 3.27038 12.0998 3.24994 12 3.24994C11.9002 3.24994 11.8015 3.27038 11.71 3.31001C11.6207 3.34796 11.5394 3.40222 11.47 3.47001L7.47 7.47001C7.32955 7.61064 7.25066 7.80126 7.25066 8.00001C7.25066 8.19876 7.32955 8.38939 7.47 8.53001C7.61062 8.67046 7.80125 8.74935 8 8.74935C8.19875 8.74935 8.38937 8.67046 8.53 8.53001L11.25 5.81001V18.19L8.53 15.47C8.38937 15.3296 8.19875 15.2507 8 15.2507C7.80125 15.2507 7.61062 15.3296 7.47 15.47C7.32955 15.6106 7.25066 15.8013 7.25066 16C7.25066 16.1988 7.32955 16.3894 7.47 16.53L11.47 20.53C11.5394 20.5978 11.6207 20.6521 11.71 20.69C11.8015 20.7296 11.9002 20.7501 12 20.7501C12.0998 20.7501 12.1984 20.7296 12.29 20.69Z" fill="#000000"></path> </g></svg>
                                </th>
                                <th @mouseenter="reportStore.hover_on = 'Created At'" @mouseleave="reportStore.hover_on = ''" v-show="reportStore.categories[15].checked" @click="sortByField('Created At')">Created On
									<svg style="vertical-align:top;" v-show="reportStore.sort_by=='Created At' && reportStore.sort_order=='asc'"  width="20px" height="20px" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path fill-rule="evenodd" clip-rule="evenodd" d="M12.0809 7.28641L12.4345 6.93286L12.788 7.28641L17 11.4984L16.2929 12.2055L12.9345 8.84707V16.9999H11.9345V8.84707L8.57605 12.2055L7.86895 11.4984L12.0809 7.28641Z" fill="#000000"></path> </g></svg>
									<svg style="vertical-align:top;" v-show="reportStore.sort_by=='Created At' && reportStore.sort_order=='desc'"  width="20px" height="20px" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path fill-rule="evenodd" clip-rule="evenodd" d="M11.9191 16.7136L11.5655 17.0671L11.212 16.7136L7 12.5016L7.70711 11.7945L11.0655 15.1529V7.00009H12.0655V15.1529L15.4239 11.7945L16.1311 12.5016L11.9191 16.7136Z" fill="#000000"></path> </g></svg>
                                    <svg viewBox="0 0 24 24" style="vertical-align:top;" v-show="reportStore.sort_by!='Created At' && reportStore.hover_on == 'Created At'" width="20px" height="20px" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M12.29 20.69C12.3792 20.6521 12.4606 20.5978 12.53 20.53L16.53 16.53C16.6625 16.3878 16.7346 16.1998 16.7312 16.0055C16.7277 15.8112 16.649 15.6258 16.5116 15.4884C16.3742 15.351 16.1888 15.2723 15.9945 15.2688C15.8002 15.2654 15.6122 15.3375 15.47 15.47L12.75 18.19V5.81001L15.47 8.53001C15.6122 8.66249 15.8002 8.73462 15.9945 8.73119C16.1888 8.72776 16.3742 8.64905 16.5116 8.51163C16.649 8.37422 16.7277 8.18884 16.7312 7.99454C16.7346 7.80024 16.6625 7.61219 16.53 7.47001L12.53 3.47001C12.4606 3.40222 12.3792 3.34796 12.29 3.31001C12.1984 3.27038 12.0998 3.24994 12 3.24994C11.9002 3.24994 11.8015 3.27038 11.71 3.31001C11.6207 3.34796 11.5394 3.40222 11.47 3.47001L7.47 7.47001C7.32955 7.61064 7.25066 7.80126 7.25066 8.00001C7.25066 8.19876 7.32955 8.38939 7.47 8.53001C7.61062 8.67046 7.80125 8.74935 8 8.74935C8.19875 8.74935 8.38937 8.67046 8.53 8.53001L11.25 5.81001V18.19L8.53 15.47C8.38937 15.3296 8.19875 15.2507 8 15.2507C7.80125 15.2507 7.61062 15.3296 7.47 15.47C7.32955 15.6106 7.25066 15.8013 7.25066 16C7.25066 16.1988 7.32955 16.3894 7.47 16.53L11.47 20.53C11.5394 20.5978 11.6207 20.6521 11.71 20.69C11.8015 20.7296 11.9002 20.7501 12 20.7501C12.0998 20.7501 12.1984 20.7296 12.29 20.69Z" fill="#000000"></path> </g></svg>
                                </th>
                                <th @mouseenter="reportStore.hover_on = 'Lead Score'" @mouseleave="reportStore.hover_on = ''" v-show="reportStore.categories[16].checked" @click="sortByField('Lead Score')">Lead Score
									<svg style="vertical-align:top;" v-show="reportStore.sort_by=='Lead Score' && reportStore.sort_order=='asc'"  width="20px" height="20px" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path fill-rule="evenodd" clip-rule="evenodd" d="M12.0809 7.28641L12.4345 6.93286L12.788 7.28641L17 11.4984L16.2929 12.2055L12.9345 8.84707V16.9999H11.9345V8.84707L8.57605 12.2055L7.86895 11.4984L12.0809 7.28641Z" fill="#000000"></path> </g></svg>
									<svg style="vertical-align:top;" v-show="reportStore.sort_by=='Lead Score' && reportStore.sort_order=='desc'"  width="20px" height="20px" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path fill-rule="evenodd" clip-rule="evenodd" d="M11.9191 16.7136L11.5655 17.0671L11.212 16.7136L7 12.5016L7.70711 11.7945L11.0655 15.1529V7.00009H12.0655V15.1529L15.4239 11.7945L16.1311 12.5016L11.9191 16.7136Z" fill="#000000"></path> </g></svg>
                                    <svg viewBox="0 0 24 24" style="vertical-align:top;" v-show="reportStore.sort_by!='Lead Score' && reportStore.hover_on == 'Lead Score'" width="20px" height="20px" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M12.29 20.69C12.3792 20.6521 12.4606 20.5978 12.53 20.53L16.53 16.53C16.6625 16.3878 16.7346 16.1998 16.7312 16.0055C16.7277 15.8112 16.649 15.6258 16.5116 15.4884C16.3742 15.351 16.1888 15.2723 15.9945 15.2688C15.8002 15.2654 15.6122 15.3375 15.47 15.47L12.75 18.19V5.81001L15.47 8.53001C15.6122 8.66249 15.8002 8.73462 15.9945 8.73119C16.1888 8.72776 16.3742 8.64905 16.5116 8.51163C16.649 8.37422 16.7277 8.18884 16.7312 7.99454C16.7346 7.80024 16.6625 7.61219 16.53 7.47001L12.53 3.47001C12.4606 3.40222 12.3792 3.34796 12.29 3.31001C12.1984 3.27038 12.0998 3.24994 12 3.24994C11.9002 3.24994 11.8015 3.27038 11.71 3.31001C11.6207 3.34796 11.5394 3.40222 11.47 3.47001L7.47 7.47001C7.32955 7.61064 7.25066 7.80126 7.25066 8.00001C7.25066 8.19876 7.32955 8.38939 7.47 8.53001C7.61062 8.67046 7.80125 8.74935 8 8.74935C8.19875 8.74935 8.38937 8.67046 8.53 8.53001L11.25 5.81001V18.19L8.53 15.47C8.38937 15.3296 8.19875 15.2507 8 15.2507C7.80125 15.2507 7.61062 15.3296 7.47 15.47C7.32955 15.6106 7.25066 15.8013 7.25066 16C7.25066 16.1988 7.32955 16.3894 7.47 16.53L11.47 20.53C11.5394 20.5978 11.6207 20.6521 11.71 20.69C11.8015 20.7296 11.9002 20.7501 12 20.7501C12.0998 20.7501 12.1984 20.7296 12.29 20.69Z" fill="#000000"></path> </g></svg>
                                </th>
                                <th @mouseenter="reportStore.hover_on = 'Tags'" @mouseleave="reportStore.hover_on = ''" v-show="reportStore.categories[17].checked" @click="sortByField('Tag')">Tags
									<svg style="vertical-align:top;" v-show="reportStore.sort_by=='Tag' && reportStore.sort_order=='asc'"  width="20px" height="20px" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path fill-rule="evenodd" clip-rule="evenodd" d="M12.0809 7.28641L12.4345 6.93286L12.788 7.28641L17 11.4984L16.2929 12.2055L12.9345 8.84707V16.9999H11.9345V8.84707L8.57605 12.2055L7.86895 11.4984L12.0809 7.28641Z" fill="#000000"></path> </g></svg>
									<svg style="vertical-align:top;" v-show="reportStore.sort_by=='Tag' && reportStore.sort_order=='desc'"  width="20px" height="20px" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path fill-rule="evenodd" clip-rule="evenodd" d="M11.9191 16.7136L11.5655 17.0671L11.212 16.7136L7 12.5016L7.70711 11.7945L11.0655 15.1529V7.00009H12.0655V15.1529L15.4239 11.7945L16.1311 12.5016L11.9191 16.7136Z" fill="#000000"></path> </g></svg>
                                    <svg viewBox="0 0 24 24" style="vertical-align:top;" v-show="reportStore.sort_by!='Tag' && reportStore.hover_on == 'Tags'" width="20px" height="20px" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M12.29 20.69C12.3792 20.6521 12.4606 20.5978 12.53 20.53L16.53 16.53C16.6625 16.3878 16.7346 16.1998 16.7312 16.0055C16.7277 15.8112 16.649 15.6258 16.5116 15.4884C16.3742 15.351 16.1888 15.2723 15.9945 15.2688C15.8002 15.2654 15.6122 15.3375 15.47 15.47L12.75 18.19V5.81001L15.47 8.53001C15.6122 8.66249 15.8002 8.73462 15.9945 8.73119C16.1888 8.72776 16.3742 8.64905 16.5116 8.51163C16.649 8.37422 16.7277 8.18884 16.7312 7.99454C16.7346 7.80024 16.6625 7.61219 16.53 7.47001L12.53 3.47001C12.4606 3.40222 12.3792 3.34796 12.29 3.31001C12.1984 3.27038 12.0998 3.24994 12 3.24994C11.9002 3.24994 11.8015 3.27038 11.71 3.31001C11.6207 3.34796 11.5394 3.40222 11.47 3.47001L7.47 7.47001C7.32955 7.61064 7.25066 7.80126 7.25066 8.00001C7.25066 8.19876 7.32955 8.38939 7.47 8.53001C7.61062 8.67046 7.80125 8.74935 8 8.74935C8.19875 8.74935 8.38937 8.67046 8.53 8.53001L11.25 5.81001V18.19L8.53 15.47C8.38937 15.3296 8.19875 15.2507 8 15.2507C7.80125 15.2507 7.61062 15.3296 7.47 15.47C7.32955 15.6106 7.25066 15.8013 7.25066 16C7.25066 16.1988 7.32955 16.3894 7.47 16.53L11.47 20.53C11.5394 20.5978 11.6207 20.6521 11.71 20.69C11.8015 20.7296 11.9002 20.7501 12 20.7501C12.0998 20.7501 12.1984 20.7296 12.29 20.69Z" fill="#000000"></path> </g></svg>
                                </th>
								<th @mouseenter="reportStore.hover_on = 'Landing Page'" @mouseleave="reportStore.hover_on = ''" v-show="reportStore.categories[18].checked" @click="sortByField('Landing Page')">Landing Page
									<svg style="vertical-align:top;" v-show="reportStore.sort_by=='Landing Page' && reportStore.sort_order=='asc'"  width="20px" height="20px" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path fill-rule="evenodd" clip-rule="evenodd" d="M12.0809 7.28641L12.4345 6.93286L12.788 7.28641L17 11.4984L16.2929 12.2055L12.9345 8.84707V16.9999H11.9345V8.84707L8.57605 12.2055L7.86895 11.4984L12.0809 7.28641Z" fill="#000000"></path> </g></svg>
									<svg style="vertical-align:top;" v-show="reportStore.sort_by=='Landing Page' && reportStore.sort_order=='desc'"  width="20px" height="20px" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path fill-rule="evenodd" clip-rule="evenodd" d="M11.9191 16.7136L11.5655 17.0671L11.212 16.7136L7 12.5016L7.70711 11.7945L11.0655 15.1529V7.00009H12.0655V15.1529L15.4239 11.7945L16.1311 12.5016L11.9191 16.7136Z" fill="#000000"></path> </g></svg>
                                    <svg viewBox="0 0 24 24" style="vertical-align:top;" v-show="reportStore.sort_by!='Landing Page' && reportStore.hover_on == 'Landing Page'" width="20px" height="20px" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M12.29 20.69C12.3792 20.6521 12.4606 20.5978 12.53 20.53L16.53 16.53C16.6625 16.3878 16.7346 16.1998 16.7312 16.0055C16.7277 15.8112 16.649 15.6258 16.5116 15.4884C16.3742 15.351 16.1888 15.2723 15.9945 15.2688C15.8002 15.2654 15.6122 15.3375 15.47 15.47L12.75 18.19V5.81001L15.47 8.53001C15.6122 8.66249 15.8002 8.73462 15.9945 8.73119C16.1888 8.72776 16.3742 8.64905 16.5116 8.51163C16.649 8.37422 16.7277 8.18884 16.7312 7.99454C16.7346 7.80024 16.6625 7.61219 16.53 7.47001L12.53 3.47001C12.4606 3.40222 12.3792 3.34796 12.29 3.31001C12.1984 3.27038 12.0998 3.24994 12 3.24994C11.9002 3.24994 11.8015 3.27038 11.71 3.31001C11.6207 3.34796 11.5394 3.40222 11.47 3.47001L7.47 7.47001C7.32955 7.61064 7.25066 7.80126 7.25066 8.00001C7.25066 8.19876 7.32955 8.38939 7.47 8.53001C7.61062 8.67046 7.80125 8.74935 8 8.74935C8.19875 8.74935 8.38937 8.67046 8.53 8.53001L11.25 5.81001V18.19L8.53 15.47C8.38937 15.3296 8.19875 15.2507 8 15.2507C7.80125 15.2507 7.61062 15.3296 7.47 15.47C7.32955 15.6106 7.25066 15.8013 7.25066 16C7.25066 16.1988 7.32955 16.3894 7.47 16.53L11.47 20.53C11.5394 20.5978 11.6207 20.6521 11.71 20.69C11.8015 20.7296 11.9002 20.7501 12 20.7501C12.0998 20.7501 12.1984 20.7296 12.29 20.69Z" fill="#000000"></path> </g></svg>
								</th>
							</tr>
						</thead>
						<tbody>
							<tr v-for="report in reportStore.reports">
								<!-- <td v-if="reportStore.categories[0].checked" class="d-none d-lg-block">{{ report.clinic.practice_name }}</td> -->
								<td @click="viewProfile(report.id)" v-if="reportStore.categories[0].checked" class="d-none d-lg-block">{{ report.first_name }}</td>
								<td @click="viewProfile(report.id)" v-if="reportStore.categories[1].checked" class="d-none d-lg-block"><span> {{ (report.last_name!=null? report.last_name : '') }} </span></td>
								 <td class="d-lg-none"><span>{{ (report.value) ? '$' +  report.value  : '' }}</span></td>
								<td class="d-lg-none lead-name"><span>{{ report.first_name + ' ' + report.last_name  }}</span></td>
								<td @click="viewProfile(report.id)" v-if="reportStore.categories[2].checked" class="phone-label"><span>{{ report.phone_form }}</span></td>
								<td @click="viewProfile(report.id)" v-if="reportStore.categories[3].checked" class="source-label"><span>{{ report.source.name }} </span></td>
								<td @click="viewProfile(report.id)" v-if="reportStore.categories[4].checked" class="d-none d-lg-block"><span>{{ report.status.name }}</span></td>
								<td @click="viewProfile(report.id)" v-if="reportStore.categories[5].checked" class="d-none d-lg-block"><span>{{ report.email }}</span></td>
								<td @click="viewProfile(report.id)" v-if="reportStore.categories[6].checked" class="d-none d-lg-block"><span>{{ report.phone }}</span></td>
								<td @click="viewProfile(report.id)" v-if="reportStore.categories[7].checked" class="d-none d-lg-block"><span>{{ report.value? new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(report.value) : '' }}</span></td>
                                <td @click="viewProfile(report.id)" v-if="reportStore.categories[8].checked" class="d-none d-lg-block"><span>{{ report.reason }}</span></td>
                                <td @click="viewProfile(report.id)" v-if="reportStore.categories[9].checked" class="d-none d-lg-block"><span>{{ report.city }}</span></td>
                                <td @click="viewProfile(report.id)" v-if="reportStore.categories[10].checked" class="d-none d-lg-block"><span>{{ report.zip_code }}</span></td>
                                <td @click="viewProfile(report.id)" v-if="reportStore.categories[11].checked" class="d-none d-lg-block"><span>{{ report.lifetimevalue? new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(report.lifetimevalue) : '' }}</span></td>
                                <td @click="viewProfile(report.id)" v-if="reportStore.categories[12].checked" class="d-none d-lg-block"><span>{{ report.consultation_booked_date }}</span></td>
                                <td @click="viewProfile(report.id)" v-if="reportStore.categories[13].checked" class="d-none d-lg-block"><span>{{ report.no_showed_date }}</span></td>
                                <td @click="viewProfile(report.id)" v-if="reportStore.categories[14].checked" class="d-none d-lg-block"><span>{{ report.convert_deal_date }}</span></td>
                                <td @click="viewProfile(report.id)" v-if="reportStore.categories[15].checked" class="d-none d-lg-block"><span>{{ report.created_at }}</span></td>
                                <td @click="viewProfile(report.id)"
                                    v-if="reportStore.categories[16].checked"
                                    class="d-none d-lg-block"
                                    :style="{
											'border': report.lead_score ? '1px solid ' + getBackgroundColor(report.lead_score) : null,
        									'background': report.lead_score ? getBackgroundColor(report.lead_score) : null,
											'width': '65px',
											'border-radius': '50px',
											'text-align': 'center',
											'margin':'10px',
											'height':'35px',
											'padding':'4px',
											'color': (report.lead_score === null || report.lead_score === '') ? 'red' : 'black', // Change text color to red when lead_score is empty or null
										}">
                                    <span :style="{'color':'#FFF'}">{{ report.lead_score }}</span>
                                </td>
                               <td @click="viewProfile(report.id)" v-if="reportStore.categories[17].checked" class="d-none d-lg-block"><span>{{ report.tagName }}</span></td>
							   <td @click="viewProfile(report.id)" v-if="reportStore.categories[18].checked" class="d-none d-lg-block"><span>{{ report.landing_page_url }}</span></td>
								<td class="hover-td">
									<a role="button" class="folder-ico move-ico" data-title="View Profile" @click="viewProfile(report.id)">
										<svg version="1.1" x="0px" y="0px" viewBox="0 0 25 25" style="enable-background:new 0 0 25 25;">
											<g>
												<path d="M12.5,11.5c1.1,0,2.1-0.4,2.8-1.1c0.7-0.7,1.1-1.7,1.1-2.8c0-1.1-0.4-2.1-1.1-2.8s-1.7-1.1-2.8-1.1S10.4,4,9.7,4.8C9,5.5,8.6,6.4,8.6,7.6c0,1.1,0.4,2.1,1.1,2.8C10.4,11.1,11.4,11.5,12.5,11.5z M10.5,5.5c0.6-0.6,1.2-0.8,2-0.8s1.5,0.3,2,0.8c0.6,0.6,0.8,1.2,0.8,2c0,0.8-0.3,1.5-0.8,2c-0.6,0.6-1.2,0.8-2,0.8s-1.5-0.3-2-0.8c-0.6-0.6-0.8-1.2-0.8-2C9.6,6.8,9.9,6.1,10.5,5.5z"/>
												<path d="M21.5,17.8c-0.3-0.5-0.8-0.8-1.4-1.1c-1.3-0.6-2.6-1-3.9-1.4c-1.3-0.3-2.5-0.5-3.7-0.5c-1.2,0-2.5,0.2-3.7,0.5c-1.3,0.3-2.6,0.8-3.9,1.3c-0.6,0.3-1,0.6-1.3,1.1S3,18.8,3,19.4v2h19v-2C22,18.8,21.8,18.2,21.5,17.8z M21,20.3H4v-0.9c0-0.3,0.1-0.7,0.3-1c0.2-0.3,0.5-0.6,1-0.8c1.2-0.6,2.4-1,3.6-1.3c1.2-0.3,2.4-0.4,3.6-0.4s2.4,0.1,3.6,0.4c1.2,0.3,2.4,0.7,3.6,1.3c0.4,0.2,0.7,0.5,1,0.8c0.2,0.3,0.3,0.7,0.3,1V20.3z"/>
											</g>
										</svg>
									</a>
								</td>
							</tr>
						</tbody>
					</table>
					<h6 v-if="reportStore.reports.length==0" class="text-center">There are no reports to display currently!</h6>
					<div class="d-flex justify-content-between pt-3">
						<a v-if="reportStore.page > 1" href="#" class="btn btn-primary" @click.prevent="prev">Prev</a>
						<div class="text-center" style="width:100%" v-if="reportStore.reports.length>0"><span class="badge bg-primary" style="font-size: 16px; margin-top:10px;">{{ reportStore.page + '/' + reportStore.last_page }}</span></div>
						<a v-if="reportStore.page<reportStore.last_page" href="#" class="btn btn-primary" @click.prevent="next">Next</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>
<script>

import { storeToRefs } from 'pinia'
import { watch } from 'vue'
import { useReportStore } from '../../stores/report';
import { useResourceStore } from '../../stores/resource';
import { useAlertStore } from '../../stores/alert';


export default {
    setup (){
        const reportStore = useReportStore();

		const resourceStore = useResourceStore();

		const alertStore = useAlertStore();

		return { reportStore, resourceStore, alertStore };
    },
    data() {
        return {
			showDatePicker : false,
			showDateRangeFilter : false,
			showLeadTypeFilter : false,
			showMediaTypeFilter : false,
			showTagTypeFilter : false,
            showTypeFilter : false,
            showReasonFilter : false,
            stageFilter : 'New Leads',
			showCategoryFilter: true,
            currentDate: new Date(),
			start:null,
			end:null,
            type:null,
			selectedTagName:'',
        }
    },
	computed: {
		checkedCategories: function(){
			let cats = [];
			this.reportStore.categories.forEach(function(category){

				if(category.checked){
					cats.push(category.name);
				}
			});
			return cats;
		},
	},
	updated(){
		this.filterCheckedWidth();
	},
	mounted(){
		let _self = this;
        this.page = this.$route.query.data;
        //this.reportStore.tab = 'Treatments Sold';

		if(this.$route.query.date){
			let date = this.$route.query.date.split('-');
			this.start = date[0];
			this.end = date[1];
		}else{
			this.start = moment().subtract(29, 'days').format('MM/DD/YYYY');
			this.end = moment().format('MM/DD/YYYY');
		}

        if(this.$route.query.type){
            this.type = this.$route.query.type
        }

        if(this.page === 'consultationBooked'){
            this.reportStore.tab = 'Consultations Booked';
        }
        if(this.page === 'newLeads'){
            this.reportStore.tab = 'New Leads';
        }
		if(this.page === 'engaged'){
            this.reportStore.tab = 'Engaged';
        }
        if(this.page === 'ConsultationsShowed'){
            this.reportStore.tab = 'Consultations Showed';
        }
        if(this.page === 'TreatmentsSold'){
            this.reportStore.tab = 'Treatments Sold';
        }
        if(this.page === 'TreatmentsSold(PMS)'){
            this.reportStore.tab = 'Treatments Sold (PMS)';
        }
        if(this.page === 'TreatmentsPresented'){
            this.reportStore.tab = 'Treatments Presented';
        }
        if(this.page === 'ConsultationsFollowUp'){
            this.reportStore.tab = 'Consultations Follow Up';
        }
        if(this.page === 'Nurturing'){
            this.reportStore.tab = 'Nurturing';
        }

		this.dateRangeClicked('true');


		$('#mailLink').removeClass('active');
		$('#profileLink').removeClass('show');
		$('#homeLink').addClass('active');

		const { chart, format } = storeToRefs(this.reportStore);

		watch(
			() => chart,
			function(){
				_self.updateChart()
			},
			{ deep: true }
		);

		watch(
			() => format,
			function(){
				if(_self.reportStore.format != '')
				_self.exportData()
			},
			{ deep: true }
		);


		function cb(start, end) {
			$('#reportrange span').html(_self.start + ' - ' + _self.end);
		}

		$('#reportrange').daterangepicker({
			startDate: this.start,
			endDate: this.end,
			locale: {
				format: 'MM/DD/YYYY'
			},
			ranges: {
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
			}
		}, cb);

		$('#reportrange').on('apply.daterangepicker', function(ev, picker){
			_self.start = picker.startDate.format('MM/DD/YYYY');
			_self.end = picker.endDate.format('MM/DD/YYYY');
			cb(_self.start, _self.end);
			_self.dateRangeClicked();
		});

		cb(this.start, this.end);

		// Search

		$(window).on('resize', function(){
			if (_self.screencheck(991)) {
				$(document).on('click touchstart', function(e){
					if( $(e.target).parent('.lead-search').length === 0 ) {
						$('.lead-search').slideUp(200);
						$('.lead-search-box').removeClass('search-open');
                        $('#wrapper').removeClass('sub-popup-active');
					}
				});
			}
		}).resize();

		$('.lead-search-ico').click( function(){
			$('.lead-search-box .lead-search').not( $(this).next('.lead-search') ).slideUp(200);
			$(this).next('.lead-search').slideToggle(200);
			$(this).parents('.lead-search-box').toggleClass('search-open');
            $(this).parents('#wrapper').toggleClass('sub-popup-active');
			return false;
		});
		$('.lead-search').on('click touchstart', function(event) {
			event.stopPropagation();
		});

		// Filter

		$('.filter-ico').click( function(){
			$('.filter-icons .lead-filterBy-box').not( $(this).next('.lead-filterBy-box') ).slideUp(200);
			$(this).next('.lead-filterBy-box').slideToggle(200);
			$(this).parents('.filter-icons').toggleClass('filter-open');
            $(this).parents('#wrapper').toggleClass('sub-popup-active');
			return false;
		});
		$('.lead-filterBy-box').on('click touchstart', function(event) {
			event.stopPropagation();
		});
		$(document).on('click touchstart', function(e){
			if( $(e.target).parent('.lead-filterBy-box').length === 0 ) {
				$('.lead-filterBy-box').slideUp(200);
				$('.filter-icons').removeClass('filter-open');
			}
		});
		$('.filterBy-back-btn, .filterBy-save-btn').click( function(){
			$('.lead-filterBy-box').slideUp(200);
			$('.filter-icons').removeClass('filter-open');
			return false;
		});

		$('.filterBy-viewall-btn').click( function(){
			$(this).parents('.lead-filterBy-box').addClass('filter-viewall');
			return false;
		});
		$('.filterBy-viewall-save').click( function(){
			$(this).parents('.lead-filterBy-box').removeClass('filter-viewall');
			return false;
		});

		if(localStorage.getItem('reportCategories')!=null){
			this.reportStore.categories = JSON.parse(localStorage.getItem('reportCategories'));
		}

		this.filterCheckedWidth();

		this.resourceStore.getSources();

		this.reportStore.getTags();

		this.resourceStore.getStatuses();
        document.addEventListener('click', this.handleClickOutside);

		//this.list();
	},
    beforeDestroy() {
        document.removeEventListener('click', this.handleClickOutside);
    },
	methods: {
        closeDropdownsOnOutsideClick(event) {
            const dropdownContainer = this.$refs.dropdownContainer;
            // Check if the click occurred outside both dropdowns
            if(dropdownContainer){
                if (!dropdownContainer.contains(event.target)) {
                    this.showLeadTypeFilter = false;
                    this.showMediaTypeFilter = false;
                    this.showTagTypeFilter = false;
                }
            }
        },
        handleClickOutside(event) {
            // Call closeDropdownsOnOutsideClick method when click occurs on the page
            this.closeDropdownsOnOutsideClick(event);
        },
        toggleDropdown(dropdown) {
            if(dropdown === 'leadType'){
                this.showLeadTypeFilter = !this.showLeadTypeFilter;
                if (this.showLeadTypeFilter) {
                    this.showMediaTypeFilter = false;
					this.showTagTypeFilter = false;
                    this.showTypeFilter = false;
                    this.showReasonFilter = false;
                }
            }
            if(dropdown === 'mediaType'){
                this.showMediaTypeFilter = !this.showMediaTypeFilter;
                if (this.showMediaTypeFilter) {
                    this.showLeadTypeFilter = false;
					this.showTagTypeFilter = false;
                    this.showTypeFilter = false;
                    this.showReasonFilter = false;
                }
            }
			if(dropdown === 'tagType'){
                this.showTagTypeFilter = !this.showTagTypeFilter;
                if (this.showTagTypeFilter) {
                    this.showLeadTypeFilter = false;
					this.showMediaTypeFilter = false;
                    this.showTypeFilter = false;
                    this.showReasonFilter = false;
                }
            }
            if(dropdown === 'type'){
                this.showTypeFilter = !this.showTypeFilter;
                if (this.showTypeFilter) {
                    this.showLeadTypeFilter = false;
                    this.showMediaTypeFilter = false;
                    this.showTagTypeFilter = false;
                    this.showReasonFilter = false;
                }
            }
            if(dropdown === 'reason'){
                this.showReasonFilter = !this.showReasonFilter;
                if(this.showReasonFilter){
                    this.showLeadTypeFilter = false;
                    this.showMediaTypeFilter = false;
                    this.showTagTypeFilter = false;
                    this.showTypeFilter = false;
                }
            }
        },
		subtractDays(date, days) {
			date.setDate(date.getDate() - days);

			return date;
		},
		dateRangeClicked(initial_load=false){
			this.reportStore.fromto = this.start+ '-' + this.end;
			this.reportStore.type = this.type;
			this.reportStore.list();
			this.showDateRangeFilter = false;
            const query = { ...this.$route.query };
            if (query.type === 'lifetimevalue' && initial_load === false) {
                delete query.date;
                this.$router.push({ query });
            }
		},
		list(){
			let _self = this;
			this.reportStore.list();
		},
		search(){
			if(this.reportStore.query.length>=3 || this.reportStore.query.length==0){
				this.reportStore.list();
			}
		},
		filterStatus(name, id){
			this.reportStore.status_id = id;
			this.reportStore.status = name;
			this.reportStore.list();
			this.showLeadTypeFilter = false;
		},
		filterSource(name, id){
			this.reportStore.source_id = id;
			this.reportStore.source = name;
			this.reportStore.list();
			this.showMediaTypeFilter = false;
		},
		filterTag(name, id){
			this.reportStore.tagId = id;
			this.reportStore.selectedTagName = name;
			this.reportStore.list();
			this.showTagTypeFilter = false;
		},
        filterType(type){
            this.reportStore.selectedType = type;
            this.reportStore.list();
            this.showTypeFilter = false;
        },
        filterReason(reason){
            this.reportStore.selectedReason = reason;
            this.reportStore.list();
            this.showReasonFilter = false;
        },
		selectTag(tagName, tagId) {
            // Your selectTag method logic here
            this.selectedTagName = tagName;
            this.showMediaTypeFilter = false;
		},
		filterTabStatus(name){
			this.reportStore.tab = name;
			this.reportStore.page = 1;
			this.reportStore.list();

            // Get the current route's query parameters
            const query = { ...this.$route.query };
            delete query.date;
            // Update the 'data' query parameter with the new name value
            query.data = name;

            // Delete the 'type' query parameter if it is 'lifetimevalue'
            if (query.type === 'lifetimevalue') {
                delete query.type;
            }

            // Push the updated query parameters to the router
            this.$router.push({ query });
		},
		filterCheckedWidth(){
			let catCount = this.checkedCategories.length;
			$('.report-table-box table tr').css('grid-template-columns', 'repeat('+catCount+', 1fr)')
		},
		saveCategories(){
			localStorage.setItem('reportCategories', JSON.stringify(this.reportStore.categories));
			$('.lead-filterBy-box').hide();
			this.alertStore.success = true;
          	this.alertStore.message = 'Column preferences have been saved successfully!';
		},
		sortByField(field){
			if(this.reportStore.sort_by == field){
				if(this.reportStore.sort_order == 'asc'){
					this.reportStore.sort_order = 'desc';
				}else{
					this.reportStore.sort_order = 'asc';
				}
			}else{
				this.reportStore.sort_by = field;
				this.reportStore.sort_order = 'desc';
			}
			this.reportStore.list();
		},
		prev(){
			if(this.reportStore.page>1){
				this.reportStore.page--;
				this.reportStore.list();
			}
		},
		next(){
			this.reportStore.page++;
			this.reportStore.list();
		},
		updateChart(){
			let _self = this;

			if(_self.reportStore.chart){

				let ctx = null;

				$('#myChart').remove();

				$('#chartContainer').append('<canvas id="myChart"></canvas>')

				ctx = document.getElementById('myChart');
				new Chart(ctx, {
					type : 'line',
					data : {
						labels : _self.reportStore.chart.y_data,
						datasets : [
							{
								data : _self.reportStore.chart.x_data,
								label :  _self.reportStore.chart.tab,
								borderColor : "#425BCF",
								fill : false,
								pointStyle: 'circle',
								pointRadius: 10,
								pointHoverRadius: 15
							}
						]
					},
					options : {
						title : {
							display : true,
							text : 'Leads Chart'
						}
					}
				});
			}
		},
		exportData(){
			this.reportStore.export();
		},
		viewProfile(id){
			localStorage.setItem('last_path', '/crtx/patient-profile/'+id);
			window.open('/crtx/patient-profile/'+id, '_blank');
		},
		screencheck(mediasize){
			if (typeof window.matchMedia !== "undefined"){
				var screensize = window.matchMedia("(max-width:"+ mediasize+"px)");
				if( screensize.matches ) {
					return true;
				}else {
					return false;
				}
			} else { // for IE9 and lower browser
				if( $winW() <=  mediasize ) {
					return true;
				}else {
					return false;
				}
			}
		},
		recordsPerPageChange(){
			this.reportStore.list();
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
	}
}
</script>

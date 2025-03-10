<template>
    <div id="main">
        <div class="container-fluid p-0">
            <div class="px-0 py-0 mt-4">
                <div class="page-back-btn d-lg-none">
                    <a role="button" class="back-btn" @click="showProfilePage()">
                        <svg
                            width="20"
                            height="14"
                            viewBox="0 0 20 14"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                fill-rule="evenodd"
                                clip-rule="evenodd"
                                d="M19.4411 6.37497H2.04859L7.25172 1.19185C7.49609 0.947473 7.49609 0.551848 7.25172 0.308098C7.00734 0.0637231 6.61172 0.0637231 6.36797 0.308098L0.180469 6.49497C-0.0601562 6.7356 -0.0601562 7.1381 0.180469 7.37872L6.36797 13.5662C6.61234 13.8106 7.00797 13.8106 7.25172 13.5662C7.49609 13.3218 7.49609 12.9262 7.25172 12.6825L2.04859 7.62497H19.4411C19.7861 7.62497 20.0661 7.34497 20.0661 6.99997C20.0661 6.65497 19.7861 6.37497 19.4411 6.37497Z"
                                fill="#514F5F"
                            />
                        </svg>
                    </a>
                </div>
                <div class="page-title d-flex flex-column flex-md-row justify-content-between align-items-center">
                    <h3 class="mb-md-0">Manage Your Practice</h3>
                    <div class="pop-bottom-btn d-sm-flex flex-column flex-sm-row my-2">
                        <!--                        <router-link to="/crtx/account/step-1" class="tooltip-ico btn btn-primary px-4 m-1" data-title="Add Practice" >
                                                    <svg width="16" height="16" viewBox="0 0 21 21" xmlns="http://www.w3.org/2000/svg" style="fill:white;">
                                                        <path d="M12.4763 0.836293V9.03629H20.2563V12.6963H12.4763V20.9363H8.47631V12.6963H0.736306V9.03629H8.47631V0.836293H12.4763Z"/>
                                                    </svg>
                                                    &nbsp;Add Practice
                                                </router-link>-->
                        <router-link to="/crtx/help" style="white-space: nowrap;" class="btn btn-primary px-4  m-1">Need
                            Assistance?
                        </router-link>
                    </div>
                </div>
                <div class="tab-data">
                    <!--                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                                            <li class="nav-item" role="presentation">
                                                <a
                                                    class="nav-link active"
                                                    id="PracticeInformation-tab"
                                                    data-bs-toggle="tab"
                                                    data-bs-target="#PracticeInformation"
                                                    type="button"
                                                    role="tab"
                                                    aria-controls="PracticeInformation"
                                                    aria-selected="true"
                                                >Practice Information</a
                                                >
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <a
                                                    class="nav-link"
                                                    id="AILearning-tab"
                                                    data-bs-toggle="tab"
                                                    data-bs-target="#AILearning"
                                                    type="button"
                                                    role="tab"
                                                    aria-controls="AILearning"
                                                    aria-selected="false"
                                                >Marketing</a
                                                >
                                            </li>
                                             <li class="nav-item" role="presentation" v-if="clinicStore.clinic.nurture_automation == 'Yes'">
                                                <a
                                                    class="nav-link"
                                                    id="Nurturing-tab"
                                                    data-bs-toggle="tab"
                                                    data-bs-target="#Nurturing"
                                                    type="button"
                                                    role="tab"
                                                    aria-controls="Nurturing"
                                                    aria-selected="false"
                                                >Nurturing Templates</a
                                                >
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <a
                                                    class="nav-link"
                                                    id="EmailTemplates-tab"
                                                    data-bs-toggle="tab"
                                                    data-bs-target="#EmailTemplates"
                                                    type="button"
                                                    role="tab"
                                                    aria-controls="EmailTemplates"
                                                    aria-selected="false"
                                                    @click="emailTabClicked()"
                                                >Email Templates</a
                                                >
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <a
                                                    class="nav-link"
                                                    id="TextTemplates-tab"
                                                    data-bs-toggle="tab"
                                                    data-bs-target="#TextTemplates"
                                                    type="button"
                                                    role="tab"
                                                    aria-controls="TextTemplates"
                                                    aria-selected="false"
                                                    @click="textTabClicked()"
                                                >Text Templates</a
                                                >
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <a
                                                    class="nav-link"
                                                    id="Calendar-tab"
                                                    data-bs-toggle="tab"
                                                    data-bs-target="#Calendar"
                                                    type="button"
                                                    role="tab"
                                                    aria-controls="Calendar"
                                                    aria-selected="false"
                                                >Appointment Calendar</a
                                                >
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <a
                                                    class="nav-link"
                                                    id="Automation-tab"
                                                    data-bs-toggle="tab"
                                                    data-bs-target="#Automation"
                                                    type="button"
                                                    role="tab"
                                                    aria-controls="Automation"
                                                    aria-selected="false"
                                                >Automation</a>
                                            </li>
                                        </ul>-->
                    <div class="tab-content" id="myTabContent">
                        <div
                            id="PracticeInformation"
                            v-if="currentPage==='practice-information'"
                        >
                            <div class="tab-main-title d-lg-none">
                                <h5>Practice Information</h5>
                            </div>
                            <div
                                class="tab-main-box"
                                :class="
                                  general_details_editing ? 'tab-edit-active tab-main-active' : 'tab-disabled-active'
                                "
                            >
                                <div class="tab-title-box d-flex align-items-center">
                                    <div class="tab-title" @click.prevent="general_details_editing = true">
                                        <h3>General Details</h3>
                                    </div>
                                    <div
                                        class="edit-option d-none d-lg-flex ms-auto"
                                        :class="general_details_editing ? 'edit-active' : ''"
                                    >
                                        <a role="button" class="save-input manage-cancel"
                                           v-show="general_details_editing" @click="general_details_editing=false">Cancel</a>
                                        <a
                                            v-if="!general_details_editing"
                                            role="button"
                                            @click.prevent="general_details_editing = true"
                                            class="edit-input manage-edit"
                                        >Edit
                                            <svg
                                                width="18"
                                                height="18"
                                                viewBox="0 0 18 18"
                                                xmlns="http://www.w3.org/2000/svg"
                                            >
                                                <path
                                                    d="M16 17.25C16 16.848 15.644 16.5 15.25 16.5C12.689 16.5 3.311 16.5 0.75 16.5C0.356 16.5 0 16.848 0 17.25C0 17.652 0.356 18 0.75 18H15.25C15.644 18 16 17.652 16 17.25ZM8.597 13.852L17.721 4.727C17.892 4.556 18 4.304 18 4.043C18 3.814 17.917 3.577 17.72 3.381L14.605 0.277C14.42 0.0919999 14.176 0 13.933 0C13.69 0 13.447 0.0919999 13.261 0.277L4.118 9.38C3.549 11.143 2.563 14.203 2.492 14.461C2.472 14.536 2.463 14.611 2.463 14.685C2.463 15.146 2.812 15.533 3.228 15.533C3.739 15.533 4.219 15.344 8.597 13.852ZM5.327 10.51L7.464 12.647L4.296 13.693L5.327 10.51ZM6.282 9.344L13.933 1.728L16.268 4.055L8.631 11.693L6.282 9.344Z"
                                                />
                                            </svg>
                                        </a>
                                        <a
                                            v-else
                                            role="button"
                                            @click.prevent="general_details_editing_save()"
                                            class="save-input manage-save"
                                        >Save</a
                                        >
                                    </div>
                                </div>
                                <div class="tab-sub-box">
                                    <div class="d-lg-none d-flex align-items-center">
                                        <a role="button" class="back-btn" @click="resetActiveTabs()">
                                            <svg
                                                width="20"
                                                height="14"
                                                viewBox="0 0 20 14"
                                                fill="none"
                                                xmlns="http://www.w3.org/2000/svg"
                                            >
                                                <path
                                                    fill-rule="evenodd"
                                                    clip-rule="evenodd"
                                                    d="M19.4411 6.37497H2.04859L7.25172 1.19185C7.49609 0.947473 7.49609 0.551848 7.25172 0.308098C7.00734 0.0637231 6.61172 0.0637231 6.36797 0.308098L0.180469 6.49497C-0.0601562 6.7356 -0.0601562 7.1381 0.180469 7.37872L6.36797 13.5662C6.61234 13.8106 7.00797 13.8106 7.25172 13.5662C7.49609 13.3218 7.49609 12.9262 7.25172 12.6825L2.04859 7.62497H19.4411C19.7861 7.62497 20.0661 7.34497 20.0661 6.99997C20.0661 6.65497 19.7861 6.37497 19.4411 6.37497Z"
                                                    fill="#514F5F"
                                                />
                                            </svg>
                                        </a>
                                        <a
                                            role="button"
                                            class="save-input btn px-4 btn-primary tab-save"
                                            @click.prevent="general_details_editing_save()"
                                        >Save</a
                                        >
                                    </div>
                                    <div class="d-lg-none mobile-tab-title">
                                        <h3>General Details</h3>
                                    </div>
                                    <div class="row gx-3">
                                        <div class="col-12">
                                            <div class="row">
                                                <!--                                                <div class="col-12">
                                                                                                    <div class="form-group">
                                                                                                        <span
                                                                                                            class="d-block text-danger mt-1"
                                                                                                            v-text="clinicStore.errors.industry"
                                                                                                        ></span>
                                                                                                        <select
                                                                                                            v-model="clinicStore.clinic.industry"
                                                                                                            class="form-control form-select"
                                                                                                            :class="clinicStore.clinic.industry ? 'filled' : ''"
                                                                                                        >
                                                                                                            <option disabled readonly value="0">Select Type</option>
                                                                                                            <option selected value="Dental">Dental</option>
                                                                                                            <option value="Medical">Medical</option>
                                                                                                        </select>
                                                                                                        <label>Type</label>
                                                                                                    </div>
                                                                                                    &lt;!&ndash;/.form-group&ndash;&gt;
                                                                                                </div>-->
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <span
                                                            class="d-block text-danger mt-1"
                                                            v-text="clinicStore.errors.website_url"
                                                        ></span>
                                                        <input
                                                            v-model="clinicStore.clinic.website_url"
                                                            type="text"
                                                            class="form-control"
                                                            :class="clinicStore.clinic.website_url ? 'filled' : ''"
                                                        />
                                                        <label>Website URL</label>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-lg-6">
                                                    <div class="form-group">
                                                        <span
                                                            class="d-block text-danger mt-1"
                                                            v-text="clinicStore.errors.practice_name"
                                                        ></span>
                                                        <input
                                                            v-model="clinicStore.clinic.practice_name"
                                                            type="text"
                                                            class="form-control"
                                                            :class="clinicStore.clinic.practice_name ? 'filled' : ''"
                                                        />
                                                        <label>Practice Name</label>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-lg-6">
                                                    <div class="form-group">
                                                        <span
                                                            class="d-block text-danger mt-1"
                                                            v-text="clinicStore.errors.practice_legal_name"
                                                        ></span>
                                                        <input
                                                            v-model="clinicStore.clinic.practice_legal_name"
                                                            type="text"
                                                            class="form-control"
                                                            :class="clinicStore.clinic.practice_legal_name ? 'filled' : ''"
                                                        />
                                                        <label>Practice Legal Name</label>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-lg-6">
                                                    <div class="form-group">
                                                    <span
                                                        class="d-block text-danger mt-1"
                                                        v-text="clinicStore.errors.practice_email"
                                                    ></span>
                                                        <input
                                                            v-model="clinicStore.clinic.practice_email"
                                                            type="text"
                                                            class="form-control"
                                                            :class="clinicStore.clinic.practice_email ? 'filled' : ''"
                                                        />
                                                        <label>Practice Email</label>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-lg-6">
                                                    <div class="form-group">
                                                        <span
                                                            class="d-block text-danger mt-1"
                                                            v-text="clinicStore.errors.phone"
                                                        ></span>
                                                        <input
                                                            id="phone"
                                                            v-model="clinicStore.clinic.phone"
                                                            type="text"
                                                            class="form-control"
                                                            :class="clinicStore.clinic.phone ? 'filled' : ''"
                                                        />
                                                        <label>Practice Phone Number</label>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-lg-6">
                                                    <div class="form-group">
                                                    <span
                                                        class="d-block text-danger mt-1"
                                                        v-text="clinicStore.errors.primary_doctor_firstname"
                                                    ></span>
                                                        <input
                                                            v-model="clinicStore.clinic.primary_doctor_firstname"
                                                            type="text"
                                                            class="form-control"
                                                            :class="clinicStore.clinic.primary_doctor_firstname ? 'filled' : ''"
                                                        />
                                                        <label>Primary Doctor Firstname</label>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-lg-6">
                                                    <div class="form-group">
                                                        <span
                                                            class="d-block text-danger mt-1"
                                                            v-text="clinicStore.errors.primary_doctor_lastname"
                                                        ></span>
                                                        <input
                                                            id="primary_doctor_lastname"
                                                            v-model="clinicStore.clinic.primary_doctor_lastname"
                                                            type="text"
                                                            class="form-control"
                                                            :class="clinicStore.clinic.primary_doctor_lastname ? 'filled' : ''"
                                                        />
                                                        <label>Primary Doctor Lastname</label>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-lg-6">
                                                    <div class="form-group">
                                                    <span
                                                        class="d-block text-danger mt-1"
                                                        v-text="clinicStore.errors.primary_doctor_email"
                                                    ></span>
                                                        <input
                                                            v-model="clinicStore.clinic.primary_doctor_email"
                                                            type="text"
                                                            class="form-control"
                                                            :class="clinicStore.clinic.primary_doctor_email ? 'filled' : ''"
                                                        />
                                                        <label>Primary Doctor Email</label>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-lg-6">
                                                    <div class="form-group">
                                                        <span
                                                            class="d-block text-danger mt-1"
                                                            v-text="clinicStore.errors.primary_doctor_phone"
                                                        ></span>
                                                        <input
                                                            id="primary_doctor_phone"
                                                            v-model="clinicStore.clinic.primary_doctor_phone"
                                                            type="text"
                                                            class="form-control"
                                                            :class="clinicStore.clinic.primary_doctor_phone ? 'filled' : ''"
                                                        />
                                                        <label>Primary Doctor Phone</label>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-lg-6">
                                                    <div class="form-group">
                                                        <div class="my-1" v-if="general_details_editing">
                                                            <label for="clinic_logo"><a style="width:auto !important;"
                                                                                        role="button"
                                                                                        class="save-input manage-cancel">Upload
                                                                Logo <b id="clinic_logo_name"></b></a></label>
                                                            <input
                                                                id="clinic_logo"
                                                                type="file"
                                                                class="form-control"
                                                                :class="clinicStore.clinic.clinic_logo ? 'filled' : ''"
                                                                style="display:none;"
                                                                @change="handleFileUpload"
                                                            />
                                                        </div>
                                                        <figure v-if="clinicStore.clinic.clinic_logo"
                                                                class="d-lg-block d-none mt-1">
                                                            <img :src="clinicStore.clinic.clinic_logo"
                                                                 class="img-thumbnail w-50"/>
                                                        </figure>
                                                        <span
                                                            class="d-block text-danger mt-1"
                                                            v-text="clinicStore.errors.clinic_logo"
                                                        ></span>
                                                        <label>Clinic Logo</label>
                                                        <figure v-if="clinicStore.clinic.clinic_logo"
                                                                class="d-block d-lg-none mt-3">
                                                            <img :src="clinicStore.clinic.clinic_logo"
                                                                 class="img-thumbnail w-50"/>
                                                        </figure>
                                                    </div>
                                                </div>

                                                <div class="col-12 col-lg-6">
                                                    <div class="form-group">
                                                        <span
                                                            class="d-block text-danger mt-1"
                                                            v-text="clinicStore.errors.tracking_phone"
                                                        ></span>
                                                        <input
                                                            id="tracking_phone"
                                                            v-model="computedTrackingPhone"
                                                            type="text"
                                                            class="form-control"
                                                            :class="computedTrackingPhone ? 'filled' : ''"
                                                        />
                                                        <label>Tracking Number Forwards To</label>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-lg-6"></div>

                                                <!--                                                <div class="col-12 col-lg-6" v-if="clinicStore.clinic.clinic_logo">-->
                                                <!--                                                    <div class="form-group">-->
                                                <!--                                                        <figure style="width:150px; height:auto; border-radius: 75px; margin-top:15px;">-->
                                                <!--                                                            <img  :src="clinicStore.clinic.clinic_logo"/>-->
                                                <!--                                                        </figure>-->
                                                <!--                                                    </div>-->
                                                <!--                                                </div>-->
                                                <!--                                                <div class="col-12">
                                                                                                    <div class="form-group flex-column">
                                                                                                        <h5 class="d-lg-none">Practice Specialty</h5>
                                                                                                        <label class="d-none d-lg-block">Practice Speciality</label>
                                                                                                        <div class="check-value">
                                                                                                            <p>
                                                                                                                {{
                                                                                                                    clinicStore.clinic.practice_specialty
                                                                                                                        ? clinicStore.clinic.practice_specialty.join(", ")
                                                                                                                        : ""
                                                                                                                }}
                                                                                                            </p>
                                                                                                        </div>
                                                                                                        <div class="check-group">
                                                                                                            <div class="d-flex flex-wrap">
                                                                                                                <div class="btn-checkbox mb-2 me-2">
                                                                                                                    <input
                                                                                                                        type="checkbox"
                                                                                                                        class="btn-check"
                                                                                                                        id="GeneralPractitioner"
                                                                                                                        name="PracticeSpecialty"
                                                                                                                        value="General Dentist"
                                                                                                                        v-model="clinicStore.clinic.practice_specialty"
                                                                                                                    />
                                                                                                                    <label
                                                                                                                        class="btn btn-outline-primary"
                                                                                                                        for="GeneralPractitioner"
                                                                                                                    >General Dentist</label
                                                                                                                    ><br/>
                                                                                                                </div>
                                                                                                                <div class="btn-checkbox mb-2 me-2">
                                                                                                                    <input
                                                                                                                        type="checkbox"
                                                                                                                        class="btn-check"
                                                                                                                        id="CosmeticDentist"
                                                                                                                        name="CosmeticDentist"
                                                                                                                        value="Cosmetic Dentist"
                                                                                                                        v-model="clinicStore.clinic.practice_specialty"
                                                                                                                    />
                                                                                                                    <label
                                                                                                                        class="btn btn-outline-primary"
                                                                                                                        for="CosmeticDentist"
                                                                                                                    >Cosmetic Dentist</label
                                                                                                                    ><br/>
                                                                                                                </div>
                                                                                                                <div class="btn-checkbox mb-2 me-2">
                                                                                                                    <input
                                                                                                                        type="checkbox"
                                                                                                                        class="btn-check"
                                                                                                                        id="OralSurgeon"
                                                                                                                        name="PracticeSpecialty"
                                                                                                                        value="Oral Surgeon"
                                                                                                                        v-model="clinicStore.clinic.practice_specialty"
                                                                                                                    />
                                                                                                                    <label class="btn btn-outline-primary"
                                                                                                                           for="OralSurgeon"
                                                                                                                    >Oral Surgeon</label
                                                                                                                    ><br/>
                                                                                                                </div>
                                                                                                                <div class="btn-checkbox mb-2 me-2">
                                                                                                                    <input
                                                                                                                        type="checkbox"
                                                                                                                        class="btn-check"
                                                                                                                        id="Periodontist"
                                                                                                                        name="PracticeSpecialty"
                                                                                                                        value="Periodontist"
                                                                                                                        v-model="clinicStore.clinic.practice_specialty"
                                                                                                                    />
                                                                                                                    <label
                                                                                                                        class="btn btn-outline-primary"
                                                                                                                        for="Periodontist"
                                                                                                                    >Periodontist</label
                                                                                                                    ><br/>
                                                                                                                </div>
                                                                                                                <div class="btn-checkbox mb-2 me-2">
                                                                                                                    <input
                                                                                                                        type="checkbox"
                                                                                                                        class="btn-check"
                                                                                                                        id="Prosthodontist"
                                                                                                                        name="PracticeSpecialty"
                                                                                                                        value="Prosthodontist"
                                                                                                                        v-model="clinicStore.clinic.practice_specialty"
                                                                                                                    />
                                                                                                                    <label
                                                                                                                        class="btn btn-outline-primary"
                                                                                                                        for="Prosthodontist"
                                                                                                                    >Prosthodontist</label
                                                                                                                    ><br/>
                                                                                                                </div>
                                                                                                                <div class="btn-checkbox mb-2 me-2">
                                                                                                                    <input
                                                                                                                        type="checkbox"
                                                                                                                        class="btn-check"
                                                                                                                        id="MultiSpecialty"
                                                                                                                        name="PracticeSpecialty"
                                                                                                                        value="Multi-Speciality"
                                                                                                                        v-model="clinicStore.clinic.practice_specialty"
                                                                                                                    />
                                                                                                                    <label
                                                                                                                        class="btn btn-outline-primary"
                                                                                                                        for="MultiSpecialty"
                                                                                                                    >Multi-Speciality</label
                                                                                                                    ><br/>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                            <span
                                                                                                                class="d-block text-danger mt-1"
                                                                                                                v-text="clinicStore.errors.practice_specialty"
                                                                                                            ></span>
                                                                                                        </div>
                                                                                                        <div
                                                                                                            id="showOther2"
                                                                                                            v-if="
                                                                                  clinicStore.clinic.practice_specialty &&
                                                                                  clinicStore.clinic.practice_specialty.includes('Multi-Speciality')
                                                                                "
                                                                                                        >
                                                                                                            <div class="row">
                                                                                                                <div class="col-lg-12">
                                                                                                                    <div class="form-group">
                                                                                      <span
                                                                                          class="d-block text-danger mt-1"
                                                                                          v-text="clinicStore.errors.practice_specialty_other"
                                                                                      ></span>
                                                                                                                        <input
                                                                                                                            type="text"
                                                                                                                            class="form-control"
                                                                                                                            v-model="
                                                                                          clinicStore.clinic.practice_specialty_other
                                                                                        " :class="clinicStore.clinic.practice_specialty_other ? 'filled' : ''"
                                                                                                                        />
                                                                                                                        <label>Please enter Multi-Speciality.</label>
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="col-12">
                                                                                                    <div class="form-group flex-column">
                                                                                                        <h5 class="d-lg-none">Primary Services</h5>
                                                                                                        <label class="d-none d-lg-block">Primary Services</label>
                                                                                                        <div class="check-value">
                                                                                                            <p>
                                                                                                                {{
                                                                                                                    clinicStore.clinic.primary_services
                                                                                                                        ? clinicStore.clinic.primary_services.join(", ")
                                                                                                                        : ""
                                                                                                                }}
                                                                                                            </p>
                                                                                                        </div>
                                                                                                        <div class="check-group">
                                                                                                            <div class="d-flex flex-wrap">
                                                                                                                <div class="btn-checkbox mb-2 me-2">
                                                                                                                    <input
                                                                                                                        type="checkbox"
                                                                                                                        class="btn-check"
                                                                                                                        id="DentalImplants"
                                                                                                                        value="Dental Implants (includes Full Arch)"
                                                                                                                        v-model="clinicStore.clinic.primary_services"
                                                                                                                    />
                                                                                                                    <label
                                                                                                                        class="btn btn-outline-primary"
                                                                                                                        for="DentalImplants"
                                                                                                                    >Dental Implants (includes Full Arch)</label
                                                                                                                    ><br/>
                                                                                                                </div>
                                                                                                                <div class="btn-checkbox mb-2 me-2">
                                                                                                                    <input
                                                                                                                        type="checkbox"
                                                                                                                        class="btn-check"
                                                                                                                        id="CosmeticDentistry"
                                                                                                                        value="Cosmetic Dentistry (includes Veneers)"
                                                                                                                        v-model="clinicStore.clinic.primary_services"
                                                                                                                    />
                                                                                                                    <label
                                                                                                                        class="btn btn-outline-primary"
                                                                                                                        for="CosmeticDentistry"
                                                                                                                    >Cosmetic Dentistry (includes Veneers)</label
                                                                                                                    ><br/>
                                                                                                                </div>
                                                                                                                <div class="btn-checkbox mb-2 me-2">
                                                                                                                    <input
                                                                                                                        type="checkbox"
                                                                                                                        class="btn-check"
                                                                                                                        id="TraditionalOMSservices"
                                                                                                                        value="Traditional OMS services"
                                                                                                                        v-model="clinicStore.clinic.primary_services"
                                                                                                                    />
                                                                                                                    <label
                                                                                                                        class="btn btn-outline-primary"
                                                                                                                        for="TraditionalOMSservices"
                                                                                                                    >Traditional OMS services</label
                                                                                                                    ><br/>
                                                                                                                </div>
                                                                                                                <div class="btn-checkbox mb-2 me-2">
                                                                                                                    <input
                                                                                                                        type="checkbox"
                                                                                                                        class="btn-check"
                                                                                                                        id="NewPatient"
                                                                                                                        value="New Patient/Emergency"
                                                                                                                        v-model="clinicStore.clinic.primary_services"
                                                                                                                    />
                                                                                                                    <label class="btn btn-outline-primary"
                                                                                                                           for="NewPatient"
                                                                                                                    >New Patient/Emergency</label
                                                                                                                    ><br/>
                                                                                                                </div>
                                                                                                                <div class="btn-checkbox mb-2 me-2">
                                                                                                                    <input
                                                                                                                        type="checkbox"
                                                                                                                        class="btn-check"
                                                                                                                        id="TraditionalGPservices"
                                                                                                                        value="Traditional GP services (includes Hygiene)"
                                                                                                                        v-model="clinicStore.clinic.primary_services"
                                                                                                                    />
                                                                                                                    <label
                                                                                                                        class="btn btn-outline-primary"
                                                                                                                        for="TraditionalGPservices"
                                                                                                                    >Traditional GP services (includes Hygiene)</label
                                                                                                                    ><br/>
                                                                                                                </div>
                                                                                                                <div class="btn-checkbox mb-2 me-2">
                                                                                                                    <input
                                                                                                                        type="checkbox"
                                                                                                                        class="btn-check"
                                                                                                                        id="Orthodontics"
                                                                                                                        value="Orthodontics and/or Clear Braces"
                                                                                                                        v-model="clinicStore.clinic.primary_services"
                                                                                                                    />
                                                                                                                    <label
                                                                                                                        class="btn btn-outline-primary"
                                                                                                                        for="Orthodontics"
                                                                                                                    >Orthodontics and/or Clear Braces</label
                                                                                                                    ><br/>
                                                                                                                </div>
                                                                                                                <div class="btn-checkbox mb-2 me-2">
                                                                                                                    <input
                                                                                                                        type="checkbox"
                                                                                                                        class="btn-check"
                                                                                                                        id="Traditional"
                                                                                                                        value="Traditional Perio services"
                                                                                                                        v-model="clinicStore.clinic.primary_services"
                                                                                                                    />
                                                                                                                    <label class="btn btn-outline-primary"
                                                                                                                           for="Traditional"
                                                                                                                    >Traditional Perio services</label
                                                                                                                    ><br/>
                                                                                                                </div>
                                                                                                                <div class="btn-checkbox mb-2 me-2">
                                                                                                                    <input
                                                                                                                        type="checkbox"
                                                                                                                        class="btn-check"
                                                                                                                        id="Other"
                                                                                                                        value="Other"
                                                                                                                        v-model="clinicStore.clinic.primary_services"
                                                                                                                    />
                                                                                                                    <label class="btn btn-outline-primary" for="Other"
                                                                                                                    >Other</label
                                                                                                                    ><br/>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                            <span
                                                                                                                class="d-block text-danger mt-1"
                                                                                                                v-text="clinicStore.errors.primary_services"
                                                                                                            ></span>
                                                                                                            <div
                                                                                                                id="showOther2"
                                                                                                                v-if="
                                                                                  clinicStore.clinic.primary_services &&
                                                                                  clinicStore.clinic.primary_services.includes('Other')
                                                                                "
                                                                                                            >
                                                                                                                <div class="row">
                                                                                                                    <div class="col-lg-8">
                                                                                                                        <div class="form-group">
                                                                                      <span
                                                                                          class="d-block text-danger mt-1"
                                                                                          v-text="clinicStore.errors.primary_services_other"
                                                                                      ></span>
                                                                                                                            <input
                                                                                                                                type="text"
                                                                                                                                class="form-control"
                                                                                                                                v-model="
                                                                                          clinicStore.clinic.primary_services_other
                                                                                        " :class="clinicStore.clinic.primary_services_other ? 'filled' : ''"
                                                                                                                            />
                                                                                                                            <label>Please describe primary
                                                                                                                                service.</label>
                                                                                                                        </div>
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>

                                                                                                <div class="col-12">
                                                                                                    <div class="form-group flex-column">
                                                                                                        <h5 class="d-lg-none">Practice Management System</h5>
                                                                                                        <label class="d-none d-lg-block"
                                                                                                        >Practice Management System</label
                                                                                                        >
                                                                                                        <div class="check-value">
                                                                                                            <p>{{ clinicStore.clinic.practice_management_system }}</p>
                                                                                                        </div>
                                                                                                        <div class="check-group">
                                                                                                            <div class="inline-check-group d-flex flex-md-wrap">
                                                                                                                <div class="btn-checkbox mb-2 me-2">
                                                                                                                    <input
                                                                                                                        type="radio"
                                                                                                                        class="btn-check"
                                                                                                                        id="Eaglesoft"
                                                                                                                        name="PracticeManagement"
                                                                                                                        value="Eaglesoft"
                                                                                                                        v-model="
                                                                                      clinicStore.clinic.practice_management_system
                                                                                    "
                                                                                                                    />
                                                                                                                    <label class="btn btn-outline-primary"
                                                                                                                           for="Eaglesoft"
                                                                                                                    >Eaglesoft</label
                                                                                                                    ><br/>
                                                                                                                </div>
                                                                                                                <div class="btn-checkbox mb-2 me-2">
                                                                                                                    <input
                                                                                                                        type="radio"
                                                                                                                        class="btn-check"
                                                                                                                        id="Dentrix"
                                                                                                                        name="PracticeManagement"
                                                                                                                        value="Dentrix"
                                                                                                                        v-model="
                                                                                      clinicStore.clinic.practice_management_system
                                                                                    "
                                                                                                                    />
                                                                                                                    <label class="btn btn-outline-primary" for="Dentrix"
                                                                                                                    >Dentrix</label
                                                                                                                    ><br/>
                                                                                                                </div>
                                                                                                                <div class="btn-checkbox mb-2 me-2">
                                                                                                                    <input
                                                                                                                        type="radio"
                                                                                                                        class="btn-check"
                                                                                                                        id="OpenDental"
                                                                                                                        name="PracticeManagement"
                                                                                                                        value="Open Dental"
                                                                                                                        v-model="
                                                                                      clinicStore.clinic.practice_management_system
                                                                                    "
                                                                                                                    />
                                                                                                                    <label class="btn btn-outline-primary"
                                                                                                                           for="OpenDental"
                                                                                                                    >Open Dental</label
                                                                                                                    ><br/>
                                                                                                                </div>
                                                                                                                <div class="btn-checkbox mb-2 me-2">
                                                                                                                    <input
                                                                                                                        type="radio"
                                                                                                                        class="btn-check"
                                                                                                                        id="None"
                                                                                                                        name="PracticeManagement"
                                                                                                                        value="None"
                                                                                                                        v-model="
                                                                                      clinicStore.clinic.practice_management_system
                                                                                    "
                                                                                                                    />
                                                                                                                    <label class="btn btn-outline-primary" for="None"
                                                                                                                    >None</label
                                                                                                                    ><br/>
                                                                                                                </div>
                                                                                                                <div class="btn-checkbox mb-2 me-2">
                                                                                                                    <input
                                                                                                                        type="radio"
                                                                                                                        class="btn-check"
                                                                                                                        id="Other2"
                                                                                                                        value="Other"
                                                                                                                        name="PracticeManagement"
                                                                                                                        v-model="
                                                                                      clinicStore.clinic.practice_management_system
                                                                                    "
                                                                                                                    />
                                                                                                                    <label class="btn btn-outline-primary" for="Other2"
                                                                                                                    >Other</label
                                                                                                                    ><br/>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                            <span
                                                                                                                class="d-block text-danger mt-1"
                                                                                                                v-text="clinicStore.errors.practice_management_system"
                                                                                                            ></span>
                                                                                                            <div
                                                                                                                id="showOther2"
                                                                                                                v-if="
                                                                                  clinicStore.clinic.practice_management_system == 'Other'
                                                                                "
                                                                                                            >
                                                                                                                <div class="row">
                                                                                                                    <div class="col-lg-8">
                                                                                                                        <div class="form-group">
                                                                                      <span
                                                                                          class="d-block text-danger mt-1"
                                                                                          v-text="clinicStore.errors.practice_different"
                                                                                      ></span>
                                                                                                                            <input
                                                                                                                                type="text"
                                                                                                                                class="form-control"
                                                                                                                                v-model="clinicStore.clinic.practice_different"
                                                                                                                                :class="clinicStore.clinic.practice_different ? 'filled' : ''"
                                                                                                                            />
                                                                                                                            <label>Please describe management
                                                                                                                                system.</label>
                                                                                                                        </div>
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>-->
                                                <div class="col-12">
                                                    <div class="form-group flex-column">
                                                        <h5 class="d-lg-none">Website Information Accuracy</h5>
                                                        <label class="d-none d-lg-block"
                                                        >Website Information Accuracy</label
                                                        >
                                                        <div class="check-value">
                                                            <p>{{
                                                                    clinicStore.clinic.website_information_detail == null ? 'Yes' : 'No'
                                                                }}</p>
                                                        </div>
                                                        <div class="check-group">
                                                            <div class="inline-check-group d-flex flex-md-wrap">
                                                                <div class="btn-checkbox mb-2 me-2 websiteRadioGroup">
                                                                    <input
                                                                        type="radio"
                                                                        class="btn-check"
                                                                        id="yesWIA"
                                                                        value="Yes"
                                                                        v-model="
                                      clinicStore.website_information_accurate
                                    "
                                                                    />
                                                                    <label class="btn btn-outline-primary" for="yesWIA"
                                                                    >Yes</label
                                                                    ><br/>
                                                                </div>
                                                                <div class="btn-checkbox mb-2 me-2">
                                                                    <input
                                                                        type="radio"
                                                                        class="btn-check"
                                                                        id="noWIA"
                                                                        value="No"
                                                                        v-model="
                                      clinicStore.website_information_accurate"
                                                                    />
                                                                    <label class="btn btn-outline-primary" for="noWIA"
                                                                    >No</label
                                                                    ><br/>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12"
                                                     v-show="clinicStore.website_information_accurate == 'No'">
                                                    <div class="form-group">
                            <span
                                class="d-block text-danger mt-1"
                                v-text="clinicStore.errors.website_information_detail"
                            ></span>
                                                        <input
                                                            type="text"
                                                            class="form-control"
                                                            v-model="clinicStore.clinic.website_information_detail"
                                                            :class="clinicStore.clinic.website_information_detail ? 'filled' : ''"
                                                        />
                                                        <label>Website Information Accuracy Description</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="col-12">
                                                    <div class="form-group">
                                                            <span
                                                                class="d-block text-danger mt-1"
                                                                v-text="clinicStore.errors.nurturing_display_name"
                                                            ></span>
                                                        <input
                                                            v-model="clinicStore.clinic.nurturing_display_name"
                                                            type="text"
                                                            class="form-control"
                                                            :class="clinicStore.clinic.nurturing_display_name ? 'filled' : ''"
                                                        />
                                                        <label>Nurturing Display Name</label>
                                                    </div>
                                                    <div class="form-group">
                                                            <span
                                                                class="d-block text-danger mt-1"
                                                                v-text="clinicStore.errors.scheduling_link"
                                                            ></span>
                                                        <input
                                                            v-model="clinicStore.clinic.scheduling_link"
                                                            type="text"
                                                            class="form-control"
                                                            :class="clinicStore.clinic.scheduling_link ? 'filled' : ''"
                                                        />
                                                        <label>Scheduling Link</label>
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="form-group">
                                                          <span
                                                              class="d-block text-danger mt-1"
                                                              v-text="clinicStore.errors.link1"
                                                          ></span>
                                                        <input
                                                            v-model="clinicStore.clinic.link1"
                                                            type="text"
                                                            class="form-control"
                                                            :class="clinicStore.clinic.link1 ? 'filled' : ''"
                                                        />
                                                        <label>Patient Testimonial Video URL1 for Nurture
                                                            Automation</label>
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="form-group">
                                                          <span
                                                              class="d-block text-danger mt-1"
                                                              v-text="clinicStore.errors.link2"
                                                          ></span>
                                                        <input
                                                            v-model="clinicStore.clinic.link2"
                                                            type="text"
                                                            class="form-control"
                                                            :class="clinicStore.clinic.link2 ? 'filled' : ''"
                                                        />
                                                        <label>Patient Testimonial Video URL2 for Nurture
                                                            Automation</label>
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="form-group">
                                                          <span
                                                              class="d-block text-danger mt-1"
                                                              v-text="clinicStore.errors.link3"
                                                          ></span>
                                                        <input
                                                            v-model="clinicStore.clinic.link3"
                                                            type="text"
                                                            class="form-control"
                                                            :class="clinicStore.clinic.link3 ? 'filled' : ''"
                                                        />
                                                        <label>Patient Testimonial Video URL3 for Nurture
                                                            Automation</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="tab-main-box"
                                :class="
                  office_details_editing ? 'tab-edit-active tab-main-active' : 'tab-disabled-active'
                "
                            >
                                <div class="tab-title-box d-flex align-items-center">
                                    <div class="tab-title" @click.prevent="office_details_editing = true">
                                        <h3>Office Details</h3>
                                    </div>
                                    <div
                                        class="edit-option d-none d-lg-flex ms-auto"
                                        :class="office_details_editing ? 'edit-active' : ''"
                                    >
                                        <a role="button" class="save-input manage-cancel"
                                           v-show="office_details_editing"
                                           @click="office_details_editing=false">Cancel</a>
                                        <a
                                            v-if="!office_details_editing"
                                            role="button"
                                            @click.prevent="office_details_editing = true"
                                            class="edit-input manage-edit"
                                        >Edit
                                            <svg
                                                width="18"
                                                height="18"
                                                viewBox="0 0 18 18"
                                                xmlns="http://www.w3.org/2000/svg"
                                            >
                                                <path
                                                    d="M16 17.25C16 16.848 15.644 16.5 15.25 16.5C12.689 16.5 3.311 16.5 0.75 16.5C0.356 16.5 0 16.848 0 17.25C0 17.652 0.356 18 0.75 18H15.25C15.644 18 16 17.652 16 17.25ZM8.597 13.852L17.721 4.727C17.892 4.556 18 4.304 18 4.043C18 3.814 17.917 3.577 17.72 3.381L14.605 0.277C14.42 0.0919999 14.176 0 13.933 0C13.69 0 13.447 0.0919999 13.261 0.277L4.118 9.38C3.549 11.143 2.563 14.203 2.492 14.461C2.472 14.536 2.463 14.611 2.463 14.685C2.463 15.146 2.812 15.533 3.228 15.533C3.739 15.533 4.219 15.344 8.597 13.852ZM5.327 10.51L7.464 12.647L4.296 13.693L5.327 10.51ZM6.282 9.344L13.933 1.728L16.268 4.055L8.631 11.693L6.282 9.344Z"
                                                />
                                            </svg>
                                        </a>
                                        <a
                                            v-else
                                            role="button"
                                            @click.prevent="office_details_editing_save()"
                                            class="save-input manage-save"
                                        >Save</a
                                        >
                                    </div>
                                </div>
                                <div class="tab-sub-box">
                                    <div class="d-lg-none d-flex align-items-center">
                                        <a role="button" class="back-btn" @click="resetActiveTabs">
                                            <svg
                                                width="20"
                                                height="14"
                                                viewBox="0 0 20 14"
                                                fill="none"
                                                xmlns="http://www.w3.org/2000/svg"
                                            >
                                                <path
                                                    fill-rule="evenodd"
                                                    clip-rule="evenodd"
                                                    d="M19.4411 6.37497H2.04859L7.25172 1.19185C7.49609 0.947473 7.49609 0.551848 7.25172 0.308098C7.00734 0.0637231 6.61172 0.0637231 6.36797 0.308098L0.180469 6.49497C-0.0601562 6.7356 -0.0601562 7.1381 0.180469 7.37872L6.36797 13.5662C6.61234 13.8106 7.00797 13.8106 7.25172 13.5662C7.49609 13.3218 7.49609 12.9262 7.25172 12.6825L2.04859 7.62497H19.4411C19.7861 7.62497 20.0661 7.34497 20.0661 6.99997C20.0661 6.65497 19.7861 6.37497 19.4411 6.37497Z"
                                                    fill="#514F5F"
                                                />
                                            </svg>
                                        </a>
                                        <a
                                            role="button"
                                            class="save-input btn px-4 btn-primary tab-save"
                                            @click.prevent="office_details_editing_save()"
                                        >Save</a
                                        >
                                    </div>
                                    <div class="d-lg-none mobile-tab-title">
                                        <h3>Office Details</h3>
                                    </div>
                                    <div class="row gx-3">
                                        <div class="col-12">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="form-group flex-column">
                                                        <label class="text-muted mb-2"
                                                        >Is the practice located in Canada?</label
                                                        >
                                                        <div class="check-value">
                                                            <p>
                                                                {{
                                                                    clinicStore.clinic.is_location_canada == "Yes"
                                                                        ? "Yes"
                                                                        : "No"
                                                                }}
                                                            </p>
                                                        </div>
                                                        <div class="check-group">
                                                            <div class="d-flex flex-wrap">
                                                                <div class="form-check me-3">
                                                                    <input
                                                                        class="form-check-input"
                                                                        type="radio"
                                                                        name="locationcanada"
                                                                        id="locationcanadayes"
                                                                        value="Yes"
                                                                        v-model="clinicStore.clinic.is_location_canada"
                                                                    />
                                                                    <label class="form-check-label"
                                                                           for="locationcanadayes">
                                                                        Yes </label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input
                                                                        class="form-check-input"
                                                                        type="radio"
                                                                        name="locationcanada"
                                                                        id="locationcanadano"
                                                                        value="No"
                                                                        v-model="clinicStore.clinic.is_location_canada"
                                                                    />
                                                                    <label class="form-check-label"
                                                                           for="locationcanadano">
                                                                        No </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <span
                                                            class="d-block text-danger mt-1"
                                                            v-text="clinicStore.errors.office_name"
                                                        ></span>
                                                        <input
                                                            type="text"
                                                            class="form-control"
                                                            v-model="clinicStore.clinic.office_name"
                                                            :class="clinicStore.clinic.office_name ? 'filled' : ''"
                                                        />
                                                        <label>Office Name</label>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <span
                                                            class="d-block text-danger mt-1"
                                                            v-text="clinicStore.errors.address"
                                                        ></span>
                                                        <input
                                                            type="text"
                                                            class="form-control"
                                                            v-model="clinicStore.clinic.address"
                                                            :class="clinicStore.clinic.address ? 'filled' : ''"
                                                        />
                                                        <label>Street Address</label>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-lg-4">
                                                    <div class="form-group">
                                                        <span
                                                            class="d-block text-danger mt-1"
                                                            v-text="clinicStore.errors.town"
                                                        ></span>
                                                        <input
                                                            type="text"
                                                            class="form-control"
                                                            v-model="clinicStore.clinic.town"
                                                            :class="clinicStore.clinic.town ? 'filled' : ''"
                                                        />
                                                        <label>City</label>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-lg-4">
                                                    <div class="form-group">
                                                        <span
                                                            class="d-block text-danger mt-1"
                                                            v-text="clinicStore.errors.state"
                                                        ></span>
                                                        <select
                                                            v-if="clinicStore.clinic.is_location_canada=='No'"
                                                            class="form-control form-select"
                                                            v-model="clinicStore.clinic.state"
                                                            :class="clinicStore.clinic.state ? 'filled' : ''"
                                                        >
                                                            <option disabled readonly></option>
                                                            <option value="Alabama">Alabama</option>
                                                            <option value="Alaska">Alaska</option>
                                                            <option value="Arizona">Arizona</option>
                                                            <option value="Arkansas">Arkansas</option>
                                                            <option value="California">California</option>
                                                            <option value="Colorado">Colorado</option>
                                                            <option value="Connecticut">Connecticut</option>
                                                            <option value="Delaware">Delaware</option>
                                                            <option value="District Of Columbia">
                                                                District Of Columbia
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
                                                            <option value="North Carolina">North Carolina</option>
                                                            <option value="North Dakota">North Dakota</option>
                                                            <option value="Ohio">Ohio</option>
                                                            <option value="Oklahoma">Oklahoma</option>
                                                            <option value="Oregon">Oregon</option>
                                                            <option value="Pennsylvania">Pennsylvania</option>
                                                            <option value="Rhode Island">Rhode Island</option>
                                                            <option value="South Carolina">South Carolina</option>
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
                                                        <select
                                                            v-else
                                                            class="form-control form-select"
                                                            v-model="clinicStore.clinic.state"
                                                            :class="clinicStore.clinic.state ? 'filled' : ''"
                                                        >
                                                            <option disabled readonly></option>
                                                            <option value="Alberta">Alberta</option>
                                                            <option value="British Columbia">British Columbia</option>
                                                            <option value="Manitoba">Manitoba</option>
                                                            <option value="New Brunswick">New Brunswick</option>
                                                            <option value="Newfoundland and Labrador">Newfoundland and
                                                                Labrador
                                                            </option>
                                                            <option value="Northwest Territories">Northwest
                                                                Territories
                                                            </option>
                                                            <option value="Nova Scotia">Nova Scotia</option>
                                                            <option value="Nunavut">Nunavut</option>
                                                            <option value="Ontario">Ontario</option>
                                                            <option value="Prince Edward Island">Prince Edward Island
                                                            </option>
                                                            <option value="Quebec">Quebec</option>
                                                            <option value="Saskatchewan">Saskatchewan</option>
                                                            <option value="Yukon Territory">Yukon Territory</option>
                                                        </select>
                                                        <label>{{
                                                                clinicStore.clinic.is_location_canada == 'Yes' ? 'Province' : 'State'
                                                            }}</label>
                                                    </div>
                                                    <!--/.form-group-->
                                                </div>
                                                <div class="col-12 col-lg-4">
                                                    <div class="form-group">
                                                        <span
                                                            class="d-block text-danger mt-1"
                                                            v-text="clinicStore.errors.zip_code"
                                                        ></span>
                                                        <input
                                                            type="text"
                                                            class="form-control"
                                                            v-model="clinicStore.clinic.zip_code"
                                                            :class="clinicStore.clinic.zip_code ? 'filled' : ''"
                                                        />
                                                        <label>{{
                                                                clinicStore.clinic.is_location_canada == 'Yes' ? 'Postal Code' : 'Zip Code'
                                                            }}</label>
                                                    </div>
                                                </div>
                                                <!--                                                <div class="col-12">
                                                                                                    <div class="form-group">
                                                                                                        <span
                                                                                                            class="d-block text-danger mt-1"
                                                                                                            v-text="clinicStore.errors.office_hours"
                                                                                                        ></span>
                                                                                                        <textarea
                                                                                                            class="form-control"
                                                                                                            rows="4"
                                                                                                            v-model="clinicStore.clinic.office_hours"
                                                                                                            :class="clinicStore.clinic.office_hours ? 'filled' : ''"
                                                                                                            @input="adjustHeight"
                                                                                                            ref="office_hours"
                                                                                                            style="overflow-y: hidden; resize: none;"
                                                                                                        ></textarea>
                                                                                                        <label>Office Hours</label>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="col-12">
                                                                                                    <div class="form-group">
                                                                                                        <span
                                                                                                            class="d-block text-danger mt-1"
                                                                                                            v-text="clinicStore.errors.holidays"
                                                                                                        ></span>
                                                                                                        <textarea
                                                                                                            class="form-control small-textarea"
                                                                                                            v-model="clinicStore.clinic.holidays"
                                                                                                            :class="clinicStore.clinic.holidays ? 'filled' : ''"
                                                                                                            @input="adjustHeight"
                                                                                                                ref="holidays"
                                                                                                                style="overflow-y: hidden; resize: none;"
                                                                                                        ></textarea>
                                                                                                        <label>Office Holidays</label>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="col-12">
                                                                                                    <div class="form-group">
                                                                                                        <span
                                                                                                            class="d-block text-danger mt-1"
                                                                                                            v-text="clinicStore.errors.locations"
                                                                                                        ></span>
                                                                                                        <div>
                                                                                                            <div class="check-group location-add-btn-box">
                                                                                                                <button
                                                                                                                    type="button"
                                                                                                                    class="btn btn-primary location-add-btn"
                                                                                                                    @click="addLocation"
                                                                                                                >
                                                                                                                    + Add Location
                                                                                                                </button>
                                                                                                            </div>
                                                                                                            <div
                                                                                                                v-for="(location, index) in clinicStore.clinic.locations"
                                                                                                                class="location-div mt-3"
                                                                                                            >
                                                                                                                <div class="row">
                                                                                                                    <div class="col-lg-12">
                                                                                                                        <div class="form-group">
                                                                                                                            <input
                                                                                                                                type="text"
                                                                                                                                class="form-control"
                                                                                                                                v-model="location.office_name"
                                                                                                                                :class="location.office_name ? 'filled' : ''"
                                                                                                                                required
                                                                                                                            />
                                                                                                                            <label>Office Name</label>
                                                                                                                        </div>
                                                                                                                    </div>
                                                                                                                    <div class="col-lg-12">
                                                                                                                        <div class="form-group">
                                                                                                                            <input
                                                                                                                                type="text"
                                                                                                                                class="form-control"
                                                                                                                                v-model="location.street_address"
                                                                                                                                :class="location.street_address ? 'filled' : ''"
                                                                                                                                required
                                                                                                                            />
                                                                                                                            <label>Street Address</label>
                                                                                                                        </div>
                                                                                                                    </div>
                                                                                                                    <div class="col-lg-4">
                                                                                                                        <div class="form-group">
                                                                                                                            <input
                                                                                                                                type="text"
                                                                                                                                class="form-control"
                                                                                                                                v-model="location.city"
                                                                                                                                :class="location.city ? 'filled' : ''"
                                                                                                                                required
                                                                                                                            />
                                                                                                                            <label>City</label>
                                                                                                                        </div>
                                                                                                                    </div>
                                                                                                                    <div class="col-lg-4">
                                                                                                                        <div class="form-group">
                                                                                                                            <select
                                                                                                                                v-if="clinicStore.clinic.is_location_canada == 'No'"
                                                                                                                                class="form-control form-select"
                                                                                                                                v-model="location.state"
                                                                                                                                :class="location.state ? 'filled' : ''"
                                                                                                                                required
                                                                                                                            >
                                                                                                                                <option disabled readonly></option>
                                                                                                                                <option value="Alabama">Alabama</option>
                                                                                                                                <option value="Alaska">Alaska</option>
                                                                                                                                <option value="Arizona">Arizona</option>
                                                                                                                                <option value="Arkansas">Arkansas
                                                                                                                                </option>
                                                                                                                                <option value="California">California
                                                                                                                                </option>
                                                                                                                                <option value="Colorado">Colorado
                                                                                                                                </option>
                                                                                                                                <option value="Connecticut">
                                                                                                                                    Connecticut
                                                                                                                                </option>
                                                                                                                                <option value="Delaware">Delaware
                                                                                                                                </option>
                                                                                                                                <option value="District Of Columbia">
                                                                                                                                    District Of Columbia
                                                                                                                                </option>
                                                                                                                                <option value="Florida">Florida</option>
                                                                                                                                <option value="Georgia">Georgia</option>
                                                                                                                                <option value="Hawaii">Hawaii</option>
                                                                                                                                <option value="Idaho">Idaho</option>
                                                                                                                                <option value="Illinois">Illinois
                                                                                                                                </option>
                                                                                                                                <option value="Indiana">Indiana</option>
                                                                                                                                <option value="Iowa">Iowa</option>
                                                                                                                                <option value="Kansas">Kansas</option>
                                                                                                                                <option value="Kentucky">Kentucky
                                                                                                                                </option>
                                                                                                                                <option value="Louisiana">Louisiana
                                                                                                                                </option>
                                                                                                                                <option value="Maine">Maine</option>
                                                                                                                                <option value="Maryland">Maryland
                                                                                                                                </option>
                                                                                                                                <option value="Massachusetts">
                                                                                                                                    Massachusetts
                                                                                                                                </option>
                                                                                                                                <option value="Michigan">Michigan
                                                                                                                                </option>
                                                                                                                                <option value="Minnesota">Minnesota
                                                                                                                                </option>
                                                                                                                                <option value="Mississippi">
                                                                                                                                    Mississippi
                                                                                                                                </option>
                                                                                                                                <option value="Missouri">Missouri
                                                                                                                                </option>
                                                                                                                                <option value="Montana">Montana</option>
                                                                                                                                <option value="Nebraska">Nebraska
                                                                                                                                </option>
                                                                                                                                <option value="Nevada">Nevada</option>
                                                                                                                                <option value="New Hampshire">
                                                                                                                                    New Hampshire
                                                                                                                                </option>
                                                                                                                                <option value="New Jersey">New Jersey
                                                                                                                                </option>
                                                                                                                                <option value="New Mexico">New Mexico
                                                                                                                                </option>
                                                                                                                                <option value="New York">New York
                                                                                                                                </option>
                                                                                                                                <option value="North Carolina">
                                                                                                                                    North Carolina
                                                                                                                                </option>
                                                                                                                                <option value="North Dakota">North
                                                                                                                                    Dakota
                                                                                                                                </option>
                                                                                                                                <option value="Ohio">Ohio</option>
                                                                                                                                <option value="Oklahoma">Oklahoma
                                                                                                                                </option>
                                                                                                                                <option value="Oregon">Oregon</option>
                                                                                                                                <option value="Pennsylvania">
                                                                                                                                    Pennsylvania
                                                                                                                                </option>
                                                                                                                                <option value="Rhode Island">Rhode
                                                                                                                                    Island
                                                                                                                                </option>
                                                                                                                                <option value="South Carolina">
                                                                                                                                    South Carolina
                                                                                                                                </option>
                                                                                                                                <option value="South Dakota">South
                                                                                                                                    Dakota
                                                                                                                                </option>
                                                                                                                                <option value="Tennessee">Tennessee
                                                                                                                                </option>
                                                                                                                                <option value="Texas">Texas</option>
                                                                                                                                <option value="Utah">Utah</option>
                                                                                                                                <option value="Vermont">Vermont</option>
                                                                                                                                <option value="Virginia">Virginia
                                                                                                                                </option>
                                                                                                                                <option value="Washington">Washington
                                                                                                                                </option>
                                                                                                                                <option value="West Virginia">
                                                                                                                                    West Virginia
                                                                                                                                </option>
                                                                                                                                <option value="Wisconsin">Wisconsin
                                                                                                                                </option>
                                                                                                                                <option value="Wyoming">Wyoming</option>
                                                                                                                            </select>
                                                                                                                            <select
                                                                                                                                v-else
                                                                                                                                class="form-control form-select"
                                                                                                                                v-model="location.state"
                                                                                                                                :class="location.state ? 'filled' : ''"
                                                                                                                                required
                                                                                                                            >
                                                                                                                                <option disabled readonly></option>
                                                                                                                                <option value="Alberta">Alberta</option>
                                                                                                                                <option value="British Columbia">British Columbia</option>
                                                                                                                                <option value="Manitoba">Manitoba</option>
                                                                                                                                <option value="New Brunswick">New Brunswick</option>
                                                                                                                                <option value="Newfoundland and Labrador">Newfoundland and Labrador</option>
                                                                                                                                <option value="Northwest Territories">Northwest Territories</option>
                                                                                                                                <option value="Nova Scotia">Nova Scotia</option>
                                                                                                                                <option value="Nunavut">Nunavut</option>
                                                                                                                                <option value="Ontario">Ontario</option>
                                                                                                                                <option value="Prince Edward Island">Prince Edward Island</option>
                                                                                                                                <option value="Quebec">Quebec</option>
                                                                                                                                <option value="Saskatchewan">Saskatchewan</option>
                                                                                                                                <option value="Yukon Territory">Yukon Territory</option>
                                                                                                                            </select>
                                                                                                                            <label>{{ clinicStore.clinic.is_location_canada == 'Yes' ? 'Province' : 'State'  }}</label>
                                                                                                                        </div>
                                                                                                                    </div>
                                                                                                                    <div class="col-lg-4">
                                                                                                                        <div class="form-group">
                                                                                                                            <input
                                                                                                                                type="text"
                                                                                                                                class="form-control"
                                                                                                                                maxlength="5"
                                                                                                                                minlength="5"
                                                                                                                                v-model="location.zip_code"
                                                                                                                                :class="location.zip_code ? 'filled' : ''"
                                                                                                                                required
                                                                                                                            />
                                                                                                                            <label>{{ clinicStore.clinic.is_location_canada == 'Yes' ? 'Postal Code' : 'Zip Code'  }}</label>
                                                                                                                        </div>
                                                                                                                    </div>
                                                                                                                    <div class="col-lg-12">
                                                                                                                        <div class="form-group mb-0">
                                                                                                                            <div>
                                                                                                                                <input
                                                                                                                                    type="button"
                                                                                                                                    class="btn btn-primary"
                                                                                                                                    value="Remove"
                                                                                                                                    @click="deleteLocation(index)"
                                                                                                                                />
                                                                                                                            </div>
                                                                                                                        </div>
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                        <label>Multiple Office Locations</label>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="col-12">
                                                                                                    <div class="form-group">
                                                                                                        <div>
                                                                              <span
                                                                                  class="d-block text-danger mt-1"
                                                                                  v-text="clinicStore.errors.multiple_doctors"
                                                                              ></span>
                                                                                                            <div class="check-group">
                                                                                                                <p class="text-muted">
                                                                                                                    Should the marketing promote multiple doctors?
                                                                                                                </p>
                                                                                                            </div>
                                                                                                            <div class="check-group">
                                                                                                                <div class="inline-check-group d-flex flex-md-wrap">
                                                                                                                    <div
                                                                                                                        class="btn-checkbox mb-2 me-2 websiteRadioGroup">
                                                                                                                        <input
                                                                                                                            type="radio"
                                                                                                                            class="btn-check"
                                                                                                                            id="yesMD"
                                                                                                                            value="Yes"
                                                                                                                            v-model="
                                                                                        clinicStore.multiple_doctors
                                                                                      "
                                                                                                                        />
                                                                                                                        <label class="btn btn-outline-primary"
                                                                                                                               for="yesMD"
                                                                                                                        >Yes</label
                                                                                                                        ><br/>
                                                                                                                    </div>
                                                                                                                    <div class="btn-checkbox mb-2 me-2">
                                                                                                                        <input
                                                                                                                            type="radio"
                                                                                                                            class="btn-check"
                                                                                                                            id="noMD"
                                                                                                                            value="No"
                                                                                                                            v-model="
                                                                                        clinicStore.multiple_doctors"
                                                                                                                        />
                                                                                                                        <label class="btn btn-outline-primary"
                                                                                                                               for="noMD"
                                                                                                                        >No</label
                                                                                                                        ><br/>
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                            <textarea
                                                                                                                class="form-control"
                                                                                                                rows="2" v-show="clinicStore.multiple_doctors=='Yes'"
                                                                                                                v-model="clinicStore.clinic.multiple_doctors"
                                                                                                                :class="clinicStore.clinic.multiple_doctors ? 'filled' : ''"
                                                                                                                @input="adjustHeight"
                                                                                                                ref="multiple_doctors"
                                                                                                                style="overflow-y: hidden; resize: none;"
                                                                                                            ></textarea>
                                                                                                        </div>
                                                                                                        <div class="check-value">
                                                                                                            <p>{{
                                                                                                                    clinicStore.clinic.multiple_doctors == null ? 'No' : 'Yes'
                                                                                                                }}</p>
                                                                                                        </div>
                                                                                                        <label>Multiple Doctors</label>
                                                                                                    </div>
                                                                                                </div>-->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="tab-main-box"
                                :class="staff_access_editing ? 'tab-edit-active tab-main-active' : 'tab-disabled-active'"
                            >
                                <div class="tab-title-box d-flex align-items-center">
                                    <div class="tab-title" @click.prevent="staff_access_editing = true">
                                        <h3>Staff Member Access</h3>
                                    </div>
                                    <div
                                        class="edit-option d-none d-lg-flex ms-auto"
                                        :class="staff_access_editing ? 'edit-active' : ''"
                                    >
                                        <a
                                            v-if="!staff_access_editing"
                                            role="button"
                                            @click.prevent="staff_access_editing = true"
                                            class="edit-input manage-edit"
                                        >Edit
                                            <svg
                                                width="18"
                                                height="18"
                                                viewBox="0 0 18 18"
                                                xmlns="http://www.w3.org/2000/svg"
                                            >
                                                <path
                                                    d="M16 17.25C16 16.848 15.644 16.5 15.25 16.5C12.689 16.5 3.311 16.5 0.75 16.5C0.356 16.5 0 16.848 0 17.25C0 17.652 0.356 18 0.75 18H15.25C15.644 18 16 17.652 16 17.25ZM8.597 13.852L17.721 4.727C17.892 4.556 18 4.304 18 4.043C18 3.814 17.917 3.577 17.72 3.381L14.605 0.277C14.42 0.0919999 14.176 0 13.933 0C13.69 0 13.447 0.0919999 13.261 0.277L4.118 9.38C3.549 11.143 2.563 14.203 2.492 14.461C2.472 14.536 2.463 14.611 2.463 14.685C2.463 15.146 2.812 15.533 3.228 15.533C3.739 15.533 4.219 15.344 8.597 13.852ZM5.327 10.51L7.464 12.647L4.296 13.693L5.327 10.51ZM6.282 9.344L13.933 1.728L16.268 4.055L8.631 11.693L6.282 9.344Z"
                                                />
                                            </svg>
                                        </a>
                                        <div v-else class="d-flex align-items-center gap-1">
                                            <a role="button" class="save-input manage-cancel"
                                               v-show="staff_access_editing"
                                               @click="staff_access_editing=false">Cancel</a>
                                            <a
                                                role="button"
                                                class="save-input manage-save me-2"
                                                @click="addStaffEmail()"
                                            >+ Add</a
                                            >
                                            <a
                                                role="button"
                                                @click.prevent="staff_access_editing_save()"
                                                class="save-input manage-save"
                                            >Save</a
                                            >
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-sub-box">
                                    <div class="d-lg-none d-flex align-items-center">
                                        <a role="button" class="back-btn" @click="resetActiveTabs">
                                            <svg
                                                width="20"
                                                height="14"
                                                viewBox="0 0 20 14"
                                                fill="none"
                                                xmlns="http://www.w3.org/2000/svg"
                                            >
                                                <path
                                                    fill-rule="evenodd"
                                                    clip-rule="evenodd"
                                                    d="M19.4411 6.37497H2.04859L7.25172 1.19185C7.49609 0.947473 7.49609 0.551848 7.25172 0.308098C7.00734 0.0637231 6.61172 0.0637231 6.36797 0.308098L0.180469 6.49497C-0.0601562 6.7356 -0.0601562 7.1381 0.180469 7.37872L6.36797 13.5662C6.61234 13.8106 7.00797 13.8106 7.25172 13.5662C7.49609 13.3218 7.49609 12.9262 7.25172 12.6825L2.04859 7.62497H19.4411C19.7861 7.62497 20.0661 7.34497 20.0661 6.99997C20.0661 6.65497 19.7861 6.37497 19.4411 6.37497Z"
                                                    fill="#514F5F"
                                                />
                                            </svg>
                                        </a>
                                        <a role="button" class="save-input btn px-4 btn-primary tab-save">Save</a>
                                    </div>
                                    <div class="d-lg-none mobile-tab-title">
                                        <h3>Staff Member Access</h3>
                                    </div>
                                    <div class="row gx-3">
                                        <div class="col-lg-12">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <div>
                                                            <div class="check-group">
                                                                <p class="text-muted">
                                                                    To invite staff members to join your account, add
                                                                    their
                                                                    emails.
                                                                </p>
                                                            </div>
                                                            <div
                                                                v-if="clinicStore.staff_error"
                                                                class="alert alert-danger alert-dismissible fade show"
                                                                role="alert"
                                                            >
                                                                There was an error saving staff emails. Please try
                                                                again.
                                                                <button
                                                                    type="button"
                                                                    class="btn-close"
                                                                    data-bs-dismiss="alert"
                                                                    aria-label="Close"
                                                                    @click="clinicStore.staff_error = false"
                                                                ></button>
                                                            </div>
                                                            <div
                                                                v-if="clinicStore.resendMessage"
                                                                class="alert alert-success alert-dismissible fade show"
                                                                role="alert"
                                                            >
                                                                {{ clinicStore.resendMessage }}
                                                                <button
                                                                    type="button"
                                                                    class="btn-close"
                                                                    data-bs-dismiss="alert"
                                                                    aria-label="Close"
                                                                    @click="clinicStore.staff_error = false"
                                                                ></button>
                                                            </div>
                                                            <!-- <div class="form-group mb-0 staff-emails" >
                                                                <input type="text" class="form-control my-2" v-for="email in clinicStore.clinic.staff_email" :value="(email.email) ? email.email : '' + ' ' +   (email.user_email_verified_at ? '( Active )' : '( Pending )')">
                                                                <label class="d-lg-none" >Staff Emails</label>
                                                            </div> -->
                                                            <div
                                                                v-for="(email, index) in clinicStore.staff_emails"
                                                                class="location-div my-2 border p-3 bg-light-gray rounded-3"
                                                            >
                                                                <div class="row align-items-center">
                                                                    <div class="col-md-8">
                                                                        <div
                                                                            class="form-group mb-0 d-flex flex-column-reverse">
                                                                            <input
                                                                                type="email"
                                                                                class="form-control"
                                                                                placeholder="Enter an email address."
                                                                                required
                                                                                v-model="clinicStore.staff_emails[index]"
                                                                                :class="clinicStore.staff_emails[index] ? 'filled' : ''"
                                                                            />
                                                                            <label>Email </label>
                                                                            <span
                                                                                class="d-block text-danger mt-1"
                                                                                v-text="clinicStore.errors.staff_emails"
                                                                            ></span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4 mt-2 mt-md-0">
                                                                        <div class="form-group mb-0">
                                                                            <div style="text-align: right">
                                                                                <a role="button"
                                                                                   @click="deleteEmail(index)">
                                                                                    <svg width="32px" height="32px"
                                                                                         viewBox="0 0 24 24" fill="none"
                                                                                         xmlns="http://www.w3.org/2000/svg">
                                                                                        <g id="SVGRepo_bgCarrier"
                                                                                           stroke-width="0"></g>
                                                                                        <g id="SVGRepo_tracerCarrier"
                                                                                           stroke-linecap="round"
                                                                                           stroke-linejoin="round"></g>
                                                                                        <g id="SVGRepo_iconCarrier">
                                                                                            <circle cx="12" cy="12"
                                                                                                    r="10"
                                                                                                    stroke="#1C274C"
                                                                                                    stroke-width="1.5"></circle>
                                                                                            <path
                                                                                                d="M14.5 9.50002L9.5 14.5M9.49998 9.5L14.5 14.5"
                                                                                                stroke="#1C274C"
                                                                                                stroke-width="1.5"
                                                                                                stroke-linecap="round"></path>
                                                                                        </g>
                                                                                    </svg>
                                                                                </a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div
                                                                v-for="(email, index) in clinicStore.clinic.staff_email"
                                                                class="location-div my-2 border p-3 bg-light-gray rounded-3"
                                                            >
                                                                <div class="row align-items-center">
                                                                    <div class="col-md-8">
                                                                        <div
                                                                            class="form-group mb-0 d-flex flex-column-reverseform-group mb-0 d-flex flex-column-reverse">
                                                                            <label
                                                                                class=""
                                                                            >{{ email.email }}</label>
                                                                            <label
                                                                            >Email
                                                                                <span
                                                                                    class="badge"
                                                                                    :class="
                                            !email.password_null && !email.name_null
                                              ? 'bg-success'
                                              : 'bg-danger'
                                          "
                                                                                >{{
                                                                                        !email.password_null && !email.name_null
                                                                                            ? "Active"
                                                                                            : "Pending"
                                                                                    }}</span
                                                                                ></label
                                                                            >
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-3 mt-2 mt-md-0">
                                                                        <a v-if="email.password_null && email.name_null"
                                                                           role="button"
                                                                           @click="resendVerification(email.email)"
                                                                           class="save-input btn btn-primary">Re-Invite</a>
                                                                    </div>
                                                                    <div class="col-md-1 mt-2 mt-md-0" v-if="parent_id">
                                                                        <a
                                                                            role="button"
                                                                            title="Delete"
                                                                            class="save-input"
                                                                            @click="setDeleteStaffMember(email.email)"
                                                                            data-bs-toggle="modal"
                                                                            data-bs-target="#DeleteStaffEmailConfirm"
                                                                        >
                                                                            <i class="fa fa-trash icon-grey"></i>
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                        <label class="d-none d-lg-block">Staff Emails</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                            </div>

                            <div class="tab-main-box" v-if="clinicStore.clinic.callrail_installation_script"
                                 :class="callrail_installtion_editing ? 'tab-edit-active tab-main-active' : 'tab-disabled-active'">
                                <div class="tab-title-box d-flex align-items-center">
                                    <div class="tab-title" @click.prevent="callrail_installtion_editing = true">
                                        <h3>Installation Instructions</h3>
                                    </div>
                                </div>
                                <div class="tab-sub-box">
                                    <div class="d-lg-none d-flex align-items-center justify-content-between">
                                        <a role="button" class="back-btn" @click="resetActiveTabs">
                                            <svg
                                                width="20"
                                                height="14"
                                                viewBox="0 0 20 14"
                                                fill="none"
                                                xmlns="http://www.w3.org/2000/svg"
                                            >
                                                <path
                                                    fill-rule="evenodd"
                                                    clip-rule="evenodd"
                                                    d="M19.4411 6.37497H2.04859L7.25172 1.19185C7.49609 0.947473 7.49609 0.551848 7.25172 0.308098C7.00734 0.0637231 6.61172 0.0637231 6.36797 0.308098L0.180469 6.49497C-0.0601562 6.7356 -0.0601562 7.1381 0.180469 7.37872L6.36797 13.5662C6.61234 13.8106 7.00797 13.8106 7.25172 13.5662C7.49609 13.3218 7.49609 12.9262 7.25172 12.6825L2.04859 7.62497H19.4411C19.7861 7.62497 20.0661 7.34497 20.0661 6.99997C20.0661 6.65497 19.7861 6.37497 19.4411 6.37497Z"
                                                    fill="#514F5F"
                                                />
                                            </svg>
                                        </a>
                                    </div>
                                    <div class="d-lg-none mobile-tab-title">
                                        <h3>JavaScript Snippet</h3>
                                    </div>
                                    <div class="row gx-3">
                                        <div class="col-md-8 col-lg-12">
                                            <div>
                                                <div>
                                                    <div class="form-group flex-column">
                                                        <label class="mb-2">JavaScript Snippet</label>
                                                        <p>Insert this code snippet immediately before the body tag on
                                                            every page of your website. You can install this snippet on
                                                            both HTTP and HTTPS pages.</p>
                                                        <input
                                                            type="text"
                                                            class="form-control"
                                                            v-model="clinicStore.clinic.callrail_installation_script"
                                                            style="border: 1px solid #ccc; color: #333; padding: 10px;"
                                                        />
                                                    </div>
                                                    <div>
                                                        <button @click="copyToClipboard" class="btn btn-link mt-2">Copy
                                                            to Clipboard
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--                        <div
                                                    id="AILearning"
                                                    v-if="currentPage==='marketing'"
                                                >
                                                    <div class="tab-main-title d-lg-none">
                                                        <h5>Media Content</h5>
                                                    </div>
                                                    <div
                                                        class="tab-main-box"
                                                        :class="media_content_editing ? 'tab-edit-active tab-main-active' : 'tab-disabled-active'"
                                                    >
                                                        <div class="tab-title-box d-flex align-items-center">
                                                            <div class="tab-title" @click.prevent="media_content_editing = true">
                                                                <h3>Media Content</h3>
                                                            </div>
                                                            <div
                                                                class="edit-option d-none d-lg-flex ms-auto"
                                                                :class="media_content_editing ? 'edit-active' : ''"
                                                            >
                                                                <a role="button" class="save-input manage-cancel" v-show="media_content_editing"
                                                                   @click="media_content_editing=false">Cancel</a>
                                                                <a
                                                                    v-if="!media_content_editing"
                                                                    role="button"
                                                                    @click.prevent="media_content_editing = true"
                                                                    class="edit-input manage-edit"
                                                                >Edit
                                                                    <svg
                                                                        width="18"
                                                                        height="18"
                                                                        viewBox="0 0 18 18"
                                                                        xmlns="http://www.w3.org/2000/svg"
                                                                    >
                                                                        <path
                                                                            d="M16 17.25C16 16.848 15.644 16.5 15.25 16.5C12.689 16.5 3.311 16.5 0.75 16.5C0.356 16.5 0 16.848 0 17.25C0 17.652 0.356 18 0.75 18H15.25C15.644 18 16 17.652 16 17.25ZM8.597 13.852L17.721 4.727C17.892 4.556 18 4.304 18 4.043C18 3.814 17.917 3.577 17.72 3.381L14.605 0.277C14.42 0.0919999 14.176 0 13.933 0C13.69 0 13.447 0.0919999 13.261 0.277L4.118 9.38C3.549 11.143 2.563 14.203 2.492 14.461C2.472 14.536 2.463 14.611 2.463 14.685C2.463 15.146 2.812 15.533 3.228 15.533C3.739 15.533 4.219 15.344 8.597 13.852ZM5.327 10.51L7.464 12.647L4.296 13.693L5.327 10.51ZM6.282 9.344L13.933 1.728L16.268 4.055L8.631 11.693L6.282 9.344Z"
                                                                        />
                                                                    </svg>
                                                                </a>
                                                                <a
                                                                    v-else
                                                                    role="button"
                                                                    @click.prevent="media_content_editing_save()"
                                                                    class="save-input manage-save"
                                                                >Save</a
                                                                >
                                                            </div>
                                                        </div>
                                                        <div class="tab-sub-box">
                                                            <div class="d-lg-none d-flex align-items-center">
                                                                <a role="button" class="back-btn" @click="resetActiveTabs">
                                                                    <svg
                                                                        width="20"
                                                                        height="14"
                                                                        viewBox="0 0 20 14"
                                                                        fill="none"
                                                                        xmlns="http://www.w3.org/2000/svg"
                                                                    >
                                                                        <path
                                                                            fill-rule="evenodd"
                                                                            clip-rule="evenodd"
                                                                            d="M19.4411 6.37497H2.04859L7.25172 1.19185C7.49609 0.947473 7.49609 0.551848 7.25172 0.308098C7.00734 0.0637231 6.61172 0.0637231 6.36797 0.308098L0.180469 6.49497C-0.0601562 6.7356 -0.0601562 7.1381 0.180469 7.37872L6.36797 13.5662C6.61234 13.8106 7.00797 13.8106 7.25172 13.5662C7.49609 13.3218 7.49609 12.9262 7.25172 12.6825L2.04859 7.62497H19.4411C19.7861 7.62497 20.0661 7.34497 20.0661 6.99997C20.0661 6.65497 19.7861 6.37497 19.4411 6.37497Z"
                                                                            fill="#514F5F"
                                                                        />
                                                                    </svg>
                                                                </a>
                                                                <a
                                                                    role="button"
                                                                    class="save-input btn px-4 btn-primary tab-save"
                                                                    @click.prevent="media_content_editing_save()"
                                                                >Save</a
                                                                >
                                                            </div>
                                                            <div class="d-lg-none mobile-tab-title">
                                                                <h3>Media Content</h3>
                                                            </div>
                                                            <div class="row gx-3">
                                                                <div class="col-12">
                                                                    <div class="row">
                                                                        <div class="col-12">
                                                                            <div class="form-group">
                                                                                <input
                                                                                    type="text"
                                                                                    class="form-control"
                                                                                    v-model="clinicStore.clinic.ai_website_url"
                                                                                    :class="clinicStore.clinic.ai_website_url ? 'filled' : ''"
                                                                                />
                                                                                <label>Website/Microsite URL</label>
                                                                            </div>
                                                                        </div>
                                                                        &lt;!&ndash; <div class="col-12">
                                                                            <div class="form-group flex-column">
                                                                                <h5 class="d-lg-none">Website Information Accuracy</h5>
                                                                                <label class="d-none d-lg-block">Website Information Accuracy</label>
                                                                                <div class="check-value">
                                                                                    <p>Yes, the information on the website is accurate.</p>
                                                                                </div>
                                                                                <div class="check-group">
                                                                                    <p class="text-muted mb-2">Is all information on your current website accurate? For example, bios, team members, office hours, phone number, address, technology, treatments offered?</p>
                                                                                    <div class="d-flex flex-wrap">
                                                                                        <div class="form-check me-3">
                                                                                            <input class="form-check-input" type="radio" name="accurate" id="Yes1" value="Yes" v-model="clinicStore.website_information_accurate">
                                                                                            <label class="form-check-label" for="Yes1">
                                                                                                Yes
                                                                                            </label>
                                                                                        </div>
                                                                                        <div class="form-check">
                                                                                            <input class="form-check-input" type="radio" name="accurate" id="No1" value="No" v-model="clinicStore.clinic.website_information_accurate">
                                                                                            <label class="form-check-label" for="No1">
                                                                                                No
                                                                                            </label>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div> &ndash;&gt;
                                                                        <div class="col-12">
                                                                            <div class="form-group flex-column">
                                                                                <label class="text-muted mb-2"
                                                                                >Do you have professional photography of the doctor and
                                                                                    office that can be used for marketing?</label
                                                                                >
                                                                                <div class="check-value">
                                                                                    <p>
                                                                                        {{
                                                                                            clinicStore.clinic.professional_photography == "Yes"
                                                                                                ? "Yes"
                                                                                                : "No"
                                                                                        }}
                                                                                    </p>
                                                                                </div>
                                                                                <div class="check-group">
                                                                                    <div class="d-flex flex-wrap">
                                                                                        <div class="form-check me-3">
                                                                                            <input
                                                                                                class="form-check-input"
                                                                                                type="radio"
                                                                                                name="professionalPhotography"
                                                                                                id="Yes2"
                                                                                                value="Yes"
                                                                                                v-model="clinicStore.clinic.professional_photography"
                                                                                            />
                                                                                            <label class="form-check-label" for="Yes2">
                                                                                                Yes </label>
                                                                                        </div>
                                                                                        <div class="form-check">
                                                                                            <input
                                                                                                class="form-check-input"
                                                                                                type="radio"
                                                                                                name="professionalPhotography"
                                                                                                id="No2"
                                                                                                value="No"
                                                                                                v-model="clinicStore.clinic.professional_photography"
                                                                                            />
                                                                                            <label class="form-check-label" for="No2">
                                                                                                No </label>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-12">
                                                                            <div class="form-group flex-column">
                                                                                <label class="text-muted mb-2"
                                                                                >Do you have professional video of the doctor that can be
                                                                                    used for marketing?</label
                                                                                >
                                                                                <div class="check-value">
                                                                                    <p>
                                                                                        {{
                                                                                            clinicStore.clinic.professional_video == 'Yes'
                                                                                                ? "Yes"
                                                                                                : "No"
                                                                                        }}
                                                                                    </p>
                                                                                </div>
                                                                                <div class="check-group">
                                                                                    <div class="d-flex flex-wrap">
                                                                                        <div class="form-check me-3">
                                                                                            <input
                                                                                                class="form-check-input"
                                                                                                type="radio"
                                                                                                name="professionalVideo"
                                                                                                id="Yes3"
                                                                                                value="Yes"
                                                                                                v-model="clinicStore.clinic.professional_video"
                                                                                            />
                                                                                            <label class="form-check-label" for="Yes3">
                                                                                                Yes </label>
                                                                                        </div>
                                                                                        <div class="form-check">
                                                                                            <input
                                                                                                class="form-check-input"
                                                                                                type="radio"
                                                                                                name="professionalVideo"
                                                                                                id="No3"
                                                                                                value="No"
                                                                                                v-model="clinicStore.clinic.professional_video"
                                                                                            />
                                                                                            <label class="form-check-label" for="No3">
                                                                                                No </label>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-12">
                                                                            <div class="form-group flex-column">
                                                                                <label class="text-muted mb-2"
                                                                                >Do you have quality patient before and after photos?</label
                                                                                >
                                                                                <div class="check-value">
                                                                                    <p>
                                                                                        {{
                                                                                            clinicStore.clinic.quality_patient_after_photos == 'Yes'
                                                                                                ? "Yes"
                                                                                                : "No"
                                                                                        }}
                                                                                    </p>
                                                                                </div>
                                                                                <div class="check-group">
                                                                                    <div class="d-flex flex-wrap">
                                                                                        <div class="form-check me-3">
                                                                                            <input
                                                                                                class="form-check-input"
                                                                                                type="radio"
                                                                                                name="qualityPatient"
                                                                                                id="Yes4"
                                                                                                value="Yes"
                                                                                                v-model="
                                                              clinicStore.clinic.quality_patient_after_photos
                                                            "
                                                                                            />
                                                                                            <label class="form-check-label" for="Yes4">
                                                                                                Yes </label>
                                                                                        </div>
                                                                                        <div class="form-check">
                                                                                            <input
                                                                                                class="form-check-input"
                                                                                                type="radio"
                                                                                                name="qualityPatient"
                                                                                                id="No4"
                                                                                                value="No"
                                                                                                v-model="
                                                              clinicStore.clinic.quality_patient_after_photos
                                                            "
                                                                                            />
                                                                                            <label class="form-check-label" for="No4">
                                                                                                No </label>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-12">
                                                                            <div class="form-group flex-column">
                                                                                <label class="text-muted mb-2"
                                                                                >Do you have patient testimonial videos?</label
                                                                                >
                                                                                <div class="check-value">
                                                                                    <p>
                                                                                        {{
                                                                                            clinicStore.clinic.patient_testimonial_videos
                                                                                                ? "Yes"
                                                                                                : "No"
                                                                                        }}
                                                                                    </p>
                                                                                </div>
                                                                                <div class="check-group">
                                                                                    <div class="d-flex flex-wrap">
                                                                                        <div class="form-check me-3">
                                                                                            <input
                                                                                                class="form-check-input"
                                                                                                type="radio"
                                                                                                name="patientTestimonial"
                                                                                                id="Yes5"
                                                                                                value="Yes"
                                                                                                v-model="clinicStore.clinic.patient_testimonial_videos"
                                                                                            />
                                                                                            <label class="form-check-label" for="Yes5">
                                                                                                Yes </label>
                                                                                        </div>
                                                                                        <div class="form-check">
                                                                                            <input
                                                                                                class="form-check-input"
                                                                                                type="radio"
                                                                                                name="patientTestimonial"
                                                                                                id="No5"
                                                                                                value="No"
                                                                                                v-model="clinicStore.clinic.patient_testimonial_videos"
                                                                                            />
                                                                                            <label class="form-check-label" for="No5">
                                                                                                No </label>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group form-group-border-top">
                                                    <textarea
                                                        row="2"
                                                        class="form-control"
                                                        v-model="clinicStore.clinic.testimonial_link"
                                                        @input="adjustHeight"
                                                        ref="testimonial_link"
                                                        style="overflow-y: hidden; resize: none;"
                                                    ></textarea>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-12">
                                                                            <div class="form-group flex-column">
                                                                                <label class="text-muted mb-2"
                                                                                >Do you have an up-to-date Google My Business page with
                                                                                    20+ patient reviews?</label
                                                                                >
                                                                                <div class="check-value">
                                                                                    <p>
                                                                                        {{
                                                                                            clinicStore.clinic.google_my_business_review == 'Yes'
                                                                                                ? "Yes"
                                                                                                : "No"
                                                                                        }}
                                                                                    </p>
                                                                                </div>
                                                                                <div class="check-group">
                                                                                    <div class="d-flex flex-wrap">
                                                                                        <div class="form-check me-3">
                                                                                            <input
                                                                                                class="form-check-input"
                                                                                                type="radio"
                                                                                                value="Yes"
                                                                                                id="Yes6"
                                                                                                v-model="clinicStore.clinic.google_my_business_review"
                                                                                            />
                                                                                            <label class="form-check-label" for="Yes6">
                                                                                                Yes </label>
                                                                                        </div>
                                                                                        <div class="form-check">
                                                                                            <input
                                                                                                class="form-check-input"
                                                                                                type="radio"
                                                                                                value="No"
                                                                                                id="No6"
                                                                                                v-model="clinicStore.clinic.google_my_business_review"
                                                                                            />
                                                                                            <label class="form-check-label" for="No6">
                                                                                                No </label>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        &lt;!&ndash; <div class="col-12">
                                                                            <div class="form-group flex-column">
                                                                                <label class="text-muted mb-2"
                                                                                >Activate Nurture Automation?</label
                                                                                >
                                                                                <div class="check-value">
                                                                                    <p>
                                                                                        {{
                                                                                            clinicStore.clinic.nurture_automation == 'Yes'
                                                                                                ? "Yes"
                                                                                                : "No"
                                                                                        }}
                                                                                    </p>
                                                                                </div>
                                                                                <div class="check-group">
                                                                                    <div class="d-flex flex-wrap">
                                                                                        <div class="form-check me-3">
                                                                                            <input
                                                                                                class="form-check-input"
                                                                                                type="radio"
                                                                                                value="Yes"
                                                                                                id="nurture-automation-yes"
                                                                                                v-model="clinicStore.clinic.nurture_automation"
                                                                                            />
                                                                                            <label class="form-check-label"
                                                                                                   for="nurture-automation-yes"> Yes </label>
                                                                                        </div>
                                                                                        <div class="form-check">
                                                                                            <input
                                                                                                class="form-check-input"
                                                                                                type="radio"
                                                                                                value="No"
                                                                                                id="nurture-automation-no"
                                                                                                v-model="clinicStore.clinic.nurture_automation"
                                                                                            />
                                                                                            <label class="form-check-label"
                                                                                                   for="nurture-automation-no"> No </label>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div> &ndash;&gt;
                                                                        <div>                                                       <div class="col-12">
                                                                                <div class="form-group">
                                                                                    <span
                                                                                        class="d-block text-danger mt-1"
                                                                                        v-text="clinicStore.errors.nurturing_display_name"
                                                                                    ></span>
                                                                                    <input
                                                                                        v-model="clinicStore.clinic.nurturing_display_name"
                                                                                        type="text"
                                                                                        class="form-control"
                                                                                        :class="clinicStore.clinic.nurturing_display_name ? 'filled' : ''"
                                                                                    />
                                                                                    <label>Nurturing Display Name</label>
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <span
                                                                                        class="d-block text-danger mt-1"
                                                                                        v-text="clinicStore.errors.scheduling_link"
                                                                                    ></span>
                                                                                    <input
                                                                                        v-model="clinicStore.clinic.scheduling_link"
                                                                                        type="text"
                                                                                        class="form-control"
                                                                                        :class="clinicStore.clinic.scheduling_link ? 'filled' : ''"
                                                                                    />
                                                                                    <label>Scheduling Link</label>
                                                                                </div>
                                                                            </div>

                                                                            <div class="col-12">
                                                                                <div class="form-group">
                                                                                  <span
                                                                                      class="d-block text-danger mt-1"
                                                                                      v-text="clinicStore.errors.link1"
                                                                                  ></span>
                                                                                    <input
                                                                                        v-model="clinicStore.clinic.link1"
                                                                                        type="text"
                                                                                        class="form-control"
                                                                                        :class="clinicStore.clinic.link1 ? 'filled' : ''"
                                                                                    />
                                                                                    <label>Patient Testimonial Video URL1 for Nurture
                                                                                        Automation</label>
                                                                                </div>
                                                                            </div>

                                                                            <div class="col-12">
                                                                                <div class="form-group">
                                                                                  <span
                                                                                      class="d-block text-danger mt-1"
                                                                                      v-text="clinicStore.errors.link2"
                                                                                  ></span>
                                                                                    <input
                                                                                        v-model="clinicStore.clinic.link2"
                                                                                        type="text"
                                                                                        class="form-control"
                                                                                        :class="clinicStore.clinic.link2 ? 'filled' : ''"
                                                                                    />
                                                                                    <label>Patient Testimonial Video URL2 for Nurture
                                                                                        Automation</label>
                                                                                </div>
                                                                            </div>

                                                                            <div class="col-12">
                                                                                <div class="form-group">
                                                                                  <span
                                                                                      class="d-block text-danger mt-1"
                                                                                      v-text="clinicStore.errors.link3"
                                                                                  ></span>
                                                                                    <input
                                                                                        v-model="clinicStore.clinic.link3"
                                                                                        type="text"
                                                                                        class="form-control"
                                                                                        :class="clinicStore.clinic.link3 ? 'filled' : ''"
                                                                                    />
                                                                                    <label>Patient Testimonial Video URL3 for Nurture
                                                                                        Automation</label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-12">
                                                                            <div class="form-group flex-column">
                                                                                <h5 class="d-lg-none">Would you like to add the doctor’s biography here to be used on landing pages and other marketing material, or do you wish AI to create a compelling message based on online information that you can review, edit, and approve?</h5>
                                                                                <label class="d-none d-lg-block"
                                                                                >Would you like to add the doctor’s biography here to be used on landing pages and other marketing material, or do you wish AI to create a compelling message based on online information that you can review, edit, and approve?</label
                                                                                >
                                                                                <div class="check-value">
                                                                                    <p>{{
                                                                                            clinicStore.clinic.doctors_biography ?? 'No'
                                                                                        }}</p>
                                                                                </div>
                                                                                <div class="check-group">
                                                                                    <div class="inline-check-group d-flex flex-md-wrap">
                                                                                        <div class="btn-checkbox mb-2 me-2">
                                                                                            <input
                                                                                                type="radio"
                                                                                                class="btn-check"
                                                                                                id="noDoctorsBiography"
                                                                                                value="No"
                                                                                                v-model="
                                                                                                     clinicStore.clinic.doctors_biography"
                                                                                            />
                                                                                            <label class="btn btn-outline-primary" for="noDoctorsBiography"
                                                                                            >Use AI to create the doctor’s biography.</label
                                                                                            ><br/>
                                                                                        </div>
                                                                                        <div class="btn-checkbox mb-2 me-2 websiteRadioGroup">
                                                                                            <input
                                                                                                type="radio"
                                                                                                class="btn-check"
                                                                                                id="yesDoctorsBiography"
                                                                                                value="Yes"
                                                                                                v-model="
                                                                                                  clinicStore.clinic.doctors_biography
                                                                                                "
                                                                                            />
                                                                                            <label class="btn btn-outline-primary" for="yesDoctorsBiography"
                                                                                            >I’ll provide the doctor’s biography.</label
                                                                                            ><br/>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-12"
                                                                             v-show="clinicStore.clinic.doctors_biography == 'Yes'">
                                                                            <div class="form-group">
                                                                                <span
                                                                                    class="d-block text-danger mt-1"
                                                                                    v-text="clinicStore.errors.doctors_biography_description"
                                                                                ></span>
                                                                                <textarea
                                                                                    type="text"
                                                                                    class="form-control"
                                                                                    v-model="clinicStore.clinic.doctors_biography_description"
                                                                                    :class="clinicStore.clinic.doctors_biography_description ? 'filled' : ''"
                                                                                    @input="adjustHeight"
                                                                                    ref="doctors_biography_description"
                                                                                    style="overflow-y: hidden; resize: none;"
                                                                                ></textarea>
                                                                                <label>Doctor's Biography Description</label>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-12">
                                                                            <div class="form-group flex-column">
                                                                                <h5 class="d-lg-none">Would you like to provide a brief bullet-point summary of the doctor’s bio or let AI do it?</h5>
                                                                                <label class="d-none d-lg-block"
                                                                                >Would you like to provide a brief bullet-point summary of the doctor’s bio or let AI do it?</label
                                                                                >
                                                                                <div class="check-value">
                                                                                    <p>{{
                                                                                            clinicStore.clinic.doctors_biography_summary ?? 'No'
                                                                                        }}</p>
                                                                                </div>
                                                                                <div class="check-group">
                                                                                    <div class="inline-check-group d-flex flex-md-wrap">
                                                                                        <div class="btn-checkbox mb-2 me-2">
                                                                                            <input
                                                                                                type="radio"
                                                                                                class="btn-check"
                                                                                                id="noDoctorsBiographySummary"
                                                                                                value="No"
                                                                                                v-model="
                                                                                                     clinicStore.clinic.doctors_biography_summary"
                                                                                            />
                                                                                            <label class="btn btn-outline-primary" for="noDoctorsBiographySummary"
                                                                                            >Use AI to create a summary of the doctor’s bio.</label
                                                                                            ><br/>
                                                                                        </div>
                                                                                        <div class="btn-checkbox mb-2 me-2 websiteRadioGroup">
                                                                                            <input
                                                                                                type="radio"
                                                                                                class="btn-check"
                                                                                                id="yesDoctorsBiographySummary"
                                                                                                value="Yes"
                                                                                                v-model="
                                                                                                  clinicStore.clinic.doctors_biography_summary
                                                                                                "
                                                                                            />
                                                                                            <label class="btn btn-outline-primary" for="yesDoctorsBiographySummary"
                                                                                            >I’ll provide a summary of the doctor’s bio.</label
                                                                                            ><br/>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-12"
                                                                             v-show="clinicStore.clinic.doctors_biography_summary == 'Yes'">
                                                                            <div class="form-group">
                                                                                <span
                                                                                    class="d-block text-danger mt-1"
                                                                                    v-text="clinicStore.errors.doctors_biography_summary"
                                                                                ></span>
                                                                                <textarea
                                                                                    class="form-control"
                                                                                    v-model="clinicStore.clinic.doctors_biography_summary_description"
                                                                                    :class="clinicStore.clinic.doctors_biography_summary_description ? 'filled' : ''"
                                                                                    @input="adjustHeight"
                                                                                    ref="doctors_biography_summary_description"
                                                                                    style="overflow-y: hidden; resize: none;"
                                                                                ></textarea>
                                                                                <label>Doctor's Biography Summary Description</label>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-12">
                                                                            <div class="form-group flex-column">
                                                                                <h5 class="d-lg-none">Would you like to provide a brief marketing message that promotes the practice to be used on landing pages and in other marketing material, or do you wish AI to create a compelling message based on information available online that you can review, edit, and approve?</h5>
                                                                                <label class="d-none d-lg-block"
                                                                                >Would you like to provide a brief marketing message that promotes the practice to be used on landing pages and in other marketing material, or do you wish AI to create a compelling message based on information available online that you can review, edit, and approve?</label
                                                                                >
                                                                                <div class="check-value">
                                                                                    <p>{{
                                                                                            clinicStore.clinic.marketing_message ?? 'No'
                                                                                        }}</p>
                                                                                </div>
                                                                                <div class="check-group">
                                                                                    <div class="inline-check-group d-flex flex-md-wrap">
                                                                                        <div class="btn-checkbox mb-2 me-2">
                                                                                            <input
                                                                                                type="radio"
                                                                                                class="btn-check"
                                                                                                id="noDoctorsMarketing"
                                                                                                value="No"
                                                                                                v-model="
                                                                                                     clinicStore.clinic.marketing_message"
                                                                                            />
                                                                                            <label class="btn btn-outline-primary" for="noDoctorsMarketing"
                                                                                            >Use AI to create the marketing message.</label
                                                                                            ><br/>
                                                                                        </div>
                                                                                        <div class="btn-checkbox mb-2 me-2 websiteRadioGroup">
                                                                                            <input
                                                                                                type="radio"
                                                                                                class="btn-check"
                                                                                                id="yesDoctorsMarketing"
                                                                                                value="Yes"
                                                                                                v-model="
                                                                                                  clinicStore.clinic.marketing_message
                                                                                                "
                                                                                            />
                                                                                            <label class="btn btn-outline-primary" for="yesDoctorsMarketing"
                                                                                            >I’ll provide a brief marketing message.</label
                                                                                            ><br/>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-12"
                                                                             v-show="clinicStore.clinic.marketing_message == 'Yes'">
                                                                            <div class="form-group">
                                                                                <span
                                                                                    class="d-block text-danger mt-1"
                                                                                    v-text="clinicStore.errors.marketing_message_description"
                                                                                ></span>
                                                                                <textarea
                                                                                    class="form-control"
                                                                                    v-model="clinicStore.clinic.marketing_message_description"
                                                                                    :class="clinicStore.clinic.marketing_message_description ? 'filled' : ''"
                                                                                    @input="adjustHeight"
                                                                                    ref="marketing_message_description"
                                                                                    style="overflow-y: hidden; resize: none;"
                                                                                ></textarea>
                                                                                <label>Doctor's Marketing Message Description</label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div
                                                        class="tab-main-box"   :class="marketing_budget_editing ? 'tab-edit-active tab-main-active' : 'tab-disabled-active'">
                                                        <div class="tab-title-box d-flex align-items-center">
                                                            <div class="tab-title" @click.prevent="marketing_budget_editing = true">
                                                                <h3>Marketing and Budget</h3>
                                                            </div>
                                                            <div
                                                                class="edit-option d-none d-lg-flex ms-auto"
                                                                :class="marketing_budget_editing ? 'edit-active' : ''">
                                                                <a role="button" class="save-input manage-cancel"
                                                                   v-show="marketing_budget_editing" @click="marketing_budget_editing=false">Cancel</a>
                                                                <a
                                                                    v-if="!marketing_budget_editing"
                                                                    role="button"
                                                                    @click.prevent="marketing_budget_editing = true"
                                                                    class="edit-input manage-edit"
                                                                >Edit
                                                                    <svg
                                                                        width="18"
                                                                        height="18"
                                                                        viewBox="0 0 18 18"
                                                                        xmlns="http://www.w3.org/2000/svg"
                                                                    >
                                                                        <path
                                                                            d="M16 17.25C16 16.848 15.644 16.5 15.25 16.5C12.689 16.5 3.311 16.5 0.75 16.5C0.356 16.5 0 16.848 0 17.25C0 17.652 0.356 18 0.75 18H15.25C15.644 18 16 17.652 16 17.25ZM8.597 13.852L17.721 4.727C17.892 4.556 18 4.304 18 4.043C18 3.814 17.917 3.577 17.72 3.381L14.605 0.277C14.42 0.0919999 14.176 0 13.933 0C13.69 0 13.447 0.0919999 13.261 0.277L4.118 9.38C3.549 11.143 2.563 14.203 2.492 14.461C2.472 14.536 2.463 14.611 2.463 14.685C2.463 15.146 2.812 15.533 3.228 15.533C3.739 15.533 4.219 15.344 8.597 13.852ZM5.327 10.51L7.464 12.647L4.296 13.693L5.327 10.51ZM6.282 9.344L13.933 1.728L16.268 4.055L8.631 11.693L6.282 9.344Z"
                                                                        />
                                                                    </svg>
                                                                </a>
                                                                <a
                                                                    v-else
                                                                    role="button"
                                                                    @click.prevent="marketing_budget_editing_save()"
                                                                    class="save-input manage-save"
                                                                >Save</a
                                                                >
                                                            </div>
                                                        </div>
                                                        <div class="tab-sub-box">
                                                            <div class="d-lg-none d-flex align-items-center justify-content-between">
                                                                <a role="button" class="back-btn" @click="resetActiveTabs">
                                                                    <svg
                                                                        width="20"
                                                                        height="14"
                                                                        viewBox="0 0 20 14"
                                                                        fill="none"
                                                                        xmlns="http://www.w3.org/2000/svg"
                                                                    >
                                                                        <path
                                                                            fill-rule="evenodd"
                                                                            clip-rule="evenodd"
                                                                            d="M19.4411 6.37497H2.04859L7.25172 1.19185C7.49609 0.947473 7.49609 0.551848 7.25172 0.308098C7.00734 0.0637231 6.61172 0.0637231 6.36797 0.308098L0.180469 6.49497C-0.0601562 6.7356 -0.0601562 7.1381 0.180469 7.37872L6.36797 13.5662C6.61234 13.8106 7.00797 13.8106 7.25172 13.5662C7.49609 13.3218 7.49609 12.9262 7.25172 12.6825L2.04859 7.62497H19.4411C19.7861 7.62497 20.0661 7.34497 20.0661 6.99997C20.0661 6.65497 19.7861 6.37497 19.4411 6.37497Z"
                                                                            fill="#514F5F"
                                                                        />
                                                                    </svg>
                                                                </a>
                                                                <a role="button" class="save-input btn px-4 btn-primary tab-save"
                                                                   @click.prevent="marketing_budget_editing_save()">Save</a>
                                                            </div>
                                                            <div class="d-lg-none mobile-tab-title">
                                                                <h3>Marketing and Budget</h3>
                                                            </div>
                                                            <div class="row gx-3">
                                                                <div class="col-12">
                                                                    <div class="row">
                                                                        <div class="col-12">
                                                                            <div class="form-group flex-column">
                                                                                <h5 class="d-lg-none">
                                                                                    Are you using a separate marketing company?
                                                                                </h5>
                                                                                <label class="d-none d-lg-block"
                                                                                >Are you using a separate marketing company?</label
                                                                                >
                                                                                <div class="check-value">
                                                                                    <p>
                                                                                        {{
                                                                                            clinicStore.clinic.separate_marketing_company == "Yes"
                                                                                                ? "Yes"
                                                                                                : "No"
                                                                                        }}
                                                                                    </p>
                                                                                </div>
                                                                                <div class="check-group">
                                                                                    <div class="d-flex flex-wrap">
                                                                                        <div class="form-check me-3">
                                                                                            <input
                                                                                                class="form-check-input"
                                                                                                type="radio"
                                                                                                name="accurate"
                                                                                                id="Yes7"
                                                                                                value="Yes"
                                                                                                v-model="
                                                              clinicStore.clinic.separate_marketing_company
                                                            "
                                                                                            />
                                                                                            <label class="form-check-label" for="Yes7">
                                                                                                Yes </label>
                                                                                        </div>
                                                                                        <div class="form-check">
                                                                                            <input
                                                                                                class="form-check-input"
                                                                                                type="radio"
                                                                                                name="accurate"
                                                                                                id="No7"
                                                                                                value="No"
                                                                                                v-model="
                                                              clinicStore.clinic.separate_marketing_company
                                                            "
                                                                                            />
                                                                                            <label class="form-check-label" for="No7">
                                                                                                No </label>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-12">
                                                                            <div class="form-group flex-column">
                                                                                <h5 class="d-lg-none">Paid Media</h5>
                                                                                <label class="d-none d-lg-block">Paid Media</label>
                                                                                <div class="check-value">
                                                                                    <p>
                                                                                        {{
                                                                                            clinicStore.clinic.paid_media
                                                                                                ? clinicStore.clinic.paid_media.join(", ")
                                                                                                : ""
                                                                                        }}
                                                                                    </p>
                                                                                </div>
                                                                                <div class="check-group">
                                                                                    <p class="text-muted mb-2">
                                                                                        What paid media would you like to use? Select all that
                                                                                        apply.
                                                                                    </p>
                                                                                    <div class="d-flex flex-wrap">
                                                                                        <div class="btn-checkbox mb-2 me-2">
                                                                                            <input
                                                                                                type="checkbox"
                                                                                                class="btn-check"
                                                                                                id="GoogleAdWords"
                                                                                                value="Google AdWords"
                                                                                                v-model="clinicStore.clinic.paid_media"
                                                                                            />
                                                                                            <label
                                                                                                class="btn btn-outline-primary"
                                                                                                for="GoogleAdWords"
                                                                                            >Google AdWords</label
                                                                                            ><br/>
                                                                                        </div>
                                                                                        <div class="btn-checkbox mb-2 me-2">
                                                                                            <input
                                                                                                type="checkbox"
                                                                                                class="btn-check"
                                                                                                id="Facebook"
                                                                                                value="Facebook"
                                                                                                v-model="clinicStore.clinic.paid_media"
                                                                                            />
                                                                                            <label class="btn btn-outline-primary"
                                                                                                   for="Facebook"
                                                                                            >Facebook</label
                                                                                            ><br/>
                                                                                        </div>
                                                                                        <div class="btn-checkbox mb-2 me-2">
                                                                                            <input
                                                                                                type="checkbox"
                                                                                                class="btn-check"
                                                                                                id="Instagram"
                                                                                                value="Instagram"
                                                                                                v-model="clinicStore.clinic.paid_media"
                                                                                            />
                                                                                            <label class="btn btn-outline-primary"
                                                                                                   for="Instagram"
                                                                                            >Instagram</label
                                                                                            ><br/>
                                                                                        </div>
                                                                                        <div class="btn-checkbox mb-2 me-2">
                                                                                            <input
                                                                                                type="checkbox"
                                                                                                class="btn-check"
                                                                                                id="YouTube"
                                                                                                value="YouTube"
                                                                                                v-model="clinicStore.clinic.paid_media"
                                                                                            />
                                                                                            <label class="btn btn-outline-primary" for="YouTube"
                                                                                            >YouTube</label
                                                                                            ><br/>
                                                                                        </div>
                                                                                        <div class="btn-checkbox mb-2 me-2">
                                                                                            <input
                                                                                                type="checkbox"
                                                                                                class="btn-check"
                                                                                                id="Other3"
                                                                                                value="Other"
                                                                                                v-model="clinicStore.clinic.paid_media"
                                                                                            />
                                                                                            <label class="btn btn-outline-primary" for="Other3"
                                                                                            >Other</label
                                                                                            ><br/>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-12">
                                                                            <div class="form-group flex-column">
                                                                                <label class="mb-2">Monthly Media Budget</label>
                                                                                <input
                                                                                    type="text"
                                                                                    class="form-control"
                                                                                    v-model="clinicStore.clinic.monthly_media_budget"
                                                                                />
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-12">
                                                                            <div class="form-group flex-column">
                                                                                <label>Primary Selling Messages</label>
                                                                                <div class="check-group text-muted">
                                                                                    <p class="mb-2">
                                                                                        What are your primary selling messages, i.e., what
                                                                                        should
                                                                                        be emphasized in your marketing?
                                                                                    </p>
                                                                                </div>
                                                                                <div class="form-group mb-0">

                                                                                    <textarea
                                                                                        row="2"
                                                                                        class="form-control"
                                                                                        v-model="clinicStore.clinic.primary_selling_message"
                                                                                        :class="clinicStore.clinic.primary_selling_message ? 'filled' : ''"
                                                                                        @input="adjustHeight"
                                                                                        ref="primarySellingMessage"
                                                                                        style="overflow-y: hidden; resize: none;"
                                                                                    ></textarea>
                                                                                    <label class="d-lg-none">Please describe in detail.</label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-12">
                                                                            <div class="form-group flex-column">
                                                                                <label class="text-muted mb-2"
                                                                                >Do you wish to promote any pricing or other promotional
                                                                                    specials?</label
                                                                                >
                                                                                <div class="check-value">
                                                                                    <p>
                                                                                        {{
                                                                                            clinicStore.clinic.promotional_specials == "Yes"
                                                                                                ? "Yes"
                                                                                                : "No"
                                                                                        }}
                                                                                    </p>
                                                                                </div>
                                                                                <div class="check-group">
                                                                                    <div class="d-flex flex-wrap">
                                                                                        <div class="form-check me-3">
                                                                                            <input
                                                                                                class="form-check-input"
                                                                                                type="radio"
                                                                                                name="promotionalspecials"
                                                                                                id="Yes8"
                                                                                                value="Yes"
                                                                                                v-model="clinicStore.clinic.promotional_specials"
                                                                                            />
                                                                                            <label class="form-check-label" for="Yes8">
                                                                                                Yes </label>
                                                                                        </div>
                                                                                        <div class="form-check">
                                                                                            <input
                                                                                                class="form-check-input"
                                                                                                type="radio"
                                                                                                name="promotionalspecials"
                                                                                                value="No"
                                                                                                id="No8"
                                                                                                v-model="clinicStore.clinic.promotional_specials"
                                                                                            />
                                                                                            <label class="form-check-label" for="No8">
                                                                                                No </label>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <label class="text-muted mb-2"
                                                                                   v-if="clinicStore.clinic.promotional_specials == 'Yes'"
                                                                                   for="No8">
                                                                                What special offers are available to your patients?
                                                                                [example: complimentary consultation, full arch dental
                                                                                implants starting at $19999, single implant starting at
                                                                                $3495, 10% off each veneer when you purchase 6 or more,
                                                                                $149 new patient special, etc.]
                                                                            </label>
                                                                            <div
                                                                                class="form-group xform-group-border-top"
                                                                                v-if="clinicStore.clinic.promotional_specials == 'Yes'"
                                                                            >

                                                                                <textarea
                                                                                    row="2"
                                                                                    class="form-control"
                                                                                    v-model="clinicStore.clinic.promotional_specials_desc"
                                                                                    :class="clinicStore.clinic.promotional_specials_desc ? 'filled' : ''"
                                                                                    @input="adjustHeight"
                                                                                    ref="promotional_specials_desc"
                                                                                    style="overflow-y: hidden; resize: none;"
                                                                                ></textarea>
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-12">
                                                                            <div class="form-group flex-column">
                                                                                <h5 class="d-lg-none">Technology</h5>
                                                                                <label class="d-none d-lg-block">Technology</label>
                                                                                <div class="check-value">
                                                                                    <p>
                                                                                        {{
                                                                                            clinicStore.clinic.technology
                                                                                                ? clinicStore.clinic.technology.join(", ")
                                                                                                : ""
                                                                                        }} {{clinicStore.clinic.technology_desc ? ' ( '+clinicStore.clinic.technology_desc+' )' : ''}}
                                                                                    </p>
                                                                                </div>
                                                                                <div class="check-group">
                                                                                    <p class="text-muted mb-2">
                                                                                        Do you have the following technology? Select all that
                                                                                        apply.
                                                                                    </p>
                                                                                    <div class="d-flex flex-wrap">
                                                                                        <div class="w-100 inline-check-group d-flex flex-wrap">
                                                                                            <div class="btn-checkbox mb-2 me-2">
                                                                                                <input
                                                                                                    type="checkbox"
                                                                                                    class="btn-check"
                                                                                                    id="IntraoralScanner"
                                                                                                    value="Intraoral Scanner"
                                                                                                    v-model="clinicStore.clinic.technology"
                                                                                                />
                                                                                                <label
                                                                                                    class="btn btn-outline-primary"
                                                                                                    for="IntraoralScanner"
                                                                                                >Intraoral Scanner</label
                                                                                                ><br/>
                                                                                            </div>
                                                                                            <div class="btn-checkbox mb-2 me-2">
                                                                                                <input
                                                                                                    type="checkbox"
                                                                                                    class="btn-check"
                                                                                                    id="AnalysisSoftware"
                                                                                                    value="Digital Smile Analysis Software"
                                                                                                    v-model="clinicStore.clinic.technology"
                                                                                                />
                                                                                                <label
                                                                                                    class="btn btn-outline-primary"
                                                                                                    for="AnalysisSoftware"
                                                                                                >Digital Smile Analysis Software</label
                                                                                                ><br/>
                                                                                            </div>
                                                                                            <div class="btn-checkbox mb-2 me-2">
                                                                                                <input
                                                                                                    type="checkbox"
                                                                                                    class="btn-check"
                                                                                                    id="InHouseMill"
                                                                                                    value="In-House Mill"
                                                                                                    v-model="clinicStore.clinic.technology"
                                                                                                />
                                                                                                <label
                                                                                                    class="btn btn-outline-primary"
                                                                                                    for="InHouseMill"
                                                                                                >In-House Mill</label
                                                                                                ><br/>
                                                                                            </div>
                                                                                            <div class="btn-checkbox mb-2 me-2">
                                                                                                <input
                                                                                                    type="checkbox"
                                                                                                    class="btn-check"
                                                                                                    id="Photogrammetry"
                                                                                                    value="Photogrammetry"
                                                                                                    v-model="clinicStore.clinic.technology"
                                                                                                />
                                                                                                <label
                                                                                                    class="btn btn-outline-primary"
                                                                                                    for="Photogrammetry"
                                                                                                >Photogrammetry</label
                                                                                                ><br/>
                                                                                            </div>
                                                                                            <div class="btn-checkbox mb-2 me-2">
                                                                                                <input
                                                                                                    type="checkbox"
                                                                                                    class="btn-check"
                                                                                                    id="Printing"
                                                                                                    value="Printing"
                                                                                                    v-model="clinicStore.clinic.technology"
                                                                                                />
                                                                                                <label class="btn btn-outline-primary"
                                                                                                       for="Printing"
                                                                                                >Printing</label
                                                                                                ><br/>
                                                                                            </div>
                                                                                            <div class="btn-checkbox mb-2 me-2">
                                                                                                <input
                                                                                                    type="checkbox"
                                                                                                    class="btn-check"
                                                                                                    id="GuidedSurgery"
                                                                                                    value="Guided Surgery"
                                                                                                    v-model="clinicStore.clinic.technology"
                                                                                                />
                                                                                                <label
                                                                                                    class="btn btn-outline-primary"
                                                                                                    for="GuidedSurgery"
                                                                                                >Guided Surgery</label
                                                                                                ><br/>
                                                                                            </div>
                                                                                            <div class="btn-checkbox mb-2 me-2">
                                                                                                <input
                                                                                                    type="checkbox"
                                                                                                    class="btn-check"
                                                                                                    value="Other"
                                                                                                    id="Other4"
                                                                                                    v-model="clinicStore.clinic.technology"
                                                                                                />
                                                                                                <label class="btn btn-outline-primary"
                                                                                                       for="Other4"
                                                                                                >Other</label
                                                                                                ><br/>
                                                                                            </div>
                                                                                            <div class="followingTechnology-other">
                                                                                                <div
                                                                                                    id="showOther4"
                                                                                                    v-if="
                                                                clinicStore.clinic.technology &&
                                                                clinicStore.clinic.technology.includes('Other')
                                                              "
                                                                                                >
                                                                                                    <div class="form-group">
                                                                                                        <input
                                                                                                            class="form-control"
                                                                                                            v-model="clinicStore.clinic.technology_desc"
                                                                                                        />
                                                                                                        <label class="d-lg-none"
                                                                                                        >Please describe technology.</label
                                                                                                        >
                                                                                                    </div>
                                                                                                    &lt;!&ndash;/.form-group&ndash;&gt;
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-12">
                                                                            <div class="form-group flex-column">
                                                                                <h5 class="d-lg-none">Financing Company</h5>
                                                                                <label class="d-none d-lg-block">Financing Company</label>
                                                                                <div class="check-value">
                                                                                    <p>
                                                                                        {{
                                                                                            clinicStore.clinic.financing_options
                                                                                                ? clinicStore.clinic.financing_options.join(", ")
                                                                                                : ""
                                                                                        }}{{clinicStore.clinic.other_finance_company ? ' ( '+clinicStore.clinic.other_finance_company+' )' : ''}}
                                                                                    </p>
                                                                                </div>
                                                                                <div class="check-group">
                                                                                    <p class="text-muted mb-2">
                                                                                        Do you work with a financing company?
                                                                                    </p>
                                                                                    <div class="check-group mb-2">
                                                                                        <div class="d-flex flex-wrap">
                                                                                            <div class="form-check me-3">
                                                                                                <input
                                                                                                    class="form-check-input"
                                                                                                    type="radio"
                                                                                                    name="financingCompany"
                                                                                                    id="Yes9"
                                                                                                    value="Yes"
                                                                                                    v-model="clinicStore.clinic.work_with_finance_company"
                                                                                                />
                                                                                                <label class="form-check-label" for="Yes9">
                                                                                                    Yes
                                                                                                </label>
                                                                                            </div>
                                                                                            <div class="form-check">
                                                                                                <input
                                                                                                    class="form-check-input"
                                                                                                    type="radio"
                                                                                                    name="financingCompany"
                                                                                                    id="No9"
                                                                                                    value="No"
                                                                                                    v-model="clinicStore.clinic.work_with_finance_company"
                                                                                                />
                                                                                                <label class="form-check-label" for="No9">
                                                                                                    No </label>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div
                                                                                        class="d-flex flex-wrap"
                                                                                        v-if="clinicStore.clinic.work_with_finance_company == 'Yes'">
                                                                                        <div class="w-100 inline-check-group d-flex flex-wrap">
                                                                                            <div class="btn-checkbox mb-2 me-2">
                                                                                                <input
                                                                                                    type="checkbox"
                                                                                                    class="btn-check"
                                                                                                    id="Proceed"
                                                                                                    value="Proceed"
                                                                                                    v-model="clinicStore.clinic.financing_options"
                                                                                                />
                                                                                                <label class="btn btn-outline-primary"
                                                                                                       for="Proceed"
                                                                                                >Proceed</label
                                                                                                ><br/>
                                                                                            </div>
                                                                                            <div class="btn-checkbox mb-2 me-2">
                                                                                                <input
                                                                                                    type="checkbox"
                                                                                                    class="btn-check"
                                                                                                    id="CareCredit"
                                                                                                    value="CareCredit"
                                                                                                    v-model="clinicStore.clinic.financing_options"
                                                                                                />
                                                                                                <label
                                                                                                    class="btn btn-outline-primary"
                                                                                                    for="CareCredit"
                                                                                                >Care Credit</label
                                                                                                ><br/>
                                                                                            </div>
                                                                                            <div class="btn-checkbox mb-2 me-2">
                                                                                                <input
                                                                                                    type="checkbox"
                                                                                                    class="btn-check"
                                                                                                    id="Alpheon"
                                                                                                    value="Alpheon"
                                                                                                    v-model="clinicStore.clinic.financing_options"
                                                                                                />
                                                                                                <label
                                                                                                    class="btn btn-outline-primary"
                                                                                                    for="Alpheon"
                                                                                                >Alpheon</label
                                                                                                ><br/>
                                                                                            </div>
                                                                                            <div class="btn-checkbox mb-2 me-2">
                                                                                                <input type="checkbox" class="btn-check"
                                                                                                       value="Sunbit" id="Sunbit2"
                                                                                                       autocomplete="off"
                                                                                                       v-model="clinicStore.clinic.financing_options">
                                                                                                <label class="btn btn-outline-primary"
                                                                                                       for="Sunbit2">Sunbit</label><br>
                                                                                            </div>
                                                                                            <div class="btn-checkbox mb-2 me-2">
                                                                                                <input type="checkbox" class="btn-check"
                                                                                                       value="PatientFi" id="PatientFi1"
                                                                                                       autocomplete="off"
                                                                                                       v-model="clinicStore.clinic.financing_options">
                                                                                                <label class="btn btn-outline-primary"
                                                                                                       for="PatientFi1">PatientFi</label><br>
                                                                                            </div>
                                                                                            <div class="btn-checkbox mb-2 me-2">
                                                                                                <input type="checkbox" class="btn-check"
                                                                                                       value="Ally" id="Ally"
                                                                                                       autocomplete="off"
                                                                                                       v-model="clinicStore.clinic.financing_options">
                                                                                                <label class="btn btn-outline-primary"
                                                                                                       for="Ally">Ally</label><br>
                                                                                            </div>
                                                                                            <div class="btn-checkbox mb-2 me-2">
                                                                                                <input type="checkbox" class="btn-check"
                                                                                                       value="FinancingInHouse" id="FinancingInHouse"
                                                                                                       autocomplete="off"
                                                                                                       v-model="clinicStore.clinic.financing_options">
                                                                                                <label class="btn btn-outline-primary"
                                                                                                       for="FinancingInHouse">Financing In-House</label><br>
                                                                                            </div>
                                                                                            <div class="btn-checkbox mb-2 me-2">
                                                                                                <input
                                                                                                    type="checkbox"
                                                                                                    class="btn-check"
                                                                                                    value="Other"
                                                                                                    id="Other7"
                                                                                                    v-model="clinicStore.clinic.financing_options"
                                                                                                />
                                                                                                <label class="btn btn-outline-primary"
                                                                                                       for="Other7"
                                                                                                >Other</label
                                                                                                ><br/>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div
                                                                                            class="financingCompany-other"
                                                                                            v-if="clinicStore.clinic.financing_options.includes('Other')"
                                                                                        >
                                                                                            <div id="showOther7">
                                                                                                <div class="form-group">
                                                                                                    <input
                                                                                                        class="form-control"
                                                                                                        v-model="
                                                                    clinicStore.clinic.other_finance_company
                                                                  " :class="clinicStore.clinic.other_finance_company ? 'filled' : ''"
                                                                                                    />
                                                                                                    <label class="d-lg-none"
                                                                                                    >Please list other companies.</label
                                                                                                    >
                                                                                                </div>
                                                                                                &lt;!&ndash;/.form-group&ndash;&gt;
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="tab-main-box" v-if="clinicStore.clinic.callrail_installation_script"   :class="callrail_installtion_editing ? 'tab-edit-active tab-main-active' : 'tab-disabled-active'">
                                                        <div class="tab-title-box d-flex align-items-center">
                                                            <div class="tab-title" @click.prevent="callrail_installtion_editing = true">
                                                                <h3>Installation Instructions</h3>
                                                            </div>
                                                        </div>
                                                        <div class="tab-sub-box">
                                                            <div class="d-lg-none d-flex align-items-center justify-content-between">
                                                                <a role="button" class="back-btn" @click="resetActiveTabs">
                                                                    <svg
                                                                        width="20"
                                                                        height="14"
                                                                        viewBox="0 0 20 14"
                                                                        fill="none"
                                                                        xmlns="http://www.w3.org/2000/svg"
                                                                    >
                                                                        <path
                                                                            fill-rule="evenodd"
                                                                            clip-rule="evenodd"
                                                                            d="M19.4411 6.37497H2.04859L7.25172 1.19185C7.49609 0.947473 7.49609 0.551848 7.25172 0.308098C7.00734 0.0637231 6.61172 0.0637231 6.36797 0.308098L0.180469 6.49497C-0.0601562 6.7356 -0.0601562 7.1381 0.180469 7.37872L6.36797 13.5662C6.61234 13.8106 7.00797 13.8106 7.25172 13.5662C7.49609 13.3218 7.49609 12.9262 7.25172 12.6825L2.04859 7.62497H19.4411C19.7861 7.62497 20.0661 7.34497 20.0661 6.99997C20.0661 6.65497 19.7861 6.37497 19.4411 6.37497Z"
                                                                            fill="#514F5F"
                                                                        />
                                                                    </svg>
                                                                </a>
                                                            </div>
                                                            <div class="d-lg-none mobile-tab-title">
                                                                <h3>JavaScript Snippet</h3>
                                                            </div>
                                                            <div class="row gx-3">
                                                                <div class="col-md-8 col-lg-12">
                                                                    <div >
                                                                        <div>
                                                                            <div class="form-group flex-column">
                                                                                <label class="mb-2">JavaScript Snippet</label>
                                                                                <p>Insert this code snippet immediately before the body tag on every page of your website. You can install this snippet on both HTTP and HTTPS pages.</p>
                                                                                <input
                                                                                    type="text"
                                                                                    class="form-control"
                                                                                    v-model="clinicStore.clinic.callrail_installation_script"
                                                                                    style="border: 1px solid #ccc; color: #333; padding: 10px;"
                                                                                />
                                                                            </div>
                                                                            <div>
                                                                                <button @click="copyToClipboard" class="btn btn-link mt-2">Copy to Clipboard</button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="tab-main-box" v-if="clinicStore.clinic.dns_records?.length" :class="callrail_installtion_editing ? 'tab-edit-active tab-main-active' : 'tab-disabled-active'">
                                                        <div class="tab-title-box d-flex align-items-center justify-content-between">
                                                            <div class="tab-title" @click.prevent="callrail_installtion_editing = true">
                                                                <h3>DNS Records</h3>
                                                            </div>

                                                            <div>
                                                                <button
                                                                    class="btn btn-link mt-2"
                                                                    :disabled="!isVerifiable"
                                                                    @click.prevent="verifyDomain"
                                                                >
                                                                    {{ domainVerificationStatus }}
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <div class="tab-sub-box">
                                                            <div class="d-lg-none d-flex align-items-center justify-content-between">
                                                                <a role="button" class="back-btn" @click="resetActiveTabs">
                                                                    <svg width="20" height="14" viewBox="0 0 20 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M19.4411 6.37497H2.04859L7.25172 1.19185C7.49609 0.947473 7.49609 0.551848 7.25172 0.308098C7.00734 0.0637231 6.61172 0.0637231 6.36797 0.308098L0.180469 6.49497C-0.0601562 6.7356 -0.0601562 7.1381 0.180469 7.37872L6.36797 13.5662C6.61234 13.8106 7.00797 13.8106 7.25172 13.5662C7.49609 13.3218 7.49609 12.9262 7.25172 12.6825L2.04859 7.62497H19.4411C19.7861 7.62497 20.0661 7.34497 20.0661 6.99997C20.0661 6.65497 19.7861 6.37497 19.4411 6.37497Z" fill="#514F5F" />
                                                                    </svg>
                                                                </a>
                                                            </div>
                                                            <div class="d-lg-none mobile-tab-title">
                                                                <h3>DNS Records</h3>
                                                            </div>
                                                            <div class="row gx-3">
                                                                <div class="col-md-12 col-lg-12">
                                                                    <div>
                                                                        <p><strong>DomainKeys Identified Mail (DKIM)</strong></p>
                                                                        <span>DKIM-signed messages help receiving mail servers validate that a message was not forged or altered in transit.
                                                                        You must complete the verification process with DKIM authentication by copying the following generated CNAME records to publish to your domain’s DNS provider.</span>
                                                                        <table class="table">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>Type</th>
                                                                                    <th>Name</th>
                                                                                    <th>Value</th>

                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                <tr v-for="(record, index) in dkimRecords" :key="index">
                                                                                    <td>{{ record.type }}</td>
                                                                                    <td>
                                                                                        <div class="input-group px-3 rounded-3 bg-light border">
                                                                                            <div class="d-flex input-group-append align-items-center justify-content-center pe-2 ">
                                                                                                <span @click="copyToClipboardDNS(record.name)" class="d-block text-primary" style="cursor: pointer;">
                                                                                                    <i class="fas fa-copy"></i>
                                                                                                </span>
                                                                                            </div>
                                                                                            <input type="text" class="form-control border-0 p-0 ps-2 text-truncate rounded-0 bg-light" v-model="record.name" />
                                                                                        </div>
                                                                                    </td>
                                                                                    <td>
                                                                                        <div class="input-group px-3 rounded-3 bg-light border">
                                                                                            <div class="d-flex input-group-append align-items-center justify-content-center pe-2">
                                                                                                <span @click="copyToClipboardDNS(record.value)" class="d-block text-primary" style="cursor: pointer;">
                                                                                                    <i class="fas fa-copy"></i>
                                                                                                </span>
                                                                                            </div>
                                                                                            <input type="text" class="form-control border-0 p-0 ps-2 text-truncate rounded-0 bg-light" v-model="record.value" />
                                                                                        </div>
                                                                                    </td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row gx-3" style="margin-top:30px;">
                                                                <div class="col-md-12 col-lg-12">
                                                                    <div>
                                                                        <p><strong>Domain-based Message Authentication, Reporting, and Conformance (DMARC)</strong></p>
                                                                        <span>DMARC specifies how email servers should handle messages that fail the authentication checks.</span>
                                                                        <table class="table">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>Type</th>
                                                                                <th>Name</th>
                                                                                <th>Value</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>

                                                                            <tr v-for="(record, index) in dmarcRecords" :key="index">
                                                                                <td>{{ record.type }}</td>
                                                                                <td>
                                                                                    <div class="input-group px-3 rounded-3 bg-light border">
                                                                                        <div class="d-flex input-group-append align-items-center justify-content-center pe-2">
                                                                                            <span @click="copyToClipboardDNS(record.name)" class="d-block text-primary" style="cursor: pointer;">
                                                                                                <i class="fas fa-copy"></i>
                                                                                            </span>
                                                                                        </div>
                                                                                        <input type="text" class="form-control border-0 p-0 ps-2 text-truncate rounded-0 bg-light" v-model="record.name" />
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="input-group px-3 rounded-3 bg-light border">
                                                                                        <div class="d-flex input-group-append align-items-center justify-content-center pe-2">
                                                                                            <span @click="copyToClipboardDNS(record.value)" class="d-block text-primary" style="cursor: pointer;">
                                                                                                <i class="fas fa-copy"></i>
                                                                                            </span>
                                                                                        </div>
                                                                                        <input type="text" class="form-control border-0 p-0 ps-2 text-truncate rounded-0 bg-light" v-model="record.value" />
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                            </div>-->

                        <!--/.tab-data-->
                        <!--                        <div
                                                    id="Nurturing"
                                                    v-if="clinicStore.clinic.nurture_automation == 'Yes' && currentPage === 'nurturing-templates'"
                                                >
                                                    <div class="tab-main-title d-lg-none">
                                                        <h5>Nurturing Templates</h5>
                                                    </div>
                                                    <div
                                                        class="tab-main-box"
                                                        :class="nurturing_template_editing ? 'tab-edit-active tab-main-active' : 'tab-disabled-active'"
                                                    >
                                                        <div class="tab-title-box d-flex align-items-center">
                                                            <div class="tab-title" @click.prevent="nurturing_template_editing = true">
                                                                <h3>Manage Nurturing Templates</h3>
                                                            </div>
                                                        </div>
                                                        <div class="tab-sub-box">
                                                            <div class="d-lg-none d-flex align-items-center">
                                                                <a role="button" class="back-btn" @click="resetActiveTabs()">
                                                                    <svg
                                                                        width="20"
                                                                        height="14"
                                                                        viewBox="0 0 20 14"
                                                                        fill="none"
                                                                        xmlns="http://www.w3.org/2000/svg"
                                                                    >
                                                                        <path
                                                                            fill-rule="evenodd"
                                                                            clip-rule="evenodd"
                                                                            d="M19.4411 6.37497H2.04859L7.25172 1.19185C7.49609 0.947473 7.49609 0.551848 7.25172 0.308098C7.00734 0.0637231 6.61172 0.0637231 6.36797 0.308098L0.180469 6.49497C-0.0601562 6.7356 -0.0601562 7.1381 0.180469 7.37872L6.36797 13.5662C6.61234 13.8106 7.00797 13.8106 7.25172 13.5662C7.49609 13.3218 7.49609 12.9262 7.25172 12.6825L2.04859 7.62497H19.4411C19.7861 7.62497 20.0661 7.34497 20.0661 6.99997C20.0661 6.65497 19.7861 6.37497 19.4411 6.37497Z"
                                                                            fill="#514F5F"
                                                                        />
                                                                    </svg>
                                                                </a>
                                                            </div>
                                                            <div class="d-lg-none mobile-tab-title">
                                                                <h3>Manage Nurturing</h3>
                                                            </div>
                                                            <div class="row gx-3">
                                                                <div class="col-md-12 col-lg-12">
                                                                    <div class="dashboard-box">
                                                                        &lt;!&ndash; <div class="dashboard-box-title d-flex align-items-center">
                                                  <h3>Nurturing</h3>
                                                </div> &ndash;&gt;
                                                                        <div class="dashboard-sub-box ">
                                                                            <table
                                                                                class="nurturing-table table table-dark table-striped table-bordered"
                                                                                style="border-color:#f0f4f8;">
                                                                                <thead>
                                                                                <tr>
                                                                                    <th scope="col">Day</th>
                                                                                    <th scope="col" width="40%">Text Template</th>
                                                                                    <th scope="col">Email Subject</th>
                                                                                    <th scope="col" style="width:40%">Email Template</th>
                                                                                </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                <tr v-if="clinicStore.nurturingTemplates.length>0"
                                                                                    v-for="template in clinicStore.nurturingTemplates">
                                                                                    <th scope="row">{{ template.dayinterval }}</th>
                                                                                    <td v-on:click="editNurturing('Text Template', template.text_template, template.dayinterval)">
                                                                                        <div class="nurturing-text-container"><span
                                                                                            class="nurture-edit-icon"><svg
                                                                                            class="feather feather-edit" fill="none" height="20"
                                                                                            stroke="#425BCF" stroke-linecap="round"
                                                                                            stroke-linejoin="round" stroke-width="2"
                                                                                            viewBox="0 0 24 24" width="24"
                                                                                            xmlns="http://www.w3.org/2000/svg"><path
                                                                                            d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path
                                                                                            d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></span>
                                                                                            <div v-html="htmlEntities(template.text_template)"></div>
                                                                                        </div>
                                                                                    </td>
                                                                                    <td v-on:click="editNurturing('Email Subject', template.email_subject, template.dayinterval)">
                                                                                        <div class="nurturing-text-container"><span
                                                                                            class="nurture-edit-icon"><svg
                                                                                            class="feather feather-edit" fill="none" height="20"
                                                                                            stroke="#425BCF" stroke-linecap="round"
                                                                                            stroke-linejoin="round" stroke-width="2"
                                                                                            viewBox="0 0 24 24" width="24"
                                                                                            xmlns="http://www.w3.org/2000/svg"><path
                                                                                            d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path
                                                                                            d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></span>
                                                                                            <div v-html="htmlEntities(template.email_subject)"></div>
                                                                                        </div>
                                                                                    </td>
                                                                                    <td v-on:click="editNurturing('Email Template', template.email_template, template.dayinterval)">
                                                                                        <div class="nurturing-text-container"><span
                                                                                            class="nurture-edit-icon"><svg
                                                                                            class="feather feather-edit" fill="none" height="20"
                                                                                            stroke="#425BCF" stroke-linecap="round"
                                                                                            stroke-linejoin="round" stroke-width="2"
                                                                                            viewBox="0 0 24 24" width="24"
                                                                                            xmlns="http://www.w3.org/2000/svg"><path
                                                                                            d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path
                                                                                            d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></span>
                                                                                            <div v-html="htmlEntities(template.email_template)"></div>
                                                                                        </div>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr v-else>There are no nurturing templates to display!</tr>
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>-->
                        <div
                            id="EmailTemplates"
                            v-if="currentPage==='email-templates'"
                        >
                            <div class="tab-main-title d-lg-none">
                                <h5>Email Templates</h5>
                            </div>
                            <div
                                class="tab-main-box" @click="emailTabClicked()"
                                :class="email_template_editing ? 'tab-edit-active tab-main-active' : 'tab-disabled-active'"
                            >
                                <div class="tab-title-box d-flex align-items-start mb-2">
                                    <div class="tab-title" @click.prevent="email_template_editing = true">
                                        <h3 class="mb-2">Edit Email Templates</h3>
                                        <p class="d-none d-lg-block">Choose a template to edit or create a new one. The
                                            template will be available
                                            when viewing emails.</p>
                                    </div>
                                    <div
                                        class="edit-option d-none d-lg-flex ms-auto"
                                    >
                                        <a
                                            style="width: 100%;white-space: nowrap"
                                            role="button"
                                            class="edit-input manage-edit general_details_editing"
                                            @click="addEmailTextTemplate('email')"
                                        >New Template
                                            <svg width="16" height="16" viewBox="0 0 21 21"
                                                 xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M12.4763 0.836293V9.03629H20.2563V12.6963H12.4763V20.9363H8.47631V12.6963H0.736306V9.03629H8.47631V0.836293H12.4763Z"/>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                                <div class="tab-sub-box mt-lg-0">
                                    <div
                                        class="d-lg-none d-sm-flex flex-column flex-sm-row justify-content-between align-items-center">
                                        <a role="button" class="back-btn me-auto" @click="resetActiveTabs()">
                                            <svg
                                                width="20"
                                                height="14"
                                                viewBox="0 0 20 14"
                                                fill="none"
                                                xmlns="http://www.w3.org/2000/svg"
                                            >
                                                <path
                                                    fill-rule="evenodd"
                                                    clip-rule="evenodd"
                                                    d="M19.4411 6.37497H2.04859L7.25172 1.19185C7.49609 0.947473 7.49609 0.551848 7.25172 0.308098C7.00734 0.0637231 6.61172 0.0637231 6.36797 0.308098L0.180469 6.49497C-0.0601562 6.7356 -0.0601562 7.1381 0.180469 7.37872L6.36797 13.5662C6.61234 13.8106 7.00797 13.8106 7.25172 13.5662C7.49609 13.3218 7.49609 12.9262 7.25172 12.6825L2.04859 7.62497H19.4411C19.7861 7.62497 20.0661 7.34497 20.0661 6.99997C20.0661 6.65497 19.7861 6.37497 19.4411 6.37497Z"
                                                    fill="#514F5F"
                                                />
                                            </svg>
                                        </a>
                                        <div
                                            class="my-1 edit-option ms-auto d-sm-flex align-items-center"
                                        >
                                            <a
                                                role="button"
                                                class="w-100 d-lg-none d-block edit-input manage-edit general_details_editing text-center"
                                                @click="addEmailTextTemplate('email')"
                                            >New Template
                                                <svg width="16" height="16" viewBox="0 0 21 21"
                                                     xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M12.4763 0.836293V9.03629H20.2563V12.6963H12.4763V20.9363H8.47631V12.6963H0.736306V9.03629H8.47631V0.836293H12.4763Z"/>
                                                </svg>
                                            </a>
                                            <a role="button" style="white-space: nowrap;"
                                               class=" w-100 my-2 d-none btn btn-primary px-4 ms-sm-3 align-items-center justify-content-center"
                                               @click="addEmailTextTemplate('email')">
                                                New Template
                                                <svg width="16" height="16" style="fill:#fff" class="ms-2"
                                                     viewBox="0 0 21 21" xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M12.4763 0.836293V9.03629H20.2563V12.6963H12.4763V20.9363H8.47631V12.6963H0.736306V9.03629H8.47631V0.836293H12.4763Z"/>
                                                </svg>
                                            </a>
                                        </div>

                                    </div>
                                    <div class="d-lg-none mobile-tab-title">
                                        <h3 class="mb-2">Edit Email Templates</h3>
                                        <p>Choose a template to edit or create a new one. The template will be available
                                            when viewing emails.</p>
                                    </div>
                                    <div class="row gx-3">
                                        <div class="col-md-12 col-lg-12">
                                            <div class="dashboard-box">
                                                <div class="dashboard-sub-box ">
                                                    <div class="row">
                                                        <div class="col-md-6 form-check-container check-tab-list">
                                                            <div class="form-check"
                                                                 v-for="emailTemplate in clinicStore.emailTemplates"
                                                                 :key="emailTemplate.id">
                                                                <input class="form-check-input" type="radio"
                                                                       name="subject" :value="emailTemplate"
                                                                       v-model="clinicStore.selectedEmailTemplate"
                                                                       :id="'emailTemplate'+emailTemplate.id">
                                                                <label class="form-check-label"
                                                                       :for="'emailTemplate'+emailTemplate.id">
                                                                    {{ emailTemplate.template_name }}
                                                                </label>
                                                            </div>
                                                            <h6 v-if="clinicStore.emailTemplates.length==0">Currently
                                                                there are no email templates to
                                                                display.</h6>
                                                        </div>
                                                        <div class="col-md-6 template-text-container px-4 py-3"
                                                             v-if="clinicStore.selectedEmailTemplate">
                                                            <div
                                                                class="edit-option d-flex ms-auto justify-content-end mb-3"
                                                            >
                                                                <a
                                                                    role="button"
                                                                    class="edit-input manage-edit general_details_editing"
                                                                    @click="editEmailTextTemplate()"
                                                                >Edit
                                                                    <svg
                                                                        width="18"
                                                                        height="18"
                                                                        viewBox="0 0 18 18"
                                                                        xmlns="http://www.w3.org/2000/svg"
                                                                    >
                                                                        <path
                                                                            d="M16 17.25C16 16.848 15.644 16.5 15.25 16.5C12.689 16.5 3.311 16.5 0.75 16.5C0.356 16.5 0 16.848 0 17.25C0 17.652 0.356 18 0.75 18H15.25C15.644 18 16 17.652 16 17.25ZM8.597 13.852L17.721 4.727C17.892 4.556 18 4.304 18 4.043C18 3.814 17.917 3.577 17.72 3.381L14.605 0.277C14.42 0.0919999 14.176 0 13.933 0C13.69 0 13.447 0.0919999 13.261 0.277L4.118 9.38C3.549 11.143 2.563 14.203 2.492 14.461C2.472 14.536 2.463 14.611 2.463 14.685C2.463 15.146 2.812 15.533 3.228 15.533C3.739 15.533 4.219 15.344 8.597 13.852ZM5.327 10.51L7.464 12.647L4.296 13.693L5.327 10.51ZM6.282 9.344L13.933 1.728L16.268 4.055L8.631 11.693L6.282 9.344Z"
                                                                        />
                                                                    </svg>
                                                                </a>
                                                                <button type="button" data-title="Delete Email Template"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#DeleteTemplateModal"
                                                                        class="delete-lead tooltip-ico">
                                                                    <svg fill="#6c757d" version="1.1" id="Capa_1"
                                                                         xmlns="http://www.w3.org/2000/svg"
                                                                         xmlns:xlink="http://www.w3.org/1999/xlink"
                                                                         width="18px" height="18px"
                                                                         viewBox="0 0 485 485" xml:space="preserve"
                                                                         stroke="#FFFFFF"><g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                                                        <g
                                                                            id="SVGRepo_tracerCarrier"
                                                                            stroke-linecap="round"
                                                                            stroke-linejoin="round"></g>
                                                                        <g
                                                                            id="SVGRepo_iconCarrier"> <g> <g> <rect
                                                                            x="67.224" width="350.535"
                                                                            height="71.81"></rect>
                                                                            <path
                                                                                d="M417.776,92.829H67.237V485h350.537V92.829H417.776z M165.402,431.447h-28.362V146.383h28.362V431.447z M256.689,431.447 h-28.363V146.383h28.363V431.447z M347.97,431.447h-28.361V146.383h28.361V431.447z"></path> </g> </g> </g></svg>
                                                                </button>
                                                            </div>
                                                            <div class="template-sub">
                                                                <label>Template Name: </label>
                                                                <span>{{
                                                                        clinicStore.selectedEmailTemplate.template_name
                                                                    }}</span>
                                                            </div>
                                                            <div class="template-sub">
                                                                <label>Subject Line: </label>
                                                                <span>{{
                                                                        clinicStore.selectedEmailTemplate.subject
                                                                    }}</span>
                                                            </div>
                                                            <div class="template-sub">
                                                                <label>Text: </label>
                                                            </div>
                                                            <div v-html="clinicStore.selectedEmailTemplate.body">

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div
                            id="TextTemplates"
                            v-if="currentPage === 'text-templates'"
                        >
                            <div class="tab-main-title d-lg-none">
                                <h5>Text Templates</h5>
                            </div>
                            <div
                                class="tab-main-box" @click="textTabClicked"
                                :class="text_template_editing ? 'tab-edit-active tab-main-active' : 'tab-disabled-active'"
                            >
                                <div class="tab-title-box d-flex align-items-start mb-2">
                                    <div class="tab-title" @click.prevent="text_template_editing = true">
                                        <h3 class="mb-2">Edit Text Templates</h3>
                                        <p class="d-none d-lg-block">Choose a template to edit or create a new one. The
                                            template will be available
                                            when viewing texts.</p>
                                    </div>
                                    <div
                                        class="edit-option d-none d-lg-flex ms-auto"
                                    >
                                        <a
                                            style="width: 100%; display: block; white-space: nowrap"
                                            role="button"
                                            class="edit-input manage-edit general_details_editing"
                                            @click="addEmailTextTemplate('text')"
                                        >New Template
                                            <svg width="16" height="16" viewBox="0 0 21 21"
                                                 xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M12.4763 0.836293V9.03629H20.2563V12.6963H12.4763V20.9363H8.47631V12.6963H0.736306V9.03629H8.47631V0.836293H12.4763Z"/>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                                <div class="tab-sub-box mt-lg-0">
                                    <div class="d-lg-none d-flex justify-content-between align-items-center">
                                        <a role="button" class="back-btn" @click="resetActiveTabs()">
                                            <svg
                                                width="20"
                                                height="14"
                                                viewBox="0 0 20 14"
                                                fill="none"
                                                xmlns="http://www.w3.org/2000/svg"
                                            >
                                                <path
                                                    fill-rule="evenodd"
                                                    clip-rule="evenodd"
                                                    d="M19.4411 6.37497H2.04859L7.25172 1.19185C7.49609 0.947473 7.49609 0.551848 7.25172 0.308098C7.00734 0.0637231 6.61172 0.0637231 6.36797 0.308098L0.180469 6.49497C-0.0601562 6.7356 -0.0601562 7.1381 0.180469 7.37872L6.36797 13.5662C6.61234 13.8106 7.00797 13.8106 7.25172 13.5662C7.49609 13.3218 7.49609 12.9262 7.25172 12.6825L2.04859 7.62497H19.4411C19.7861 7.62497 20.0661 7.34497 20.0661 6.99997C20.0661 6.65497 19.7861 6.37497 19.4411 6.37497Z"
                                                    fill="#514F5F"
                                                />
                                            </svg>
                                        </a>
                                        <div
                                            class="edit-option d-lg-none d-flex ms-auto"
                                        >
                                            <a
                                                style="width: 200px;"
                                                role="button"
                                                class="edit-input manage-edit general_details_editing"
                                                @click="addEmailTextTemplate('text')"
                                            >New Template
                                                <svg width="16" height="16" viewBox="0 0 21 21"
                                                     xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M12.4763 0.836293V9.03629H20.2563V12.6963H12.4763V20.9363H8.47631V12.6963H0.736306V9.03629H8.47631V0.836293H12.4763Z"/>
                                                </svg>
                                            </a>
                                        </div>
                                        <!--                                        <a role="button" style="white-space: nowrap;" class="w-100 my-2 d-lg-none d-flex btn btn-primary px-4 ms-sm-3 align-items-center justify-content-center" @click="addEmailTextTemplate('text')">
                                                                                    New Template
                                                                                    <svg width="16" height="16" style="fill:#fff" class="ms-2" viewBox="0 0 21 21" xmlns="http://www.w3.org/2000/svg">
                                                                                        <path d="M12.4763 0.836293V9.03629H20.2563V12.6963H12.4763V20.9363H8.47631V12.6963H0.736306V9.03629H8.47631V0.836293H12.4763Z"/>
                                                                                    </svg>
                                                                                </a>-->
                                    </div>
                                    <div class="d-lg-none mobile-tab-title">
                                        <h3 class="mb-2">Edit Text Templates</h3>
                                        <p>Choose a template to edit or create a new one. The template will be available
                                            when viewing texts.</p>
                                    </div>

                                    <div class="row gx-3">
                                        <div class="col-md-12 col-lg-12">
                                            <div class="dashboard-box">
                                                <div class="dashboard-sub-box ">
                                                    <div class="row">
                                                        <div class="col-md-6 form-check-container check-tab-list">
                                                            <div class="form-check"
                                                                 v-for="template in clinicStore.textTemplates"
                                                                 :key="template.id">
                                                                <input class="form-check-input" type="radio"
                                                                       name="subject" :value="template"
                                                                       v-model="clinicStore.selectedTextTemplate"
                                                                       :id="'textTemplate'+template.id">
                                                                <label class="form-check-label"
                                                                       :for="'textTemplate'+template.id">
                                                                    {{ template.template_name }}
                                                                </label>
                                                            </div>
                                                            <h6 v-if="clinicStore.textTemplates.length==0">Currently
                                                                there are no text templates to
                                                                display.</h6>
                                                        </div>
                                                        <div class="col-md-6 template-text-container px-4 py-3"
                                                             v-if="clinicStore.selectedTextTemplate">
                                                            <div
                                                                class="edit-option d-flex ms-auto justify-content-end mb-3"
                                                            >
                                                                <a
                                                                    role="button"
                                                                    class="edit-input manage-edit general_details_editing"
                                                                    @click="editEmailTextTemplate('text')"
                                                                >Edit
                                                                    <svg
                                                                        width="18"
                                                                        height="18"
                                                                        viewBox="0 0 18 18"
                                                                        xmlns="http://www.w3.org/2000/svg"
                                                                    >
                                                                        <path
                                                                            d="M16 17.25C16 16.848 15.644 16.5 15.25 16.5C12.689 16.5 3.311 16.5 0.75 16.5C0.356 16.5 0 16.848 0 17.25C0 17.652 0.356 18 0.75 18H15.25C15.644 18 16 17.652 16 17.25ZM8.597 13.852L17.721 4.727C17.892 4.556 18 4.304 18 4.043C18 3.814 17.917 3.577 17.72 3.381L14.605 0.277C14.42 0.0919999 14.176 0 13.933 0C13.69 0 13.447 0.0919999 13.261 0.277L4.118 9.38C3.549 11.143 2.563 14.203 2.492 14.461C2.472 14.536 2.463 14.611 2.463 14.685C2.463 15.146 2.812 15.533 3.228 15.533C3.739 15.533 4.219 15.344 8.597 13.852ZM5.327 10.51L7.464 12.647L4.296 13.693L5.327 10.51ZM6.282 9.344L13.933 1.728L16.268 4.055L8.631 11.693L6.282 9.344Z"
                                                                        />
                                                                    </svg>
                                                                </a>
                                                                <button type="button" data-title="Delete Text Template"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#DeleteTemplateModal"
                                                                        class="delete-lead tooltip-ico">
                                                                    <svg fill="#6c757d" version="1.1" id="Capa_1"
                                                                         xmlns="http://www.w3.org/2000/svg"
                                                                         xmlns:xlink="http://www.w3.org/1999/xlink"
                                                                         width="18px" height="18px"
                                                                         viewBox="0 0 485 485" xml:space="preserve"
                                                                         stroke="#FFFFFF"><g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                                                        <g
                                                                            id="SVGRepo_tracerCarrier"
                                                                            stroke-linecap="round"
                                                                            stroke-linejoin="round"></g>
                                                                        <g
                                                                            id="SVGRepo_iconCarrier"> <g> <g> <rect
                                                                            x="67.224" width="350.535"
                                                                            height="71.81"></rect>
                                                                            <path
                                                                                d="M417.776,92.829H67.237V485h350.537V92.829H417.776z M165.402,431.447h-28.362V146.383h28.362V431.447z M256.689,431.447 h-28.363V146.383h28.363V431.447z M347.97,431.447h-28.361V146.383h28.361V431.447z"></path> </g> </g> </g></svg>
                                                                </button>
                                                            </div>
                                                            <div class="template-sub">
                                                                <label>Template Name: </label>
                                                                <span>{{
                                                                        clinicStore.selectedTextTemplate.template_name
                                                                    }}</span>
                                                            </div>
                                                            <div class="template-sub">
                                                                <label>Text: </label>
                                                            </div>
                                                            <div style="white-space: pre-line">
                                                                {{ clinicStore.selectedTextTemplate.body }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-data" v-if="currentPage === 'appointment-calendar'">
                            <ul class="nav nav-tabs" id="myTab2" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <a
                                        class="nav-link active"
                                        id="Scheduler-tab"
                                        data-bs-toggle="tab"
                                        data-bs-target="#Scheduler"
                                        type="button"
                                        role="tab"
                                        aria-controls="Scheduler"
                                        aria-selected="true"
                                        @click="refreshCalendar()"
                                    >Setup Calendar</a
                                    >
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a
                                        class="nav-link"
                                        id="Plugin-tab"
                                        data-bs-toggle="tab"
                                        data-bs-target="#Plugin"
                                        type="button"
                                        role="tab"
                                        aria-controls="Plugin"
                                        aria-selected="false"
                                    >Download Plugin</a
                                    >
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a
                                        class="nav-link"
                                        id="Template-tab"
                                        data-bs-toggle="tab"
                                        data-bs-target="#Template"
                                        type="button"
                                        role="tab"
                                        aria-controls="Template"
                                        aria-selected="false"
                                    >Templates</a
                                    >
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div
                                    id="Scheduler" class="tab-pane fade show active" role="tabpanel"
                                    aria-labelledby="Scheduler-tab"
                                >
                                    <div class="tab-main-title d-lg-none">
                                        <h5>Appointment Calendar</h5>
                                    </div>
                                    <div
                                        class="tab-main-box" @click="appointmentTabClicked()"
                                        :class="
                                  calendar_details_editing ? 'tab-edit-active tab-main-active' : 'tab-disabled-active'
                                "
                                    >
                                        <div class="tab-title-box d-flex align-items-center">
                                            <div class="tab-title" @click.prevent="calendar_details_editing = true">
                                                <h3>Appointment Calendar</h3>
                                            </div>
                                            <div
                                                class="edit-option d-none d-lg-flex ms-auto"
                                                :class="calendar_details_editing ? 'edit-active' : ''"
                                            >
                                                <a role="button" class="save-input manage-cancel"
                                                   v-show="calendar_details_editing"
                                                   @click="calendar_details_editing=false">Cancel</a>
                                                <a
                                                    v-if="!calendar_details_editing"
                                                    role="button"
                                                    @click.prevent="calendar_details_editing = true"
                                                    class="edit-input manage-edit"
                                                >Edit
                                                    <svg
                                                        width="18"
                                                        height="18"
                                                        viewBox="0 0 18 18"
                                                        xmlns="http://www.w3.org/2000/svg"
                                                    >
                                                        <path
                                                            d="M16 17.25C16 16.848 15.644 16.5 15.25 16.5C12.689 16.5 3.311 16.5 0.75 16.5C0.356 16.5 0 16.848 0 17.25C0 17.652 0.356 18 0.75 18H15.25C15.644 18 16 17.652 16 17.25ZM8.597 13.852L17.721 4.727C17.892 4.556 18 4.304 18 4.043C18 3.814 17.917 3.577 17.72 3.381L14.605 0.277C14.42 0.0919999 14.176 0 13.933 0C13.69 0 13.447 0.0919999 13.261 0.277L4.118 9.38C3.549 11.143 2.563 14.203 2.492 14.461C2.472 14.536 2.463 14.611 2.463 14.685C2.463 15.146 2.812 15.533 3.228 15.533C3.739 15.533 4.219 15.344 8.597 13.852ZM5.327 10.51L7.464 12.647L4.296 13.693L5.327 10.51ZM6.282 9.344L13.933 1.728L16.268 4.055L8.631 11.693L6.282 9.344Z"
                                                        />
                                                    </svg>
                                                </a>
                                                <a
                                                    v-else
                                                    role="button"
                                                    @click.prevent="appointment_editing_save('detail')"
                                                    class="save-input manage-save"
                                                >Save</a
                                                >
                                            </div>
                                        </div>
                                        <div class="tab-sub-box">
                                            <div class="d-lg-none d-flex align-items-center">
                                                <a role="button" class="back-btn" @click="resetActiveTabs()">
                                                    <svg
                                                        width="20"
                                                        height="14"
                                                        viewBox="0 0 20 14"
                                                        fill="none"
                                                        xmlns="http://www.w3.org/2000/svg"
                                                    >
                                                        <path
                                                            fill-rule="evenodd"
                                                            clip-rule="evenodd"
                                                            d="M19.4411 6.37497H2.04859L7.25172 1.19185C7.49609 0.947473 7.49609 0.551848 7.25172 0.308098C7.00734 0.0637231 6.61172 0.0637231 6.36797 0.308098L0.180469 6.49497C-0.0601562 6.7356 -0.0601562 7.1381 0.180469 7.37872L6.36797 13.5662C6.61234 13.8106 7.00797 13.8106 7.25172 13.5662C7.49609 13.3218 7.49609 12.9262 7.25172 12.6825L2.04859 7.62497H19.4411C19.7861 7.62497 20.0661 7.34497 20.0661 6.99997C20.0661 6.65497 19.7861 6.37497 19.4411 6.37497Z"
                                                            fill="#514F5F"
                                                        />
                                                    </svg>
                                                </a>
                                                <a
                                                    role="button"
                                                    class="save-input btn px-4 btn-primary tab-save"
                                                    @click.prevent="appointment_editing_save('detail')"
                                                >Save</a
                                                >
                                            </div>
                                            <div class="d-lg-none mobile-tab-title">
                                                <h3>Appointment Calendar</h3>
                                            </div>
                                            <div class="row gx-3">
                                                <div class="col-12">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="form-group flex-column">
                                                                <h5 class="d-lg-none">Calendar Type</h5>
                                                                <label class="d-none d-lg-block">Calendar Type</label>
                                                                <div class="check-value">
                                                                    <p>
                                                                        {{
                                                                            appointmentStore.calendar_data.calendar_type
                                                                                ? appointmentStore.calendar_data.calendar_type
                                                                                : ""
                                                                        }}
                                                                    </p>
                                                                </div>
                                                                <div class="check-group">
                                                                    <div class="d-flex flex-wrap">
                                                                        <div class="btn-checkbox mb-2 me-2">
                                                                            <input
                                                                                type="radio"
                                                                                class="btn-check"
                                                                                id="InOfficeAppointment"
                                                                                name="InOfficeAppointment"
                                                                                value="In-Office Appointment"
                                                                                v-model="appointmentStore.calendar_data.calendar_type"
                                                                            />
                                                                            <label
                                                                                class="btn btn-outline-primary"
                                                                                for="InOfficeAppointment"
                                                                            >In-Office Appointment</label
                                                                            ><br/>
                                                                        </div>
                                                                        <div class="btn-checkbox mb-2 me-2">
                                                                            <input
                                                                                type="radio"
                                                                                class="btn-check"
                                                                                id="PhoneConsultation"
                                                                                name="PhoneConsultation"
                                                                                value="Phone Call Appointment"
                                                                                v-model="appointmentStore.calendar_data.calendar_type"
                                                                            />
                                                                            <label
                                                                                class="btn btn-outline-primary"
                                                                                for="PhoneConsultation"
                                                                            >Phone Call Appointment</label
                                                                            ><br/>
                                                                        </div>
                                                                    </div>
                                                                    <span
                                                                        class="d-block text-danger mt-1"
                                                                        v-text="appointmentStore.errors.calendar_type"
                                                                    ></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-12">
                                                            <div class="form-group flex-column">
                                                                <h5 class="d-lg-none">Patients</h5>
                                                                <label class="d-none d-lg-block">Patients</label>
                                                                <div class="check-value">
                                                                    <p>
                                                                        {{
                                                                            appointmentStore.calendar_data.patient_types
                                                                                ? appointmentStore.calendar_data.patient_types.join(", ")
                                                                                : ""
                                                                        }}
                                                                    </p>
                                                                </div>
                                                                <div class="check-group">
                                                                    <div class="d-flex flex-wrap">
                                                                        <div class="btn-checkbox mb-2 me-2">
                                                                            <input
                                                                                type="checkbox"
                                                                                class="btn-check"
                                                                                id="NewPatient2"
                                                                                name="PatientTypes"
                                                                                value="New Patient"
                                                                                v-model="appointmentStore.calendar_data.patient_types"
                                                                            />
                                                                            <label
                                                                                class="btn btn-outline-primary"
                                                                                for="NewPatient2"
                                                                            >New Patient</label
                                                                            ><br/>
                                                                        </div>
                                                                        <div class="btn-checkbox mb-2 me-2">
                                                                            <input
                                                                                type="checkbox"
                                                                                class="btn-check"
                                                                                id="ReturningPatient"
                                                                                name="PatientTypes"
                                                                                value="Existing Patient"
                                                                                v-model="appointmentStore.calendar_data.patient_types"
                                                                            />
                                                                            <label
                                                                                class="btn btn-outline-primary"
                                                                                for="ReturningPatient"
                                                                            >Existing Patient</label
                                                                            ><br/>
                                                                        </div>
                                                                    </div>
                                                                    <span
                                                                        class="d-block text-danger mt-1"
                                                                        v-text="appointmentStore.errors.patient_types"
                                                                    ></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-12">
                                                            <div class="form-group flex-column">
                                                                <h5 class="d-lg-none">Services</h5>
                                                                <label class="d-none d-lg-block">Services</label>
                                                                <div class="check-value">
                                                                    <p>
                                                                        {{
                                                                            appointmentStore.calendar_data.service_types
                                                                                ? getSelectedServiceTypes()/*appointmentStore.calendar_data.service_types.join(", ")*/
                                                                                : ""
                                                                        }}
                                                                    </p>
                                                                </div>
                                                                <div class="check-group">
                                                                    <div class="d-flex flex-wrap">
                                                                        <div class="btn-checkbox mb-2 me-2"
                                                                             v-for="type in appointmentStore.service_types">
                                                                            <input
                                                                                type="checkbox"
                                                                                class="btn-check"
                                                                                :id="type.id"
                                                                                name="Services"
                                                                                :value="type.id"
                                                                                v-model="appointmentStore.calendar_data.service_types"
                                                                            />
                                                                            <label
                                                                                class="btn btn-outline-primary"
                                                                                :for="type.id"
                                                                            >{{ type.name }}</label
                                                                            ><br/>
                                                                        </div>
                                                                    </div>
                                                                    <span
                                                                        class="d-block text-danger mt-1"
                                                                        v-text="appointmentStore.errors.service_types"
                                                                    ></span>
                                                                </div>
                                                            </div>
                                                            <button v-if="calendar_details_editing"
                                                                    class="btn btn-primary"
                                                                    @click="toggleCreateService">Add Service
                                                            </button>
                                                            <div class=" mt-3" v-if="showCreateService">
                                                                <div class="d-sm-flex">
                                                                    <input
                                                                        class="form-control"
                                                                        v-model="newServiceName"
                                                                        type="text"
                                                                        placeholder="Enter service name"
                                                                    />
                                                                    <button
                                                                        class="btn btn-primary d-block ms-sm-3 mt-2 mt-sm-0"
                                                                        @click="createService">Save
                                                                    </button>
                                                                </div>
                                                                <p v-if="errorMessage" class="mt-1 error text-danger">
                                                                    {{ errorMessage }}</p>
                                                                <p v-if="successMessage" class="mt-1 text-success">
                                                                    {{ successMessage }}</p>
                                                            </div>
                                                        </div>
                                                        <div class="col-12 mt-3">
                                                            <div class="form-group flex-column">
                                                                <h5 class="d-lg-none">Notification Email (CC)</h5>
                                                                <label class="d-none d-lg-block">Notification Email
                                                                    (CC)</label>
                                                                <input
                                                                    type="email"
                                                                    class="form-control"
                                                                    v-model="clinicStore.clinic.emails_for_scheduling"
                                                                    placeholder="Enter notification emails"
                                                                />
                                                            </div>
                                                            <span
                                                                class="d-block text-danger mt-1"
                                                                v-text="appointmentStore.errors.emails_for_scheduling"
                                                            ></span>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-main-title d-lg-none">
                                        <h5>Appointment Schedule</h5>
                                    </div>
                                    <div
                                        class="tab-main-box"
                                        :class="
                                  calendar_schedule_editing ? 'tab-edit-active tab-main-active' : 'tab-disabled-active'
                                "
                                    >
                                        <div class="tab-title-box d-flex align-items-center">
                                            <div class="tab-title" @click.prevent="calendar_schedule_editing = true">
                                                <h3>Appointment Schedule</h3>
                                            </div>
                                            <div
                                                class="edit-option d-none d-lg-flex ms-auto"
                                                :class="calendar_schedule_editing ? 'edit-active' : ''"
                                            >
                                                <a role="button" class="save-input manage-cancel"
                                                   v-show="calendar_schedule_editing"
                                                   @click="calendar_schedule_editing=false">Cancel</a>
                                                <a
                                                    v-if="!calendar_schedule_editing"
                                                    role="button"
                                                    @click.prevent="calendar_schedule_editing = true"
                                                    class="edit-input manage-edit"
                                                >Edit
                                                    <svg
                                                        width="18"
                                                        height="18"
                                                        viewBox="0 0 18 18"
                                                        xmlns="http://www.w3.org/2000/svg"
                                                    >
                                                        <path
                                                            d="M16 17.25C16 16.848 15.644 16.5 15.25 16.5C12.689 16.5 3.311 16.5 0.75 16.5C0.356 16.5 0 16.848 0 17.25C0 17.652 0.356 18 0.75 18H15.25C15.644 18 16 17.652 16 17.25ZM8.597 13.852L17.721 4.727C17.892 4.556 18 4.304 18 4.043C18 3.814 17.917 3.577 17.72 3.381L14.605 0.277C14.42 0.0919999 14.176 0 13.933 0C13.69 0 13.447 0.0919999 13.261 0.277L4.118 9.38C3.549 11.143 2.563 14.203 2.492 14.461C2.472 14.536 2.463 14.611 2.463 14.685C2.463 15.146 2.812 15.533 3.228 15.533C3.739 15.533 4.219 15.344 8.597 13.852ZM5.327 10.51L7.464 12.647L4.296 13.693L5.327 10.51ZM6.282 9.344L13.933 1.728L16.268 4.055L8.631 11.693L6.282 9.344Z"
                                                        />
                                                    </svg>
                                                </a>
                                                <a
                                                    v-else
                                                    role="button"
                                                    @click.prevent="appointment_editing_save('schedule')"
                                                    class="save-input manage-save"
                                                >Save</a
                                                >
                                            </div>
                                        </div>
                                        <div class="tab-sub-box">
                                            <div class="d-lg-none d-flex align-items-center">
                                                <a role="button" class="back-btn" @click="resetActiveTabs()">
                                                    <svg
                                                        width="20"
                                                        height="14"
                                                        viewBox="0 0 20 14"
                                                        fill="none"
                                                        xmlns="http://www.w3.org/2000/svg"
                                                    >
                                                        <path
                                                            fill-rule="evenodd"
                                                            clip-rule="evenodd"
                                                            d="M19.4411 6.37497H2.04859L7.25172 1.19185C7.49609 0.947473 7.49609 0.551848 7.25172 0.308098C7.00734 0.0637231 6.61172 0.0637231 6.36797 0.308098L0.180469 6.49497C-0.0601562 6.7356 -0.0601562 7.1381 0.180469 7.37872L6.36797 13.5662C6.61234 13.8106 7.00797 13.8106 7.25172 13.5662C7.49609 13.3218 7.49609 12.9262 7.25172 12.6825L2.04859 7.62497H19.4411C19.7861 7.62497 20.0661 7.34497 20.0661 6.99997C20.0661 6.65497 19.7861 6.37497 19.4411 6.37497Z"
                                                            fill="#514F5F"
                                                        />
                                                    </svg>
                                                </a>
                                                <a
                                                    role="button"
                                                    class="save-input btn px-4 btn-primary tab-save"
                                                    @click.prevent="appointment_editing_save('schedule')"
                                                >Save</a
                                                >
                                            </div>
                                            <div class="d-lg-none mobile-tab-title">
                                                <h3>Appointment Schedule</h3>
                                            </div>
                                            <div class="row gx-3">
                                                <div class="col-md-12 col-lg-12">
                                                    <div class="row">
                                                        <div class="col-6">
                                                            <div class="form-group">
                                                                <span
                                                                    class="d-block text-danger mt-1"
                                                                    v-text="clinicStore.errors.repeat_type"
                                                                ></span>
                                                                <select
                                                                    v-model="appointmentStore.calendar_data.repeat_type"
                                                                    class="form-control form-select"
                                                                    :class="appointmentStore.calendar_data.repeat_type ? 'filled' : ''"
                                                                >
                                                                    <option selected disabled readonly value="">Select
                                                                        Repeat
                                                                        Type
                                                                    </option>
                                                                    <option value="Repeat weekly">Repeat weekly</option>
                                                                    <option value="Does not repeat">Does not repeat
                                                                    </option>
                                                                </select>
                                                                <p style="font-size: 14px; color:gray">Set when you are
                                                                    regularly available for appointments.</p>
                                                                <label>Availability</label>
                                                            </div>
                                                            <!--/.form-group-->
                                                        </div>
                                                        <div class="col-6">
                                                            <div class="form-group">
                                                                <span
                                                                    class="d-block text-danger mt-1"
                                                                    v-text="clinicStore.errors.duration"
                                                                ></span>
                                                                <select
                                                                    id="duration-select"
                                                                    v-model="appointmentStore.calendar_data.duration"
                                                                    class="form-control form-select"
                                                                    :class="appointmentStore.calendar_data.duration ? 'filled' : ''"
                                                                    @change="buildAvailableTimes()"
                                                                >
                                                                    <option selected disabled readonly value="">Select
                                                                        Duration
                                                                    </option>
                                                                    <option value="30">30 Minutes</option>
                                                                    <option value="45">45 Minutes</option>
                                                                    <option value="60">1 Hour</option>
                                                                    <option value="custom">Custom</option>
                                                                </select>
                                                                <p style="font-size: 14px; color:gray">How long should
                                                                    each
                                                                    appointment last?</p>
                                                                <label>Appointment Duration</label>
                                                            </div>
                                                            <!--/.form-group-->
                                                        </div>
                                                        <div class="col-12"
                                                             v-if="appointmentStore.calendar_data.repeat_type=='Repeat weekly'">
                                                            <div v-if="scheduling_errors.length>0"
                                                                 class="alert alert-danger alert-dismissible fade show"
                                                                 role="alert">
                                                                {{ scheduling_errors.toString() }}
                                                                <button type="button" class="btn-close"
                                                                        data-bs-dismiss="alert"
                                                                        aria-label="Close"
                                                                        @click="scheduling_errors = []"></button>
                                                            </div>
                                                            <div class="appointment-box">
                                                                <table class="table">
                                                                    <tbody>
                                                                    <tr v-for="(day, index) in ['sun', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat']">
                                                                        <!--                                                                {{appointmentStore.calendar_data.availabilities[day+'_start'] + ' | ' + appointmentStore.calendar_data.availabilities[day+'_end']}}-->
                                                                        <td>{{
                                                                                day.charAt(0).toUpperCase() + day.slice(1)
                                                                            }}
                                                                        </td>
                                                                        <td>
                                                                            <div
                                                                                v-if="appointmentStore.calendar_data.availabilities[day+'_start'] && appointmentStore.calendar_data.availabilities[day+'_end']">
                                                                                <div class="d-inline-block w-50">
                                                                                    <select
                                                                                        v-for="(time, index) in appointmentStore.calendar_data.availabilities[day+'_start']"
                                                                                        class="form-control form-select d-block"
                                                                                        :id="'start-avail'+day+index"
                                                                                        @change="dayStartChange($event, day, index)">
                                                                                        <option
                                                                                            v-for="timeLabel in this.calendar_times_start"
                                                                                            :value="timeLabel"
                                                                                            :selected="time==timeLabel">
                                                                                            {{ timeLabel }}
                                                                                        </option>
                                                                                    </select>
                                                                                </div>
                                                                                <div class="d-inline-block w-50">
                                                                                    <select
                                                                                        v-for="(time, index) in appointmentStore.calendar_data.availabilities[day+'_end']"
                                                                                        class="form-control form-select d-block"
                                                                                        :id="'end-avail'+day+index"
                                                                                        @change="dayEndChange($event, day, index)">
                                                                                        <option
                                                                                            v-for="timeLabel in this.calendar_times_end"
                                                                                            :value="timeLabel"
                                                                                            :selected="time==timeLabel">
                                                                                            {{ timeLabel }}
                                                                                        </option>
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                            <span v-else>Unavailable</span>
                                                                        </td>
                                                                        <td style="width: 150px; text-align: center;">
                                                                            <a role="button" class="mx-2 tooltip-ico"
                                                                               data-title="Remove"
                                                                               @click="removeAvailability(day)"
                                                                               v-show="appointmentStore.calendar_data.availabilities[day+'_start'] && appointmentStore.calendar_data.availabilities[day+'_end']">
                                                                                <svg width="24px" height="24px"
                                                                                     viewBox="0 0 24 24"
                                                                                     xmlns="http://www.w3.org/2000/svg"
                                                                                     fill="#66686c" stroke="#66686c">
                                                                                    <g id="SVGRepo_bgCarrier"
                                                                                       stroke-width="0"></g>
                                                                                    <g id="SVGRepo_tracerCarrier"
                                                                                       stroke-linecap="round"
                                                                                       stroke-linejoin="round"></g>
                                                                                    <g id="SVGRepo_iconCarrier">
                                                                                        <path fill="none"
                                                                                              stroke="#66686c"
                                                                                              stroke-width="2"
                                                                                              d="M12,22 C17.5228475,22 22,17.5228475 22,12 C22,6.4771525 17.5228475,2 12,2 C6.4771525,2 2,6.4771525 2,12 C2,17.5228475 6.4771525,22 12,22 Z M5,5 L19,19"></path>
                                                                                    </g>
                                                                                </svg>
                                                                            </a>
                                                                            <a role="button" class="mx-2 tooltip-ico"
                                                                               data-title="Add"
                                                                               @click="addAvailability(day)">
                                                                                <svg width="24px" height="24px"
                                                                                     viewBox="0 0 24 24"
                                                                                     xmlns="http://www.w3.org/2000/svg"
                                                                                     fill="#66686c">
                                                                                    <g id="SVGRepo_bgCarrier"
                                                                                       stroke-width="0"></g>
                                                                                    <g id="SVGRepo_tracerCarrier"
                                                                                       stroke-linecap="round"
                                                                                       stroke-linejoin="round"></g>
                                                                                    <g id="SVGRepo_iconCarrier">
                                                                                        <path fill="none"
                                                                                              stroke="#66686c"
                                                                                              stroke-width="2"
                                                                                              d="M12,22 C17.5228475,22 22,17.5228475 22,12 C22,6.4771525 17.5228475,2 12,2 C6.4771525,2 2,6.4771525 2,12 C2,17.5228475 6.4771525,22 12,22 Z M12,18 L12,6 M6,12 L18,12"></path>
                                                                                    </g>
                                                                                </svg>
                                                                            </a>
                                                                            <a role="button" class="mx-2 tooltip-ico"
                                                                               data-title="Copy"
                                                                               @click="copyAvailability(day)"
                                                                               v-show="appointmentStore.calendar_data.availabilities[day+'_start'] && appointmentStore.calendar_data.availabilities[day+'_end']">
                                                                                <svg width="24px" height="24px"
                                                                                     viewBox="0 0 24 24" fill="none"
                                                                                     xmlns="http://www.w3.org/2000/svg"
                                                                                     stroke="#66686c">
                                                                                    <g id="SVGRepo_bgCarrier"
                                                                                       stroke-width="0"></g>
                                                                                    <g id="SVGRepo_tracerCarrier"
                                                                                       stroke-linecap="round"
                                                                                       stroke-linejoin="round"></g>
                                                                                    <g id="SVGRepo_iconCarrier">
                                                                                        <path
                                                                                            d="M3 16V4C3 2.89543 3.89543 2 5 2H15M9 22H18C19.1046 22 20 21.1046 20 20V8C20 6.89543 19.1046 6 18 6H9C7.89543 6 7 6.89543 7 8V20C7 21.1046 7.89543 22 9 22Z"
                                                                                            stroke="#66686c"
                                                                                            stroke-width="2"
                                                                                            stroke-linecap="round"
                                                                                            stroke-linejoin="round"></path>
                                                                                    </g>
                                                                                </svg>
                                                                            </a>
                                                                        </td>
                                                                    </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                        <div class="col-12 mb-2"
                                                             v-if="appointmentStore.calendar_data.repeat_type=='Does not repeat'">
                                                            <div class="appointment-box">
                                                                <table class="table" id="fixed-table">
                                                                    <tbody>
                                                                    <tr v-for="(day, i) in appointmentStore.calendar_data.fixed_appointment_dates">
                                                                        <td><input type="text" readonly
                                                                                   class="form-control"
                                                                                   name="fixed-date"/></td>
                                                                        <td>
                                                                            <div
                                                                                v-if="appointmentStore.calendar_data.fixed_appointment_dates[i].start_times && appointmentStore.calendar_data.fixed_appointment_dates[i].end_times">
                                                                                <div class="d-inline-block w-50">
                                                                                    <select
                                                                                        v-for="(time, index) in appointmentStore.calendar_data.fixed_appointment_dates[i].start_times"
                                                                                        class="form-control form-select d-block"
                                                                                        :id="'start-select'+i+index"
                                                                                        @change="fixedDayStartChange($event, i, index)">
                                                                                        <option
                                                                                            v-for="timeLabel in this.calendar_times_start"
                                                                                            :value="timeLabel"
                                                                                            :selected="time==timeLabel">
                                                                                            {{ timeLabel }}
                                                                                        </option>
                                                                                    </select>
                                                                                </div>
                                                                                <div class="d-inline-block w-50">
                                                                                    <select
                                                                                        v-for="(time, index) in appointmentStore.calendar_data.fixed_appointment_dates[i].end_times"
                                                                                        class="form-control form-select d-block"
                                                                                        :id="'end-select'+i+index"
                                                                                        @change="fixedDayEndChange($event, i, index)">
                                                                                        <option
                                                                                            v-for="timeLabel in this.calendar_times_end"
                                                                                            :value="timeLabel"
                                                                                            :selected="time==timeLabel">
                                                                                            {{ timeLabel }}
                                                                                        </option>
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                        <td style="width:100px; text-align: center;">
                                                                            <a role="button" class="mx-2 tooltip-ico"
                                                                               data-title="Remove"
                                                                               @click="removeSameDayFixedDateTime(i)">
                                                                                <svg width="24px" height="24px"
                                                                                     viewBox="0 0 24 24"
                                                                                     xmlns="http://www.w3.org/2000/svg"
                                                                                     fill="#66686c" stroke="#66686c">
                                                                                    <g id="SVGRepo_bgCarrier"
                                                                                       stroke-width="0"></g>
                                                                                    <g id="SVGRepo_tracerCarrier"
                                                                                       stroke-linecap="round"
                                                                                       stroke-linejoin="round"></g>
                                                                                    <g id="SVGRepo_iconCarrier">
                                                                                        <path fill="none"
                                                                                              stroke="#66686c"
                                                                                              stroke-width="2"
                                                                                              d="M12,22 C17.5228475,22 22,17.5228475 22,12 C22,6.4771525 17.5228475,2 12,2 C6.4771525,2 2,6.4771525 2,12 C2,17.5228475 6.4771525,22 12,22 Z M5,5 L19,19"></path>
                                                                                    </g>
                                                                                </svg>
                                                                            </a>
                                                                            <a role="button" class="mx-2 tooltip-ico"
                                                                               data-title="Add"
                                                                               @click="addSameDayFixedDateTime(i)">
                                                                                <svg width="24px" height="24px"
                                                                                     viewBox="0 0 24 24"
                                                                                     xmlns="http://www.w3.org/2000/svg"
                                                                                     fill="#66686c">
                                                                                    <g id="SVGRepo_bgCarrier"
                                                                                       stroke-width="0"></g>
                                                                                    <g id="SVGRepo_tracerCarrier"
                                                                                       stroke-linecap="round"
                                                                                       stroke-linejoin="round"></g>
                                                                                    <g id="SVGRepo_iconCarrier">
                                                                                        <path fill="none"
                                                                                              stroke="#66686c"
                                                                                              stroke-width="2"
                                                                                              d="M12,22 C17.5228475,22 22,17.5228475 22,12 C22,6.4771525 17.5228475,2 12,2 C6.4771525,2 2,6.4771525 2,12 C2,17.5228475 6.4771525,22 12,22 Z M12,18 L12,6 M6,12 L18,12"></path>
                                                                                    </g>
                                                                                </svg>
                                                                            </a>
                                                                        </td>
                                                                    </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                            <span
                                                                class="d-block text-danger mt-1"
                                                                v-text="fixedError"
                                                            ></span>
                                                            <button class="btn btn-link" role="button"
                                                                    @click="addFixedDateTime">Add Date
                                                            </button>
                                                        </div>
                                                        <div class="col-12"
                                                             v-if="appointmentStore.calendar_data.repeat_type=='Repeat weekly'">
                                                            <div class="form-group mb-0">
                                                                <p style="font-size: 14px; color:gray">Set when you are
                                                                    unavailable for appointments.</p>
                                                                <label>Appointment Unavailability</label>
                                                            </div>
                                                            <div class="appointment-box">
                                                                <table class="table" id="unavailable-table">
                                                                    <tbody>
                                                                    <tr v-for="(day, i) in appointmentStore.calendar_data.unavailable_appointment_dates">
                                                                        <td><input type="text" readonly
                                                                                   class="form-control"
                                                                                   name="unavailable-date"/></td>
                                                                        <td style="width:100px; text-align: center;">
                                                                            <a role="button" class="mx-2 tooltip-ico"
                                                                               data-title="Remove"
                                                                               @click="removeUnavailableAppointmentDate(i)">
                                                                                <svg width="24px" height="24px"
                                                                                     viewBox="0 0 24 24"
                                                                                     xmlns="http://www.w3.org/2000/svg"
                                                                                     fill="#66686c" stroke="#66686c">
                                                                                    <g id="SVGRepo_bgCarrier"
                                                                                       stroke-width="0"></g>
                                                                                    <g id="SVGRepo_tracerCarrier"
                                                                                       stroke-linecap="round"
                                                                                       stroke-linejoin="round"></g>
                                                                                    <g id="SVGRepo_iconCarrier">
                                                                                        <path fill="none"
                                                                                              stroke="#66686c"
                                                                                              stroke-width="2"
                                                                                              d="M12,22 C17.5228475,22 22,17.5228475 22,12 C22,6.4771525 17.5228475,2 12,2 C6.4771525,2 2,6.4771525 2,12 C2,17.5228475 6.4771525,22 12,22 Z M5,5 L19,19"></path>
                                                                                    </g>
                                                                                </svg>
                                                                            </a>
                                                                        </td>
                                                                    </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                            <span
                                                                class="d-block text-danger mt-1"
                                                                v-text="unavailableError"
                                                            ></span>
                                                            <button class="btn btn-link w-25" role="button"
                                                                    v-if="calendar_schedule_editing"
                                                                    @click="addUnavailableAppointmentDate">Add Date
                                                            </button>
                                                        </div>
                                                        <div id="notification-reminder" class="col-12 mt-2">
                                                            <div class="form-group mb-0">
                                                                <p style="font-size: 14px; color:gray">Set when the
                                                                    customer
                                                                    receive reminder notification for scheduled
                                                                    appointment.</p>
                                                                <label>Appointment Reminder Notifications</label>
                                                            </div>
                                                            <div class="appointment-box">
                                                                <table class="table" id="unavailable-table">
                                                                    <tbody>
                                                                    <tr v-for="(reminder,index) in appointmentStore.calendar_data.reminders">
                                                                        <td>
                                                                            <select class="form-control form-select"
                                                                                    v-model="appointmentStore.calendar_data.reminders[index].type"
                                                                                    :class="appointmentStore.calendar_data.reminders[index].type ? 'filled' : ''"
                                                                            >
                                                                                <option value="email">Email</option>
                                                                                <option value="text">Text</option>
                                                                            </select>
                                                                        </td>
                                                                        <td style="width:20%">
                                                                            <input type="number" class="form-control"
                                                                                   :max="getMaxDurationValue(index)"
                                                                                   v-model="appointmentStore.calendar_data.reminders[index].interval"
                                                                                   :class="appointmentStore.calendar_data.reminders[index].interval? 'filled' : '' "
                                                                                   :style="appointmentStore.calendar_data.reminders[index].interval>getMaxDurationValue(index) ? 'border:1px solid red;' : ''"
                                                                                   :title="'Must be between 1 and '+ getMaxDurationValue(index) + ' ' + appointmentStore.calendar_data.reminders[index].unit "
                                                                            />
                                                                        </td>
                                                                        <td>
                                                                            <select class="form-control form-select"
                                                                                    v-model="appointmentStore.calendar_data.reminders[index].unit"
                                                                                    :class="appointmentStore.calendar_data.reminders[index].unit ? 'filled' : ''"
                                                                            >
                                                                                <option value="minutes">Minutes</option>
                                                                                <option value="hours">Hours</option>
                                                                                <option value="days">Days</option>
                                                                                <option value="weeks">Weeks</option>
                                                                            </select>
                                                                            <span
                                                                                class="d-block text-danger mt-1 d-block"
                                                                            ></span>
                                                                        </td>
                                                                        <td style="width:100px; text-align: center;">
                                                                            <a role="button" class="mx-2 tooltip-ico"
                                                                               data-title="Remove"
                                                                               @click="removeReminderNotification(index)">
                                                                                <svg width="24px" height="24px"
                                                                                     viewBox="0 0 24 24"
                                                                                     xmlns="http://www.w3.org/2000/svg"
                                                                                     fill="#66686c" stroke="#66686c">
                                                                                    <g id="SVGRepo_bgCarrier"
                                                                                       stroke-width="0"></g>
                                                                                    <g id="SVGRepo_tracerCarrier"
                                                                                       stroke-linecap="round"
                                                                                       stroke-linejoin="round"></g>
                                                                                    <g id="SVGRepo_iconCarrier">
                                                                                        <path fill="none"
                                                                                              stroke="#66686c"
                                                                                              stroke-width="2"
                                                                                              d="M12,22 C17.5228475,22 22,17.5228475 22,12 C22,6.4771525 17.5228475,2 12,2 C6.4771525,2 2,6.4771525 2,12 C2,17.5228475 6.4771525,22 12,22 Z M5,5 L19,19"></path>
                                                                                    </g>
                                                                                </svg>
                                                                            </a>
                                                                        </td>
                                                                    </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                            <span
                                                                class="d-block text-danger mt-1"
                                                                v-if="showReminderError"
                                                            >
                                                        One or more duration value is invalid please fix them before proceeding.
                                                    </span>
                                                            <button class="btn btn-link w-25 mt-2" role="button"
                                                                    v-if="calendar_schedule_editing"
                                                                    @click="addReminderNotification">Add Notification
                                                            </button>
                                                        </div>
                                                        <div class="col-12 mt-2">
                                                            <div class="form-group">
                                                        <span
                                                            class="d-block text-danger mt-1"
                                                            v-text="clinicStore.errors.timezone"
                                                        ></span>
                                                                <select
                                                                    v-model="appointmentStore.calendar_data.timezone"
                                                                    class="form-control form-select"
                                                                    :class="appointmentStore.calendar_data.timezone ? 'filled' : ''"
                                                                >
                                                                    <option selected disabled readonly value="">Select
                                                                        Timezone
                                                                    </option>
                                                                    <option value="America/New_York">(GMT -5:00) Eastern
                                                                        Time
                                                                        (US &amp; Canada)
                                                                    </option>
                                                                    <option value="America/Chicago">(GMT -6:00) Central
                                                                        Time
                                                                        (US
                                                                        &amp; Canada)
                                                                    </option>
                                                                    <option value="America/Denver">(GMT -7:00) Mountain
                                                                        Time
                                                                        (US
                                                                        &amp; Canada)
                                                                    </option>
                                                                    <option value="America/Los_Angeles">(GMT -8:00)
                                                                        Pacific
                                                                        Time
                                                                        (US &amp; Canada)
                                                                    </option>
                                                                    <option value="America/Anchorage">(GMT -9:00) Alaska
                                                                        Time
                                                                        (US &amp; Canada)
                                                                    </option>
                                                                </select>
                                                                <label>Timezone</label>
                                                            </div>
                                                            <!--/.form-group-->
                                                        </div>
                                                        <div class="col-12">
                                                            <div
                                                                class="form-group d-flex flex-column-reverse form-group">
                                                                <a role="button"
                                                                   class="btn btn-primary btn-link calendar-link"
                                                                   @click="copyDirectLink">Click To Copy Calendar
                                                                    Link</a>
                                                                <label>Calendar View</label>
                                                            </div>
                                                            <!--/.form-group-->
                                                        </div>
                                                        <div class="col-12 mt-2">
                                                            <div class="form-group">
                                                                <FullCalendar ref="fullCalendar"
                                                                              :options="calendarOptions"
                                                                              :custom-buttons="customButtons"
                                                                              v-if="showCalendar" :key="calendarKey"/>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="Plugin" class="tab-main-box tab-pane fade" role="tabpanel"
                                     aria-labelledby="Plugin-tab">
                                    <div class="col-12 mb-3" v-if="clinicStore.clinic.license_key">
                                        <div class="card rounded-3">
                                            <div class="card-header px-4 py-3 bg-light-gray">
                                                <div class="d-md-flex">
                                                    <div class="mb-3 mb-md-0">
                                                        <h4 class="fw-bold">An easy-to-use WordPress
                                                            plugin</h4>
                                                        <p class="mb-0 text-secondary">
                                                            Download and activate the plugin through
                                                            the
                                                            'Plugins' screen in WordPress.
                                                        </p>
                                                    </div>
                                                    <div class="ps-md-3 ms-auto">
                                                        <a :href="envData || 'javascript:void(0);'"
                                                           class="btn btn-primary text-nowrap d-inline-flex align-items-center px-4">
                                                            <svg width="22" height="22"
                                                                 viewBox="0 0 22 22"
                                                                 fill="none"
                                                                 xmlns="http://www.w3.org/2000/svg">
                                                                <path
                                                                    d="M19.25 13.75V17.4167C19.25 17.9029 19.0568 18.3692 18.713 18.713C18.3692 19.0568 17.9029 19.25 17.4167 19.25H4.58333C4.0971 19.25 3.63079 19.0568 3.28697 18.713C2.94315 18.3692 2.75 17.9029 2.75 17.4167V13.75M6.41667 9.16667L11 13.75M11 13.75L15.5833 9.16667M11 13.75V2.75"
                                                                    stroke="white"
                                                                    stroke-width="1.5"
                                                                    stroke-linecap="round"
                                                                    stroke-linejoin="round"></path>
                                                            </svg>
                                                            <span
                                                                class="ps-2">Download Plugin</span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body fs-6 border-bottom">
                                                <p class="mb-1"><strong> After installing:</strong>
                                                </p>
                                                <ul class="mb-0">
                                                    <li>You'll see a new item on the left-hand menu
                                                        called
                                                        'Appointments'.
                                                    </li>
                                                    <li>Activate Schedule Appointment through the
                                                        Client
                                                        ID
                                                        and License Key.
                                                    </li>
                                                    <li>
                                                        Once your license is activated, you can use
                                                        the
                                                        <code>[ScheduleAppointment
                                                            clinic_id="xxx"]</code>
                                                        shortcode on any post or page to add an
                                                        appointment
                                                        booking form for your leads.
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="card-body fs-6 border-bottom">
                                                <div class="d-md-flex align-items-center">
                                                    <p class="mb-3 mb-md-0">Client ID:
                                                        <strong>{{ clinicStore.clinic.id }}</strong>
                                                    </p>
                                                    <div class="ps-md-3 ms-auto">
                                                        <a href="javascript:void(0);"
                                                           @click="copyToClipboardDownload(clinicStore.clinic.id)"
                                                           class="btn text-nowrap d-inline-flex align-items-center btn-outline-primary px-4">Copy
                                                            Clinic ID</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body fs-6">
                                                <div class="d-md-flex align-items-center">
                                                    <p class="mb-3 mb-md-0">License key: <strong>{{
                                                            clinicStore.clinic.license_key
                                                        }}</strong></p>
                                                    <div class="ps-md-3 ms-auto">
                                                        <a href="javascript:void(0);"
                                                           @click="copyToClipboardDownload(clinicStore.clinic.license_key)"
                                                           class="btn text-nowrap d-inline-flex align-items-center btn-outline-primary px-4">Copy
                                                            License Key</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="Template" class="tab-pane fade" role="tabpanel" aria-labelledby="Template-tab">

                                    <div class="tab-main-title d-lg-none">
                                        <h5>Appointment Email Template</h5>
                                    </div>

                                    <div
                                        class="tab-main-box"
                                        :class="
                                  appointment_template_editing ? 'tab-edit-active tab-main-active' : 'tab-disabled-active'
                                "
                                    >
                                        <div class="tab-title-box d-flex align-items-center">
                                            <div class="tab-title" @click.prevent="appointment_template_editing = true">
                                                <h3>Appointment Email Template</h3>
                                            </div>
                                            <div
                                                class="edit-option d-none d-lg-flex ms-auto"
                                                :class="appointment_template_editing ? 'edit-active' : ''"
                                            >
                                                <a role="button" class="save-input manage-cancel"
                                                   v-show="appointment_template_editing"
                                                   @click="appointment_template_editing=false">Cancel</a>
                                                <a
                                                    v-if="!appointment_template_editing"
                                                    role="button"
                                                    @click.prevent="appointment_template_editing = true"
                                                    class="edit-input manage-edit"
                                                >Edit
                                                    <svg
                                                        width="18"
                                                        height="18"
                                                        viewBox="0 0 18 18"
                                                        xmlns="http://www.w3.org/2000/svg"
                                                    >
                                                        <path
                                                            d="M16 17.25C16 16.848 15.644 16.5 15.25 16.5C12.689 16.5 3.311 16.5 0.75 16.5C0.356 16.5 0 16.848 0 17.25C0 17.652 0.356 18 0.75 18H15.25C15.644 18 16 17.652 16 17.25ZM8.597 13.852L17.721 4.727C17.892 4.556 18 4.304 18 4.043C18 3.814 17.917 3.577 17.72 3.381L14.605 0.277C14.42 0.0919999 14.176 0 13.933 0C13.69 0 13.447 0.0919999 13.261 0.277L4.118 9.38C3.549 11.143 2.563 14.203 2.492 14.461C2.472 14.536 2.463 14.611 2.463 14.685C2.463 15.146 2.812 15.533 3.228 15.533C3.739 15.533 4.219 15.344 8.597 13.852ZM5.327 10.51L7.464 12.647L4.296 13.693L5.327 10.51ZM6.282 9.344L13.933 1.728L16.268 4.055L8.631 11.693L6.282 9.344Z"
                                                        />
                                                    </svg>
                                                </a>
                                                <a
                                                    v-else
                                                    role="button"
                                                    @click.prevent="updateAppointmentTemplate()"
                                                    class="save-input manage-save"
                                                >Save</a
                                                >
                                            </div>
                                        </div>
                                        <div class="tab-sub-box">
                                            <div class="d-lg-none d-flex align-items-center">
                                                <a role="button" class="back-btn" @click="resetActiveTabs()">
                                                    <svg
                                                        width="20"
                                                        height="14"
                                                        viewBox="0 0 20 14"
                                                        fill="none"
                                                        xmlns="http://www.w3.org/2000/svg"
                                                    >
                                                        <path
                                                            fill-rule="evenodd"
                                                            clip-rule="evenodd"
                                                            d="M19.4411 6.37497H2.04859L7.25172 1.19185C7.49609 0.947473 7.49609 0.551848 7.25172 0.308098C7.00734 0.0637231 6.61172 0.0637231 6.36797 0.308098L0.180469 6.49497C-0.0601562 6.7356 -0.0601562 7.1381 0.180469 7.37872L6.36797 13.5662C6.61234 13.8106 7.00797 13.8106 7.25172 13.5662C7.49609 13.3218 7.49609 12.9262 7.25172 12.6825L2.04859 7.62497H19.4411C19.7861 7.62497 20.0661 7.34497 20.0661 6.99997C20.0661 6.65497 19.7861 6.37497 19.4411 6.37497Z"
                                                            fill="#514F5F"
                                                        />
                                                    </svg>
                                                </a>
                                                <a
                                                    role="button"
                                                    class="save-input btn px-4 btn-primary tab-save"
                                                    @click.prevent="updateAppointmentTemplate()"
                                                >Save</a
                                                >
                                            </div>
                                            <div class="d-lg-none mobile-tab-title">
                                                <h3>Appointment Email Template</h3>
                                            </div>
                                            <div class="row gx-3">


                                                <div class="col-12">
                                                    <div class="row">
                                                        <div class="col-12 mt-3">
                                                            <div class="form-group flex-column">
                                                                <h5 class="d-lg-none">Email Subject</h5>
                                                                <label class="d-none d-lg-block">Email Subject</label>
                                                                <input
                                                                    type="text"
                                                                    class="form-control"
                                                                    v-model="selectedAppointmentTemplate.subject"
                                                                    placeholder="Email Subject"
                                                                />
                                                            </div>
                                                            <span
                                                                class="d-block text-danger mt-1"
                                                                v-text="appointmentStore.errors.subject"
                                                            ></span>
                                                        </div>

                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="row">
                                                        <div class="col-12 mt-3">
                                                            <div class="form-group flex-column">
                                                                <h5 class="d-lg-none">Email Template</h5>
                                                                <label class="d-none d-lg-block">Email Template</label>
                                                                <ckeditor
                                                                    :editor="editor"
                                                                    v-model="selectedAppointmentTemplate.body"
                                                                    :config="editorConfig"
                                                                    :disabled="!appointment_template_editing"
                                                                ></ckeditor>
                                                            </div>
                                                            <span
                                                                class="d-block text-danger mt-1"
                                                                v-text="appointmentStore.errors.body"
                                                            ></span>
                                                        </div>

                                                    </div>
                                                </div>
                                                <!-- Placeholder Information -->
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-main-title d-lg-none">
                                        <h5>Reminder Email Template</h5>
                                    </div>

                                    <div
                                        class="tab-main-box"
                                        :class="
                                  reminder_email_template_editing ? 'tab-edit-active tab-main-active' : 'tab-disabled-active'
                                "
                                    >
                                        <div class="tab-title-box d-flex align-items-center">
                                            <div class="tab-title"
                                                 @click.prevent="reminder_email_template_editing = true">
                                                <h3>Reminder Email Template</h3>
                                            </div>
                                            <div
                                                class="edit-option d-none d-lg-flex ms-auto"
                                                :class="reminder_email_template_editing ? 'edit-active' : ''"
                                            >
                                                <a role="button" class="save-input manage-cancel"
                                                   v-show="reminder_email_template_editing"
                                                   @click="reminder_email_template_editing=false">Cancel</a>
                                                <a
                                                    v-if="!reminder_email_template_editing"
                                                    role="button"
                                                    @click.prevent="reminder_email_template_editing = true"
                                                    class="edit-input manage-edit"
                                                >Edit
                                                    <svg
                                                        width="18"
                                                        height="18"
                                                        viewBox="0 0 18 18"
                                                        xmlns="http://www.w3.org/2000/svg"
                                                    >
                                                        <path
                                                            d="M16 17.25C16 16.848 15.644 16.5 15.25 16.5C12.689 16.5 3.311 16.5 0.75 16.5C0.356 16.5 0 16.848 0 17.25C0 17.652 0.356 18 0.75 18H15.25C15.644 18 16 17.652 16 17.25ZM8.597 13.852L17.721 4.727C17.892 4.556 18 4.304 18 4.043C18 3.814 17.917 3.577 17.72 3.381L14.605 0.277C14.42 0.0919999 14.176 0 13.933 0C13.69 0 13.447 0.0919999 13.261 0.277L4.118 9.38C3.549 11.143 2.563 14.203 2.492 14.461C2.472 14.536 2.463 14.611 2.463 14.685C2.463 15.146 2.812 15.533 3.228 15.533C3.739 15.533 4.219 15.344 8.597 13.852ZM5.327 10.51L7.464 12.647L4.296 13.693L5.327 10.51ZM6.282 9.344L13.933 1.728L16.268 4.055L8.631 11.693L6.282 9.344Z"
                                                        />
                                                    </svg>
                                                </a>
                                                <a
                                                    v-else
                                                    role="button"
                                                    @click.prevent="updateReminderEmailTemplate()"
                                                    class="save-input manage-save"
                                                >Save</a
                                                >
                                            </div>
                                        </div>
                                        <div class="tab-sub-box">
                                            <div class="d-lg-none d-flex align-items-center">
                                                <a role="button" class="back-btn" @click="resetActiveTabs()">
                                                    <svg
                                                        width="20"
                                                        height="14"
                                                        viewBox="0 0 20 14"
                                                        fill="none"
                                                        xmlns="http://www.w3.org/2000/svg"
                                                    >
                                                        <path
                                                            fill-rule="evenodd"
                                                            clip-rule="evenodd"
                                                            d="M19.4411 6.37497H2.04859L7.25172 1.19185C7.49609 0.947473 7.49609 0.551848 7.25172 0.308098C7.00734 0.0637231 6.61172 0.0637231 6.36797 0.308098L0.180469 6.49497C-0.0601562 6.7356 -0.0601562 7.1381 0.180469 7.37872L6.36797 13.5662C6.61234 13.8106 7.00797 13.8106 7.25172 13.5662C7.49609 13.3218 7.49609 12.9262 7.25172 12.6825L2.04859 7.62497H19.4411C19.7861 7.62497 20.0661 7.34497 20.0661 6.99997C20.0661 6.65497 19.7861 6.37497 19.4411 6.37497Z"
                                                            fill="#514F5F"
                                                        />
                                                    </svg>
                                                </a>
                                                <a
                                                    role="button"
                                                    class="save-input btn px-4 btn-primary tab-save"
                                                    @click.prevent="updateReminderEmailTemplate()"
                                                >Save</a
                                                >
                                            </div>
                                            <div class="d-lg-none mobile-tab-title">
                                                <h3>Reminder Email Template</h3>
                                            </div>
                                            <div class="row gx-3">


                                                <div class="col-12">
                                                    <div class="row">
                                                        <div class="col-12 mt-3">
                                                            <div class="form-group flex-column">
                                                                <h5 class="d-lg-none">Email Subject</h5>
                                                                <label class="d-none d-lg-block">Email Subject</label>
                                                                <input
                                                                    type="text"
                                                                    class="form-control"
                                                                    v-model="selectedReminderEmailTemplate.subject"
                                                                    placeholder="Email Subject"
                                                                />
                                                            </div>
                                                            <span
                                                                class="d-block text-danger mt-1"
                                                                v-text="appointmentStore.reminderEmailErrors.subject"
                                                            ></span>
                                                        </div>

                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="row">
                                                        <div class="col-12 mt-3">
                                                            <div class="form-group flex-column">
                                                                <h5 class="d-lg-none">Email Template</h5>
                                                                <label class="d-none d-lg-block">Email Template</label>
                                                                <ckeditor
                                                                    :editor="editor"
                                                                    v-model="selectedReminderEmailTemplate.body"
                                                                    :config="editorConfig"
                                                                    :disabled="!reminder_email_template_editing"
                                                                ></ckeditor>
                                                            </div>
                                                            <span
                                                                class="d-block text-danger mt-1"
                                                                v-text="appointmentStore.reminderEmailErrors.body"
                                                            ></span>
                                                        </div>

                                                    </div>
                                                </div>
                                                <!-- Placeholder Information -->
                                                <!--                                        <div class="col-12 mt-4">
                                                                                            <h5>Placeholder Information</h5>
                                                                                            <ul>
                                                                                                <li class="mb-2" v-for="(description, placeholder) in placeholders" :key="placeholder">
                                                                                                    <code>{{ formatPlaceholder(placeholder) }}</code>: {{ description }}
                                                                                                </li>
                                                                                            </ul>
                                                                                        </div>-->
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-main-title d-lg-none">
                                        <h5>Reminder Text Template</h5>
                                    </div>

                                    <div
                                        class="tab-main-box"
                                        :class="
                                  reminder_text_template_editing ? 'tab-edit-active tab-main-active' : 'tab-disabled-active'
                                "
                                    >
                                        <div class="tab-title-box d-flex align-items-center">
                                            <div class="tab-title"
                                                 @click.prevent="reminder_text_template_editing = true">
                                                <h3>Reminder Text Template</h3>
                                            </div>
                                            <div
                                                class="edit-option d-none d-lg-flex ms-auto"
                                                :class="reminder_text_template_editing ? 'edit-active' : ''"
                                            >
                                                <a role="button" class="save-input manage-cancel"
                                                   v-show="reminder_text_template_editing"
                                                   @click="reminder_text_template_editing=false">Cancel</a>
                                                <a
                                                    v-if="!reminder_text_template_editing"
                                                    role="button"
                                                    @click.prevent="reminder_text_template_editing = true"
                                                    class="edit-input manage-edit"
                                                >Edit
                                                    <svg
                                                        width="18"
                                                        height="18"
                                                        viewBox="0 0 18 18"
                                                        xmlns="http://www.w3.org/2000/svg"
                                                    >
                                                        <path
                                                            d="M16 17.25C16 16.848 15.644 16.5 15.25 16.5C12.689 16.5 3.311 16.5 0.75 16.5C0.356 16.5 0 16.848 0 17.25C0 17.652 0.356 18 0.75 18H15.25C15.644 18 16 17.652 16 17.25ZM8.597 13.852L17.721 4.727C17.892 4.556 18 4.304 18 4.043C18 3.814 17.917 3.577 17.72 3.381L14.605 0.277C14.42 0.0919999 14.176 0 13.933 0C13.69 0 13.447 0.0919999 13.261 0.277L4.118 9.38C3.549 11.143 2.563 14.203 2.492 14.461C2.472 14.536 2.463 14.611 2.463 14.685C2.463 15.146 2.812 15.533 3.228 15.533C3.739 15.533 4.219 15.344 8.597 13.852ZM5.327 10.51L7.464 12.647L4.296 13.693L5.327 10.51ZM6.282 9.344L13.933 1.728L16.268 4.055L8.631 11.693L6.282 9.344Z"
                                                        />
                                                    </svg>
                                                </a>
                                                <a
                                                    v-else
                                                    role="button"
                                                    @click.prevent="updateReminderTextTemplate()"
                                                    class="save-input manage-save"
                                                >Save</a
                                                >
                                            </div>
                                        </div>
                                        <div class="tab-sub-box">
                                            <div class="d-lg-none d-flex align-items-center">
                                                <a role="button" class="back-btn" @click="resetActiveTabs()">
                                                    <svg
                                                        width="20"
                                                        height="14"
                                                        viewBox="0 0 20 14"
                                                        fill="none"
                                                        xmlns="http://www.w3.org/2000/svg"
                                                    >
                                                        <path
                                                            fill-rule="evenodd"
                                                            clip-rule="evenodd"
                                                            d="M19.4411 6.37497H2.04859L7.25172 1.19185C7.49609 0.947473 7.49609 0.551848 7.25172 0.308098C7.00734 0.0637231 6.61172 0.0637231 6.36797 0.308098L0.180469 6.49497C-0.0601562 6.7356 -0.0601562 7.1381 0.180469 7.37872L6.36797 13.5662C6.61234 13.8106 7.00797 13.8106 7.25172 13.5662C7.49609 13.3218 7.49609 12.9262 7.25172 12.6825L2.04859 7.62497H19.4411C19.7861 7.62497 20.0661 7.34497 20.0661 6.99997C20.0661 6.65497 19.7861 6.37497 19.4411 6.37497Z"
                                                            fill="#514F5F"
                                                        />
                                                    </svg>
                                                </a>
                                                <a
                                                    role="button"
                                                    class="save-input btn px-4 btn-primary tab-save"
                                                    @click.prevent="updateReminderTextTemplate()"
                                                >Save</a
                                                >
                                            </div>
                                            <div class="d-lg-none mobile-tab-title">
                                                <h3>Reminder Text Template</h3>
                                            </div>
                                            <div class="row gx-3">
                                                <div class="col-12">
                                                    <div class="row">
                                                        <div class="col-12 mt-3">
                                                            <div class="form-group flex-column">
                                                                <h5 class="d-lg-none">Text Template</h5>
                                                                <label class="d-none d-lg-block">Text Template</label>
                                                                <textarea class="form-control"
                                                                          v-model="selectedReminderTextTemplate.body"></textarea>
                                                                <!--                                                        <ckeditor
                                                                                                                            :editor="editor"
                                                                                                                            v-model="selectedReminderTextTemplate.body"
                                                                                                                            :config="editorConfig"
                                                                                                                            :disabled="!reminder_text_template_editing"
                                                                                                                        ></ckeditor>-->
                                                            </div>
                                                            <span
                                                                class="d-block text-danger mt-1"
                                                                v-text="appointmentStore.reminderTextErrors.body"
                                                            ></span>
                                                        </div>

                                                    </div>
                                                </div>
                                                <!-- Placeholder Information -->
                                                <!--                                        <div class="col-12 mt-4">
                                                                                            <h5>Placeholder Information</h5>
                                                                                            <ul>
                                                                                                <li class="mb-2" v-for="(description, placeholder) in placeholders" :key="placeholder">
                                                                                                    <code>{{ formatPlaceholder(placeholder) }}</code>: {{ description }}
                                                                                                </li>
                                                                                            </ul>
                                                                                        </div>-->
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-main-box">
                                        <div class="tab-title-box d-flex align-items-center">
                                            <div class="tab-title"
                                                 @click.prevent="reminder_text_template_editing = true">
                                                <h3>Placeholder Information</h3>
                                            </div>
                                        </div>
                                        <div class="col-12 mt-4">
                                            <ul>
                                                <li class="mb-2"
                                                    v-for="(description, placeholder) in placeholders"
                                                    :key="placeholder">
                                                    <code>{{ formatPlaceholder(placeholder) }}</code>:
                                                    {{ description }}
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div
                            id="Automation"
                            v-if="currentPage==='automation'"
                        >
                            <div class="tab-main-title d-lg-none">
                                <h5>Automation Templates</h5>
                            </div>
                            <div
                                class="tab-main-box"
                                :class="nurturing_template_editing ? 'tab-edit-active tab-main-active' : 'tab-disabled-active'"
                            >
                                <div class="tab-title-box d-flex align-items-center">
                                    <div class="tab-title" @click.prevent="nurturing_template_editing = true">
                                        <h3>Manage Automation Templates</h3>
                                    </div>
                                </div>
                                <div class="tab-sub-box">
                                    <div class="d-lg-none d-flex align-items-center">
                                        <a role="button" class="back-btn" @click="resetActiveTabs()">
                                            <svg
                                                width="20"
                                                height="14"
                                                viewBox="0 0 20 14"
                                                fill="none"
                                                xmlns="http://www.w3.org/2000/svg"
                                            >
                                                <path
                                                    fill-rule="evenodd"
                                                    clip-rule="evenodd"
                                                    d="M19.4411 6.37497H2.04859L7.25172 1.19185C7.49609 0.947473 7.49609 0.551848 7.25172 0.308098C7.00734 0.0637231 6.61172 0.0637231 6.36797 0.308098L0.180469 6.49497C-0.0601562 6.7356 -0.0601562 7.1381 0.180469 7.37872L6.36797 13.5662C6.61234 13.8106 7.00797 13.8106 7.25172 13.5662C7.49609 13.3218 7.49609 12.9262 7.25172 12.6825L2.04859 7.62497H19.4411C19.7861 7.62497 20.0661 7.34497 20.0661 6.99997C20.0661 6.65497 19.7861 6.37497 19.4411 6.37497Z"
                                                    fill="#514F5F"
                                                />
                                            </svg>
                                        </a>
                                    </div>
                                    <div class="d-lg-none mobile-tab-title">
                                        <h3>Manage Automation</h3>
                                    </div>
                                    <div class="bg-custom border-0 card rounded shadow-none mb-4">
                                        <div class="card-body"
                                             v-for="(template, index) in clinicStore.immediateTemplates"
                                             :key="template.dayinterval"
                                             :class="{ 'd-none': index % 2 !== 0 && index + 1 === clinicStore.immediateTemplates.length }">
                                            <h3><small>Immediate Reply for New Leads</small></h3>
                                            <div class="row g-3 mt-2">
                                                <div class="col-md-6">
                                                    <div class="nurturing-template-item">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <div>
                                                                <div class="d-flex mb-2">
                                                                    <lable class="fw-bold">Text Template</lable>
                                                                    <div class="ms-auto">
                                                                        <label class="form-check form-switch p-0">
                                                                            <label class="form-check-label">Off</label>
                                                                            <input type="checkbox"
                                                                                   class="float-none form-check-input mx-2 position-static"
                                                                                   v-model="clinicStore.immediateTemplatesToggle.sms.isEnabled"
                                                                                   true-value="1" false-value="0"
                                                                                   @change="toggleTemplateStatus('sms')"
                                                                                   style="opacity: 1;">
                                                                            <label class="form-check-label">On</label>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div
                                                                    class="bg-white border position-relative p-2 p-lg-3 rounded"
                                                                    v-on:click="editImmediate('Text Template', template.text_template, template.dayinterval)">

                                                                    <div class="nurturing-text-container">
                                                                    <span class="nurture-edit-icon">
                                                                        <svg class="feather feather-edit" fill="none"
                                                                             height="20" stroke="#425BCF"
                                                                             stroke-linecap="round"
                                                                             stroke-linejoin="round" stroke-width="2"
                                                                             viewBox="0 0 24 24" width="24"
                                                                             xmlns="http://www.w3.org/2000/svg">
                                                                            <path
                                                                                d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                                                            <path
                                                                                d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                                                        </svg>
                                                                    </span>
                                                                        <div
                                                                            v-html="htmlEntities(template.text_template)"></div>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="nurturing-template-item">
                                                        <div class="d-flex justify-content-between align-items-center">

                                                            <div>
                                                                <div class="d-flex mb-2">
                                                                    <lable class="fw-bold">Email Template</lable>
                                                                    <div class="ms-auto">
                                                                        <label class="form-check form-switch p-0">
                                                                            <label class="form-check-label">Off</label>
                                                                            <input type="checkbox"
                                                                                   class="float-none form-check-input mx-2 position-static"
                                                                                   v-model="clinicStore.immediateTemplatesToggle.email.isEnabled"
                                                                                   true-value="1" false-value="0"
                                                                                   @change="toggleTemplateStatus('email')"
                                                                                   style="opacity: 1;">
                                                                            <label class="form-check-label">On</label>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div
                                                                    class="bg-white border position-relative p-2 p-lg-3 rounded"
                                                                    v-on:click="editImmediate('Email Template', template.email_template, template.dayinterval, template.email_subject)">

                                                                    <div class="nurturing-text-container">
                                                                            <span class="nurture-edit-icon">
                                                                            <svg class="feather feather-edit"
                                                                                 fill="none" height="20"
                                                                                 stroke="#425BCF" stroke-linecap="round"
                                                                                 stroke-linejoin="round"
                                                                                 stroke-width="2" viewBox="0 0 24 24"
                                                                                 width="24"
                                                                                 xmlns="http://www.w3.org/2000/svg">
                                                                                <path
                                                                                    d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                                                                <path
                                                                                    d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                                                            </svg>
                                                                        </span>
                                                                        <div
                                                                            v-html="htmlEntities(template.email_template)"></div>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                            <div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="row gx-3">
                                        <div class="col-md-12 col-lg-12">
                                            <div class="dashboard-box">
                                                <!-- <div class="dashboard-box-title d-flex align-items-center">
                          <h3>Nurturing</h3>
                        </div> -->
                                                <div class="dashboard-sub-box ">
                                                    <table
                                                        class="nurturing-table table table-dark table-striped table-bordered"
                                                        style="border-color:#f0f4f8;">
                                                        <thead>

                                                        <tr>

                                                            <th scope="col" width="40%">Automation Name</th>
                                                            <th scope="col" width="25%">Status</th>
                                                            <th scope="col" style="width:30%">Edit</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>

                                                        <tr v-for="(value, key) in clinicStore.automationCampaign"
                                                            :key="key">
                                                            <td>{{ value.name }}</td>
                                                            <td>
                                                                <label class="switch">
                                                                    <input type="checkbox"
                                                                           v-model="value.status"
                                                                           @change="handleToggleChange(value)"
                                                                           :value=value.status
                                                                    >
                                                                    <span class="slider round"></span>
                                                                </label>
                                                            </td>
                                                            <td>
                                                                <div class="edit-option d-lg-flex ms-auto"
                                                                     :class="general_details_editing ? 'edit-active' : ''">
                                                                    <a @click="viewStatusAutomation(value.id)"
                                                                       role="button"
                                                                       class="edit-input manage-edit"
                                                                    >
                                                                        Edit
                                                                        <svg
                                                                            width="18"
                                                                            height="18"
                                                                            viewBox="0 0 18 18"
                                                                            xmlns="http://www.w3.org/2000/svg"
                                                                        >
                                                                            <path
                                                                                d="M16 17.25C16 16.848 15.644 16.5 15.25 16.5C12.689 16.5 3.311 16.5 0.75 16.5C0.356 16.5 0 16.848 0 17.25C0 17.652 0.356 18 0.75 18H15.25C15.644 18 16 17.652 16 17.25ZM8.597 13.852L17.721 4.727C17.892 4.556 18 4.304 18 4.043C18 3.814 17.917 3.577 17.72 3.381L14.605 0.277C14.42 0.0919999 14.176 0 13.933 0C13.69 0 13.447 0.0919999 13.261 0.277L4.118 9.38C3.549 11.143 2.563 14.203 2.492 14.461C2.472 14.536 2.463 14.611 2.463 14.685C2.463 15.146 2.812 15.533 3.228 15.533C3.739 15.533 4.219 15.344 8.597 13.852ZM5.327 10.51L7.464 12.647L4.296 13.693L5.327 10.51ZM6.282 9.344L13.933 1.728L16.268 4.055L8.631 11.693L6.282 9.344Z"
                                                                            />
                                                                        </svg>
                                                                    </a>
                                                                </div>
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
            </div>
        </div>
    </div>
    <div class="modal fade" id="editNurturingModal" tabindex="-1" aria-labelledby="editNurturingModal"
         aria-hidden="true">
        <div class="modal-dialog add-lead-model modal-dialog-centered modal-lg">
            <div class="modal-content p-md-3 bg-light-gray">
                <div class="modal-header">
                    <h3 class="modal-title" id="staticBackdropLabel">{{ editTitle }}</h3>
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
                        <ckeditor :editor="editor" v-model="editContent" :config="editorConfig"
                                  style="white-space:pre-line; height:100%;"></ckeditor>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" @click="updateNurturingTemplate">Save</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="editImmediateModal" tabindex="-1" aria-labelledby="editImmediateModal"
         aria-hidden="true">
        <div class="modal-dialog add-lead-model modal-dialog-centered modal-lg">
            <div class="modal-content p-md-3 bg-light-gray">
                <div class="modal-header">
                    <h3 class="modal-title" id="staticBackdropLabel">{{ editTitle }}</h3>
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
                        <!-- Conditionally show email subject input -->
                        <div v-if="editType === 'email_template'" class="mb-3">
                            <label for="emailSubject" class="form-label">Email Subject</label>
                            <input type="text" class="form-control" id="emailSubject" v-model="emailSubject">
                        </div>
                        <ckeditor :editor="editor" v-model="editContent" :config="editorConfig"
                                  style="white-space:pre-line; height:100%;"></ckeditor>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" @click="updateImmediateTemplate">Save</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="templateModal" tabindex="-1" aria-labelledby="templateModal" aria-hidden="true">
        <div class="modal-dialog add-lead-model modal-dialog-centered modal-lg">
            <div class="modal-content p-md-3 bg-light-gray">
                <div class="modal-header">
                    <h3 class="modal-title" id="staticBackdropLabel">
                        {{ clinicStore.templateType.charAt(0).toUpperCase() + clinicStore.templateType.slice(1) }}
                        Template</h3>
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
                        <div class="modal-form">
                            <div v-if="clinicStore.errorMessage" class="alert alert-danger alert-dismissible fade show"
                                 role="alert">
                                {{ clinicStore.errorMessage }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"
                                        @click="clinicStore.errorMessage = ''"></button>
                            </div>
                            <form autocomplete="off">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Name</label>
                                            <input type="text" class="form-control"
                                                   v-model="clinicStore.template.template_name">
                                            <span class="d-block text-danger mt-1"
                                                  v-text="clinicStore.templateErrors['template_name']"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-12"
                                         v-if="clinicStore.templateType === 'email' || clinicStore.templateType === 'appointment'">
                                        <div class="form-group">
                                            <label>Subject</label>
                                            <input type="text" class="form-control"
                                                   v-model="clinicStore.template.subject">
                                            <span class="d-block text-danger mt-1"
                                                  v-text="clinicStore.templateErrors['subject']"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Template</label>
                                            <textarea class="form-control"
                                                      v-if="clinicStore.templateType === 'text'"
                                                      v-model="clinicStore.template.body"
                                                      style="white-space: pre-line;"></textarea>
                                            <div
                                                v-if="clinicStore.templateType === 'email' || clinicStore.templateType === 'appointment'">
                                                <ckeditor :editor="editor"
                                                          v-model="clinicStore.template.body"
                                                          :config="editorConfig"
                                                          style="white-space: pre-line; height: 100%;"></ckeditor>
                                            </div>
                                            <span class="d-block text-danger mt-1"
                                                  v-text="clinicStore.templateErrors['body']"></span>
                                        </div>
                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" @click="updateEmailTextTemplate">Save</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="DeleteTemplateModal" tabindex="-1" aria-labelledby="DeleteModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content p-3">
                <div class="modal-body text-md-center p-3 mb-3">
                    <h3>Are you sure you want to delete this Template?</h3>
                    <p class="m-0">This will delete this template permanently. you cannot undo this action.</p>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-outline-primary px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary px-4" @click="deleteTemplate()">Delete</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="CustomDurationModal" tabindex="-1" aria-labelledby="CustomDurationModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content p-3">
                <div class="modal-body text-md-center p-3 mb-3">
                    <h3>Custom Duration</h3>
                    <input type="number" value="30" min="30" id="custom-duration" class="form-control">
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-outline-primary px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary px-4" @click="setCustomDuration()">Save</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="DeleteStaffEmailConfirm" tabindex="-1" aria-labelledby="LeadFullDetailsLabel"
         aria-hidden="true">
        <div class="modal-dialog add-lead-model modal-dialog-centered">
            <div class="modal-content p-md-3 bg-light-gray">
                <div class="modal-header border-0 text-center justify-content-start align-items-start">
                    <h4 class="fw-bold text-center">Are you sure you want delete?</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <svg width="20" height="14" viewBox="0 0 20 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                  d="M19.4411 6.37503H2.04859L7.25172 1.19191C7.49609 0.947534 7.49609 0.551909 7.25172 0.308159C7.00734 0.0637842 6.61172 0.0637842 6.36797 0.308159L0.180469 6.49503C-0.0601562 6.73566 -0.0601562 7.13816 0.180469 7.37878L6.36797 13.5663C6.61234 13.8107 7.00797 13.8107 7.25172 13.5663C7.49609 13.3219 7.49609 12.9263 7.25172 12.6825L2.04859 7.62503H19.4411C19.7861 7.62503 20.0661 7.34503 20.0661 7.00003C20.0661 6.65503 19.7861 6.37503 19.4411 6.37503Z"
                                  fill="#514F5F"></path>
                        </svg>
                    </button>
                </div>
                <div class="modal-footer text-center justify-content-center border-0 pt-0">
                    <button type="button" class="btn btn-outline-primary px-4 w-25" data-bs-dismiss="modal">Cancel
                    </button>
                    <button type="button" class="btn btn-primary px-4 w-25" @click="deleteStaffMember()">Yes</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="AppointmentDetailsModal" tabindex="-1" aria-labelledby="AppointmentDetailsModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content p-md-3 bg-light-gray">
                <div class="modal-header">
                    <h6 class="modal-title fs-4 mb-3 mb-lg-0" id="staticBackdropLabel">Appointment Details</h6>
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
                        <div class="confirm-appointment-date mw-100" v-if="selectedAppointment!=null">
                            <i class="fas fa-calendar-alt"></i>
                            <div class="lead lead-link"><a role="button"
                                                           @click="viewProfile(selectedAppointment.crm_customer.id)">
                                <h3>{{ selectedAppointment.crm_customer.first_name }}
                                    {{ selectedAppointment.crm_customer.last_name }}</h3></a></div>
                            <div class="fs-6 mb-1">{{ dateFormat(selectedAppointment.date) }}</div>
                            <div><h2 class="fw-light mb-1">{{ timeFormat(selectedAppointment.time) }}</h2></div>
                            <div v-if="selectedAppointment.patient_type != null">{{
                                    selectedAppointment.patient_type
                                }}
                            </div>
                            <div v-if="selectedAppointment.payment_type != null">{{
                                    selectedAppointment.payment_type.charAt(0).toUpperCase() + selectedAppointment.payment_type.slice(1)
                                }}
                            </div>
                            <div v-if="selectedAppointment.services != null">{{
                                    selectedAppointment.services.name
                                }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import {useAlertStore} from "../../stores/alert";
import {useClinicStore} from "../../stores/clinic";
import {useAuthStore} from "../../stores/auth";
import {useAppointmentStore} from "../../stores/appointment";
import FullCalendar from '@fullcalendar/vue3'
import dayGridPlugin from '@fullcalendar/daygrid'
import timeGridPlugin from '@fullcalendar/timegrid'
import CKEditor from '@ckeditor/ckeditor5-vue';
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
import htmlEntities from 'he';
import CKEditorInspector from '@ckeditor/ckeditor5-inspector';
import UploadAdapter from '../../stores/UploadAdapter';
import {onMounted} from 'vue'
import axios from "axios";
//import Dropdown from '@ckeditor/ckeditor5-ui/src/dropdown/dropdownview';

export default {
    setup() {
        const clinicStore = useClinicStore();

        const alertStore = useAlertStore();

        const authStore = useAuthStore();

        const appointmentStore = useAppointmentStore();

        return {clinicStore, alertStore, authStore, appointmentStore};
    },
    data() {
        return {
            showCreateService: false,
            newServiceName: "",
            errorMessage: "",
            successMessage: "",
            tracking_phone: null,
            general_details_editing: false,
            office_details_editing: false,
            staff_access_editing: false,
            media_content_editing: false,
            marketing_budget_editing: false,
            callrail_installtion_editing: false,
            nurturing_template_editing: false,
            text_template_editing: false,
            email_template_editing: false,
            calendar_details_editing: false,
            calendar_schedule_editing: false,
            appointment_template_editing: false,
            reminder_email_template_editing: false,
            reminder_text_template_editing: false,
            editTitle: '',
            parent_id: null,
            deleteStaffEmail: null,
            editContent: '',
            dayInterval: '',
            editType: '',
            calendar_times_start: [],
            calendar_times_end: [],
            toggleStatus: false,
            editor: ClassicEditor,
            domainVerificationStatus: '',
            editorConfig: {
                extraPlugins: [this.uploader],
            },
            currentPage: 'practice-information',
            automationCampaign: [],
            start: null,
            end: null,
            showCalendar: false,
            selectedAppointment: null,
            scheduling_errors: [],
            calendarKey: 0,
            envData: null,
            calendarOptions: {
                plugins: [dayGridPlugin, timeGridPlugin],
                initialView: 'timeGridWeek',
                /* headerToolbar: {
                    left: 'prev,next',
                    center: 'title',
                    right: 'timeGridWeek,timeGridDay' // user can switch between the two
                },*/
                weekends: true,
                events: [
                    {title: 'event 1', date: '2019-04-01'},
                    {title: 'event 2', date: '2019-04-02'}
                ],
                validRange: {
                    start: moment().valueOf(),
                },
                customButtons: {
                    prev: { // this overrides the prev button
                        click: () => {
                            console.log("eventPrev");
                            let calendarApi = this.$refs.fullCalendar.getApi();
                            calendarApi.prev();
                            //this.calendarKey--;
                        }
                    },
                    next: { // this overrides the next button
                        click: () => {
                            console.log("eventNext");
                            let calendarApi = this.$refs.fullCalendar.getApi();
                            calendarApi.next();
                            // this.calendarKey++;
                        }
                    }
                },
                eventContent: (arg) => {
                    let timeText = document.createElement('div')
                    timeText.innerHTML = arg.timeText;
                    //include additional info
                    let additionalText = document.createElement('i')

                    let arrayOfDomNodes = [];

                    timeText.className = "fc-event-time";

                    additionalText.className = "fc-event-title";
                    additionalText.innerHTML = 'Available';

                    arrayOfDomNodes = [timeText, additionalText];

                    if (this.appointmentStore.appointments) {
                        this.appointmentStore.appointments.every(function (appointment) {
                            if (appointment.date == moment(arg.event.start).format('YYYY-MM-DD') && appointment.time == moment(arg.event.start).format('HH:mm:ss')) {

                                timeText.className = "fc-event-time booked";

                                additionalText.className = "fc-event-title booked";
                                additionalText.innerHTML = 'Booked - ' + appointment.crm_customer.first_name + ' ' + appointment.crm_customer.last_name;

                                arrayOfDomNodes = [timeText, additionalText];

                                return false;
                            }

                            return true;
                        });
                    }

                    if (this.appointmentStore.calendar_data.unavailable_appointment_dates) {
                        this.appointmentStore.calendar_data.unavailable_appointment_dates.forEach(function (un) {
                            if (moment(arg.event.start).format('MM/DD/YYYY') === un.date) {
                                /*timeText.innerHTML = '<br>';
                                timeText.className = "fc-event-time unavailable";

                                additionalText.className = "fc-event-title unavailable";
                                additionalText.innerHTML = 'Unavailable';*/

                                timeText.innerHTML = '';
                                timeText.className = "fc-event-time unavailable";

                                additionalText.className = "fc-event-title unavailable";
                                additionalText.innerHTML = 'Unavailable';

                                arrayOfDomNodes = [timeText, additionalText];

                                return false;
                            }

                            return true
                        });
                    }

                    return {domNodes: arrayOfDomNodes};
                },
                eventClick: (info) => {
                    let _self = this;
                    if (this.appointmentStore.appointments) {
                        this.appointmentStore.appointments.every(function (appointment) {
                            if (appointment.date === moment(info.event.start).format('YYYY-MM-DD') && appointment.time === moment(info.event.start).format('HH:mm:ss')) {
                                _self.selectedAppointment = appointment;
                                $('#AppointmentDetailsModal').modal('show');
                                return false;
                            }

                            return true;
                        });
                    }
                }
            },
            fixedError: '',
            unavailableError: '',
            showReminderError: false,
            placeholders: {
                lead_first_name: "The first name of the lead/client.",
                lead_last_name: "The last name of the lead/client.",
                lead_email: "The email address of the lead/client.",
                lead_phone: "The phone number of the lead/client.",
                clinic_dr_name: "The name of the practitioner handling the appointment.",
                appointment_date: "The scheduled appointment date.",
                appointment_time: "The scheduled appointment time.",
                appointment_patient_type: "The type of patient (e.g., new, returning).",
                appointment_service_name: "The service being provided during the appointment.",
                appointment_comments: "Additional comments or requests made by the lead.",
                appointment_change_link: "A link for the lead to make changes to their appointment.",
                practice_name: "The name of the practice.",
                practice_contact_info: "The contact number of the practice."
            },
        };
    },
    components: {ckeditor: CKEditor.component, FullCalendar},
    mounted() {

        let _self = this;

        this.currentPage = this.$route.params.page ? this.$route.params.page : 'practice-information';

        this.clinicStore.find();
        this.clinicStore.getNurturingTemplates();
        this.clinicStore.getImmediateTemplates();
        this.clinicStore.getAutomationCampaign();
        this.appointmentStore.getServicesList();
        this.clinicStore.getToggleValues();
        this.appointmentStore.find();
        this.downloadSchedualdPlugin();


        this.parent_id = localStorage.getItem('parent_id');
        this.clinicStore.fetchDomainVerificationStatus()
            .then(status => {
                this.domainVerificationStatus = status;
            })
            .catch(error => {
                console.error('Failed to fetch domain verification status', error);
            });
        // this.initializeCKEditor();
        let page = this.$route.query.page;
        if (this.currentPage == 'email-templates') {
            $('.nav-item .nav-link').removeClass('active');
            $('.tab-content .tab-pane').removeClass('show').removeClass('active');
            $('#EmailTemplates-tab').addClass('active');
            $('#EmailTemplates').addClass('active').addClass('show');
            this.clinicStore.templateType = 'email';
            this.clinicStore.getTemplates('email');
        } else if (this.currentPage == 'text-templates') {
            $('.nav-item .nav-link').removeClass('active');
            $('.tab-content .tab-pane').removeClass('show').removeClass('active');
            $('#TextTemplates-tab').addClass('active');
            $('#TextTemplates').addClass('active').addClass('show');
            this.clinicStore.templateType = 'text';
            this.clinicStore.getTemplates('text');
        } else if (this.currentPage == 'appointment-calendar') {
            this.clinicStore.templateType = 'appointment';
            this.clinicStore.getTemplates('appointment');
            this.clinicStore.templateType = 'reminder-email';
            this.clinicStore.getTemplates('reminder-email');
            this.clinicStore.templateType = 'reminder-text';
            this.clinicStore.getTemplates('reminder-text');
        }

        $('#mailLink').removeClass('active');
        $('#homeLink').removeClass('active');
        $('#profileLink').addClass('show');

        IMask(document.getElementById("phone"), {
            mask: "(000) 000-0000",
        });

        IMask(document.getElementById("tracking_phone"), {
            mask: "(000) 000-0000",
        });

        IMask(document.getElementById("primary_doctor_phone"), {
            mask: "(000) 000-0000",
        });

        this.general_details_editing = false;
        //this.toggleStatus = this.automationCampaign.status === 'Active';

        this.buildAvailableTimes();

        if (this.currentPage === 'appointment-calendar') {
            _self.showCalendar = true;
        } else {
            _self.showCalendar = false;
        }

        this.adjustHeight(); // Adjust all textareas when the component is mounted
    },
    updated() {

        let _self = this;

        $("#clinic_logo").on('change', function () {
            let filename = this.files[0].name;
            $('#clinic_logo_name').html(" | " + filename);
        });

        $('input[name="fixed-date"]').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            minDate: moment(),
            locale: {
                format: 'MM/DD/YYYY'
            }
        });

        $('input[name="fixed-date"]').on('apply.daterangepicker', function (ev, picker) {
            _self.fixedError = '';
            let index = $('#fixed-table tr').index($(this).closest('tr'));
            let isDateAddCancelled = false;
            _self.appointmentStore.calendar_data.fixed_appointment_dates.forEach(function (date) {
                if (moment(date.date, 'MM/DD/YYYY').diff(moment(picker.startDate)) == 0) {
                    _self.fixedError = 'Cannot add again the already added date';
                    _self.appointmentStore.calendar_data.fixed_appointment_dates.splice(index, 1);
                    isDateAddCancelled = true;
                    return;
                }
            });
            if (!isDateAddCancelled)
                _self.appointmentStore.calendar_data.fixed_appointment_dates[index].date = picker.startDate.format('MM/DD/YYYY');
        });

        $('input[name="unavailable-date"]').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            minDate: moment(),
            locale: {
                format: 'MM/DD/YYYY'
            }
        });

        $('input[name="unavailable-date"]').on('apply.daterangepicker', function (ev, picker) {
            _self.unavailableError = '';
            let index = $('#unavailable-table tr').index($(this).closest('tr'));
            let isDateAddCancelled = false;
            _self.appointmentStore.calendar_data.unavailable_appointment_dates.forEach(function (date) {
                if (moment(date.date, 'MM/DD/YYYY').diff(moment(picker.startDate)) == 0) {
                    _self.unavailableError = 'Cannot add again the already added date';
                    _self.appointmentStore.calendar_data.unavailable_appointment_dates.splice(index, 1);
                    isDateAddCancelled = true;
                    return;
                }
            });
            if (!isDateAddCancelled)
                _self.appointmentStore.calendar_data.unavailable_appointment_dates[index].date = picker.startDate.format('MM/DD/YYYY');
            _self.calendarKey++;
        });

        if (this.appointmentStore.calendar_data.repeat_type == 'Does not repeat') {
            this.appointmentStore.calendar_data.fixed_appointment_dates.forEach(function (date, i) {
                $('#fixed-table tr:nth-child(' + (i + 1) + ') td input[name="fixed-date"]').data('daterangepicker').setStartDate(date.date);
            });

            let count = 0;
            this.calendarOptions.events = [];
            this.appointmentStore.calendar_data.fixed_appointment_dates.forEach(function (date, index) {
                date.start_times.forEach(function (time, i) {
                    const start = moment(date.date + ' ' + time, 'MM/DD/YYYY hh:mma');
                    const end = moment(date.date + ' ' + date.end_times[i], 'MM/DD/YYYY hh:mma');

                    let diff = end.diff(start, 'minutes');
                    while (diff >= _self.appointmentStore.calendar_data.duration) {
                        let isBooked = false;
                        let username = '';
                        _self.appointmentStore.appointments.forEach(function (appointment) {
                            if (moment(appointment.date, 'YYYY-MM-DD').format('MM/DD/YYYY') == date.date && start.format('HH:mm:ss') == appointment.time) {
                                isBooked = true;
                                username = appointment.crm_customer.first_name + ' ' + appointment.crm_customer.last_name;
                            }
                        });
                        _self.calendarOptions.events[count] = {
                            title: isBooked ? 'Booked - ' + username : 'Available',
                            start: start.valueOf(),
                            end: start.add(_self.appointmentStore.calendar_data.duration, 'm').valueOf(),
                            color: isBooked ? 'green' : ''
                        };
                        diff = end.diff(start, 'minutes');
                        count++;
                    }
                });
            });
        }

        if (this.appointmentStore.calendar_data.repeat_type == 'Repeat weekly') {

            let days_arr = ['sun', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat'];

            _self.calendarOptions.events = [];
            for (let day = 0; day < days_arr.length; day++) {
                let isUnavailableDate = false;
                /*if(this.appointmentStore.calendar_data.unavailable_appointment_dates != null){
                    this.appointmentStore.calendar_data.unavailable_appointment_dates.forEach(function(un, i){
                        console.log(moment(days_arr[day], 'ddd').format('MM/DD/YYYY'), un.date);
                        if(moment(days_arr[day], 'ddd').format('MM/DD/YYYY')===un.date){
                            isUnavailableDate = true
                        }
                    });
                }*/
                if (this.appointmentStore.calendar_data.availabilities[days_arr[day] + '_start'] != null) {
                    this.appointmentStore.calendar_data.availabilities[days_arr[day] + '_start'].forEach(function (time, i) {
                        const start = moment(time, 'hh:mma');
                        const end = moment(_self.appointmentStore.calendar_data.availabilities[days_arr[day] + '_end'][i], 'hh:mma');
                        let diff = end.diff(start, 'minutes');
                        while (diff >= _self.appointmentStore.calendar_data.duration) {
                            _self.calendarOptions.events.push({
                                title: 'Available',
                                daysOfWeek: [day],
                                startTime: start.format('HH:mm:ss'),
                                endTime: start.add(_self.appointmentStore.calendar_data.duration, 'm').format('HH:mm:ss')
                            });
                            diff = end.diff(start, 'minutes');
                        }
                    });
                }
            }

            this.appointmentStore.calendar_data.unavailable_appointment_dates.forEach(function (date, i) {
                $('#unavailable-table tr:nth-child(' + (i + 1) + ') td input[name="unavailable-date"]').data('daterangepicker').setStartDate(moment(date.date).format('MM/DD/YYYY'));
            });
        }
        // this.appointmentStore.getServicesList();
    },
    computed: {
        isVerifiable() {
            return this.domainVerificationStatus === 'Verify' || this.domainVerificationStatus === 'Failed, Verify again';
        },
        dkimRecords() {
            return this.clinicStore.clinic.dns_records.filter(record => record.type === 'CNAME');
        },
        dmarcRecords() {
            return this.clinicStore.clinic.dns_records.filter(record => record.type === 'TXT');
        },
        computedTrackingPhone: {
            get() {
                return this.clinicStore.clinic.tracking_phone || this.clinicStore.clinic.phone;
            },
            set(value) {
                this.clinicStore.clinic.tracking_phone = value;
            }
        },
        selectedAppointmentTemplate() {
            return this.clinicStore.selectedAppointmentTemplate || {
                template_name: '',
                subject: 'Your appointment has been successfully scheduled.',
                body: `
                Hi {{lead_first_name}} {{lead_last_name}},
                <br/><br/>
                Your appointment has been successfully scheduled. Please review the details below to ensure everything is correct.
                It is essential you reply to this email to confirm your appointment.
                <br/><br/>

                <u><b>Personal Details :- </b></u>
                <br>
                Name - {{lead_first_name}} {{lead_last_name}}<br>
                Email - {{lead_email}}<br>
                Phone - {{lead_phone}}<br>
                <br>
                <u><b>Appointment Details :- </b></u>
                <br>
                Practitioner - {{clinic_dr_name}}<br>
                Date/Time - {{appointment_date}} at {{appointment_time}}<br>
                Patient - {{appointment_patient_type}}<br>
                Service - {{appointment_service_name}}<br>
                Comments/Requests - {{appointment_comments}}<br>
                <br><br>

                If you want to make changes to your scheduled appointment please click below. <br>
                {{appointment_change_link}}
                <br><br>
                <ul>
                    <li>You will receive reminders via email and text message as your appointment date approaches. These reminders are to help ensure that you do not miss your scheduled time.</li>
                    <li>Please make every effort to attend your appointment. Missing an appointment can delay your treatment and affect your dental health. If you need to reschedule, please contact us at least 24 hours in advance to avoid any cancellation fees and to help us provide timely care to other patients.</li>
                </ul>
                <br><br>

                Regards,<br>
                Client Success Representative<br>
                Team CRTX`
            };
        },
        selectedReminderEmailTemplate() {
            return this.clinicStore.selectedReminderEmailTemplate || {
                template_name: '',
                subject: `Reminder: Your Upcoming Appointment with Dr. {{clinic_dr_name}}`,
                body: `
                <b>Hi {{lead_first_name}} {{lead_last_name}}</b>,
                <br/>
                This is a friendly reminder that your appointment with Dr. {{clinic_dr_name}} is coming up. Please review the details below to ensure everything is correct:
                <br/>
                <u><b>Appointment Details: </b></u>
                <br>
                <ul>
                    <li><b>Date/Time</b>: {{appointment_date}} at {{appointment_time}}</li>
                    <li><b>Practitioner</b>: {{clinic_dr_name}}</li>
                    <li><b>Service</b>: {{appointment_service_name}}<br></li>
                </ul>
                <br>
                For any questions, feel free to contact us at {{practice_contact_info}}.<br><br>
                We look forward to seeing you soon!<br>
                <b>{{practice_name}}</b>`
            };
        },
        selectedReminderTextTemplate() {
            return this.clinicStore.selectedReminderTextTemplate || {
                template_name: '',
                body: `Hi {{lead_first_name}} {{lead_last_name}}, this is a reminder that your appointment with Dr. {{clinic_dr_name}} is coming up on {{appointment_date}} at {{appointment_time}}. Need to reschedule? Contact us at {{practice_contact_info}} at least 24 hours in advance. Looking forward to your visit! – {{practice_name}}`
            };
        },
        editorConfig() {
            return {
                // Other configurations
                readOnly: !this.appointment_template_editing, // Use computed value for readOnly
                extraPlugins: [this.uploader],
                toolbar: {
                    items: [
                        'undo', 'redo',
                        '|',
                        'heading',
                        '|',
                        'fontfamily', 'fontsize', 'fontColor', 'fontBackgroundColor',
                        '|',
                        'bold', 'italic', 'strikethrough', 'subscript', 'superscript', 'code',
                        '|',
                        'link', 'uploadImage', 'blockQuote', 'codeBlock',
                        '|',
                        'alignment',
                        '|',
                        'bulletedList', 'numberedList', 'todoList', 'outdent', 'indent'
                    ],
                    shouldNotGroupWhenFull: true
                },
                image: {
                    toolbar: [
                        'imageTextAlternative', 'imageStyle:full', 'imageStyle:side'
                    ]
                },
                table: {
                    contentToolbar: ['tableColumn', 'tableRow', 'mergeTableCells']
                },
                mediaEmbed: {
                    previewsInData: true
                }
            };
        },
    },
    watch: {
        '$data': {
            deep: true,
            handler() {
                this.$nextTick(() => {
                    this.adjustHeight(); // Adjust height for all textareas when any data changes
                });
                // this.appointmentStore.getServicesList();
            }

        },
    },
    methods: {
        toggleCreateService() {
            this.showCreateService = !this.showCreateService;
            this.newServiceName = "";
            this.errorMessage = "";
            this.successMessage = "";
        },
        createService() {
            const clinicId = localStorage.getItem("clinic_id");

            if (!this.newServiceName.trim()) {
                this.errorMessage = "Service name is required!";
                return;
            }

            axios
                .post("/api/v1/services", {
                    name: this.newServiceName,
                    clinic_id: clinicId,
                })
                .then((response) => {
                    if (response.data.success) {
                        this.successMessage = "Service created successfully!";
                        this.appointmentStore.getServicesList();
                        this.newServiceName = "";
                        this.errorMessage = "";
                    }
                })
                .catch((error) => {
                    if (error.response && error.response.data && error.response.data.errors) {
                        this.errorMessage = error.response.data.errors.name
                            ? error.response.data.errors.name[0]
                            : "An error occurred!";
                    } else {
                        this.errorMessage = "An error occurred!";
                    }
                });
        },
        verifyDomain() {
            axios.post(`/api/v1/clinics/${this.clinicStore.clinic.id}/verify`)
                .then(response => {
                    this.domainVerificationStatus = 'Verification pending';
                    // Swal.fire({
                    //     title: 'Email Sent!',
                    //     text: 'A verification email has been sent. Please verify.',
                    //     icon: 'success',
                    //     confirmButtonText: 'OK'
                    // });
                })
                .catch(error => {
                    console.error(error);
                    alert('An error occurred while sending the verification email.');
                });
        },
        general_details_editing_save() {
            this.submit(1);

            if (Object.keys(this.clinicStore.errors).length > 0) {
                return null;
            }

            this.general_details_editing = false;
        },
        office_details_editing_save() {
            this.submit(2);

            if (Object.keys(this.clinicStore.errors).length > 0) {
                return null;
            }

            this.office_details_editing = false;
        },
        staff_access_editing_save() {
            if (!this.clinicStore.staff_emails[0]) {
                this.clinicStore.errors.staff_emails = "Please enter an Email Address";
                return null;
            }

            this.submit(3);

            if (Object.keys(this.clinicStore.errors).length > 0) {
                return null;
            }

        },
        media_content_editing_save() {
            this.submit(4);

            if (Object.keys(this.clinicStore.errors).length > 0) {
                return null;
            }

            this.media_content_editing = false;
        },
        marketing_budget_editing_save() {
            this.submit(5);

            if (Object.keys(this.clinicStore.errors).length > 0) {
                return null;
            }

            this.marketing_budget_editing = false;
        },
        callrail_installtion_editing_save() {
            this.submit(6);

            if (Object.keys(this.clinicStore.errors).length > 0) {
                return null;
            }

            this.callrail_installtion_editing = false;
        },
        appointment_editing_save(section) {
            if (section == 'detail') {
                if (this.appointmentStore.calendar_data.calendar_type.length == 0) {
                    this.appointmentStore.errors.calendar_type = 'At least one calendar type must be selected.';
                } else {
                    this.appointmentStore.errors.calendar_type = '';
                }

                if (this.appointmentStore.calendar_data.patient_types.length == 0) {
                    this.appointmentStore.errors.patient_types = 'At least one patient type must be selected.';
                } else {
                    this.appointmentStore.errors.patient_types = '';
                }

                if (this.appointmentStore.calendar_data.service_types.length == 0) {
                    this.appointmentStore.errors.service_types = 'At least one service type must be selected.';
                } else {
                    this.appointmentStore.errors.service_types = '';
                }

                if (this.clinicStore.clinic.emails_for_scheduling === null || this.clinicStore.clinic.emails_for_scheduling === '') {
                    this.appointmentStore.errors.emails_for_scheduling = 'Recipients Email is required.';
                } else {
                    this.appointmentStore.errors.emails_for_scheduling = '';
                }

                if (this.appointmentStore.errors.calendar_type != '' || this.appointmentStore.errors.patient_types != '' ||
                    this.appointmentStore.errors.service_types != '' || this.appointmentStore.errors.emails_for_scheduling != '') {
                    return;
                }
            } else {

                let _self = this;

                _self.showReminderError = false;

                _self.scheduling_errors = [];

                let days_arr = ['sun', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat'];

                for (let day = 0; day < days_arr.length; day++) {
                    if (this.appointmentStore.calendar_data.availabilities[days_arr[day] + '_start'] != null && this.appointmentStore.calendar_data.availabilities[days_arr[day] + '_end'] != null) {
                        this.appointmentStore.calendar_data.availabilities[days_arr[day] + '_start'].forEach(function (time, i) {
                            const start = moment(time, 'hh:mma');
                            const end = moment(_self.appointmentStore.calendar_data.availabilities[days_arr[day] + '_end'][i], 'hh:mma');
                            if (end.diff(start, 'minutes') < _self.appointmentStore.calendar_data.duration) {
                                _self.scheduling_errors.push(' ' + days_arr[day].toUpperCase() + ':- Difference in time is less than duration.\n')
                            }
                        });
                    }
                }
                if (_self.scheduling_errors.length > 0) {
                    return null;
                }

                let reminderError = false;
                this.appointmentStore.calendar_data.reminders.forEach(function (reminder, index) {
                    if (reminder.duration > _self.getMaxDurationValue(index)) {
                        _self.showReminderError = true;
                        reminderError = true
                    }
                });

                if (reminderError) {
                    $('html, body').animate({
                        scrollTop: $("#notification-reminder").offset().top
                    }, 2000);
                    return null;
                }
            }

            this.appointmentStore.save(section);
            this.calendar_details_editing = false;
            this.calendar_schedule_editing = false;
            this.appointment_template_editing = false;
        },
        buildAvailableTimes() {

            if (this.appointmentStore.calendar_data.duration == 'custom') {
                $('#CustomDurationModal').modal('show');
            }

            let interval = parseInt(15);

            const periodsInADay = moment.duration(1, 'day').as('minutes');

            const timeLabels = [];
            const startTimeMoment = moment('00:00', 'hh:mm');
            for (let i = 0; i <= periodsInADay; i += interval) {
                startTimeMoment.add(i === 0 ? 0 : interval, 'minutes');
                timeLabels.push(startTimeMoment.format('hh:mma'));
            }

            this.calendar_times_start = timeLabels;
            this.calendar_times_end = timeLabels;
        },
        addAvailability(day) {
            if (this.appointmentStore.calendar_data.availabilities[day + '_start'] == null)
                this.appointmentStore.calendar_data.availabilities[day + '_start'] = [];

            if (this.appointmentStore.calendar_data.availabilities[day + '_end'] == null)
                this.appointmentStore.calendar_data.availabilities[day + '_end'] = [];

            let _self = this;
            let start_len = this.appointmentStore.calendar_data.availabilities[day + '_start'].length;

            /*setTimeout(function(){
                $('#start-avail'+(day)+(start_len)+' option').filter(function () {
                    return moment(this.value, 'hh:mma').isBefore(moment(_self.appointmentStore.calendar_data.availabilities[day+'_end'][start_len-1], 'hh:mma'));
                }).remove();
                $('#end-avail'+(day)+(start_len)+' option').filter(function () {
                    return moment(this.value, 'hh:mma').isBefore(moment(_self.appointmentStore.calendar_data.availabilities[day+'_end'][start_len-1], 'hh:mma'));
                }).remove();
            }, 1000);*/

            this.appointmentStore.calendar_data.availabilities[day + '_start'].push('09:00am');
            this.appointmentStore.calendar_data.availabilities[day + '_end'].push('07:00pm');
        },
        removeAvailability(day) {
            this.appointmentStore.calendar_data.availabilities[day + '_start'] = null;
            this.appointmentStore.calendar_data.availabilities[day + '_end'] = null;
        },
        copyAvailability(day) {
            let availability_start = this.appointmentStore.calendar_data.availabilities[day + '_start'];
            let availability_end = this.appointmentStore.calendar_data.availabilities[day + '_end'];

            let days = ['sun', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat'];
            for (let d = 0; d < days.length; d++) {
                this.appointmentStore.calendar_data.availabilities[days[d] + '_start'] = availability_start;
                this.appointmentStore.calendar_data.availabilities[days[d] + '_end'] = availability_end;
            }
        },
        dayStartChange($e, day, index) {
            this.appointmentStore.calendar_data.availabilities[day + '_start'][index] = $e.target.value;

            /* $('#end-avail'+day+(index)+' option').filter(function () {
                 return moment(this.value, 'hh:mma').isBefore(moment($e.target.value, 'hh:mma'));
             }).remove();*/
        },
        dayEndChange($e, day, index) {
            this.appointmentStore.calendar_data.availabilities[day + '_end'][index] = $e.target.value;

            /*  $('#start-avail'+(day)+(index+1)+' option').filter(function () {
                  return moment(this.value, 'hh:mma').isBefore(moment($e.target.value, 'hh:mma'));
              }).remove();

              $('#end-avail'+(day)+(index+1)+' option').filter(function () {
                  return moment(this.value, 'hh:mma').isBefore(moment($e.target.value, 'hh:mma'));
              }).remove();*/
        },
        fixedDayStartChange($e, i, index) {
            this.appointmentStore.calendar_data.fixed_appointment_dates[i].start_times[index] = $e.target.value;

            $('#end-select' + (i) + (index) + ' option').filter(function () {
                return moment(this.value, 'hh:mma').isBefore(moment($e.target.value, 'hh:mma'));
            }).remove();
        },
        fixedDayEndChange($e, i, index) {
            this.appointmentStore.calendar_data.fixed_appointment_dates[i].end_times[index] = $e.target.value;

            $('#start-select' + (i) + (index + 1) + ' option').filter(function () {
                return moment(this.value, 'hh:mma').isBefore(moment($e.target.value, 'hh:mma'));
            }).remove();

            $('#end-select' + (i) + (index + 1) + ' option').filter(function () {
                return moment(this.value, 'hh:mma').isBefore(moment($e.target.value, 'hh:mma'));
            }).remove();
        },
        setCustomDuration() {
            this.appointmentStore.calendar_data.duration = $('#custom-duration').val();
            this.buildAvailableTimes();
            $('#CustomDurationModal').modal('hide');
            let options = document.getElementById('duration-select').options;
            for (let i = 0; i < options.length - 1; i++) {
                if (options[i].value == $('#custom-duration').val()) {
                    return;
                }
            }

            $("#duration-select option").eq(options.length - 1).before('<option value="' + $('#custom-duration').val() + '">' + $('#custom-duration').val() + ' Minutes</option>');
        },
        addFixedDateTime() {
            this.appointmentStore.calendar_data.fixed_appointment_dates.push({
                id: 0,
                date: moment().format('MM/DD/YYYY'),
                start_times: ['09:00am'],
                end_times: ['07:00pm']
            });
        },
        addSameDayFixedDateTime(index) {
            let _self = this;
            let start_len = this.appointmentStore.calendar_data.fixed_appointment_dates[index].start_times.length;
            setTimeout(function () {
                $('#start-select' + (index) + (start_len) + ' option').filter(function () {
                    return moment(this.value, 'hh:mma').isBefore(moment(_self.appointmentStore.calendar_data.fixed_appointment_dates[index].end_times[start_len - 1], 'hh:mma'));
                }).remove();
                $('#end-select' + (index) + (start_len) + ' option').filter(function () {
                    return moment(this.value, 'hh:mma').isBefore(moment(_self.appointmentStore.calendar_data.fixed_appointment_dates[index].end_times[start_len - 1], 'hh:mma'));
                }).remove();
            }, 1000);

            this.appointmentStore.calendar_data.fixed_appointment_dates[index].start_times.push('09:00am');
            this.appointmentStore.calendar_data.fixed_appointment_dates[index].end_times.push('07:00pm');
        },
        removeSameDayFixedDateTime(index) {
            this.appointmentStore.calendar_data.fixed_appointment_dates.splice(index, 1);
        },
        addUnavailableAppointmentDate() {
            this.appointmentStore.calendar_data.unavailable_appointment_dates.push({
                id: 0,
                date: moment().format('MM/DD/YYYY')
            });
        },
        removeUnavailableAppointmentDate(index) {
            this.appointmentStore.calendar_data.unavailable_appointment_dates.splice(index, 1);
        },
        addReminderNotification() {
            this.appointmentStore.calendar_data.reminders.push({id: null, type: 'email', interval: 1, unit: 'minutes'})
        },
        removeReminderNotification(index) {
            this.appointmentStore.calendar_data.reminders.splice(index, 1);
        },
        getSelectedServiceTypes() {
            let _self = this;
            let serviceTypes = [];
            if (this.appointmentStore.service_types)
                this.appointmentStore.service_types.forEach(function (type) {
                    if (_self.appointmentStore.calendar_data.service_types.includes(type.id + '')) {
                        serviceTypes.push(type.name);
                    }
                });

            return serviceTypes.join(',');
        },
        copyDirectLink() {
            navigator.clipboard.writeText(window.location.origin + '/crtx/schedule-appointment/' + this.authStore.clinic_id + '/sa-step-1');
            alert('Link copied to clipboard.');
        },
        submit(section) {
            this.clinicStore.errors = [];

            // General Details Validation

            if (section == 1) {
                /* if (!this.clinicStore.clinic.industry) {
                     this.clinicStore.errors["industry"] = "Industry field is required.";
                 }*/

                if (!this.clinicStore.clinic.website_url) {
                    this.clinicStore.errors["website_url"] = "Website URL field is required.";
                }

                if (!this.clinicStore.clinic.practice_name) {
                    this.clinicStore.errors["practice_name"] = "Practice Name field is required.";
                }

                if (!this.clinicStore.clinic.practice_legal_name) {
                    this.clinicStore.errors["practice_legal_name"] =
                        "Practice Legal Name field is required.";
                }

                if (!this.clinicStore.clinic.practice_email) {
                    this.clinicStore.errors["practice_email"] = "Practice Email field is required.";
                } else if (
                    !/^[^@]+@\w+(\.\w+)+\w$/.test(this.clinicStore.clinic.practice_email)
                ) {
                    this.clinicStore.errors["practice_email"] = "Invalid email address entered.";
                }

                if (!this.clinicStore.clinic.phone) {
                    this.clinicStore.errors["phone"] = "Phone field is required.";
                }

                /* if (!this.clinicStore.clinic.practice_specialty) {
                     this.clinicStore.errors["practice_specialty"] =
                         "Practice Speciality field is required.";
                 } else if (this.clinicStore.clinic.practice_specialty.includes('Multi-Speciality') && !this.clinicStore.clinic.practice_specialty_other) {
                     this.clinicStore.errors["practice_specialty_other"] = 'Multi-Speciality field is required.';
                 }

                 if (!this.clinicStore.clinic.primary_services) {
                     this.clinicStore.errors["primary_services"] =
                         "Primary Services field is required.";
                 }*/

                /*  if (!this.clinicStore.clinic.practice_management_system) {
                      this.clinicStore.errors["practice_management_system"] =
                          "Practice Management System field is required.";
                  }
  */
                // if(this.clinicStore.clinic.practice_management_system == 'Other' && !this.clinicStore.clinic.other_management_system){
                // 	this.clinicStore.errors['other_management_system'] = 'Other Management System field is required.';
                // }

                if (this.clinicStore.website_information_accurate == 'No' && !this.clinicStore.clinic.website_information_detail) {
                    this.clinicStore.errors["website_information_detail"] =
                        "Website Information Details field is required.";
                }

                this.clinicStore.logoData.append('clinic_id', this.authStore.clinic_id)
                var imagefile = document.querySelector('#clinic_logo');
                if (imagefile.files.length > 0) {
                    this.clinicStore.logoData.append('clinic_logo', imagefile.files[0]);
                }
            }

            // Office Details Validation

            if (section == 2) {
                if (!this.clinicStore.clinic.address) {
                    this.clinicStore.errors["address"] = "Street Address field is required.";
                }

                if (!this.clinicStore.clinic.town) {
                    this.clinicStore.errors["town"] = "City field is required.";
                }

                if (!this.clinicStore.clinic.state) {
                    this.clinicStore.errors["state"] = "Street Address field is required.";
                }

                if (!this.clinicStore.clinic.zip_code) {
                    this.clinicStore.errors["zip_code"] = "Zip Code field is required.";
                }

                /*if (!this.clinicStore.clinic.office_hours) {
                    this.clinicStore.errors["office_hours"] = "Office Hours field is required.";
                }*/

                /*if (!this.clinicStore.clinic.holidays) {
                    this.clinicStore.errors["holidays"] = "Holidays field is required.";
                }*/

                var self = this;

                /* if (this.clinicStore.clinic.locations.length > 0) {
                     this.clinicStore.clinic.locations.every(function (location) {
                         if (
                             !location.office_name ||
                             !location.street_address ||
                             !location.town ||
                             !location.state ||
                             !location.zip_code
                         ) {
                             self.clinicStore.errors["locations"] = "All fields are required";
                             return false;
                         }
                         return true;
                     });
                 }*/

                // if (!this.clinicStore.clinic.multiple_doctors) {
                //     this.errors['multiple_doctors'] = 'Multiple Doctors field is required.';
                // }
            }

            // Staff Emails Validation

            if (section == 3) {
                if (this.clinicStore.staff_emails) {
                    this.clinicStore.staff_emails.every(function (email) {
                        if (!/^[^@]+@\w+(\.\w+)+\w$/.test(email)) {
                            self.clinicStore.errors["staff_emails"] =
                                "Staff emails contains invalid emails!";
                            return false;
                        }
                        return true;
                    });
                }
            }

            if (section == 4) {
                if (this.clinicStore.clinic.nurture_automation == 'Yes') {
                    if (!this.clinicStore.clinic.scheduling_link) {
                        this.clinicStore.errors['scheduling_link'] = 'Scheduling Link field is required!';
                    }
                    if (!this.clinicStore.clinic.link1) {
                        this.clinicStore.errors['link1'] = 'Link-1 field is required!';
                    }
                    if (!this.clinicStore.clinic.link2) {
                        this.clinicStore.errors['link2'] = 'Link-2 field is required!';
                    }
                    if (!this.clinicStore.clinic.link3) {
                        this.clinicStore.errors['link3'] = 'Link-3 field is required!';
                    }
                }
            }

            // console.log(this.clinicStore.errors);

            if (Object.keys(this.clinicStore.errors).length == 0) {
                this.clinicStore.save(section);
            } else {
                this.alertStore.success = false;
                this.alertStore.message = 'There are validation errors on your form submission. Please fix them to save form!'
            }
        },
        addLocation() {
            this.clinicStore.clinic.locations.push({
                office_name: "",
                street_address: "",
                city: "",
                state: "",
                zip_code: "",
            });
        },
        deleteLocation(index) {
            this.clinicStore.clinic.locations.splice(index, 1);
        },
        deleteEmail(index) {
            this.clinicStore.staff_emails.splice(index, 1);
        },
        addStaffEmail() {
            this.clinicStore.staff_emails.push('');
        },
        resetActiveTabs() {
            this.general_details_editing = false;
            this.office_details_editing = false;
            this.staff_access_editing = false;
            this.media_content_editing = false;
            this.marketing_budget_editing = false;
            this.callrail_installtion_editing = false;
            this.nurturing_template_editing = false;
            this.email_template_editing = false;
            this.text_template_editing = false;
            this.calendar_schedule_editing = false;
            this.calendar_details_editing = false;
            this.appointment_template_editing = false;
        },
        showProfilePage() {
            $('#profileLink').trigger('click');
        },
        resendVerification(email) {
            this.clinicStore.resendVerification(email);
        },
        setDeleteStaffMember(email) {
            this.deleteStaffEmail = email;
        },
        deleteStaffMember() {
            this.clinicStore.deleteStaffMember(this.deleteStaffEmail);
            this.clinicStore.find();
            $('#DeleteStaffEmailConfirm').modal('hide');
        },
        editNurturing(type, template, dayinterval) {
            this.editTitle = 'Day ' + dayinterval + ': ' + type;
            this.dayInterval = dayinterval;
            this.editContent = template;
            let arr = type.split(' ');
            this.editType = arr[0].toLowerCase() + '_' + arr[1].toLowerCase();
            $('#editNurturingModal').modal('show');
        },
        editImmediate(type, template, dayinterval, emailSubject = '') {
            this.editTitle = type;
            this.dayInterval = dayinterval;
            this.editContent = template;
            this.emailSubject = emailSubject; // Set emailSubject
            let arr = type.split(' ');
            this.editType = arr[0].toLowerCase() + '_' + arr[1].toLowerCase();
            $('#editImmediateModal').modal('show');
        },
        editEmailTextTemplate() {
            this.clinicStore.templateErrors = [];
            this.clinicStore.templateAction = 'edit';

            if (this.clinicStore.templateType === 'email') {
                this.clinicStore.template.template_name = this.clinicStore.selectedEmailTemplate.template_name;
                this.clinicStore.template.subject = this.clinicStore.selectedEmailTemplate.subject;
                this.clinicStore.template.body = this.clinicStore.selectedEmailTemplate.body;
            } else if (this.clinicStore.templateType === 'text') {
                this.clinicStore.template.template_name = this.clinicStore.selectedTextTemplate.template_name;
                this.clinicStore.template.subject = '';
                this.clinicStore.template.body = this.clinicStore.selectedTextTemplate.body;
            }

            $('#templateModal').modal('show');
        },
        addEmailTextTemplate() {
            this.clinicStore.templateErrors = [];
            this.clinicStore.templateAction = 'add';
            this.clinicStore.template = {template_name: '', subject: '', body: ''};
            $('#templateModal').modal('show');
        },
        updateNurturingTemplate() {
            let _self = this;
            let data = {
                content: this.editContent,
                type: this.editType,
                interval: this.dayInterval,
            };
            this.clinicStore.saveNurturingTemplate(data).then(function (response) {
                $('#editNurturingModal').modal('hide');
            });
        },
        updateImmediateTemplate() {
            let _self = this;

            let data = {
                content: this.editContent,
                type: this.editType,
                interval: this.dayInterval,
            };

            if (this.editType === 'email_template') {
                data.email_subject = this.emailSubject;
            }

            this.clinicStore.saveNurturingTemplate(data).then(function (response) {
                $('#editImmediateModal').modal('hide');
            });
        },
        updateEmailTextTemplate(type) {
            this.clinicStore.updateTemplate();
        },
        async updateAppointmentTemplate() {
            let _self = this;
            this.appointmentStore.errors = {};

            // Validation
            let isValid = true;

            if (!this.selectedAppointmentTemplate.subject || this.selectedAppointmentTemplate.subject.trim() === '') {
                this.appointmentStore.errors.subject = 'Email Subject is required.';
                isValid = false;
            }
            if (!this.selectedAppointmentTemplate.body || this.selectedAppointmentTemplate.body.trim() === '') {
                this.appointmentStore.errors.body = 'Email Body is required.';
                isValid = false;
            }


            // If not valid, stop execution
            if (!isValid) {
                return;
            }
            try {
                this.selectedAppointmentTemplate.template_name = 'Appointment Template';

                let templateData = {
                    id: this.selectedAppointmentTemplate.id,
                    type: 'appointment',
                    template_name: this.selectedAppointmentTemplate.template_name,
                    subject: this.selectedAppointmentTemplate.subject || '',
                    body: this.selectedAppointmentTemplate.body,
                };
                console.log(templateData);
                this.appointment_template_editing = false;

                // Call the update function and wait for the result
                await this.clinicStore.updateappointmentEmailTemplate(templateData);
                this.appointment_template_editing = false;
            } catch (error) {
                console.error('Error saving template:', error);
            }
        },
        updateReminderEmailTemplate() {
            let _self = this;
            this.appointmentStore.reminderEmailErrors = {};

            // Validation
            let isValid = true;

            if (!this.selectedReminderEmailTemplate.subject || this.selectedReminderEmailTemplate.subject.trim() === '') {
                this.appointmentStore.reminderEmailErrors.subject = 'Email Subject is required.';
                isValid = false;
            }
            if (!this.selectedReminderEmailTemplate.body || this.selectedReminderEmailTemplate.body.trim() === '') {
                this.appointmentStore.reminderEmailErrors.body = 'Email Body is required.';
                isValid = false;
            }


            // If not valid, stop execution
            if (!isValid) {
                return;
            }
            try {
                this.selectedReminderEmailTemplate.template_name = 'Reminder Email Template';

                let templateData = {
                    id: this.selectedReminderEmailTemplate.id,
                    type: 'reminder-email',
                    template_name: this.selectedReminderEmailTemplate.template_name,
                    subject: this.selectedReminderEmailTemplate.subject || '',
                    body: this.selectedReminderEmailTemplate.body,
                };

                this.reminder_email_template_editing = false;

                // Call the update function and wait for the result
                this.clinicStore.updateappointmentEmailTemplate(templateData);
                this.appointment_template_editing = false;
            } catch (error) {
                console.error('Error saving template:', error);
            }
        },
        updateReminderTextTemplate() {
            let _self = this;
            this.appointmentStore.reminderTextErrors = {};

            // Validation
            let isValid = true;


            if (!this.selectedReminderTextTemplate.body || this.selectedReminderTextTemplate.body.trim() === '') {
                this.appointmentStore.reminderTextErrors.body = 'Email Body is required.';
                isValid = false;
            }


            // If not valid, stop execution
            if (!isValid) {
                return;
            }
            try {
                this.selectedReminderTextTemplate.template_name = 'Reminder Text Template';

                let templateData = {
                    id: this.selectedReminderTextTemplate.id,
                    type: 'reminder-text',
                    template_name: this.selectedReminderTextTemplate.template_name,
                    body: this.selectedReminderTextTemplate.body,
                };

                this.reminder_text_template_editing = false;

                // Call the update function and wait for the result
                this.clinicStore.updateappointmentEmailTemplate(templateData);
                this.appointment_template_editing = false;
            } catch (error) {
                console.error('Error saving template:', error);
            }
        },
        deleteTemplate() {
            this.clinicStore.deleteTemplate();
        },
        emailTabClicked() {
            this.clinicStore.templateType = 'email';
            this.clinicStore.getTemplates('email');
        },
        textTabClicked() {
            this.clinicStore.templateType = 'text';
            this.clinicStore.getTemplates('text');
        },
        appointmentTabClicked() {
            this.clinicStore.templateType = 'appointment';
            this.clinicStore.getTemplates('appointment');
        },
        handleToggleChange(value) {
            const clinicId = this.authStore.clinic_id;
            this.clinicStore.updateToggles(clinicId, value.id);
        },
        viewStatusAutomation(id) {
            localStorage.setItem('last_path', '/crtx/automation/' + id);
            window.open('/crtx/automation/' + id, '_blank');
        },
        htmlEntities(input) {
            return htmlEntities.decode(input);
        },
        copyToClipboard() {
            const script = this.clinicStore.clinic.callrail_installation_script;
            navigator.clipboard.writeText(script);
            console.log('Link copied to clipboard.');
        },
        uploader(editor) {
            editor.plugins.get('FileRepository').createUploadAdapter = (loader) => {
                return new UploadAdapter(loader);
            };

        },
        copyToClipboardDNS(text) {
            navigator.clipboard.writeText(text).then(() => {
                console.log('Copied to clipboard');
            }).catch(err => {
                console.error('Could not copy text: ', err);
            });
        },
        getToggleValues() {
            this.clinicStore.getToggleValues()
        },
        toggleTemplateStatus(templateType) {
            this.clinicStore.toggleTemplateStatus(templateType)
        },
        handleFileUpload(event) {
            const file = event.target.files[0];
            const reader = new FileReader();

            this.clinicStore.clinic.clinic_logo_name = file.name;
            reader.onload = (e) => {
                this.clinicStore.clinic.clinic_logo = e.target.result;
            };

            if (file) {
                reader.readAsDataURL(file);
            }
        },
        adjustHeight() {
            const textareas = [
                this.$refs.primarySellingMessage,
                this.$refs.holidays,
                this.$refs.promotional_specials_desc,
                this.$refs.office_hours,
                this.$refs.multiple_doctors,
                this.$refs.testimonial_link,
                this.$refs.doctors_biography_description,
                this.$refs.doctors_biography_summary_description,
                this.$refs.marketing_message_description,
                // Add more references as needed
            ];
            textareas.forEach(textarea => {
                if (textarea) {
                    textarea.style.height = 'auto'; // Reset height
                    textarea.style.height = textarea.scrollHeight + 'px'; // Set to scroll height
                }
            });
        },
        viewProfile(id) {
            localStorage.setItem('last_path', '/crtx/patient-profile/' + id);
            window.open('/crtx/patient-profile/' + id, '_blank');
        },
        dateFormat(date) {
            if (date) {
                return moment(date, 'YYYY-MM-DD').format('dddd, MMMM Do YYYY')
            }
            return ''
        },
        timeFormat(time) {
            if (time) {
                return moment(time, 'HH:mm:ss').format('hh:mm A');
            }
            return ''
        },
        formatPlaceholder(placeholder) {
            return `{{${placeholder}}}`;
        },
        copyToClipboardDownload(value) {
            const el = document.createElement('textarea');
            el.value = value;
            document.body.appendChild(el);
            el.select();
            document.execCommand('copy');
            document.body.removeChild(el);

            // Optionally, show a success message or feedback to the user
            alert('Copied to clipboard!');
        },
        async downloadSchedualdPlugin() {
            try {
                const response = await axios.get('/get-env-data');
                this.envData = response.data.env_data.SCHEDUAL_APPOINTMENT_LINK;
            } catch (error) {
                console.error('Error fetching environment variables:', error);
            }
        },
        getMaxDurationValue(index) {
            let value = 0;
            switch (this.appointmentStore.calendar_data.reminders[index].unit) {
                case 'minutes':
                    value = 28 * 12 * 60;
                    break;
                case 'hours':
                    value = 28 * 60;
                    break;
                case 'days':
                    value = 28;
                    break;
                case 'weeks':
                    value = 28 / 7;
                    break;
            }

            return value;
        },
        refreshCalendar() {
            this.$refs.fullCalendar.getApi();
        }
    },

};

</script>
<style>
.fc-event-title {
    width: 100%;
}

.fc-v-event {
    border-color: #fff;
}

.fc-event-main:has(div.booked) {
    background-color: green;
    border-color: green;
    margin: 0px;
    cursor: pointer;
}

.fc-event-main:has(div.unavailable) {
    background: #ddd;
    border-color: #ddd;
    margin: 0px;
    color: #333;
}
</style>

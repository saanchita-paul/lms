<template>
    <div id="main" class="user-profile">
        <div class="container-fluid p-0">
            <div class="mt-4">
                <div class="page-back-btn d-lg-none d-flex">
                    <a role="button" class="back-btn" @click="showProfilePage">
                        <svg width="20" height="14" viewBox="0 0 20 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                  d="M19.4411 6.37497H2.04859L7.25172 1.19185C7.49609 0.947473 7.49609 0.551848 7.25172 0.308098C7.00734 0.0637231 6.61172 0.0637231 6.36797 0.308098L0.180469 6.49497C-0.0601562 6.7356 -0.0601562 7.1381 0.180469 7.37872L6.36797 13.5662C6.61234 13.8106 7.00797 13.8106 7.25172 13.5662C7.49609 13.3218 7.49609 12.9262 7.25172 12.6825L2.04859 7.62497H19.4411C19.7861 7.62497 20.0661 7.34497 20.0661 6.99997C20.0661 6.65497 19.7861 6.37497 19.4411 6.37497Z"
                                  fill="#514F5F"/>
                        </svg>
                    </a>
                    <a class="save-input tab-save" target="_blank"
                       href="https://billing.stripe.com/p/login/9AQ5mJeYZ5jQ1y09AA" id="UpdateBilling-tab"
                       type="button" role="tab" aria-selected="false">Update Billing</a>
                </div>
                <div class="page-title">
                    <h3>Manage Your Account</h3>
                </div>
                <div v-if="this.authStore.profileUpdateSuccess" class="alert alert-success alert-dismissible fade show"
                     role="alert">
                    {{ this.authStore.profileUpdateMessage }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"
                            @click="this.authStore.profileUpdateSuccess = false"></button>
                </div>
                <div v-if="!authStore.profileUpdateSuccess && authStore.profileUpdateMessage"
                     class="alert alert-danger alert-dismissible fade show" role="alert">
                    There are errors in your form submission! {{ this.authStore.profileUpdateMessage }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <div class="tab-data">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="PracticeInformation-tab" data-bs-toggle="tab"
                               data-bs-target="#PracticeInformation" type="button" role="tab"
                               aria-controls="PracticeInformation" aria-selected="true">Profile Information</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="AILearning-tab" data-bs-toggle="tab" data-bs-target="#AILearning"
                               type="button" role="tab" aria-controls="AILearning" aria-selected="false">Change
                                Password</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="Settings-tab" data-bs-toggle="tab" data-bs-target="#Settings"
                               type="button" role="tab" aria-controls="Settings" aria-selected="false">Notifications</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" target="_blank"
                               href="https://billing.stripe.com/p/login/9AQ5mJeYZ5jQ1y09AA" id="UpdateBilling-tab"
                               type="button" role="tab" aria-selected="false">Update Billing</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="PracticeInformation" role="tabpanel"
                             aria-labelledby="PracticeInformation-tab">
                            <div class="tab-main-title d-lg-none">
                                <h5>Profile Details</h5>
                            </div>
                            <div class="tab-main-box"
                                 :class="profile_details_editing ? 'tab-edit-active tab-main-active' : 'tab-disabled-active'">
                                <div class="tab-title-box d-flex align-items-center">
                                    <div class="tab-title" @click.prevent="profile_details_editing=true">
                                        <h3>Profile Details</h3>
                                    </div>
                                    <div class="edit-option d-none d-lg-flex ms-auto"
                                         :class="profile_details_editing ? 'edit-active' : ''">
                                        <a v-if="!profile_details_editing" href="#"
                                           @click.prevent="profile_details_editing = true"
                                           class="edit-input manage-edit">Edit
                                            <svg width="18" height="18" viewBox="0 0 18 18"
                                                 xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M16 17.25C16 16.848 15.644 16.5 15.25 16.5C12.689 16.5 3.311 16.5 0.75 16.5C0.356 16.5 0 16.848 0 17.25C0 17.652 0.356 18 0.75 18H15.25C15.644 18 16 17.652 16 17.25ZM8.597 13.852L17.721 4.727C17.892 4.556 18 4.304 18 4.043C18 3.814 17.917 3.577 17.72 3.381L14.605 0.277C14.42 0.0919999 14.176 0 13.933 0C13.69 0 13.447 0.0919999 13.261 0.277L4.118 9.38C3.549 11.143 2.563 14.203 2.492 14.461C2.472 14.536 2.463 14.611 2.463 14.685C2.463 15.146 2.812 15.533 3.228 15.533C3.739 15.533 4.219 15.344 8.597 13.852ZM5.327 10.51L7.464 12.647L4.296 13.693L5.327 10.51ZM6.282 9.344L13.933 1.728L16.268 4.055L8.631 11.693L6.282 9.344Z"/>
                                            </svg>
                                        </a>
                                        <a v-else href="#" @click.prevent="profile_details_editing_save()"
                                           class="save-input manage-save">Save</a>
                                    </div>
                                </div>
                                <div class="tab-sub-box">
                                    <div class="d-lg-none d-flex align-items-center">
                                        <a href="#" class="back-btn" @click.prevent="profile_details_editing=false">
                                            <svg width="20" height="14" viewBox="0 0 20 14" fill="none"
                                                 xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                      d="M19.4411 6.37497H2.04859L7.25172 1.19185C7.49609 0.947473 7.49609 0.551848 7.25172 0.308098C7.00734 0.0637231 6.61172 0.0637231 6.36797 0.308098L0.180469 6.49497C-0.0601562 6.7356 -0.0601562 7.1381 0.180469 7.37872L6.36797 13.5662C6.61234 13.8106 7.00797 13.8106 7.25172 13.5662C7.49609 13.3218 7.49609 12.9262 7.25172 12.6825L2.04859 7.62497H19.4411C19.7861 7.62497 20.0661 7.34497 20.0661 6.99997C20.0661 6.65497 19.7861 6.37497 19.4411 6.37497Z"
                                                      fill="#514F5F"/>
                                            </svg>
                                        </a>
                                        <a href="#" class="save-input tab-save"
                                           @click.prevent="profile_details_editing_save()">Save</a>
                                    </div>
                                    <div class="d-lg-none mobile-tab-title">
                                        <h3>Profile Details</h3>
                                    </div>
                                    <div class="row gx-3">
                                        <div class="col-12">
                                            <div class="row">
                                                <div class="col-12 col-lg-12">
                                                    <div class="form-group">
                                                        <span class="d-block text-danger mt-1"
                                                              v-if="authStore.changePasswordErrors.name">{{
                                                                authStore.changePasswordErrors.name
                                                            }}</span>
                                                        <input v-model="authStore.profileUser.name" type="text"
                                                               class="form-control"
                                                               :class="authStore.profileUser.name ? 'filled' : ''">
                                                        <label>Full Name</label>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-lg-12">
                                                    <div class="form-group">
                                                        <span class="d-block text-danger mt-1"
                                                              v-if="authStore.changePasswordErrors.email">{{
                                                                authStore.changePasswordErrors.email
                                                            }}</span>
                                                        <input v-model="authStore.profileUser.email" type="text"
                                                               class="form-control"
                                                               :class="authStore.profileUser.email ? 'filled' : ''">
                                                        <label>Email</label>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-lg-12">
                                                    <div class="form-group">
                                                        <span class="d-block text-danger mt-1" v-if="authStore.changePasswordErrors.phone">{{ authStore.changePasswordErrors.phone }}</span>
                                                        <input v-model="authStore.profileUser.phone" id="phone" type="text" class="form-control" :class="authStore.profileUser.phone ? 'filled' : ''">
                                                        <label>Phone</label>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-lg-12">
                                                    <div class="form-group">
                                                        <div class="my-1" v-if="profile_details_editing">
                                                            <label for="profile_photo"><a style="width:auto !important;"
                                                                                          role="button"
                                                                                          class="save-input manage-cancel">Upload
                                                                Photo <b id="profile_photo_name"></b></a></label>
                                                            <input
                                                                id="profile_photo"
                                                                type="file"
                                                                class="form-control"
                                                                style="display:none;"/>
                                                        </div>
                                                        <div class="header-profile mb-2"
                                                             v-if="authStore.profileUser.profile_pic">
                                                            <figure
                                                                style="width:150px; height:auto; border-radius: 75px;">
                                                                <img :src="authStore.profileUser.profile_pic.preview"/>
                                                            </figure>
                                                        </div>
                                                        <div v-else>
                                                            <span>No Profile Photo</span>
                                                        </div>
                                                        <span class="d-block text-danger mt-1"></span>
                                                        <label>Profile Photo</label>
                                                    </div>
                                                </div>
                                                <!--                                                2fa-->
                                                <div v-if="!profile_details_editing" class="mt-4">
                                                    <h3 class="mb-4">Two-Factor Authentication (2FA)</h3>
                                                    <div class="form-group">
                                                        <span class="mt-1">{{ authStore.user.two_factor_enabled ? 'On' : 'Off' }}</span>
                                                        <label>2FA Verification:</label>
                                                    </div>
                                                    <div class="form-group" v-if="authStore.user.two_factor_enabled">
                                                        <span class="mt-1">
                                                            {{ authStore.user.two_factor_type === 'email' ? 'Email' :
                                                            authStore.user.two_factor_type === 'sms' ? 'SMS' :
                                                                authStore.user.two_factor_type === 'both' ? 'Email and SMS' : '' }}
                                                        </span>
                                                        <label>Verifications Methods:</label>
                                                    </div>
                                                </div>
                                                <div v-else class="mt-4">
                                                    <h3 class="mb-4">Two-Factor Authentication (2FA)</h3>

                                                    <!-- 2FA On/Off Toggle -->
                                                    <div class="form-group mb-3">

                                                        <div class="check-group d-flex flex-wrap mb-1">
                                                            <div class="btn-checkbox mb-2 me-2">
                                                                <input type="radio"
                                                                       class="btn-check"
                                                                       name="two_factor_status"
                                                                       id="twoFactorOn"
                                                                       :value="1"
                                                                       :checked="authStore.user.two_factor_enabled === 1"
                                                                       v-model="authStore.user.two_factor_enabled"
                                                                       autocomplete="off">
                                                                <label class="btn btn-outline-primary" for="twoFactorOn">On</label>
                                                            </div>
                                                            <div class="btn-checkbox mb-2 me-2">
                                                                <input type="radio"
                                                                       class="btn-check"
                                                                       name="two_factor_status"
                                                                       id="twoFactorOff"
                                                                       :value="0"
                                                                       :checked="authStore.user.two_factor_enabled === 0"
                                                                       v-model="authStore.user.two_factor_enabled"
                                                                       autocomplete="off">
                                                                <label class="btn btn-outline-primary" for="twoFactorOff">Off</label>
                                                            </div>
                                                        </div>
                                                        <label class="form-label d-block">2FA Verification:</label>
                                                    </div>

                                                    <!-- Verification Options -->
                                                    <div v-if="authStore.user.two_factor_enabled" class="form-group">
                                                        <div class="check-group d-flex flex-wrap mb-1">
                                                            <div class="btn-checkbox mb-2 me-2">
                                                                <input type="checkbox"
                                                                       class="btn-check"
                                                                       id="emailVerification"
                                                                       :checked="authStore.user.two_factor_type === 'email' || authStore.user.two_factor_type === 'both'"
                                                                       @change="updateTwoFactorType($event, 'email')"
                                                                       autocomplete="off">
                                                                <label class="btn btn-outline-primary" for="emailVerification">Email</label>
                                                            </div>
                                                            <div class="btn-checkbox mb-2 me-2">
                                                                <input type="checkbox"
                                                                       class="btn-check"
                                                                       id="smsVerification"
                                                                       :checked="authStore.user.two_factor_type === 'sms' || authStore.user.two_factor_type === 'both'"
                                                                       @change="updateTwoFactorType($event, 'sms')"
                                                                       autocomplete="off">
                                                                <label class="btn btn-outline-primary" for="smsVerification">SMS</label>
                                                            </div>
                                                        </div>
                                                        <label class="form-label d-block">Verification Methods:</label>
                                                    </div>
                                                    <span class="d-block text-danger mt-1" v-if="authStore.changePasswordErrors.two_factor_type">{{ authStore.changePasswordErrors.two_factor_type }}</span>
                                                </div>
                                                <!--                                                2fa end-->
                                                <!--												<div class="col-12 col-lg-12" v-if="authStore.profileUser.profile_pic">
                                                                                                    <div class="header-profile">
                                                                                                        <div class="header-profile-info">
                                                                                                            <h5>{{authStore.profileUser.name}}</h5>
                                                                                                            <p>{{authStore.profileUser.email}}</p>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>-->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="AILearning" role="tabpanel" aria-labelledby="AILearning-tab">
                            <div class="tab-main-title d-lg-none">
                                <h5>Change Password</h5>
                            </div>
                            <div class="tab-main-box"
                                 :class="password_details_editing ? 'tab-edit-active tab-main-active' : 'tab-disabled-active'">
                                <div class="tab-title-box d-flex align-items-center">
                                    <div class="tab-title" @click.prevent="password_details_editing=true">
                                        <h3>Password Details</h3>
                                    </div>
                                    <div class="edit-option d-none d-lg-flex ms-auto"
                                         :class="password_details_editing ? 'edit-active' : ''">
                                        <a v-if="!password_details_editing" href="#"
                                           @click.prevent="password_details_editing = true"
                                           class="edit-input manage-edit">Edit
                                            <svg width="18" height="18" viewBox="0 0 18 18"
                                                 xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M16 17.25C16 16.848 15.644 16.5 15.25 16.5C12.689 16.5 3.311 16.5 0.75 16.5C0.356 16.5 0 16.848 0 17.25C0 17.652 0.356 18 0.75 18H15.25C15.644 18 16 17.652 16 17.25ZM8.597 13.852L17.721 4.727C17.892 4.556 18 4.304 18 4.043C18 3.814 17.917 3.577 17.72 3.381L14.605 0.277C14.42 0.0919999 14.176 0 13.933 0C13.69 0 13.447 0.0919999 13.261 0.277L4.118 9.38C3.549 11.143 2.563 14.203 2.492 14.461C2.472 14.536 2.463 14.611 2.463 14.685C2.463 15.146 2.812 15.533 3.228 15.533C3.739 15.533 4.219 15.344 8.597 13.852ZM5.327 10.51L7.464 12.647L4.296 13.693L5.327 10.51ZM6.282 9.344L13.933 1.728L16.268 4.055L8.631 11.693L6.282 9.344Z"/>
                                            </svg>
                                        </a>
                                        <a v-else href="#" @click.prevent="password_details_editing_save()"
                                           class="save-input manage-save">Save</a>
                                    </div>
                                </div>
                                <div class="tab-sub-box">
                                    <div class="d-lg-none d-flex align-items-center">
                                        <a role="button" class="back-btn"
                                           @click.prevent="password_details_editing=false">
                                            <svg width="20" height="14" viewBox="0 0 20 14" fill="none"
                                                 xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                      d="M19.4411 6.37497H2.04859L7.25172 1.19185C7.49609 0.947473 7.49609 0.551848 7.25172 0.308098C7.00734 0.0637231 6.61172 0.0637231 6.36797 0.308098L0.180469 6.49497C-0.0601562 6.7356 -0.0601562 7.1381 0.180469 7.37872L6.36797 13.5662C6.61234 13.8106 7.00797 13.8106 7.25172 13.5662C7.49609 13.3218 7.49609 12.9262 7.25172 12.6825L2.04859 7.62497H19.4411C19.7861 7.62497 20.0661 7.34497 20.0661 6.99997C20.0661 6.65497 19.7861 6.37497 19.4411 6.37497Z"
                                                      fill="#514F5F"/>
                                            </svg>
                                        </a>
                                        <a href="#" class="save-input tab-save"
                                           @click.prevent="password_details_editing_save()">Save</a>
                                    </div>
                                    <div class="d-lg-none mobile-tab-title">
                                        <h3>Password Details</h3>
                                    </div>
                                    <div class="row gx-3">
                                        <div class="col-12">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <span class="d-block text-danger mt-1"
                                                              v-if="authStore.changePasswordErrors.password">{{
                                                                authStore.changePasswordErrors.password
                                                            }}</span>
                                                        <input type="password" class="form-control"
                                                               v-model="authStore.changePassword">
                                                        <label v-show="!password_details_editing">********</label>
                                                        <label>Password</label>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <span class="d-block text-danger mt-1"
                                                              v-if="authStore.changePasswordErrors.password_confirmation">{{
                                                                authStore.changePasswordErrors.password_confirmation
                                                            }}</span>
                                                        <input type="password" class="form-control"
                                                               v-model="authStore.changeConfirmPassword">
                                                        <label v-show="!password_details_editing">********</label>
                                                        <label>Confirm Password</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="Settings" role="tabpanel" aria-labelledby="Settings-tab">
                            <div class="tab-main-title d-lg-none">
                                <h5>Notification</h5>
                            </div>
                            <div class="tab-main-box"
                                 :class="settings_editing ? 'tab-edit-active tab-main-active' : 'tab-disabled-active'">
                                <div class="tab-title-box d-flex align-items-center">
                                    <div class="tab-title" @click.prevent="settings_editing=true">
                                        <h3>Notification Preferences</h3>
                                    </div>
                                    <div class="edit-option d-none d-lg-flex ms-auto"
                                         :class="settings_editing ? 'edit-active' : ''">
<!--                                        <a v-if="!settings_editing" href="#"
                                           @click.prevent="settings_editing = true"
                                           class="edit-input manage-edit">Edit
                                            <svg width="18" height="18" viewBox="0 0 18 18"
                                                 xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M16 17.25C16 16.848 15.644 16.5 15.25 16.5C12.689 16.5 3.311 16.5 0.75 16.5C0.356 16.5 0 16.848 0 17.25C0 17.652 0.356 18 0.75 18H15.25C15.644 18 16 17.652 16 17.25ZM8.597 13.852L17.721 4.727C17.892 4.556 18 4.304 18 4.043C18 3.814 17.917 3.577 17.72 3.381L14.605 0.277C14.42 0.0919999 14.176 0 13.933 0C13.69 0 13.447 0.0919999 13.261 0.277L4.118 9.38C3.549 11.143 2.563 14.203 2.492 14.461C2.472 14.536 2.463 14.611 2.463 14.685C2.463 15.146 2.812 15.533 3.228 15.533C3.739 15.533 4.219 15.344 8.597 13.852ZM5.327 10.51L7.464 12.647L4.296 13.693L5.327 10.51ZM6.282 9.344L13.933 1.728L16.268 4.055L8.631 11.693L6.282 9.344Z"/>
                                            </svg>
                                        </a>-->
                                        <a href="#" @click.prevent="settings_editing_save()"
                                           class="save-input manage-save">Save</a>
                                    </div>
                                </div>
                                <div class="tab-sub-box">
                                    <div class="d-lg-none d-flex align-items-center">
                                        <a role="button" class="back-btn"
                                           @click.prevent="settings_editing=false">
                                            <svg width="20" height="14" viewBox="0 0 20 14" fill="none"
                                                 xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                      d="M19.4411 6.37497H2.04859L7.25172 1.19185C7.49609 0.947473 7.49609 0.551848 7.25172 0.308098C7.00734 0.0637231 6.61172 0.0637231 6.36797 0.308098L0.180469 6.49497C-0.0601562 6.7356 -0.0601562 7.1381 0.180469 7.37872L6.36797 13.5662C6.61234 13.8106 7.00797 13.8106 7.25172 13.5662C7.49609 13.3218 7.49609 12.9262 7.25172 12.6825L2.04859 7.62497H19.4411C19.7861 7.62497 20.0661 7.34497 20.0661 6.99997C20.0661 6.65497 19.7861 6.37497 19.4411 6.37497Z"
                                                      fill="#514F5F"/>
                                            </svg>
                                        </a>
                                        <a href="#" class="save-input tab-save"
                                           @click.prevent="settings_editing_save()">Save</a>
                                    </div>
                                    <div class="d-lg-none mobile-tab-title">
                                        <h3>Notification Preferences</h3>
                                    </div>
                                    <div class="row gx-3">
                                        <div class="col-12">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" id="doNotDisturbSwitch" true-value="1" false-value="0" v-model="authStore.settings.doNotDisturb">
                                                        <label class="form-check-label" for="doNotDisturbSwitch">Do Not Disturb</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-6">
                                            <h5 class="mt-4 mb-2 pb-1 border-bottom" style="border-color: #dbe3eb;">Email Notifications</h5>
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" id="followUpEmailNotificationSwitch" true-value="1" false-value="0" v-model="authStore.settings.followUpEmailNotification">
                                                        <label class="form-check-label" for="followUpEmailNotificationSwitch">Follow Up Notifications</label>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" id="leadReconnectingEmailNotificationSwitch" true-value="1" false-value="0" v-model="authStore.settings.leadReconnectingEmailNotification">
                                                        <label class="form-check-label" for="leadReconnectingEmailNotificationSwitch">Lead Reconnecting Notifications</label>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" id="appointmentEmailNotificationSwitch" true-value="1" false-value="0" v-model="authStore.settings.appointmentEmailNotification">
                                                        <label class="form-check-label" for="appointmentEmailNotificationSwitch">Appointment Scheduled Notifications</label>
                                                    </div>
                                                </div>
                                                <div class="col-12" v-if="wherebyLink">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" id="wherebyEmailNotificationSwitch" true-value="1" false-value="0" v-model="authStore.settings.wherebyEmailNotification">
                                                        <label class="form-check-label" for="wherebyEmailNotificationSwitch">Video Consultation Notifications</label>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" id="dailySummaryEmailNotificationSwitch" true-value="1" false-value="0" v-model="authStore.settings.dailySummaryEmailNotification">
                                                        <label class="form-check-label" for="dailySummaryEmailNotificationSwitch">Daily Summary Notifications</label>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" id="weeklySummaryEmailNotificationSwitch" true-value="1" false-value="0" v-model="authStore.settings.weeklySummaryEmailNotification">
                                                        <label class="form-check-label" for="weeklySummaryEmailNotificationSwitch">Weekly Summary Notifications</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-6">
                                            <h5 class="mt-4 mb-2 pb-1 border-bottom" style="border-color: #dbe3eb;">Text Notifications</h5>
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" id="followUpTextNotificationSwitch" true-value="1" false-value="0" v-model="authStore.settings.followUpTextNotification">
                                                        <label class="form-check-label" for="followUpTextNotificationSwitch">Follow Up Notifications</label>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" id="leadReconnectingTextNotificationSwitch" true-value="1" false-value="0" v-model="authStore.settings.leadReconnectingTextNotification">
                                                        <label class="form-check-label" for="leadReconnectingTextNotificationSwitch">Lead Reconnecting Notifications</label>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" id="appointmentTextNotificationSwitch" true-value="1" false-value="0" v-model="authStore.settings.appointmentTextNotification">
                                                        <label class="form-check-label" for="appointmentTextNotificationSwitch">Appointment Scheduled Notifications</label>
                                                    </div>
                                                </div>
                                                <div class="col-12" v-if="wherebyLink">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" id="wherebyTextNotificationSwitch" true-value="1" false-value="0" v-model="authStore.settings.wherebyTextNotification">
                                                        <label class="form-check-label" for="wherebyTextNotificationSwitch">Video Consultation Notifications</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-6">
                                            <h5 class="mt-4 mb-2 pb-1 border-bottom" style="border-color: #dbe3eb;">Browser Notifications</h5>
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" id="followUpBrowserNotificationSwitch" true-value="1" false-value="0" v-model="authStore.settings.followUpBrowserNotification">
                                                        <label class="form-check-label" for="followUpBrowserNotificationSwitch">Follow Up Notifications</label>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" id="leadReconnectingBrowserNotificationSwitch" true-value="1" false-value="0" v-model="authStore.settings.leadReconnectingBrowserNotification">
                                                        <label class="form-check-label" for="leadReconnectingBrowserNotificationSwitch">Lead Reconnecting Notifications</label>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" id="appointmentBrowserNotificationSwitch" true-value="1" false-value="0" v-model="authStore.settings.appointmentBrowserNotification">
                                                        <label class="form-check-label" for="appointmentBrowserNotificationSwitch">Appointment Scheduled Notifications</label>
                                                    </div>
                                                </div>
                                                <div class="col-12" v-if="wherebyLink">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" id="wherebyBrowserNotificationSwitch" true-value="1" false-value="0" v-model="authStore.settings.wherebyBrowserNotification">
                                                        <label class="form-check-label" for="wherebyBrowserNotificationSwitch">Video Consultation Notifications</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!--/.tab-data-->
            </div>
        </div>
    </div>
</template>
<script>
import {useAuthStore} from '../../stores/auth';
export default {
    setup() {
        const authStore = useAuthStore();
        return {authStore};
    },
    data() {
        return {
            profile_details_editing: false,
            password_details_editing: false,
            settings_editing: false,
            wherebyLink: null,

        }
    },
    updated() {
        $("#profile_photo").on('change', function () {
            let filename = this.files[0].name;
            $('#profile_photo_name').html(" | " + filename);
        });
        IMask(document.getElementById('phone'), {
            mask: '(000) 000-0000'
        });
        this.setWherebyLink();
    },
    mounted() {
        let _self = this;
        $('#homeLink').removeClass('active');
        $('#mailLink').removeClass('active');
        $('#profileLink').addClass('show');
        if(this.$route.params.page == 'notifications'){
            $('.user-profile .nav-item .nav-link').removeClass('active');
            $('.user-profile .tab-content .tab-pane').removeClass('show').removeClass('active');
            $('#Settings-tab').addClass('active');
            $('#Settings').addClass('active').addClass('show');
        }
        IMask(document.getElementById('phone'), {
            mask: '(000) 000-0000'
        });
        this.setWherebyLink();
    },
    methods: {
        setWherebyLink() {
            const link = localStorage.getItem('whereby_link');
            if (link && link.length > 5) {
                this.wherebyLink = link;
            } else {
                this.wherebyLink = null;
            }
        },
        profile_details_editing_save() {
            this.submit(1);
            if (Object.keys(this.authStore.changePasswordErrors).length > 0) {
                return null;
            }
            this.profile_details_editing = false;
        },
        password_details_editing_save() {
            this.submit(2);
            if (Object.keys(this.authStore.changePasswordErrors).length > 0) {
                return null;
            }
            this.password_details_editing = false;
        },
        settings_editing_save() {
            this.submit(3);
            this.settings_editing = false;
        },
        submit(section) {
            this.authStore.changePasswordErrors = [];
            // Profile Details Validation
            if (section == 1) {

                this.authStore.profileUser.phone = this.authStore.profileUser.phone.replace(/[\(\)\s-]/g, '')

                if (!this.authStore.profileUser.name) {
                    this.authStore.changePasswordErrors['name'] = 'Name field is required.';
                }
                if (!this.authStore.profileUser.email) {
                    this.authStore.changePasswordErrors['email'] = 'Email field is required.';
                } else if (!/^[^@]+@\w+(\.\w+)+\w$/.test(this.authStore.profileUser.email)) {
                    this.authStore.changePasswordErrors['email'] = 'Provided email is invalid.'
                }

                if(this.authStore.profileUser.phone && this.authStore.profileUser.phone.length != 10){
                    this.authStore.changePasswordErrors['phone'] = 'Provided Phone no. is invalid.';
                }

                if (this.authStore.user.two_factor_enabled === 1) {
                    if (!['email', 'both','sms'].includes(this.authStore.user.two_factor_type)) {
                        this.authStore.changePasswordErrors['two_factor_type'] = 'Any one verification method is required when 2FA is enabled.';
                    }
                }

                this.authStore.profileData = new FormData();
                this.authStore.profileData.append('id', this.authStore.profileUser.id);
                this.authStore.profileData.append('name', this.authStore.profileUser.name);
                this.authStore.profileData.append('email', this.authStore.profileUser.email);
                this.authStore.profileData.append('phone', this.authStore.profileUser.phone);
                var imagefile = document.querySelector('#profile_photo');
                if (imagefile.files.length > 0) {
                    this.authStore.profileData.append("profile_pic", imagefile.files[0]);
                }

                // Add 2FA data
                this.authStore.profileData.append('two_factor_enabled', this.authStore.user.two_factor_enabled);
                if (this.authStore.user.two_factor_enabled === 0) {
                    this.authStore.profileData.append('two_factor_type', null);
                }else{
                    this.authStore.profileData.append('two_factor_type', this.authStore.user.two_factor_type);
                }
            }
            // Password Details Validation
            if (section == 2) {
                if (!this.authStore.changePassword) {
                    this.authStore.changePasswordErrors['password'] = 'Password field is required.';
                } else if (this.authStore.changePassword.length < 8) {
                    this.authStore.changePasswordErrors['password'] = 'Password field must be at least 8 characters.';
                }
                if (!this.authStore.changeConfirmPassword) {
                    this.authStore.changePasswordErrors['password_confirmation'] = 'Confirm Password field is required.';
                } else if (this.authStore.changeConfirmPassword.length < 8) {
                    this.authStore.changePasswordErrors['password_confirmation'] = 'Confirm Password field must be at least 8 characters.';
                }
                if (this.authStore.changePassword !== this.authStore.changeConfirmPassword) {
                    this.authStore.changePasswordErrors['password_confirmation'] = 'Password and Confirm Password field is not same.';
                }
            }
            if(section==3){
                // TODO: Add client validation for settings
            }
            if (Object.keys(this.authStore.changePasswordErrors).length == 0) {
                this.authStore.updateProfile(section);
            }
        },
        showProfilePage() {
            $('#profileLink').trigger('click');
        },
        updateTwoFactorType(event, type) {
            const emailChecked = document.getElementById('emailVerification').checked;
            const smsChecked = document.getElementById('smsVerification').checked;

            if (emailChecked && smsChecked) {
                this.authStore.user.two_factor_type = 'both';
            } else if (emailChecked) {
                this.authStore.user.two_factor_type = 'email';
            } else if (smsChecked) {
                this.authStore.user.two_factor_type = 'sms';
            } else {
                this.authStore.user.two_factor_type = '';
            }
        }
    }
}
</script>

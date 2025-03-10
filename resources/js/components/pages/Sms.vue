<template>
    <div id="main" class="bg-light-gray mt-3">
		<div class="inbox-main" :class="rightView=='new' ? 'new-sms-active' : ''">
			<div class="inbox-left">
				<div class="inbox-left-top">
					<div class="inbox-left-title">
						<div class="d-flex align-items-center">
							<div class="d-flex align-items-center">
								<h2>Inbox</h2>
								<!-- <span class="notification-number">{{inboxStore.totalUnread}}</span> -->
							</div>
							<div class="add-sms-icon tooltip-ico" data-title="New Text">
								<router-link :to="routeName === 'dashboard' ? '/crtx/dashboard?view=new&page=sms' : '/crtx/inbox?view=new&page=sms'">
									<svg width="21" height="21" viewBox="0 0 21 21" xmlns="http://www.w3.org/2000/svg">
										<path d="M12.4763 0.836293V9.03629H20.2563V12.6963H12.4763V20.9363H8.47631V12.6963H0.736306V9.03629H8.47631V0.836293H12.4763Z"></path>
									</svg>
								</router-link>
							</div>
						</div>
					</div>
					<div class="sms-email-box">
						<router-link :to="routeName === 'dashboard' ? '/crtx/dashboard?view=list&page=sms' : '/crtx/inbox?view=list&page=sms'" class="activated" id="linkSMS" @click="showSMS">Text <span>{{ inboxStore.totalUnread }}</span></router-link>
						<router-link v-if="authStore.inbox_id" :to="routeName === 'dashboard' ? '/crtx/dashboard?view=list&page=email' : '/crtx/inbox?view=list&page=email'" id="linkEmail">Email <span>{{ inboxStore.totalReceivedEmailsUnread }}</span></router-link>
					</div>
					<div class="inbox-search">
						<span class="search-input-ico">
							<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M13.97 15.031C12.491 16.269 10.586 17.016 8.509 17.016C3.812 17.016 0 13.204 0 8.508C0 3.812 3.812 0 8.509 0C13.204 0 17.017 3.812 17.017 8.508C17.017 10.586 16.27 12.492 15.032 13.969L19.781 18.719C19.927 18.865 20 19.057 20 19.25C20 19.837 19.463 20 19.25 20C19.058 20 18.866 19.927 18.719 19.78L13.97 15.031ZM8.509 1.501C4.641 1.501 1.502 4.641 1.502 8.508C1.502 12.375 4.641 15.515 8.509 15.515C12.375 15.515 15.516 12.375 15.516 8.508C15.516 4.641 12.375 1.501 8.509 1.501Z" fill="#959595"/>
							</svg>
						</span>
						<input type="text" class="form-control" placeholder="Search inbox..." v-model="searchTermSms" v-on:keyup="searchSMS">
					</div>
				</div>
				<div class="inbox-chat-list">
					<div class="tab-menu-list" v-if="rightView != 'list' && rightView != 'new'">
						<div class="d-flex justify-content-center py-2"  v-if="inboxStore.page > 1">
							<a role="button" class="btn btn-primary" @click.prevent="prev">Load Prev</a>
						</div>

						<router-link v-if="inboxStore.smsList.length>0" v-for="lead in inboxStore.smsList" :to="routeName === 'dashboard' ? '/crtx/dashboard/'+lead.id+'?page=sms' : '/crtx/inbox/'+lead.id+'?page=sms'" class="tab-menu" :class="{active:lead.id==leadId, unread : lead.has_sms==1}"  data-rel="tab-1" @click="showChat(lead.id)"><!-- active -->
							<div class="chat-list-box">
								<div class="chat-profile-img">
									<figure>
										<span>{{ lead.first_name.charAt(0).toUpperCase() + lead.last_name.charAt(0).toUpperCase() }}</span>
									</figure>
								</div>
								<div  class="chat-profile-info">
									<h6>{{lead.first_name +' '+ lead.last_name}}</h6>
									<p>{{ lead.chat }}</p>
								</div>
								<div class="chat-profile-date">
									<span>{{new Date(lead.created_at).toLocaleDateString('en-us', { year:"numeric", month:"short", day:"numeric",timeZone: "UTC"})}}</span>{{new Date(lead.created_at).toLocaleTimeString('en-us', {hour: '2-digit', minute:'2-digit'}) }}
								</div>
							</div>
						</router-link>

						<div class="d-flex justify-content-center py-2" v-if="inboxStore.page<inboxStore.last_page && inboxStore.smsList.length>0">
							<a  role="button" class="btn btn-primary" @click.prevent="next">Load Next</a>
						</div>
					</div>
				</div>
			</div>
			<div class="inbox-right">
				<div v-show="inboxStore.textSent" class="alert alert-success alert-dismissible fade show mb-0" role="alert">
					Text Sent Successfully!
					<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" @click="inboxStore.textSent = false"></button>
				</div>
				<div v-show="inboxStore.textSentFailed" class="alert alert-danger alert-dismissible fade show mb-0" role="alert">
					There was an error sending your text!
					<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" @click="inboxStore.textSentFailed = false"></button>
				</div>
				<div class="inbox-tab" v-if="rightView == 'chat'">
					<div id="tab-1" class="tab-box tab-active">
						<div class="chat-log">
							<div class="chat-log-top">
								<router-link :to="routeName === 'dashboard' ? '/crtx/dashboard?view=list&page=sms' : '/crtx/inbox?view=list&page=sms'" role="button" class="chat-log-back" @click="showChat">
									<svg width="20" height="14" viewBox="0 0 20 14" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path fill-rule="evenodd" clip-rule="evenodd" d="M19.4411 6.37503H2.04859L7.25172 1.19191C7.49609 0.947534 7.49609 0.551909 7.25172 0.308159C7.00734 0.0637842 6.61172 0.0637842 6.36797 0.308159L0.180469 6.49503C-0.0601562 6.73566 -0.0601562 7.13816 0.180469 7.37878L6.36797 13.5663C6.61234 13.8107 7.00797 13.8107 7.25172 13.5663C7.49609 13.3219 7.49609 12.9263 7.25172 12.6825L2.04859 7.62503H19.4411C19.7861 7.62503 20.0661 7.34503 20.0661 7.00003C20.0661 6.65503 19.7861 6.37503 19.4411 6.37503Z" fill="#514F5F"></path>
									</svg>
								</router-link>
								<div class="chat-title-box">
									<h3>{{  inboxStore.lead_name? inboxStore.lead_name : '' }}</h3>
									<router-link :to="'/crtx/patient-profile/'+this.leadId" class="patient-profile-link">
										View Patient Profile
										<svg version="1.1" x="0px" y="0px" viewBox="0 0 20 20">
											<g>
												<path class="st0" d="M0.6,9.4H18l-5.2-5.2c-0.2-0.2-0.2-0.6,0-0.9c0.2-0.2,0.6-0.2,0.9,0l6.2,6.2c0.2,0.2,0.2,0.6,0,0.9l-6.2,6.2c-0.2,0.2-0.6,0.2-0.9,0c-0.2-0.2-0.2-0.6,0-0.9l5.2-5.1H0.6c-0.3,0-0.6-0.3-0.6-0.6C0,9.7,0.2,9.4,0.6,9.4z"/>
											</g>
										</svg>
									</router-link>
								</div>
							</div>
							<div class="chat-log-bottom" >
								<div class="msger-chat">
									<div v-for="(chats, index) in inboxStore.chatList">
										<span class="msger-timeline">{{new Date(index).toLocaleDateString('en-us', { year:"numeric", month:"short", day:"numeric",timeZone: "UTC"})}}</span>
										<div class="msger-chat-box" v-for="chat in chats"  :class="chat.inbound == 0? 'msger-chat-right' : ''">
											<div class="msger-img">
												<figure>
												<span v-if="chat.inbound === 1" :title="getFullName(chat)">
													{{ getInitials(chat.dis_first_name, chat.dis_last_name) }}
												</span>
												<span v-else :title="getFullName(chat)">
													{{ getInitials(chat.dis_first_name, chat.dis_last_name) }}
												</span>
												</figure>
											</div>
											<div class="msger-msg-box">
												<div class="msger-msg">
													<p >{{chat.chat}}</p>
												</div>
												<span class="msg-time">
													{{ new Date(chat.created_at).toLocaleTimeString('en-us', {hour: '2-digit', minute:'2-digit'}) }}
													<span class="mx-2" v-if="chat.inbound==0">
														<svg v-if="chat.delivered == 0" width="24px" height="24px" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg" role="img"><title>Not Delivered</title><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <circle cx="12" cy="12" r="10" stroke="#1C274C" stroke-width="1.5"></circle> <path d="M12 17V11" stroke="#1C274C" stroke-width="1.5" stroke-linecap="round"></path> <circle cx="1" cy="1" r="1" transform="matrix(1 0 0 -1 11 9)" fill="#1C274C"></circle> </g></svg>
														<svg v-if="chat.delivered == 1" fill="#000000" width="16px" height="16px" viewBox="0 0 32 32" version="1.1" xmlns="http://www.w3.org/2000/svg" role="img"><title>Delivered</title><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M30.844 3.602c-0.221-0.204-0.518-0.328-0.844-0.328-0.365 0-0.693 0.156-0.921 0.406l-0.001 0.001-20.121 21.993-6.076-6.036c-0.226-0.226-0.538-0.366-0.883-0.366-0.69 0-1.25 0.56-1.25 1.25 0 0.348 0.142 0.663 0.371 0.889l0 0 7 6.953 0.022 0.015 0.015 0.021c0.074 0.061 0.159 0.114 0.25 0.156l0.007 0.003c0.037 0.026 0.079 0.053 0.123 0.077l0.007 0.003c0.135 0.056 0.292 0.089 0.457 0.089 0.175 0 0.341-0.037 0.491-0.103l-0.008 0.003c0.053-0.031 0.098-0.061 0.14-0.094l-0.003 0.002c0.102-0.050 0.189-0.11 0.268-0.179l-0.001 0.001 0.015-0.023 0.020-0.014 21-22.953c0.204-0.221 0.328-0.518 0.328-0.844 0-0.365-0.156-0.693-0.406-0.921l-0.001-0.001zM8.876 15.204l0.022 0.015 0.015 0.021c0.073 0.059 0.156 0.111 0.244 0.152l0.007 0.003c0.039 0.028 0.083 0.056 0.13 0.081l0.007 0.003c0.135 0.056 0.292 0.088 0.456 0.088 0.175 0 0.34-0.037 0.491-0.102l-0.008 0.003c0.053-0.031 0.098-0.061 0.14-0.094l-0.003 0.002c0.102-0.050 0.189-0.11 0.268-0.179l-0.001 0.001 0.015-0.023 0.020-0.014 11.269-12.317c0.203-0.221 0.328-0.518 0.328-0.844 0-0.69-0.559-1.25-1.25-1.25-0.365 0-0.693 0.156-0.921 0.405l-0.001 0.001-10.39 11.357-2.833-2.814c-0.226-0.226-0.538-0.366-0.883-0.366-0.69 0-1.25 0.56-1.25 1.25 0 0.348 0.142 0.663 0.372 0.889l0 0z"></path> </g></svg>
													</span>
												</span>
											</div>
										</div>
									</div>
								</div>
								<div class="msger-inputarea">
                                    <div class="border-bottom-1">
                                        <span class="my-2 d-block text-black-50">Choose a template to use for this text.</span>
                                        <div class="d-flex justify-content-start mb-3 gap-3">
                                            <select class="form-select exportSelect w-50" placeholder="Select Template" v-model="selectedTextTemplate">
                                                <option disabled selected :value="null">Select Template</option>
                                                <option v-for="template in clinicStore.textTemplates" :value="template">{{template.template_name}}</option>
                                            </select>
                                            <a role="button" class="edit-input manage-edit fs-6 w-25" @click="insertBodyTemplate">
                                                Insert Text
                                            </a>
                                            <a @click="viewTemplate()" role="button" class="btn-link w-25 pt-2">View Templates</a>
                                        </div>
                                    </div>
									<div class="position-relative">
										<textarea class="form-control" placeholder="Message Here..." v-model="message" required></textarea>
										<button type="submit" class="msg-sent-btn" @click="sendChat">
											<svg version="1.1" x="0px" y="0px" viewBox="0 0 25 25" style="enable-background:new 0 0 25 25;">
												<g>
													<path id="Vector" class="st0" d="M14.8,25L0,10.2L25,0L14.8,25z M14.1,21.5l7.3-17.8L3.5,10.9l3.9,3.9l7-4.2l-4.2,7L14.1,21.5z"/>
												</g>
											</svg>
										</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="chat-list-tab" v-if="rightView == 'list'">
					<div class="d-none d-lg-block chat-log-top">
						<div class="chat-title-box align-items-center">
							<h3>Texts Inbox</h3>
							<span class="ms-auto sms-info">
                                <span v-if="!unread_click">
                                    {{inboxStore.totalSMS}} Texts
                                </span>
                                <span v-else>
                                     <span @click="AllSmsClick()" role="button">All Texts </span>
                                </span>
                                 |
                                <span @click="unreadClick()" role="button"><b>{{inboxStore.totalUnread}} Unread</b></span>
							</span>
						</div>
					</div>
					<div class="inbox-chat-list">
						<div class="tab-menu-list">
							<div class="d-flex justify-content-center py-2" v-if="inboxStore.page > 1">
								<a  role="button" class="btn btn-primary" @click.prevent="prev">Load Prev</a>
							</div>
							<router-link v-if="inboxStore.smsList.length>0" v-for="lead in inboxStore.smsList" :to="routeName === 'dashboard' ? '/crtx/dashboard/'+lead.id+'?page=sms' : '/crtx/inbox/'+lead.id+'?page=sms'" class="d-block"  :class="{active : this.leadId == lead.id, unread : lead.has_sms==1}" @click="showChat(lead.id)">
								<div class="chat-list-box">
									<div class="chat-profile-img">
										<figure>
											<span>{{ lead.first_name.charAt(0).toUpperCase() + lead.last_name.charAt(0).toUpperCase() }}</span>
										</figure>
									</div>
									<div  class="chat-profile-info">
										<h6>{{ lead.first_name +' '+ lead.last_name }}</h6>
										<p>{{ lead.chat }}</p>
									</div>
									<div class="chat-profile-date">
										<span>{{new Date(lead.created_at).toLocaleDateString('en-us', { year:"numeric", month:"short", day:"numeric",timeZone: "UTC"})}}</span>{{ new Date(lead.created_at).toLocaleTimeString('en-us', {hour: '2-digit', minute:'2-digit'}) }}
									</div>
								</div>
							</router-link>
							<div class="d-flex justify-content-center py-2" v-if="inboxStore.page<inboxStore.last_page">
								<a  role="button" class="btn btn-primary" @click.prevent="next">Load Next</a>
							</div>
						</div>
					</div>
				</div>
				<div class="new-sms-box" v-show="rightView=='new'">
					<div class="chat-log-top">
						<router-link :to="routeName === 'dashboard' ? '/crtx/dashboard?view=list&page=sms' : '/crtx/inbox?view=list&page=sms'" class="sms-log-mobile sms-log-back">
							<svg width="20" height="14" viewBox="0 0 20 14" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path fill-rule="evenodd" clip-rule="evenodd" d="M19.4411 6.37503H2.04859L7.25172 1.19191C7.49609 0.947534 7.49609 0.551909 7.25172 0.308159C7.00734 0.0637842 6.61172 0.0637842 6.36797 0.308159L0.180469 6.49503C-0.0601562 6.73566 -0.0601562 7.13816 0.180469 7.37878L6.36797 13.5663C6.61234 13.8107 7.00797 13.8107 7.25172 13.5663C7.49609 13.3219 7.49609 12.9263 7.25172 12.6825L2.04859 7.62503H19.4411C19.7861 7.62503 20.0661 7.34503 20.0661 7.00003C20.0661 6.65503 19.7861 6.37503 19.4411 6.37503Z" fill="#514F5F"></path>
							</svg>
						</router-link>
						<div class="chat-title-box align-items-center">
							<h3>New Message</h3>
							<router-link :to="routeName === 'dashboard' ? '/crtx/dashboard?view=list&page=sms' : '/crtx/inbox?view=list&page=sms'" class="ms-auto sms-log-desktop sms-log-back" @click="showSMS">
								Cancel
							</router-link>
						</div>
						<div class="sms-inputarea-box">
							<div class="input-group">
								<div class="input-group-prepend">
									<label>To:</label>
								</div>
								<input type="search" id="leadSearchMobile" class="form-control" v-model="searchTerm" v-on:keyup="searchLeads"/>
							</div>
						</div>
					</div>

					<div class="chat-log-bottom">
						<div class="msger-chat">
							<span class="msger-timeline"></span>
						</div>
						<div class="sms-inputarea">
							<div class="sms-inputarea-box">
								<div class="input-group">
									<div class="input-group-prepend">
										<label>To:</label>
									</div>
									<input class="form-control" id="leadSearch" v-model="searchTerm" v-on:keydown="searchLeads">
								</div>
                                <div class="m-2 border-bottom-1">
                                    <span class="my-2 d-block text-black-50">Choose a template to use for this text.</span>
                                    <div class="d-flex justify-content-start mb-3 gap-3">
                                        <select class="form-select exportSelect w-50" placeholder="Select Template" v-model="selectedTextTemplate">
                                            <option disabled selected :value="null">Select Template</option>
                                            <option v-for="template in clinicStore.textTemplates" :value="template">{{template.template_name}}</option>
                                        </select>
                                        <a href="#" class="edit-input manage-edit fs-6 w-25" @click="insertTemplateText()">
                                            Insert Text
                                        </a>
                                        <a @click="viewTemplate()" role="button" class="btn-link w-25 pt-2">View Templates</a>
                                    </div>
                                </div>
								<span class="d-block text-danger ml-3" v-text="errors.selectedLead"></span>
								<textarea class="form-control" placeholder="Type a message" v-model="newMessage"></textarea>
								<span class="d-block text-danger ml-3" v-text="errors.newMessage"></span>
								<button type="button" class="btn btn-blue btn-sm" @click="sendNewSms">Send</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>
<script>

import { useAuthStore } from '../../stores/auth';
import { useInboxStore } from '../../stores/inbox';
import {useClinicStore} from "../../stores/clinic";


export default {
    setup (){
        const inboxStore = useInboxStore();
		const authStore = useAuthStore();
        const clinicStore = useClinicStore();

        return { inboxStore, authStore, clinicStore };
    },
    data() {
        return {
			errors: [],
			rightView : 'list',
			leadId : null,
			message : '',
			timer : null,
			myValue : null,
			searchTerm : '',
			newMessage: '',
			selectedLead: '',
			searchTermSms: '',
			userInitials: '',
            unread_click:false,
            selectedTextTemplate:null,
            routeName:'',
        }
    },
    mounted(){

		let _self = this;

        this.routeName = this.$route.name;

		let nameArr = this.authStore.user.name.split(' ');
		if(nameArr.length>=2){
			this.userInitials = nameArr[0].charAt(0).toUpperCase() + nameArr[1].charAt(0).toUpperCase();
		}else{
			this.userInitials = nameArr[0].charAt(0).toUpperCase() + nameArr[0].charAt(1).toUpperCase();
		}

        this.clinicStore.getTemplates('text');

		$('#homeLink').removeClass('active');
		$('#profileLink').removeClass('show');
		$('#mailLink').addClass('active');

		$( "#leadSearch, #leadSearchMobile" ).autocomplete({
			source: function (request, response) {
				response($.map(_self.inboxStore.searchedLeads, function (value, key) {
					//console.log(value);
					return {
						label: value.FullName + ', ' + value.email + ', ' + value.phone,
						value: value.id
					}
				}));
			},
			select: function(event, ui) {
				//console.log(ui);
				_self.searchTerm = ui.item.label;
				_self.selectedLead = ui.item.value;
        	}
		});

		if(this.$route.params.id){
			this.leadId = this.$route.params.id;
			this.inboxStore.listSMS(this.leadId);
            this.inboxStore.listSMS();
			this.rightView = 'chat';
			//this.setUpTimer();
		}else{
			if(this.$route.query.view === 'list'){
				this.showSMS();
			}else{
				this.addSMS();
				this.inboxStore.listSMS();
			}
		}
	},
	watch:{
		$route (to, from){
			if(to.name === 'inbox' && this.$route.query.view === 'list'){
				this.showSMS();
			}
			if(to.name === 'inbox' && this.$route.query.view === 'new'){
				this.addSMS();
			}
			if(this.timer) clearInterval(this.timer);
		}
	},
	methods: {
		addSMS(){
			this.rightView = 'new';
			this.leadId = null;
			if(this.timer) clearInterval(this.timer);
		},
		showChat(id){
			this.leadId = id;
			this.rightView = 'chat';
			if(this.timer) clearInterval(this.timer);
			//this.setUpTimer();
		},
		showSMS(){
			this.leadId = null;
			this.rightView = 'list';
			this.inboxStore.listSMS(this.leadId);
			if(this.timer) clearInterval(this.timer);
		},
		sendChat(){
			this.inboxStore.sendChat(this.leadId, this.message);
			this.message = '';
		},
		searchLeads(){
			if(this.searchTerm.length>3){
				this.inboxStore.searchLeads(this.searchTerm);
			}
		},
		sendNewSms(){
			if(!this.newMessage){
				this.errors['newMessage'] = 'The message field is required!';
			}

			if(!this.selectedLead){
				this.errors['selectedLead'] = 'The lead field is required';
			}

			if(Object.keys(this.errors).length>0){
				return null;
			}

			this.inboxStore.sendNewSms(this.selectedLead, this.newMessage);

			this.newMessage = '';
			this.searchTerm = '';
			this.selectedLead = null;
			this.searchedLeads = null
		},
		searchSMS(){
			if(this.searchTermSms.length>=3){
				this.inboxStore.searchSMS(this.searchTermSms);
			}else if(this.searchTermSms.length==0){
				this.inboxStore.searchSMS();
			}
		},
		setUpTimer()
		{
			let _self = this;
			if(_self.leadId){
                clearInterval(_self.timer);
				_self.timer = setInterval(function(){
					_self.inboxStore.listSMS(_self.leadId, false);
				}, 10000);
			}
		},
		prev(){
			this.inboxStore.page--;
			this.inboxStore.listSMS(null);
		},
		next(){
			this.inboxStore.page++;
			this.inboxStore.listSMS(null);
		},
		getFullName(chat) {
			if (!chat.dis_last_name) {
			return chat.dis_first_name.toUpperCase();
			} else {
			return chat.dis_first_name + ' ' + chat.dis_last_name;
			}
		},
		getInitials(firstName, lastName) {
			if (!lastName) {
			return firstName.substring(0, 2).toUpperCase();
			} else {
			return firstName.charAt(0).toUpperCase() + lastName.charAt(0).toUpperCase();
			}
		},
        insertTemplateText(){
            this.newMessage = this.selectedTextTemplate.body;
        },
        insertBodyTemplate(){
            this.rightView = 'chat';
            this.message = this.selectedTextTemplate.body
        },
        viewTemplate(){
            localStorage.setItem('last_path', '/crtx/manage-practice?page=text-templates');
            window.open('/crtx/manage-practice?page=text-templates', '_blank');
        },
        unreadClick(){
            this.inboxStore.listSMS(this.leadId,true,true);
            this.unread_click = true;
        },
        AllSmsClick(){
            this.inboxStore.listSMS(this.leadId,true,false);
            this.unread_click = false;
        }
    }
}
</script>

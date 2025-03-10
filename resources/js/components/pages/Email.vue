<template>
    <div id="main" class="bg-light-gray mt-3" v-if="authStore.inbox_id">
		<div class="inbox-main" :class="rightView=='new' ? 'new-sms-active' : ''">
			<div class="inbox-left">
				<div class="inbox-left-top">
					<div class="inbox-left-title">
						<div class="d-flex align-items-center">
							<div class="d-flex align-items-center">
								<h2>Inbox</h2>
								<!-- <span class="notification-number">{{inboxStore.totalUnread}}</span> -->
							</div>
							<div class="add-sms-icon tooltip-ico" data-title="New Email">
								<router-link :to="routeName === 'dashboard' ? '/crtx/dashboard?view=new&page=email' : '/crtx/inbox?view=new&page=email'">
									<svg width="21" height="21" viewBox="0 0 21 21" xmlns="http://www.w3.org/2000/svg">
										<path d="M12.4763 0.836293V9.03629H20.2563V12.6963H12.4763V20.9363H8.47631V12.6963H0.736306V9.03629H8.47631V0.836293H12.4763Z"></path>
									</svg>
								</router-link>
							</div>
						</div>
					</div>
					<div class="sms-email-box">
						<router-link :to="routeName === 'dashboard' ? '/crtx/dashboard?view=list&page=sms' : '/crtx/inbox?view=list&page=sms'" id="linkSMS">Text <span>{{ inboxStore.totalUnread }}</span></router-link>
						<router-link v-if="authStore.inbox_id" :to="routeName === 'dashboard' ? '/crtx/dashboard?view=list&page=email&type=received' : '/crtx/inbox?view=list&page=email&type=received'" class="activated" id="linkEmail" >Email <span>{{ inboxStore.totalReceivedEmailsUnreadCount ?? 0 }}</span></router-link>
					</div>
					<div class="sms-email-box">
						<router-link v-if="authStore.inbox_id" :to="routeName === 'dashboard' ? '/crtx/dashboard?view=list&page=email&type=received' : '/crtx/inbox?view=list&page=email&type=received'" :class="inboxStore.emailType=='received' ? 'activated' : ''">Received</router-link>
						<router-link v-if="authStore.inbox_id" :to="routeName === 'dashboard' ? '/crtx/dashboard?view=list&page=email&type=sent' : '/crtx/inbox?view=list&page=email&type=sent'" :class="inboxStore.emailType=='sent' ? 'activated' : ''">Sent</router-link>
					</div>
					<div class="inbox-search">
						<span class="search-input-ico">
							<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M13.97 15.031C12.491 16.269 10.586 17.016 8.509 17.016C3.812 17.016 0 13.204 0 8.508C0 3.812 3.812 0 8.509 0C13.204 0 17.017 3.812 17.017 8.508C17.017 10.586 16.27 12.492 15.032 13.969L19.781 18.719C19.927 18.865 20 19.057 20 19.25C20 19.837 19.463 20 19.25 20C19.058 20 18.866 19.927 18.719 19.78L13.97 15.031ZM8.509 1.501C4.641 1.501 1.502 4.641 1.502 8.508C1.502 12.375 4.641 15.515 8.509 15.515C12.375 15.515 15.516 12.375 15.516 8.508C15.516 4.641 12.375 1.501 8.509 1.501Z" fill="#959595"/>
							</svg>
						</span>
						<input type="text" class="form-control" placeholder="Search inbox..." v-model="searchTermEmail" v-on:keyup="searchEmails">
					</div>
				</div>
				<div class="inbox-chat-list">
					<div class="tab-menu-list" v-if="rightView != 'list' && rightView != 'new' && inboxStore.emailList.length>0">
						<!-- <div class="d-flex justify-content-center py-2"  v-if="!inboxStore.firstEmail">
							<a role="button" class="btn btn-primary" @click.prevent="prev">Load Prev</a>
						</div> -->
						<router-link
                            v-if="inboxStore.emailList.length>0 && inboxStore.emailType=='received'"
                            v-for="email in inboxStore.emailList"
                            :to="(routeName === 'dashboard' ? '/crtx/dashboard/' : '/crtx/inbox/')+email.data.dataEmails.id+'?page=email&type='+inboxStore.emailType+'&email='+(inboxStore.emailType=='received'? email.data.dataEmails.from : email.data.dataEmails.to[0])"
                            class="tab-menu"
                            data-rel="tab-1"
                            @click="showEmail(email.data.dataEmails.from)"
                            :class="{email_active:email.data.dataEmails.from===emailId, unread : inboxStore.emailType=='received' && !email.read}"
                        >
                            <!-- active -->
							<div class="chat-list-box 4r">
								<div class="chat-profile-img">
									<figure>
										<span v-if="inboxStore.emailType=='received'">{{ email.data.dataEmails.from.split('@')[0].charAt(0).toUpperCase() + email.data.dataEmails.from.split('@')[1].charAt(0).toUpperCase() }}</span>
										<span v-else>{{ email.data.dataEmails.to[0].split('@')[0].charAt(0).toUpperCase() + email.data.dataEmails.to[0].split('@')[1].charAt(0).toUpperCase() }}</span>
									</figure>
								</div>
								<div  class="chat-profile-info">
									<h6>{{inboxStore.emailType=='received' ? email.data.dataEmails.from : email.data.dataEmails.to[0] }}</h6>
									<p>{{ email.data.dataEmails.subject }}</p>
								</div>
								<div class="chat-profile-date">
									<span>{{new Date(email.data.dataEmails.createdAt).toLocaleDateString('en-us', { year:"numeric", month:"short", day:"numeric"})}}</span>{{new Date(email.data.dataEmails.createdAt).toLocaleTimeString('en-us', {hour: '2-digit', minute:'2-digit'}) }}
								</div>
                                <div class="chat-profile-count" v-if="email.data.count > 1">
                                    <span class="badge bg-primary">+{{ email.data.count-1 }}</span>
                                </div>
                            </div>
						</router-link>
                        <router-link
                            v-if="inboxStore.emailList.length>0 && inboxStore.emailType=='sent'"
                            v-for="email in inboxStore.emailList"
                            :to="(routeName === 'dashboard' ? '/crtx/dashboard/' : '/crtx/inbox/') + email.data.dataEmails.id+'?page=email&type='+inboxStore.emailType+'&email='+(inboxStore.emailType=='received'? email.data.dataEmails.from : email.data.dataEmails.to[0])"
                            class="tab-menu"
                            data-rel="tab-1"
                            @click="showEmail(email.data.dataEmails.to[0])"
                            :class="{email_active:email.data.dataEmails.to[0]===emailId, unread : inboxStore.emailType=='received' && !email.read}"
                        >
                            <!-- active -->
                            <div class="chat-list-box 4s">
                                <div class="chat-profile-img">
                                    <figure>
                                        <span v-if="inboxStore.emailType=='received'">{{ email.data.dataEmails.from.split('@')[0].charAt(0).toUpperCase() + email.data.dataEmails.from.split('@')[1].charAt(0).toUpperCase() }}</span>
                                        <span v-else>{{ email.data.dataEmails.to[0].split('@')[0].charAt(0).toUpperCase() + email.data.dataEmails.to[0].split('@')[1].charAt(0).toUpperCase() }}</span>
                                    </figure>
                                </div>
                                <div  class="chat-profile-info">
                                    <h6>{{inboxStore.emailType=='received' ? email.data.dataEmails.from : email.data.dataEmails.to[0] }}</h6>
                                    <p>{{ email.data.dataEmails.subject }}</p>
                                </div>
                                <div class="chat-profile-date">
                                    <span>{{new Date(email.data.dataEmails.createdAt).toLocaleDateString('en-us', { year:"numeric", month:"short", day:"numeric"})}}</span>{{new Date(email.data.dataEmails.createdAt).toLocaleTimeString('en-us', {hour: '2-digit', minute:'2-digit'}) }}
                                </div>
                                <div class="chat-profile-count" v-if="email.data.count > 1">
                                    <span class="badge bg-primary">+{{ email.data.count-1 }}</span>
                                </div>
                            </div>
                        </router-link>

						<div class="d-flex justify-content-center py-2" v-if="!inboxStore.lastEmail && inboxStore.emailList.length>0">
							<a  role="button" class="btn btn-primary" @click.prevent="next">Load More...</a>
						</div>
					</div>
					<div v-if="inboxStore.emailList.length==0" class="text-center m-3">
						There are no emails to display
					</div>
				</div>
			</div>
			<div class="inbox-right">
				<div v-show="inboxStore.emailSent" class="alert alert-success alert-dismissible fade show mb-0" role="alert">
					Email Sent Successfully!
					<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" @click="inboxStore.emailSent = false"></button>
				</div>
				<div class="inbox-tab" v-if="rightView == 'read' && inboxStore.selectedEmails">
					<div id="tab-1" class="tab-box tab-active">
						<div class="chat-log">
							<div class="chat-log-top">
								<router-link role="button" :to="routeName === 'dashboard' ? '/crtx/dashboard?view=list&page=email' : '/crtx/inbox?view=list&page=email'" class="chat-log-back" @click="showEmail">
									<svg width="20" height="14" viewBox="0 0 20 14" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path fill-rule="evenodd" clip-rule="evenodd" d="M19.4411 6.37503H2.04859L7.25172 1.19191C7.49609 0.947534 7.49609 0.551909 7.25172 0.308159C7.00734 0.0637842 6.61172 0.0637842 6.36797 0.308159L0.180469 6.49503C-0.0601562 6.73566 -0.0601562 7.13816 0.180469 7.37878L6.36797 13.5663C6.61234 13.8107 7.00797 13.8107 7.25172 13.5663C7.49609 13.3219 7.49609 12.9263 7.25172 12.6825L2.04859 7.62503H19.4411C19.7861 7.62503 20.0661 7.34503 20.0661 7.00003C20.0661 6.65503 19.7861 6.37503 19.4411 6.37503Z" fill="#514F5F"></path>
									</svg>
								</router-link>
								<div class="chat-title-box justify-content-between">
									<div class="chat-title-box justify-content-start">
										<div class="chat-profile-img">
											<figure>
												<span>
                                                    {{ inboxStore.selectedEmails[0].data.to[0]
                                                        ? (inboxStore.selectedEmails[0].data.to[0].split('@')[0].charAt(0).toUpperCase() +
                                                            inboxStore.selectedEmails[0].data.to[0].split('@')[1].charAt(0).toUpperCase())
                                                        : ''
                                                    }}
                                                    </span>
                                            </figure>
										</div>
										<div>
											<h3 v-if="inboxStore.selectedEmails && inboxStore.selectedEmails[0].data.from">{{ inboxStore.emailType=='received' ? inboxStore.selectedEmails[0].data.to[0] : inboxStore.selectedEmails[0].data.to[0]}}</h3>
											<h5 class="mb-0 mt-1">Subject: {{ inboxStore.selectedEmails ? inboxStore.selectedEmails[0].data.subject : ''}}</h5>
											<span v-if="inboxStore.emailType=='received'">From: {{ inboxStore.selectedEmails? inboxStore.selectedEmails[0].data.from : '' }}</span>
											<span v-else>From: {{ inboxStore.selectedEmails? inboxStore.selectedEmails[0].data.from : '' }}</span>
										</div>
									</div>
									<div>
										<a role="button" class="patient-profile-link" v-if="inboxStore.emailType === 'received'" @click="inboxStore.showReply=!inboxStore.showReply; inboxStore.emailForm.subject='Re: '+inboxStore.selectedEmails[0].data.subject; inboxStore.emailForm.body='<br><br>------------------------------'+inboxStore.selectedEmails[0].body;" style="margin-right:20px;">
										 Reply <svg width="64px" height="64px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="#425BCF" transform="matrix(-1, 0, 0, 1, 0, 0)"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M14.6644 5.47875L18.6367 9.00968C20.2053 10.404 20.9896 11.1012 20.9896 11.9993C20.9896 12.8975 20.2053 13.5946 18.6367 14.989L14.6644 18.5199C13.9484 19.1563 13.5903 19.4746 13.2952 19.342C13 19.2095 13 18.7305 13 17.7725V15.4279C9.4 15.4279 5.5 17.1422 4 19.9993C4 10.8565 9.33333 8.57075 13 8.57075V6.22616C13 5.26817 13 4.78917 13.2952 4.65662C13.5903 4.52407 13.9484 4.8423 14.6644 5.47875Z" stroke="#425BCF" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>
										</a>
										<!-- <a role="button" class="patient-profile-link" @click="inboxStore.showReply=!inboxStore.showReply; inboxStore.emailForm.subject='Fw: '+inboxStore.selectedEmails[0].data.subject; inboxStore.emailForm.to=''; inboxStore.emailForm.body='<br><br>------------------------------'+inboxStore.selectedEmails[0].body;">
											Forward <svg width="64px" height="64px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="#425BCF"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M14.6644 5.47875L18.6367 9.00968C20.2053 10.404 20.9896 11.1012 20.9896 11.9993C20.9896 12.8975 20.2053 13.5946 18.6367 14.989L14.6644 18.5199C13.9484 19.1563 13.5903 19.4746 13.2952 19.342C13 19.2095 13 18.7305 13 17.7725V15.4279C9.4 15.4279 5.5 17.1422 4 19.9993C4 10.8565 9.33333 8.57075 13 8.57075V6.22616C13 5.26817 13 4.78917 13.2952 4.65662C13.5903 4.52407 13.9484 4.8423 14.6644 5.47875Z" stroke="#425BCF" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>
										</a> -->
									</div>
								</div>
							</div>
							<div class="chat-log-bottom bg-light-gray" >
								<!-- <div class="msger-email m-1">
									<div class="m-0 p-2 border-1 rounded" v-html="inboxStore.selectedEmails[0]? inboxStore.selectedEmails[0].body : ''" >

									</div>
								</div> -->

								<div class="msger-email m-1 p-2 border-1 rounded"  v-for="(email, index) in inboxStore.selectedEmails" @click="expandEmail(email.data.id)" :class="[index!=0? 'shrinked-email ' + email.data.id :  '']">
									<div class="chat-list-box 2" :class="email.data.read==true || email.data.read==false ? 'chat-list-box-grey' : ''">
										<div class="chat-profile-img"  v-if="index!=0">
											<figure>
												<span>{{ email.data.from.split('@')[0].charAt(0).toUpperCase() + email.data.from.split('@')[1].charAt(0).toUpperCase() }}</span>
											</figure>
										</div>
										<div  class="chat-profile-info"  v-if="index!=0">
											<h6>{{ email.data.from }}</h6>
											<p>{{ 'To: ' + email.data.to[0] }}</p>
											<p>{{ 'Subj: ' + email.data.subject }}</p>
<!--											<div v-if="email.data.read==false">-->
<!--												<svg  fill="#000000" width="16px" height="16px" viewBox="0 0 32 32" version="1.1" xmlns="http://www.w3.org/2000/svg" role="img"><title>Delivered</title><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M30.844 3.602c-0.221-0.204-0.518-0.328-0.844-0.328-0.365 0-0.693 0.156-0.921 0.406l-0.001 0.001-20.121 21.993-6.076-6.036c-0.226-0.226-0.538-0.366-0.883-0.366-0.69 0-1.25 0.56-1.25 1.25 0 0.348 0.142 0.663 0.371 0.889l0 0 7 6.953 0.022 0.015 0.015 0.021c0.074 0.061 0.159 0.114 0.25 0.156l0.007 0.003c0.037 0.026 0.079 0.053 0.123 0.077l0.007 0.003c0.135 0.056 0.292 0.089 0.457 0.089 0.175 0 0.341-0.037 0.491-0.103l-0.008 0.003c0.053-0.031 0.098-0.061 0.14-0.094l-0.003 0.002c0.102-0.050 0.189-0.11 0.268-0.179l-0.001 0.001 0.015-0.023 0.020-0.014 21-22.953c0.204-0.221 0.328-0.518 0.328-0.844 0-0.365-0.156-0.693-0.406-0.921l-0.001-0.001zM8.876 15.204l0.022 0.015 0.015 0.021c0.073 0.059 0.156 0.111 0.244 0.152l0.007 0.003c0.039 0.028 0.083 0.056 0.13 0.081l0.007 0.003c0.135 0.056 0.292 0.088 0.456 0.088 0.175 0 0.34-0.037 0.491-0.102l-0.008 0.003c0.053-0.031 0.098-0.061 0.14-0.094l-0.003 0.002c0.102-0.050 0.189-0.11 0.268-0.179l-0.001 0.001 0.015-0.023 0.020-0.014 11.269-12.317c0.203-0.221 0.328-0.518 0.328-0.844 0-0.69-0.559-1.25-1.25-1.25-0.365 0-0.693 0.156-0.921 0.405l-0.001 0.001-10.39 11.357-2.833-2.814c-0.226-0.226-0.538-0.366-0.883-0.366-0.69 0-1.25 0.56-1.25 1.25 0 0.348 0.142 0.663 0.372 0.889l0 0z"></path> </g></svg>-->
<!--											</div>-->
<!--											<div v-if="email.data.read==true">-->
<!--												<svg  fill="#355ADD" width="16px" height="16px" viewBox="0 0 32 32" version="1.1" xmlns="http://www.w3.org/2000/svg" role="img"><title>Delivered</title><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M30.844 3.602c-0.221-0.204-0.518-0.328-0.844-0.328-0.365 0-0.693 0.156-0.921 0.406l-0.001 0.001-20.121 21.993-6.076-6.036c-0.226-0.226-0.538-0.366-0.883-0.366-0.69 0-1.25 0.56-1.25 1.25 0 0.348 0.142 0.663 0.371 0.889l0 0 7 6.953 0.022 0.015 0.015 0.021c0.074 0.061 0.159 0.114 0.25 0.156l0.007 0.003c0.037 0.026 0.079 0.053 0.123 0.077l0.007 0.003c0.135 0.056 0.292 0.089 0.457 0.089 0.175 0 0.341-0.037 0.491-0.103l-0.008 0.003c0.053-0.031 0.098-0.061 0.14-0.094l-0.003 0.002c0.102-0.050 0.189-0.11 0.268-0.179l-0.001 0.001 0.015-0.023 0.020-0.014 21-22.953c0.204-0.221 0.328-0.518 0.328-0.844 0-0.365-0.156-0.693-0.406-0.921l-0.001-0.001zM8.876 15.204l0.022 0.015 0.015 0.021c0.073 0.059 0.156 0.111 0.244 0.152l0.007 0.003c0.039 0.028 0.083 0.056 0.13 0.081l0.007 0.003c0.135 0.056 0.292 0.088 0.456 0.088 0.175 0 0.34-0.037 0.491-0.102l-0.008 0.003c0.053-0.031 0.098-0.061 0.14-0.094l-0.003 0.002c0.102-0.050 0.189-0.11 0.268-0.179l-0.001 0.001 0.015-0.023 0.020-0.014 11.269-12.317c0.203-0.221 0.328-0.518 0.328-0.844 0-0.69-0.559-1.25-1.25-1.25-0.365 0-0.693 0.156-0.921 0.405l-0.001 0.001-10.39 11.357-2.833-2.814c-0.226-0.226-0.538-0.366-0.883-0.366-0.69 0-1.25 0.56-1.25 1.25 0 0.348 0.142 0.663 0.372 0.889l0 0z"></path> </g></svg>-->
<!--											</div>-->
										</div>
										<div class="chat-profile-date">
											<!-- <div class="add-sms-icon email-expand">
												<router-link to="/crtx/inbox?view=new&page=email">
													<svg fill="#FFFFFF" height="200px" width="200px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 242.133 242.133" xml:space="preserve" stroke="#FFFFFF"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path id="XMLID_15_" d="M227.133,83.033c8.283,0,15-6.716,15-15V15c0-8.284-6.717-15-15-15H174.1c-8.284,0-15,6.716-15,15 s6.716,15,15,15h16.82l-69.854,69.854L51.213,30h16.82c8.284,0,15-6.716,15-15s-6.716-15-15-15H15C6.717,0,0,6.716,0,15v53.033 c0,8.284,6.717,15,15,15c8.285,0,15-6.716,15-15v-16.82l69.854,69.854L30,190.92V174.1c0-8.284-6.715-15-15-15 c-8.283,0-15,6.716-15,15v53.033c0,8.284,6.717,15,15,15h53.033c8.284,0,15-6.716,15-15c0-8.284-6.716-15-15-15h-16.82 l69.854-69.854l69.854,69.854H174.1c-8.284,0-15,6.716-15,15c0,8.284,6.716,15,15,15h53.033c8.283,0,15-6.716,15-15V174.1 c0-8.284-6.717-15-15-15c-8.285,0-15,6.716-15,15v16.82l-69.854-69.854l69.854-69.854v16.82 C212.133,76.317,218.848,83.033,227.133,83.033z"></path> </g></svg>
												</router-link>
											</div> -->
											<div>
												<span>{{new Date( email.data.createdAt).toLocaleDateString('en-us', { year:"numeric", month:"short", day:"numeric"})}}</span>{{new Date( email.data.createdAt).toLocaleTimeString('en-us', {hour: '2-digit', minute:'2-digit'}) }}
											</div>

										</div>
									</div>
									<div class="mb-2 p-2" v-html="email.body" :class="email.data.read ? 'body-grey' : ''">

									</div>
								</div>
								<div class="msger-inputarea" v-if="inboxStore.showReply">
									<button type="button" class="btn-close mb-2" aria-label="Close" style="float:right;" @click="inboxStore.showReply = false">
										<svg width="20" height="14" viewBox="0 0 20 14" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path fill-rule="evenodd" clip-rule="evenodd" d="M19.4411 6.37503H2.04859L7.25172 1.19191C7.49609 0.947534 7.49609 0.551909 7.25172 0.308159C7.00734 0.0637842 6.61172 0.0637842 6.36797 0.308159L0.180469 6.49503C-0.0601562 6.73566 -0.0601562 7.13816 0.180469 7.37878L6.36797 13.5663C6.61234 13.8107 7.00797 13.8107 7.25172 13.5663C7.49609 13.3219 7.49609 12.9263 7.25172 12.6825L2.04859 7.62503H19.4411C19.7861 7.62503 20.0661 7.34503 20.0661 7.00003C20.0661 6.65503 19.7861 6.37503 19.4411 6.37503Z" fill="#514F5F"></path>
										</svg>
									</button>
									<!-- :readonly="inboxStore.selectedEmails[0]? inboxStore.selectedEmails[0].sender.emailAddress : false" -->
									<input type="text" class="form-control mb-2" placeholder="To" v-model="inboxStore.emailForm.to" required />
									<span class="d-block text-danger 1" v-text="replyErrors.to"></span>
									<div class="d-flex justify-content-end m-2 gap-2">
										<a href="#" class="edit-input manage-edit fs-6" v-show="!profile_editing" @click="showReplyCC=!showReplyCC; inboxStore.emailForm.cc = '';">
											Cc
										</a>
										<a href="#" class="edit-input manage-edit fs-6" v-show="!profile_editing" @click="showReplyBCC=!showReplyBCC; inboxStore.emailForm.bcc = '';">
											Bcc
										</a>
									</div>
									<input type="text" class="form-control mb-2" placeholder="Cc" v-model="inboxStore.emailForm.cc" v-if="showReplyCC"/>
									<span class="d-block text-danger 1" v-text="replyErrors.cc"></span>
									<input type="text" class="form-control mb-2" placeholder="Bcc" v-model="inboxStore.emailForm.bcc" v-if="showReplyBCC"/>
									<span class="d-block text-danger 1" v-text="replyErrors.bcc" ></span>
                                    <span class="my-2 d-block text-black-50">Choose a template to use for this email.</span>
                                    <div class="d-flex justify-content-start mb-3 gap-3">
                                        <select class="form-select exportSelect w-50" placeholder="Select Template" v-model="selectedEmailTemplate">
                                            <option disabled selected :value="null">Select Template</option>
                                            <option v-for="template in clinicStore.emailTemplates" :value="template">{{template.template_name}}</option>
                                        </select>
                                        <a href="#" class="edit-input manage-edit fs-6 w-25" @click="prependTemplateText()">
                                            Insert Text
                                        </a>
                                        <a @click="viewTemplate()" role="button"  class="btn-link w-25 pt-2">View Templates</a>
                                    </div>
									<input type="text" class="form-control mb-2" placeholder="Subject" v-model="inboxStore.emailForm.subject" />
									<span class="d-block text-danger 1" v-text="replyErrors.subject" ></span>
									<ckeditor :editor="editor" v-model="inboxStore.emailForm.body" :config="editorConfig"></ckeditor>
									<span class="d-block text-danger 1" v-text="replyErrors.body"></span>
									<button type="submit" class="email-sent-btn" @click="sendEmail" style="position:relative; float:right; margin-top:20px;">
										Send
									</button>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="chat-list-tab" v-if="rightView == 'list'">
					<div class="d-none d-lg-block chat-log-top">
						<div class="chat-title-box align-items-center">
							<h3>Emails Inbox: <span>{{ inboxStore.inboxEmail ? inboxStore.inboxEmail : '' }}</span></h3>
							<span class="ms-auto sms-info">
							 <b v-if="inboxStore.emailType=='received'" @click="unreadClick()" role="button">
                                {{inboxStore.emailType=='received'? inboxStore.totalReceivedEmailsUnreadCount : ''}} Unread |
                             </b>
                                <span v-if="!unread_click">
                                    {{inboxStore.totalEmails}} Emails
                                </span>
                                <span v-else>
                                    <span @click="AllEmailsClick()" role="button">All Emails</span>
                                </span>
							</span>
						</div>
					</div>
					<div class="inbox-chat-list">
						<div class="tab-menu-list">
							<!-- <div class="d-flex justify-content-center py-2" v-if="!inboxStore.firstEmail && inboxStore.emailList.length>0">
								<a  role="button" class="btn btn-primary" @click.prevent="prev">Load Prev1</a>
							</div> -->
							<router-link
                                v-if="inboxStore.emailList.length>0"
                                v-for="email in inboxStore.emailList"
                                :to="(routeName === 'dashboard' ? '/crtx/dashboard/' : '/crtx/inbox/') + email.data.dataEmails.id+'?page=email&type='+inboxStore.emailType+'&email='+ (inboxStore.emailType=='received'? email.data.dataEmails.from : email.data.dataEmails.to[0])"
                                class="d-block"
                                @click="showEmail(email.data.dataEmails.from)"
                                :class="{email_active:email.data.dataEmails.to[0] === emailId, unread : inboxStore.emailType=='received' && !email.read}">
								<div class="chat-list-box 3">
									<div class="chat-profile-img">
										<figure>
											<span v-if="inboxStore.emailType=='received'">{{ email.data.dataEmails.from.split('@')[0].charAt(0).toUpperCase() + email.data.dataEmails.from.split('@')[1].charAt(0).toUpperCase() }}</span>
											<span v-else>{{ email.data.dataEmails.to[0].split('@')[0].charAt(0).toUpperCase() + email.data.dataEmails.to[0].split('@')[1].charAt(0).toUpperCase() }}</span>
										</figure>
									</div>
									<div  class="chat-profile-info">
										<h6>{{inboxStore.emailType=='received' ? email.data.dataEmails.from : email.data.dataEmails.to[0] }}</h6>
										<p>{{ email.data.dataEmails.subject }}</p>
									</div>
									<div class="chat-profile-date">
										<span>{{new Date(email.data.dataEmails.createdAt).toLocaleDateString('en-us', { year:"numeric", month:"short", day:"numeric"})}}</span>{{ new Date(email.data.dataEmails.createdAt).toLocaleTimeString('en-us', {hour: '2-digit', minute:'2-digit'}) }}
									</div>
                                    <div class="chat-profile-count" v-if="email.data.count > 1">
                                        <span class="badge bg-primary">+{{ email.data.count-1 }}</span>
                                    </div>
                                </div>
							</router-link>
							<div class="d-flex justify-content-center py-2" v-if="!inboxStore.lastEmail && inboxStore.emailList.length>0">
								<a  role="button" class="btn btn-primary" @click.prevent="next">Load More...</a>
							</div>
						</div>
					</div>
				</div>
				<div class="new-sms-box" v-show="rightView=='new'">
					<div class="chat-log-top">
						<router-link :to="routeName === 'dashboard' ? '/crtx/dashboard?view=list&page=email' : '/crtx/inbox?view=list&page=email'" class="sms-log-mobile sms-log-back">
							<svg width="20" height="14" viewBox="0 0 20 14" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path fill-rule="evenodd" clip-rule="evenodd" d="M19.4411 6.37503H2.04859L7.25172 1.19191C7.49609 0.947534 7.49609 0.551909 7.25172 0.308159C7.00734 0.0637842 6.61172 0.0637842 6.36797 0.308159L0.180469 6.49503C-0.0601562 6.73566 -0.0601562 7.13816 0.180469 7.37878L6.36797 13.5663C6.61234 13.8107 7.00797 13.8107 7.25172 13.5663C7.49609 13.3219 7.49609 12.9263 7.25172 12.6825L2.04859 7.62503H19.4411C19.7861 7.62503 20.0661 7.34503 20.0661 7.00003C20.0661 6.65503 19.7861 6.37503 19.4411 6.37503Z" fill="#514F5F"></path>
							</svg>
						</router-link>
						<div class="chat-title-box align-items-center">
							<h3>New Email</h3>
							<router-link :to="routeName === 'dashboard' ? '/crtx/dashboard?view=list&page=email' : '/crtx/inbox?view=list&page=email'" class="ms-auto sms-log-desktop sms-log-back" @click="showInbox">
								Cancel
							</router-link>
						</div>
						<!-- <div class="sms-inputarea-box">
							<div class="input-group">
								<div class="input-group-prepend">
									<label>To:</label>
								</div>
								<input type="search" id="emailSearchMobile" class="form-control" v-model="searchTerm" v-on:keyup="searchEmails"/>
							</div>
						</div> -->
					</div>
					<div class="chat-log-bottom">
						<div class="msger-inputarea">
							<input type="text" class="form-control mb-2" placeholder="To" v-model="inboxStore.emailForm.to" required/>
							<span class="d-block text-danger 1" v-text="errors.to"></span>
							<div class="d-flex justify-content-end m-2 gap-2">
								<a href="#" class="edit-input manage-edit fs-6" v-show="!profile_editing" @click.prevent="toggleReplyField('cc')">
									Cc
								</a>
								<a href="#" class="edit-input manage-edit fs-6" v-show="!profile_editing" @click.prevent="toggleReplyField('bcc')">
									Bcc
								</a>
							</div>
							<input type="text" class="form-control mb-2" placeholder="Cc" v-model="inboxStore.emailForm.cc" v-if="showNewCC"/>
							<span class="d-block text-danger" v-text="errors.cc"></span>
							<input type="text" class="form-control mb-2" placeholder="Bcc" v-model="inboxStore.emailForm.bcc" v-if="showNewBCC"/>
							<span class="d-block text-danger" v-text="errors.bcc"></span>
                            <span class="my-2 d-block text-black-50">Choose a template to use for this email.</span>
                            <div class="d-flex justify-content-start mb-3 gap-3">
                                <select class="form-select exportSelect w-50" placeholder="Select Template" v-model="selectedEmailTemplate">
                                    <option disabled selected :value="null">Select Template</option>
                                    <option v-for="template in clinicStore.emailTemplates" :value="template">{{template.template_name}}</option>
                                </select>
                                <a href="#" class="edit-input manage-edit fs-6 w-25" @click.prevent="insertTemplateText()">
                                    Insert Text
                                </a>
                                <a @click="viewTemplate()" role="button" class="btn-link w-25 pt-2">View Templates</a>
                            </div>
							<input type="text" class="form-control mb-2" placeholder="Subject" v-model="inboxStore.emailForm.subject"/>
							<span class="d-block text-danger" v-text="errors.subject"></span>
							<ckeditor :editor="editor" v-model="inboxStore.emailForm.body" :config="editorConfig" style="white-space:pre; height:100%;"></ckeditor>
							<span class="d-block text-danger 1" v-text="errors.body"></span>
							<button type="submit" class="email-sent-btn" @click="sendNewEmail" style="position:relative; float:right; margin-top:20px;">
								Send
							</button>
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
import CKEditor from '@ckeditor/ckeditor5-vue';
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
import router from '../../routes';
import {useClinicStore} from "../../stores/clinic";
import UploadAdapter from '../../stores/UploadAdapter';

export default {
    setup (){
        const inboxStore = useInboxStore();
		const authStore = useAuthStore();
        const clinicStore = useClinicStore()

        return { inboxStore, authStore, clinicStore };
    },
    data() {
        return {
			errors: [],
			replyErrors: [],
			showNewCC:false,
			showNewBCC:false,
			showReplyCC:false,
			showReplyBCC:false,
			rightView : 'list',
			emailId : null,
			emailAddress:null,
            unread_click:false,
			message : '',
			searchTerm : '',
			newEmail: '',
			searchTermEmail: '',
            selectedEmailTemplate:null,
			//showReply: false,
			editor: ClassicEditor,
			editorConfig: {
				extraPlugins: [this.uploader],
			},
            routeName:'',
        }
    },
	components:{ckeditor: CKEditor.component},
    mounted(){

		let _self = this;

        this.routeName = this.$route.name;

		this.searchTerm = '';

        $('#homeLink').removeClass('active');
		$('#profileLink').removeClass('show');
		$('#mailLink').addClass('active');

        this.clinicStore.getTemplates('email');

		if(this.$route.query.type)
			this.inboxStore.emailType = this.$route.query.type;

		if(this.$route.query.email)
			this.inboxStore.emailAddress = this.$route.query.email;

		if(this.$route.params.id && this.$route.query.view != 'new'){
            this.emailId = this.$route.query.email;
			this.inboxStore.showEmail();
			this.inboxStore.listEmails();
			this.rightView = 'read';
		}else{
			if(this.$route.query.view == 'list'){
				this.showInbox();
			}else{
				this.addEmail();
				this.inboxStore.listEmails();
                this.inboxStore.emailForm.to = '';
                if(this.$route.query.email){
                    this.inboxStore.emailForm.to = this.$route.query.email;
                }
				this.inboxStore.emailForm.subject = '';
				this.inboxStore.emailForm.body = '';
				this.inboxStore.emailForm.cc = '';
				this.inboxStore.emailForm.bcc = '';
			}
		}
	},
	watch:{
		$route (to, from){
            if(to.name == 'inbox' && this.$route.query.view == 'list'){
				//this.showInbox();
			}
			if(to.name == 'inbox' && this.$route.query.view == 'new'){
				this.addEmail();
			}
			//if(this.timer) clearInterval(this.timer);
		}
	},
	methods: {
        toggleReplyField(field){
            if (field === 'cc') {
                this.showNewCC = !this.showNewCC;
                if (!this.showNewCC) {
                    this.inboxStore.emailForm.cc = ''; // Clear only when cc is hidden
                }
            } else if (field === 'bcc') {
                this.showNewBCC = !this.showNewBCC;
                if (!this.showNewBCC) {
                    this.inboxStore.emailForm.bcc = ''; // Clear only when bcc is hidden
                }
            }
        },
		addEmail(){
			this.rightView = 'new';
			this.emailId = null;
			this.inboxStore.emailForm.to = '';
		},
		showEmail(email){
			this.emailId = email;
			this.rightView = 'read';
			this.inboxStore.readSendEmail(email);
			this.inboxStore.showReply = false;
		},
		showInbox(){
			this.emailId = null;
			this.rightView = 'list';
			this.inboxStore.listEmails();
		},
		sendEmail(){
			this.replyErrors = [];
            if (!this.inboxStore.emailForm.to) {
                this.replyErrors['to'] = 'The "to" field is required!';
            } else {
                // Split by comma, trim whitespace, and filter out empty strings
                const toEmails = this.inboxStore.emailForm.to.split(',').map(email => email.trim()).filter(email => email);

                // Validate each email address in the list
                const invalidEmails = toEmails.filter(email =>
                    !/^[^@]+@\w+(\.\w+)+\w$/.test(email) || email.includes('@noreply.com')
                );

                if (invalidEmails.length > 0) {
                    this.replyErrors['to'] = invalidEmails.includes('@noreply.com')
                        ? 'Unreachable email address at `@noreply.com`.'
                        : 'Invalid email address entered.';
                } else {
                    // Clear error if all emails are valid
                    delete this.replyErrors['to'];
                }
            }

            if (this.inboxStore.emailForm.cc) {
                const emails = this.inboxStore.emailForm.cc.split(',').map(email => email.trim());
                const emailRegex = /^[^@]+@\w+(\.\w+)+\w$/;

                // Validate all emails
                const invalidEmail = emails.find(email => !emailRegex.test(email));
                const noreplyEmail = emails.find(email => email.includes('@noreply.com'));

                if (invalidEmail) {
                    this.replyErrors['cc'] = 'Invalid cc email address.';
                } else if (noreplyEmail) {
                    this.replyErrors['cc'] = 'Unreachable email address at `@noreply.com`.';
                } else {
                    delete this.replyErrors['cc']; // Clear error if all emails are valid
                }
            } else {
                delete this.replyErrors['cc'];
            }

            if (this.inboxStore.emailForm.bcc) {
                const emails = this.inboxStore.emailForm.bcc.split(',').map(email => email.trim());
                const emailRegex = /^[^@]+@\w+(\.\w+)+\w$/;

                // Validate all emails
                const invalidEmail = emails.find(email => !emailRegex.test(email));
                const noreplyEmail = emails.find(email => email.includes('@noreply.com'));

                if (invalidEmail) {
                    this.replyErrors['bcc'] = 'Invalid bcc email address.';
                } else if (noreplyEmail) {
                    this.replyErrors['bcc'] = 'Unreachable email address at `@noreply.com`.';
                } else {
                    delete this.replyErrors['bcc']; // Clear error if all emails are valid
                }
            } else {
                delete this.replyErrors['bcc'];
            }

            if(!this.inboxStore.emailForm.subject){
				this.replyErrors['subject'] = 'The subject field is required';
			}

			if(Object.keys(this.replyErrors).length>0){
				return null;
			}

            let temp_email_to = this.inboxStore.emailForm.to;
			this.inboxStore.sendEmail();
            this.inboxStore.listEmails();
            this.inboxStore.emailForm.to = temp_email_to;
            this.inboxStore.emailForm.subject = '';
            this.inboxStore.emailForm.body = '';
            this.inboxStore.emailForm.cc = '';
            this.inboxStore.emailForm.bcc = '';
		},
		sendNewEmail(){

			let _self = this;

			this.errors = [];

            if (!this.inboxStore.emailForm.to) {
                this.replyErrors['to'] = 'The "to" field is required!';
            } else {
                // Split by comma, trim whitespace, and filter out empty strings
                const toEmails = this.inboxStore.emailForm.to.split(',').map(email => email.trim()).filter(email => email);

                // Validate each email address in the list
                const invalidEmails = toEmails.filter(email =>
                    !/^[^@]+@\w+(\.\w+)+\w$/.test(email) || email.includes('@noreply.com')
                );

                if (invalidEmails.length > 0) {
                    this.replyErrors['to'] = invalidEmails.includes('@noreply.com')
                        ? 'Unreachable email address at `@noreply.com`.'
                        : 'Invalid email address entered.';
                } else {
                    // Clear error if all emails are valid
                    this.replyErrors['to'] = '';
                }
            }

            if (this.inboxStore.emailForm.cc) {
                const emails = this.inboxStore.emailForm.cc.split(',').map(email => email.trim());
                const emailRegex = /^[^@]+@\w+(\.\w+)+\w$/;

                // Validate all emails
                const invalidEmail = emails.find(email => !emailRegex.test(email));
                const noreplyEmail = emails.find(email => email.includes('@noreply.com'));

                if (invalidEmail) {
                    this.replyErrors['cc'] = 'Invalid cc email address.';
                } else if (noreplyEmail) {
                    this.replyErrors['cc'] = 'Unreachable email address at `@noreply.com`.';
                } else {
                    delete this.replyErrors['cc']; // Clear error if all emails are valid
                }
            } else {
                delete this.replyErrors['cc'];
            }

            if (this.inboxStore.emailForm.bcc) {
                const emails = this.inboxStore.emailForm.bcc.split(',').map(email => email.trim());
                const emailRegex = /^[^@]+@\w+(\.\w+)+\w$/;

                // Validate all emails
                const invalidEmail = emails.find(email => !emailRegex.test(email));
                const noreplyEmail = emails.find(email => email.includes('@noreply.com'));

                if (invalidEmail) {
                    this.replyErrors['bcc'] = 'Invalid bcc email address.';
                } else if (noreplyEmail) {
                    this.replyErrors['bcc'] = 'Unreachable email address at `@noreply.com`.';
                } else {
                    delete this.replyErrors['bcc']; // Clear error if all emails are valid
                }
            } else {
                delete this.replyErrors['bcc'];
            }

            if(!this.inboxStore.emailForm.subject){
				this.errors['subject'] = 'The subject field is required';
			}

			if(Object.keys(this.errors).length>0){
				return null;
			}

			this.inboxStore.sendEmail().then(function(){
				router.push('/crtx/inbox?view=list&page=email&type='+_self.inboxStore.emailType);
			});
		},
		searchEmails(){
			if(this.searchTermEmail.length>=3){
				this.inboxStore.listEmails(this.searchTermEmail);
			}else if(this.searchTermEmail.length==0){
				this.inboxStore.listEmails();
			}
		},
		expandEmail(id){
			if($('.'+id).hasClass('shrinked-email')){
				$('.'+id).removeClass('shrinked-email');
			}else{
				$('.'+id).addClass('shrinked-email')
			}
		},
		prev(){
			this.inboxStore.pageEmail--;
			this.inboxStore.listEmails(null);
		},
		next(){
			this.inboxStore.pageEmail++;
			this.inboxStore.listEmails(null);
		},
        insertTemplateText(){
            this.inboxStore.emailForm.subject = this.selectedEmailTemplate.subject;
            this.inboxStore.emailForm.body = this.selectedEmailTemplate.body;
        },
        prependTemplateText(){
            this.inboxStore.emailForm.subject = this.selectedEmailTemplate.subject;
            this.inboxStore.emailForm.body = this.selectedEmailTemplate.body + '\n\n' + this.inboxStore.emailForm.body;
        },
        viewTemplate(){
            localStorage.setItem('last_path', '/crtx/manage-practice?page=email-templates');
            window.open('/crtx/manage-practice?page=email-templates', '_blank');
        },
		uploader(editor){
            editor.plugins.get( 'FileRepository' ).createUploadAdapter = ( loader ) => {
                return new UploadAdapter( loader );
            };
        },
        unreadClick(){
            this.inboxStore.listEmails(null, true);
            this.unread_click = true;
        },
        AllEmailsClick(){
            this.inboxStore.listEmails(null,false);
            this.unread_click = false;
        }
    }
}
</script>

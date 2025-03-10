<template>
    <div class="modal fade" id="AddLeadModal" tabindex="-1" aria-labelledby="AddLeadModalLabel" aria-hidden="true">
        <div class="modal-dialog add-lead-model modal-lg modal-dialog-centered">
            <div class="modal-content p-md-3 bg-light-gray">
                <div class="modal-header">
                    <h3 class="modal-title mb-3 mb-lg-0" id="staticBackdropLabel">Add Lead</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <svg width="20" height="14" viewBox="0 0 20 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M19.4411 6.37503H2.04859L7.25172 1.19191C7.49609 0.947534 7.49609 0.551909 7.25172 0.308159C7.00734 0.0637842 6.61172 0.0637842 6.36797 0.308159L0.180469 6.49503C-0.0601562 6.73566 -0.0601562 7.13816 0.180469 7.37878L6.36797 13.5663C6.61234 13.8107 7.00797 13.8107 7.25172 13.5663C7.49609 13.3219 7.49609 12.9263 7.25172 12.6825L2.04859 7.62503H19.4411C19.7861 7.62503 20.0661 7.34503 20.0661 7.00003C20.0661 6.65503 19.7861 6.37503 19.4411 6.37503Z" fill="#514F5F"></path>
                        </svg>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="modal-form">
                        <div v-if="leadStore.errorMessage" class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{leadStore.errorMessage}}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" @click="leadStore.errorMessage = ''"></button>
                        </div>
                        <form @submit.prevent="addLead" id="addLeadForm" autocomplete="off">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Stage</label>
                                        <select class="form-control form-select" :class="{'unfilled' : leadStore.errors.status_id, 'filled':leadStore.lead.status_id}" v-model="leadStore.lead.status_id">
                                            <option selected disabled value="null">Select Stage</option>
                                            <option value="1">New Lead</option>
                                            <option value="5">In Discussion</option>
                                            <option value="2">Attempt 1</option>
                                            <option value="3">Attempt 2</option>
                                            <option value="4">Attempt 3</option>
                                            <option value="17">Nurturing</option>
                                            <option value="6">Practice Follow-Up</option>
                                        </select>
                                        <span class="d-block text-danger mt-1" v-text="leadStore.errors.status_id"></span>
                                    </div>
                                </div>
                                <div class="col-md-4 d-none d-md-block">
                                    <div class="form-group">
                                        <label>Lead Type</label>
                                        <select class="form-control form-select" :class="{'unfilled' : leadStore.errors.phone_form, 'filled':leadStore.lead.phone_form}" v-model="leadStore.lead.phone_form">
                                            <option selected disabled value="null">Select Lead Type</option>
                                            <option value="Web Form">Web Form</option>
                                            <option value="Phone Call">Phone Call</option>
                                        </select>
                                        <span class="d-block text-danger mt-1" v-text="leadStore.errors.phone_form"></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Source</label>
                                        <select class="form-control form-select" :class="{'unfilled' : leadStore.errors.source_id, 'filled':leadStore.lead.source_id}" v-model="leadStore.lead.source_id">
                                            <option selected disabled value="null">Select Source</option>
                                            <option v-for="source in resourceStore.sources" :value="source.id">{{ source.source_name }}</option>
                                        </select>
                                        <span class="d-block text-danger mt-1" v-text="leadStore.errors.source_id"></span>
                                    </div>
                                </div>
                                <div class="col-md-12 d-md-none">
                                    <div class="form-group">
                                        <label>Lead Type</label>
                                        <div class="row popup-check-group check-group">
                                            <div class="col-6">
                                                <div class="btn-checkbox">
                                                    <input type="radio" class="btn-check"  id="PhoneCall" name="leadType" value="Phone Call" v-model="leadStore.lead.phone_form">
                                                    <label class="btn btn-outline-primary" for="PhoneCall">Phone Call</label>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="btn-checkbox">
                                                    <input type="radio" class="btn-check" id="WebForm" name="leadType" value="Web Form" v-model="leadStore.lead.phone_form">
                                                    <label class="btn btn-outline-primary" for="WebForm">Web Form</label>
                                                </div>
                                            </div>
                                        </div>
                                        <span class="d-block text-danger mt-1" v-text="leadStore.errors.phone_form"></span>
                                    </div>
                                </div>
                                <div class="col-md-12 d-md-none">
                                    <div class="form-group">
                                        <label>Quick Note</label>
                                        <input type="text" class="form-control">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>First Name</label>
                                        <input type="text" class="form-control" :class="{'unfilled' : leadStore.errors.first_name, 'filled':leadStore.lead.first_name}" v-model="leadStore.lead.first_name">
                                        <span class="d-block text-danger mt-1" v-text="leadStore.errors.first_name"></span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Last Name</label>
                                        <input type="text" class="form-control" :class="{'unfilled' : leadStore.errors.last_name, 'filled':leadStore.lead.last_name}" v-model="leadStore.lead.last_name">
                                        <span class="d-block text-danger mt-1" v-text="leadStore.errors.last_name"></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group verify-group">
                                        <span class="verified" :class="[leadStore.lead.phone_verified? 'verified' : 'not-verified']" @click="leadStore.lead.phone_verified=!leadStore.lead.phone_verified">
                                            Verified?
                                            <span class="verified-check">
                                                <span class="verified-check-ico">
                                                    <svg version="1.1" x="0px" y="0px" viewBox="0 0 10 10" style="enable-background:new 0 0 10 10;">
                                                        <path d="M3.3,8.6C3.2,8.6,3,8.6,2.9,8.4L0.2,5.7c-0.2-0.2-0.2-0.6,0-0.8s0.6-0.2,0.8,0l2.4,2.4l5.7-5.7c0.2-0.2,0.6-0.2,0.8,0c0.2,0.2,0.2,0.6,0,0.8L3.7,8.4C3.6,8.6,3.5,8.6,3.3,8.6z"></path>
                                                    </svg>
                                                </span>
                                            </span>
                                        </span>
                                        <label>Phone Number</label>
                                        <input type="text" autocomplete="false" class="form-control" :class="{'unfilled' : leadStore.errors.phone, 'filled':leadStore.lead.phone}" id="phone" v-model="leadStore.lead.phone">
                                        <span class="d-block text-danger mt-1" v-text="leadStore.errors.phone"></span>
                                    </div>
                                </div>
<!--                                <div class="col-md-6">
                                    <div class="form-group verify-group">
                                        <span class="verified" :class="[leadStore.lead.email_not_available? 'verified' : 'not-verified']" @click="leadStore.lead.email_not_available=!leadStore.lead.email_not_available">
                                            {{leadStore.lead.email_not_available ? 'Yes' : 'No'}}
                                            <span class="verified-check">
                                                <span class="verified-check-ico">
                                                    <svg version="1.1" x="0px" y="0px" viewBox="0 0 10 10" style="enable-background:new 0 0 10 10;">
                                                        <path d="M3.3,8.6C3.2,8.6,3,8.6,2.9,8.4L0.2,5.7c-0.2-0.2-0.2-0.6,0-0.8s0.6-0.2,0.8,0l2.4,2.4l5.7-5.7c0.2-0.2,0.6-0.2,0.8,0c0.2,0.2,0.2,0.6,0,0.8L3.7,8.4C3.6,8.6,3.5,8.6,3.3,8.6z"></path>
                                                    </svg>
                                                </span>
                                            </span>
                                        </span>
                                        <label>Email not available?</label>
                                    </div>
                                </div>-->
                                <div class="col-md-6">
                                    <div class="form-group verify-group">
                                        <span class="verified" :class="[leadStore.lead.email_not_available? 'verified' : 'not-verified']" @click="leadStore.lead.email_not_available=!leadStore.lead.email_not_available" style="right:120px">
                                             {{!leadStore.lead.email_not_available ? 'Yes' : 'No'}}
                                            <span class="verified-check">
                                                <span class="verified-check-ico">
                                                    <svg version="1.1" x="0px" y="0px" viewBox="0 0 10 10" style="enable-background:new 0 0 10 10;">
                                                        <path d="M3.3,8.6C3.2,8.6,3,8.6,2.9,8.4L0.2,5.7c-0.2-0.2-0.2-0.6,0-0.8s0.6-0.2,0.8,0l2.4,2.4l5.7-5.7c0.2-0.2,0.6-0.2,0.8,0c0.2,0.2,0.2,0.6,0,0.8L3.7,8.4C3.6,8.6,3.5,8.6,3.3,8.6z"></path>
                                                    </svg>
                                                </span>
                                            </span>
                                        </span>
                                        <span v-if="!leadStore.lead.email_not_available" class="verified" :class="[leadStore.lead.email_verified? 'verified' : 'not-verified']" @click="leadStore.lead.email_verified=!leadStore.lead.email_verified">
                                            Verified?
                                            <span class="verified-check">
                                                <span class="verified-check-ico">
                                                    <svg version="1.1" x="0px" y="0px" viewBox="0 0 10 10" style="enable-background:new 0 0 10 10;">
                                                        <path d="M3.3,8.6C3.2,8.6,3,8.6,2.9,8.4L0.2,5.7c-0.2-0.2-0.2-0.6,0-0.8s0.6-0.2,0.8,0l2.4,2.4l5.7-5.7c0.2-0.2,0.6-0.2,0.8,0c0.2,0.2,0.2,0.6,0,0.8L3.7,8.4C3.6,8.6,3.5,8.6,3.3,8.6z"></path>
                                                    </svg>
                                                </span>
                                            </span>
                                        </span>
                                        <label>Email Address</label>
                                        <input v-if="!leadStore.lead.email_not_available" type="email" class="form-control" :class="{'unfilled' : leadStore.errors.email, 'filled':leadStore.lead.email}" v-model="leadStore.lead.email">
                                        <span class="d-block text-danger mt-1" v-text="leadStore.errors.email"></span>
                                    </div>
                                </div>
                                <div class="col-lg-5 col-md-4">
                                    <div class="form-group">
                                        <label>Date of Birth</label>
                                        <input type="text" autocomplete="false" placeholder="MM/DD/YYYY" id="dob" class="datepicker form-control" :class="{'unfilled' : leadStore.errors.dob, 'filled':leadStore.lead.dob}" v-model="leadStore.lead.dob">
                                        <span class="d-block text-danger mt-1" v-text="leadStore.errors.dob"></span>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-8">
                                    <div class="form-group">
                                        <label>City</label>
                                        <input type="text" class="form-control" :class="{'unfilled' : leadStore.errors.city, 'filled':leadStore.lead.city}" v-model="leadStore.lead.city">
                                        <span class="d-block text-danger mt-1" v-text="leadStore.errors.city"></span>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-4">
                                    <div class="form-group">
                                        <label>State</label>
                                        <select class="form-control form-select" v-model="leadStore.lead.state">
                                            <option selected disabled value="null">Select State</option>
                                            <option value="Alabama">Alabama</option>
                                            <option value="Alaska">Alaska</option>
                                            <option value="Arizona">Arizona</option>
                                            <option value="Arkansas">Arkansas</option>
                                            <option value="California">California</option>
                                            <option value="Colorado">Colorado</option>
                                            <option value="Connecticut">Connecticut</option>
                                            <option value="Delaware">Delaware</option>
                                            <option value="District Of Columbia">District Of Columbia</option>
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
                                        <span class="d-block text-danger mt-1" v-text="leadStore.errors.state"></span>
                                    </div>
                                </div>
                                <div class="col-md-12 d-none d-md-block">
                                    <div class="form-group">
                                        <label>Quick Note</label>
                                        <input type="text" class="form-control" :class="{'unfilled' : leadStore.errors.badge, 'filled':leadStore.lead.badge}" v-model="leadStore.lead.badge">
                                        <span class="d-block text-danger mt-1" v-text="leadStore.errors.badge"></span>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Description</label>
                                        <textarea class="form-control" :class="{'unfilled' : leadStore.errors.description, 'filled':leadStore.lead.description}" v-model="leadStore.lead.description"></textarea>
                                        <span class="d-block text-danger mt-1" v-text="leadStore.errors.description"></span>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="text-md-end pop-bottom-btn">
                                        <button type="button" data-bs-dismiss="modal" class="btn btn-primary-outline px-4">Cancel</button>
                                        <button type="submit" class="btn btn-primary px-4">Add Lead</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>

import { useLeadStore } from '../../../stores/lead';
import { useResourceStore } from '../../../stores/resource';

export default {
    setup (){
        const leadStore = useLeadStore();

        const resourceStore = useResourceStore();

        return { leadStore, resourceStore };
    },
    data() {
        return {

        }
    },
    mounted(){

        let _self = this;

        $.datetimepicker.setDateFormatter('moment');

		$('#dob').datetimepicker({
			format: 'MM/DD/YYYY',
			timepicker : false,
			maxDate:'+1970/01/01',
			onSelectDate:function(ct,$i){
				_self.leadStore.lead.dob = $('#dob').val();
			}
		});

        this.maskDobPhone();

        this.resourceStore.getSources();

        this.$nextTick(function () {
           this.maskDobPhone();
        });

        $('#AddLeadModal').on('hidden.bs.modal', function (e) {
            $("#addLeadForm")[0].reset();
        });

       // $( ".datepicker" ).datepicker();
    },
    methods: {
        addLead(){

            this.maskDobPhone();

            this.leadStore.errors = [];

            this.leadStore.errorMessage = '';

            if(!this.leadStore.lead.status_id){
                this.leadStore.errors['status_id'] = 'Status field is required';
            }

            if(!this.leadStore.lead.status_id){
                this.leadStore.errors['phone_form'] = 'Lead Type field is required';
            }

            if(!this.leadStore.lead.source_id){
                this.leadStore.errors['source_id'] = 'Source field is required';
            }

            if(!this.leadStore.lead.first_name){
                 this.leadStore.errors['first_name'] = 'First Name field is required';
            }

            if(!this.leadStore.lead.source_id){
                this.leadStore.errors['last_name'] = 'Last Name field is required';
            }

            if(!this.leadStore.lead.phone){
                this.leadStore.errors['phone'] = 'Phone Number field is required';
            }

            if(!this.leadStore.lead.email_not_available){
                if(!this.leadStore.lead.email){
                    this.leadStore.errors['email'] = 'Email Address field is required';
                }else if (!/^[^@]+@\w+(\.\w+)+\w$/.test(this.leadStore.lead.email)) {
                    this.leadStore.errors['email'] = 'Invalid email address entered.';
                }
            }

            // if(!this.leadStore.lead.state){
            //     this.leadStore.errors['state'] = 'State field is required';
            // }

            // if(!this.leadStore.lead.description){
            //     this.leadStore.errors['description'] = 'Description field is required';
            // }

            if(Object.keys(this.leadStore.errors).length>0){
                return null;
            }

            this.leadStore.add().then((response)=>{
                console.log('modal', response);
                if(this.leadStore.errorMessage==''){
                    this.leadStore.lead = {
                        status_id : null,
                        source_id : null,
                        phone_form : null,
                        first_name : null,
                        last_name : null,
                        phone : null,
                        email : null,
                        dob : null,
                        city : null,
                        description : null,
                        phone_verified : 0,
                        email_verified : 0,
                        badge : null,
                        clinic_id : null
                    };
                    $("#AddLeadModal").modal('hide');
                    this.leadStore.count();
                    this.leadStore.list();
                }
            });
        },
        maskDobPhone(){
            IMask(document.getElementById('phone'), {
                mask: '(000) 000-0000'
            });

            IMask(document.getElementById('dob'), {
                mask: '00/00/0000'
            });
        }
    }
}

</script>

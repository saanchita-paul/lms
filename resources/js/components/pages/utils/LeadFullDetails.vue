<template>
    <div class="modal fade" id="LeadFullDetailsModal" tabindex="-1" aria-labelledby="LeadFullDetailsLabel" aria-hidden="true">
        <div class="modal-dialog add-lead-model modal-lg modal-dialog-centered">
            <div class="modal-content p-md-3 bg-light-gray">
                <div class="modal-header">
                    <h3 class="modal-title" id="staticBackdropLabel">View Profile Details</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <svg width="20" height="14" viewBox="0 0 20 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M19.4411 6.37503H2.04859L7.25172 1.19191C7.49609 0.947534 7.49609 0.551909 7.25172 0.308159C7.00734 0.0637842 6.61172 0.0637842 6.36797 0.308159L0.180469 6.49503C-0.0601562 6.73566 -0.0601562 7.13816 0.180469 7.37878L6.36797 13.5663C6.61234 13.8107 7.00797 13.8107 7.25172 13.5663C7.49609 13.3219 7.49609 12.9263 7.25172 12.6825L2.04859 7.62503H19.4411C19.7861 7.62503 20.0661 7.34503 20.0661 7.00003C20.0661 6.65503 19.7861 6.37503 19.4411 6.37503Z" fill="#514F5F"></path>
                        </svg>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="modal-form">
                       <div id="form-container">
                           <div v-if="patientStore.patient.phone_form == 'Phone Call' && patientStore.callDetails != null && patientStore.callDetails.length>0">
    <!--                           <div class="mb-1 row border-bottom">
                                   <label class="col-sm-3 col-form-label" >Name Score</label>
                                   <div class="col-sm-9 answer">
                                       <p v-text="patientStore.patient.name_score"></p>
                                   </div>
                               </div>
                               <div class="mb-1 row border-bottom">
                                   <label class="col-sm-3 col-form-label" >Phone Score</label>
                                   <div class="col-sm-9 answer">
                                       <p v-text="patientStore.patient.phone_score"></p>
                                   </div>
                               </div>
                               <div class="mb-1 row border-bottom">
                                   <label class="col-sm-3 col-form-label" >Email Score</label>
                                   <div class="col-sm-9 answer">
                                       <p v-text="patientStore.patient.email_score"></p>
                                   </div>
                               </div>
                               <div class="mb-1 row border-bottom">
                                   <label class="col-sm-3 col-form-label" >Total Calls</label>
                                   <div class="col-sm-9 answer">
                                       <p v-text="patientStore.patient.total_calls"></p>
                                   </div>
                               </div>-->
                               <div class="mb-1 row border-bottom">
                                   <label class="col-sm-3 col-form-label" >Source</label>
                                   <div class="col-sm-9 answer">
                                       <p v-text="patientStore.callDetails[0].source"></p>
                                   </div>
                               </div>
                               <div class="mb-1 row border-bottom">
                                   <label class="col-sm-3 col-form-label" >Campaign</label>
                                   <div class="col-sm-9 answer">
                                       <p v-text="patientStore.callDetails[0].campaign"></p>
                                   </div>
                               </div>
                               <div class="mb-1 row border-bottom">
                                   <label class="col-sm-3 col-form-label" >Keywords</label>
                                   <div class="col-sm-9 answer">
                                       <p v-text="patientStore.callDetails[0].keywords"></p>
                                   </div>
                               </div>
                               <div class="mb-1 row border-bottom">
                                   <label class="col-sm-3 col-form-label" >Medium</label>
                                   <div class="col-sm-9 answer">
                                       <p v-text="patientStore.callDetails[0].medium"></p>
                                   </div>
                               </div>
                               <div class="mb-1 row border-bottom">
                                   <label class="col-sm-3 col-form-label" >Landing Page</label>
                                   <div class="col-sm-9 answer">
                                       <p v-text="patientStore.callDetails[0].landing_page_url"></p>
                                   </div>
                               </div>
                               <div class="mb-1 row border-bottom" v-if="patientStore.form_url">
                                   <label class="col-sm-3 col-form-label" >Form URL</label>
                                   <div class="col-sm-9 answer">
                                       <p v-text="patientStore.callDetails[0].form_url"></p>
                                   </div>
                               </div>
                           </div>
                           <div v-if="patientStore.patient.phone_form == 'Web Form'">
                               <!--                           <div class="mb-1 row border-bottom">
                                                              <label class="col-sm-3 col-form-label" >Name Score</label>
                                                              <div class="col-sm-9 answer">
                                                                  <p v-text="patientStore.patient.name_score"></p>
                                                              </div>
                                                          </div>
                                                          <div class="mb-1 row border-bottom">
                                                              <label class="col-sm-3 col-form-label" >Phone Score</label>
                                                              <div class="col-sm-9 answer">
                                                                  <p v-text="patientStore.patient.phone_score"></p>
                                                              </div>
                                                          </div>
                                                          <div class="mb-1 row border-bottom">
                                                              <label class="col-sm-3 col-form-label" >Email Score</label>
                                                              <div class="col-sm-9 answer">
                                                                  <p v-text="patientStore.patient.email_score"></p>
                                                              </div>
                                                          </div>
                                                          <div class="mb-1 row border-bottom">
                                                              <label class="col-sm-3 col-form-label" >Total Calls</label>
                                                              <div class="col-sm-9 answer">
                                                                  <p v-text="patientStore.patient.total_calls"></p>
                                                              </div>
                                                          </div>-->
                               <div class="mb-1 row border-bottom">
                                   <label class="col-sm-3 col-form-label" >Source</label>
                                   <div class="col-sm-9 answer">
                                       <p v-text="patientStore.source"></p>
                                   </div>
                               </div>
                               <div class="mb-1 row border-bottom">
                                   <label class="col-sm-3 col-form-label" >Campaign</label>
                                   <div class="col-sm-9 answer">
                                       <p v-text="patientStore.campaign"></p>
                                   </div>
                               </div>
                               <div class="mb-1 row border-bottom">
                                   <label class="col-sm-3 col-form-label" >Keywords</label>
                                   <div class="col-sm-9 answer">
                                       <p v-text="patientStore.keywords"></p>
                                   </div>
                               </div>
                               <div class="mb-1 row border-bottom">
                                   <label class="col-sm-3 col-form-label" >Medium</label>
                                   <div class="col-sm-9 answer">
                                       <p v-text="patientStore.medium"></p>
                                   </div>
                               </div>
                               <div class="mb-1 row border-bottom">
                                   <label class="col-sm-3 col-form-label" >Landing Page</label>
                                   <div class="col-sm-9 answer">
                                       <p v-text="patientStore.landing_page_url"></p>
                                   </div>
                               </div>
                               <div class="mb-1 row border-bottom" v-if="patientStore.form_url">
                                   <label class="col-sm-3 col-form-label" >Form URL</label>
                                   <div class="col-sm-9 answer">
                                       <p v-text="patientStore.form_url"></p>
                                   </div>
                               </div>
                           </div>
                           <h2 class="pt-4 fs-2 border-bottom pb-3" v-if="patientStore.zip_code">Form Details</h2>
                           <div class="mb-1 row border-bottom" v-if="patientStore.credit_score">
                               <label class="col-sm-4 col-form-label" >Credit Score</label>
                               <div class="col-sm-8 col-form-label answer">
                                   <p v-text="patientStore.credit_score"></p>
                               </div>
                           </div>
                           <div class="mb-1 row border-bottom" v-if="patientStore.dental_condition">
                               <label class="col-sm-4 col-form-label" >Dental Condition</label>
                               <div class="col-sm-8 col-form-label answer">
                                   <p v-text="patientStore.dental_condition"></p>
                               </div>
                           </div>
                           <div class="mb-1 row border-bottom" v-if="patientStore.stopped_you_previously">
                               <label class="col-sm-4 col-form-label" >Stopped You Previously</label>
                               <div class="col-sm-8 col-form-label answer">
                                   <p v-text="patientStore.stopped_you_previously"></p>
                               </div>
                           </div>
                           <span v-if="patientStore.formArray">
                               <div class="mb-1 row border-bottom" v-for="(formItem, index) in patientStore.formArray">
                                   <label class="col-sm-4 col-form-label">{{ formItem[0] }}</label>
                                   <div class="col-sm-8 col-form-label answer">
                                       <p v-text="formItem[1]"></p>
                                   </div>
                               </div>
                           </span>
                       </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>

import { usePatientStore } from '../../../stores/patient';

export default {
    setup (){
        const patientStore = usePatientStore();
        return { patientStore };
    },
}

</script>

<template>
    <div class="modal fade" id="WebFormDetailsModal" tabindex="-1" aria-labelledby="WebFormDetailsLabel" aria-hidden="true">
        <div class="modal-dialog add-lead-model modal-lg modal-dialog-centered">
            <div class="modal-content p-md-3 bg-light-gray">
                <div class="modal-header">
                    <h3 class="modal-title" id="staticBackdropLabel">Form Details</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <svg width="20" height="14" viewBox="0 0 20 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M19.4411 6.37503H2.04859L7.25172 1.19191C7.49609 0.947534 7.49609 0.551909 7.25172 0.308159C7.00734 0.0637842 6.61172 0.0637842 6.36797 0.308159L0.180469 6.49503C-0.0601562 6.73566 -0.0601562 7.13816 0.180469 7.37878L6.36797 13.5663C6.61234 13.8107 7.00797 13.8107 7.25172 13.5663C7.49609 13.3219 7.49609 12.9263 7.25172 12.6825L2.04859 7.62503H19.4411C19.7861 7.62503 20.0661 7.34503 20.0661 7.00003C20.0661 6.65503 19.7861 6.37503 19.4411 6.37503Z" fill="#514F5F"></path>
                        </svg>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="modal-form">
                       <div id="form-container">
                            <div class="mb-1 row border-bottom" v-for="(formItem, index) in patientStore.formArray">
                                <label for="staticEmail" class="col-sm-3 col-form-label">{{ formItem[0] }}</label>
                                <div class="col-sm-9">
                                    <input type="text" readonly class="form-control-plaintext" id="staticEmail" :value="formItem[1]">
                                </div>
                            </div>
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
    }
}

</script>
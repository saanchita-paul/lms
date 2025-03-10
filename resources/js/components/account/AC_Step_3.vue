<template>
    <h2 class="mt-3 mt-md-5 mb-3">Tell Us More About Your Practice</h2>
    <form @submit.prevent="submit">
<!--        <div class="row">
            <div class="d-flex mb-1 form-group">
                <p class="m-0 me-md-3 me-auto">Are there multiple locations?</p>
                <div class="form-check me-3">
                    <input class="form-check-input" type="radio" value="Yes" name="locationsradio" id="multiple_locations_yes" v-model="authStore.form.multiple_locations">
                    <label class="form-check-label" for="multiple_locations_yes">
                        Yes
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" value="No" name="locationsradio" id="multiple_locations_no" v-model="authStore.form.multiple_locations">
                    <label class="form-check-label" for="multiple_locations_no">
                        No
                    </label>
                </div>
            </div>
        </div>-->
        <div class="row">
            <div class="d-flex my-3 form-group">
                <p class="m-0 me-md-3 me-auto">Is the practice located in Canada?</p>
                <div class="form-check me-3">
                    <input class="form-check-input" type="radio" value="Yes" name="canadaradio" id="canada_yes" v-model="authStore.form.is_location_canada">
                    <label class="form-check-label" for="canada_yes">
                        Yes
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" value="No" name="canadaradio" id="canada_no" v-model="authStore.form.is_location_canada">
                    <label class="form-check-label" for="canada_no">
                        No
                    </label>
                </div>
            </div>
        </div>
<!--        <div class="row" v-if="authStore.form.multiple_locations=='Yes'">
            <div class="col-md-8">
                <a role="button" class="btn btn-outline-primary me-2 float-end" @click="addRow">Add Location</a>
            </div>
        </div>-->
        <div class="row gx-3 my-3">
            <div class="col-md-8 col-lg-8">
                <p class="mb-2">Practice Address</p>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <input  type="text" name="office_name" class="form-control" :class="{/*'unfilled' : errors.address,*/ 'filled':authStore.form.office_name}" placeholder="" v-model="authStore.form.office_name">
                            <label>Office Name</label>
                            <!--                            <span class="d-block text-danger mt-1" v-text="errors.address"></span>-->
                        </div>
                        <div class="form-group">
                            <input  type="text" name="address" class="form-control" :class="{/*'unfilled' : errors.address,*/ 'filled':authStore.form.address}" placeholder="" v-model="authStore.form.address">
                            <label>Street Address</label>
<!--                            <span class="d-block text-danger mt-1" v-text="errors.address"></span>-->
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <input  type="text" name="town" class="form-control" :class="{/*'unfilled' : errors.town,*/ 'filled':authStore.form.town}" placeholder="" v-model="authStore.form.town">
                            <label>City</label>
<!--                            <span class="d-block text-danger mt-1" v-text="errors.town"></span>-->
                        </div><!--/.form-group-->
                    </div>
                    <div class="col-md-4 col-6">
                        <div class="form-group">
                            <select v-if="authStore.form.is_location_canada == 'No'" class="form-control form-select" :class="{/*'unfilled' : errors.state,*/ 'filled':authStore.form.state}" aria-label="Default select example" v-model="authStore.form.state">
                                <option selected value=""></option>
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
                            <select v-else class="form-control form-select" :class="{/*'unfilled' : errors.state,*/ 'filled':authStore.form.state}" aria-label="Default select example" v-model="authStore.form.state">
                                <option selected value=""></option>
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
                            <label>{{ authStore.form.is_location_canada == 'Yes' ? 'Province' : 'State'  }}</label>
<!--                            <span class="d-block text-danger mt-1" v-text="errors.state"></span>-->
                        </div><!--/.form-group-->
                    </div>
                    <div class="col-md-4 col-6">
                        <div class="form-group">
                            <input  type="text" name="zip" class="form-control" maxlength="5" minlength="5" :class="{/*'unfilled' : errors.zip_code,*/ 'filled':authStore.form.zip_code}" placeholder="" v-model="authStore.form.zip_code">
                            <label>{{ authStore.form.is_location_canada == 'Yes' ? 'Postal Code' : 'Zip Code'  }}</label>
<!--                            <span class="d-block text-danger mt-1" v-text="errors.zip_code"></span>-->
                        </div><!--/.form-group-->
                    </div>
                </div>
                <p class="mb-2">Office Details</p>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <input  type="text" name="Phone" id="phone" class="form-control" :class="{/*'unfilled' : errors.phone,*/ 'filled':authStore.form.phone}" placeholder="" v-model="authStore.form.phone">
                            <label>Phone Number</label>
<!--                            <span class="d-block text-danger mt-1" v-text="errors.phone"></span>-->
                        </div><!--/.form-group-->
                    </div>
<!--                    <div class="col-md-6">
                        <div class="form-group">
                            <textarea  class="form-control" :class="{/*'unfilled' : errors.office_hours,*/ 'filled':authStore.form.office_hours}" name="OfficeHours"  placeholder="" v-model="authStore.form.office_hours"></textarea >
                            <label>Office Hours</label>
&lt;!&ndash;                            <span class="d-block text-danger mt-1" v-text="errors.office_hours"></span>&ndash;&gt;
                        </div>&lt;!&ndash;/.form-group&ndash;&gt;
                    </div>-->
                </div>
            </div>
        </div>
<!--        <div class="row gx-3 my-3" v-for="(location, index) in authStore.form.multiple_location_details">
            <div class="col-md-8">
                <a role="button" class="btn btn-outline-primary me-2 float-end" @click="removeRow(index)">Remove Location</a>
            </div>
            <div class="col-md-8 col-lg-8">
                <p class="mb-2">Practice Address</p>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <input required type="text" name="office_name" class="form-control" :class="{'filled':location.office_name}" placeholder="" v-model="location.office_name">
                            <label>Office Name</label>
                            &lt;!&ndash;                            <span class="d-block text-danger mt-1" v-if="locationErrors[index].street_address" v-text="locationErrors[index].street_address"></span>&ndash;&gt;
                        </div>&lt;!&ndash;/.form-group&ndash;&gt;
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <input required type="text" name="address" class="form-control" :class="{'filled':location.street_address}" placeholder="" v-model="location.street_address">
                            <label>Street Address</label>
&lt;!&ndash;                            <span class="d-block text-danger mt-1" v-if="locationErrors[index].street_address" v-text="locationErrors[index].street_address"></span>&ndash;&gt;
                        </div>&lt;!&ndash;/.form-group&ndash;&gt;
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <input required type="text" name="town" class="form-control" :class="{'filled':location.city}" placeholder="" v-model="location.city">
                            <label>City</label>
&lt;!&ndash;                            <span class="d-block text-danger mt-1" v-text="locationErrors[index].city"></span>&ndash;&gt;
                        </div>&lt;!&ndash;/.form-group&ndash;&gt;
                    </div>
                    <div class="col-md-4 col-6">
                        <div class="form-group">
                            <select v-if="authStore.form.is_location_canada=='No'" required class="form-control form-select" :class="{'filled':location.state}" aria-label="Default select example" v-model="location.state">
                                <option selected value=""></option>
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
                            <select v-else required class="form-control form-select" :class="{'filled':location.state}" aria-label="Default select example" v-model="location.state">
                                <option selected value=""></option>
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
                            <label>{{ authStore.form.is_location_canada == 'Yes' ? 'Province' : 'State'  }}</label>
&lt;!&ndash;                            <span class="d-block text-danger mt-1" v-text="locationErrors[index].state"></span>&ndash;&gt;
                        </div>&lt;!&ndash;/.form-group&ndash;&gt;
                    </div>
                    <div class="col-md-4 col-6">
                        <div class="form-group">
                            <input required type="text" name="zip" class="form-control" maxlength="5" minlength="5" :class="{'filled':location.zip_code}" placeholder="" v-model="location.zip_code">
                            <label>{{ authStore.form.is_location_canada == 'Yes' ? 'Postal Code' : 'Zip Code'  }}</label>
&lt;!&ndash;                            <span class="d-block text-danger mt-1" v-text="locationErrors[index].zip_code"></span>&ndash;&gt;
                        </div>&lt;!&ndash;/.form-group&ndash;&gt;
                    </div>
                </div>
                <p class="mb-2">Office Details</p>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <input required type="text" name="Phone" id="phone" class="form-control" :class="{'filled':location.phone}" placeholder="" v-model="location.phone">
                            <label>Phone Number</label>
&lt;!&ndash;                            <span class="d-block text-danger mt-1" v-text="locationErrors[index].phone"></span>&ndash;&gt;
                        </div>&lt;!&ndash;/.form-group&ndash;&gt;
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <textarea required class="form-control" :class="{'filled':location.office_hours}" name="OfficeHours"  placeholder="" v-model="location.office_hours"></textarea >
                            <label>Office Hours</label>
&lt;!&ndash;                            <span class="d-block text-danger mt-1" v-text="locationErrors[index].office_hours"></span>&ndash;&gt;
                        </div>&lt;!&ndash;/.form-group&ndash;&gt;
                    </div>
                </div>
            </div>
        </div>-->
        <div class="bottom-btn-box">
            <a class="btn btn-outline-primary me-2" @click="back">Go Back</a>
            <button type="submit" class="btn btn-primary submit-btn">Continue</button>
        </div><!--/.form-group-->
    </form>
</template>
<script>
import router from '../../routes'
import { useAuthStore } from '../../stores/auth';

export default {
    setup (){
        const authStore = useAuthStore();

        return { authStore };
    },
    data() {
        return {
           /* errors: [],*/
        }
    },
    mounted(){
        // Get all input elements with class="CLASS_NAME"
        const inputs = document.querySelectorAll('input.form-control, textarea.form-control, select.form-control')

        // Iterate over elements and add each one of them a `focus` listener
        inputs.forEach(input => {
            input.addEventListener('change', this.inputFilled)
        });

        IMask(document.getElementById('phone'), {
            mask: '(000) 000-0000'
        });
    },
    methods: {
        submit() {

            let _self = this;

           /* const Regex = /^(?:(?:\+|0{0,2})91(\s*[\-]\s*)?|[0]?)?[6789]\d{9}$/;

            this.errors = [];*/

          /*  if(!this.authStore.form.address){
                this.errors['address'] = 'Practice Address field is required.';
            }

            if(!this.authStore.form.town){
                this.errors['town'] = 'City field is required.';
            }

            if(!this.authStore.form.state){
                this.errors['state'] = 'State field is required.';
            }

            if(!this.authStore.form.zip_code){
                this.errors['zip_code'] = 'Zip Code field is required.';
            }else if(this.authStore.form.zip_code.length != 5){
                this.errors['zip_code'] = 'Zip Code field must be of 5 digits.';
            }

            if(!this.authStore.form.phone){
                this.errors['phone'] = 'Phone field is required.';
            }

            if(!this.authStore.form.office_hours){
                this.errors['office_hours'] = 'Office Hours field is required.';
            }

            if(!this.authStore.form.multiple_locations){
                this.errors['multiple_locations'] = 'Multiple Location field is required.';
            }*/

          /*  if(Object.keys(this.errors).length>0){
                return null;
            }*/

            this.$emit('form-submitted', this.form);

            router.push('/crtx/account/step-4');
        },
        back(){
            this.$emit('step-back');
            router.push('/crtx/account/step-2');
        },
        inputFilled(event){
            event.target.classList.add("filled");
        },
        acceptNumber() {
            var x = this.authStore.form.phone.replace(/\D/g, '').match(/(\d{0,3})(\d{0,3})(\d{0,4})/);
            this.authStore.form.phone = !x[2] ? x[1] : '(' + x[1] + ') ' + x[2] + (x[3] ? '-' + x[3] : '');
        },
        addRow(){
          this.authStore.form.multiple_location_details.push({"office_name":"", "street_address":"","city":"","state":"","zip_code":"", "phone":"", "office_hours":""});
        },
        removeRow(index){
            this.authStore.form.multiple_location_details.splice(index, 1)
        }
    },
}
</script>

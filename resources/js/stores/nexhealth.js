import { defineStore } from "pinia";
import axios from "axios";
import router from "../routes";
import { useAuthStore } from "./auth";
import { useAlertStore } from './alert';
import { forEach } from "lodash";

export const useNexhealthStore = defineStore({
    id: "nexhealth",
    state: () => ({
        appointmentTypes:[],
        profileUpdated: false,
        noteSaved: false,
        noteDeleted: false,
        errors: [],
        patient: {},
        authStore: useAuthStore(),
        alertStore: useAlertStore(),
        selectedId: null,
        newNote: "",
        formData: "",
        formArray: [],
        trustfullDetails: "",
        trustfullDetailsArray: [],
        callrailDetails: "",
        callrailDetailsArray: [],
        conversationalTranscript: [],
        scoreStatus: 'LOW',
        phoneScoreStatus: 'LOW',
        nameScoreStatus: 'LOW',
        emailScoreStatus: 'LOW',
        priorCalls:null,
        possibleSpam:null,
        deliverableStatus: null,
        dataBreaches:null
    }),
    getters: {
        //doubleCount: (state) => state.counter * 2
    },
    actions: {
        getConfig() {
            return {
                headers: {
                    headers: {
                        Accept: "application/json",
                        Authorization:
                            this.authStore.token_type +
                            " " +
                            this.authStore.token,
                        ContentType: "application/x-www-form-urlencoded"
                    },
                },
            };
        },
        // getAppoinmentTypes() {
        //     let _self = this;
        //     const config = this.getConfig();
        //     axios
        //         .get("https://nexhealth.info/appointment_types?subdomain=sierra-dental-care&location_id=21179", config)
        //         .then(function (response) {
        //             if (response.data.code) {
        //                 _self.appointmentTypes = response.data.templates;
        //             } else {
        //                 console.log(_self.errors);
        //             }
        //         })
        //         .catch(function (error) {
        //             console.log("error", error);
        //         });
        // },
    },
});

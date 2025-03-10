import { defineStore } from "pinia";
import axios from "axios";
import router from "../routes";
import { useAuthStore } from "../stores/auth";
import {useAlertStore} from "./alert";


export const useReportStore = defineStore({
    id: "report",
    state: () => ({
        authStore: useAuthStore(),
        alertStore: useAlertStore(),
        emailStatistics: null,
        emailStatisticsDateRangeStart: moment().subtract(29, 'days'),
        emailStatisticsDateRangeEnd: moment(),
        emailStatisticsChart:null,
        emailStatisticTotalEmail:0,
        emailStatisticsDataset: [{name:"Submitted", visible:true},
            {name: "Delivered", visible:true},
            {name:"Opened", visible:true},
            {name:"Clicked", visible:true},
            {name: "Unsubscribed", visible:true},
            {name: "Bounced", visible:true},
            {name:"Complaints", visible:true},
            {name: "Suppressed", visible:true}],
        reports: [],
        page: 1,
        perPage: 10,
        fromto: "",
        type: "",
        query: "",
        source_id: 0,
        source: "Select Media",
        status_id: 0,
        status: "Select Stage",
        tab: "New Leads",
        last_page: 0,
        total_item: 0,
        total_lifetimevalue: 0,
        total_amounts: 0,
        chart: null,
        sort_by: "",
        sort_order: "desc",
        hover_on:"",
        format: "",
        categories: [
            { name: "First Name", checked: true },
            { name: "Last Name", checked: true },
            { name: "Lead Type", checked: true },
            { name: "Source", checked: true },
            { name: "Stage", checked: true },
            { name: "Email", checked: true },
            { name: "Phone", checked: true },
            { name: "Treatment Amount", checked: true },
            { name: "Reason", checked: false },
            { name: "City", checked: false },
            { name: "Zip Code", checked: false },
            { name: "Life Time Value", checked: false },
            { name: "Scheduled For", checked: false },
            { name: "No Showed Date", checked: false },
            { name: "When Booked", checked: false },
            { name: "Created On", checked: false },
            { name: "Lead Score", checked: false },
            { name: "Tags", checked: false },
            { name: "Landing Page", checked: false },
        ],
        savedTags: [], // Declaring savedTags property
        selectedTagName : "Select Tag",
        tagId : 0,
        savedTypes:['Web Form', 'Phone Call'],
        selectedType: null,
        selectedReason:null,
        savedReasons:[{label:"Price shopping"}, {label:"Office is too far"}, {label:"Medicaid or Medicare patient"}, {label:"Too expensive/couldn't afford it"}, {label:"Call disconnected, hung up"},
            {label:"Current Patient"}, {label:"Spanish Speaking"}, {label:"Insurance / Senior Grants"}, {label:"No Credit Card"}, {label:"Duplicate Lead"}, {label:"STOP"},
            {label:"General Dentistry"}, {label:"Does not provide service"}, {label:"No Reason"}, {label:"Think about it"}, {label:"Stated Not Interested"}, {label:"Credit Challenged"}, {label:"Refuse to give CC"},
            {label:"No cosigner"}, {label:"Veneers/Braces/Cosmetic"}, {label:"Language Barrier"}, {label:"Spam"}, {label:"Wrong Number"}, {label:"Hung Up"}, {label:"No Way to Contact"}, {label:"Fax Number"},
            {label:"Vendor"}, {label:"Another Dentist Office"}, {label:"Robo Call"}, {label:"False Advertising"}, {label:"Senior Grants"}, {label:"Insurance"}, {label:"No Appointment Available"}, {label:"Other"}],
    }),
    getters: {},
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
                    },
                },
            };
        },
        async list() {

            let _self = this;

            const config = this.getConfig();

            let arr = this.fromto.split("-");

            let tabs = this.tab.toLowerCase().split(" ").join("_");

            if (this.type === 'lifetimevalue' || tabs === 'treatments_sold_(pms)') {
                this.categories = this.categories.map(category => {
                    if (category.name === 'Life Time Value') {
                        return { ...category, checked: true };
                    }
                    return category;
                });
            }
            axios
                .post(
                    "/api/v1/report",
                    {
                        start_date: moment(arr[0]).format('DD/MM/YYYY'),
                        end_date: moment(arr[1]).format('DD/MM/YYYY'),
                        page_no: this.page,
                        numberOfRecord: _self.perPage,
                        search: this.query,
                        status_id: this.status_id,
                        tabs: tabs,
                        source_id: this.source_id,
                        sort_order:this.sort_order,
                        sort_by:this.sort_by,
                        clinic_ids:this.authStore.clinic_id,
                        tagId:this.tagId,
                        type:this.type,
                        formType:this.selectedType,
                        reason:this.selectedReason != null ? this.selectedReason.label : null
                    },
                    config
                )
                .then(function (response) {
                    if (response.data.success) {
                        _self.reports = response.data.getReport;
                        _self.last_page = response.data.total_pages;
                        _self.total_item = response.data.total_item;
                        _self.total_lifetimevalue = response.data.total_lifetimevalue;
                        _self.total_amounts = response.data.total_amounts;
                        _self.chart = response.data.chart;
                        // console.log(_self.chart);
                    }
                })
                .catch(function (error) {
                    console.log("error", error);
                });
        },
        async export() {
            try {
                let _self = this;
                const config = this.getConfig();

                let arr = this.fromto.split("-");

                let tabs = this.tab.toLowerCase().split(" ").join("_");

                let tagId = this.tagId;

                const response = await axios.get(
                    "/api/v1/report-export",
                    {
                        params: {
                            start_date: moment(arr[0]).format('DD/MM/YYYY'),
                            end_date: moment(arr[1]).format('DD/MM/YYYY'),
                            search: this.query,
                            status_id: this.status_id,
                            tagId:tagId,
                            tabs: tabs,
                            source_id: this.source_id,
                            format: this.format,
                            clinic_id: this.authStore.clinic_id,
                            formType: this.selectedType,
                            reason:this.selectedReason != null ? this.selectedReason.label : null
                        },
                    },
                    config
                ); // Replace with your API endpoint

                if (response.data.success) {
                    if (this.format == "csv") {
                        this.downloadCsv(response.data.data);
                    } else if (this.format == "pdf") {
                        // console.log(response.data.data);
                        const { jsPDF } = window.jspdf;
                        const doc = new jsPDF();
                        const clean = DOMPurify.sanitize(response.data.data);
                        doc.html(clean, {
                            callback: function (doc) {
                                doc.save("report_"+ moment(new Date()).format('MMDDYYYYHHMM') +".pdf");
                            },
                            x: 10,
                            y: 10,
                        });
                    } else if(this.format == "xlsx"){
                        this.exportToExcel(response.data.data);
                    }
                }
            } catch (error) {
                console.error("Error exporting data:", error);
            }

            this.format = "";
        },
        downloadCsv(data) {
            const csvContent = this.convertToCsv(data);
            const blob = new Blob([csvContent], { type: "text/csv" });
            const url = URL.createObjectURL(blob);
            const a = document.createElement("a");
            a.href = url;
            a.download = "report_"+ moment(new Date()).format('MMDDYYYYHHMM') +".csv";
            a.click();
            URL.revokeObjectURL(url);
        },
        convertToCsv(data) {
            const csvRows = [];
            // Assuming your data is an array of objects with properties that need to be exported
            data.forEach((item) => {
                const row = Object.values(item).join(",");
                csvRows.push(row);
            });
            //console.log(csvRows);
            return csvRows.join("\n");
        },
        exportToExcel(data) {

            /* make the worksheet */
            var ws = XLSX.utils.json_to_sheet(data);
            /* add to workbook */
            var wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, ws, "Leads");

            /* generate an XLSX file */
            XLSX.writeFile(wb, "report_"+ moment(new Date()).format('MMDDYYYYHHMM') +".xlsx");
        },
        getTags() {
            const self = this;
            const clinicId = this.authStore.clinic_id;
            const savedTags = [];
            const config = {
                headers: {
                    Accept: 'application/json',
                    Authorization: `${this.authStore.token_type} ${this.authStore.token}`
                }
            };
            // Make a request to your backend API to fetch tags based on clinic_id
            axios.get(`/api/v1/tags?clinic_id=${clinicId}`, config)
                .then(function(response) {
                    if (response.data.success) {
                        self.savedTags = response.data.clinic_tags;
                    }
                })
                .catch(function(error) {
                    console.error('Error fetching tags:', error);
                });
        },
        getEmailStatistics(){
            const _self = this;
            const config = this.getConfig();

            axios
                .post(
                    "/api/v1/email-statistics",
                    {
                        clinic_id:this.authStore.clinic_id,
                        from:moment(this.emailStatisticsDateRangeStart).format("YYYY-MM-DD[T]HH:mm:ss"),
                        to:moment(this.emailStatisticsDateRangeEnd).format("YYYY-MM-DD[T]HH:mm:ss")
                    },
                    config
                )
                .then(function (response) {
                    if (response.data.success) {
                        _self.emailStatistics = response.data.data;
                        _self.emailStatisticsTotalEmail =  _self.emailStatistics.logStatusSummary.EmailTotal;
                        _self.emailStatisticsChart = response.data.data.chartData;
                    }else{
                        _self.alertStore.success = false;
                        _self.alertStore.message = response.data.message;
                    }
                })
                .catch(function (error) {
                    console.log("error", error);
                });
        }
    },
});

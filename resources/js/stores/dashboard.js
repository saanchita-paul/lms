import {defineStore} from 'pinia'
import axios from 'axios'
import router from '../routes'
import {useAuthStore} from '../stores/auth';

export const useDashboardStore = defineStore({
    id: 'dashboard',
    state: () => ({
        authStore: useAuthStore(),
        kpiTreatmentSoldAllTime: 0,
        kpiTreatmentSoldCurrentMonth: 0,
        kpiTreatmentSoldCurrentYear: 0,
        kpiLifeTimeValueSum: 0,
        treatmentSoldChart: [],
        totalLeadsChart: [],
        leadsBySourceChart: [],
        nurturingData: [],
        nurturingDateRange: 13,
        customMessage: null,
        salesSummaryDateRangeStart: moment().subtract(29, 'days'),
        salesSummaryDateRangeEnd: moment(),
        marketingDateRangeStart: moment().subtract(29, 'days'),
        marketingDateRangeEnd: moment(),
        nurturingDateRangeStart: moment().subtract(29, 'days'),
        nurturingDateRangeEnd: moment(),
        leadsBySourceDateRange: 13,
        salesSummary: null,
        zohomarketingdashboard: null,
        recentLeads: [],
        recentLeadsPage:1,
        recentLeadsLastPage:1,
        recentLeadsFollowUpCount:0,
        recentLeadsScheduledCount:0,
        views:{clinicStats:false, salesSummary:false, patientPipeline:false, trends:false, marketingSource:false, nurturing:false, leadActivity:true, inbox:true},
        perPage: 10,
        query: '',
        status_id: 1,
        sort_by: 'Created At',
        sort_order: 'desc',
        hover_on : '',
        currentTab:'follow-ups',
        categories: [
            {name: 'Full Name', checked: true},
            {name: 'Email', checked: true},
            {name: 'Source',checked: true},
            {name: 'Lead Type', checked: true},
            {name: 'Phone', checked: true},
            {name: 'Date of Birth',checked: false},
            {name: 'Created At', checked: true},
            {name: 'Lead Score', checked: true},
            {name: 'Tags', checked: false},
            { name: "Landing Page", checked: false }
        ]
    }),
    getters: {},
    actions: {
        getConfig() {
            return {
                headers: {
                    headers: {
                        Accept: 'application/json',
                        Authorization: this.authStore.token_type + ' ' + this.authStore.token
                    }
                },
            }
        },
        getKPITreatmentsSold() {
            let _self = this;
            const config = this.getConfig();

            axios.post('/api/v1/my-dashboard', {clinic_ids: this.authStore.clinic_id}, config)
                .then(function (response) {
                    if (response.data.success) {
                        _self.kpiTreatmentSoldAllTime = response.data.crmCustomerAllTime;
                        _self.kpiTreatmentSoldCurrentMonth = response.data.crmCustomerCurrentMonth;
                        _self.kpiLifeTimeValueSum = response.data.crmCustomerLifeTimeValueSum;
                        _self.kpiTreatmentSoldCurrentYear = response.data.crmCustomerCurrentYear;
                        _self.treatmentSoldChart = response.data.chart_treatment_sold;
                        _self.totalLeadsChart = response.data.chart_treatment_all;
                        _self.customMessage = response.data.customMessage;
                    }
                })
                .catch(function (error) {
                    console.log('error', error);
                });
        },
        getKPISalesSummary(start, end) {
            let _self = this;
            const config = this.getConfig();

            axios.post('/api/v1/sales-summary', {
                start_date: start,
                end_date: end,
                clinic_ids: this.authStore.clinic_id
            }, config)
                .then(function (response) {
                    if (response.data.success) {
                        _self.salesSummary = response.data.data;
                    }
                })
                .catch(function (error) {
                    console.log('error', error);
                });
        },
        getMarketingLeadsBySource() {
            let _self = this;
            const config = this.getConfig();

            axios.post('/api/v1/leads-by-sources', {
                start_date: this.marketingDateRangeStart.format('YYYY-MM-DD'),
                end_date: this.marketingDateRangeEnd.format('YYYY-MM-DD'),
                clinic_ids: this.authStore.clinic_id
            }, config)
                .then(function (response) {
                    if (response.data.success) {
                        _self.leadsBySourceChart = response.data.data;
                        _self.zohomarketingdashboard = response.data.data.zohomarketingdashboard;
                    }
                })
                .catch(function (error) {
                    console.log('error', error);
                });
        },
        getNurturing() {
            let _self = this;
            const config = this.getConfig();

            axios.post('/api/v1/dashboard-nurturing', {
                start_date: this.nurturingDateRangeStart.format('YYYY-MM-DD'),
                end_date: this.nurturingDateRangeEnd.format('YYYY-MM-DD'),
                clinic_ids: this.authStore.clinic_id
            }, config)
                .then(function (response) {
                    if (response.data.success) {
                        _self.nurturingData = response.data.data;
                    }
                })
                .catch(function (error) {
                    console.log('error', error);
                });
        },
        getRecentLeadsCount(){
            let _self = this;

            const config = this.getConfig();

            this.recentLeads = [];

            axios.post('/api/v1/recent-leads-count', {
                clinic_id: this.authStore.clinic_id,
            }, config).then(function(response) {
                if(response.data.success){
                    _self.recentLeadsFollowUpCount = response.data.followUpCount;
                    _self.recentLeadsScheduledCount = response.data.scheduledCount;
                }
            }).catch(function(error) {
                console.log('error', error);
            })
        },
        getRecentLeads(){
            let _self = this;

            const config = this.getConfig();

            this.recentLeads = [];

            axios.post('/api/v1/recent-leads', {
                statusId:this.currentTab === 'follow-ups' ? 6 : 12,
                currentPage:this.recentLeadsPage,
                clinic_id: this.authStore.clinic_id,
                query: this.query,
                perPage: this.perPage,
                sortBy: this.sort_by,
                sort_order: this.sort_order,
            }, config).then(function(response) {
                if(response.data.success){
                    _self.recentLeads = response.data.data;
                    _self.recentLeadsPage = response.data.meta.current_page;
                    _self.recentLeadsLastPage = response.data.meta.last_page;
                }
            }).catch(function(error) {
                console.log('error', error);
            })
        }
    }
});

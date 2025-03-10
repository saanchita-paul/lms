<template>
    <div id="main"  class="bg-light-gray">
        <div class="container-fluid p-0">
            <div class="px-0 py-0 mt-4">
                <div class="dashboard-tab">
                    <div class="tab-content" id="myTabContent">
                        <div id="KPIs">
                            <div class="dashboard-box">
                                <div class="dashboard-box-title d-sm-flex align-items-center">
                                    <h3>Email Statistics</h3>
                                    <div class="align-items-center d-flex dashboard-select ms-auto position-relative">
                                        <div class="mt-2 select-date-dropdown">
                                            <div class="dropdown">
                                                <div id="reportrange"
                                                     style="color: white;background: #355ADD; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                                                    <i class="fa fa-calendar"></i>&nbsp;
                                                    <span></span> <i class="fa fa-caret-down"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <a href="#" class="btn-sm filter-ico ms-2 mt-2 tooltip-ico" data-title="Dataset Filter">
                                            <svg width="18" height="18" viewBox="0 0 18 18" xmlns="http://www.w3.org/2000/svg" style="fill: var(--bs-primary);">
                                                <path
                                                    d="M7.675 18V12.375H9.175V14.45H18V15.95H9.175V18H7.675ZM0 15.95V14.45H6.175V15.95H0ZM4.675 11.8V9.75H0V8.25H4.675V6.15H6.175V11.8H4.675ZM7.675 9.75V8.25H18V9.75H7.675ZM11.825 5.625V0H13.325V2.05H18V3.55H13.325V5.625H11.825ZM0 3.55V2.05H10.325V3.55H0Z"/>
                                            </svg>
                                        </a>
                                        <div class="lead-filterBy-box">
                                            <div class="lead-filterBy-top d-flex align-items-center">
                                                <div class="filterBy-back">
                                                    <a href="#" class="filterBy-back-btn">
                                                        <svg width="20" height="14" viewBox="0 0 20 14" fill="none"
                                                             xmlns="http://www.w3.org/2000/svg">
                                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                                  d="M19.4411 6.37503H2.04859L7.25172 1.19191C7.49609 0.947534 7.49609 0.351909 7.25172 0.308159C7.00734 0.0637842 6.61172 0.0637842 6.36797 0.308159L0.180469 6.49503C-0.0601562 6.73566 -0.0601562 7.13816 0.180469 7.37878L6.36797 13.5663C6.61234 13.8107 7.00797 13.8107 7.25172 13.5663C7.49609 13.3219 7.49609 12.9263 7.25172 12.6825L2.04859 7.62503H19.4411C19.7861 7.62503 20.0661 7.34503 20.0661 7.00003C20.0661 6.65503 19.7861 6.37503 19.4411 6.37503Z"
                                                                  fill="#514F5F"></path>
                                                        </svg>
                                                    </a>
                                                </div>
                                                <div class="filterBy-title mx-lg-auto">
                                                    <h5>Filters</h5>
                                                </div>
                                            </div>
                                            <div class="filterBy-cate-list">
                                                <span v-for="dataset in reportStore.emailStatisticsDataset">{{ dataset.name }}</span>
                                            </div>
                                            <div class="lead-filterBy-middle">
                                                <div class="form-check" v-for="dataset in reportStore.emailStatisticsDataset">
                                                    <input class="form-check-input" type="checkbox" :id="dataset.name"
                                                           v-model="dataset.visible" >
                                                    <label class="form-check-label" :for="dataset.name">{{ dataset.name }}</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row justify-content-center">
                                    <div class="col-md-11">
                                        <div id="chartContainer">
                                            <canvas id="myChart"></canvas>
                                        </div>
                                        <div id="myChart-legend"></div>
                                    </div>
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
import { useAuthStore } from '../../stores/auth';
import { useSupportStore } from '../../stores/support';
import { useClinicStore } from '../../stores/clinic';
import {useReportStore} from "../../stores/report";
import {storeToRefs} from "pinia";
import {watch} from "vue";

export default {
    setup (){
        const authStore = useAuthStore();

        const reportStore = useReportStore();

        return {authStore, reportStore};
    },
    data() {
        return {
            showDatasetFilter: false,
        }
    },
    mounted(){
        const _self = this;

        let ranges = {
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 14 Days': [moment().subtract(14, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'Last 3 Month': [moment().subtract(3, 'month'), moment()],
            'Last 6 Months': [moment().subtract(6, 'month'), moment()],
            'Last Year': [moment().subtract(1, 'year'), moment()],
            'All Time': ['01/01/2000', moment().endOf('year')],
        };

        function cb(start, end) {
            $('#reportrange span').html(start.format('D MMM YYYY') + ' - ' + end.format('D MMM YYYY'));
        }

        $('#reportrange').daterangepicker({
            startDate: this.reportStore.emailStatisticsDateRangeStart,
            endDate: this.reportStore.emailStatisticsDateRangeEnd,
            locale: {
                format: 'MM/DD/YYYY'
            },
            ranges: ranges
        }, cb);

        $('#reportrange').on('apply.daterangepicker', function (ev, picker) {
            _self.reportStore.emailStatisticsDateRangeStart = picker.startDate;
            _self.reportStore.emailStatisticsDateRangeEnd = picker.endDate;
            cb(_self.reportStore.emailStatisticsDateRangeStart, _self.reportStore.emailStatisticsDateRangeEnd);
            _self.getEmailStatistics();
        });

        cb(this.reportStore.emailStatisticsDateRangeStart, this.reportStore.emailStatisticsDateRangeEnd);

        this.getEmailStatistics();

        const { emailStatisticsChart, emailStatisticsDataset } = storeToRefs(this.reportStore);

        watch(
            () => emailStatisticsChart,
            function(){
                _self.updateChart()
            },
            { deep: true }
        );

        watch(
            () => emailStatisticsDataset,
            function(){
                _self.updateChart()
            },
            { deep: true }
        );

        $('.filter-ico').click(function () {
            $('.filter-icons .lead-filterBy-box').not($(this).next('.lead-filterBy-box')).slideUp(200);
            $(this).next('.lead-filterBy-box').slideToggle(200);
            $(this).parents('.filter-icons').toggleClass('filter-open');
            $(this).parents('#wrapper').toggleClass('sub-popup-active');
            return false;
        });
        $('.lead-filterBy-box').on('click touchstart', function (event) {
            event.stopPropagation();
        });
        $(document).on('click touchstart', function (e) {
            if ($(e.target).parent('.lead-filterBy-box').length === 0) {
                $('.lead-filterBy-box').slideUp(200);
                $('.filter-icons').removeClass('filter-open');
            }
        });
        $('.filterBy-back-btn, .filterBy-save-btn').click(function () {
            $('.lead-filterBy-box').slideUp(200);
            $('.filter-icons').removeClass('filter-open');
            return false;
        });
    },
    methods: {
        getEmailStatistics(){
            this.reportStore.getEmailStatistics();
        },
        updateChart(){
            let _self = this;

            if(_self.reportStore.emailStatistics) {

                let ctx = null;
                let myChart = null;

                $('#myChart').remove();

                $('#chartContainer').append('<canvas id="myChart"></canvas>')

                ctx = document.getElementById('myChart');
                myChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: _self.reportStore.emailStatisticsChart['Dates'],
                        datasets: [
                            {
                                data: _self.reportStore.emailStatisticsChart['Summary']['Submitted'],
                                label: 'Submitted',
                                backgroundColor: _self.reportStore.emailStatisticsDataset[0].visible ? "rgb(144, 205, 244, 1)"  : "rgb(144, 205, 244, 0.3)",
                                borderColor : "rgb(144, 205, 244, 1)",
                                strokeStyle : _self.reportStore.emailStatisticsDataset[0].visible ? "rgb(144, 205, 244, 1)"  : "rgb(144, 205, 244, 0.3)",
                                borderWidth:1,
                                hidden: !_self.reportStore.emailStatisticsDataset[0].visible,
                                order: 8
                            },
                            {
                                data: _self.reportStore.emailStatisticsChart['Summary']['Delivered'],
                                label: 'Delivered',
                                backgroundColor: _self.reportStore.emailStatisticsDataset[1].visible ? "rgb(84, 87, 255, 1)" : "rgb(84, 87, 255, 0.3)",
                                borderColor : "rgb(84, 87, 255, 1)",
                                strokeStyle: _self.reportStore.emailStatisticsDataset[1].visible ? "rgb(84, 87, 255, 1)" : "rgb(84, 87, 255, 0.3)",
                                borderWidth:1,
                                hidden: !_self.reportStore.emailStatisticsDataset[1].visible,
                                order: 7
                            },
                            {
                                data: _self.reportStore.emailStatisticsChart['Summary']['Opened'],
                                label: 'Opened',
                                backgroundColor: _self.reportStore.emailStatisticsDataset[2].visible ? "rgb(49, 151, 149, 1)" : "rgb(49, 151, 149, 0.3)",
                                borderColor : "rgb(49, 151, 149, 1)",
                                strokeStyle: _self.reportStore.emailStatisticsDataset[2].visible ? "rgb(49, 151, 149, 1)" : "rgb(49, 151, 149, 0.3)",
                                borderWidth:1,
                                hidden: !_self.reportStore.emailStatisticsDataset[2].visible,
                                order: 6
                            },
                            {
                                data: _self.reportStore.emailStatisticsChart['Summary']['Clicked'],
                                label: 'Clicked',
                                backgroundColor: _self.reportStore.emailStatisticsDataset[3].visible ? "rgb(79, 209, 197, 1)" : "rgb(79, 209, 197, 0.3)",
                                borderColor : "rgb(79, 209, 197, 1)",
                                strokeStyle: _self.reportStore.emailStatisticsDataset[3].visible ? "rgb(79, 209, 197, 1)" : "rgb(79, 209, 197, 0.3)",
                                borderWidth:1,
                                hidden: !_self.reportStore.emailStatisticsDataset[3].visible,
                                order: 5
                            },
                            {
                                data: _self.reportStore.emailStatisticsChart['Summary']['Unsubscribed'],
                                label: 'Unsubscribed',
                                backgroundColor: _self.reportStore.emailStatisticsDataset[4].visible ? "rgb(128, 90, 213, 1)" : "rgb(128, 90, 213, 0.3)",
                                borderColor : "rgb(128, 90, 213, 1)",
                                strokeStyle: _self.reportStore.emailStatisticsDataset[4].visible ? "rgb(128, 90, 213, 1)" : "rgb(128, 90, 213, 0.3)",
                                borderWidth:1,
                                hidden: !_self.reportStore.emailStatisticsDataset[4].visible,
                                order: 4
                            },
                            {
                                data: _self.reportStore.emailStatisticsChart['Summary']['Bounced'],
                                label: 'Bounced',
                                backgroundColor: _self.reportStore.emailStatisticsDataset[5].visible ? "rgb(248, 103, 100, 1)" : "rgb(248, 103, 100, 0.3)",
                                borderColor : "rgb(248, 103, 100, 1)",
                                strokeStyle: _self.reportStore.emailStatisticsDataset[5].visible ? "rgb(248, 103, 100, 1)" : "rgb(248, 103, 100, 0.3)",
                                borderWidth:1,
                                hidden: !_self.reportStore.emailStatisticsDataset[5].visible,
                                order: 3
                            },
                            {
                                data: _self.reportStore.emailStatisticsChart['Summary']['Complaints'],
                                label: 'Complaints',
                                backgroundColor: _self.reportStore.emailStatisticsDataset[6].visible ? "rgb(113, 128, 150, 1)" : "rgb(113, 128, 150, 0.3)",
                                borderColor : "rgb(113, 128, 150, 1)",
                                strokeStyle: _self.reportStore.emailStatisticsDataset[6].visible ? "rgb(113, 128, 150, 1)" : "rgb(113, 128, 150, 0.3)",
                                borderWidth:1,
                                hidden: !_self.reportStore.emailStatisticsDataset[6].visible,
                                order: 2
                            },
                            {
                                data: _self.reportStore.emailStatisticsChart['Summary']['Suppressed'],
                                label: 'Suppressed',
                                backgroundColor: _self.reportStore.emailStatisticsDataset[7].visible ? "rgb(203, 213, 224, 1)" : "rgb(203, 213, 224, 0.3)",
                                borderColor : "rgb(203, 213, 224, 1)",
                                strokeStyle: _self.reportStore.emailStatisticsDataset[7].visible ? "rgb(203, 213, 224, 1)" : "rgb(203, 213, 224, 0.3)",
                                borderWidth:1,
                                hidden: !_self.reportStore.emailStatisticsDataset[7].visible,
                                order: 1
                            },
                        ]
                    },
                    options: {
                        title: {
                            display: true,
                            text: 'Email Statistics Chart',
                        },
                        plugins: {
                            tooltip: {
                                mode: 'index',
                                intersect: false
                            },
                            legend: {
                                display: true,
                                position: 'bottom',
                                padding: 20,
                                fullSize:true,
                                labels: {
                                    boxWidth: 12,
                                    boxHeight: 12,
                                    font:{
                                        size:10,
                                    },
                                    generateLabels: (chart) => {
                                        const datasets = chart.data.datasets;
                                        return datasets.map((data, i) => ({
                                            text: data.label + " " + data.data.reduce((partialSum, a) => partialSum + a, 0) + " (" + _self.getPercentStats(data.data.reduce((partialSum, a) => partialSum + a, 0)) + "%)",
                                            fillStyle: data.backgroundColor,
                                            strokeStyle:data.strokeStyle,
                                            index: i,
                                            fontColor: _self.reportStore.emailStatisticsDataset[i].visible ? 'rgba(102, 102, 102, 1)' : 'rgba(102, 102, 102, 0.3)',
                                        }))
                                    }
                                },
                                onClick: (e, legendItem, legend) => {
                                    _self.reportStore.emailStatisticsDataset[legendItem.index].visible = !_self.reportStore.emailStatisticsDataset[legendItem.index].visible;
                                }
                            },
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                            },
                            x: {
                                ticks: {
                                    maxTicksLimit: 12
                                },
                                grid:{
                                    display:false
                                }
                            }
                        }
                    },
                });
            }
        },
        getPercentStats(stat){
           let per = ((stat*100)/this.reportStore.emailStatisticsTotalEmail).toFixed(0);

           if(!isNaN(per)){
               return per;
           }

           return '0';
        },
    }
}

</script>

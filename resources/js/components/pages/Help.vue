<template>
    <div id="main"  class="bg-light-gray reports">
        <div class="container-fluid p-0">
            <div class="px-0 py-0 mt-4">
				<div class="report-box">
					<div class="report-top-box">
						<h4 class=" mb-2">How can we help?</h4>
                        <p>Schedule an Appointment with CRTX Support: <a :href="supportCalendar" target="_blank">Support Calendar</a></p>
                        <p>Schedule an Appointment about Marketing Results: <a :href="strategyCalendar" target="_blank">Strategy Calendar</a></p>
                        <p>Many questions can be answered by the Agent below</p>
                    </div>
                    <help-chatbot></help-chatbot>
                </div>

            </div>
        </div>
    </div>
</template>
<script>

import HelpChatbot from "./HelpChatbot.vue";

export default {
    components: {
        HelpChatbot,
    },
    data() {
        return {
            supportCalendar: process.env.MIX_VUE_APP_SUPPORT_CALENDAR,
            strategyCalendar: process.env.MIX_VUE_APP_STRATEGY_CALENDAR
        }
    },
    mounted(){
        console.log("Support Calendar:", this.supportCalendar);
        console.log("Strategy Calendar:", this.strategyCalendar);
    },
    methods: {

    }
}

</script>

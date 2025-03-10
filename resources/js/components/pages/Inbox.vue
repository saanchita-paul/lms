<template>

    <Sms v-if="page=='sms'"></Sms>
    <Email v-if="page=='email'"></Email>

</template>
<script>
import Email from './Email.vue';
import Sms from './Sms.vue';

export default {
    setup (){
        
    },
    components : {Sms, Email},
    data() {
        return {
			page:''
        }
    },
    mounted(){
        this.page = this.$route.query.page
    }
}
</script>
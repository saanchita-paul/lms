import { defineStore } from 'pinia'
import axios from 'axios';
import router from '../routes'
import { useAuthStore } from '../stores/auth';

export const useInboxStore = defineStore({
  id: 'inbox',
  state: () => ({
    authStore: useAuthStore(),
    clinic_id:localStorage.getItem('clinic_id'),
    searchedLeads: [],

    smsList: [],
    chatList: [],
    leftList: [],
    lead_name: '',
    totalSMS: 0,
    totalUnread : 0,
    textSent:false,
    textSentFailed:false,
    newSmsCount: 0,
    page : 1,
    last_page : 0,

    emailSent:false,
    showReply:false,
    totalEmails:0,
    totalReceivedEmails:0,
    totalSentEmails:0,
    totalReceivedEmailsUnread:0,
    totalReceivedEmailsUnreadCount:0,
    totalSentEmailsUnread:0,
    emailList: [],
    selectedEmails: null,
    leftEmailList:[],
    firstEmail:false,
    lastEmail:true,
    pageEmail:1,
    pageSize:50,
    emailType:'received',
    emailAddress:'',
    inboxEmail:'',
    emailForm:{
      inbox_id:'',
      to:'',
      cc:'',
      bcc:'',
      subject:'',
      body:'',
      created_at:''
    }
  }),
  getters: {

  },
  actions: {
    getConfig(){
        return  {
          headers: {
            headers: {
              Accept: 'application/json',
              Authorization: this.authStore.token_type + ' ' + this.authStore.token
            }
          },
        }
    },
    listSMS(leadId, loading = true,unread = false){
        let _self = this;
        const config = this.getConfig();

        config.params = {loading:loading};

        axios.post('/api/v1/inboxchat', {page:(!leadId)? this.page : 1, clinic_id:this.clinic_id, leadId:leadId,unread:unread}, config)
        .then(function (response) {
           // console.log(response);
            if(response.data.success){
              if(leadId){
                _self.lead_name = response.data.data.firstNames[0] + ' ' + response.data.data.lastNames[0];
                _self.chatList  = response.data.data.crmcustomer;
              }else{
                _self.smsList  = response.data.data.crmcustomer.data;
                _self.totalSMS = response.data.totalChats;
                _self.totalUnread = response.data.Unread;
                _self.last_page = response.data.data.crmcustomer.last_page;
                _self.getNewSmsCount();
              }
            }
        })
        .catch(function (error) {
            console.log('error', error);
        });
    },
    sendChat(leadId, chat)
    {
      let _self = this;
      const config = this.getConfig();

      axios.post('/api/v1/storeSms', {lead_id:leadId, user_id:this.authStore.user.id, chat:chat}, config)
      .then(function (response) {
          if(response.data.success){
            _self.listSMS(leadId);
            _self.listSMS();
            _self.getNewSmsCount();
            _self.textSent = true;

            setTimeout(function(){
              _self.textSent = false;
            }, 5000);
          }else{
            _self.textSentFailed = true;

            setTimeout(function(){
              _self.textSentFailed = false;
            }, 5000)
          }
      })
      .catch(function (error) {
          console.log('error', error);

          _self.textSentFailed = true;

          setTimeout(function(){
            _self.textSentFailed = false;
          }, 5000)
      });
    },
    searchLeads(searchTerm){
      let _self = this;
      const config = this.getConfig();

      axios.post('/api/v1/getcontact', {searchTerm:searchTerm, clinic_id:this.clinic_id}, config)
      .then(function (response) {
          //console.log(response);
          if(response.status == 200){
            _self.searchedLeads = response.data.results;
          }else{
            _self.searchedLeads = [];
          }
      })
      .catch(function (error) {
          console.log('error', error);
      });
    },
    sendNewSms(leadId, message){
      let _self = this;
      const config = this.getConfig();

      axios.post('/api/v1/storeSms', {lead_id:leadId, user_id:this.authStore.user.id, chat:message}, config)
      .then(function (response) {
          //console.log(response);
          if(response.data.success){

            _self.listSMS(leadId);
            _self.listSMS();
            _self.getNewSmsCount();

            _self.textSent = true;

            setTimeout(function(){
              _self.textSent = false;
            }, 5000);
          }else{
            _self.textSentFailed = true;

            setTimeout(function(){
              _self.textSentFailed = false;
            }, 5000)
          }
      })
      .catch(function (error) {
          console.log('error', error);
          _self.textSentFailed = true;

          setTimeout(function(){
            _self.textSentFailed = false;
          }, 5000)
      });
    },
    searchSMS(searchTerm){
      let _self = this;
      const config = this.getConfig();

      axios.post('/api/v1/inboxchat', {clinic_id:this.clinic_id, searchTerm:searchTerm}, config)
      .then(function (response) {
          //console.log(response);
          if(response.data.success){
            _self.smsList  = response.data.data.crmcustomer.data;
          }
      })
      .catch(function (error) {
          console.log('error', error);
      });
    },
    getNewSmsCount(loading = true){
      let _self = this;
      const config = this.getConfig();

      config.params = {loading:loading};

      axios.post('/api/v1/getmessagecount', {clinic_id:this.authStore.clinic_id, inbox_id:this.authStore.inbox_id, loading:loading}, config)
      .then(function (response) {
          //console.log(response);
          if(response.data.success){
            _self.newSmsCount  = response.data.count;
            _self.totalReceivedEmailsUnread = response.data.email_unread_count;
          }
      })
      .catch(function (error) {
          console.log('error', error);
      });
    },

    //----------Email----------------

    listEmails(searchTerm='',unread = false){
      let _self = this;
      const config = this.getConfig();

      // if(_self.emailType=='received')
      // _self.totalReceivedEmailsUnread = 0;
      // else
      // _self.totalSentEmailsUnread = 0;

      this.emailList = [];

      axios.post(this.emailType=='received'?'/api/v1/received-email-list':'/api/v1/sent-email-list', {page:this.pageEmail, size:50, search_keyword:searchTerm, inbox:this.authStore.inbox_id,unread:unread}, config)
      .then(function (response) {

          if(response.data.success){
            _self.emailList  = response.data.data.received_email_list;
             _self.totalEmails = (response.data.data.total_email_count < 0) ? 0 : response.data.data.total_email_count;
             _self.firstEmail = response.data.data.first;
             _self.lastEmail = response.data.data.last;
             _self.inboxEmail = response.data.data.emailAddress;
             _self.totalReceivedEmailsUnreadCount = response.data.data.unreadCount;
          }
      })
      .catch(function (error) {
          console.log('error', error);
      });
    },
    showEmail(){
      let _self = this;
      const config = this.getConfig();

      axios.post(this.emailType=='received'?'/api/v1/email-thread':'/api/v1/sent-email-thread', {inbox:this.authStore.inbox_id, email_id:this.emailAddress}, config)
      .then(function (response) {
          if(response.data.success){
            _self.selectedEmails  = response.data.data.email_list;
              let emailString = _self.selectedEmails[0].data.from;
              let email = emailString.match(/<([^>]+)>/)[1];
              _self.emailForm.to = email;
            _self.emailForm.subject = _self.selectedEmails[0].data.subject;
            _self.emailForm.created_at = _self.selectedEmails[0].data.created_at;
          }
      })
      .catch(function (error) {
          console.log('error', error);
      });
    },
    async sendEmail(){
      let _self = this;
      const config = this.getConfig();

      this.emailForm.inbox_id = this.authStore.inbox_id;

      axios.post('/api/v1/send-email', this.emailForm, config)
      .then(function (response) {
          if(response.data.success){
            _self.emailSent = true;
            setTimeout(function(){
              _self.emailSent = false;
            }, 5000);
            _self.showReply = false;
            // Object.keys(_self.emailForm).forEach(key => {
            //   delete _self.emailForm[key];
            // });
            _self.listEmails();
          }
      })
      .catch(function (error) {
          console.log('error', error);
      });
    },

    async readSendEmail(email){
          let _self = this;
          const config = this.getConfig();
          let clinic_id = _self.clinic_id;
          this.emailForm.from_email = email;
          this.emailForm.clinic_id = clinic_id;

          axios.post('/api/v1/read-received-email', this.emailForm, config)
              .then(function (response) {
                  if(response.data.success){
                  }
              })
              .catch(function (error) {
                  console.log('error', error);
              });
      },
  }
});

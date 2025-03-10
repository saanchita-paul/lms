import { defineStore } from 'pinia'
import axios from 'axios'
import { useAuthStore } from '../stores/auth';
import { useAlertStore } from './alert';

export const useTagStore = defineStore({
  id: 'tag',
  state: () => ({
    savedTags : [],
    autocompleteTags : [],
    authStore: useAuthStore(),
    alertStore: useAlertStore(),
    searchQuery: '',
  }),
  actions: {
    getTags(clinicId,leadId) {
        const self = this;
        const config = {
            headers: {
                Accept: 'application/json',
                Authorization: `${this.authStore.token_type} ${this.authStore.token}`
            }
        };    
        // Make a request to your backend API to fetch tags based on clinic_id
        axios.get(`/api/v1/tags?clinic_id=${clinicId}&lead_id=${leadId}`, config)
            .then(function(response) {
                if (response.data.success) {
                    self.savedTags  = response.data.tags;
                }
            })
            .catch(function(error) {
                console.error('Error fetching tags:', error);
            });
    },
    saveTag(TagName, clinic_id, leadId = null) {
      const _self = this;
      const config = {
        headers: {
          Accept: 'application/json',
          Authorization: `${this.authStore.token_type} ${this.authStore.token}`
        }
      };
      const formData = {
        tagName: TagName,
        clinic_id: clinic_id,
        lead_id: leadId
      };
      axios.post('/api/v1/tags', formData, config)
        .then(function (response) {
            console.log(response.data);
            if (response.data.success) {
            // Optionally, update the local tags list after successful creation
            _self.alertStore.success = response.data.success;
            _self.alertStore.message = response.data.message;
            _self.getTags(clinic_id,leadId);
          }
        })
        .catch(function (error) {
          console.log('Error saving tag:', error);
        });
    },
    deleteTag(tagId,leadId) {
        const _self = this;
        const config = {
            headers: {
                Accept: 'application/json',
                Authorization: `${this.authStore.token_type} ${this.authStore.token}`
            }
        };    
        // Make a request to your backend API to delete the tag
        axios.delete(`/api/v1/tags/${tagId}/${leadId}`, config)
            .then(response => {
                if (response.data.message) {
                    // If the tag is successfully deleted, remove it from the savedTags array
                    this.savedTags = this.savedTags.filter(tag => tag.id !== tagId);
                    _self.alertStore.success = response.data.success;
                    _self.alertStore.message = response.data.message;
                    console.log('Tag deleted successfully.');
                } else {
                    console.error('Error deleting tag:', response.data.message);
                }
            })
            .catch(error => {
                console.error('Error deleting tag:', error);
            });
    },
    async getAutocompleteTags(clinic_id, searchquery) {
      const self = this;
      const config = {
        headers: {
          Accept: 'application/json',
          Authorization: `${this.authStore.token_type} ${this.authStore.token}`
        }
      };

      const requestData = {
        clinic_id: clinic_id,
        search_query: searchquery
      };

      // Make a POST request to your backend API to fetch tags based on clinic_id and lead_id
      axios.post('/api/v1/autocomplete', requestData, config)
        .then(function(response) {          
          if (response.data.success) {
            self.autocompleteTags = response.data.tags;            
           }
           else {
            self.autocompleteTags = []; // Clear autocomplete tags if no tags found
           }
        })
        .catch(function(error) {
          console.error('Error fetching tags:', error);
        });
    },    
  }
});
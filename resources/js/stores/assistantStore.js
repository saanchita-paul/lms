import { defineStore } from 'pinia';
import axios from 'axios';

export const useAssistantStore = defineStore('assistant', {
  actions: {
    async createAssistant(clinic_id) {
      try {
        const response = await axios.post('/api/v1/assistants/create', { clinic_id });
        return response.data;
      } catch (error) {
        console.error('Failed to create assistant:', error);
        return null;
      }
    }
  }
});

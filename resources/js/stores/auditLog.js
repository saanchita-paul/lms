import { defineStore } from 'pinia';
import axios from 'axios';
import { useAuthStore } from './auth';

export const useAuditLogStore = defineStore({
  id: 'auditLog',
  state: () => ({
    auditLogs: [],
    uniqueLatestFields: [],
    authStore: useAuthStore(),
  }),
  actions: {
    async fetchAuditLogs(subject_id) {
      const config = {
        headers: {
          Accept: 'application/json',
          Authorization: `${this.authStore.token_type} ${this.authStore.token}`,
        },
      };

      try {
        const response = await axios.get(`/api/v1/audit-logs/field-updates/${subject_id}`, config);
        if (response.data) {
          this.auditLogs = response.data;          
        }
      } catch (error) {
        console.error('Error fetching audit logs:', error);
      }
    },
  },
});
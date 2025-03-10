import { defineStore } from 'pinia'
import { useAuthStore } from '../stores/auth';

export const useAlertStore = defineStore({
  id: 'alert',
  state: () => ({
    success:false,
    message:''
  }),
});
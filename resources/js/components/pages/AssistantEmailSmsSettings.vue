<template>
    <div class="card p-4 auto-reply-toggles mt-4 mb-4">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" id="autoReplyEmail" v-model="autoReplyEmail" @change="updateAutoReply('email')">
            <label class="form-check-label" for="autoReplyEmail">Auto Reply for Email</label>
            <div class="settings-item mt-4">
                <label><strong>Email Instructions:</strong></label>
                <p v-if="!isEditingEmail">{{ emailInstructions }}</p>
                <textarea rows="10"
                          v-if="isEditingEmail"
                          v-model="emailInstructions"
                          placeholder="Edit email instructions"
                ></textarea>
                <button
                    class="btn btn-primary"
                    v-if="!isEditingEmail"
                    @click="editInstruction('email')"
                >
                    Edit
                </button>
                <button
                    class="btn btn-primary pt-2"
                    v-if="isEditingEmail"
                    @click="saveInstruction('email')"
                >
                    Update
                </button>
                <button
                    class="btn btn-secondary pt-2"
                    v-if="isEditingEmail"
                    @click="cancelEdit('email')"
                >
                    Cancel
                </button>
            </div>
        </div>
        <div class="form-check form-switch mt-2">
            <input class="form-check-input" type="checkbox" id="autoReplySMS" v-model="autoReplySMS" @change="updateAutoReply('sms')">
            <label class="form-check-label" for="autoReplySMS">Auto Reply for SMS</label>
            <div class="settings-item mt-4">
                <label><strong>SMS Instructions:</strong></label>
                <p v-if="!isEditingSMS">{{ smsInstructions }}</p>
                <textarea rows="10"
                          v-if="isEditingSMS"
                          v-model="smsInstructions"
                          placeholder="Edit sms instructions"
                ></textarea>
                <button
                    class="btn btn-primary"
                    v-if="!isEditingSMS"
                    @click="editInstruction('sms')"
                >
                    Edit
                </button>
                <button
                    class="btn btn-primary pt-2"
                    v-if="isEditingSMS"
                    @click="saveInstruction('sms')"
                >
                    Update
                </button>
                <button
                    class="btn btn-secondary pt-2"
                    v-if="isEditingSMS"
                    @click="cancelEdit('sms')"
                >
                    Cancel
                </button>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";
import Swal from "sweetalert2";

export default {
    data() {
        return {
            assistantId: localStorage.getItem('assistant_id') || null,
            autoReplyEmail: false,
            autoReplySMS: false,
            instructions: null,
            smsInstructions: "",
            emailInstructions: "",
            originalSMSInstructions: "",
            originalEmailInstructions: "",
            isEditingSMS: false,
            isEditingEmail: false,
        };
    },
    mounted() {
        this.getAutoReply();
        this.fetchInstructions();
    },
    methods: {
        async updateAutoReply(type) {
            const clinicId = localStorage.getItem('clinic_id');
            const payload = {
                auto_reply_email: this.autoReplyEmail,
                auto_reply_sms: this.autoReplySMS,
            };

            try {
                const response = await fetch(`/api/v1/clinics/${clinicId}/auto-reply`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(payload),
                });

                if (response.ok) {
                    const result = await response.json();
                    this.autoReplyEmail = result.data.auto_reply_email;
                    this.autoReplySMS = result.data.auto_reply_sms;
                } else {
                    const error = await response.json();
                    console.error('Failed to update Auto Reply:', error.message);
                }
            } catch (error) {
                console.error(`Error updating Auto Reply for ${type}:`, error);
            }
        },
        async getAutoReply(){
            const clinicId = localStorage.getItem('clinic_id');
            const response = await fetch(`/api/v1/clinics/${clinicId}/auto-reply`, {
                method: 'GET',
            });

            if (response.ok) {
                const result = await response.json();
                this.autoReplyEmail = result.data.auto_reply_email === 1;
                this.autoReplySMS = result.data.auto_reply_sms === 1;
            } else {
                const error = await response.json();
                console.error('Failed to update Auto Reply:', error.message);
            }
        },
        async fetchInstructions() {
            this.loading = true;
            this.error = null;

            try {
                const clinicId = localStorage.getItem('clinic_id');
                const response = await axios.get(`/api/v1/clinics/${clinicId}/instructions`);

                this.instructions = response.data;
                this.smsInstructions = this.instructions.data.sms_instructions;
                this.emailInstructions = this.instructions.data.email_instructions;
                this.originalSMSInstructions = this.smsInstructions;
                this.originalEmailInstructions = this.emailInstructions;
            } catch (err) {
                this.error = "Failed to fetch instructions. Please try again.";
                console.error(err);
            } finally {
                this.loading = false;
            }
        },
        editInstruction(type) {
            if (type === "sms") {
                this.isEditingSMS = true;
            } else if (type === "email") {
                this.isEditingEmail = true;
            }
        },
        cancelEdit(type) {
            if (type === "sms") {
                this.smsInstructions = this.originalSMSInstructions;
                this.isEditingSMS = false;
            } else if (type === "email") {
                this.emailInstructions = this.originalEmailInstructions;
                this.isEditingEmail = false;
            }
        },
        async saveInstruction(type) {
            try {
                const payload = {
                    sms_instructions: this.smsInstructions,
                    email_instructions: this.emailInstructions,
                };
                if (type === "sms") {
                    payload.sms = this.smsInstructions;
                } else if (type === "email") {
                    payload.email = this.emailInstructions;
                }

                const clinicId = localStorage.getItem('clinic_id');
                await axios.put(`/api/v1/clinics/${clinicId}/instructions`, payload);

                if (type === "sms") {
                    this.originalSMSInstructions = this.smsInstructions;
                    this.isEditingSMS = false;
                } else if (type === "email") {
                    this.originalEmailInstructions = this.emailInstructions;
                    this.isEditingEmail = false;
                }
                await this.fetchInstructions();
                Swal.fire({
                    title: "Success",
                    text: `${type.toUpperCase()} instructions updated successfully!`,
                    icon: "success",
                    confirmButtonText: "OK",
                });
            } catch (err) {
                console.error(`Failed to save ${type} instructions:`, err);
                Swal.fire({
                    title: "Error",
                    text: "Failed to save instructions. Please try again.",
                    icon: "error",
                    confirmButtonText: "OK",
                });
            }
        },

    }
};

</script>

<style scoped>
.settings-item {
    margin-bottom: 15px;
}

.settings-item label {
    display: block;
    font-weight: bold;
    margin-bottom: 5px;
}

.settings-item p, .settings-item input, .settings-item textarea {
    font-size: 16px;
    width: 100%;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
}

.settings-item textarea {
    height: auto;
    resize: vertical;
}

.form-check .form-check-input {
    opacity: 1;
}
</style>

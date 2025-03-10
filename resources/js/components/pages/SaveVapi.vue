<template>
    <div class="container mt-5">
        <h2 class="mb-4">Save Voice Agent Assistant & Phone ID</h2>
        <form @submit.prevent="saveOrUpdate">
            <div>
                <label>Assistant ID:</label>
                <input v-model="vapi_assistant_id" type="text" required />
            </div>
            <div>
                <label>Phone Number ID:</label>
                <input v-model="vapi_phone_number_id" type="text" required />
            </div>
            <button type="submit" :disabled="loading" class="btn btn-primary mt-4">
                {{ isUpdating ? "Update" : "Save" }}
            </button>
        </form>
    </div>
</template>

<script>
import axios from "axios";
import Swal from "sweetalert2";

export default {
    name: 'SaveVapi',
    data() {
        return {
            vapi_assistant_id: "",
            vapi_phone_number_id: "",
            clinic_id: localStorage.getItem("clinic_id"),
            isUpdating: false,
            loading: false,
        };
    },
    async created() {
        try {
            const response = await axios.get(`/api/v1/voice_agent/clinic/${this.clinic_id}`);
            if (response.data) {
                this.vapi_assistant_id = response.data.vapi_assistant_id;
                this.vapi_phone_number_id = response.data.vapi_phone_number_id;
                this.isUpdating = true;
            }
        } catch (error) {
            console.error("No existing data found, proceeding with save.");
        }
    },
    methods: {
        async saveOrUpdate() {
            this.loading = true;
            const payload = {
                vapi_assistant_id: this.vapi_assistant_id,
                vapi_phone_number_id: this.vapi_phone_number_id,
            };

            try {
                await axios.post(`/api/v1/voice_agent/clinic/${this.clinic_id}`, payload);
                Swal.fire({
                    icon: "success",
                    title: this.isUpdating ? "Updated Successfully!" : "Saved Successfully!",
                    showConfirmButton: true,
                    confirmButtonText: 'OK'
                });
                this.isUpdating = true;
            } catch (error) {
                console.error("Error:", error);
                alert("Failed to save/update.");
            } finally {
                this.loading = false;
            }
        },
    },
};
</script>

<style scoped>
.container {
    margin: auto;
    padding: 20px;
    border: 1px solid #ddd;
    border-radius: 5px;
    background: #fff;
}

label {
    display: block;
    margin: 10px 0 5px;
}

input {
    width: 100%;
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 4px;
}
</style>

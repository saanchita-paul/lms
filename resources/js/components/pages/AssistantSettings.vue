<template>
    <div class="settings-container">
        <div class="settings-card">
            <div class="settings-item">
                <label><strong>Assistant Name:</strong></label>
                <p v-if="!isEditing">{{ assistantData.name }}</p>
                <input type="text" v-if="isEditing" v-model="assistantData.name" placeholder="Edit assistant name" />
            </div>
            <div class="settings-item">
                <label><strong>Instructions:</strong></label>
                <p v-if="!isEditing">{{ assistantData.instructions }}</p>
                <textarea v-if="isEditing" rows="10" v-model="assistantData.instructions" placeholder="Edit instructions"></textarea>
            </div>
            <button class="btn btn-primary" v-if="!isEditing" @click="enableEditMode">
                Edit
            </button>
            <button v-if="isEditing" class="btn btn-primary" @click="saveChanges" :disabled="isSaving">
                <span v-if="isSaving">Updating...<i class="fa fa-spinner fa-spin"></i></span>
                <span v-else>Update</span>
            </button>
            <button v-if="isEditing" class="btn btn-secondary" @click="cancelEdit">
                Cancel
            </button>
        </div>
    </div>
</template>

<script>
import axios from "axios";
import Swal from "sweetalert2";

export default {
    data() {
        return {
            assistantData: {},
            assistantId: '',
            isSaving: false,
            isEditing: false,
        };
    },
    created() {
        this.assistantId = localStorage.getItem('assistant_id');
        this.fetchAssistantData();
    },
    methods: {
        fetchAssistantData() {
            const clinicId = localStorage.getItem('clinic_id');

            if (!clinicId) {
                console.error('No clinic_id found in localStorage');
                this.loading = false;
                return;
            }
            axios.get(`/api/v1/assistant/${clinicId}`)
                .then(response => {
                    this.assistantData = response.data;
                    this.loading = false;
                })
                .catch(error => {
                    console.error('Error fetching assistant:', error);
                    this.loading = false;
                });
        },
        enableEditMode() {
            this.isEditing = true;
            this.originalInstructions = this.assistantData.instructions;
        },
        cancelEdit() {
            this.isEditing = false;
            this.assistantData.instructions = this.originalInstructions;
        },
        async saveChanges() {
            this.isSaving = true;
            try {
                const response = await axios.post(`/api/v1/assistant/${this.assistantId}`, {
                    instructions: this.assistantData.instructions,
                    name: this.assistantData.name,
                });
                console.log('Assistant settings updated successfully', response.data);
                this.isSaving = false;
                this.isEditing = false;
                Swal.fire({
                    title: 'Success!',
                    text: 'Settings updated successfully!',
                    icon: 'success',
                    confirmButtonText: 'OK'
                });
            } catch (error) {
                console.error('Error updating assistant:', error);
                // alert("Failed to update settings.");
            }
        }

    }
};

</script>

<style scoped>
.settings-container {
    margin: 0 auto;
    padding: 0 0 20px;
}

h3 {
    text-align: center;
    margin-bottom: 20px;
}

.settings-card {
    background-color: #ffffff;
    border-radius: 8px;
    padding: 20px;
    box-shadow: #ffffff;
}

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

ul {
    list-style-type: none;
    padding: 0;
}

ul li {
    background-color: #e3e3e3;
    margin: 5px 0;
    padding: 8px;
    border-radius: 4px;
}
.settings-item input,
.settings-item textarea {
    cursor: text;
}
</style>

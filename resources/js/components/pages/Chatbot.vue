<template>
    <div>
        <div class="main">
            <div class="chatbot">
                <h3 class="text-start">
                    <span v-if="loading">
                        <i class="fa fa-spinner fa-spin"></i> Loading...
                    </span>
                    <span v-else>
                        {{ capitalizeFirstLetter(assistantData.name) ? capitalizeFirstLetter(assistantData.name) : assistantData.id }}
                    </span>
                </h3>
                <!-- Chat messages window -->
                <div ref="chatWindow" class="chat-window">
                    <div
                        v-for="(message, index) in messages"
                        :key="index"
                        :class="['message', message.sender]"
                        :style="message.sender === assistantData.name ? formattedAssistantStyle : {}">
                        <div> <strong>{{ message.sender }}: </strong></div>
                        <!-- Use v-html for Markdown rendering only for Assistant messages -->
                        <div v-if="message.sender === assistantData.name" v-html="renderMarkdown(message.text)"></div>
                        <!-- Use plain text rendering for User messages -->
                        <div v-else>{{ message.text }}</div>
                    </div>
                    <!-- Typing indicator -->
                    <div v-if="isTyping"
                         :class="['message', 'bot', 'typing']" :style="formattedAssistantStyle">
                        <span>{{ assistantData.name }} is typing...<i class="fa fa-spinner fa-spin"></i></span>
                    </div>
                </div>

                <!-- Input and send button -->
                <div class="input-container">
                    <input v-model="userInput" @keyup.enter="sendMessage" placeholder="Type your question..." />
                    <button @click="sendMessage">Send</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";
import {marked} from "marked";

export default {
    data() {
        return {
            userInput: '',
            messages: [],
            isTyping: false,
            assistantData: {},
            assistantId: '',
            loading: true,
            threadId: null,
        };
    },
    computed: {
        formattedAssistantStyle() {
            return {
                background: '#f0f8ff'
            };
        },
    },
    created() {
        this.assistantId = localStorage.getItem('assistant_id');
        this.fetchAssistantData();
    },
    mounted() {
        const ring = document.getElementsByClassName('ring')[0];
        if (ring) {
            ring.style.display = 'none';
        }

        const backdrop = document.getElementById('backdrop');
        if (backdrop) {
            backdrop.style.backgroundColor = 'transparent';
        }
    },
    methods: {
        renderMarkdown(text) {
            if (!text) return '';
            marked.setOptions({
                renderer: new marked.Renderer(),
                gfm: true,
                tables: true,
                breaks: true,
                pedantic: false,
                sanitize: true, // Keeps it safe
                smartLists: true,
                smartypants: false,
            });
            return marked(text);
        },
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
                })
                .finally(() => {
                    this.loading = false;
                });
        },
        sendMessage() {
            if (this.userInput.trim() !== '') {
                const question = this.userInput;
                const clinicId = localStorage.getItem('clinic_id');
                this.messages.push({ sender: 'User', text: question });
                this.userInput = '';
                this.isTyping = true;
                this.scrollToBottom();

                axios.post(`/api/v1/assistant/${this.assistantId}/ask-assistant`, {
                    question,
                    clinic_id: clinicId,
                    thread_id: this.threadId
                })
                    .then(response => {
                        this.isTyping = false;
                        // if (!this.threadId && response.data.thread_id) {
                            this.threadId = response.data.threadId;
                        // }
                        this.messages.push({
                            sender: this.assistantData.name,
                            text: response.data?.allAnswers[0]
                        });
                        this.scrollToBottom();
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        this.isTyping = false;
                        this.messages.push({ sender: this.assistantData.name, text: 'Sorry, something went wrong.' });
                        this.scrollToBottom();
                    });
            }
        },
        scrollToBottom() {
            this.$nextTick(() => {
                const chatWindow = this.$refs.chatWindow;
                if (chatWindow) {
                    chatWindow.scrollTop = chatWindow.scrollHeight;
                }
            });
        },
        capitalizeFirstLetter(string) {
            if (!string) return '';
            return string.charAt(0).toUpperCase() + string.slice(1);
        },

    },
    watch: {
        messages() {
            this.scrollToBottom();
        },
    }
};
</script>

<style scoped>
.chatbot h3 {
    align-items: center;
}

.clear-icon {
    margin-left: 10px;
    cursor: pointer;
    color: #ff9900;
    font-size: 18px;
    float: right;
}

.clear-icon:hover {
    color: #cc7a00;
}
button.removeFile {
    background: red;
    padding: 5px 9px;
}
button.removeFile:hover {
    background-color: #ea082dde;
}
.main {
    padding-top: 4%;
}
.chatbot {
    max-width: 600px;
    margin: 0 auto;
    border: 1px solid #ccc;
    padding: 10px;
    border-radius: 5px;
    background-color: #f7f7f7;
    font-family: Arial, sans-serif;
    height: 80vh;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}
.chat-window {
    flex-grow: 1;
    overflow-y: auto;
    border: 1px solid #ddd;
    padding: 10px;
    margin-bottom: 10px;
    background-color: #fff;
    border-radius: 5px;
}
.input-container {
    display: flex;
    border-top: 1px solid #ddd;
    padding-top: 10px;
}

.message {
    margin-bottom: 10px;
    padding: 10px;
    border-radius: 15px;
    display: inline-block;
    max-width: 70%;
    word-wrap: break-word;
    text-align: left;
}

.message.User {
    background-color: #425bcf;
    color: white;
    align-self: flex-end;
    float: right;
    display: flex;
}

.message.Bot {
    background-color: #e5e5ea;
    color: black;
    align-self: flex-start;
    text-align: left;
}

.message.typing {
    background-color: #e5e5ea;
    color: #999;
    font-style: italic;
    border-radius: 15px;
    padding: 10px;
}

input {
    width: 85%;
    padding: 10px;
    border-radius: 5px;
    border: 1px solid #ccc;
}

button {
    padding: 10px 20px;
    background-color: #425bcf;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    margin-left: 10px;
}

button:hover {
    background-color: #425bcf;
}

.chat-container {
    width: 100%;
    padding: 10px;
    background-color: #f0f0f0;
    overflow-y: auto;
    height: 400px;
    max-height: calc(100vh - 150px);
}

.message {
    display: block;
    width: fit-content;
    max-width: 80%;
    padding: 10px 15px;
    border-radius: 20px;
    margin: 10px 0;
    clear: both;
}
.user-message {
    background-color: #425bcf;
    color: white;
    align-self: flex-end;
    margin-left: auto;
    border-radius: 20px 20px 0 20px;
}
.bot-message {
    background-color: #e0e0e0;
    color: black;
    align-self: flex-start;
    margin-right: auto;
    border-radius: 20px 20px 20px 0;
}
.typing-indicator {
    font-style: italic;
    color: gray;
    margin-top: 5px;
}

:deep(#backdrop) {
    background-color: transparent !important;
}

</style>

<template>
    <div class="chat-container chat-container card card-body border-0 shadow-sm mt-lg-4">
        <div class="row g-0">
            <div class="col-md-5">
                <h3>Threads</h3>
                <div v-if="loadingThreads" class="loader-container">
                    <span class="loader"></span> Loading threads...<i class="fa fa-spinner fa-spin"></i>
                </div>
                <p v-else-if="threads.length === 0" class="no-threads">No history available yet. Start a conversation to see it here.</p>

                 <div v-if="!loadingThreads" class="thread-list-box">
                    <ul class="thread-list">
                        <li v-for="(group, index) in groupedThreads" :key="index" class="date-header">
                            <!-- Date header with both relative and exact dates -->
                            <strong class="d-block mb-2 text-black-50">{{ group.relativeDateLabel }}, {{ group.exactDateLabel }}</strong>
                            <ul class="m-0 p-0">
                                <li
                                    v-for="thread in group.threads"
                                    :key="thread.thread_id"
                                    @click="selectThread(thread.thread_id)"
                                    :class="[
                                        'position-relative',
                                        { active: thread.thread_id === selectedThreadId }
                                    ]">
                                    <div class="thread-time">
                                        <span class="end-0 me-2 message-time mt-2 pt-1 top-0 position-absolute">{{ formatTime(thread.last_message_time) }}</span>
                                    </div>
                                    <div class="thread-message-id">
                                        <p class="m-0 text-truncate thread-message">{{ thread.last_message }}</p>
                                        <p class="m-0 text-black-50 text-truncate thread-id">{{ thread.thread_id }}</p>
                                    </div>

                                </li>
                            </ul>
                        </li>
                    </ul>

                     <button v-if="canLoadMore" @click="loadMoreThreads" class="load-more-button text-center btn-sm btn bg-primary text-white mt-3">
                         Load More <i class="fa fa-solid fa-chevron-down"></i>
                     </button>
                 </div>

            </div>

            <div class="col-md-7">
                <div :class="['chat-history', { 'chat-history-active': isChatHistoryActive }]">
                    <h3>Messages</h3>
                    <span class="message-close" @click="closeMessageBox"></span>
                    <div v-if="loadingMessages" class="loader-container m-0 text-center">
                        <span class="loader"></span> Loading messages...<i class="fa fa-spinner fa-spin"></i>
                    </div>
                    <div class="chat-history-list" v-else-if="messages.length && selectedThreadId">
                        <div v-for="message in messages" :key="message.id" class="message">
                            <div class="message-header">
                                <p class="role-time">
                                    <strong>{{ message.role === 'assistant' ? capitalizeFirstLetter(assistantData.name) : capitalizeFirstLetter(message.role) }}</strong>
                                </p>
                                <span class="message-time">{{ formatDate(message.created_at) }}</span>
                            </div>
                            <p class="message-response" v-html="renderMarkdown(message.content[0].text.value)"></p>
                        </div>
                    </div>
                    <div v-else>
                        <div class="chat-history-list">
                            <p class="m-0 text-center" v-if="threads.length === 0">No threads available to show chat history.</p>
                            <p class="m-0 text-center" v-else-if="!selectedThreadId">Select a thread to view chat history.</p>
                            <p class="m-0 text-center" v-else>No messages available in this thread yet.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import {marked} from "marked";

export default {
    data() {
        return {
            threads: [],
            messages: [],
            selectedThreadId: null,
            assistantData: {},
            groupedThreads: [],
            currentPage: 1,
            canLoadMore: false,
            isChatHistoryActive: false,
            loadingThreads: true,
            loadingMessages: false,
            messageBox: true
        };
    },
    async mounted() {
        await this.fetchThreads();
        await this.fetchAssistantData();
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
        closeMessageBox() {
            this.isChatHistoryActive = false;
        },
        async fetchAssistantData() {
            const clinicId = localStorage.getItem('clinic_id');

            if (!clinicId) {
                console.error('No clinic_id found in localStorage');
                this.loading = false;
                return;
            }
            try {
                const response = await axios.get(`/api/v1/assistant/${clinicId}`);
                this.assistantData = response.data;
            } catch (error) {
                console.error('Error fetching assistant:', error);
            }
        },
        async fetchThreads() {
            try {
                this.loadingThreads = true;
                const assistantId = localStorage.getItem('assistant_id');
                const clinicId = localStorage.getItem('clinic_id');
                const response = await axios.get(`/api/v1/threads/${assistantId}?page=${this.currentPage}&clinic_id=${clinicId}`);
                this.threads = [...this.threads, ...response.data.data];
                this.groupThreadsByDate();

                // Automatically select the first thread
                if (this.threads.length && !this.selectedThreadId) {
                    this.selectThread(this.threads[0].thread_id);
                }

                // Check if there are more threads to load
                this.canLoadMore = response.data.pagination.total > this.threads.length;
            } catch (error) {
                console.error("Error fetching threads:", error);
            } finally {
                this.loadingThreads = false;
            }
        },
        async fetchChatHistory(threadId) {
            try {
                this.loadingMessages = true;
                const clinicId = localStorage.getItem('clinic_id'); // Fetch clinic_id from localStorage
                const response = await axios.get(`/api/v1/thread/${threadId}`, {
                    params: {
                        clinic_id: clinicId, // Pass clinic_id as a query parameter
                    },
                });
                this.messages = response.data.data.sort((a, b) => new Date(a.created_at) - new Date(b.created_at));
            } catch (error) {
                console.error("Error fetching chat history:", error);
            } finally {
                this.loadingMessages = false;
            }
        },
        selectThread(threadId) {
                this.selectedThreadId = threadId;
                this.isChatHistoryActive = true;
                this.fetchChatHistory(threadId);
        },
        groupThreadsByDate() {
            const grouped = {};
            this.threads.forEach(thread => {
                const date = moment.unix(thread.last_message_time).startOf('day');
                const today = moment().startOf('day');
                const diffDays = today.diff(date, 'days');

                // Determine relative date label
                let relativeLabel = '';
                if (diffDays === 0) {
                    relativeLabel = 'Today';
                } else if (diffDays === 1) {
                    relativeLabel = 'Yesterday';
                } else {
                    relativeLabel = `${diffDays} days ago`;
                }

                // Determine exact date label (e.g., "Nov 12")
                const exactLabel = date.format('MMM D');

                // Group threads under combined labels
                const combinedLabel = `${relativeLabel}, ${exactLabel}`;
                if (!grouped[combinedLabel]) grouped[combinedLabel] = [];
                grouped[combinedLabel].push(thread);
            });

            // Convert grouped object to array with labels
            this.groupedThreads = Object.keys(grouped).map(dateLabel => ({
                relativeDateLabel: dateLabel.split(',')[0],
                exactDateLabel: dateLabel.split(',')[1],
                threads: grouped[dateLabel],
            }));
        },
        formatTime(timestamp) {
            return moment.unix(timestamp).format('h:mm A');
        },
        loadMoreThreads() {
            this.currentPage++;
            this.fetchThreads();
        },
        capitalizeFirstLetter(string) {
            if (!string) return '';
            return string.charAt(0).toUpperCase() + string.slice(1);
        },
        formatDate(dateString) {
            return moment.unix(dateString).format('MMM D, YYYY - h:mm A');
        }
    },
};

</script>
<style scoped>
.chat-container {display: flex;margin: 0 auto;}
.chat-history {width: 100%;}
ul li {line-height: 2.5;cursor: pointer;}
.message {margin-bottom: 1em;padding: 0.5em;border-bottom: 1px solid #ddd;}
.active {background-color: #f0f0f0;}
p.role {font-size: 16px;margin-bottom: 10px;}
.message-header {display: flex;justify-content: space-between;}
.role-time {display: flex;gap: 10px;}
.message-time {font-size: 0.9em;color: gray;}
.no-threads {font-style: italic;color: gray;text-align: left;}
.message-header .role-time { margin: 0; }
.message p { margin-bottom: 0.5rem; }
ul.thread-list { margin: 0; padding: 0; list-style: none; direction: ltr; }
ul.thread-list li { border-bottom: 1px solid #EDEDED; line-height: 1.8; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; padding: 0.7rem 1rem; }
ul.thread-list li.active { background: #D6E0EA; direction: ltr; }
.thread-list-box, .chat-history-list { max-height: 79vh; overflow: auto; direction: rtl; }
.chat-history-list { padding: 1rem; background: #F9F9FA; border-top: 1px solid #EDEDED; }
.chat-history-list .message { background: #fff; border: 1px solid #EDEDED; padding: 0; margin-bottom: 1rem; }
.chat-history-list .message .message-header { background: #EDEDED; padding: 0.9rem; }
.chat-history-list .message .message-response { padding: 0.9rem; }
.chat-history-list .message:last-child { margin-bottom: 0; }
.chat-history-list { direction: ltr; }
.thread-list-box { border-top: 1px solid #EDEDED; }
ul.thread-list li.active {font-weight: 500;}
p.m-0.text-truncate.thread-message {min-width: 0px;font-weight: 600;overflow: hidden;text-overflow: ellipsis;white-space: nowrap;}
.thread-message-id {display: flex;flex-direction: column;-webkit-box-flex: 1;flex-grow: 1;justify-content: space-around;overflow: hidden;font-size: 13px;font-style: normal;font-weight: normal;}
p.m-0.text-black-50.text-truncate.thread-id {display: flex;-webkit-box-flex: 1;flex-grow: 1;gap: 12px;-webkit-box-align: center;align-items: center;overflow: hidden;color: gray;font-size: 11px;line-height: 16px;}
.thread-time {font-size: 12px;font-style: normal;font-weight: normal;flex-shrink: 0;margin-left: 18px;overflow: hidden;min-width: 0px;text-overflow: ellipsis;text-align: right;}
ul.thread-list li .thread-message-id { padding-right: 2.7rem; }
.thread-list-box ul.thread-list > li { padding: 0; border: 0; }
ul.thread-list li.date-header { margin-top: 1rem; }
.date-header > strong { padding: 0 0 0 1rem; }
.chat-history .chat-history-list { height: 100%; background: #F0F4F8; }
.message-close { position: absolute; height: 28px; width: 28px; right: 2rem; top: 3rem; display: none; cursor: pointer;}
.message-close:before, .message-close:after { content: ""; position: absolute; left: 0; right: 0; top: 0; bottom: 0; background: #070F1C; height: 100%; width: 2px; margin: auto; }
.message-close::before { transform: rotate(45deg); }
.message-close::after { transform: rotate(-45deg); }
@media only screen and (max-width: 767px) {
    .chat-history.chat-history-active { transform: translateY(0);opacity: 1;visibility: visible;}
    .chat-history { position: fixed; background: #fff; left: 0; right: 0; bottom: 0; z-index: 3; height: 80vh; border-radius: 50px 50px 0 0; padding: 3rem 2rem 5rem; box-shadow: 0px -50vh 0px rgba(0, 0, 0, 0.3); transform: translateY(50%);transition: 0.35s all;opacity: 0;visibility: hidden;}
    .chat-history .chat-history-list { max-height: 55vh; }
    .message-close { display: block; }
    .thread-list-box { max-height: inherit; }
    .chat-container { display: flex; margin: 0 auto 65px; }
}
</style>

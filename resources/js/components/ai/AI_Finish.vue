<template>
<!--    <Header></Header>-->
    <div class="welcome-gray-box welcome-box p-5">
		<div class="welcome-box-info">
			<h1 class="d-flex flex-column flex-md-row align-items-center justify-content-center">Welcome To
				<figure>
					<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 923 190" style="enable-background:new 0 0 923 190;">
						<g>
							<path d="M34.28,59.34v68.31c0,10.11,8.23,18.35,18.35,18.35h170.66v21H52.63c-21.69,0-39.35-17.64-39.35-39.35V59.34c0-21.71,17.66-39.35,39.35-39.35h170.66v21H52.63C42.51,41,34.28,49.23,34.28,59.34z"/>
							<path d="M415.64,41h-147v126h-21v-147h168.01c23.17,0,42,18.85,42,42c0,23.15-18.83,42-42,42V83c11.58,0,21-9.43,21-21S427.22,41,415.64,41z M356.22,83l101.42,78.02V167h-26.68l-81.9-63h-38.42V83H356.22z"/>
							<path d="M680.87,19.99v21h-94.59v126h-21V41h-94.42v-21H680.87z"/>
							<path d="M900.62,29.74L836.86,93.5l63.76,63.76V167h-21v-1.04L817.66,104h-44.17l-61.96,61.96V167h-21v-9.74l63.76-63.76l-63.76-63.76v-9.74h21v1.04L773.49,83h44.17l61.96-61.96v-1.04h21V29.74z"/>
						</g>
					</svg>
				</figure>
			</h1>

            <p>
                Schedule your quick onboarding kickoff call with CRTX Account Manager, your dedicated Client Success Manager.
                Please note the doctor MUST be present for this call. During this call, CRTX Account Manager will confirm all
                information added to your CRTX profile is accurate and review next steps for going live with your
                advertising
            </p>
            <div style="left: 0; width: 100%; height: 650px; position: relative; background-color:white; padding:20px;">
                <iframe src="https://calendar.app.google/mdy7S9c8V7KfoT7Q8" style="top: 0; left: 0; width: 100%; height: 100%; position: absolute; border: 0;" allowfullscreen></iframe>
            </div>

                <!-- <p>Congratulations, {{authStore.fullname}}. Registration is complete. Click below to manage your practice.</p> -->
                
                <div class="text-center" style="padding: 20px;">
                    <button v-if="authStore.user" @click="manage" class="btn btn-primary btn-lg">Manage your Practice</button>
                    <button v-else @click="login" class="btn btn-primary btn-lg">Sign In</button>
                </div>
		</div>
	</div>
</template>
<script>
import router from '../../routes'
import { useAuthStore } from '../../stores/auth';
import Header from "../Header.vue";

export default {
    components: {Header},
    setup (){
        const authStore = useAuthStore();

        return { authStore };
    },
    data() {
        return {
            isLoading:false
        }
    },
    methods: {
        manage(){
            router.push('/crtx/manage-practice');
        },
        login(){
            router.push('/crtx/account/signin');
        }
    }
}
</script>

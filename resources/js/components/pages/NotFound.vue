<template>
    <div class="account-main-box py-5 px-2 p-md-5">
		<div class="container">
<!--			<div class="logo-box">
				<figure><svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 923 190" style="enable-background:new 0 0 923 190;">
					<g>
						<path d="M34.28,59.34v68.31c0,10.11,8.23,18.35,18.35,18.35h170.66v21H52.63c-21.69,0-39.35-17.64-39.35-39.35V59.34c0-21.71,17.66-39.35,39.35-39.35h170.66v21H52.63C42.51,41,34.28,49.23,34.28,59.34z"/>
						<path d="M415.64,41h-147v126h-21v-147h168.01c23.17,0,42,18.85,42,42c0,23.15-18.83,42-42,42V83c11.58,0,21-9.43,21-21S427.22,41,415.64,41z M356.22,83l101.42,78.02V167h-26.68l-81.9-63h-38.42V83H356.22z"/>
						<path d="M680.87,19.99v21h-94.59v126h-21V41h-94.42v-21H680.87z"/>
						<path d="M900.62,29.74L836.86,93.5l63.76,63.76V167h-21v-1.04L817.66,104h-44.17l-61.96,61.96V167h-21v-9.74l63.76-63.76l-63.76-63.76v-9.74h21v1.04L773.49,83h44.17l61.96-61.96v-1.04h21V29.74z"/>
					</g>
				</svg></figure>
			</div>-->
			<div class="mt-1 mt-md-1">
				<h1 class="mt-1 mb-3">Not Found</h1>
				<div class="row">
					<div class="col-md-8">
						<div class="learning-info">
							<p>The resource you are looking for was not found on this server.</p>
							<img src="https://placehold.co/600x400?text=404\nNot+Found" alt="">
						</div>
						<!-- <div class="oxy-video-container">
							<video autoplay="" loop="" playsinline="" muted="" style="width: 92%;">
								<source src="https://microsite.com/wp-content/uploads/2022/10/Microsite_Health_Brain_Video.mp4">
							</video>
							<div class="oxy-video-overlay"></div>
						</div> -->
					</div>
				</div>
			</div>
			<div class="bottom-btn-box d-flex align-items-center justify-content-end justify-content-md-start">
				<router-link to="/crtx/dashboard?view=list&page=sms" class="btn btn-primary submit-btn px-4">Go to Home</router-link>
			</div><!--/.form-group-->
		</div>
	</div>
</template>
 <script>
 import router from '../../routes'

 export default {
     setup(){

     },
     data() {
         return {

         }
     },
 }
 </script>

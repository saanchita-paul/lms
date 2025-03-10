<div style="font-family: sans-serif;font-size: small;">
	Hello! 
	<br>
	<br>
	@if($emailformat == 1)
	Your practice has a new patient lead added to the Practice Follow Up Column in your CRM. <br>
	Please click on the link below to add notes and move to the appropriate columns. 
	@endif

	@if($emailformat == 2)
	We just booked a new consultation for {{ $practicename }}!
	@endif

	<br><br>
	Patient Name: {{ $patientname }}
	<br><br>
	Patient DOB: {{ $patientdob }}
	<br><br>
	Appointment Request time and day: {{ $appointmentdate }}
	<br><br>
	Patient Cell Phone: {{ $patientphone }}
	<br><br>
	Patient Email: {{ $patientemail }}
	<br><br>
	Verbal Confirmation: The Doctor is setting aside time for you for this appointment, is there any reason that you wouldn’t be able to make it?: {{ $verbal_confirmation }}
	<br><br>
	Has the patient been informed of the consultation fee if there is one?: {{ $informed_consult_fee }}
	<br><br>
	For the description of the Patient's problem/issue and notes regarding the Patient's budget, please click on following link:  
    <br><br>
	{{ $url }}

	<br><br><br>Thank you. - Microsite Health Lead Management Center
</div>	
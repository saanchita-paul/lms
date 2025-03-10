/*
 * Form Validation
 */
$(function () {

  $('select[required]').css({
    position: 'absolute',
    display: 'inline',
    height: 0,
    padding: 0,
    border: '1px solid rgba(255,255,255,0)',
    width: 0
  });

  $("#formValidate").validate({
    rules: {
      first_name: {
        required: true,
        minlength: 3
      },
      phone: {
        required: true
      },
      clinic_id: {
        required: true,
      },
      tnc_select: "required",
    },
    
    errorElement: 'div',
    errorPlacement: function (error, element) {
      var placement = $(element).data('error');
      if (placement) {
        $(placement).append(error)
      } else {
        error.insertAfter(element);
      }
    }
  });
});
/* Custom General jQuery
/*--------------------------------------------------------------------------------------------------------------------------------------*/
;(function($, window, document, undefined) {
	//Genaral Global variables
	"use strict";
	var $win = $(window);
	var $doc = $(document);
	var $winW = function(){ return $(window).width(); };
	var $winH = function(){ return $(window).height(); };
	var $screensize = function(element){
			$(element).width($winW()).height($winH());
		};

		var screencheck = function(mediasize){
			if (typeof window.matchMedia !== "undefined"){
				var screensize = window.matchMedia("(max-width:"+ mediasize+"px)");
				if( screensize.matches ) {
					return true;
				}else {
					return false;
				}
			} else { // for IE9 and lower browser
				if( $winW() <=  mediasize ) {
					return true;
				}else {
					return false;
				}
			}
		};

	$doc.ready(function() {
/*--------------------------------------------------------------------------------------------------------------------------------------*/
		// Remove No-js Class
		$("html").removeClass('no-js').addClass('js');

		/* Main Navigation Sticky
		* ----------------------------------------------------------------------------------------------------------------------*/
		var intialtop = $(document).scrollTop();
		var headerheight = $("#header").outerHeight();
		$(window).scroll(function() {
			var afterscrolltop = $(document).scrollTop();
			if( afterscrolltop > headerheight ) {
				$("#header").addClass("navshow");
			} else {
				$("#header").removeClass("navshow");
			}
		});

		/* input fill
		================================================== */
		$('.submit-btn').click(function() {
			$(".form-control").each(function() {
			if ($(this).val() !== "") {
				$(this).addClass('filled');
				$(this).removeClass('unfilled');
			} else {
				$(this).addClass('unfilled');
				$(this).removeClass('filled');
			}
			});

		});

		$(".form-control").change(function() {
			$(this).val() ? $(this).addClass("filled") : $(this).removeClass("filled");
		});


		/* Password Show
		================================================== */
		$('.showpass').parent('.form-group').addClass('password-group');
		$(".showpass").on("click", function () {
			$(this).toggleClass("active");
			if ($(this).hasClass("active")) {
				$(this).prev("input").attr("type", "text");
			} else {
				$(this).prev("input").attr("type", "password");
			}
	 	});

		$('input[type="radio"]').click(function(){
			var demovalue = $(this).val();
			$("div.radioDiv").hide();
			$("#show"+demovalue).show();
		});

		$('input[type="checkbox"]').click(function(){
			var demovalue = $(this).val();
			$("div.checkDiv").hide();
			$("#show"+demovalue).show();
		});

		$('.location-add-btn').click(function() {
			$('.location-add-btn-box').toggle("slide");
			$('.location-div').toggle("slide");
		  });

		/* disabled remove box
		---------------------------------------------------------------------*/
		$(".edit-input").click(function(event){
			event.preventDefault();
			$(this).parent('.edit-option').addClass('edit-active');
			$(this).parents('.tab-main-box').addClass('tab-edit-active');
			$(this).parents('.tab-main-box').removeClass('tab-disabled-active');
			$('.form-control').attr('disabled',false);
		});

		$(window).on('resize', function(){
			if (!screencheck(991)) {
				$(".save-input").click(function(event){
					event.preventDefault();
					$(this).parent('.edit-option').removeClass('edit-active');
					$(this).parents('.tab-main-box').removeClass('tab-edit-active');
					$(this).parents('.tab-main-box').addClass('tab-disabled-active');
					$('.form-control').attr('disabled',true);
				});
			}
		}).resize();

		/* Menu ICon Append prepend for responsive
		---------------------------------------------------------------------*/
		$(window).on('resize', function(){
			if (screencheck(991)) {
				$(".tab-title-box").click(function(event){
					$(this).removeClass('tab-main-active');
					$(this).parent('.tab-main-box').find('.form-control').addClass('filled');
					$(this).parent('.tab-main-box').addClass('tab-main-active');
					$('.form-control').attr('disabled',false);
				});
				$(".back-btn").click(function(event){
					$(this).parents('.tab-main-box').removeClass('tab-main-active');
					$('.form-control').attr('disabled',true);
				});


			}
		}).resize();


		/* Move Drop down
		---------------------------------------------------------------------*/
		// $('.move-ico').click( function(){
		// 	$('.move-box .move-stage-box').not( $(this).next('.move-stage-box') ).slideUp(200);
		// 	$(this).next('.move-stage-box').slideToggle(200);
		// 	$(this).parents('tr').toggleClass('open');
		// 	return false;
		// });

		// $('.move-stage-box').on('click touchstart', function(event) {
		// 	event.stopPropagation();
		// });

		// $(document).on('click touchstart', function(e){
		// 	if( $(e.target).parent('.move-stage-box').length === 0 ) {
		// 		$('.move-stage-box').slideUp(200);
		// 		$('tr').removeClass('open');
		// 	}
		// });

		$('.js-example-basic-single').select2();

		/* Show more data
		---------------------------------------------------------------------*/
		// $('.show-more-link').click( function(){
		// 	$('.other-show-box .other-show-data').not( $(this).next('.other-show-data') ).slideUp(200);
		// 	$(this).next('.other-show-data').slideToggle(200);
		// 	$(this).toggleClass('show-more-open');
		// 	return false;
		// });


		$(window).on('resize', function(){
			if (screencheck(991)) {
				$(document).on('click touchstart', function(e){
					if( $(e.target).parent('.lead-search').length === 0 ) {
						$('.lead-search').slideUp(200);
						$('.lead-search-box').removeClass('search-open');
					}
				});
			}
		}).resize();

		/* Move Drop down
		---------------------------------------------------------------------*/
		// $('.lead-search-ico').click( function(){
		// 	$('.lead-search-box .lead-search').not( $(this).next('.lead-search') ).slideUp(200);
		// 	$(this).next('.lead-search').slideToggle(200);
		// 	$(this).parents('.lead-search-box').toggleClass('search-open');
		// 	return false;
		// });
		// $('.lead-search').on('click touchstart', function(event) {
		// 	event.stopPropagation();
		// });



		/* Date picker
		---------------------------------------------------------------------*/
		$( ".datepicker" ).datepicker();


		/* Edit Note
		---------------------------------------------------------------------*/
		$('.note-edit').click( function(){
			$(this).parents('.profile-note-box').addClass('note-editable');
			return false;
		});
		$('.note-save .btn, .edit-mobile-back').click( function(){
			$(this).parents('.profile-note-box').removeClass('note-editable');
			return false;
		});

		/* Add Note
		---------------------------------------------------------------------*/
		$('.add-note-btn').click( function(){
			$(this).parents('.profile-note-list').find('.note-add-box').addClass('note-add-active');
			$(this).parent('.new-note-btn-box').addClass('note-add-top-active');
			return false;
		});
		$('.done-note-btn').click( function(){
			$(this).parents('.profile-note-list').find('.note-add-box').removeClass('note-add-active');
			$(this).parent('.new-note-btn-box').removeClass('note-add-top-active');
			return false;
		});
		$('.card-mobile-back').click( function(){
			$('.note-add-box').removeClass('note-add-active');
			$('.new-note-btn-box').removeClass('note-add-top-active');
			return false;
		});

		/* Filter Drop down
		---------------------------------------------------------------------*/
		// $('.filter-ico').click( function(){
		// 	$('.filter-icons .lead-filterBy-box').not( $(this).next('.lead-filterBy-box') ).slideUp(200);
		// 	$(this).next('.lead-filterBy-box').slideToggle(200);
		// 	$(this).parents('.filter-icons').toggleClass('filter-open');
		// 	return false;
		// });
		// $('.lead-filterBy-box').on('click touchstart', function(event) {
		// 	event.stopPropagation();
		// });
		// $(document).on('click touchstart', function(e){
		// 	if( $(e.target).parent('.lead-filterBy-box').length === 0 ) {
		// 		$('.lead-filterBy-box').slideUp(200);
		// 		$('.filter-icons').removeClass('filter-open');
		// 	}
		// });
		// $('.filterBy-back-btn, .filterBy-save-btn').click( function(){
		// 	$('.lead-filterBy-box').slideUp(200);
		// 	$('.filter-icons').removeClass('filter-open');
		// 	return false;
		// });

		// $('.filterBy-viewall-btn').click( function(){
		// 	$(this).parents('.lead-filterBy-box').addClass('filter-viewall');
		// 	return false;
		// });
		// $('.filterBy-viewall-save').click( function(){
		// 	$(this).parents('.lead-filterBy-box').removeClass('filter-viewall');
		// 	return false;
		// });


		/* chat tab close
		---------------------------------------------------------------------*/
		$(window).on('resize', function(){
			if (screencheck(991)) {
				$('.tab-box').removeClass('tab-active');
			}
		}).resize();
		$('.tab-menu-list .tab-menu').on('click', function(){
			var target = $(this).attr('data-rel');
			$('.tab-menu-list .tab-menu').removeClass('active');
			$(this).addClass('active');
			$(this).removeClass('unread');
			$("#"+target).removeClass('tab-active');
			$("#"+target).addClass('tab-active');
			// $(this).find('.tab-box').first().show();
			$("#"+target).fadeIn('slow').siblings(".tab-box").hide();
			return false;
		});

		$('.chat-log-back').on('click', function(){
			$(this).parents('.tab-box').removeClass('tab-active');
			return false;
		});

		/* Add SMS
		---------------------------------------------------------------------*/
		$(".add-sms-icon").on('click', function(){
			$(this).parents('.inbox-main').addClass('new-sms-active');
			return false;
		});
		$('.sms-log-back').on('click', function(){
			$(this).parents('.inbox-main').removeClass('new-sms-active');
			return false;
		});


		/* date dropdown stopPropagation
		---------------------------------------------------------------------*/
		$('.select-date-dropdown-list').on('click touchstart', function(event) {
			event.stopPropagation();
		});




/*--------------------------------------------------------------------------------------------------------------------------------------*/
	});

/*All function nned to define here for use strict mode
----------------------------------------------------------------------------------------------------------------------------------------*/



/*--------------------------------------------------------------------------------------------------------------------------------------*/
})(jQuery, window, document);

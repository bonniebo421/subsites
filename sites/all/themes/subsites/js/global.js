$(document).ready(function() {

/* NAVIGATION HIDE/SHOW */
	// mouseover nav item
	$("nav a").mouseenter(function() {
		var navItem = $(this).attr('id'); // pull nav id
		$(this).addClass("highlight"); //highlight blue 
		$(this).siblings().removeClass("highlight"); //remove other highlights
		$("div#"+navItem).slideDown(200); // show current dropdown
		$("div#"+navItem).siblings().hide(); // hide other dropdowns
		// mouseout nav item
		$(this).mouseleave(function() {
			// if hover over dropdown
			$(".nav-wrapper").mouseenter(function() {
				$("div#"+navItem).show(); // show current dropdown
				$("nav a#"+navItem).addClass("highlight");
				$("nav a#"+navItem).siblings().removeClass("highlight"); //remove other highlights
				$(".nav-wrapper").mouseleave(function() {
					$("div#"+navItem).hide(); // hide dropdown
					$("nav a#"+navItem).removeClass("highlight"); //remove highlight
				});
				$("div#"+navItem).siblings().hide(); // hide other dropdowns
			});
			// otherwise
			$("div#"+navItem).hide(); // hide dropdown
			$("nav a").removeClass("highlight"); //remove highlight blue on nav hover
		});
	});
	// MOBILE
	var mobileNav = "#mobile nav";
	$("#mobile #menu").click(function() {
		$("form#search-block-form--2").slideUp("fast");
		$("form#search-block-form").slideUp("fast");
		if($(mobileNav).is(":visible")){
			$(mobileNav).slideUp(300);
		}
		else{
			$(mobileNav).slideDown(300);
		}
	});

/* LANGUAGE GUIDES HIDE/SHOW */
	$("#language-icon").click(function() {
		if($("#language-guides").is(":visible")){
			$("#language-guides").slideUp(200);
		}
		else{
			$("#language-guides").slideDown("fast");
		}
	});

/* TEASER DESCRIPTION HIDE/SHOW */
	if($(window).width() >= 550) {
		$(mobileNav).slideUp("fast");
		$(".teaser-description").mouseenter(function() {
			$(this).addClass("stretch");
			$(this).parent().find('p').slideDown("fast");
		});
		$(".teaser-description").mouseleave(function() {
			$(this).parent().find('p').slideUp("fast");
			$(this).removeClass("stretch");
		});
	};

/* BOX CLOSE */
	$(".close-button").click(function() {
		$(this).parent().hide();
		return false;
	});

/* DESKTOP SEARCH BAR HIDE/SHOW */
	$("#desktop-search-icon").click(function() {
		if($("form#search-block-form--2").is(":visible")){
			$("form#search-block-form--2").slideUp("fast");
		}
		else{
			$("form#search-block-form--2").slideDown("fast");
		}
		if($("form#search-block-form").is(":visible")){
			$("form#search-block-form").slideUp("fast");
		}
		else{
			$("form#search-block-form").slideDown("fast");
		}
	});
/* MOBILE SEARCH BAR HIDE/SHOW */
	$("#mobile-search-icon").click(function() {
		$(mobileNav).slideUp("fast");
		if($("form#search-block-form--2").is(":visible")){
			$("form#search-block-form--2").slideUp("fast");
		}
		else{
			$("form#search-block-form--2").slideDown("fast");
		}
		if($("form#search-block-form").is(":visible")){
			$("form#search-block-form").slideUp("fast");
		}
		else{
			$("form#search-block-form").slideDown("fast");
		}
	});

/* HIDE MOBILE DROPDOWNS ON RESIZE */
	$( window ).resize(function() {
		$("#language-guides").slideUp("fast");
		if($(window).width() >= 550) {
			$(mobileNav).slideUp("fast");
		};
	});

/*  hide & show functionality for daily schedule items  */
	$("h4.title").click(function() {
		if($(this).parent().next().is(":hidden")){
			$(this).parent().next().slideDown(300); // slidedown content
  		}
  		else{
			$(this).parent().next().slideUp(300); // hide content
  		}
  		return false; // remove anchor <a> functionality
	});

/*  hide & show functionality for FAQ item  */
	$("span.hide").hide();
	$(".read-more").click(function() {
		if($(this).next().is(":hidden")){
			$(this).next().slideDown(300); // slidedown content
			$(this).find("span.show").hide();
			$(this).find("span.hide").show();
  		}
  		else{
			$(this).next().slideUp(300); // hide content
			$(this).find("span.show").show();
			$(this).find("span.hide").hide();
  		}
  		return false; // remove anchor <a> functionality
	});

/* slideshow initialization */

	var numItems = $(".bxslider li").length;
	if(numItems == 1){
		$('.bxslider').bxSlider({
			video: true,
			useCSS: false,
			auto: false,
			infiniteLoop: false,
			touchEnabled: false
		});
	}
	else{
		$('.bxslider').bxSlider({
			video: true,
			useCSS: false,
			auto: true
		});
		$(".bx-prev").hide();
		$(".bx-next").hide();
		$(".bxslider").mouseenter(function() {
			$(".bx-prev").show();
			$(".bx-next").show();
			$(".bx-controls").mouseenter(function() {
				$(".bx-prev").show();
				$(".bx-next").show();
			});
			$(".bxslider").mouseleave(function() {
				$(".bx-prev").hide();
				$(".bx-next").hide();
			});
		});
	};


/* daily schedulate date picker */
    $('div#date-picker').datepicker({
        todayHighlight: true
    }).on('changeDate', function () {
        var date = $(this).datepicker('getDate');

        var year  = date.getFullYear();
        var month = date.getMonth() + 1;
        var day   = date.getDate();

        window.location = window.location.protocol + '//' + window.location.hostname + '/daily-schedule?day=' + year + '-' + month + '-' + day;
    });


 /* google maps disable scroll */
	$('#map').on("mouseup",function(){
		$('#map iframe').addClass('scroll-off');
	});
	$('#map').on("mousedown",function(){
		$('#map iframe').removeClass('scroll-off');
	});
	$("#map iframe").mouseleave(function () {
		$('#map iframe').addClass('scroll-off');
	});
});

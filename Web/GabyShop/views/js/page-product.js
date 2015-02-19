// JavaScript Document
$(document).ready(function(){

	// AddThis share menu plugin
	
    var delay = 400;
    
    function hideMenu() {
        if (!$('.custom_button').data('in') && !$('.hover_menu').data('in') && !$('.hover_menu').data('hidden')) {
            $('.hover_menu').fadeOut('fast');
            $('.custom_button').removeClass('active');
            $('.hover_menu').data('hidden', true);
        }
    }
    
    $('.custom_button, .hover_menu').mouseenter(function() {
        $('.hover_menu').slideDown(300);
        $('.custom_button').addClass('active');
        $(this).data('in', true);
        $('.hover_menu').data('hidden', false);
    }).mouseleave(function() {
        $(this).data('in', false);
        setTimeout(hideMenu, delay);
    });
   
 });

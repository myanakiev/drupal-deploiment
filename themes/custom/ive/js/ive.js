/*(function ($, Drupal, drupalSettings) {
 $(document).ready(function() {
	$("a[href^='http']").attr('target', '_blank');
	
	$(".node a[href^='http']").addClass('external-link');
	
	$(".block > h2").click(function() {
		$(this).siblings('.content').toggle(700);
	});
	
 });
})(jQuery, Drupal, drupalSettings);*/



(function ($, Drupal, drupalSettings) {
  //externalLink
  Drupal.behaviors.externalLink = {
	  attach: function(context, settings) {
		  $("a[href^='http']", context).attr('target', '_blank').addClass('external-link');
	  }
  }
  
  //blockCollapsable
  Drupal.behaviors.blockCollapsable = {
	  attach: function(context, settings) {
		  $(".sidebar .block h2", context).click(function() {
			  $(this).siblings('.content').slideToggle('fast');
		  });
	  }
  }
})(jQuery, Drupal, drupalSettings);



(function ($, Drupal, drupalSettings) {
  //makeTabs
  Drupal.behaviors.makeTabs = {
	  attach: function(context, settings) {
		  $("#tabs").tabs();
	  }
  }
})(jQuery, Drupal, drupalSettings);

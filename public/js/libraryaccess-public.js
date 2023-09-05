(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

		var ajaxUrl = libraryaccess_public_vars.ajax_url;
	
		function updateCourseCount() {
			$.ajax({
				url: ajaxUrl,
				type: 'POST',
				data: {
					action: 'get_course_count',
					timestamp: new Date().getTime()
				},
				success: function(response) {
					var course_count = parseInt(response);
					var ldProfileStatCoursesCount = $(".ld-profile-stat-courses strong");
	
					console.log('ld-profile-stat-courses Count:', ldProfileStatCoursesCount.text());
					console.log(course_count);
	
					if (course_count === parseInt(ldProfileStatCoursesCount.text())) {
						$("#learndash-loading").css("display", "none");
						clearInterval(interval);
					} else {
						$("#learndash-loading").css("display", "block");
					}
				}
			});
		}
	
		function refreshContent() {
			$("#ld-profile").load(location.href + " #ld-profile");
		}
	
		updateCourseCount();
		var interval = setInterval(updateCourseCount, 1000);
		setInterval(refreshContent, 2000);

})( jQuery );

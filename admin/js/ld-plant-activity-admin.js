(function( $ ) {
	'use strict';

	jQuery(document).ready(function($) {
		$('.view-statistics').on('click', function(e) {
			e.preventDefault();

			const lessonId = $(this).data('lesson');
			const userId   = $(this).data('user');

			$('#statistic-modal').show();
			$('#statistic-modal-content').html('Loading...');

			$.post(window.PlantActivityStats.ajax_url, {
				action: 'get_plant_activity_statistics',
				_wpnonce: window.PlantActivityStats.nonce,
				lesson_id: lessonId,
				user_id: userId
			}, function(response) {
				if (response.success) {
					let html = '<table class="widefat striped"><tbody>';
					$.each(response.data.stats, function(key, value) {
						html += '<tr><td><strong>' + key.replace(/_/g, ' ') + '</strong></td><td>' + value + '</td></tr>';
					});
					html += '</tbody></table>';
					$('#statistic-modal-content').html(html);
				} else {
					$('#statistic-modal-content').html('<p>' + response.data.message + '</p>');
				}
			});
		});

		$('#statistic-modal-close, #statistic-modal-overlay').on('click', function() {
			$('#statistic-modal').hide();
		});
	});

	/**
	 * All of the code for your admin-facing JavaScript source
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

})( jQuery );

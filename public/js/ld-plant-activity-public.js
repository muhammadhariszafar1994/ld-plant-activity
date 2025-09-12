// (function( $ ) {
// 	'use strict';

	

// 	/**
// 	 * All of the code for your public-facing JavaScript source
// 	 * should reside in this file.
// 	 *
// 	 * Note: It has been assumed you will write jQuery code here, so the
// 	 * $ function reference has been prepared for usage within the scope
// 	 * of this function.
// 	 *
// 	 * This enables you to define handlers, for when the DOM is ready:
// 	 *
// 	 * $(function() {
// 	 *
// 	 * });
// 	 *
// 	 * When the window is loaded:
// 	 *
// 	 * $( window ).load(function() {
// 	 *
// 	 * });
// 	 *
// 	 * ...and/or other possibilities.
// 	 *
// 	 * Ideally, it is not considered best practise to attach more than a
// 	 * single DOM-ready or window-load handler for a particular page.
// 	 * Although scripts in the WordPress core, Plugins and Themes may be
// 	 * practising this, we should strive to set a better example in our own work.
// 	 */

// })( jQuery );

function restartPlantActivity(lessonId, url) {
	const resetData = {
		water_progress: 0,
		water_points: 0,
		sun_progress: 0,
		sun_points: 0,
		nutrient_progress: 0,
		nutrient_points: 0,
		balance_recovery_points: 0,
		total: 0,
		last_growth_point: 0,
		plant_assets: '',
		activity_status: 0,
		activity_started: '',
		activity_completed: '',
		activity_updated: ''
	};

	const formData = new FormData();
	formData.append('action', 'sfwd_save_plant_activity_statistic');
	formData.append('_wpnonce', window.LDPlantActivityReset.nonce);
	formData.append('lesson_id', lessonId);

	for (const key in resetData) {
		formData.append(key, resetData[key]);
	}

	fetch(window.LDPlantActivityReset.ajax_url, {
		method: 'POST',
		credentials: 'include',
		body: formData,
	})
	.then(res => res.json())
	.then(result => {
		alert('Plant Activity has been reset!');
		window.open(url, '_blank');
	})
	.catch(err => {
		console.error('Restart failed:', err);
		alert('Failed to reset activity. Please try again.');
	});
}
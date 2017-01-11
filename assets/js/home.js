"use strict";
$(document).ready(function() {
	var base = $('#intro').attr('data-url') + 'Home/';
	getData();

	function getData() {

		$.ajax({
	        dataType: "json",
	        url: base + 'getAll',
	        success: function(result) {
	            $('.number.characters').text(result.data.characters);
	            $('.number.keys').text(result.data.keys);
	            $('.number.profit').text(result.data.profit);
	            $('.number.transactions').text(result.data.transactions);
	        }
	    });
	}
});
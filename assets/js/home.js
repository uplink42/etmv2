"use strict";
$(document).ready(function() {
	var base = $('#intro').attr('data-url') + 'Home/';
	getData();

	function getData() {
		$.ajax({
	        dataType: "json",
	        url: base + 'getAll',
	        success: function(result) {
	            $('.number.characters').text(result.characters);
	            $('.number.keys').text(result.keys);
	            $('.number.profit').text(result.profit);
	            $('.number.transactions').text(result.transactions);
	        }
	    });
	}
});
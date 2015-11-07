(function() {
	
$(document).ready(function() {
	
	$('tbody > tr').each(
		function(index, element) {
			
			// add hover listeners for measurement rows
			$(element).hover(
				function() {
					$(element).addClass('rowHover');
				},
				function() {
					$(element).removeClass('rowHover');
				}
			);
			
			// add click listeners for measurement rows
			$(element).click(function() {
				$('tbody > tr').each(function(index, element) {
					$(element).removeClass('rowSelected');
				});
				$(element).addClass('rowSelected');
			});
		}
	);
	
});

//// mouse enter handler for measurement rows
//function rowEnter(event) {
//	alert('entered');
//	$(event.relatedTarget).addClass('rowHover');
//}
//
//// mouse leave handler for measurement rows
//function rowLeave(event) {
//	alert('left');
//	$(event.relatedTarget).removeClass('rowHover');
//}
//
//// mouse click handler for measurement rows
//function rowClick(event) {
//	$('tbody > tr').each(function(index, element) {
//		$(element).removeClass('rowSelected');
//	});
//	
//	$(this).addClass('rowSelected');
//}

})();
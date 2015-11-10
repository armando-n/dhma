//$(function() {  
//   var window_height = $(window).height(),
//   content_height = window_height - 200;
//   $('.myscrollable').height(content_height);
//});

//$( window ).resize(function() {
//   var window_height = $(window).height(),
//   content_height = window_height - 200;
//   $('.myscrollable').height(content_height);
//});

(function() {
	
$(document).ready(function() {
	// initialize the members table
	var membersTable = $('#membertable').DataTable( {
		scrollY: '35vh',
		scrollCollapse: true,
		paging: false,
		info: false,
		select: true,
		buttons: [
		    {
		    	extend: 'selected',
		    	text: 'This is a test',
		    	action: function (e, dt, button, config) {
		    		var rows = dt.rows( { selected: true } ).count();
		    	    alert( 'There are ' +rows+ '(s) selected in the table');
		    	}
		    }
        ]
	} );
	
	// add a button to the table
	membersTable.buttons().container().appendTo(membersTable.table().container());
});

function rowSelected(e, dt, node, config) {
	var rows = dt.rows( { selected: true } ).count();
    alert( 'There are ' +rows+ '(s) selected in the table');
}
	
})();
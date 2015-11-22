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
		//paging: false,
		//info: false,
		select: true,
		dom: 'ftB',
		buttons: {
			name: 'primary',
			buttons: [
			    {
			    	extend: 'selectedSingle',
			    	text: 'Edit',
			    	action: editMember
			    },
			    {
			    	extend: 'selected',
			    	text: 'Delete',
			    	action: deleteMember
			    },
			    'columnsToggle'
			]
//	buttons: [
//    {
//    	extend: 'selectedSingle',
//    	text: 'Edit',
//    	action: editMember
//    },
//    {
//    	extend: 'selected',
//    	text: 'Delete',
//    	action: deleteMember
//    },
		}
	
	} );
	
	// add a button to the table
//	membersTable.buttons().container().appendTo(membersTable.table().container());
});

function editMember(e, dt, button, config) {
	var rows = dt.rows( { selected: true } ).count();
	var rowData = dt.rows( { selected: true } ).data();
    alert( 'You have chosen to edit the member: ' +rowData.Name);
}

function deleteMember(e, dt, button, config) {
	var rows = dt.rows( { selected: true } ).count();
	alert('You have chosen to delete ' +rows+ ' member(s)');
}
	
})();
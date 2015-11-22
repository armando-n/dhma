(function() {
	
$(document).ready(function() {
	// initialize the members table
	var membersTable = $('#membertable').DataTable( {
		ajax: {
			url: '/na_lab5/users_getAll',
			dataSrc: ''
		},
		columns: [
	        {
	        	data: 'picture',
	        	render: data_showPicture,
	        	title: 'Picture'
	        },
		    {
		    	data: 'firstName',
		    	render: data_linkProfile,
		    	title: 'First Name'
		    },
		    {
		    	data: 'lastName',
		    	render: data_linkProfile,
		    	title: 'Last Name'
	    	},
		    {
	    		data: 'email',
	    		render: data_linkEmail,
	    		title: 'Email'
			}
		],
		scrollY: '35vh',
		scrollCollapse: true,
		select: true,
		dom: 'Bft',
		buttons: {
			name: 'primary',
			buttons: ['columnsToggle']
		}
	
	} );
	
	new $.fn.dataTable.Buttons(membersTable, {
		name: 'commands',
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
			}
        ]
	} );
	
	$('<span>Click to Show/Hide Columns:</span>').prependTo($('#membertable_wrapper'));
	membersTable.button(1, null).container().appendTo(membersTable.table().container());
	membersTable.button(0, null).container().addClass('btn-group-justified');
	membersTable.button(1, null).container().addClass('btn-group-justified');
});

function data_showPicture(data, type, row, meta) {
	var result = '<a href="/na_lab5/profile_show_' +row.userName+ '">';
	result = result +  '<img src="/na_lab5/images/profile/' +data+ '" class="img-circle" alt="' +row.userName+ '\'s profile picture" width="45" height="45" />';
	result = result + '</a>';
	return result;
}

function data_linkEmail(data, type, row, meta) {
	return '<a href="mailto:' +data+ '">' +data+ '</a>';
}

function data_linkProfile(data, type, row, meta) {
	return '<a href="/na_lab5/profile_show_' +row.userName+ '">' +data+ '</a>';
}

function editMember(e, dt, button, config) {
	var selectedRow = dt.row( {selected: true} ).data();
	window.location.assign('/na_lab5/profile_edit_show_' +selectedRow.userName);
}

function deleteMember(e, dt, button, config) {
	var rows = dt.rows( {selected: true} );
	var rowCount = rows.count();
	var url = '/na_lab5/members_delete';
	
	if (window.confirm('Are you sure you want to delete the ' +rowCount+ ' selected member(s)?')) {
		for (var i = 0; i < rowCount; i++)
			url = url + '_' + rows.data()[i].userName;

		$.ajax( {
			'url': url,
			'success': function() {
				$('#membertable').DataTable().ajax.reload();
			},
			'error': function() {}
		} );
	}	
}
	
})();
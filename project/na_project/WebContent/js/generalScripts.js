(function() {
	
$(document).ready(function() {
	$('.changeTheme > a.lightTheme').click(changeTheme);
	$('.changeTheme > a.darkTheme').click(changeTheme);
});

function changeTheme() {
	var theme = $(this).text();
	
	// send request to server
	$.ajax({
		url: 'profile_edit_theme_'+theme,
		dataType: 'json',
		method: 'GET',
		success: function(response) {
			if (response.success) {
				if (response.data.rowsAffected < 1)
					console.log('The '+theme+' theme was already set.');
				window.location.reload();
			}
			else
				alert('Changes to theme storing failed: ' +response.error);
		},
		error: function() { alert('Error: invalid response when attempting to store theme changes.'); }
	});
	
	return false;
}

})();
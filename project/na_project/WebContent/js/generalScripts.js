(function() {
	
$(document).ready(function() {
	$('.changeTheme > a.lightTheme').click(changeTheme);
	$('.changeTheme > a.darkTheme').click(changeTheme);
	$('#main-nav [data-toggle="tooltip"]').tooltip( { container: 'body' } );
});

function changeTheme() {
	var theme = $(this).text();
	var showTooltips = $(this).attr('data-toggle') !== undefined;
	
	if ($(this).hasClass('disable'))
	    return false;
	
	// send request to server
	$.ajax({
		url: 'profile_edit_theme_'+theme,
		dataType: 'json',
		method: 'GET',
		success: function(response) {
			if (response.success) {
			    var css, cssRemove;
			    var js, jsRemove;
			    var img;
			    var urlBase;
			    
				if (response.data.rowsAffected < 1)
					console.log('The selected theme was already set.');
					
			    urlBase = window.location.href.substring(0, window.location.href.lastIndexOf('/')+1);
					
			    // determine which css/js/logo to load/unload
				if (theme === 'dark') {
				    css = 'bootstrap.dark.min.css';
				    cssRemove = 'bootstrap.min.css';
				    js = 'dark-unica.js';
				    jsRemove = 'light-default.js';
				    img = 'images/logo_dark.png';
				} else {
				    css = 'bootstrap.min.css';
				    cssRemove = 'bootstrap.dark.min.css';
				    js = 'light-default.js';
				    jsRemove = 'dark-unica.js';
				    img = 'images/logo.png';
				}
				
				// change theme btns appearance and tooltips
				if (theme === 'dark') {
				    $('.darkTheme').addClass('disable').attr('title', 'The dark theme is enabled');
				    $('.lightTheme').removeClass('disable').attr('title', 'Enable the light site theme');
				    $('.darkTheme[data-toggle="tooltip"]').tooltip('fixTitle');
                    $('.lightTheme[data-toggle="tooltip"]').tooltip('fixTitle');
                    
				    if (showTooltips)
				        $('.darkTheme[data-toggle="tooltip"]').tooltip('show');
				} else {
				    $('.darkTheme').removeClass('disable').attr('title', 'Enable the dark site theme');
				    $('.lightTheme').addClass('disable').attr('title', 'The light theme is enabled');
				    $('.darkTheme[data-toggle="tooltip"]').tooltip('fixTitle');
				    $('.lightTheme[data-toggle="tooltip"]').tooltip('fixTitle');
                    
				    if (showTooltips)
				        $('.lightTheme[data-toggle="tooltip"]').tooltip('show');
				}
				
				// remove and replace css for old theme
				$('<link rel="stylesheet" type="text/css" href="css/'+css+'" />').appendTo('head');
				$('link[href*="'+cssRemove+'"]').remove();
				
				// remove and replace js for charts old theme
				var control = window.location.pathname.split('/')[2];
				if (control.includes('measurements') || control === 'demo') {
				    $('body').hide();
				    $('script[src*="'+jsRemove+'"]').remove();
    				$('<script src="lib/highcharts/'+js+'"></script>').appendTo('head');
    				$('#refreshCharts').click();
    				$('body').show();
                }
                
                // replace brand image
                $('#brand-image').attr('src', urlBase+img);
			}
			else
				alert('Changes to theme storing failed: ' +response.error);
		},
		error: function() { alert('Error: invalid response when attempting to store theme changes.'); }
	});
	
	return false;
}

})();
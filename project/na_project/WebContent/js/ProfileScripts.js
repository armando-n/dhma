(function() {

$(document).ready(function() {
	$('#picture').change(loadPicture);
});

function loadPicture() {
	// make sure File API is supported. display file name only if it's not
	if (! (window.File && window.FileReader && window.FileList) ) {
		$('#picture-wrapper').html($(this).val().trim());
		return;
	}
	
	var files = this.files;
	var latestFile = files[files.length-1];
	
	// show loading icon
	$('#picture-wrapper').html('<img src="/na_project/images/icon_loading_small.gif" class="img-responsive" alt="Loading picture icon" />');
	
	// make sure the file is an image
	if (!latestFile.type.match('image.*')) {
		$('#picture-wrapper').html('<p class="text-danger text-center">The selected file is not a valid image.</p>');
		return;
	}
	
	// assign event handler for when file is finished being read
	var reader = new FileReader();
	reader.onload = function(event) {
		$('#picture-wrapper').html('');
		var img = $('<img id="profileImg" src="' +event.target.result+ '" class="img-responsive img-rounded" alt="Selected profile picture" />');
		img.appendTo($('#picture-wrapper'));
	};
	
	// read the file
	reader.readAsDataURL(latestFile);
}

})();
(function() {

$(document).ready( function() {
	$('#picture').change(loadPicture);
	$('#email').blur(checkEmail);
	$('#userName').blur(checkUserName);
	$('#password').blur(checkPasswords);
	$('#retypePassword').blur(checkPasswords);
	$('#firstName').blur(checkFirstName);
	$('#lastName').blur(checkLastName);
	$('#phone').blur(checkPhone);
	$('#facebook').blur(checkFacebook);
	$('#dob').blur(checkDOB);
	$('#signupForm').submit(validateForm);
	
	// add date picker for signup form
	$('.date-picker').datetimepicker( { format: 'YYYY-MM-DD' } );
	
	$('[data-toggle="tooltip"]').tooltip();
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
	$('#picture-wrapper').html('<img src="/dhma/images/icon_loading_small.gif" class="img-responsive" alt="Loading picture icon" />');
	
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

function validateForm() {
	return checkDOB() && checkFacebook() && checkPhone && checkFirstName() &&
		   checkLastName() && checkEmail() && checkUserName() && checkPasswords();
}

function checkDOB() {
	return validateInput($('#dob'), 'Date of birth', true, null, null, /^((\d{4}-\d\d-\d\d)|(\d\d\/\d\d\/\d{4}))$/, 'yyyy-mm-dd or mm/dd/yyyy');
}

function checkFacebook() {
	return validateInput($('#facebook'), 'Facebook', true, null, 50, /((http|https):\/\/)?(www\.)?facebook\.com\/.+/, 'http://www.facebook.com/mypage');
}

function checkPhone() {
	return validateInput($('#phone'), 'Phone number', true, null, null, /^(1\s*[-\/\.]?)?(\((\d{3})\)|(\d{3}))\s*[-\/\.]?\s*(\d{3})\s*[-\/\.]?\s*(\d{4})\s*(([xX]|[eE][xX][tT])\.?\s*(\d+))*$/, 'xxx-xxx-xxxx');
}

function checkFirstName() {
	return checkName($('#firstName'), 'First name');
}

function checkLastName() {
	return checkName($('#lastName'), 'Last name');
}

function checkName(input, inputName) {
	return validateInput(input, inputName, true, null, 30, /^[a-zA-Z '-]+$/, 'only letters/spaces/apostrophes/dashes allowed.');
}

function checkEmail() {
	return validateInput($('#email'), 'E-mail', false, null, 30, /^[A-Za-z0-9](([_\.\-]?[a-zA-Z0-9]+)*)@([A-Za-z0-9]+)(([\.\-]?[a-zA-Z0-9]+)*)\.([A-Za-z]+)$/, 'x@x.x');
}

function checkUserName() {
	if (!validateInput($('#userName'), 'User name', false, null, 20, /^[a-zA-Z0-9-]+$/, 'only numbers/letters/dashes allowed.'))
		return false;
	
	var userName = $('#userName').val().trim();
	var uNameHelp = $('#uNameHelp');
	
	// show loading icon
	uNameHelp.html('<img src="/dhma/images/icon_loading_small.gif" alt="loading icon" class="img-responsive" />').parent().removeClass('has-error');
	
	// send request to server to make sure user name is not already taken
	var returnValue = false;
	$.ajax( {
		url: '/dhma/users_get_' + userName,
		data: $('#userName').serialize(),
		dataType: 'json',
		async: false,
		success: function(result) {
			if (result.error != undefined) { // unable to retreive user with specified user name. it must not exist.
				uNameHelp.html('User name available').addClass('text-success');
				uNameHelp.parent().removeClass('has-error');
				returnValue = true;
			} else {
				uNameHelp.html('This user name is already taken. Please try another.').removeClass('text-success');
				uNameHelp.parent().addClass('has-error');
				returnValue = false;
			}
		},
		error: function() {
			console.log('Failed to receive response from server for user name check.');
			returnValue = false;
		}
	} );
	
	return returnValue;
}

function checkPasswords() {
	var firstPass = $('#password').val().trim();
	var secondPass = $('#retypePassword').val().trim();
	var help = $('#passHelp');
	var passParents = $('input[type=password]').parent();
	
	// make sure password is not empty
	if (secondPass.length == 0)
		return false;
	
	// make sure passwords are at least 6 characters long
	if (firstPass.length < 6) {
		help.text('Password must be at least 6 characters long.').removeClass('text-success');
		passParents.addClass('has-error');
		return false;
	}
	
	// make sure passwords are 20 characters or less
	if (firstPass.length > 20) {
		help.text('Password cannot be more than 20 characters long.').removeClass('text-success');
		passParents.addClass('has-error');
		return false;
	}
	
	// make sure passwords match
	if (firstPass !== secondPass) {
		help.text('Your passwords do not match.').removeClass('text-success');
		passParents.addClass('has-error');
		return false;
	}
	
	// success
	help.text('Passwords match.').addClass('text-success');
	passParents.removeClass('has-error');
	return true;
}

function validateInput(input, inputName, isEmptyOkay, minLength, maxLength, regex, validForm) {
	var inputVal = $(input).val().trim();
	var help = $(input).parent().find('.help-block');
	
	if (inputVal.length == 0)
		return isEmptyOkay;
	
	if (minLength && inputVal.length < minLength) {
		help.text(inputName+ ' must be at least ' +minLength+ ' characters long.').parent().addClass('has-error');
		return false;
	}
	
	if (maxLength && inputVal.length > maxLength) {
		help.text(inputName+ ' cannot be more than ' +maxLength+ ' characters long.').parent().addClass('has-error');
		return false;
	}
	
	var isValid = regex.test(inputVal);
	if (!isValid) {
		help.text(inputName+ ' invalid. It must be of the form: ' +validForm).parent().addClass('has-error');
		return false;
	}
	
	// success
	help.text('').parent().removeClass('has-error');
	return true;
}

})();
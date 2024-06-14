const EMAIL_REGEX = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@usc\.edu$/;

function isValidEmail(email) {
	return EMAIL_REGEX.test(email);
}
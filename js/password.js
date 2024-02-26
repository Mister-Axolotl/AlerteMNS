const password = document.querySelector('#password');
const eyeIcon = document.querySelector('#eyeIcon');

function displayPassword() {
	// each letter is a dot -> see the password in letter
	if (password.classList.contains('dots')) {
		password.type = "text";
		password.classList = 'visible';
		eyeIcon.src = "images/closed_eye.png";
	} else if (password.classList.contains('visible')) {
		password.type = "password";
		password.classList = 'dots';
		eyeIcon.src = "images/opened_eye.png";
	}
}
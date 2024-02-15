const password = document.querySelector('#password');
const eyeIcon = document.querySelector('#eyeIcon');

function displayPassword() {
	// each letter is a dot -> see the password in letter
	if (password.classList.contains('dots')) {
		password.type = "text";
		password.classList = 'visible';
		// TODO : mettre les bonnes images (oeil barré)
		eyeIcon.src = "images/bell.png";
	} else if (password.classList.contains('visible')) {
		password.type = "password";
		password.classList = 'dots';
		// TODO : mettre les bonnes images (oeil non barré)
		eyeIcon.src = "images/loupe.png";
	}
}
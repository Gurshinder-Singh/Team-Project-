@ -0,0 +1,50 @@
// Password Strength Indicator
const passwordField = document.getElementById('password');
const passwordStrength = document.getElementById('password-strength');

if (passwordField && passwordStrength) {
    passwordField.addEventListener('input', () => {
        const value = passwordField.value;
        let strength = 'Weak';

        if (value.length > 8 && /[A-Z]/.test(value) && /[0-9]/.test(value)) {
            strength = 'Strong';
        } else if (value.length > 4) {
            strength = 'Moderate';
        }

        passwordStrength.textContent = `Strength: ${strength}`;
        passwordStrength.style.color = strength === 'Strong' ? 'green' : strength === 'Moderate' ? 'orange' : 'red';
    });
}

// Password Match Validation
const confirmPasswordField = document.getElementById('confirm-password');
const form = document.querySelector('form');
const errorMessage = document.createElement('p');

if (confirmPasswordField) {
    confirmPasswordField.addEventListener('input', () => {
        if (passwordField.value !== confirmPasswordField.value) {
            errorMessage.textContent = 'Passwords do not match!';
            errorMessage.style.display = 'block';
            form.appendChild(errorMessage);
        } else {
            errorMessage.style.display = 'none';
        }
    });
}

// Password Visibility Toggle
document.querySelectorAll('.toggle-password').forEach(toggle => {
    toggle.addEventListener('click', () => {
        const passwordField = toggle.previousElementSibling;
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            toggle.textContent = '👁'; 
        } else {
            passwordField.type = 'password';
            toggle.textContent = '👁'; 
        }
    });
});


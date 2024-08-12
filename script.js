// // Select elements
// const registerLink = document.getElementById('register-link');
// const registerPopup = document.getElementById('register-popup');
// const closePopup = document.getElementById('close-popup');
// const registrationForm = document.getElementById('registration-form');
// const registerBtn = document.getElementById('register-btn');
// const registrationResponse = document.getElementById('registration-response');
// const loadingIndicator = document.getElementById('loading-indicator');

// // Add event listeners
// registerLink.addEventListener('click', () => {
//     registerPopup.classList.add('show');
//     registerPopup.querySelector('.popup-content').classList.add('show');
// });

// closePopup.addEventListener('click', () => {
//     registerPopup.classList.remove('show');
//     registerPopup.querySelector('.popup-content').classList.remove('show');
// });

// registerBtn.addEventListener('click', (e) => {
//     e.preventDefault();
//     const formData = new FormData(registrationForm);
//     if (!validateForm(formData)) {
//         return;
//     }
//     loadingIndicator.style.display = 'block';
//     // Simulate API call (replace with actual API call)
//     setTimeout(() => {
//         loadingIndicator.style.display = 'none';
//         registrationResponse.textContent = 'Registration successful!';
//         registrationResponse.style.color = 'green';
//     }, 2000);
// });

// // Form validation function
// function validateForm(formData) {
//     const name = formData.get('name');
//     const email = formData.get('email');
//     const farmName = formData.get('farm-name');
//     const products = formData.get('products');

//     if (!name || !email || !farmName || !products) {
//         alert('Please fill in all fields');
//         return false;
//     }

//     if (!validateEmail(email)) {
//         alert('Invalid email address');
//         return false;
//     }

//     return true;
// }

// // Email validation function
// function validateEmail(email) {
//     const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
//     return emailRegex.test(email);
// }

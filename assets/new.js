var signupBtn = document.querySelector(".signup")
var loginBtn = document.querySelector(".login")
var signupForm = document.querySelector(".signupReveal")
var loginForm = document.querySelector(".loginReveal")



signupForm.style.display = "none"
loginBtn.style.display = "none"
signupBtn.style.display = "none" 

signupBtn.addEventListener('click', showSignupForm = () => {
    signupForm.style.display = "block"
    loginBtn.style.display = "none"
    signupBtn.style.display = "none"
})

loginBtn.addEventListener('click', showLoginForm = () => {
    loginForm.style.display = "block"
    loginBtn.style.display = "none"
    signupBtn.style.display = "none"
})

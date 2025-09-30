$(document).ready(function() {
  $('#loginForm').submit(function(e) {
    e.preventDefault();

    const loginData = {
      email: $('#email').val().trim(),
      password: $('#password').val()
    };

    if (!loginData.email || !loginData.password) {
      alert('Please enter email and password.');
      return;
    }

    $.ajax({
      url: 'php/login.php',
      type: 'POST',
      data: loginData,
      dataType: 'json',
      success: function(response) {
        if (response.success) {
          alert('Login successfully!');
          // Save user info to localStorage (optional)
          localStorage.setItem('user', JSON.stringify(response.user));
         
          // Redirect after login
          window.location.href = 'profile.html';
        } else {
          alert('Login failed: ' + response.message);
        }
      },
      error: function(xhr) {
        console.log(xhr.responseText); // Shows PHP errors in console
        alert('Server error. Please try again later.');
      }
    });
  });
});

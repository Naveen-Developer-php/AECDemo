    $(document).ready(function () {
      $('#registerForm').submit(function (e) {
        e.preventDefault();

        const userData = {
          fullname: $('#fullname').val().trim(),
          email: $('#email').val().trim(),
          password: $('#password').val(),
          age: $('#age').val() || null,
          dob: $('#dob').val() || null,
          contact: $('#contact').val().trim() || null,
        };

        if (!userData.fullname || !userData.email || !userData.password) {
          alert('Please fill all required fields.');
          return;
        }

        $.ajax({
          url: 'php/register.php',
          type: 'POST',
          data: userData,
          dataType: 'json',
          success: function (response) {
            if (response.success) {
              alert('Registration successful! You can now login.');
              window.location.href = 'login.html';
            } else {
              alert('Error: ' + response.message);
            }
          },
          error: function (xhr) {
            console.log(xhr.responseText); // see actual error
            alert('Server error. Please try again later.');
          },
        });
      });
    });
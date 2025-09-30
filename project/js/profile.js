$(document).ready(function () {
  const user = JSON.parse(localStorage.getItem('user'));
  if (!user) {
    alert('Please login first.');
    window.location.href = 'login.html';
    return;
  }

  $('#fullname').val(user.fullname);
  $('#email').val(user.email);
  $('#age').val(user.age || '');
  $('#dob').val(user.dob || '');
  $('#contact').val(user.contact || '');

  $('#profileForm').submit(function(e) {
    e.preventDefault();

    const updatedData = {
      fullname: $('#fullname').val().trim(),
      email: $('#email').val().trim(),
      age: $('#age').val() || null,
      dob: $('#dob').val() || null,
      contact: $('#contact').val().trim() || null
    };

    $.ajax({
      url: 'php/profile.php',
      type: 'POST',
      data: updatedData,
      dataType: 'json',
      success: function(response) {
        if (response.success) {
          alert('Profile updated successfully!');
          localStorage.setItem('user', JSON.stringify(response.user));
        } else {
          alert('Update failed: ' + response.message);
        }
      },
      error: function(xhr) {
        console.log(xhr.responseText); // check for PHP errors
        alert('Server error. Please try again later.');
      }
    });
  });

  $('#logoutBtn').click(function () {
    localStorage.removeItem('user');
    window.location.href = 'login.html';
  });
});

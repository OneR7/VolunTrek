document.getElementById('loginForm').addEventListener('submit', function(event) {
    event.preventDefault();

    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'loginUser.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var response = JSON.parse(xhr.responseText);
            var responseDiv = document.getElementById('response');
            if (response.status === "success") {
                window.location = response.redirect; // Redirect to the specified page
            } else {
                responseDiv.innerHTML += "<p class='error'>" + response.message + "</p>";
            }
        }
    };

    var email = document.getElementById('email').value;
    var password = document.getElementById('password').value;
    xhr.send('email=' + encodeURIComponent(email) + '&password=' + encodeURIComponent(password));
});
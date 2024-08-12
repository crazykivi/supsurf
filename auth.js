document.getElementById('toggle-password-field').addEventListener('click', function (event) {
    event.preventDefault();
    var modal = document.getElementById('password-modal');
    modal.style.display = "block";
});

document.getElementsByClassName('close-auth')[0].addEventListener('click', function () {
    var modal = document.getElementById('password-modal');
    modal.style.display = "none";
});
window.addEventListener('click', function (event) {
    var modal = document.getElementById('password-modal');
    if (event.target == modal) {
        modal.style.display = "none";
    }
});

function togglePasswordVisibility() {
    var passwordInput = document.getElementById('password');
    if (passwordInput.type === "password") {
        passwordInput.type = "text";
    } else {
        passwordInput.type = "password";
    }
}

function authenticate() {
    var password = document.getElementById('password').value;

    // Хешируем пароль перед отправкой
    var encoder = new TextEncoder();
    var data = encoder.encode(password);
    crypto.subtle.digest('SHA-256', data).then(function (hashBuffer) {
        var hashArray = Array.from(new Uint8Array(hashBuffer));
        var hashHex = hashArray.map(b => b.toString(16).padStart(2, '0')).join('');

        // Теперь отправляем хеш пароля
        var formData = new FormData();
        formData.append('password', hashHex);

        var xhr = new XMLHttpRequest();
        xhr.open("POST", "functions/auth.php", true);
        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4 && xhr.status == 200) {
                location.href = xhr.responseText; // Перенаправление на URL, полученный от сервера
            }
        };
        xhr.send(formData);
    }).catch(function (err) {
        console.error("Ошибка хэширования:", err);
    });
}
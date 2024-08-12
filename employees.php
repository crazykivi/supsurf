<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Сотрудники</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 20px;
            position: relative;
        }

        h1 {
            text-align: center;
        }

        .employee-form {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
        }

        .employee-form input,
        .employee-form button {
            display: block;
            width: 100%;
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }

        .employee-form button {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .employee-form button:hover {
            background-color: #45a049;
        }

        .employee-table {
            width: 100%;
            border-collapse: collapse;
        }

        .employee-table th,
        .employee-table td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        .employee-table th {
            background-color: #f2f2f2;
            text-align: left;
        }

        .top-left {
            position: absolute;
            top: 20px;
            left: 20px;
        }

        .button.left {
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 10px 20px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .button.left:hover {
            background-color: #45a049;
        }

        .top-right {
            position: absolute;
            top: 20px;
            right: 20px;
        }

        .button.right {
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 10px 20px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .button.right:hover {
            background-color: #45a049;
        }

        .delete-button {
            background-color: #ff4d4d;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 5px 10px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .delete-button:hover {
            background-color: #e60000;
        }

        .form-container {
            display: none;
        }

        .form-container.active {
            display: block;
        }

        .form-container {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
            display: none;
        }

        .form-container.active {
            display: block;
        }

        .form-container input,
        .form-container button {
            display: block;
            width: 100%;
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }

        .form-container button {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .form-container button:hover {
            background-color: #45a049;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .data-table th,
        .data-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .data-table th {
            background-color: #f2f2f2;
        }

        .delete-button {
            background-color: #ff4d4d;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 5px 10px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .delete-button:hover {
            background-color: #e60000;
        }

        .button-container {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        .switch-button {
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 10px 20px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
            margin: 0 10px;
        }

        .switch-button:hover {
            background-color: #45a049;
            transform: scale(1.05);
        }

        .switch-button:active {
            background-color: #3e8e41;
            transform: scale(1);
        }
    </style>
</head>

<body>
    <div class="top-left">
        <button class="button left" onclick="window.location.href='clientele.php'">Обратно</button>
    </div>
    <div class="top-right">
        <button class="button right" onclick="window.location.href='logout.php'">Выйти</button>
    </div>
    <h1>Добавление данных</h1>
    <div class="button-container">
        <button id="employeeButton" class="switch-button">Добавить сотрудника</button>
        <button id="userButton" class="switch-button">Добавить учетную запись</button>
    </div>


    <div id="employeeForm" class="form-container">
        <h2>Добавить нового сотрудника</h2>
        <input type="text" id="employeeName" placeholder="Имя сотрудника">
        <input type="text" id="employeePosition" placeholder="Должность">
        <button onclick="addEmployee()">Добавить сотрудника</button>
    </div>

    <div id="userForm" class="form-container">
        <h2>Добавить новую учетную запись</h2>
        <input type="text" id="username" placeholder="Имя пользователя">
        <input type="password" id="password" placeholder="Пароль">
        <button onclick="addUser()">Добавить учетную запись</button>
    </div>

    <h2>Список сотрудников</h2>
    <table class="employee-table">
        <thead>
            <tr>
                <th>Имя</th>
                <th>Должность</th>
                <th>Дата начала работы</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody id="employeesContainer">
            <!-- Сотрудники будут заполнены данными из вашей базы данных -->
        </tbody>
    </table>

    <h2>Список учетных записей</h2>
    <table class="employee-table">
        <thead>
            <tr>
                <th>Имя пользователя</th>
                <th>Дата создания</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody id="usersContainer">
            <!-- Учетные записи будут заполнены данными из вашей базы данных -->
        </tbody>
    </table>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            fetchEmployees();
            fetchUsers();
        });

        function fetchEmployees() {
            fetch('functions/get_employees.php')
                .then(response => response.json())
                .then(data => {
                    const container = document.getElementById('employeesContainer');
                    container.innerHTML = '';
                    data.forEach(employee => {
                        const employeeRow = document.createElement('tr');
                        const employeeHTML = `
                    <td>${employee.name}</td>
                    <td>${employee.position}</td>
                    <td>${employee.hire_date}</td>
                    <td><button class="delete-button" onclick="deleteEmployee(${employee.id})">Удалить</button></td>
                `;
                        employeeRow.innerHTML = employeeHTML;
                        container.appendChild(employeeRow);
                    });
                })
                .catch(error => console.error('Ошибка:', error));
        }

        document.addEventListener('DOMContentLoaded', () => {
            const employeeButton = document.getElementById('employeeButton');
            const userButton = document.getElementById('userButton');
            const employeeForm = document.getElementById('employeeForm');
            const userForm = document.getElementById('userForm');

            employeeButton.addEventListener('click', () => {
                employeeForm.classList.add('active');
                userForm.classList.remove('active');
            });

            userButton.addEventListener('click', () => {
                userForm.classList.add('active');
                employeeForm.classList.remove('active');
            });
        });

        function addEmployee() {
            const name = document.getElementById('employeeName').value;
            const position = document.getElementById('employeePosition').value;

            fetch('functions/add_employee.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        name,
                        position
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`Network response was not ok ${response.statusText}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        fetchEmployees();
                        document.getElementById('employeeName').value = '';
                        document.getElementById('employeePosition').value = '';
                    } else {
                        alert('Ошибка при добавлении сотрудника: ' + data.error);
                    }
                })
                .catch(error => console.error('Ошибка:', error));
        }

        function addUser() {
            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;

            // Хешируем пароль перед отправкой
            const encoder = new TextEncoder();
            const data = encoder.encode(password);
            crypto.subtle.digest('SHA-256', data).then(hashBuffer => {
                const hashArray = Array.from(new Uint8Array(hashBuffer));
                const hashHex = hashArray.map(b => b.toString(16).padStart(2, '0')).join('');

                // Теперь отправляем хеш пароля и имя пользователя
                const payload = JSON.stringify({
                    username,
                    password: hashHex
                });

                fetch('functions/add_user.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: payload
                    })
                    .then(response => response.json())
                    .then(result => {
                        if (result.success) {
                            alert('Учетная запись успешно добавлена');
                        } else {
                            alert('Ошибка: ' + result.error);
                        }
                    })
                    .catch(error => console.error('Error adding user:', error));
            }).catch(err => {
                console.error("Ошибка хэширования:", err);
            });
        }

        function deleteEmployee(id) {
            fetch('functions/delete_employee.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        id
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`Network response was not ok ${response.statusText}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        fetchEmployees();
                    } else {
                        alert('Ошибка при удалении сотрудника: ' + data.error);
                    }
                })
                .catch(error => console.error('Ошибка:', error));
        }


        function fetchUsers() {
            fetch('functions/get_users.php')
                .then(response => response.json())
                .then(data => {
                    const container = document.getElementById('usersContainer');
                    container.innerHTML = '';
                    data.forEach(user => {
                        const userRow = document.createElement('tr');
                        const userHTML = `
                    <td>${user.username}</td>
                    <td>${user.created_at}</td>
                    <td><button class="delete-button" onclick="deleteUser(${user.id})">Удалить</button></td>
                `;
                        userRow.innerHTML = userHTML;
                        container.appendChild(userRow);
                    });
                })
                .catch(error => console.error('Ошибка:', error));
        }

        function deleteUser(id) {
            fetch('functions/delete_user.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        id
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`Network response was not ok ${response.statusText}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        fetchUsers(); // Обновляем список учетных записей после удаления
                    } else {
                        alert('Ошибка при удалении учетной записи: ' + data.error);
                    }
                })
                .catch(error => console.error('Ошибка:', error));
        }
    </script>
</body>

</html>
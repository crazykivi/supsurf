<?php
session_start();

// Проверяем, установлена ли переменная сессии
if (!isset($_SESSION["authenticated"]) || $_SESSION["authenticated"] !== true) {
    // Если пользователь не аутентифицирован, перенаправляем его на главную страницу
    header("Location: http://localhost/index");
    exit;
}

// Подключение к базе данных
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "supsurf";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

/*
$sql = "SELECT r.id, r.name, r.status, b.date, r.phone, r.notes, r.created_at, b.status AS booking_status 
        FROM supsurf.registration r
        JOIN supsurf.bookings b ON r.booking_id = b.id
        WHERE b.date >= CURDATE()";
        */
$sql = "SELECT 
            r.id, 
            r.name, 
            r.status, 
            b.date, 
            r.phone, 
            r.notes, 
            r.created_at, 
            b.status AS booking_status,
            t.service
        FROM 
            supsurf.registration r
        JOIN 
            supsurf.bookings b ON r.booking_id = b.id
        JOIN 
            supsurf.types t ON b.idTypes = t.idTypes
        WHERE 
            b.date >= CURDATE()";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Информация брони</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #333;
            color: #fff;
            margin: 0;
            padding: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #555;
        }

        tr:hover {
            background-color: #555;
        }

        #modal {
            display: none;
            position: fixed;
            z-index: 2;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.7);
        }

        #modal-content {
            position: relative;
            background-color: #222;
            margin: 10% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
            animation-name: animatetop;
            animation-duration: 0.4s
        }

        @keyframes animatetop {
            from {
                top: -300px;
                opacity: 0
            }

            to {
                top: 0;
                opacity: 1
            }
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: #fff;
            text-decoration: none;
            cursor: pointer;
        }

        p {
            margin: 5px 0;
        }

        button {
            background-color: #353535;
            border: none;
            color: white;
            padding: 15px 32px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 12px;
            margin: 4px 2px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #585858;
        }

        #datePickerContainer,
        #bookingSlotsContainer {
            margin: 20px;
            padding: 10px;
        }

        #bookingSlotsTable {
            width: 100%;
            border-collapse: collapse;
        }

        #bookingSlotsTable th,
        #bookingSlotsTable td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }

        #bookingSlotsTable th {
            background-color: #f4f4f4;
        }

        .available {
            color: green;
        }

        .reserved {
            color: red;
        }

        .button {
            padding: 5px 10px;
            color: white;
            border: none;
            cursor: pointer;
        }

        .edit-button {
            background-color: #007BFF;
        }

        .edit-button:hover {
            background-color: #0056b3;
        }

        #bookingSlotsTable th {
            background-color: #555;
            color: white;
        }

        h1 {
            text-align: center;
        }

        .instructor {
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            padding: 20px;
            color: white;
            /*
            color:black;
            background-color: #fff;
            */
        }

        .instructor h2 {
            margin-top: 0;
            /* color: #333; */
        }

        .instructor ul {
            list-style: none;
            padding: 0;
        }

        .instructor li {
            margin: 10px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .instructor button {
            background-color: #ff4d4d;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 5px 10px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .instructor button:hover {
            background-color: #e60000;
        }

        .add-style {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
        }

        .add-style select,
        .add-style button {
            padding: 5px;
            margin-right: 10px;
        }

        .employee-form {
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
            text-align: left;
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
    </style>
</head>

<body>
    <div class="top-right">
        <button class="button" onclick="window.location.href='employees.php'">Список сотрудников</button>
    </div>
    <button class="button" onclick="window.location.href='functions/logout.php'">Выйти</button>
    <h1>Добро пожаловать! Здесь ваша информация о заявках.</h1>

    <h2>Таблица резервов</h2>
    <div id="datePickerContainer">
        <label for="datePicker">Выберите дату:</label>
        <input type="date" id="datePicker">
    </div>
    <div id="bookingSlotsContainer">
        <table id="bookingSlotsTable">
            <thead>
                <tr>
                    <th>Время</th>
                    <th>Статус</th>
                    <th>Действие</th>
                </tr>
            </thead>
            <tbody id="bookingSlotsBody"></tbody>
        </table>
    </div>
    <h2>Таблица заявок</h2>
    <table id="requestTable">
        <tr>
            <th>Имя</th>
            <th>Статус</th>
            <th>Тип прогулки</th>
            <th>Дата</th>
        </tr>
        <?php
        while ($row = $result->fetch_assoc()) {
            echo "<tr onclick='showModal(" . json_encode($row) . ")'>
                <td>" . htmlspecialchars($row['name']) . "</td>
                <td>" . htmlspecialchars($row['status']) . "</td>
                <td>" . htmlspecialchars($row['service']) . "</td>
                <td>" . htmlspecialchars($row['date']) . "</td>
              </tr>";
        }
        ?>
    </table>

    <div id="modal">
        <div id="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <p id="detail"></p>
        </div>
    </div>
    <h1>Инструкторы и их стили</h1>
    <div id="instructors-container"></div>
    <h1>Акции</h1>
    <div class="promotion-form">
        <h2>Добавить новую акцию</h2>
        <input type="text" id="promotionName" placeholder="Название акции">
        <input type="date" id="startDate" placeholder="Дата начала">
        <input type="date" id="endDate" placeholder="Дата окончания">
        <textarea id="description" placeholder="Описание акции"></textarea>
        <select id="employeeSelect">
            <!-- Опции будут заполнены данными из вашей базы данных -->
        </select>
        <button onclick="addPromotion()">Добавить акцию</button>
    </div>

    <table class="promotion-table">
        <thead>
            <tr>
                <th>Название акции</th>
                <th>Дата начала</th>
                <th>Дата окончания</th>
                <th>Описание</th>
                <th>Ответственный</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody id="promotionsContainer">
            <!-- Акции будут заполнены данными из вашей базы данных -->
        </tbody>
    </table>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            fetchPromotions();
            fetchEmployees();
        });

        function fetchPromotions() {
            fetch('functions/get_promotions_panel.php')
                .then(response => response.json())
                .then(data => {
                    const container = document.getElementById('promotionsContainer');
                    container.innerHTML = '';
                    data.forEach(promotion => {
                        const promoRow = document.createElement('tr');
                        const promoHTML = `
                    <td>${promotion.promotion_name}</td>
                    <td>${promotion.start_date}</td>
                    <td>${promotion.end_date}</td>
                    <td>${promotion.description}</td>
                    <td>${promotion.employee_name}</td>
                    <td>
                        <button onclick="removePromotion(${promotion.promotion_id}, ${promotion.employee_id})">Удалить</button>
                    </td>
                `;
                        promoRow.innerHTML = promoHTML;
                        container.appendChild(promoRow);
                    });
                })
                .catch(error => console.error('Ошибка:', error));
        }

        function fetchEmployees() {
            fetch('functions/get_employees_panel.php')
                .then(response => response.json())
                .then(data => {
                    const select = document.getElementById('employeeSelect');
                    select.innerHTML = '';
                    data.forEach(employee => {
                        const option = document.createElement('option');
                        option.value = employee.id;
                        option.textContent = employee.name;
                        select.appendChild(option);
                    });
                })
                .catch(error => console.error('Ошибка:', error));
        }

        function addPromotion() {
            const promotionName = document.getElementById('promotionName').value;
            const startDate = document.getElementById('startDate').value;
            const endDate = document.getElementById('endDate').value;
            const description = document.getElementById('description').value;
            const employeeId = document.getElementById('employeeSelect').value;

            fetch('functions/add_promotion_panel.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        promotionName,
                        startDate,
                        endDate,
                        description,
                        employeeId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        fetchPromotions();
                        document.getElementById('promotionName').value = '';
                        document.getElementById('startDate').value = '';
                        document.getElementById('endDate').value = '';
                        document.getElementById('description').value = '';
                    } else {
                        alert('Ошибка при добавлении акции');
                    }
                })
                .catch(error => console.error('Ошибка:', error));
        }

        function removePromotion(promotionId, employeeId) {
            fetch('functions/remove_promotion_panel.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        promotionId,
                        employeeId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        fetchPromotions();
                    } else {
                        alert('Ошибка при удалении акции');
                    }
                })
                .catch(error => console.error('Ошибка:', error));
        }
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            fetchInstructors();
        });

        function fetchInstructors() {
            fetch('functions/get_instructors_panel.php')
                .then(response => response.json())
                .then(data => {
                    const container = document.getElementById('instructors-container');
                    container.innerHTML = '';
                    data.forEach(instructor => {
                        const instructorDiv = document.createElement('div');
                        instructorDiv.className = 'instructor';

                        const typesList = (instructor.types || []).map(type => `
                    <li>
                        ${type.name}
                        <button onclick="removeType(${instructor.id}, ${type.id})">Удалить</button>
                    </li>
                `).join('');

                        const instructorHTML = `
                    <h2>${instructor.name}</h2>
                    <ul>
                        ${typesList}
                    </ul>
                    <div class="add-style">
                        <select id="typeSelect-${instructor.id}">
                            <!-- Опции будут заполнены данными из вашей базы данных -->
                        </select>
                        <button onclick="addType(${instructor.id})">Добавить стиль</button>
                    </div>
                `;
                        instructorDiv.innerHTML = instructorHTML;
                        container.appendChild(instructorDiv);
                        fetchTypes(instructor.id);
                    });
                })
                .catch(error => console.error('Ошибка:', error));
        }

        function fetchTypes(instructorId) {
            fetch('functions/get_types.php')
                .then(response => response.json())
                .then(data => {
                    const select = document.getElementById(`typeSelect-${instructorId}`);
                    select.innerHTML = ''; // Очищаем существующие опции перед добавлением новых
                    data.forEach(type => {
                        const option = document.createElement('option');
                        option.value = type.idTypes;
                        option.textContent = type.service;
                        select.appendChild(option);
                    });
                })
                .catch(error => console.error('Ошибка:', error));
        }

        function removeType(instructorId, typeId) {
            fetch('functions/remove_type.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        instructorId,
                        typeId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        fetchInstructors();
                    } else {
                        alert('Ошибка при удалении стиля');
                    }
                })
                .catch(error => console.error('Ошибка:', error));
        }

        function addType(instructorId) {
            const select = document.getElementById(`typeSelect-${instructorId}`);
            const typeId = select.value;

            fetch('functions/add_type.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        instructorId,
                        typeId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        fetchInstructors();
                    } else {
                        alert('Ошибка при добавлении стиля');
                    }
                })
                .catch(error => console.error('Ошибка:', error));
        }
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const datePicker = document.getElementById('datePicker');
            const bookingSlotsBody = document.getElementById('bookingSlotsBody');

            datePicker.addEventListener('change', () => {
                fetch(`functions/get_bookings_admin.php?date=${datePicker.value}`)
                    .then(response => response.json())
                    .then(bookings => {
                        updateBookingSlots(bookings);
                    })
                    .catch(error => console.error('Error fetching bookings:', error));
            });

            function updateBookingSlots(bookings) {
                bookingSlotsBody.innerHTML = '';

                bookings.forEach(booking => {
                    const row = document.createElement('tr');
                    const timeCell = document.createElement('td');
                    const statusCell = document.createElement('td');
                    const actionCell = document.createElement('td');

                    timeCell.textContent = new Date(booking.date).toLocaleTimeString('ru-RU', {
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                    statusCell.textContent = booking.status;
                    statusCell.className = booking.status.toLowerCase();

                    const toggleButton = document.createElement('button');
                    toggleButton.textContent = 'Переключить статус';
                    toggleButton.className = 'button edit-button';
                    toggleButton.addEventListener('click', () => toggleBookingStatus(booking.id, booking.status));

                    actionCell.appendChild(toggleButton);

                    row.appendChild(timeCell);
                    row.appendChild(statusCell);
                    row.appendChild(actionCell);

                    bookingSlotsBody.appendChild(row);
                });
            }

            function toggleBookingStatus(bookingId, currentStatus) {
                const newStatus = currentStatus === 'Available' ? 'Reserved' : 'Available';

                fetch(`functions/edit_booking_status.php`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            bookingId: bookingId,
                            status: newStatus
                        })
                    })
                    .then(response => response.json())
                    .then(result => {
                        alert(result.message);
                        datePicker.dispatchEvent(new Event('change'));
                    })
                    .catch(error => console.error('Error editing booking status:', error));
            }
        });
    </script>
    <script>
        function showModal(data) {
            document.getElementById('detail').innerHTML = '<p><strong>Имя:</strong> ' + data.name +
                '</p><p><strong>Статус:</strong> ' + data.status +
                '</p><p><strong>Тип прогулки:</strong> ' + data.service +
                '</p><p><strong>Дата:</strong> ' + data.date +
                '</p><p><strong>Телефон:</strong> ' + data.phone +
                '</p><p><strong>Заметки:</strong> ' + data.notes +
                '</p><p><strong>Дата создания:</strong> ' + data.created_at +
                '</p><p><strong>Статус бронирования:</strong> ' + data.booking_status +
                '</p><select id="newStatus">' +
                '<option value="Бронь подтверждена">Бронь подтверждена</option>' +
                '<option value="Бронь отменена">Бронь отменена</option>' +
                '<option value="Нет ответа">Нет ответа</option>' +
                '</select>' +
                '<button onclick="updateStatus(' + data.id + ')">Обновить статус</button>';
            document.getElementById('modal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('modal').style.display = 'none';
        }

        function updateStatus(registrationId) {
            var newStatus = document.getElementById('newStatus').value;
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "functions/update_status.php", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.onload = function() {
                if (xhr.status >= 200 && xhr.status < 300) {
                    closeModal();
                    loadData();
                } else {
                    alert('Ошибка обновления статуса');
                }
            };
            xhr.send("id=" + registrationId + "&status=" + newStatus);
        }

        function loadData() {
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "functions/fetch_data.php", true);
            xhr.onload = function() {
                if (xhr.status >= 200 && xhr.status < 300) {
                    document.getElementById('requestTableBody').innerHTML = html;
                } else {
                    alert('Ошибка загрузки данных');
                }
            };
            xhr.send();
        }
    </script>

</body>

</html>
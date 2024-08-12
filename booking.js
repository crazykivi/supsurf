document.addEventListener('DOMContentLoaded', () => {
    const datePicker = document.getElementById('datePicker');
    const leftArrow = document.getElementById('leftArrow');
    const rightArrow = document.getElementById('rightArrow');
    const dataBody = document.getElementById('dataBody');
    const bookingModal = document.getElementById('bookingModal');
    const closeModal = document.querySelector('.close');
    const bookingForm = document.getElementById('bookingForm');
    const bookingDatetime = document.getElementById('bookingDatetime');
    const bookingId = document.getElementById('bookingId');
    const nameInput = document.getElementById('name');
    const phoneInput = document.getElementById('phone');

    let currentDate = new Date();

    const formatDate = date => date.toISOString().slice(0, 10);

    const fetchData = date => {
        dataBody.innerHTML = '';

        fetch(`functions/get_data.php?date=${date}`)
            .then(response => response.json())
            .then(data => {
                if (data.length === 0) {

                    dataBody.innerHTML = `<tr><td colspan="2">В этот день записей нету</td></tr>`;
                    return;
                }

                data.forEach(item => {
                    const row = document.createElement('tr');

                    const timeCell = document.createElement('td');
                    const actionCell = document.createElement('td');

                    const dateTime = new Date(item.date);
                    const hours = dateTime.getHours().toString().padStart(2, '0');
                    const minutes = dateTime.getMinutes().toString().padStart(2, '0');

                    timeCell.textContent = `${hours}:${minutes} ${item.service}`;
                    actionCell.innerHTML = `<a href="#" data-id="${item.id}" data-datetime="${dateTime}">Оставить резерв</a>`;

                    row.appendChild(timeCell);
                    row.appendChild(actionCell);

                    dataBody.appendChild(row);
                });

                document.querySelectorAll('#dataTable a').forEach(link => {
                    link.addEventListener('click', event => {
                        event.preventDefault();

                        const id = link.getAttribute('data-id');
                        const datetime = new Date(link.getAttribute('data-datetime'));

                        const formattedMinutes = datetime.getMinutes().toString().padStart(2, '0');

                        bookingId.value = id;
                        bookingDatetime.textContent = `${datetime.getDate()} ${datetime.toLocaleDateString('ru-RU', {
                            month: 'long',
                        })} ${datetime.getFullYear()} в ${datetime.getHours()}:${formattedMinutes}`;

                        bookingModal.style.display = 'block';
                    });
                });
            })
            .catch(error => console.error('Error fetching data:', error));
    };

    const updateDisplay = () => {
        datePicker.value = formatDate(currentDate);
        fetchData(formatDate(currentDate));
    };

    datePicker.addEventListener('change', () => {
        currentDate = new Date(datePicker.value);
        updateDisplay();
    });

    leftArrow.addEventListener('click', () => {
        currentDate.setDate(currentDate.getDate() - 1);
        updateDisplay();
    });

    rightArrow.addEventListener('click', () => {
        currentDate.setDate(currentDate.getDate() + 1);
        updateDisplay();
    });

    closeModal.addEventListener('click', () => {
        bookingModal.style.display = 'none';
    });

    window.addEventListener('click', event => {
        if (event.target === bookingModal) {
            bookingModal.style.display = 'none';
        }
    });

    bookingForm.addEventListener('submit', event => {
        event.preventDefault();

        const formData = new FormData(bookingForm);

        fetch('functions/reserve.php', {
            method: 'POST',
            body: formData,
        })
            .then(response => response.json())
            .then(result => {
                alert(result.message);
                bookingModal.style.display = 'none';
                updateDisplay();
            })
            .catch(error => console.error('Error submitting form:', error));
    });

    nameInput.addEventListener('input', () => {
        nameInput.value = nameInput.value.replace(/[^a-zA-Zа-яА-Я\s]/g, '');
    });

    phoneInput.addEventListener('focus', () => {
        if (!/^\+\d*$/.test(phoneInput.value))
            phoneInput.value = '+7';
    });

    phoneInput.addEventListener('keypress', e => {
        if (!/\d/.test(e.key))
            e.preventDefault();
    });

    updateDisplay();
});


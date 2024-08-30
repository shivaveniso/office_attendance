document.addEventListener('DOMContentLoaded', function () {
    const calendarContainer = document.getElementById('calendar');
    const popup = document.getElementById('popup');
    const popupDate = document.getElementById('popup-date');
    let selectedDate;

    // Fetch attendance data
    fetch('get_attendance.php')
        .then(response => response.json())
        .then(data => renderCalendar(data));

    function renderCalendar(attendanceData) {
        const today = new Date();
        const currentYear = today.getFullYear();
        const currentMonth = today.getMonth(); // 0-based
        const firstDay = new Date(currentYear, currentMonth, 1);
        const lastDay = new Date(currentYear, currentMonth + 1, 0);
        const numDays = lastDay.getDate();

        let calendarHTML = '';

        for (let day = 1; day <= numDays; day++) {
            const date = `${currentYear}-${String(currentMonth + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
            const dayOfWeek = new Date(currentYear, currentMonth, day).getDay();
            const isToday = date === today.toISOString().split('T')[0];
            const status = attendanceData[date];
            const isWeekend = dayOfWeek === 0 || dayOfWeek === 6;

            let className = 'calendar-day';
            let statusDot = '';

            if (isWeekend) {
                className += ' weekend';
            } else if (status === 'present') {
                className += ' present';
                statusDot = '<span class="status-dot present"></span>';
            } else if (status === 'absent') {
                className += ' absent';
                statusDot = '<span class="status-dot absent"></span>';
            }

            if (isToday) {
                className += ' today';
            }

            calendarHTML += `
                <div class="${className}" data-date="${date}">
                    ${day}${statusDot}
                </div>
            `;
        }

        calendarContainer.innerHTML = calendarHTML;

        // Add event listeners
        calendarContainer.querySelectorAll('.calendar-day').forEach(day => {
            day.addEventListener('click', function () {
                selectedDate = this.dataset.date;
                popupDate.textContent = selectedDate;
                popup.classList.add('active');
            });
        });
    }

    window.markAttendance = function () {
        fetch('mark_attendance.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({ date: selectedDate })
        })
            .then(response => response.text())
            .then(message => {
                alert(message);
                popup.classList.remove('active');
                // Refresh calendar
                fetch('get_attendance.php')
                    .then(response => response.json())
                    .then(data => renderCalendar(data));
            });
    };

    window.unmarkAttendance = function () {
        fetch('unmark_attendance.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({ date: selectedDate })
        })
            .then(response => response.text())
            .then(message => {
                alert(message);
                popup.classList.remove('active');
                // Refresh calendar
                fetch('get_attendance.php')
                    .then(response => response.json())
                    .then(data => renderCalendar(data));
            });
    };

    window.closePopup = function () {
        popup.classList.remove('active');
    };

    window.logout = function () {
        fetch('logout.php')
            .then(response => {
                if (response.redirected) {
                    window.location.href = response.url; // Redirect to the login page with a success message
                }
            });
    };
});

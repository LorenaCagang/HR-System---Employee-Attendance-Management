@extends('layouts.sidebar')

@section('title', 'Dashboard')
@push('styles')
  <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@endpush

@section('content')
  <div class="top-left-logo">
    <img src="{{ asset('company/logo.png') }}" alt="Logo" />
  </div>

  <div class="dashboard">
    <div class="top-row d-flex justify-content-between align-items-start">
      <div class="greeting-text">
        Hello {{ auth()->user()->name ?? 'User' }}
        <div class="date-time d-flex align-items-center gap-2">
          <span class="current-date">{{ date('F d, Y') }}</span>

          <span class="current-time d-flex align-items-center gap-1">
            <i class="bi bi-clock"></i>
            <span id="live-time">08:45 AM</span>
          </span>
<!-- Date Filter -->
<form method="GET" action="{{ route('dashboard') }}" id="date-filter-form">
  <div class="custom-date-wrapper">
    <input
  type="date"
  id="filter-date"
  name="filter_date"
  class="form-control form-control-sm"
  value="{{ request('filter_date', date('Y-m-d')) }}" 
/>

  </div>
</form>

        </div>
      </div>

      <div class="stats-summary d-flex mb-3 align-items-start">
        <div class="stat-item d-flex align-items-center">
          <i
            class="bi bi-people-fill text-dark"
            style="margin-left: 1.5rem; position: relative; left: 1.5rem;"
          ></i>

          <div>
          <div class="stat-number" style="margin-left: 1.5rem;">{{ $present_count }}</div>

            <div class="stat-label small text-muted">Total Employees Present</div>
          </div>
        </div>
        <div class="stat-item d-flex align-items-center gap-2">
          <i
            class="bi bi-person-badge-fill text-dark"
            style="margin-left: 1.5rem; position: relative; left: 1.5rem;"
          ></i>
          <div>
    <div class="stat-number" style="margin-left: 1.5rem;">{{ $colleagues_present }}</div>
    <div class="stat-label small text-muted">Colleagues</div>
  </div>
</div>
        <div class="stat-item d-flex align-items-center gap-2">
          <i
            class="bi bi-person-vcard-fill text-dark"
            style="margin-left: 1.5rem; position: relative; left: 1.5rem;"
          ></i>
          <div>
            <div class="stat-number" style="margin-left: 2.5rem;">{{ $head_present }}</div>
            <div class="stat-label small text-muted">Dept. Heads</div>
          </div>


        </div>
      </div>
    </div>

    <div class="schedule-box">
  <div class="schedule-inner">

    <div class="schedule-header d-flex align-items-center justify-content-between mb-3">
      <div class="d-flex align-items-center gap-2">
        <h5 class="schedule-title mb-0">Schedule</h5>
        <div class="d-flex gap-2">
          <select class="form-select form-select-sm" id="schedule-month">
            @for ($m = 1; $m <= 12; $m++)
              <option value="{{ $m }}" {{ $m == date('n') ? 'selected' : '' }}>
                {{ date('F', mktime(0, 0, 0, $m, 10)) }}
              </option>
            @endfor
          </select>
          <select class="form-select form-select-sm" id="schedule-year">
            @php $year = date('Y'); @endphp
            @for ($i = $year - 2; $i <= $year + 2; $i++)
              <option value="{{ $i }}" {{ $i == $year ? 'selected' : '' }}>
                {{ $i }}
              </option>
            @endfor
          </select>
        </div>
      </div>

      <button class="plus-btn" id="openModalBtn">
        <i class="bi bi-plus-lg"></i>
      </button>
    </div>

<!-- Timeline area -->
<div class="schedule-scroll">
  <div class="schedule-days-scroll">
    <div class="schedule-days mb-3"></div>
  </div>
  <div class="schedule-timeline" id="scheduleTimeline">
    <!-- Default schedule list rendered here -->
   <div id="allSchedules">
  

  </div>
</div>
</div>
</div>
</div>

<div class="employee-table-wrapper">
<div class="employee-table-box">
  <div class="employee-header d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">Employees</h5>
    <form method="GET" action="{{ route('dashboard') }}" class="d-flex gap-2 m-0 align-items-center">
  <select
    class="form-select form-select-sm"
    name="department_id"
    onchange="this.form.submit()"
    style="min-width: 250px; border-radius: 2rem;"
  >
    <option value="">All Departments</option>
    @foreach ($departments as $department)
      <option value="{{ $department->id }}" {{ request('department_id') == $department->id ? 'selected' : '' }}>
        {{ $department->name }}
      </option>
    @endforeach
  </select>

  <input
    type="text"
    name="search"
    class="form-control form-control-sm search-with-icon"
    placeholder="Search..."
    value="{{ request('search') }}"
    style="min-width: 160px; border-radius: 2rem; padding-left: 2.5rem;"
  />

  <!-- Optional: Submit button if you want (can be hidden if you want auto-submit) -->
  <button type="submit" class="btn btn-primary d-none">Search</button>

  <i
        class="bi bi-arrow-up-right-circle-fill view-more-icon"
      ></i>
</form>

  </div>



    <!-- Table -->
    <div class="table-responsive">
      <table class="table custom-employee-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Date</th>
            <th>Time-In</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
        @forelse ($employee_rows as $emp)
  <tr class="employee-row"
      data-id="{{ $emp['id'] }}"
      data-name="{{ $emp['name'] }}"
      data-present="{{ $emp['present_days'] ?? 0 }}"
      data-absent="{{ $emp['absent_days'] ?? 0 }}">
    <td>{{ $emp['id'] }}</td>
    <td>
      <div class="name-cell">
  <img
    src="{{ asset('storage/' . ($emp['photo'] ?? 'default.jpg')) }}"
    alt="{{ $emp['name'] }}"
    class="profile-pic"
  />
  {{ $emp['name'] }}
</div>

    </td>
    <td>{{ $emp['date'] }}</td>
    <td>{{ $emp['time_in'] ?? '—' }}</td>
    <td>{{ $emp['status'] }}</td>
  </tr>
@empty
  <tr>
    <td colspan="5" class="text-center">No employees found.</td>
  </tr>
@endforelse

        </tbody>
      </table>
      <nav aria-label="Page navigation" class="d-flex justify-content-center mt-3">
  {{ $attendance_query->appends(request()->query())->links('pagination::bootstrap-5') }}
</nav>

    </div>
  </div>
</div>


    <!-- Added new blocks here -->
    <div class="attendance-report-wrapper">
  <h2 class="attendance-report-title">Attendance Report</h2>
  
  <div class="attendance-stats">
    <div class="attendance-indicator">
      <div class="indicator-icon">&#8598;</div>
      <div class="indicator-details">
        <div class="indicator-label"></div>
        <div class="indicator-value"></div>
      </div>
    </div>
    <div class="attendance-indicator">
      <div class="indicator-icon">&#8600;</div>
      <div class="indicator-details">
        <div class="indicator-label"></div>
        <div class="indicator-value"></div>
      </div>
    </div>
  </div>
  <div id="indicator-label-message" class="indicator-label-message">
    Please click an employee to view attendance report
  </div>
  <div id="attendance-chart" class="attendance-chart"></div>
</div>


  <!-- Chart Container -->
<div class="timeliness-graph-wrapper">
  <div class="graph-header">
    <h3>Timeliness Overview</h3>
    <div class="graph-controls">
      <select class="year-select">
        @php
          $currentYear = now()->year;
        @endphp

        @for ($i = 0; $i <= 5; $i++)
          @php
            $year = $currentYear - $i;
          @endphp
          <option value="{{ $year }}" {{ isset($selectedYear) && $selectedYear == $year ? 'selected' : '' }}>
            {{ $year }}
          </option>
        @endfor
      </select>

      <div class="icon-circle">
        <span class="arrow-icon">&#8599;</span> 
      </div>
    </div>
  </div>

  <div id="timeliness-chart"></div>
</div>

<div class="employee-composition-wrapper">
  <h2 class="composition-title">Employee Composition</h2>
  <div id="employee-composition-chart" class="composition-chart"></div>
  <div class="composition-percentages">
    <div class="percentage-item">
      <span class="bullet female"></span>
      <span class="percent">{{ $female_percent }}%</span>
      <div class="label-box">
        <i class="fas fa-female"></i> Female
      </div>
    </div>
    <div class="percentage-item">
      <span class="bullet male"></span>
      <span class="percent">{{ $male_percent }}%</span>
      <div class="label-box">
        <i class="fas fa-male"></i> Male
      </div>
    </div>
  </div>
</div>


   <!-- Schedule Modal -->
<div id="customModal" class="custom-modal-overlay">
  <div class="custom-modal-content">
    <form method="POST" action="{{ route('schedule.store') }}">
      @csrf

      <div class="custom-modal-header">
        <div class="modal-title">
          <h5>Schedule</h5>
          <div class="icon-circle">
            <i class="bi bi-plus-lg text-white"></i>
          </div>
        </div>
        <button type="button" class="close-btn" onclick="closeModal()">×</button>
      </div>

      <div class="custom-modal-body">
        <label for="title">Title:</label>
        <input type="text" name="title" id="title" class="form-control mb-2" required />

        <label for="description">Description:</label>
        <textarea name="description" id="description" class="form-control mb-2"></textarea>

        <div class="d-flex gap-2 mb-2">
          <div class="flex-fill">
            <label for="schedule_date">Date:</label>
            <input type="date" name="schedule_date" id="schedule_date" class="form-control" required />
          </div>
          <div class="flex-fill">
            <label for="schedule_time">Time:</label>
            <input type="time" name="schedule_time" id="schedule_time" class="form-control" required />
          </div>
        </div>

        <div class="text-center">
          <button type="submit" class="submit-btn">Submit</button>
        </div>
      </div>
    </form>
  </div>
</div>


  @push('scripts')
  <script>
  const schedules = @json($schedules);
  const daysOfWeek = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];

  function generateDays(month, year) {
    const lastDay = new Date(year, month, 0).getDate();
    let daysHtml = '';

    for (let day = 1; day <= lastDay; day++) {
      const tempDate = new Date(year, month - 1, day);
      const dayName = daysOfWeek[tempDate.getDay()];
      const today = new Date();
      const isToday = day === today.getDate() && month === today.getMonth() + 1 && year === today.getFullYear();

      daysHtml += `
        <div class="day-box ${isToday ? 'selected' : ''}" data-day="${day}">
          <div class="day-name">${dayName}</div>
          <div class="day-number">${day}</div>
        </div>
      `;
    }

    document.querySelector(".schedule-days").innerHTML = daysHtml;
    
  // Center today if found
  const container = document.querySelector(".schedule-days-scroll");
  const todayBox = document.querySelector(".day-box.selected");

  if (container && todayBox) {
    const boxCenter = todayBox.offsetLeft + todayBox.offsetWidth / 2;
    const containerCenter = container.clientWidth / 2;
    container.scrollLeft = boxCenter - containerCenter;
  }

    // Add click events to each day box
    document.querySelectorAll(".day-box").forEach(dayBox => {
      dayBox.addEventListener('click', () => {
        document.querySelectorAll(".day-box").forEach(el => el.classList.remove('selected'));
        dayBox.classList.add('selected');
        const selectedDay = parseInt(dayBox.dataset.day);
        filterSchedulesByDay(selectedDay, month, year);
      });
    });

    // On initial load, display schedules of the selected day (today or first day)
    let initialSelectedDay = 1;
    const selectedDayBox = document.querySelector(".day-box.selected");
    if (selectedDayBox) {
      initialSelectedDay = parseInt(selectedDayBox.dataset.day);
    }
    filterSchedulesByDay(initialSelectedDay, month, year);
  }

  function filterSchedulesByDay(day, month, year) {
    // Construct selected date string directly in yyyy-mm-dd format to avoid timezone shift
    const selectedDate = `${year}-${String(month).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
    const scheduleContainer = document.getElementById('allSchedules');

    // Clear existing schedules
    scheduleContainer.innerHTML = '';

    // Filter schedules for the selected date by string comparison
    const filteredSchedules = schedules.filter(schedule => {
      return schedule.schedule_date === selectedDate;
    });

    if (filteredSchedules.length === 0) {
      scheduleContainer.innerHTML = '<p>No schedules found for this date.</p>';
      return;
    }

    // Render filtered schedules with delete buttons
    filteredSchedules.forEach(schedule => {
      // Format time safely (assume schedule.schedule_time is "HH:mm:ss")
      const [hour, minute, second] = schedule.schedule_time.split(':');
      const timeFormatted = new Date(1970, 0, 1, hour, minute, second || 0)
        .toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', hour12: true });

      // Format date for display (safe local date formatting)
      const [y, m, d] = schedule.schedule_date.split('-');
      const dateFormatted = new Date(y, m - 1, d)
        .toLocaleDateString(undefined, { year: 'numeric', month: 'long', day: 'numeric' });

      const scheduleCard = document.createElement('div');
      scheduleCard.className = 'd-flex mb-3 p-3 border align-items-start schedule-card custom-rounded';

      scheduleCard.dataset.date = schedule.schedule_date;

      scheduleCard.innerHTML = `
    <div style="min-width: 90px; font-weight: 600; color: white; padding-right: 12px; border-right: 1px solid #ddd;">
      ${timeFormatted}
    </div>
    <div class="ps-3 flex-grow-1">
<small class="d-block mb-1" style="color: #E0A800;">${dateFormatted}</small>

<h6 class="mb-1 text-white">${schedule.title}</h6>
${schedule.description ? `<p class="mb-0 text-white">${schedule.description}</p>` : ''}

    </div>
    <button class="ms-3 delete-schedule-btn"
            style="background: transparent; border: none; color: white; padding: 0;"
            data-id="${schedule.id}" title="Delete Schedule">
      <i class="bi bi-trash"></i>
    </button>
  `;


      scheduleContainer.appendChild(scheduleCard);
    });

    // Attach delete event listeners
    attachDeleteListeners();
  }

  function attachDeleteListeners() {
    // Read CSRF token from meta tag
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    document.querySelectorAll('.delete-schedule-btn').forEach(button => {
      button.addEventListener('click', function () {
        const scheduleId = this.dataset.id;

        Swal.fire({
          title: 'Delete this schedule?',
          text: "This action cannot be undone.",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#e3342f',
          confirmButtonText: 'Yes, delete it!',
          cancelButtonText: 'Cancel'
        }).then((result) => {
          if (result.isConfirmed) {
            fetch(`/schedules/${scheduleId}`, {
              method: 'DELETE',
              headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
              }
            })
            .then(res => {
              if (!res.ok) throw new Error('Network response was not ok');
              return res.json();
            })
            .then(data => {
              if (data.success) {
                // Remove deleted schedule card from DOM
                const card = document.querySelector(`.delete-schedule-btn[data-id="${scheduleId}"]`)?.closest('.schedule-card');
                if (card) card.remove();

                Swal.fire('Deleted!', 'The schedule was deleted.', 'success');
              } else {
                Swal.fire('Error', data.message || 'Unable to delete schedule.', 'error');
              }
            })
            .catch(() => {
              Swal.fire('Error', 'Something went wrong.', 'error');
            });
          }
        });
      });
    });
  }

  // Initial load
  window.addEventListener('DOMContentLoaded', () => {
    const month = parseInt(document.getElementById('schedule-month').value);
    const year = parseInt(document.getElementById('schedule-year').value);
    generateDays(month, year);
  });
  // Month/year change triggers regeneration
  document.getElementById('schedule-month').addEventListener('change', () => {
    const month = parseInt(document.getElementById('schedule-month').value);
    const year = parseInt(document.getElementById('schedule-year').value);
    generateDays(month, year);
  });

  document.getElementById('schedule-year').addEventListener('change', () => {
    const month = parseInt(document.getElementById('schedule-month').value);
    const year = parseInt(document.getElementById('schedule-year').value);
    generateDays(month, year);
  });


document.addEventListener('DOMContentLoaded', function () {
  const employeeRows = document.querySelectorAll('.employee-row');
  const chartContainer = document.getElementById('attendance-chart');
  const presentValue = document.querySelector('.attendance-indicator .indicator-value');
  const absentValue = document.querySelectorAll('.attendance-indicator .indicator-value')[1];
  const labelMessage = document.getElementById('indicator-label-message'); // Get the label div

  employeeRows.forEach(row => {
    row.addEventListener('click', function () {
      const present = parseInt(this.dataset.present);
      const absent = parseInt(this.dataset.absent);
      const total = present + absent;

      // Hide the label message when an employee is clicked
      if (labelMessage) {
        labelMessage.style.display = 'none';
      }

      // Update stats
      presentValue.textContent = present;
      absentValue.textContent = absent;

      // Clear existing chart
      chartContainer.innerHTML = '';

      // Draw new attendance circles
      for (let i = 0; i < total; i++) {
        const circle = document.createElement('div');
        circle.classList.add('attendance-circle');

        if (i < present) {
          circle.classList.add('present'); // yellow
        } else {
          circle.classList.add('absent'); // gray
        }

        chartContainer.appendChild(circle);
      }
    });
  });
});

    // Employee composition chart (static example values)
    const malePercent = {{ $male_percent }};
    const femalePercent = {{ $female_percent }};
    const totalEmployees = Math.round((malePercent + femalePercent) === 0 ? 0 : (100 / (malePercent + femalePercent)) * (malePercent + femalePercent));

    var chartDom = document.getElementById('employee-composition-chart');
    var myChart = echarts.init(chartDom);

    var option = {
      series: [
        {
          type: 'pie',
          radius: ['70%', '90%'],
          avoidLabelOverlap: false,
          roundCap: true,
          startAngle: 90,
          label: {
            show: true,
            position: 'center',
            formatter: '{b|' + totalEmployees + '}\n{c|Total}',
            rich: {
              b: {
                fontSize: 30,
                color: '#111827',
                lineHeight: 40
              },
              c: {
                fontSize: 12,
                color: '#6b7280'
              }
            }
          },
          labelLine: {
            show: false
          },
          data: [
            {
              value: malePercent,
              name: 'Male (' + malePercent + '%)',
              itemStyle: {
                color: '#000000'
              }
            },
            {
              value: femalePercent,
              name: 'Female (' + femalePercent + '%)',
              itemStyle: {
                color: '#FFD760'
              }
            }
          ]
        }
      ]
    };

    myChart.setOption(option);

    // Year select change handler: redirect page with selected year
    document.querySelector('.year-select').addEventListener('change', function() {
      let selectedYear = this.value;
      window.location.href = "{{ url()->current() }}" + "?year=" + selectedYear;
    });

    // Data from Laravel backend (pass from controller)
    const onTimeData = @json($monthly_on_time_values);
    const lateData = @json($monthly_late_values);
    // Initialize echarts
    var chartDom = document.getElementById('timeliness-chart');
    var myChart = echarts.init(chartDom);

    var option = {
      tooltip: {
        trigger: 'axis'
      },
      xAxis: {
        type: 'category',
        boundaryGap: false,
        data: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
              'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        axisLine: { lineStyle: { color: '#e5e7eb' } },
        axisLabel: { color: '#6b7280', fontSize: 12 }
      },
      yAxis: {
        type: 'value',
        show: false
      },
      grid: {
        left: '0%',
        right: '0%',
        bottom: '8%',
        top: '5%',
        containLabel: true
      },
      series: [
        {
          name: 'On Time',
          type: 'line',
          smooth: true,
          data: onTimeData,
          lineStyle: {
            color: '#FFD760',
            width: 3
          },
          itemStyle: {
            color: '#FFD760'
          },
          areaStyle: {
            color: 'rgba(255, 215, 96, 0.15)'
          }
        },
        {
          name: 'Late',
          type: 'line',
          smooth: true,
          data: lateData,
          lineStyle: {
            color: '#9ca3af',
            type: 'dashed',
            width: 2
          },
          itemStyle: {
            color: '#9ca3af'
          }
        }
      ]
    };

    myChart.setOption(option);

  document.getElementById('filter-date').addEventListener('change', function () {
    document.getElementById('date-filter-form').submit();
  });

    // Live clock
    function updateClock() {
      const now = new Date();
      const options = { hour: '2-digit', minute: '2-digit', second: '2-digit' };
      document.getElementById('live-time').textContent = now.toLocaleTimeString([], options);
    }

    setInterval(updateClock, 1000);
    updateClock();

    // Modal open/close handlers
    const openBtn = document.getElementById('openModalBtn');
    const modal = document.getElementById('customModal');

    openBtn?.addEventListener('click', () => {
      modal.style.display = 'flex';
    });

    function closeModal() {
      modal.style.display = 'none';
    }

    window.addEventListener('click', function (e) {
      if (e.target === modal) {
        closeModal();
      }
      
    });
 document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector("#customModal form"); // ✅ exact form selection

    form.addEventListener("submit", function (e) {
      e.preventDefault();

      Swal.fire({
        title: "Are you sure?",
        text: "Do you want to add this schedule?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, submit it!"
      }).then((result) => {
        if (result.isConfirmed) {
          form.submit(); // ✅ this will now work
        }
      });
    });
  });
  @if (session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '{{ session('success') }}',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'OK'
        });
    @endif
  </script>
@endpush
@endsection

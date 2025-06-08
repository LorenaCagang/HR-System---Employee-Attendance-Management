@extends('layouts.sidebar')

@section('content')
  @push('styles')
    <link rel="stylesheet" href="{{ asset('css/employee.css') }}">
  @endpush
  <div class="top-left-logo">
    <img src="{{ asset('company/logo.png') }}" alt="Logo" />
  </div>
  <div class="container-fluid">
    <div class="content-wrapper">
    <form method="GET" id="filtersForm">
      <div class="row align-items-center mb-3">
      <div class="col-auto">
        <h2 class="mb-0">Employee List</h2>
      </div>
      <div class="col d-flex justify-content-end align-items-center gap-2 flex-wrap">
        <div style="position: relative; max-width: 300px;">
        <i class="bi bi-search"
          style="position: absolute; top: 50%; left: 10px; transform: translateY(-50%); color: #aaa;"></i>
        <input type="text" name="search" id="searchInput" class="form-control ps-5"
          placeholder="Search employees..." value="{{ request('search') }}">

        </div>


        <input type="date" name="date" id="timeInFilter" class="form-control" value="{{ $selectedDate }}"
        style="max-width: 180px;" onchange="document.getElementById('filtersForm').submit();">

        <select name="department" id="departmentFilter" class="form-select" style="max-width: 200px;"
        onchange="document.getElementById('filtersForm').submit();">
        <option value="">All Departments</option>
        @foreach ($departments as $department)
      <option value="{{ $department->name }}" {{ request('department') == $department->name ? 'selected' : '' }}>
        {{ $department->name }}
      </option>
      @endforeach
        </select>

        <button type="button" class="btn-add-filter" data-bs-toggle="modal" data-bs-target="#addEmployeeModal">
        <i class="bi bi-plus-lg me-1 text-dark"></i> Add
        </button>


      </div>
      </div>
    </form>



    <!-- Add Employee Modal -->
    <div class="modal fade" id="addEmployeeModal" tabindex="-1" aria-labelledby="addEmployeeLabel" aria-hidden="true">
      <div class="modal-dialog" style="max-width:1200px;">

      <form id="employeeForm" enctype="multipart/form-data">
        @csrf
        <div class="modal-content modal-size">
        <div class="modal-header">
          <h5 class="modal-title" id="addEmployeeLabel">
          Add New Employee
          <i class="bi bi-plus-circle-fill" style="color: #ffd660; margin-left: 8px;"></i>
          </h5>

          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">

          <div class="mb-3 text-center">
          <div class="employee-img-wrapper mx-auto position-relative" style="width:120px; height:120px;">
            <label for="photoInput" style="cursor:pointer; display:block;">
            <img id="employeePhotoPreview" class="img-fluid rounded-circle"
              src="{{ old('photo_path', asset('images/img.jpg')) }}" alt=" Attach Image"
              style="width:120px; height:120px; object-fit: cover;" />
            </label>

            <label class="upload-icon" for="photoInput"
            style="position:absolute; bottom:0; right:0; cursor:pointer;">
            <i class="bi bi-pencil-fill custom-pencil"></i>
            </label>

            <input type="file" id="photoInput" name="photo" hidden accept="image/*" />
          </div>
          </div>


          <div class="row mb-4">
          <div class="col">
            <label for="first_name" class="form-label">First Name</label>
            <input type="text" id="first_name" name="firstname" class="form-control form-rounded" required>
          </div>
          <div class="col">
            <label for="middle_name" class="form-label">Middle Name</label>
            <input type="text" id="middle_name" name="middlename" class="form-control form-rounded">
          </div>
          <div class="col">
            <label for="last_name" class="form-label">Last Name</label>
            <input type="text" id="last_name" name="lastname" class="form-control form-rounded" required>
          </div>
          <div class="col">
            <label for="suffix" class="form-label">Suffix</label>
            <input type="text" id="suffix" name="suffix" class="form-control form-rounded">
          </div>
          </div>

          <div class="row mb-4">
          <div class="col-md-3">
            <label for="birthday" class="form-label">Birthday</label>
            <input type="date" id="birthday" name="birthday" class="form-control form-rounded">
          </div>
          <div class="col-md-3">
            <label for="department" class="form-label">Department</label>
            <select id="department" name="department_id" class="form-select form-rounded">
            <option value="" selected disabled>Select Department</option>
            @foreach ($departments as $department)
        <option value="{{ $department->id }}">{{ $department->name }}</option>
        @endforeach
            </select>

          </div>
          <div class=" col-md-3">
            <label for="position" class="form-label">Position</label>
            <input type="text" id="position" name="position" class="form-control form-rounded">
          </div>
          <div class="col-md-3">
            <label for="hire_date" class="form-label">Date Hired</label>
            <input type="date" id="hire_date" name="hire_date" class="form-control form-rounded">
          </div>
          </div>

          <div class="row mb-4">
          <div class="col">
            <label for="email" class="form-label">Email</label>
            <input type="email" id="email" name="email" class="form-control form-rounded" required>
          </div>
          <div class="col">
            <label for="contact_number" class="form-label">Contact Number</label>
            <input type="text" id="contact_number" name="contact_number" class="form-control form-rounded">
          </div>
          </div>

          <div class="mb-4">
          <label for="remarks" class="form-label">Remarks</label>
          <textarea id="remarks" name="remarks" class="form-control form-rounded" rows="2"></textarea>
          </div>


          <div class="row mb-3">
          <div class="col-md-6">
            <label class="form-label">Gender</label>
            <div class="row">
            <div class="col-6">
              <div class="form-check ms-3">

              <input class="form-check-input" type="radio" name="gender" id="genderMale" value="0">
              <label class="form-check-label" for="genderMale">Male</label>
              </div>
            </div>
            <div class="col-6">
              <div class="form-check ms-3">
              <input class="form-check-input" type="radio" name="gender" id="genderFemale" value="1">
              <label class="form-check-label" for="genderFemale">Female</label>
              </div>
            </div>
            </div>
          </div>

          <div class="col-md-6">
            <label class="form-label">Is Active?</label>
            <div class="row">
            <div class="col-6">
              <div class="form-check ms-3">
              <input class="form-check-input" type="radio" name="is_active" id="activeYes" value="1">
              <label class="form-check-label" for="activeYes">Yes</label>


              </div>
            </div>
            <div class="col-6">
              <div class="form-check ms-3">

              <input class="form-check-input" type="radio" name="is_active" id="activeNo" value="0">
              <label class="form-check-label" for="activeNo">No</label>
              </div>
            </div>
            </div>
          </div>
          </div>

        </div>
        <div class="modal-footer justify-content-center">
          <button type="submit" class="btn btn-dark rounded-pill px-4">Save</button>
        </div>


        </div>
      </form>
      </div>
    </div>

    <!-- Employee Table -->

    <div class="table-wrapper table-hover">
      <table class="table my-table">

      <thead>
        <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Department</th>
        <th>Position</th>
        <th>Email</th>
        <th>Time in</th>
        <th>Status</th>
        <th>Actions</th>
        </tr>
      </thead>
      <tbody id="employeeTableBody">
        @forelse ($employees as $employee)
        <tr data-time-in="{{ $employee->time_in ? \Carbon\Carbon::parse($employee->time_in)->format('Y-m-d') : '' }}">

        <td class="align-middle">{{ $employee->id }}</td>
        <td class="align-middle">
        <div class="d-flex align-items-center gap-2">
        <img src="{{ $employee->photo ? asset('storage/' . $employee->photo) : asset('employees/img.jpg') }}"
          alt=" {{ $employee->firstname }}'s Photo" class="rounded-circle"
          style="width: 32px; height: 32px; object-fit: cover;">

        <span> {{ $employee->firstname }}
          @if($employee->middlename)
        {{ $employee->middlename }}
        @endif
          {{ $employee->lastname }}
          @if($employee->suffix)
        , {{ $employee->suffix }}
        @endif
        </span>
        </div>
        </td>
        <td class="align-middle">{{ $employee->department->name ?? 'N/A' }}</td>
        <td class="align-middle">{{ $employee->position ?? '-' }}</td>
        <td class="align-middle">{{ $employee->email }}</td>
        <td class="align-middle">
        {{ $employee->time_in
      ? \Carbon\Carbon::parse($employee->time_in)->format('Y-m-d h:i A')
      : 'No Time-In Today' }}
        </td>

        <td class="text-center align-middle">{{ $employee->status }}</td>
        <td class=" text-center align-middle">
        <div class="d-flex justify-content-center align-items-center gap-3 py-2">
        <button type="button" class="btn btn-link p-0 text-dark editBtn" title="Edit" data-bs-toggle="modal"
          data-bs-target="#editEmployeeModal" data-id="{{ $employee->id }}"
          data-firstname=" {{ $employee->firstname }}" data-middlename="{{ $employee->middlename }}"
          data-lastname=" {{ $employee->lastname }}" data-suffix="{{ $employee->suffix }}"
          data-birthday=" {{ $employee->birthday }}" data-email="{{ $employee->email }}"
          data-is_active=" {{ $employee->is_active }}" data-contact_number="{{ $employee->contact_number }}">
          <i class=" bi bi-pencil-square fs-5"></i>
        </button>


        <a href="javascript:void(0);" class="btn btn-link p-0 text-dark viewEmployeeBtn"
          data-id="{{ $employee->id }}" title=" View">
          <i class="bi bi-eye fs-5"></i>
        </a>


        @if(session('success'))
        <script>
        document.addEventListener('DOMContentLoaded', function () {
        Swal.fire({
        icon: 'success',
        title: 'Success',
        text: @json(session('success')),
        showConfirmButton: false,
        timer: 2000
        });
        });
        </script>
      @endif

        <form action="{{ route('employees.toggle', $employee->id) }}" method="POST" class="m-0 p-0 d-inline">
          @csrf
          @method('PATCH')
          <button type="submit" class="btn btn-link p-0 text-dark" title="Toggle Status">
          <i class="bi {{ $employee->is_active ? 'bi-toggle-on' : 'bi-toggle-off' }} fs-4"></i>
          </button>
        </form>




        </div>
        </td>

        </tr>
      @empty
      <tr>
      <td colspan=" 8" class="text-center text-muted py-4">No employees found.
      </td>
      </tr>
      @endforelse
      </tbody>



      </table>
    </div>
    <!-- Edit Employee Modal -->
    <div class="modal fade" id="editEmployeeModal" tabindex="-1" aria-labelledby="editEmployeeLabel" aria-hidden="true">
      <div class="modal-dialog" style="max-width:1200px;">
      <form id="editEmployeeForm" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <input type="hidden" id="edit_id" name="id">
        <div class="modal-content modal-size">
        <div class="modal-header">
          <h5 class="modal-title" id="editEmployeeLabel">
          Edit Employee
          <i class="bi bi-pencil-square" style="color: #FFD660;"></i>
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">

          <div class="mb-3 text-center">
          <div class="employee-img-wrapper mx-auto position-relative" style="width:120px; height:120px;">
            <label for="editPhotoInput" style="cursor:pointer; display:block;">
            <img id="editEmployeePhotoPreview" class="img-fluid rounded-circle"
              src="{{ asset('images/img.jpg') }}" alt=" Attach Image"
              style="width:120px; height:120px; object-fit: cover;" />
            </label>
            <label class="upload-icon" for="editPhotoInput"
            style="position:absolute; bottom:0; right:0; cursor:pointer;">
            <i class="bi bi-pencil-fill custom-pencil"></i>
            </label>
            <input type="file" id="editPhotoInput" name="photo" hidden accept="image/*" />
          </div>
          </div>

          <div class="row mb-4">
          <div class="col">
            <label for="edit_first_name" class="form-label">First Name</label>
            <input type="text" id="edit_first_name" name="firstname" class="form-control form-rounded" required>
          </div>
          <div class="col">
            <label for="edit_middle_name" class="form-label">Middle Name</label>
            <input type="text" id="edit_middle_name" name="middlename" class="form-control form-rounded">
          </div>
          <div class="col">
            <label for="edit_last_name" class="form-label">Last Name</label>
            <input type="text" id="edit_last_name" name="lastname" class="form-control form-rounded" required>
          </div>
          <div class="col">
            <label for="edit_suffix" class="form-label">Suffix</label>
            <input type="text" id="edit_suffix" name="suffix" class="form-control form-rounded">
          </div>
          </div>

          <div class="row mb-4">
          <div class="col-md-3">
            <label for="edit_birthday" class="form-label">Birthday</label>
            <input type="date" id="edit_birthday" name="birthday" class=" form-control form-rounded">
          </div>

          <div class="col-md-3">
            <label for="edit_department" class="form-label">Department</label>
            <select id="edit_department" name="department_id" class="form-select form-rounded">
            <option value="" selected disabled>Select Department</option>
            @foreach ($departments as $department)
        <option value="{{ $department->id }}">{{ $department->name }}</option>
        @endforeach
            </select>
          </div>
          <div class=" col-md-3">
            <label for="edit_position" class="form-label">Position</label>
            <input type="text" id="edit_position" name="position" class="form-control form-rounded">
          </div>
          <div class="col-md-3">
            <label for="edit_hire_date" class="form-label">Date Hired</label>
            <input type="date" id="edit_hire_date" name="hire_date" class="form-control form-rounded">
          </div>
          </div>

          <div class="row mb-4">
          <div class="col">
            <label for="edit_email" class="form-label">Email</label>
            <input type="email" id="edit_email" name="email" class="form-control form-rounded" required>
          </div>
          <div class="col">
            <label for="edit_contact_number" class="form-label">Contact Number</label>
            <input type="text" id="edit_contact_number" name="contact_number" class="form-control form-rounded">
          </div>
          </div>

          <div class="mb-4">
          <label for="edit_remarks" class="form-label">Remarks</label>
          <textarea id="edit_remarks" name="remarks" class="form-control form-rounded" rows="2"></textarea>
          </div>

          <div class="row mb-3">
          <div class="col-md-6">
            <label class="form-label">Gender</label>
            <div class="row">
            <div class="col-6">
              <div class="form-check ms-3">

              <input class="form-check-input" type="radio" name="gender" id="edit_genderMale" value="0">
              <label class="form-check-label" for="edit_genderMale">Male</label>
              </div>
            </div>
            <div class="col-6">
              <div class="form-check ms-3">
              <input class="form-check-input" type="radio" name="gender" id="edit_genderFemale" value="1">
              <label class="form-check-label" for="edit_genderFemale">Female</label>

              </div>
            </div>
            </div>
          </div>

          <div class="col-md-6">
            <label class="form-label">Is Active?</label>
            <div class="row">
            <div class="col-6">
              <div class="form-check ms-3">

              <input class="form-check-input" type="radio" name="is_active" id="edit_activeYes" value="1">
              <label class="form-check-label" for="edit_activeYes">Yes</label>


              </div>
            </div>
            <div class="col-6">
              <div class="form-check ms-3">
              <input class="form-check-input" type="radio" name="is_active" id="edit_activeNo" value="0">
              <label class="form-check-label" for="edit_activeNo">No</label>

              </div>
            </div>
            </div>
          </div>
          </div>

        </div>
        <div class="modal-footer justify-content-center">
          <button type="submit" class="btn btn-dark rounded-pill px-4">Save Changes</button>
        </div>

        </div>
      </form>
      </div>
    </div>

    <!-- View Employee Modal -->
    <div class="modal fade" id="viewEmployeeModal" tabindex="-1" aria-labelledby="viewEmployeeLabel" aria-hidden="true">
      <div class="modal-dialog" style="max-width:1200px;">
      <div class="modal-content modal-size">
        <div class="modal-header">
        <h5 class="modal-title" id="viewEmployeeLabel">
          View Employee
          <i class="bi bi-eye-fill" style="color: #FFD660;"></i>
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">

        <div class="mb-3 text-center">
          <div class="employee-img-wrapper mx-auto position-relative" style="width:120px; height:120px;">
          <img id="viewEmployeePhotoPreview" class="img-fluid rounded-circle" src="{{ asset('images/img.jpg') }}"
            alt=" Employee Photo" style="width:120px; height:120px; object-fit: cover;" />
          </div>
        </div>

        <div class="row mb-4">
          <div class="col">
          <label class="form-label">First Name</label>
          <input type="text" id="view_first_name" class="form-control form-rounded" readonly>
          </div>
          <div class="col">
          <label class="form-label">Middle Name</label>
          <input type="text" id="view_middle_name" class="form-control form-rounded" readonly>
          </div>
          <div class="col">
          <label class="form-label">Last Name</label>
          <input type="text" id="view_last_name" class="form-control form-rounded" readonly>
          </div>
          <div class="col">
          <label class="form-label">Suffix</label>
          <input type="text" id="view_suffix" class="form-control form-rounded" readonly>
          </div>
        </div>

        <div class="row mb-4">
          <div class="col-md-3">
          <label class="form-label">Birthday</label>
          <input type="date" id="view_birthday" class="form-control form-rounded" readonly>
          </div>
          <div class="col-md-3">
          <label class="form-label">Department</label>
          <input type="text" id="view_department" class="form-control form-rounded" readonly>
          </div>
          <div class="col-md-3">
          <label class="form-label">Position</label>
          <input type="text" id="view_position" class="form-control form-rounded" readonly>
          </div>
          <div class="col-md-3">
          <label class="form-label">Date Hired</label>
          <input type="date" id="view_hire_date" class="form-control form-rounded" readonly>
          </div>
        </div>

        <div class="row mb-4">
          <div class="col">
          <label class="form-label">Email</label>
          <input type="email" id="view_email" class="form-control form-rounded" readonly>
          </div>
          <div class="col">
          <label class="form-label">Contact Number</label>
          <input type="text" id="view_contact_number" class="form-control form-rounded" readonly>
          </div>
        </div>

        <div class="mb-4">
          <label class="form-label">Remarks</label>
          <textarea id="view_remarks" class="form-control form-rounded" rows="2" readonly></textarea>
        </div>

        <div class="row mb-3">
          <div class="col-md-6">
          <label class="form-label">Gender</label>
          <div class="row">
            <div class="col-6">
            <div class="form-check ms-3">
              <input class="form-check-input" type="radio" name="view_gender" id="view_gender_male" value="0"
              disabled>
              <label class="form-check-label" for="view_gender_male">Male</label>
            </div>
            </div>
            <div class="col-6">
            <div class="form-check ms-3">
              <input class="form-check-input" type="radio" name="view_gender" id="view_gender_female" value="1"
              disabled>
              <label class="form-check-label" for="view_gender_female">Female</label>
            </div>
            </div>
          </div>
          </div>

          <div class="col-md-6">
          <label class="form-label">Is Active?</label>
          <div class="row">
            <div class="col-6">
            <div class="form-check ms-3">
              <input class="form-check-input" type="radio" name="view_is_active" id="view_is_active_yes"
              value="1" disabled>
              <label class="form-check-label" for="view_is_active_yes">Yes</label>
            </div>
            </div>
            <div class="col-6">
            <div class="form-check ms-3">
              <input class="form-check-input" type="radio" name="view_is_active" id="view_is_active_no"
              value="0" disabled>
              <label class="form-check-label" for="view_is_active_no">No</label>
            </div>
            </div>
          </div>
          </div>
        </div>


        </div>
        <div class="modal-footer">
        <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
      </div>
    </div>
    </div>
  </div>




  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>



  <script>
    document.getElementById('photoInput').addEventListener('change', function (event) {
    const file = event.target.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = function (e) {
      document.getElementById('employeePhotoPreview').src = e.target.result;
      }
      reader.readAsDataURL(file);
    }
    });
    $('#employeeForm').submit(function (e) {
    e.preventDefault();

    var formData = new FormData(this);
    $.ajax({
      url: "{{ route('employees.store') }}",
      type: "POST",
      data: formData,
      processData: false,
      contentType: false,
      success: function () {
      $('#addEmployeeModal').modal('hide');
      $('#employeeForm')[0].reset();

      Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: 'Employee added successfully.',
        timer: 2000,
        showConfirmButton: false
      }).then(() => {
        location.reload();
      });
      },
      error: function (xhr) {
      if (xhr.status === 422) {
        const errors = xhr.responseJSON.errors;
        let errorMessages = '';
        for (const key in errors) {
        if (errors.hasOwnProperty(key)) {
          errorMessages += errors[key].join('<br>') + '<br>';
        }
        }
        Swal.fire({
        icon: 'error',
        title: 'Validation Error',
        html: errorMessages,
        });
      } else if (xhr.responseJSON && xhr.responseJSON.error) {
        Swal.fire({
        icon: 'error',
        title: 'Error',
        text: xhr.responseJSON.error,
        });
      } else {
        alert('Error: ' + xhr.responseText);
      }
      }
    });
    });


    $('#photoInput').on('change', function (e) {
    var file = e.target.files[0];
    if (file) {
      var reader = new FileReader();
      reader.onload = function (ev) {
      $('#employeePhotoPreview').attr('src', ev.target.result);
      }
      reader.readAsDataURL(file);
    }
    });
    $('#addEmployeeModal').on('show.bs.modal', function () {
    $('input[name="gender"]').prop('checked', false);
    $('input[name="is_active"]').prop('checked', false);

    });



    let searchTimeout;
    document.getElementById('searchInput').addEventListener('input', function () {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
      document.getElementById('filtersForm').submit();
    }, 200); // wait 500ms before submitting
    });
    $(document).ready(function () {
    $('.editBtn').on('click', function (e) {
      e.preventDefault();

      var id = $(this).data('id');
      var url = '/employees/' + id + '/edit';
      var modal = $('#editEmployeeModal');

      const isActive = $(this).data('is_active');
      if (isActive == 1) {
      $('#edit_activeYes').prop('checked', true);
      } else {
      $('#edit_activeNo').prop('checked', true);
      }

      // ✅ This AJAX block should be INSIDE the click handler
      $.ajax({
      type: 'GET',
      url: url,
      success: function (response) {
        console.log(response);

        if (response.photo) {
        $('#editEmployeePhotoPreview').attr('src', '/storage/' + response.photo);
        }

        $('#edit_id').val(response.id);
        $('#edit_first_name').val(response.firstname);
        $('#edit_middle_name').val(response.middlename);
        $('#edit_last_name').val(response.lastname);
        $('#edit_suffix').val(response.suffix);
        $('#edit_email').val(response.email);
        $('#edit_contact_number').val(response.contact_number);
        $('#edit_position').val(response.position);
        $('#edit_department').val(response.department_id);
        $('#edit_hire_date').val(response.hire_date ? response.hire_date.substring(0, 10) : '');
        $('#edit_birthday').val(response.birthday ? response.birthday.substring(0, 10) : '');
        $('input[name="gender"][value="' + response.gender + '"]').prop('checked', true);
        $('input[name="is_active"][value="' + response.is_active + '"]').prop('checked', true);
        $('#edit_remarks').val(response.remarks);

        modal.find('.modal-title').html('Edit Employee <i class="bi bi-pencil-square" style="color: #FFD660;"></i>');

        modal.modal('show');
      },
      error: function (xhr) {
        alert('Failed to fetch employee data.');
        console.error(xhr);
      }
      });
    });
    });


    $('#editPhotoInput').on('change', function (event) {

    const file = event.target.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = function (e) {
      document.getElementById('editEmployeePhotoPreview').src = e.target.result;
      }
      reader.readAsDataURL(file);
    }
    });

    function populateEditForm(data) {
    $('#edit_id').val(data.id);
    $('#edit_first_name').val(data.firstname);
    $('#edit_middle_name').val(data.middlename);
    $('#edit_last_name').val(data.lastname);
    $('#edit_suffix').val(data.suffix);
    $('#edit_birthday').val(data.birthday);

    $('#edit_department').val(data.department_id);
    $('#edit_position').val(data.position);
    $('#edit_hire_date').val(data.hire_date);
    $('#edit_email').val(data.email);
    $('#edit_contact_number').val(data.contact_number);
    $('#edit_remarks').val(data.remarks);
    $('input[name="gender"][value="' + data.gender + '"]').prop('checked', true);
    $('input[name="is_active"][value="' + data.is_active + '"]').prop('checked', true);

    }
    $('#editEmployeeForm').submit(function (e) {
    e.preventDefault();

    var formData = new FormData(this);
    var id = $('#edit_id').val();

    $.ajax({
      url: '/employees/' + id,
      type: 'POST',
      data: formData,
      processData: false,
      contentType: false,
      headers: {
      'X-HTTP-Method-Override': 'PUT'
      },
      success: function (response) {
      $('#editEmployeeModal').modal('hide');
      Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: 'Employee updated successfully.',
        timer: 2000,
        showConfirmButton: false
      }).then(() => {
        location.reload();
      });
      },
      error: function (xhr) {
      if (xhr.status === 422) {
        const errors = xhr.responseJSON.errors;
        let errorMessages = '';
        for (const key in errors) {
        if (errors.hasOwnProperty(key)) {
          errorMessages += errors[key].join('<br>') + '<br>';
        }
        }
        Swal.fire({
        icon: 'error',
        title: 'Validation Error',
        html: errorMessages,
        });
      } else if (xhr.responseJSON && xhr.responseJSON.error) {
        Swal.fire({
        icon: 'error',
        title: 'Error',
        text: xhr.responseJSON.error,
        });
      } else {
        alert('Error: ' + xhr.responseText);
      }
      }
    });
    });
    $('.viewEmployeeBtn').click(function () {
    var employeeId = $(this).data('id');
    var baseAssetUrl = "{{ asset('') }}";
    $.ajax({
      url: '/employees/' + employeeId,
      type: 'GET',
      success: function (response) {
      var photoPath = response.photo || '';
      var photoUrl = photoPath ? ('/storage/' + photoPath) : '{{ asset("images/img.jpg") }}';
      $('#viewEmployeePhotoPreview').attr('src', photoUrl);


      $('#view_first_name').val(response.firstname);
      $('#view_middle_name').val(response.middlename);
      $('#view_last_name').val(response.lastname);
      $('#view_suffix').val(response.suffix);
      $('#view_birthday').val(response.birthday ? response.birthday.substring(0, 10) : '');
      $('#view_department').val(response.department?.name || '');
      $('#view_position').val(response.position);
      $('#view_hire_date').val(response.hire_date ? response.hire_date.substring(0, 10) : '');
      $('#view_email').val(response.email);
      $('#view_contact_number').val(response.contact_number);
      $('#view_remarks').val(response.remarks);

      // Gender: 0 = Male, 1 = Female
      $('input[name="view_gender"]').prop('checked', false);
      if (response.gender === 0) {
        $('#view_gender_male').prop('checked', true);
      } else if (response.gender === 1) {
        $('#view_gender_female').prop('checked', true);
      }

      // Is Active: 1 = Yes, 0 = No
      $('input[name="view_is_active"]').prop('checked', false);
      if (response.is_active == 1) {
        $('#view_is_active_yes').prop('checked', true);
      } else {
        $('#view_is_active_no').prop('checked', true);
      }


      $('#viewEmployeeModal').modal('show');
      },
      error: function () {
      alert('Unable to fetch employee details.');
      }
    });

    });




  </script>
@endsection
@extends('layouts.sidebar')

@section('content')
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/employee.css') }}">
    @endpush

        <!-- Add Select2 CSS -->
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

        <!-- Custom CSS for Select2 to match form styling -->
        <style>
            .select2-container .select2-selection--single {
                height: 38px;
                border: 1px solid #ced4da;
                border-radius: 0.25rem;
            }

            .select2-container--default .select2-selection--single .select2-selection__rendered {
                line-height: 38px;
                color: #495057;
            }

            .select2-container--default .select2-selection--single .select2-selection__arrow {
                height: 38px;
            }

            .select2-container {
                width: 100% !important;
            }
        </style>
        <div class="top-left-logo">
            <img src="{{ asset('company/logo.png') }}" alt="Logo" />
        </div>
    <div class="container-fluid">
        <div class="content-wrapper">
        <div class="container">
            <div class="row align-items-center mb-3">
                <div class="col-auto">
                    <h2 class="mb-0">Department Management</h2>
                </div>
                <div class="col">
                    <div class="d-flex justify-content-end align-items-center gap-2 flex-wrap">
                        <input type="text" id="searchInput" class="form-control" placeholder="Search departments..."
                            style="max-width: 300px;">
                        <select id="statusFilter" class="form-select" style="max-width: 200px;">
                            <option value="">All Statuses</option>
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                        </select>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#addDepartmentModal">
                            Add
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Department Modal -->
        <div class="modal fade" id="addDepartmentModal" tabindex="-1" aria-labelledby="addDepartmentLabel" aria-hidden="true">
            <div class="modal-dialog" style="max-width:1200px;">
                <form id="departmentForm" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-content modal-size">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addDepartmentLabel">Add New Department</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3 text-center">
                                <div class="employee-img-wrapper mx-auto position-relative" style="width:120px; height:120px;">
                                    <label for="photoInput" style="cursor:pointer; display:block;">
                                        <img id="departmentPhotoPreview" class="img-fluid rounded-circle"
                                            src="{{ asset('storage/employees/img.jpg') }}"
 alt="Attach Image"
                                            style="width:120px; height:120px; object-fit: cover;" />
                                    </label>
                                    <label class="upload-icon" for="photoInput"
                                        style="position:absolute; bottom:0; right:0; cursor:pointer;">
                                        <i class="bi bi-pencil-fill custom-pencil"></i>
                                    </label>
                                    <input type="file" id="photoInput" name="profile_picture" hidden accept="image/*" />
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col">
                                    <label for="department_name" class="form-label">Department Name</label>
                                    <input type="text" id="department_name" name="name" class="form-control form-rounded"
                                        required>
                                </div>
                                <div class="col">
                                    <label for="dept_head_id" class="form-label">Department Head</label>
                                    <select id="dept_head_id" name="dept_head_id" class="form-select form-rounded select2">
                                        <option value="" selected>Select Department Head</option>
                                        @foreach ($employees as $employee)
                                            <option value="{{ $employee->id }}">{{ $employee->firstname }} {{ $employee->lastname }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label for="department_description" class="form-label">Description</label>
                                <textarea id="department_description" name="description" class="form-control form-rounded"
                                    rows="2"></textarea>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Is Active?</label>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-check ms-3">
                                                <input class="form-check-input" type="radio" name="is_active" id="activeYes"
                                                    value="1" checked>
                                                <label class="form-check-label" for="activeYes">Yes</label>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-check ms-3">
                                                <input class="form-check-input" type="radio" name="is_active" id="activeNo"
                                                    value="0">
                                                <label class="form-check-label" for="activeNo">No</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success rounded-pill px-4">Save</button>
                            <button type="button" class="btn btn-secondary rounded-pill px-4"
                                data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Department Table -->
        <div class="table-wrapper table-hover">
            <table class="table my-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Department Head</th>
                        <th>Total Employees</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($departments as $department)
                        <tr>
                            <td class="align-middle">{{ $department->id }}</td>
                            <td class="align-middle">{{ $department->name }}</td>
                            <td class="align-middle">
                                {{ $department->departmentHead ? $department->departmentHead->firstname . ' ' . $department->departmentHead->lastname : 'N/A' }}
                            </td>
                            <td class="align-middle">{{ $department->employees->count() }}</td>
                            <td class="text-center align-middle">{{ $department->is_active ? 'Active' : 'Inactive' }}</td>
                            <td class="text-center align-middle">
                                <div class="d-flex justify-content-center align-items-center gap-3 py-2">
                                    <button type="button" class="btn btn-link p-0 text-dark editBtn" title="Edit"
                                        data-bs-toggle="modal" data-bs-target="#editDepartmentModal" data-id="{{ $department->id }}"
                                        data-name="{{ $department->name }}" data-description="{{ $department->description }}"
                                        data-department-head-id="{{ $department->dept_head_id }}"
                                        data-is-active="{{ $department->is_active }}"
                                        data-profile-picture="{{ $department->profile_picture ? asset('storage/' . $department->profile_picture) : asset('images/img.jpg') }}">
                                        <i class="bi bi-pencil-square fs-5"></i>
                                    </button>
                                    <a href="javascript:void(0);" class="btn btn-link p-0 text-dark viewDepartmentBtn"
                                        data-id="{{ $department->id }}" title="View">
                                        <i class="bi bi-eye fs-5"></i>
                                    </a>
                                    <form action="{{ route('departments.toggle', $department->id) }}" method="POST"
                                        class="m-0 p-0 d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-link p-0 text-dark" title="Toggle Status">
                                            <i class="bi {{ $department->is_active ? 'bi-toggle-on' : 'bi-toggle-off' }} fs-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">No departments found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Edit Department Modal -->
        <div class="modal fade" id="editDepartmentModal" tabindex="-1" aria-labelledby="editDepartmentLabel" aria-hidden="true">
            <div class="modal-dialog" style="max-width:1200px;">
                <form id="editDepartmentForm" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="edit_id" name="id">
                    <div class="modal-content modal-size">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editDepartmentLabel">Edit Department</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3 text-center">
                                <div class="employee-img-wrapper mx-auto position-relative" style="width:120px; height:120px;">
                                    <label for="editPhotoInput" style="cursor:pointer; display:block;">
                                        <img id="editDepartmentPhotoPreview" class="img-fluid rounded-circle"
                                            src="{{ asset('images/img.jpg') }}" alt="Attach Image"
                                            style="width:120px; height:120px; object-fit: cover;" />
                                    </label>
                                    <label class="upload-icon" for="editPhotoInput"
                                        style="position:absolute; bottom:0; right:0; cursor:pointer;">
                                        <i class="bi bi-pencil-fill custom-pencil"></i>
                                    </label>
                                    <input type="file" id="editPhotoInput" name="profile_picture" hidden accept="image/*" />
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col">
                                    <label for="edit_department_name" class="form-label">Department Name</label>
                                    <input type="text" id="edit_department_name" name="name" class="form-control form-rounded"
                                        required>
                                </div>
                                <div class="col">
                                    <label for="edit_dept_head_id" class="form-label">Department Head</label>
                                    <select id="edit_dept_head_id" name="dept_head_id" class="form-select form-rounded select2">
                                        <option value="">Select Department Head</option>
                                        @foreach ($employees as $employee)
                                            <option value="{{ $employee->id }}">{{ $employee->firstname }} {{ $employee->lastname }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label for="edit_department_description" class="form-label">Description</label>
                                <textarea id="edit_department_description" name="description" class="form-control form-rounded"
                                    rows="2"></textarea>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Is Active?</label>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-check ms-3">
                                                <input class="form-check-input" type="radio" name="is_active"
                                                    id="edit_activeYes" value="1">
                                                <label class="form-check-label" for="edit_activeYes">Yes</label>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-check ms-3">
                                                <input class="form-check-input" type="radio" name="is_active" id="edit_activeNo"
                                                    value="0">
                                                <label class="form-check-label" for="edit_activeNo">No</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success rounded-pill px-4">Save Changes</button>
                            <button type="button" class="btn btn-secondary rounded-pill px-4"
                                data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- View Department Modal -->
        <div class="modal fade" id="viewDepartmentModal" tabindex="-1" aria-labelledby="viewDepartmentLabel" aria-hidden="true">
            <div class="modal-dialog" style="max-width:1200px;">
                <div class="modal-content modal-size">
                    <div class="modal-header">
                        <h5 class="modal-title" id="viewDepartmentLabel">View Department</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3 text-center">
                            <div class="employee-img-wrapper mx-auto position-relative" style="width:120px; height:120px;">
                                <img id="viewDepartmentPhotoPreview" class="img-fluid rounded-circle"
                                    src="{{ asset('images/img.jpg') }}" alt="Department Photo"
                                    style="width:120px; height:120px; object-fit: cover;" />
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col">
                                <label class="form-label">Department Name</label>
                                <input type="text" id="view_department_name" class="form-control form-rounded" readonly>
                            </div>
                            <div class="col">
                                <label class="form-label">Department Head</label>
                                <input type="text" id="view_department_head_name" class="form-control form-rounded" readonly>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Description</label>
                            <textarea id="view_department_description" class="form-control form-rounded" rows="2"
                                readonly></textarea>
                        </div>
                        <div class="row mb-4">
                            <div class="col">
                                <label class="form-label">Total Employees</label>
                                <input type="text" id="view_total_employees" class="form-control form-rounded" readonly>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Is Active?</label>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-check ms-3">
                                            <input class="form-check-input" type="radio" name="view_is_active"
                                                id="view_active_yes" value="1" disabled>
                                            <label class="form-check-label" for="view_active_yes">Yes</label>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-check ms-3">
                                            <input class="form-check-input" type="radio" name="view_is_active"
                                                id="view_active_no" value="0" disabled>
                                            <label class="form-check-label" for="view_active_no">No</label>
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
                </form>
            </div>
        </div>
        </div>
        </div>
        <!-- Include jQuery, Select2, and SweetAlert2 -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
            $(document).ready(function () {
                // Initialize Select2 for Add Department Modal
                $('#dept_head_id').select2({
                    placeholder: "Select Department Head",
                    allowClear: true,
                    width: '100%'
                });

                // Initialize Select2 for Edit Department Modal
                $('#edit_dept_head_id').select2({
                    placeholder: "Select Department Head",
                    allowClear: true,
                    width: '100%'
                });

                // Ensure Select2 works within Bootstrap modals
                $('#addDepartmentModal, #editDepartmentModal').on('shown.bs.modal', function () {
                    $('#dept_head_id, #edit_dept_head_id').select2({
                        dropdownParent: $(this),
                        placeholder: "Select Department Head",
                        allowClear: true,
                        width: '100%'
                    });
                });

                // Image Preview for Add Modal
                $('#photoInput').on('change', function (event) {
                    const file = event.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function (e) {
                            $('#departmentPhotoPreview').attr('src', e.target.result);
                        };
                        reader.readAsDataURL(file);
                    }
                });

                // Image Preview for Edit Modal
                $('#editPhotoInput').on('change', function (event) {
                    const file = event.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function (e) {
                            $('#editDepartmentPhotoPreview').attr('src', e.target.result);
                        };
                        reader.readAsDataURL(file);
                    }
                });

                // Reset Add Modal on Open
                $('#addDepartmentModal').on('show.bs.modal', function () {
                    $('#departmentForm')[0].reset();
                    $('#departmentPhotoPreview').attr('src', '{{ asset("images/img.jpg") }}');
                    $('input[name="is_active"]').prop('checked', false);
                    $('#activeYes').prop('checked', true);
                    $('#dept_head_id').val('').trigger('change'); // Reset Select2
                    $('#department_description').val(''); // Reset description
                });

                // Search and Filter for Table
                $('#searchInput, #statusFilter').on('input change', function () {
                    var searchTerm = $('#searchInput').val().toLowerCase();
                    var statusFilter = $('#statusFilter').val();

                    $('table.my-table tbody tr').each(function () {
                        var name = $(this).find('td:nth-child(2)').text().toLowerCase();
                        var status = $(this).find('td:nth-child(5)').text();

                        var matchesSearch = name.includes(searchTerm);
                        var matchesStatus = !statusFilter || status === statusFilter;

                        if (matchesSearch && matchesStatus) {
                            $(this).show();
                        } else {
                            $(this).hide();
                        }
                    });
                });

                // Add Department Form Submission
                $('#departmentForm').submit(function (e) {
                    e.preventDefault();
                    var formData = new FormData(this);
                    $.ajax({
                        url: "{{ route('departments.store') }}",
                        type: "POST",
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (response) {
                            $('#addDepartmentModal').modal('hide');
                            $('#departmentForm')[0].reset();
                            $('#departmentPhotoPreview').attr('src', '{{ asset("images/img.jpg") }}');
                            $('#dept_head_id').val('').trigger('change'); // Reset Select2
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: 'Department added successfully.',
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload();
                            });
                        },
                        error: function (xhr) {
                            console.error('Add Department Error:', xhr.responseJSON);
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
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: xhr.responseJSON?.message || 'An error occurred while adding the department.',
                                });
                            }
                        }
                    });
                });

                // Edit Department Button
                $('.editBtn').on('click', function () {
                    var id = $(this).data('id');
                    var name = $(this).data('name');
                    var description = $(this).data('description');
                    var departmentHeadId = $(this).data('department-head-id');
                    var isActive = $(this).data('is-active');
                    var profilePicture = $(this).data('profile-picture');
                    var modal = $('#editDepartmentModal');

                    $('#edit_id').val(id);
                    $('#edit_department_name').val(name);
                    $('#edit_department_description').val(description);
                    $('#edit_dept_head_id').val(departmentHeadId || '').trigger('change'); // Update Select2
                    $('input[name="is_active"]').prop('checked', false);
                    if (isActive == 1) {
                        $('#edit_activeYes').prop('checked', true);
                    } else {
                        $('#edit_activeNo').prop('checked', true);
                    }
                    $('#editDepartmentPhotoPreview').attr('src', profilePicture);
                    modal.modal('show');
                });

                // Edit Department Form Submission
                $('#editDepartmentForm').submit(function (e) {
                    e.preventDefault();
                    var formData = new FormData(this);
                    var id = $('#edit_id').val();
                    $.ajax({
                        url: "{{ url('/departments') }}/" + id,
                        type: "POST",
                        data: formData,
                        processData: false,
                        contentType: false,
                        headers: {
                            'X-HTTP-Method-Override': 'PUT'
                        },
                        success: function (response) {
                            $('#editDepartmentModal').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: 'Department updated successfully.',
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload();
                            });
                        },
                        error: function (xhr) {
                            console.error('Edit Department Error:', xhr.responseJSON);
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
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: xhr.responseJSON?.message || 'An error occurred while updating the department.',
                                });
                            }
                        }
                    });
                });

                // View Department Button
                $('.viewDepartmentBtn').click(function () {
                    var departmentId = $(this).data('id');
                    $.ajax({
                        url: "{{ url('/departments') }}/" + departmentId,
                        type: 'GET',
                        success: function (response) {
                            console.log('Response:', response); // Debug to inspect response structure

                            // Set photo, fallback to default if profile_picture is missing
                            var photoUrl = response.profile_picture
                                ? '{{ asset('storage') }}/' + response.profile_picture
                                : '{{ asset('images/img.jpg') }}';
                            $('#viewDepartmentPhotoPreview').attr('src', photoUrl);

                            // Set department name
                            $('#view_department_name').val(response.name || '');

                            // Set description
                            $('#view_department_description').val(response.description || '');

                            // Set department head name, handle null or missing department_head
                            var departmentHeadName = response.department_head
                                ? (response.department_head.firstname + ' ' + response.department_head.lastname)
                                : 'N/A';
                            $('#view_department_head_name').val(departmentHeadName);

                            // Set total employees
                            $('#view_total_employees').val(response.employees ? response.employees.length : 0);

                            // Set active status
                            $('input[name="view_is_active"]').prop('checked', false);
                            if (response.is_active == 1) {
                                $('#view_active_yes').prop('checked', true);
                            } else {
                                $('#view_active_no').prop('checked', true);
                            }

                            // Show the modal
                            $('#viewDepartmentModal').modal('show');
                        },
                        error: function (xhr) {
                            console.error('View Department Error:', xhr.responseJSON);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: xhr.responseJSON?.message || 'Unable to fetch department details.',
                            });
                        }
                    });
                });
            });
        </script>
@endsection
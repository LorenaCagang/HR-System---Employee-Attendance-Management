<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>@yield('title', 'HR Dashboard')</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <!-- Google Font: Poppins -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
  <!-- SweetAlert2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


  <style>
    body {
      margin: 0;
      padding: 0;
      background-color: #f8f9fa;
      font-family: 'Poppins', sans-serif;
    }

    .top-nav {
      position: absolute;
      top: 1rem;
      right: 1rem;
      display: flex;
      align-items: center;
      gap: 1rem;
    }

    .text-circle-group {
      background-color: rgba(255, 255, 255, 0.5);
      border-radius: 50px;
      padding: 0.5rem 1.25rem;
      display: flex;
      gap: 1.5rem;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
    }

    .text-item {
      color: #333;
      font-size: 0.95rem;
      cursor: pointer;
      transition: all 0.2s ease-in-out;
      padding: 0.25rem 0.75rem;
      border-radius: 50px;
    }

    .text-item.active {
      background-color: #353535;
      color: white;
    }

    .settings-circle {
      background-color: rgba(255, 255, 255, 0.5);
      border-radius: 50px;
      padding: 0.5rem 1rem;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #333;
      cursor: pointer;
      font-weight: 500;
      font-size: 0.95rem;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
      transition: all 0.2s ease-in-out;
    }

    .profile-circle {
      background-color: rgba(255, 255, 255, 0.5);
      border-radius: 50%;
      width: 45px;
      height: 45px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #333;
      cursor: pointer;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
      transition: all 0.2s ease-in-out;
    }

    .settings-circle.active,
    .profile-circle.active {
      background-color: #353535;
      color: white;
    }

    .text-item {
  text-decoration: none !important;
}
.swal2-confirm {
  color: #353535 !important; 
  font-weight: 600;
}

  </style>

  {{-- Place for page-specific CSS --}}
  @stack('styles')
</head>
<body>

  <div class="top-nav">
    <div class="text-circle-group">
      <a href="{{ route('dashboard') }}" class="text-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a>
      <a href="{{ route('departments.index') }}" class="text-item {{ request()->routeIs('departments.index') ? 'active' : '' }}">Departments</a>
      <a href="{{ route('employees.index') }}" class="text-item {{ request()->routeIs('employees.index') ? 'active' : '' }}">Employees</a>

    </div>

<div class="dropdown">
  <div 
    class="text-item profile-circle dropdown-toggle" 
    id="profileDropdown" 
    role="button" 
    data-bs-toggle="dropdown" 
    aria-expanded="false"
    title="Profile"
  >
    <i class="bi bi-person-fill fs-5"></i>
  </div>
  <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
    <li>
      <a href="#" class="dropdown-item" onclick="event.preventDefault(); confirmLogout();">
        Logout
      </a>
    </li>
  </ul>
</div>

<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
  @csrf
</form>


  </div>

  <div class="container mt-5 pt-5">
    @yield('content')
  </div>

  <script>
    const items = document.querySelectorAll('.text-item');
    items.forEach(item => {
      item.addEventListener('click', () => {
        items.forEach(i => i.classList.remove('active'));
        item.classList.add('active');
      });
    });

function confirmLogout() {
  Swal.fire({
    title: 'Are you sure you want to log out?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Yes, log out',
    cancelButtonText: 'Cancel',
    reverseButtons: true,
    confirmButtonColor: '#FFD760', 
    cancelButtonColor: '#353535'    
  }).then((result) => {
    if (result.isConfirmed) {
      document.getElementById('logout-form').submit();
    }
  });
}


  
  </script>



  {{-- Place for page-specific JS --}}

<!-- Bootstrap Bundle JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<!-- ECharts -->
<script src="https://cdn.jsdelivr.net/npm/echarts@5/dist/echarts.min.js"></script>

@yield('scripts')
@stack('scripts')
</body>
</html>

 <nav class="navbar navbar-expand-lg bg-white shadow-sm px-3 py-3 navbar-bottom-border">
     <div class="container-fluid d-flex justify-content-between align-items-center flex-nowrap">
         <!-- Left Logo & Title -->
         <a href="#" style="text-decoration: none;cursor: pointer;">
             <div class="d-flex align-items-center flex-nowrap overflow-hidden">
                 <div class="icon-box d-inline-flex align-items-center justify-content-center rounded-3 shadow me-2"
                     style="width:40px; height:40px; background: linear-gradient(135deg, #FFC107, #F44336);">
                     <i class="bi bi-book fs-2 text-white"></i>
                 </div>
                 <!-- Responsive text -->
                 <span class="navbar-brand nav-text-orange mb-0 fs-5" style="white-space: nowrap;">FluentAll
                     Student</span>
             </div>
         </a>
         <!-- Toggler stays inline -->
         <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMenu">
             <span class="navbar-toggler-icon"></span>
         </button>

         <!-- Navbar menu -->
         <div class="collapse navbar-collapse justify-content-between" id="navbarMenu">
             <ul class="navbar-nav ms-3 mb-2 mb-lg-0">
                 <li class="nav-item me-3">
                     <a class="nav-link active d-flex align-items-center" href="#">
                         <i class="bi bi-house me-1"></i> Home
                     </a>
                 </li>
                 <li class="nav-item me-3">
                     <a class="nav-link d-flex align-items-center" href="#"><i class="bi bi-calendar3 me-1"></i>
                         My Calendar</a>
                 </li>
                 <li class="nav-item me-3">
                     <a class="nav-link d-flex align-items-center" href="#"><i
                             class="bi bi-chat-left-text me-1"></i> Messages</a>
                 </li>
                 <li class="nav-item me-3">
                     <a class="nav-link d-flex align-items-center" href="{{ route('student.public.profile') }}">
                         <i class="bi bi-eye me-1"></i> Profile
                     </a>
                 </li>


             </ul>

             <div class="d-flex align-items-center gap-2">
                 <a href="#" class="btn btn-switch">
                     🔁 Switch to Student View
                 </a>
                 <div class="dropdown">
                     <div class="dropdown-toggle rounded-circle border border-warning text-warning fw-bold d-flex align-items-center justify-content-center"
                         style="width: 32px; height: 32px; cursor: pointer;" data-bs-toggle="dropdown"
                         aria-expanded="false" tabindex="0" role="button">
                         T
                     </div>
                     <ul class="dropdown-menu dropdown-menu-end">
                         <li><a class="dropdown-item" href="">View Profile</a></li>
                         <li><a class="dropdown-item" href="">Settings</a></li>
                         <li>
                             <hr class="dropdown-divider">
                         </li>
                         {{-- <li>
                             <a class="dropdown-item text-danger" href="javascript:void(0);"
                                 onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                 Logout
                             </a>
                             <form id="logout-form" action="{{ route('teacher.logout') }}" method="POST"
                                 class="d-none">
                                 @csrf
                             </form>
                         </li> --}}
                         <li>
                             <a class="dropdown-item text-danger" href="#"
                                 onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                 Logout
                             </a>
                             <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                 @csrf
                             </form>
                         </li>


                     </ul>
                 </div>
             </div>
         </div>
     </div>
 </nav>

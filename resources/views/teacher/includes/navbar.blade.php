 <nav class="navbar navbar-expand-lg bg-white shadow-sm px-3 py-3 navbar-bottom-border">
     <div class="container-fluid d-flex justify-content-between align-items-center flex-nowrap">
         <!-- Left Logo & Title -->
         <a href="{{ route('teacher.dashboard') }}" style="text-decoration: none;cursor: pointer;">
             <div class="d-flex align-items-center flex-nowrap overflow-hidden">
                 <div class="icon-box d-inline-flex align-items-center justify-content-center rounded-3 shadow me-2"
                     style="width:40px; height:40px; background: linear-gradient(135deg, #FFC107, #F44336);">
                     <i class="bi bi-book fs-2 text-white"></i>
                 </div>
                 <!-- Responsive text -->
                 <span class="navbar-brand nav-text-orange mb-0 fs-5" style="white-space: nowrap;">FluentAll
                     Teacher</span>
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
                     <a class="nav-link active d-flex align-items-center" href="{{ route('teacher.dashboard') }}">
                         <i class="bi bi-house me-1"></i> Home
                     </a>
                 </li>
                 <li class="nav-item me-3">
                     <a class="nav-link d-flex align-items-center" href="{{ route('teacher.calendar') }}"><i
                             class="bi bi-calendar3 me-1"></i>
                         My Calendar</a>
                 </li>

                 <li class="nav-item me-3 position-relative">
                     <a class="nav-link d-flex align-items-center position-relative"
                         href="{{ route('teacher.chats.index') }}">
                         <i class="bi bi-chat-left-text me-1"></i>
                         Messages
                         @if (isset($unreadMessagesCount) && $unreadMessagesCount > 0)
                             <span
                                 class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger notification-badge">
                                 {{ $unreadMessagesCount > 99 ? '99+' : $unreadMessagesCount }}
                             </span>
                         @endif
                     </a>
                 </li>

                 <style>
                     .notification-badge {
                         font-size: 0.65rem;
                         padding: 0.25em 0.4em;
                         margin-left: -10px;
                         margin-top: -5px;
                         animation: pulse 2s infinite;
                     }

                     @keyframes pulse {
                         0% {
                             box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.7);
                         }

                         70% {
                             box-shadow: 0 0 0 10px rgba(220, 53, 69, 0);
                         }

                         100% {
                             box-shadow: 0 0 0 0 rgba(220, 53, 69, 0);
                         }
                     }

                     .nav-link {
                         position: relative;
                     }
                 </style>

                 <script>
                     // Function to update navbar notification count - FIXED VERSION
                     function updateNavbarNotificationCount() {
                         const currentPath = window.location.pathname;
                         let unreadCountRoute;

                         if (currentPath.includes('/teacher/')) {
                             unreadCountRoute = '{{ route('teacher.chat.unread-count') }}';
                         } else if (currentPath.includes('/student/')) {
                             unreadCountRoute = '{{ route('student.chat.unread-count') }}';
                         } else {
                             // Default to teacher route if we can't determine
                             unreadCountRoute = '{{ route('teacher.chat.unread-count') }}';
                         }

                         fetch(unreadCountRoute)
                             .then(response => {
                                 if (!response.ok) {
                                     throw new Error(`HTTP error! status: ${response.status}`);
                                 }
                                 return response.json();
                             })
                             .then(data => {
                                 console.log('Navbar update response:', data); // Debug log

                                 const badge = document.querySelector('.notification-badge');
                                 const messagesLink = document.querySelector('a[href*="chats"]');

                                 if (data.unread_count > 0) {
                                     if (badge) {
                                         badge.textContent = data.unread_count > 99 ? '99+' : data.unread_count;
                                     } else if (messagesLink) {
                                         // Create badge if it doesn't exist
                                         const newBadge = document.createElement('span');
                                         newBadge.className =
                                             'position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger notification-badge';
                                         newBadge.textContent = data.unread_count > 99 ? '99+' : data.unread_count;
                                         messagesLink.appendChild(newBadge);
                                     }
                                 } else {
                                     if (badge) {
                                         badge.remove();
                                     }
                                 }
                             })
                             .catch(error => {
                                 console.error('Error fetching unread count:', error);
                             });
                     }

                     // Update count every 30 seconds
                     setInterval(updateNavbarNotificationCount, 30000);

                     // Update count when page becomes visible (user switches back to tab)
                     document.addEventListener('visibilitychange', function() {
                         if (!document.hidden) {
                             updateNavbarNotificationCount();
                         }
                     });

                     // Update count on page load
                     document.addEventListener('DOMContentLoaded', function() {
                         updateNavbarNotificationCount();
                     });
                 </script>

                 <li class="nav-item me-3">
                     <a class="nav-link d-flex align-items-center" href="{{ route('teacher.zoom.meetings.index') }}"><i
                             class="bi bi-camera-video me-1"></i> Lessons</a>
                 </li>
                 <li class="nav-item me-3">
                     <a class="nav-link d-flex align-items-center" href="{{ route('teacher.profile.edit') }}">
                         <i class="bi bi-eye me-1"></i> Profile
                     </a>
                 </li>


             </ul>

             <div class="d-flex align-items-center gap-2">
                 <a href="{{ route('index') }}" class="btn btn-switch">
                     🔁 Switch to Student View
                 </a>
                 <div class="dropdown">
                     <div class="dropdown-toggle rounded-circle border border-warning text-warning fw-bold d-flex align-items-center justify-content-center"
                         style="width: 32px; height: 32px; cursor: pointer;" data-bs-toggle="dropdown"
                         aria-expanded="false" tabindex="0" role="button">
                         T
                     </div>
                     <ul class="dropdown-menu dropdown-menu-end">
                         <li><a class="dropdown-item" href="{{ route('teacher.public.profile') }}">My Profile</a></li>
                         <li><a class="dropdown-item" href="{{ route('teacher.settings.index') }}">Lesson Settings</a>
                         </li>
                         <li><a class="dropdown-item" href="{{ route('teacher.wallet.index') }}">Wallet</a></li>
                         <li>
                             <hr class="dropdown-divider">
                         </li>
                         <li>
                             <a class="dropdown-item text-danger" href="javascript:void(0);"
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

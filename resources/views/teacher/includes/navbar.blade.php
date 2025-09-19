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
                         @if (isset($totalUnreadCount) && $totalUnreadCount > 0)
                             <span
                                 class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger notification-badge">
                                 {{ $totalUnreadCount > 99 ? '99+' : $totalUnreadCount }}
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
                     // SIMPLIFIED - Single route approach
                     function updateNavbarNotificationCount() {
                         const currentPath = window.location.pathname;
                         let unreadCountRoute;

                         if (currentPath.includes('/teacher/')) {
                             unreadCountRoute = '{{ route('teacher.messages.combined-unread-count') }}';
                         } else if (currentPath.includes('/student/')) {
                             unreadCountRoute = '{{ route('student.messages.combined-unread-count') }}';
                         } else {
                             // Default to teacher route
                             unreadCountRoute = '{{ route('teacher.messages.combined-unread-count') }}';
                         }

                         fetch(unreadCountRoute)
                             .then(response => {
                                 if (!response.ok) {
                                     throw new Error(`HTTP error! status: ${response.status}`);
                                 }
                                 return response.json();
                             })
                             .then(data => {
                                 console.log('Combined unread count response:', data);

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
                                 console.error('Error fetching combined unread count:', error);
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

                 <li class="nav-item dropdown me-3 position-relative">
                     @php
                         $lessonNotifications = auth()
                             ->user()
                             ->unreadNotifications()
                             ->where('type', 'App\Notifications\LessonDeductedNotification');
                         $lessonCount = $lessonNotifications->count();
                     @endphp

                     <!-- Lessons link / bell -->
                     <a class="nav-link position-relative d-flex align-items-center"
                         href="{{ route('teacher.zoom.meetings.index') }}" id="lessonDropdown" role="button"
                         data-bs-toggle="{{ $lessonCount > 0 ? 'dropdown' : '' }}" aria-expanded="false">
                         <i class="bi bi-bell me-1"></i> Lessons
                         @if ($lessonCount > 0)
                             <span
                                 class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger notification-badge lesson-notification-badge">
                                 {{ $lessonCount > 99 ? '99+' : $lessonCount }}
                             </span>
                         @endif
                     </a>

                     @if ($lessonCount > 0)
                         <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="lessonDropdown"
                             style="width: 300px;">
                             <li class="dropdown-header">Recent Lesson Notifications</li>
                             <div id="lesson-notifications-list" style="max-height: 300px; overflow-y: auto;">
                                 {{-- @foreach ($lessonNotifications->take(5) as $notification)
                                     <li>
                                         <a class="dropdown-item small lesson-link" data-id="{{ $notification->id }}"
                                             href="{{ route('teacher.zoom.meetings.index') }}">
                                             {{ $notification->data['message'] }}
                                             <br>
                                             <span
                                                 class="text-muted small">{{ $notification->created_at->diffForHumans() }}</span>
                                         </a>
                                     </li>
                                 @endforeach --}}
                                 @foreach ($lessonNotifications->take(5) as $notification)
                                     <li>
                                         <a class="dropdown-item small lesson-link d-flex align-items-start"
                                             data-id="{{ $notification->id }}"
                                             href="{{ route('teacher.zoom.meetings.index') }}">
                                             <i class="{{ $notification->data['icon'] }} me-2"></i>
                                             <div>
                                                 {{ $notification->data['message'] }}<br>
                                                 <span
                                                     class="text-muted small">{{ $notification->created_at->diffForHumans() }}</span>
                                             </div>
                                         </a>
                                     </li>
                                 @endforeach

                                 @if ($lessonCount > 5)
                                     <li>
                                         <hr class="dropdown-divider">
                                     </li>
                                     <li><a class="dropdown-item text-center small"
                                             href="{{ route('teacher.lesson.notifications') }}">View All</a></li>
                                 @endif
                             </div>
                         </ul>
                     @endif
                 </li>

                 <script>
                     // Mark a notification as read
                     async function markNotificationRead(id) {
                         await fetch(`/teacher/notifications/mark-read/${id}`, {
                             method: 'POST',
                             headers: {
                                 'X-CSRF-TOKEN': '{{ csrf_token() }}'
                             }
                         });
                     }

                     function updateLessonDropdown() {
                         fetch('{{ route('teacher.notifications.unread-count') }}')
                             .then(res => res.json())
                             .then(data => {
                                 const badge = document.querySelector('.lesson-notification-badge');
                                 const list = document.getElementById('lesson-notifications-list');
                                 const dropdown = document.getElementById('lessonDropdown');

                                 if (data.unread_count > 0) {
                                     if (badge) badge.textContent = data.unread_count > 99 ? '99+' : data.unread_count;
                                     else {
                                         const span = document.createElement('span');
                                         span.className =
                                             'position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger notification-badge lesson-notification-badge';
                                         span.textContent = data.unread_count > 99 ? '99+' : data.unread_count;
                                         dropdown.appendChild(span);
                                     }
                                     if (list) {
                                         list.innerHTML = '';
                                         data.notifications.slice(0, 5).forEach(notif => {
                                             const li = document.createElement('li');
                                             li.innerHTML = `
                                                <a class="dropdown-item small lesson-link d-flex align-items-start" data-id="${notif.id}" href="${dropdown.href}">
                                                    <i class="${notif.icon} me-2"></i>
                                                    <div>
                                                        ${notif.message}<br>
                                                        <span class="text-muted small">${notif.time}</span>
                                                    </div>
                                                </a>`;
                                             list.appendChild(li);
                                         });
                                     }

                                 } else {
                                     if (badge) badge.remove();
                                     if (list) list.innerHTML =
                                         '<li class="dropdown-item small text-muted">No new notifications</li>';
                                 }
                             });
                     }

                     // Poll every 15 seconds for new notifications
                     setInterval(updateLessonDropdown, 15000);
                     document.addEventListener('DOMContentLoaded', updateLessonDropdown);

                     // Handle clicks on individual notifications
                     document.addEventListener('click', function(e) {
                         const link = e.target.closest('.lesson-link');
                         if (link) {
                             e.preventDefault();
                             const notifId = link.dataset.id;
                             const targetUrl = link.href;

                             markNotificationRead(notifId).then(() => {
                                 // Remove clicked notification from dropdown
                                 link.parentElement.remove();

                                 // Remove badge if no notifications left
                                 const remaining = document.querySelectorAll('#lesson-notifications-list .lesson-link')
                                     .length;
                                 if (remaining === 0) {
                                     const badge = document.querySelector('.lesson-notification-badge');
                                     if (badge) badge.remove();
                                     const list = document.getElementById('lesson-notifications-list');
                                     if (list) list.innerHTML =
                                         '<li class="dropdown-item small text-muted">No new notifications</li>';
                                 }

                                 // Redirect to the lessons page
                                 window.location.href = targetUrl;
                             });
                         }
                     });

                     // Clicking bell with no notifications goes directly to Lessons
                     document.getElementById('lessonDropdown').addEventListener('click', function(e) {
                         const hasNotifications = document.querySelectorAll('#lesson-notifications-list .lesson-link').length >
                             0;
                         if (!hasNotifications) {
                             window.location.href = this.href;
                         }
                     });
                 </script>





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

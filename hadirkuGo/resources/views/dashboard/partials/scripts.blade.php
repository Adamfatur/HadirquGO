@include('partials.floating_feedback')
@include('lecturer.changelog_modal')

@php
    $isLecturer = Auth::user()->hasRole('Lecturer');
    $notifRoute = $isLecturer ? 'lecturer.notifications.fetch' : 'notifications.fetch';
@endphp

<style>
    .animate-card { opacity: 0; transform: translateY(20px); animation: fadeInUp 0.6s ease-out forwards; }
    .animate-icon { transition: transform 0.3s, box-shadow 0.3s; }
    .animate-icon:hover { transform: scale(1.1); box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.15); }
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    .card h5, .card p { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
    .icon-circle { border-radius: 50%; box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1); }
    
    #notifications-timeline { position: relative; max-height: 400px; overflow-y: auto; padding-right: 10px; }
    #notifications-timeline::before { content: ''; position: absolute; top: 0; left: 20px; width: 2px; height: 100%; background-color: #e9ecef; z-index: 1; }
    .notification-item { position: relative; display: flex; align-items: flex-start; margin-bottom: 20px; padding-left: 40px; z-index: 2; }
    .notification-item::before { content: ''; position: absolute; top: 10px; left: 16px; width: 10px; height: 10px; border-radius: 50%; background-color: #1e3a8a; border: 2px solid white; z-index: 3; }
    .notification-item .avatar { width: 40px; height: 40px; border-radius: 50%; margin-right: 10px; border: 2px solid #1e3a8a; }
    .notification-content { background-color: #f8f9fa; border: 1px solid #e9ecef; border-radius: 10px; padding: 10px; flex: 1; transition: background-color 0.3s ease; }
    .notification-content:hover { background-color: #e9ecef; }
    .notification-content .user-name { font-weight: bold; color: #1e3a8a; margin-bottom: 5px; }
    .notification-content .notification-message { font-size: 0.9rem; color: #333; margin-bottom: 5px; }
    .notification-content .notification-time { font-size: 0.8rem; color: #6c757d; text-align: right; }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
        fetchNotifications();
        setInterval(fetchNotifications, 5000);
    });

    function fetchNotifications() {
        const url = "{{ route($notifRoute) }}";
        fetch(url)
            .then(response => response.json())
            .then(data => {
                const notificationsTimeline = document.getElementById('notifications-timeline');
                if (!notificationsTimeline) return;
                notificationsTimeline.innerHTML = '';

                if (data.notifications.length === 0) {
                    notificationsTimeline.innerHTML = `
                        <div class="text-center py-4">
                            <i class="fas fa-bell-slash fa-2x text-muted mb-3"></i>
                            <p class="text-muted">No notifications yet.</p>
                        </div>
                    `;
                    return;
                }

                data.notifications.forEach(notification => {
                    const rolePrefix = "{{ Auth::user()->hasRole('Lecturer') ? 'lecturer' : 'student' }}";
                    const evalUrl = notification.user_member_id ? `/${rolePrefix}/evaluation/${notification.user_member_id}` : null;
                    const rank = notification.user_rank || 999;
                    const frameColor = notification.user_frame_color || '#3b82f6';
                    let imgStyle = rank <= 50 ? `border: 2px solid ${frameColor}; box-shadow: 0 0 8px ${frameColor}66; padding: 1px;` : '';

                    const avatarHtml = evalUrl
                        ? `<a href="${evalUrl}" class="text-decoration-none"><img src="${notification.user_avatar}" alt="${notification.user_name}" class="avatar" style="${imgStyle}"></a>`
                        : `<img src="${notification.user_avatar}" alt="${notification.user_name}" class="avatar" style="${imgStyle}">`;
                    const nameHtml = evalUrl
                        ? `<a href="${evalUrl}" class="text-decoration-none text-dark fw-bold">${notification.user_name}</a>`
                        : `${notification.user_name}`;
                    const titleBadge = (notification.user_title && notification.user_rank <= 50)
                        ? `<span class="badge rounded-pill px-2 py-1 ms-1" style="font-size: 0.65rem; background: #eff6ff; color: #1d4ed8; border: 1px solid #93c5fd;"><i class="fas fa-award me-1"></i>${notification.user_title}</span>`
                        : '';

                    const notificationElement = document.createElement('div');
                    notificationElement.className = 'notification-item';
                    notificationElement.innerHTML = `
                        ${avatarHtml}
                        <div class="notification-content">
                            <div class="user-name">${nameHtml}${titleBadge}</div>
                            <div class="notification-message">${notification.message}</div>
                            <div class="notification-time">${notification.time}</div>
                        </div>
                    `;
                    notificationsTimeline.appendChild(notificationElement);
                });
            })
            .catch(error => console.error('Error fetching notifications:', error));
    }
</script>

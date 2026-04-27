<script>
    document.addEventListener('DOMContentLoaded', function () {
        fetchTeamActivities();
        setInterval(fetchTeamActivities, 5000);
    });

    function formatTime(timeStr) {
        if (!timeStr) return 'N/A';
        const date = new Date(timeStr);
        return date.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });
    }

    function fetchTeamActivities() {
        const container = document.getElementById('teamActivityList');
        if (!container) return;

        fetch("{{ route('student.team.activities') }}")
            .then(res => res.json())
            .then(data => {
                let timelineHtml = '<ul class="timeline">';
                
                if (data.active_attendances.length === 0 && data.recent_checkouts.length === 0) {
                    timelineHtml = '<div class="text-center py-4"><i class="fas fa-users-slash fa-2x text-muted mb-3"></i><p class="text-muted">No team activity yet.</p></div>';
                } else {
                    // Show Active Attendances
                    data.active_attendances.forEach(att => {
                        const avatar = att.user.avatar ? (att.user.avatar.startsWith('http') ? att.user.avatar : '/' + att.user.avatar) : '/images/default-avatar.png';
                        const locationName = att.attendance_location?.name || 'Unknown Location';
                        const checkinTime = formatTime(att.checkin_time);

                        timelineHtml += `
                            <li class="timeline-item">
                                <div class="timeline-content">
                                    <small class="text-success d-block mb-1 fw-bold">Active Now</small>
                                    <div class="d-flex align-items-center mb-2">
                                        <img src="${avatar}" class="rounded-circle me-2" style="width: 35px; height: 35px; object-fit: cover;" alt="Avatar">
                                        <strong class="text-dark">${att.user.name}</strong>
                                    </div>
                                    <p class="mb-0 small text-muted">
                                        Started at <span class="fw-bold text-dark">${checkinTime}</span> in <span class="fw-bold text-primary">${locationName}</span>
                                    </p>
                                </div>
                            </li>
                        `;
                    });

                    // Show Recent Checkouts
                    data.recent_checkouts.forEach(att => {
                        const avatar = att.user.avatar ? (att.user.avatar.startsWith('http') ? att.user.avatar : '/' + att.user.avatar) : '/images/default-avatar.png';
                        const locationName = att.attendance_location?.name || 'Unknown Location';
                        const checkoutTime = formatTime(att.checkout_time);

                        timelineHtml += `
                            <li class="timeline-item">
                                <div class="timeline-content">
                                    <small class="text-danger d-block mb-1 fw-bold">Checked Out</small>
                                    <div class="d-flex align-items-center mb-2">
                                        <img src="${avatar}" class="rounded-circle me-2" style="width: 35px; height: 35px; object-fit: cover;" alt="Avatar">
                                        <strong class="text-dark">${att.user.name}</strong>
                                    </div>
                                    <p class="mb-0 small text-muted">
                                        Finished at <span class="fw-bold text-dark">${checkoutTime}</span> from <span class="fw-bold text-danger">${locationName}</span>
                                    </p>
                                </div>
                            </li>
                        `;
                    });
                }

                timelineHtml += '</ul>';
                container.innerHTML = timelineHtml;
            })
            .catch(err => {
                console.error('Error fetching team activities:', err);
                container.innerHTML = '<div class="alert alert-danger small">Failed to load team activities.</div>';
            });
    }
</script>

<style>
    .timeline { list-style: none; position: relative; padding-left: 0; margin: 0; }
    .timeline::before { content: ''; position: absolute; left: 16px; top: 0; width: 2px; height: 100%; background: #e2e8f0; z-index: 1; }
    .timeline-item { position: relative; padding-left: 45px; padding-bottom: 25px; }
    .timeline-item::before { content: ''; position: absolute; left: 12px; top: 5px; width: 10px; height: 10px; border-radius: 50%; background: #1e3a8a; border: 2px solid white; z-index: 2; }
    .timeline-content { background: #f8fafc; border-radius: 12px; padding: 12px 15px; border: 1px solid #e2e8f0; transition: all 0.3s ease; }
    .timeline-content:hover { transform: translateX(5px); background: #fff; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
</style>

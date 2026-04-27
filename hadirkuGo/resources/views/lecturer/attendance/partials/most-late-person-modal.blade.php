<!-- {{ __('Most Late') }} Person Modal -->
<div class="modal fade" id="mostLatePersonModal" tabindex="-1" aria-labelledby="mostLatePersonModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #dc3545; color: white;">
                <h5 class="modal-title" id="mostLatePersonModalLabel">
                    <i class="fas fa-clock me-2" style="color: #ffc107;"></i>
                    Top 10 {{ __('Most Late') }} Person Rankings
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @if($mostLatePersonRankings->isNotEmpty())
                    <div class="alert alert-danger mb-3">
                        <i class="fas fa-info-circle"></i>
                        <strong>{{ __('Most Late') }} Person</strong> is the person who checked <strong>in</strong> last each day — arrived the latest in the team.
                        <br><small class="text-muted">Sorted by total count (descending).</small>
                    </div>
                    <div class="list-group">
                        @foreach($mostLatePersonRankings as $index => $data)
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <h6 class="mb-1">
                                            @if($index === 0)
                                                <i class="fas fa-trophy me-2" style="color: gold;"></i>
                                            @elseif($index === 1)
                                                <i class="fas fa-medal me-2" style="color: silver;"></i>
                                            @elseif($index === 2)
                                                <i class="fas fa-medal me-2" style="color: #cd7f32;"></i>
                                            @else
                                                <span class="badge bg-secondary me-2">#{{ $index + 1 }}</span>
                                            @endif
                                            <strong>{{ ucfirst(preg_replace('/[0-9]/', '', $data['user']->name)) }}</strong>
                                        </h6>
                                    </div>
                                    <span class="badge bg-danger rounded-pill">{{ $data['count'] }}x Late</span>
                                </div>
                                @if(!empty($data['dates']))
                                    <div class="ms-4">
                                        <small class="text-muted d-block mb-2">
                                            <i class="fas fa-calendar-check me-1"></i>
                                            <strong>Dates arrived last (checkin time):</strong>
                                        </small>
                                        <div class="d-flex flex-wrap gap-1">
                                            @foreach($data['dates'] as $dateInfo)
                                                <span class="badge bg-light text-dark border" style="font-size: 0.75rem;">
                                                    {{ \Carbon\Carbon::parse($dateInfo['date'])->format('d M') }} 
                                                    <span class="text-danger">({{ \Carbon\Carbon::parse($dateInfo['time'])->format('H:i') }})</span>
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        Great! No one is late this month.
                    </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

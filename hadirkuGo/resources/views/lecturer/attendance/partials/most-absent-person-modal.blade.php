<!-- {{ __('Most Absent') }} Person Modal -->
<div class="modal fade" id="mostAbsentPersonModal" tabindex="-1" aria-labelledby="mostAbsentPersonModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #ffc107; color: #000;">
                <h5 class="modal-title" id="mostAbsentPersonModalLabel">
                    <i class="fas fa-user-slash me-2"></i>
                    Top 10 {{ __('Most Absent') }} Person Rankings
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @if($mostAbsentPersonRankings->isNotEmpty())
                    <div class="alert alert-warning mb-3">
                        <i class="fas fa-info-circle"></i>
                        <strong>{{ __('Most Absent') }} Person</strong> is the person who is most frequently absent (excluding weekends and public holidays).
                        <br><small class="text-muted">Sorted by total absent days (descending).</small>
                    </div>
                    <div class="list-group">
                        @foreach($mostAbsentPersonRankings as $index => $data)
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
                                    <span class="badge bg-warning text-dark rounded-pill">{{ $data['count'] }}x Absent</span>
                                </div>
                                @if(!empty($data['dates']))
                                    <div class="ms-4">
                                        <small class="text-muted d-block mb-2">
                                            <i class="fas fa-calendar-times me-1"></i>
                                            <strong>Absent dates:</strong>
                                        </small>
                                        <div class="d-flex flex-wrap gap-1">
                                            @foreach($data['dates'] as $dateInfo)
                                                <span class="badge bg-light text-dark border" style="font-size: 0.75rem;">
                                                    {{ \Carbon\Carbon::parse($dateInfo['date'])->format('d M') }}
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
                        Perfect! Everyone attended this month.
                    </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

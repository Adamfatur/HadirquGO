{{-- Motivational Quote Section for Attendance Success --}}
@if(isset($quote) && $quote)
<div class="quote-section text-center my-4" id="quoteSection" style="opacity: 0; transform: translateY(20px); transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);">
    <div class="position-relative d-inline-block" style="max-width: 480px;">
        <i class="fas fa-quote-left position-absolute text-primary" style="font-size: 1.5rem; opacity: 0.15; top: -8px; left: -5px;"></i>
        <p class="fst-italic px-4 mb-2" style="font-size: 1.05rem; line-height: 1.7; color: #4a5568;" id="quoteText">
            {{ $quote->quote }}
        </p>
        <i class="fas fa-quote-right position-absolute text-primary" style="font-size: 1.5rem; opacity: 0.15; bottom: 15px; right: -5px;"></i>
        <p class="small fw-semibold mb-0" style="color: #1e3a8a;">— {{ $quote->author }}</p>
    </div>
</div>
<script>
    // Smooth fade-in for quote
    setTimeout(function() {
        var qs = document.getElementById('quoteSection');
        if (qs) { qs.style.opacity = '1'; qs.style.transform = 'translateY(0)'; }
    }, 800);
</script>
@endif

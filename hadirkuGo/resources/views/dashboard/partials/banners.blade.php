@if($activeBanners->isNotEmpty())
<div class="banner-mobile-only mb-3" style="position: relative; overflow: hidden; border-radius: 15px; width: 100%; height: auto; background-color: #f0f0f0; margin-bottom: 0px;">
    @if($activeBanners->count() > 1)
        <div class="banner-slider" style="display: flex; overflow-x: auto; scroll-snap-type: x mandatory; width: 100%;">
            @foreach($activeBanners as $banner)
                <div class="banner-slide" style="flex: 0 0 100%; width: 100%; scroll-snap-align: start; position: relative;">
                    <img src="{{ $banner->banner_url }}" alt="Banner Informasi" style="width: 100%; max-height: 200px; height: auto; display: block; border-radius: 15px; object-fit: cover;">
                </div>
            @endforeach
        </div>
        <style>
            .banner-slider::-webkit-scrollbar {
                display: none;
            }
            .banner-slider {
                -ms-overflow-style: none;
                scrollbar-width: none;
            }
        </style>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const slider = document.querySelector('.banner-slider');
                let scrollPosition = 0;

                setInterval(() => {
                    if (!slider) return;

                    scrollPosition += slider.offsetWidth;
                    if (scrollPosition >= slider.scrollWidth) {
                        scrollPosition = 0;
                    }
                    slider.scroll({
                        left: scrollPosition,
                        behavior: 'smooth'
                    });
                }, 5000);
            });
        </script>
    @elseif($activeBanners->count() == 1)
        <img src="{{ $activeBanners->first()->banner_url }}" alt="Banner Informasi" style="width: 100%; max-height: 200px; height: auto; display: block; border-radius: 15px; object-fit: cover;">
    @endif
</div>
@endif
<style>
    @media (min-width: 768px) {
        .banner-mobile-only {
            display: none !important;
        }
    }
</style>

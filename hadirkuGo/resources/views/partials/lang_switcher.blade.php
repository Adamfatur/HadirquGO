{{-- Language Switcher - compact pill toggle --}}
<div class="lang-switcher" style="position: fixed; top: 12px; right: 12px; z-index: 1050; display: flex; gap: 3px; background: rgba(0,0,0,0.4); border-radius: 16px; padding: 2px; backdrop-filter: blur(8px);">
    <a href="{{ route('lang.switch', 'en') }}" class="lang-opt {{ app()->getLocale() === 'en' ? 'active' : '' }}" style="padding: 3px 12px; border-radius: 14px; font-size: 0.7rem; font-weight: 600; text-decoration: none; color: rgba(255,255,255,0.5); transition: all 0.2s; {{ app()->getLocale() === 'en' ? 'background: #f4d03f; color: #1a1a2e;' : '' }}">EN</a>
    <a href="{{ route('lang.switch', 'id') }}" class="lang-opt {{ app()->getLocale() === 'id' ? 'active' : '' }}" style="padding: 3px 12px; border-radius: 14px; font-size: 0.7rem; font-weight: 600; text-decoration: none; color: rgba(255,255,255,0.5); transition: all 0.2s; {{ app()->getLocale() === 'id' ? 'background: #f4d03f; color: #1a1a2e;' : '' }}">ID</a>
</div>

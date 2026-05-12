@php
    use App\Models\Setting;

    $photoUrl = !empty(optional($muzaki)->foto) ? asset('storage/' . $muzaki->foto) : asset('assets/img/avatar1.jpg');
    $setting = Setting::first();
    $waNumber = preg_replace('/\D+/', '', $setting->telepon ?? '');
    if (str_starts_with($waNumber, '0')) {
        $waNumber = '62' . substr($waNumber, 1);
    } elseif (str_starts_with($waNumber, '8')) {
        $waNumber = '62' . $waNumber;
    }
@endphp

<style>
    body { padding-bottom: 86px; }
    .muzaki-bottom-nav {
        position: fixed;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 1000;
        background: #fff;
        border-top: 1px solid #edf0f3;
        box-shadow: 0 -8px 24px rgba(15, 23, 42, .08);
    }
    .muzaki-nav-inner {
        max-width: 540px;
        min-height: 72px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: repeat({{ $waNumber ? 5 : 4 }}, 1fr);
        align-items: center;
        gap: 2px;
        padding: 8px 8px calc(8px + env(safe-area-inset-bottom));
    }
    .muzaki-nav-item {
        border: 0;
        background: transparent;
        color: #64748b;
        text-decoration: none;
        font-size: 11px;
        font-weight: 700;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 3px;
        line-height: 1.1;
    }
    .muzaki-nav-item i { font-size: 21px; line-height: 1; }
    .muzaki-nav-item.active { color: #fc8c04; }
    .muzaki-nav-item.logout { color: #dc2626; }
    .muzaki-avatar {
        width: 46px;
        height: 46px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid rgba(255,255,255,.8);
        background: #fff;
    }
</style>

<div class="muzaki-bottom-nav">
    <div class="muzaki-nav-inner">
        <a href="{{ route('muzaki.mobile') }}" class="muzaki-nav-item {{ request()->routeIs('muzaki.mobile') ? 'active' : '' }}">
            <i class="bi bi-house-door-fill"></i>
            <span>Home</span>
        </a>
        <a href="{{ route('muzaki.profile') }}" class="muzaki-nav-item {{ request()->routeIs('muzaki.profile') ? 'active' : '' }}">
            <i class="bi bi-person-circle"></i>
            <span>Profil</span>
        </a>
        <a href="{{ route('muzaki.riwayat') }}" class="muzaki-nav-item {{ request()->routeIs('muzaki.riwayat') || request()->routeIs('muzaki.riwayat.detail') || request()->routeIs('muzaki.program.detail') ? 'active' : '' }}">
            <i class="bi bi-clock-history"></i>
            <span>Riwayat</span>
        </a>
        @if($waNumber)
            <a href="https://wa.me/{{ $waNumber }}" target="_blank" rel="noopener" class="muzaki-nav-item">
                <i class="bi bi-whatsapp"></i>
                <span>WhatsApp</span>
            </a>
        @endif
        <form action="{{ route('muzaki.logout') }}" method="POST" class="m-0">
            @csrf
            <button type="submit" class="muzaki-nav-item logout w-100">
                <i class="bi bi-box-arrow-right"></i>
                <span>Logout</span>
            </button>
        </form>
    </div>
</div>

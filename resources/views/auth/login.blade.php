<!doctype html>
<html lang="id">
  <head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <title>Login {{ $setting->nama_perusahaan ?? 'LAZISMU' }}</title>

    <link rel="icon" type="image/png" href="{{ asset($setting->path_logo ?? 'logo.png') }}"/>

    <link href="{{ asset('tabler/dist/css/tabler.min.css') }}" rel="stylesheet"/>
    <link href="{{ asset('tabler/dist/css/tabler-flags.min.css') }}" rel="stylesheet"/>
    <link href="{{ asset('tabler/dist/css/tabler-payments.min.css') }}" rel="stylesheet"/>
    <link href="{{ asset('tabler/dist/css/tabler-vendors.min.css') }}" rel="stylesheet"/>
    <link href="{{ asset('tabler/dist/css/demo.min.css') }}" rel="stylesheet"/>

    <style>
      @import url('https://rsms.me/inter/inter.css');

      :root {
        --tblr-font-sans-serif: 'Inter Var', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
        --lazis-orange: #fc8c04;
        --lazis-orange-dark: #de7900;
        --lazis-orange-soft: #fff2df;
        --lazis-ink: #232323;
        --lazis-muted: #767676;
        --lazis-border: rgba(222, 121, 0, .16);
      }

      * {
        box-sizing: border-box;
      }

      body {
        min-height: 100vh;
        margin: 0;
        color: var(--lazis-ink);
        font-feature-settings: "cv03", "cv04", "cv11";
        background:
          radial-gradient(circle at 12% 18%, rgba(252, 140, 4, .18) 0 12%, transparent 28%),
          radial-gradient(circle at 90% 8%, rgba(255, 193, 7, .18) 0 10%, transparent 28%),
          linear-gradient(135deg, #fffaf3 0%, #ffffff 46%, #fff7ea 100%);
        opacity: 0;
        transition: opacity .45s ease;
      }

      body.loaded {
        opacity: 1;
      }

      .auth-shell {
        position: relative;
        min-height: 100vh;
        display: flex;
        align-items: stretch;
        overflow: hidden;
      }

      .auth-shell::before,
      .auth-shell::after {
        content: "";
        position: absolute;
        border: 1px solid rgba(252, 140, 4, .14);
        border-radius: 999px;
        pointer-events: none;
      }

      .auth-shell::before {
        width: 320px;
        height: 320px;
        left: -120px;
        top: -90px;
      }

      .auth-shell::after {
        width: 430px;
        height: 430px;
        right: -190px;
        bottom: -180px;
      }

      .brand-panel {
        position: relative;
        flex: 1 1 58%;
        min-height: 100vh;
        padding: clamp(28px, 5vw, 72px);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        gap: 40px;
        background: linear-gradient(145deg, rgba(252, 140, 4, .97), rgba(222, 121, 0, .94));
        color: #fff;
        overflow: hidden;
      }

      .brand-panel::before {
        content: "";
        position: absolute;
        inset: 0;
        background:
          linear-gradient(90deg, rgba(255, 255, 255, .14) 1px, transparent 1px),
          linear-gradient(180deg, rgba(255, 255, 255, .14) 1px, transparent 1px);
        background-size: 46px 46px;
        mask-image: linear-gradient(135deg, rgba(0, 0, 0, .65), transparent 72%);
        pointer-events: none;
      }

      .brand-panel::after {
        content: "";
        position: absolute;
        width: 640px;
        height: 640px;
        right: -260px;
        top: 10%;
        border: 96px solid rgba(255, 255, 255, .12);
        border-radius: 50%;
        pointer-events: none;
      }

      .brand-content,
      .impact-grid,
      .developer-credit {
        position: relative;
        z-index: 1;
      }

      .brand-chip {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        width: fit-content;
        min-height: 38px;
        padding: 8px 14px;
        border: 1px solid rgba(255, 255, 255, .32);
        border-radius: 999px;
        background: rgba(255, 255, 255, .14);
        backdrop-filter: blur(12px);
        font-weight: 700;
      }

      .brand-chip-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: #fff;
        box-shadow: 0 0 0 6px rgba(255, 255, 255, .18);
      }

      .brand-title {
        max-width: 710px;
        margin: 30px 0 18px;
        font-size: clamp(36px, 5.5vw, 76px);
        line-height: .96;
        letter-spacing: 0;
        font-weight: 900;
      }

      .brand-copy {
        max-width: 620px;
        margin: 0;
        color: rgba(255, 255, 255, .88);
        font-size: clamp(16px, 1.45vw, 20px);
        line-height: 1.65;
      }

      .impact-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 14px;
        max-width: 740px;
      }

      .impact-card {
        min-height: 124px;
        padding: 20px;
        border: 1px solid rgba(255, 255, 255, .28);
        border-radius: 8px;
        background: rgba(255, 255, 255, .16);
        backdrop-filter: blur(16px);
        box-shadow: 0 24px 60px rgba(120, 62, 0, .2);
      }

      .impact-card svg {
        width: 28px;
        height: 28px;
        margin-bottom: 18px;
      }

      .impact-card strong {
        display: block;
        font-size: 15px;
        line-height: 1.35;
      }

      .impact-card span {
        display: block;
        margin-top: 5px;
        color: rgba(255, 255, 255, .78);
        font-size: 13px;
      }

      .developer-credit {
        display: flex;
        align-items: center;
        gap: 12px;
        color: rgba(255, 255, 255, .82);
        font-size: 13px;
      }

      .developer-credit img {
        max-height: 34px;
        width: auto;
        filter: brightness(0) invert(1);
        opacity: .9;
      }

      .form-panel {
        position: relative;
        z-index: 1;
        flex: 0 0 min(520px, 42%);
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: clamp(20px, 4vw, 56px);
      }

      .login-card {
        width: min(100%, 430px);
        padding: clamp(24px, 4vw, 38px);
        border: 1px solid var(--lazis-border);
        border-radius: 8px;
        background: rgba(255, 255, 255, .9);
        box-shadow: 0 24px 80px rgba(92, 59, 18, .12);
        backdrop-filter: blur(18px);
      }

      .logo-wrap {
        display: flex;
        justify-content: center;
        margin-bottom: 24px;
      }

      .logo-wrap img {
        width: min(180px, 58vw);
        max-height: 112px;
        object-fit: contain;
      }

      .login-heading {
        margin: 0;
        text-align: center;
        color: var(--lazis-orange-dark);
        font-size: clamp(24px, 3.2vw, 32px);
        line-height: 1.18;
        font-weight: 900;
      }

      .login-subtitle {
        margin: 10px auto 28px;
        max-width: 320px;
        color: var(--lazis-muted);
        text-align: center;
        line-height: 1.55;
      }

      .form-label {
        color: #4b4b4b;
        font-weight: 700;
      }

      .form-control,
      .input-group-flat .form-control,
      .input-group-text {
        min-height: 50px;
        border-color: #ead7bd;
        background-color: #fffdf9;
      }

      .form-control:focus {
        border-color: var(--lazis-orange);
        box-shadow: 0 0 0 .25rem rgba(252, 140, 4, .16);
      }

      .input-icon {
        color: var(--lazis-orange-dark);
      }

      .password-toggle {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #8b755b;
      }

      .password-toggle:hover {
        color: var(--lazis-orange-dark);
      }

      .btn-primary {
        min-height: 50px;
        border-color: var(--lazis-orange);
        background: linear-gradient(135deg, var(--lazis-orange), var(--lazis-orange-dark));
        color: #fff;
        font-weight: 800;
        box-shadow: 0 16px 30px rgba(252, 140, 4, .24);
      }

      .btn-primary:hover,
      .btn-primary:focus {
        border-color: #bd6500;
        background: linear-gradient(135deg, #ff9f24, #c96f00);
        color: #fff;
      }

      .login-helper {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin: 16px 0 24px;
        color: var(--lazis-muted);
        font-size: 13px;
      }

      .login-helper a {
        color: var(--lazis-orange-dark);
        font-weight: 700;
      }

      .form-check-input:checked {
        border-color: var(--lazis-orange);
        background-color: var(--lazis-orange);
      }

      .mobile-brand {
        display: none;
        margin: -4px 0 22px;
        padding: 14px;
        border-radius: 8px;
        background: var(--lazis-orange-soft);
        color: var(--lazis-orange-dark);
        font-weight: 800;
        text-align: center;
      }

      #splashScreen,
      #loadingOverlay {
        position: fixed;
        inset: 0;
        z-index: 10000;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        background: #fffaf3;
        transition: opacity .45s ease;
      }

      #loadingOverlay {
        display: none;
        background: rgba(255, 250, 243, .92);
        backdrop-filter: blur(10px);
      }

      #splashScreen img,
      #loadingOverlay img {
        width: 132px;
        max-width: 44vw;
        height: auto;
      }

      #splashScreen p,
      #loadingOverlay p {
        margin: 20px 0 0;
        color: var(--lazis-orange-dark);
        font-weight: 800;
      }

      .loader-ring {
        width: 42px;
        height: 42px;
        margin-top: 22px;
        border: 4px solid rgba(252, 140, 4, .18);
        border-top-color: var(--lazis-orange);
        border-radius: 50%;
        animation: spin .8s linear infinite;
      }

      @keyframes spin {
        to {
          transform: rotate(360deg);
        }
      }

      @media (max-width: 991.98px) {
        .auth-shell {
          min-height: 100svh;
          display: block;
          padding: 22px;
        }

        .brand-panel {
          display: none;
        }

        .form-panel {
          min-height: calc(100svh - 44px);
          padding: 0;
        }

        .login-card {
          width: 100%;
        }

        .mobile-brand {
          display: block;
        }
      }

      @media (max-width: 575.98px) {
        .auth-shell {
          padding: 14px;
        }

        .form-panel {
          align-items: flex-start;
          min-height: calc(100svh - 28px);
          padding-top: 10px;
        }

        .login-card {
          padding: 22px 18px;
        }

        .login-helper {
          align-items: flex-start;
          flex-direction: column;
        }
      }
    </style>
  </head>

  <body>
    <div id="splashScreen">
      <img src="{{ asset($setting->path_logo ?? 'logo.png') }}" alt="Logo {{ $setting->nama_perusahaan ?? 'LAZISMU' }}">
      <p>Memuat sistem...</p>
      <div class="loader-ring" aria-hidden="true"></div>
    </div>

    <script src="{{ asset('tabler/dist/js/demo-theme.min.js') }}"></script>

    <main class="auth-shell" id="loginPage" style="display:none;">
      <section class="brand-panel" aria-label="Layanan zakat">
        <div class="brand-content">
          <div class="brand-chip">
            <span class="brand-chip-dot" aria-hidden="true"></span>
            Lembaga Zakat, Infak, dan Sedekah
          </div>
          <h1 class="brand-title">{{ $setting->nama_perusahaan ?? 'LAZISMU' }}</h1>
          <p class="brand-copy">
            Kelola amanah umat dengan pencatatan yang rapi, transparan, dan mudah dipantau oleh tim pengelola.
          </p>
        </div>

        <div class="impact-grid">
          <div class="impact-card">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-heart-handshake" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
              <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
              <path d="M19.5 12.572l-7.5 7.428l-7.5 -7.428a5 5 0 1 1 7.5 -6.566a5 5 0 1 1 7.5 6.572"/>
              <path d="M12 6l-2 4l4 3l2 -5"/>
            </svg>
            <strong>Amanah Tersalurkan</strong>
            <span>Data donasi dan program lebih mudah dipantau.</span>
          </div>
          <div class="impact-card">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-report-money" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
              <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
              <path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2"/>
              <path d="M9 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z"/>
              <path d="M14 11h-2.5a1.5 1.5 0 0 0 0 3h1a1.5 1.5 0 0 1 0 3h-2.5"/>
              <path d="M12 17v1m0 -8v1"/>
            </svg>
            <strong>Laporan Terarah</strong>
            <span>Rekap zakat, infak, dan sedekah lebih siap digunakan.</span>
          </div>
          <div class="impact-card">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-users-group" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
              <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
              <path d="M10 13a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"/>
              <path d="M8 21v-1a4 4 0 0 1 8 0v1"/>
              <path d="M15 5a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"/>
              <path d="M17 10h1a4 4 0 0 1 4 4v1"/>
              <path d="M5 5a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"/>
              <path d="M2 15v-1a4 4 0 0 1 4 -4h1"/>
            </svg>
            <strong>Tim Terhubung</strong>
            <span>Akses kerja harian dibuat ringkas untuk amil.</span>
          </div>
        </div>

        <div class="developer-credit">
          <img src="{{ asset('piclogo.png') }}" alt="PartnerInCode">
          <span>&copy; PartnerInCode Project {{ date('Y') }}</span>
        </div>
      </section>

      <section class="form-panel">
        <div class="login-card">
          <div class="mobile-brand">Lembaga Zakat, Infak, dan Sedekah</div>

          <div class="logo-wrap">
            <img src="{{ asset($setting->path_logo ?? 'logo.png') }}" alt="Logo {{ $setting->nama_perusahaan ?? 'LAZISMU' }}">
          </div>

          <h2 class="login-heading">Selamat Datang</h2>
          <p class="login-subtitle">Masuk untuk mengelola data penghimpunan, program, dan laporan lembaga.</p>

          @if (session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
          @endif

          @if ($errors->any())
            <div class="alert alert-danger">
              <ul class="mb-0">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <form method="POST" action="{{ route('login') }}" autocomplete="off">
            @csrf
            <div class="mb-3">
              <label class="form-label" for="username">Username</label>
              <div class="input-group input-group-flat">
                <span class="input-group-text input-icon">
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                    <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0"/>
                    <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"/>
                  </svg>
                </span>
                <input type="text" id="username" name="username" class="form-control" placeholder="Masukkan username"
                       value="{{ old('username') }}" required autofocus autocomplete="username">
              </div>
            </div>

            <div class="mb-0">
              <label class="form-label" for="password">Password</label>
              <div class="input-group input-group-flat">
                <span class="input-group-text input-icon">
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                    <path d="M5 13a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v6a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2z"/>
                    <path d="M11 16a1 1 0 1 0 2 0a1 1 0 0 0 -2 0"/>
                    <path d="M8 11v-4a4 4 0 1 1 8 0v4"/>
                  </svg>
                </span>
                <input type="password" id="password" name="password" class="form-control"
                       placeholder="Masukkan password" required autocomplete="current-password">
                <span class="input-group-text">
                  <a href="#" class="password-toggle" title="Tampilkan password" id="toggle-password" aria-label="Tampilkan password">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                      <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                      <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"/>
                      <path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6"/>
                    </svg>
                  </a>
                </span>
              </div>
            </div>

            <div class="login-helper">
              <label class="form-check mb-0">
                <input type="checkbox" name="remember" class="form-check-input">
                <span class="form-check-label">Ingat saya</span>
              </label>

              @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}">Lupa password?</a>
              @endif
            </div>

            <button type="submit" id="loginBtn" class="btn btn-primary w-100">
              Masuk Sistem
            </button>
          </form>
        </div>
      </section>
    </main>

    <div id="loadingOverlay">
      <img src="{{ asset($setting->path_logo ?? 'logo.png') }}" alt="Loading">
      <p>Memproses login...</p>
      <div class="loader-ring" aria-hidden="true"></div>
    </div>

    <script src="{{ asset('tabler/dist/js/tabler.min.js') }}" defer></script>
    <script>
      window.addEventListener('load', () => {
        const splash = document.getElementById('splashScreen');
        const loginPage = document.getElementById('loginPage');

        document.body.classList.add('loaded');

        setTimeout(() => {
          splash.style.opacity = '0';
          setTimeout(() => {
            splash.style.display = 'none';
            loginPage.style.display = '';
          }, 450);
        }, 900);
      });

      const passwordToggle = document.getElementById('toggle-password');
      if (passwordToggle) {
        passwordToggle.addEventListener('click', function(e) {
          e.preventDefault();
          const passwordInput = document.getElementById('password');
          const isHidden = passwordInput.type === 'password';
          passwordInput.type = isHidden ? 'text' : 'password';
          this.setAttribute('title', isHidden ? 'Sembunyikan password' : 'Tampilkan password');
          this.setAttribute('aria-label', isHidden ? 'Sembunyikan password' : 'Tampilkan password');
        });
      }

      const loginBtn = document.getElementById('loginBtn');
      const loadingOverlay = document.getElementById('loadingOverlay');

      if (loginBtn) {
        loginBtn.addEventListener('click', function(e) {
          if (!this.form.checkValidity()) {
            return;
          }

          e.preventDefault();
          loadingOverlay.style.display = 'flex';
          this.disabled = true;
          this.innerHTML = `
            <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
            Memproses...
          `;
          setTimeout(() => this.form.submit(), 250);
        });
      }
    </script>
  </body>
</html>

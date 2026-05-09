<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lazismu Muzaki</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <style>
        :root {
            --brand: #fc8c04;
            --brand-dark: #d97706;
            --ink: #172033;
            --muted: #64748b;
            --soft: #fff7ed;
        }
        body { min-height: 100vh; background: linear-gradient(180deg, #fff7ed 0%, #ffffff 48%, #f7f9fb 100%); color: var(--ink); }
        .mobile-shell { max-width: 520px; margin: 0 auto; }
        .top-band { background: linear-gradient(135deg, var(--brand) 0%, #ffb14a 100%); color: #fff; border-bottom-left-radius: 26px; border-bottom-right-radius: 26px; }
        .login-card, .program-card { border: 0; border-radius: 14px; box-shadow: 0 14px 35px rgba(15, 23, 42, .10); overflow: hidden; }
        .btn-brand { background: var(--brand); border-color: var(--brand); color: #fff; font-weight: 700; min-height: 52px; }
        .btn-brand:hover { background: var(--brand-dark); border-color: var(--brand-dark); color: #fff; }
        .btn-scan { border-color: var(--brand); color: var(--brand-dark); font-weight: 700; min-height: 52px; }
        .program-banner { aspect-ratio: 16 / 8; object-fit: cover; background: #ffedd5; }
        .progress { height: 10px; background: #ffe4c7; }
        .progress-bar { background: var(--brand); }
        .section-title { font-size: 1.05rem; font-weight: 800; }
        .small-muted { color: var(--muted); font-size: .9rem; }
    </style>
</head>
<body>
    <div class="mobile-shell">
        <div class="top-band px-3 pt-4 pb-5">
            <div class="d-flex align-items-center gap-3 mb-3">
                <img src="{{ asset($setting->path_logo ?? 'logo.png') }}" alt="Logo" style="height: 58px;" class="bg-white rounded p-1">
                <div>
                    <div class="fw-bold fs-4 lh-sm">Lazismu</div>
                    <div class="opacity-75">Portal Muzaki</div>
                </div>
            </div>
            <p class="mb-0">Masuk untuk melihat identitas, riwayat setoran, dan program berjalan.</p>
        </div>

        <main class="px-3 pb-4" style="margin-top: -34px;">
            <div class="card login-card mb-4">
                <div class="card-body p-3 p-sm-4">
                    <h1 class="h4 fw-bold mb-2">Login Muzaki</h1>
                    <p class="small-muted mb-3">Gunakan NIK, Nomor Induk Muzaki, atau scan barcode kartu.</p>

                    @if($errors->any())
                        <div class="alert alert-danger">{{ $errors->first() }}</div>
                    @endif

                    <form method="POST" action="{{ route('muzaki.login.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold">NIK / Nomor Induk</label>
                            <input type="text" name="login_id" id="loginId" class="form-control form-control-lg" maxlength="30" value="{{ old('login_id') }}" placeholder="MZK-2605-ABCDE" required autofocus>
                        </div>
                        <button class="btn btn-brand btn-lg w-100 mb-2">Masuk</button>
                        <button type="button" class="btn btn-scan btn-lg w-100" id="scanButton">Scan Barcode</button>
                    </form>

                    <div id="scanPanel" class="mt-3 d-none">
                        <video id="scanVideo" class="w-100 rounded border" playsinline></video>
                        <div class="small-muted mt-2">Arahkan kamera ke barcode/QR pada kartu muzaki.</div>
                    </div>

                    <div class="d-grid gap-2 mt-3">
                        <a href="{{ route('muzaki.register') }}" class="btn btn-light btn-lg">Registrasi</a>
                        <!-- <a href="{{ route('login') }}" class="text-center text-decoration-none small-muted">Login Admin</a> -->
                    </div>
                </div>
            </div>

            <div class="section-title mb-2">Program Berjalan</div>
            <div class="d-grid gap-3">
                @forelse($programs as $program)
                    @php
                        $target = (float) ($program->target ?? 0);
                        $terkumpul = (float) ($program->terkumpul ?? 0);
                        $progress = $target > 0 ? min(100, ($terkumpul / $target) * 100) : 0;
                    @endphp
                    <div class="card program-card">
                        @if($program->banner_path)
                            <img src="{{ asset('storage/' . $program->banner_path) }}" class="program-banner w-100" alt="{{ $program->nama_program }}">
                        @else
                            <div class="program-banner d-flex align-items-center justify-content-center fw-bold text-warning">Lazismu</div>
                        @endif
                        <div class="card-body p-3">
                            <h2 class="h5 fw-bold mb-1">{{ $program->nama_program }}</h2>
                            <div class="small-muted mb-3">{{ $program->lokasi ?: 'Lokasi belum diisi' }}</div>
                            <div class="d-flex justify-content-between small mb-1">
                                <span>Terkumpul</span>
                                <strong>Rp {{ number_format($terkumpul, 0, ',', '.') }}</strong>
                            </div>
                            <div class="progress mb-2">
                                <div class="progress-bar" style="width: {{ $progress }}%"></div>
                            </div>
                            <div class="small-muted">Target Rp {{ number_format($target, 0, ',', '.') }}</div>
                        </div>
                    </div>
                @empty
                    <div class="alert alert-info">Belum ada program aktif.</div>
                @endforelse
            </div>
        </main>
    </div>

    <script>
        const scanButton = document.getElementById('scanButton');
        const scanPanel = document.getElementById('scanPanel');
        const video = document.getElementById('scanVideo');
        const input = document.getElementById('loginId');

        scanButton.addEventListener('click', async function () {
            if (!('BarcodeDetector' in window)) {
                alert('Browser ini belum mendukung scan langsung. Ketik NIK atau Nomor Induk Muzaki dari kartu.');
                return;
            }

            scanPanel.classList.remove('d-none');
            const stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } });
            video.srcObject = stream;
            await video.play();

            const detector = new BarcodeDetector({ formats: ['qr_code', 'code_128', 'ean_13'] });
            const scan = async () => {
                const codes = await detector.detect(video);
                if (codes.length) {
                    input.value = codes[0].rawValue;
                    stream.getTracks().forEach(track => track.stop());
                    input.form.submit();
                    return;
                }
                requestAnimationFrame(scan);
            };
            scan();
        });
    </script>
</body>
</html>

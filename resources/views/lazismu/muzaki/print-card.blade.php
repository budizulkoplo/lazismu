<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Kartu Muzaki</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 24px; color: #111827; }
        .card {
            width: 520px;
            height: 310px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            padding: 20px 22px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            background: #fffaf3;
            position: relative;
        }
        .header { display: flex; align-items: flex-start; }
        .logo { width: 168px; max-height: 64px; object-fit: contain; }
        .title { color: #fc8c04; font-size: 20px; font-weight: 800; line-height: 1.1; margin-bottom: 6px; }
        .content { display: flex; justify-content: space-between; gap: 20px; align-items: flex-end; }
        .name { font-size: 24px; font-weight: 800; margin-bottom: 8px; }
        .meta { font-size: 15px; margin-bottom: 6px; }
        .login-info { font-size: 13px; margin-top: 10px; color: #374151; }
        .qr { text-align: center; background: #fff; padding: 12px; border-radius: 8px; border: 1px solid #f6d8b2; }
        @media print { button, a { display: none; } body { margin: 0; } }
    </style>
</head>
<body>
    @php
        $loginUrl = 'https://tinyurl.com/muzakikaliwungu';
        $fileName = 'kartu-muzaki-' . \Illuminate\Support\Str::slug($muzaki->nama) . '.png';
        $shareText = 'Kartu Muzaki ' . $muzaki->nama . '. Akses login: ' . $loginUrl . ' | ID: ' . $muzaki->login_code;
    @endphp
    <button onclick="window.print()">Cetak</button>
    <button type="button" id="downloadCard">Unduh PNG</button>
    <button type="button" id="shareCard">Share WA</button>
    <a href="{{ route('lazismu.muzaki.index') }}">Kembali</a>

    <div class="card" id="muzakiCard">
        <div class="header">
            <img src="{{ asset('logopt/1776842680_lazismu.png') }}" class="logo" alt="Lazismu">
        </div>

        <div class="content">
            <div>
                <div class="title">Kartu Muzaki</div>
                <div class="name">{{ $muzaki->nama }}</div>
                <div class="meta">Jenis: {{ ucfirst($muzaki->jenis_muzaki ?? 'pribadi') }}</div>
                <div class="meta">ID: {{ $muzaki->login_code }}</div>
                <div class="meta">NIK: {{ $muzaki->nik ?: '-' }}</div>
                <div class="meta">No HP: {{ $muzaki->no_hp ?: '-' }}</div>
                <div class="login-info">Login muzaki: <strong>{{ $loginUrl }}</strong></div>
            </div>
            <div class="qr">
                {!! QrCode::size(130)->generate($muzaki->login_code) !!}
                <div style="margin-top:8px;font-weight:700;">{{ $muzaki->login_code }}</div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
    <script>
        const fileName = @json($fileName);
        const shareText = @json($shareText);
        const requestedAction = @json(request('action'));

        async function cardBlob() {
            const canvas = await html2canvas(document.getElementById('muzakiCard'), {
                backgroundColor: '#fffaf3',
                scale: 2,
                useCORS: true
            });

            return new Promise(resolve => canvas.toBlob(resolve, 'image/png'));
        }

        document.getElementById('downloadCard').addEventListener('click', async function() {
            const blob = await cardBlob();
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = fileName;
            link.click();
            URL.revokeObjectURL(link.href);
        });

        async function shareCard() {
            const blob = await cardBlob();
            const file = new File([blob], fileName, { type: 'image/png' });

            try {
                if (navigator.canShare && navigator.canShare({ files: [file] })) {
                    await navigator.share({ text: shareText, files: [file] });
                    return;
                }
            } catch (error) {
                // Browser can require a direct user gesture for file sharing.
            }

            window.open('https://wa.me/?text=' + encodeURIComponent(shareText), '_blank');
        }

        document.getElementById('shareCard').addEventListener('click', shareCard);

        window.addEventListener('load', function() {
            if (requestedAction === 'print') {
                window.print();
            } else if (requestedAction === 'download') {
                document.getElementById('downloadCard').click();
            } else if (requestedAction === 'share') {
                shareCard();
            }
        });
    </script>
</body>
</html>

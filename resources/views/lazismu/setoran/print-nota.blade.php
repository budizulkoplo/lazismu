<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    @php
        $jenisNota = strtolower(trim($setoran->kodeSetoran->jenis_setoran ?? 'setoran'));
        $judulNota = $jenisNota === 'program' && $setoran->program
            ? 'Nota ' . $setoran->program->nama_program
            : 'Nota ' . ucfirst($jenisNota);
    @endphp
    <title>{{ $judulNota }}</title>
    <style>
        @page { size: 58mm auto; margin: 0; }
        * { box-sizing: border-box; }
        body {
            width: 58mm;
            margin: 0;
            color: #111827;
            font-family: Arial, sans-serif;
            font-size: 10px;
            line-height: 1.35;
        }
        .nota {
            width: 58mm;
            padding: 4mm 3mm;
        }
        .center { text-align: center; }
        .logo {
            max-width: 24mm;
            max-height: 16mm;
            object-fit: contain;
            margin: 0 auto 2mm;
        }
        h1 {
            font-size: 13px;
            margin: 0 0 1mm;
        }
        .muted { color: #4b5563; }
        .line {
            border-top: 1px dashed #111827;
            margin: 2.5mm 0;
        }
        .row {
            display: flex;
            justify-content: space-between;
            gap: 3mm;
            margin: 1mm 0;
        }
        .label { color: #4b5563; }
        .value {
            text-align: right;
            font-weight: 700;
            word-break: break-word;
        }
        .total {
            font-size: 14px;
            font-weight: 800;
        }
        .actions {
            padding: 4mm;
        }
        @media print {
            .actions { display: none; }
        }
    </style>
</head>
<body>
    @php
        $fileName = 'nota-setoran-' . $setoran->id . '.png';
        $shareText = $judulNota . ' a.n. ' . ($setoran->muzaki->nama ?? '-') . ' sebesar Rp ' . number_format((float) $setoran->nominal, 0, ',', '.') . '.';
    @endphp
    <div class="actions">
        <button onclick="window.print()">Cetak Nota</button>
        <button type="button" id="downloadNota">Unduh PNG</button>
        <button type="button" id="shareNota">Share WA</button>
        <a href="{{ route('lazismu.setoran.index') }}">Kembali</a>
    </div>

    <div class="nota" id="setoranNota">
        <div class="center">
            <img src="{{ asset('logopt/1776842680_lazismu.png') }}" class="logo" alt="Lazismu">
            <h1>{{ $judulNota }}</h1>
            <div>{{ optional($setoran->created_at)->format('d/m/Y') }}</div>
        </div>

        <div class="line"></div>

        <div class="row">
            <span class="label">Muzaki</span>
            <span class="value">{{ $setoran->muzaki->nama ?? '-' }}</span>
        </div>
        <div class="row">
            <span class="label">ID</span>
            <span class="value">{{ $setoran->muzaki->login_code ?? '-' }}</span>
        </div>
        <div class="row total">
            <span>Total</span>
            <span>Rp {{ number_format($setoran->nominal, 0, ',', '.') }}</span>
        </div>

        <div class="line"></div>

        <div class="center">
            <div>Terima kasih.</div>
            <div class="muted">Semoga Allah membalas kebaikan Anda.</div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
    <script>
        const fileName = @json($fileName);
        const shareText = @json($shareText);
        const requestedAction = @json(request('action'));

        async function notaBlob() {
            const canvas = await html2canvas(document.getElementById('setoranNota'), {
                backgroundColor: '#ffffff',
                scale: 3,
                useCORS: true
            });

            return new Promise(resolve => canvas.toBlob(resolve, 'image/png'));
        }

        document.getElementById('downloadNota').addEventListener('click', async function() {
            const blob = await notaBlob();
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = fileName;
            link.click();
            URL.revokeObjectURL(link.href);
        });

        async function shareNota() {
            const blob = await notaBlob();
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

        document.getElementById('shareNota').addEventListener('click', shareNota);

        window.addEventListener('load', function() {
            if (requestedAction === 'print') {
                window.print();
            } else if (requestedAction === 'download') {
                document.getElementById('downloadNota').click();
            } else if (requestedAction === 'share') {
                shareNota();
            }
        });
    </script>
</body>
</html>

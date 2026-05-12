<x-app-layout>
    <x-slot name="pagetitle">Dashboard Lazismu</x-slot>

    <div class="app-content">
        <div class="container-fluid py-4">
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                <div>
                    <h3 class="mb-1">Dashboard Lazismu</h3>
                    <p class="text-muted mb-0">Kelola data muzaki, program, kode setoran, dan transaksi dana zakat, infaq, serta program.</p>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#scanMuzakiModal">
                        <i class="bi bi-qr-code-scan"></i> Scan QR Muzaki
                    </button>
                    <a href="{{ route('muzaki.login') }}" class="btn btn-outline-warning">
                        <i class="bi bi-person-badge"></i> Portal Muzaki
                    </a>
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <small class="text-muted">Total Muzaki</small>
                            <h2 class="mb-0">{{ number_format($totalMuzaki) }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <small class="text-muted">Program Aktif</small>
                            <h2 class="mb-0">{{ number_format($programActive) }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <small class="text-muted">Jumlah Setoran</small>
                            <h2 class="mb-0">{{ number_format($totalSetoran) }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm h-100 bg-warning-subtle">
                        <div class="card-body">
                            <small class="text-muted">Total Dana</small>
                            <h2 class="mb-0">Rp {{ number_format($totalDana, 0, ',', '.') }}</h2>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100 bg-success-subtle">
                        <div class="card-body">
                            <small class="text-muted">Nominal Bisa Digunakan</small>
                            <h2 class="mb-0">Rp {{ number_format($totalDigunakan, 0, ',', '.') }}</h2>
                            <p class="mb-0 text-muted">Zakat 70%, infaq 80%, program 100% masuk program.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100 bg-info-subtle">
                        <div class="card-body">
                            <small class="text-muted">Bagian PDM</small>
                            <h2 class="mb-0">Rp {{ number_format($totalPdm, 0, ',', '.') }}</h2>
                            <p class="mb-0 text-muted">Zakat 30% dan infaq 20%.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <a href="{{ route('lazismu.muzaki.index') }}" class="card border-0 shadow-sm text-decoration-none h-100">
                        <div class="card-body">
                            <h5 class="mb-1">Muzaki</h5>
                            <p class="text-muted mb-0">Master data Muzaki.</p>
                        </div>
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="{{ route('lazismu.program.index') }}" class="card border-0 shadow-sm text-decoration-none h-100">
                        <div class="card-body">
                            <h5 class="mb-1">Program</h5>
                            <p class="text-muted mb-0">Kelola program infaq aktif beserta target lokasi dan periode.</p>
                        </div>
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="{{ route('lazismu.setoran.index') }}" class="card border-0 shadow-sm text-decoration-none h-100">
                        <div class="card-body">
                            <h5 class="mb-1">Setoran</h5>
                            <p class="text-muted mb-0">Input transaksi zakat, infaq, atau setoran program.</p>
                        </div>
                    </a>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-transparent">
                            <h5 class="mb-0">Ringkasan Dana</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-3">
                                <span>Zakat</span>
                                <strong>Rp {{ number_format($ringkasanJenis['zakat'] ?? 0, 0, ',', '.') }}</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span>Infaq</span>
                                <strong>Rp {{ number_format($ringkasanJenis['infaq'] ?? 0, 0, ',', '.') }}</strong>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Program</span>
                                <strong>Rp {{ number_format($ringkasanJenis['program'] ?? 0, 0, ',', '.') }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Setoran Terbaru</h5>
                            <a href="{{ route('lazismu.setoran.index') }}" class="btn btn-sm btn-outline-warning">Lihat Semua</a>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-sm align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Muzaki</th>
                                        <th>Jenis</th>
                                        <th>Program</th>
                                        <th class="text-end">Nominal</th>
                                        <th class="text-end">Bisa Digunakan</th>
                                        <th class="text-end">PDM</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($setoranTerbaru as $item)
                                        <tr>
                                            <td>{{ optional($item->created_at)->format('d/m/Y') }}</td>
                                            <td>{{ $item->muzaki->nama ?? '-' }}</td>
                                            <td>{{ ucfirst($item->kodeSetoran->jenis_setoran ?? '-') }}</td>
                                            <td>{{ $item->program->nama_program ?? '-' }}</td>
                                            <td class="text-end">Rp {{ number_format($item->nominal, 0, ',', '.') }}</td>
                                            <td class="text-end">Rp {{ number_format($item->nominal_digunakan_calculated, 0, ',', '.') }}</td>
                                            <td class="text-end">Rp {{ number_format($item->nominal_pdm_calculated, 0, ',', '.') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-4">Belum ada setoran.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="scanMuzakiModal" tabindex="-1" aria-labelledby="scanMuzakiModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header">
                    <h5 class="modal-title" id="scanMuzakiModalLabel">Scan QR Muzaki</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">ID / NIK Muzaki</label>
                        <div class="input-group">
                            <input type="text" class="form-control js-dashboard-scan-code" placeholder="Scan QR atau ketik ID/NIK">
                            <button type="button" class="btn btn-warning js-dashboard-start-scan">
                                <i class="bi bi-camera"></i> Scan
                            </button>
                        </div>
                    </div>
                    <video class="w-100 rounded border d-none js-dashboard-scan-video" playsinline></video>
                    <div class="small text-muted mt-2 js-dashboard-scan-status">
                        Scan QR muzaki untuk membuka halaman input setoran.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-warning js-dashboard-open-setoran">Buka Setoran</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const muzakis = @json($muzakiScanOptions);
            const setoranIndexUrl = @json(route('lazismu.setoran.index'));
            const modalEl = document.getElementById('scanMuzakiModal');
            const codeInput = modalEl?.querySelector('.js-dashboard-scan-code');
            const scanButton = modalEl?.querySelector('.js-dashboard-start-scan');
            const openButton = modalEl?.querySelector('.js-dashboard-open-setoran');
            const video = modalEl?.querySelector('.js-dashboard-scan-video');
            const status = modalEl?.querySelector('.js-dashboard-scan-status');
            let scanStream = null;
            let scanning = false;

            function setStatus(message, isError = false) {
                if (!status) return;
                status.textContent = message;
                status.classList.toggle('text-danger', isError);
                status.classList.toggle('text-muted', !isError);
            }

            function stopScan() {
                scanning = false;
                if (scanStream) {
                    scanStream.getTracks().forEach(track => track.stop());
                    scanStream = null;
                }
                if (video) {
                    video.pause();
                    video.srcObject = null;
                    video.classList.add('d-none');
                }
            }

            function findMuzaki(code) {
                const cleaned = String(code || '').trim();
                if (!cleaned) return null;

                return muzakis.find(item => item.code === cleaned || item.nik === cleaned) || null;
            }

            function openSetoranByCode(code) {
                const muzaki = findMuzaki(code);
                if (!muzaki) {
                    setStatus('Muzaki dengan ID/NIK tersebut tidak ditemukan.', true);
                    return;
                }

                window.location.href = `${setoranIndexUrl}?muzaki_id=${encodeURIComponent(muzaki.id)}`;
            }

            async function startScan() {
                if (!('BarcodeDetector' in window)) {
                    setStatus('Browser belum mendukung scan langsung. Ketik ID/NIK pada kolom.', true);
                    codeInput?.focus();
                    return;
                }

                try {
                    stopScan();
                    scanStream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } });
                    video.srcObject = scanStream;
                    video.classList.remove('d-none');
                    await video.play();

                    scanning = true;
                    setStatus('Arahkan kamera ke QR muzaki.');

                    const detector = new BarcodeDetector({ formats: ['qr_code', 'code_128', 'ean_13'] });
                    const scan = async () => {
                        if (!scanning) return;

                        const codes = await detector.detect(video);
                        if (codes.length) {
                            codeInput.value = codes[0].rawValue;
                            stopScan();
                            openSetoranByCode(codeInput.value);
                            return;
                        }

                        requestAnimationFrame(scan);
                    };

                    scan();
                } catch (error) {
                    stopScan();
                    setStatus('Kamera tidak bisa dibuka. Pastikan izin kamera aktif atau ketik ID/NIK.', true);
                }
            }

            scanButton?.addEventListener('click', startScan);
            openButton?.addEventListener('click', function () {
                openSetoranByCode(codeInput?.value);
            });
            codeInput?.addEventListener('change', function () {
                openSetoranByCode(this.value);
            });
            modalEl?.addEventListener('hidden.bs.modal', function () {
                stopScan();
                if (codeInput) codeInput.value = '';
                setStatus('Scan QR muzaki untuk membuka halaman input setoran.');
            });
        });
    </script>
</x-app-layout>

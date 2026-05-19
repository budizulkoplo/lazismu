<x-app-layout>
    <x-slot name="pagetitle">Setoran</x-slot>

    <x-slot name="csscustom">
        <style>
            .action-dropdown .dropdown-menu { min-width: 9rem; }
            .action-dropdown .dropdown-item { font-size: .82rem; }
        </style>
    </x-slot>

    <div class="app-content">
        <div class="container-fluid py-4">
            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                <div>
                    <h3 class="mb-1">Input Setoran</h3>
                    <p class="text-muted mb-0">Pilih muzaki terlebih dahulu, lalu pilih jenis setoran yang akan diinput.</p>
                </div>
            </div>

            @include('lazismu.partials.alert')

            <div class="card border-0 shadow-sm mb-3 quick-setoran-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-3">
                        <div>
                            <h5 class="mb-1">Pilih Muzaki</h5>
                            <p class="text-muted mb-0">Cari nama, ID, NIK, atau scan barcode kartu muzaki.</p>
                        </div>
                    </div>
                    <div class="row g-3 align-items-end">
                        <div class="col-lg-7">
                            <label class="form-label fw-semibold">Cari Muzaki</label>
                            <select class="form-select js-select2 js-quick-muzaki-select">
                                <option value="">Ketik nama / ID / NIK muzaki</option>
                                @foreach($muzakis as $muzakiOption)
                                    <option
                                        value="{{ $muzakiOption->id }}"
                                        data-code="{{ $muzakiOption->login_code }}"
                                        data-nik="{{ $muzakiOption->nik }}"
                                        @selected(optional($selectedMuzaki)->id === $muzakiOption->id)
                                    >
                                        {{ $muzakiOption->nama }} - {{ $muzakiOption->login_code }}{{ $muzakiOption->nik ? ' - ' . $muzakiOption->nik : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-5">
                            <label class="form-label fw-semibold">Scan / ID Muzaki</label>
                            <div class="input-group">
                                <input type="text" class="form-control js-quick-code" placeholder="Scan barcode atau ketik ID">
                                <button class="btn btn-outline-success js-quick-scan" type="button">
                                    <i class="bi bi-qr-code-scan"></i> Scan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if($selectedMuzaki)
                <div class="card border-0 shadow-sm mb-3 selected-flow-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-3">
                            <div>
                                <div class="text-muted small">Muzaki Terpilih</div>
                                <h4 class="mb-1">{{ $selectedMuzaki->nama }}</h4>
                                <div class="text-muted">
                                    ID {{ $selectedMuzaki->login_code }}{{ $selectedMuzaki->nik ? ' | NIK ' . $selectedMuzaki->nik : '' }}
                                </div>
                                <div class="text-muted">{{ $selectedMuzaki->alamat ?: 'Alamat belum diisi' }}</div>
                            </div>
                            <a href="{{ route('lazismu.setoran.index') }}" class="btn btn-light">Ganti Muzaki</a>
                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col-md-6 col-xl-3">
                                <button
                                    type="button"
                                    class="btn btn-warning w-100 setoran-action-btn js-start-setoran"
                                    data-muzaki="{{ $selectedMuzaki->id }}"
                                    data-kode="{{ optional($kodeByJenis->get('zakat'))->id }}"
                                    data-jenis="zakat"
                                    @disabled(!$kodeByJenis->has('zakat'))
                                >
                                    Zakat
                                </button>
                            </div>
                            <div class="col-md-6 col-xl-3">
                                <button
                                    type="button"
                                    class="btn btn-info w-100 setoran-action-btn js-start-setoran"
                                    data-muzaki="{{ $selectedMuzaki->id }}"
                                    data-kode="{{ optional($kodeByJenis->get('infaq'))->id }}"
                                    data-jenis="infaq"
                                    @disabled(!$kodeByJenis->has('infaq'))
                                >
                                    Infaq
                                </button>
                            </div>
                            @foreach($programs as $program)
                                <div class="col-md-6 col-xl-3">
                                    <button
                                        type="button"
                                        class="btn btn-outline-warning w-100 setoran-action-btn js-start-setoran"
                                        data-muzaki="{{ $selectedMuzaki->id }}"
                                        data-kode="{{ optional($kodeByJenis->get('program'))->id }}"
                                        data-jenis="program"
                                        data-program="{{ $program->id }}"
                                        @disabled(!$kodeByJenis->has('program'))
                                    >
                                        <span class="d-block fw-semibold">{{ $program->nama_program }}</span>
                                        <small>Setor Program</small>
                                    </button>
                                </div>
                            @endforeach
                        </div>

                        @if($selectedProgramStats->isNotEmpty())
                            <div class="row g-3 mb-3">
                                @foreach($selectedProgramStats as $stat)
                                    <div class="col-md-6 col-xl-4">
                                        <div class="program-progress-card">
                                            <div class="d-flex justify-content-between gap-2 mb-1">
                                                <div class="fw-semibold text-truncate">{{ $stat['program']->nama_program }}</div>
                                                <small>{{ $stat['target'] > 0 ? $stat['percent'] . '%' : '-' }}</small>
                                            </div>
                                            <div class="small text-muted mb-2">
                                                Setor Rp {{ number_format($stat['total'], 0, ',', '.') }}
                                                @if($stat['target'] > 0)
                                                    / Target Rp {{ number_format($stat['target'], 0, ',', '.') }}
                                                @else
                                                    / Target belum diisi
                                                @endif
                                            </div>
                                            <div class="progress program-progress">
                                                <div class="progress-bar bg-warning" style="width: {{ $stat['percent'] }}%"></div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <div class="card border-0 bg-white">
                            <div class="card-header bg-white">
                                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                    <h5 class="mb-0">Riwayat Setoran {{ $selectedMuzaki->nama }}</h5>
                                    <form method="GET" action="{{ route('lazismu.setoran.index') }}" class="row g-2 align-items-end history-filter-form">
                                        <input type="hidden" name="muzaki_id" value="{{ $selectedMuzaki->id }}">
                                        <div class="col-auto">
                                            <label class="form-label small mb-1">Tipe</label>
                                            <select name="jenis_setoran" class="form-select form-select-sm">
                                                <option value="">Semua</option>
                                                @foreach($kodeSetorans as $kode)
                                                    <option value="{{ $kode->jenis_setoran }}" @selected(request('jenis_setoran') === $kode->jenis_setoran)>{{ ucfirst($kode->jenis_setoran) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-auto">
                                            <label class="form-label small mb-1">Program</label>
                                            <select name="program_id" class="form-select form-select-sm">
                                                <option value="">Semua</option>
                                                @foreach($filterPrograms as $program)
                                                    <option value="{{ $program->id }}" @selected((string) request('program_id') === (string) $program->id)>{{ $program->nama_program }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-auto d-flex gap-1">
                                            <button class="btn btn-sm btn-warning">Filter</button>
                                            <a href="{{ route('lazismu.setoran.index', ['muzaki_id' => $selectedMuzaki->id]) }}" class="btn btn-sm btn-light">Reset</a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-sm align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Jenis</th>
                                            <th>Program</th>
                                            <th class="text-end">Nominal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($selectedSetorans as $item)
                                            <tr>
                                                <td>{{ optional($item->created_at)->format('d/m/Y') }}</td>
                                                <td>{{ ucfirst($item->kodeSetoran->jenis_setoran ?? '-') }}</td>
                                                <td>{{ $item->program->nama_program ?? '-' }}</td>
                                                <td class="text-end">Rp {{ number_format($item->nominal, 0, ',', '.') }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center text-muted py-4">Belum ada riwayat setoran.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @unless($selectedMuzaki)
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                        <div>
                            <h5 class="mb-1">Riwayat Semua Transaksi</h5>
                            <p class="text-muted mb-0">Daftar lengkap untuk koreksi atau pengecekan setoran.</p>
                        </div>
                    </div>
                    <form method="GET" action="{{ route('lazismu.setoran.index') }}" class="row g-2 align-items-end mb-3 history-filter-form">
                        <div class="col-md-4 col-lg-3">
                            <label class="form-label small mb-1">Tipe Setoran</label>
                            <select name="jenis_setoran" class="form-select form-select-sm js-history-jenis-filter">
                                <option value="">Semua tipe</option>
                                @foreach($kodeSetorans as $kode)
                                    <option value="{{ $kode->jenis_setoran }}" @selected(request('jenis_setoran') === $kode->jenis_setoran)>{{ ucfirst($kode->jenis_setoran) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 col-lg-3 js-history-program-filter">
                            <label class="form-label small mb-1">Program</label>
                            <select name="program_id" class="form-select form-select-sm">
                                <option value="">Semua program</option>
                                @foreach($filterPrograms as $program)
                                    <option value="{{ $program->id }}" @selected((string) request('program_id') === (string) $program->id)>{{ $program->nama_program }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 col-lg-3 d-flex gap-2">
                            <button class="btn btn-sm btn-warning">Filter</button>
                            <a href="{{ route('lazismu.setoran.index') }}" class="btn btn-sm btn-light">Reset</a>
                        </div>
                    </form>
                    <div class="table-responsive">
                        <table class="table table-sm table-striped align-middle js-lazismu-table w-100">
                            <thead class="table-light">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Muzaki</th>
                                    <th>Jenis</th>
                                    <th>Program</th>
                                    <th class="text-end">Nominal</th>
                                    <th width="90">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($setorans as $setoran)
                                    <tr>
                                        <td>{{ optional($setoran->created_at)->format('d/m/Y') }}</td>
                                        <td>
                                            <div>{{ $setoran->muzaki->nama ?? '-' }}</div>
                                            <small class="text-muted">{{ $setoran->muzaki->nik ?? '-' }}</small>
                                        </td>
                                        <td>{{ ucfirst($setoran->kodeSetoran->jenis_setoran ?? '-') }}</td>
                                        <td>{{ $setoran->program->nama_program ?? '-' }}</td>
                                        <td class="text-end">Rp {{ number_format($setoran->nominal, 0, ',', '.') }}</td>
                                        <td class="text-nowrap">
                                            @php
                                                $shareText = 'Nota ' . ucfirst($setoran->kodeSetoran->jenis_setoran ?? 'setoran') . ' a.n. ' . ($setoran->muzaki->nama ?? '-') . ' sebesar Rp ' . number_format((float) $setoran->nominal, 0, ',', '.') . '.';
                                            @endphp
                                            <div class="dropdown action-dropdown">
                                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                    Aksi
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li><button class="dropdown-item" type="button" data-bs-toggle="modal" data-bs-target="#editSetoranModal{{ $setoran->id }}">Edit</button></li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li><button class="dropdown-item js-hidden-page-action" type="button" data-url="{{ route('lazismu.setoran.print', ['setoran' => $setoran, 'action' => 'print']) }}">Cetak nota</button></li>
                                                    <li><button class="dropdown-item js-hidden-page-action" type="button" data-url="{{ route('lazismu.setoran.print', ['setoran' => $setoran, 'action' => 'download']) }}">Unduh nota</button></li>
                                                    <li><a class="dropdown-item" href="https://wa.me/?text={{ urlencode($shareText) }}" target="_blank">Share WA</a></li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <form action="{{ route('lazismu.setoran.destroy', $setoran) }}" method="POST" onsubmit="return confirm('Hapus transaksi setoran ini?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button class="dropdown-item text-danger">Hapus</button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endunless
        </div>
    </div>

    @include('lazismu.setoran.partials.create-modal', ['muzakis' => $muzakis, 'kodeSetorans' => $kodeSetorans, 'programs' => $programs])
    @unless($selectedMuzaki)
        @foreach($setorans as $setoran)
            @include('lazismu.setoran.partials.edit-modal', ['setoran' => $setoran, 'muzakis' => $muzakis, 'kodeSetorans' => $kodeSetorans, 'programs' => $programs])
        @endforeach
    @endunless

    <div class="modal fade" id="muzakiPickerModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <div>
                        <h5 class="modal-title mb-1">Daftar Muzaki</h5>
                        <small class="text-muted">Cari dan pilih muzaki berdasarkan detail identitasnya.</small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-striped align-middle js-lazismu-table js-muzaki-picker-table w-100">
                            <thead class="table-light">
                                <tr>
                                        <th>ID/NIK</th>
                                    <th>Nama</th>
                                    <th>Alamat</th>
                                    <th>No HP</th>
                                    <th>Email</th>
                                    <th width="110">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($muzakis as $muzakiOption)
                                    <tr>
                                        <td>
                                            <div>{{ $muzakiOption->login_code }}</div>
                                            <small class="text-muted">{{ $muzakiOption->nik ?: '-' }}</small>
                                        </td>
                                        <td>{{ $muzakiOption->nama }}</td>
                                        <td>{{ $muzakiOption->alamat ?: '-' }}</td>
                                        <td>{{ $muzakiOption->no_hp ?: '-' }}</td>
                                        <td>{{ $muzakiOption->email ?: '-' }}</td>
                                        <td>
                                            <button
                                                type="button"
                                                class="btn btn-sm btn-warning js-pilih-muzaki"
                                                data-id="{{ $muzakiOption->id }}"
                                            >
                                                Pilih
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-slot name="jscustom">
        @include('lazismu.partials.datatable-select2')
        <style>
            .quick-setoran-card {
                background: linear-gradient(135deg, #fff7ed 0%, #ffffff 58%, #fff3df 100%);
            }
            .selected-flow-card {
                background: #fffaf3;
            }
            .setoran-action-btn {
                min-height: 76px;
                border-radius: 12px;
                font-weight: 700;
                white-space: normal;
            }
            .setoran-form-card {
                background: #fffaf3;
                border: 1px solid #f6d8b2;
                border-radius: 12px;
                padding: 1rem;
            }
            .selected-muzaki-card {
                border: 1px solid #f6d8b2;
                border-left: 5px solid #fc8c04;
                border-radius: 10px;
                background: #fffaf3;
                padding: .75rem;
            }
            .program-choice-panel {
                border: 1px dashed #fc8c04;
                background: #fff7ed;
                border-radius: 12px;
                padding: .85rem;
                min-height: 100%;
            }
            .nominal-input-box {
                background: #fff7ed;
                border: 1px solid #f6d8b2;
                border-radius: 12px;
                padding: .75rem;
            }
            .nominal-input-box .input-group-text {
                background: #fc8c04;
                border-color: #fc8c04;
                color: #fff;
                font-weight: 800;
            }
            .nominal-input-box .form-control {
                font-weight: 800;
                color: #111827;
            }
            .program-progress-card {
                border: 1px solid #f6d8b2;
                background: #fff;
                border-radius: 12px;
                padding: .85rem;
            }
            .program-progress {
                height: .55rem;
                background: #f3f4f6;
            }
            .split-preview {
                background: #f8fafc;
                border: 1px solid #e2e8f0;
                border-radius: 12px;
                padding: .75rem;
            }
            .history-filter-form .form-select,
            .history-filter-form .btn {
                min-height: 31px;
            }
        </style>
        <script>
            let activeSetoranForm = null;
            const programStats = @json($programStatsForJs);
            const rupiahFormatter = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                maximumFractionDigits: 0
            });

            function formatRupiah(value) {
                return rupiahFormatter.format(Number(value || 0));
            }

            function toggleProgramField(formId) {
                const form = document.getElementById(formId);
                if (!form) return;

                const jenisSelect = form.querySelector('.js-jenis-setoran');
                const programWrapper = form.querySelector('.js-program-wrapper');
                const programSelect = form.querySelector('.js-program-select');
                const targetWrapper = form.querySelector('.js-target-wrapper');
                const targetInput = form.querySelector('.js-target-program-input');

                const selectedText = (jenisSelect.options[jenisSelect.selectedIndex]?.dataset.jenis || '').trim().toLowerCase();
                const showProgram = selectedText === 'program';

                programWrapper.classList.toggle('d-none', !showProgram);
                programSelect.required = showProgram;

                if (!showProgram) {
                    programSelect.value = '';
                    if (targetInput) targetInput.value = '';
                    if (targetWrapper) targetWrapper.classList.add('d-none');
                    if (window.jQuery && $(programSelect).data('select2')) {
                        $(programSelect).val('').trigger('change.select2');
                    }
                }

                updateProgramProgress(form);
                renderSplitPreview(form);
            }

            function getSelectedProgramStats(form) {
                const muzakiId = form.querySelector('.js-muzaki-select')?.value;
                const programId = form.querySelector('.js-program-select')?.value;

                return programStats?.[muzakiId]?.[programId] || {
                    total: 0,
                    target: 0,
                    is_first: true
                };
            }

            function updateProgramProgress(form) {
                const jenisSelect = form.querySelector('.js-jenis-setoran');
                const programSelect = form.querySelector('.js-program-select');
                const targetWrapper = form.querySelector('.js-target-wrapper');
                const targetInput = form.querySelector('.js-target-program-input');
                const updateTargetButton = form.querySelector('.js-update-target-btn');
                const targetHelp = form.querySelector('.js-target-help');
                const progress = form.querySelector('.js-program-progress');

                if (!jenisSelect || !programSelect || !targetWrapper || !targetInput || !progress) return;

                const jenis = (jenisSelect.options[jenisSelect.selectedIndex]?.dataset.jenis || '').trim().toLowerCase();
                const showProgram = jenis === 'program' && programSelect.value;

                if (!showProgram) {
                    targetWrapper.classList.add('d-none');
                    targetInput.required = false;
                    targetInput.readOnly = false;
                    updateTargetButton?.classList.add('d-none');
                    progress.innerHTML = '';
                    return;
                }

                const stats = getSelectedProgramStats(form);
                const existingTarget = Number(stats.target || 0);
                const nominal = Number(form.querySelector('.js-nominal-input')?.value || 0);
                const total = Number(stats.total || 0);
                const effectiveTarget = Number(targetInput.value || existingTarget || 0);
                const afterTotal = total + nominal;
                const percent = effectiveTarget > 0 ? Math.min(100, (afterTotal / effectiveTarget) * 100) : 0;
                const hasExistingTarget = existingTarget > 0;

                targetWrapper.classList.remove('d-none');

                if (!targetInput.value && existingTarget > 0) {
                    targetInput.value = existingTarget;
                }

                targetInput.required = !hasExistingTarget;
                targetInput.readOnly = hasExistingTarget;
                updateTargetButton?.classList.toggle('d-none', !hasExistingTarget);

                if (targetHelp) {
                    targetHelp.textContent = hasExistingTarget
                        ? 'Target sudah tersimpan. Klik Update Target jika ingin mengubah nominal target.'
                        : 'Target wajib diisi untuk setoran program pertama muzaki.';
                }

                progress.innerHTML = `
                    <div class="program-progress-card">
                        <div class="d-flex justify-content-between gap-2 mb-1">
                            <span class="fw-semibold">Progress muzaki di program ini</span>
                            <span>${effectiveTarget > 0 ? percent.toFixed(1).replace('.0', '') + '%' : '-'}</span>
                        </div>
                        <div class="small text-muted mb-2">
                            Sudah setor ${formatRupiah(total)}
                            ${nominal > 0 ? `&middot; Setelah input ${formatRupiah(afterTotal)}` : ''}
                            ${effectiveTarget > 0 ? `&middot; Target ${formatRupiah(effectiveTarget)}` : '&middot; Target belum diisi'}
                        </div>
                        <div class="progress program-progress">
                            <div class="progress-bar bg-warning" style="width: ${percent}%"></div>
                        </div>
                    </div>
                `;
            }

            function renderSelectedMuzaki(form) {
                const select = form.querySelector('.js-muzaki-select');
                const summary = form.querySelector('.js-selected-muzaki');
                const option = select?.selectedOptions?.[0];

                if (!select || !summary || !option || !option.value) {
                    if (summary) summary.innerHTML = 'Pilih muzaki untuk melihat detail identitas.';
                    return;
                }

                const nama = option.dataset.nama || option.textContent.trim();
                const nik = option.dataset.nik || '-';
                const code = option.dataset.code || nik;
                const alamat = option.dataset.alamat || '-';
                const hp = option.dataset.hp || '-';
                const email = option.dataset.email || '-';
                const escapeHtml = (value) => String(value || '-')
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#039;');

                summary.innerHTML = `
                    <div class="selected-muzaki-card">
                        <div class="fw-semibold text-dark">${escapeHtml(nama)}</div>
                        <div>ID: ${escapeHtml(code)} &middot; NIK: ${escapeHtml(nik)}</div>
                        <div>Alamat: ${escapeHtml(alamat)}</div>
                        <div>No HP: ${escapeHtml(hp)} &middot; Email: ${escapeHtml(email)}</div>
                    </div>
                `;
            }

            const setoranIndexUrl = @json(route('lazismu.setoran.index'));

            function openCreateSetoranWithMuzaki(muzakiId, kodeId = null, programId = null) {
                const modalEl = document.getElementById('createSetoranModal');
                const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
                const form = document.getElementById('createSetoranForm');
                activeSetoranForm = form;

                resetCreateSetoranForm();

                modalEl.addEventListener('shown.bs.modal', function prefillSetoranForm() {
                    if (muzakiId) {
                        setMuzakiForForm(form, muzakiId);
                    }
                    if (kodeId) {
                        setKodeSetoranForForm(form, kodeId);
                    }
                    if (programId) {
                        setProgramForForm(form, programId);
                    }

                    const quickSelect = document.querySelector('.js-quick-muzaki-select');
                    if (quickSelect) {
                        if (window.jQuery && $(quickSelect).data('select2')) {
                            $(quickSelect).val('').trigger('change.select2');
                        } else {
                            quickSelect.value = '';
                        }
                    }
                }, { once: true });

                modal.show();
            }

            function resetCreateSetoranForm() {
                const form = document.getElementById('createSetoranForm');
                if (!form) return;

                form.reset();

                form.querySelectorAll('.js-select2').forEach(function(select) {
                    if (window.jQuery && $(select).data('select2')) {
                        $(select).val('').trigger('change');
                    } else {
                        select.value = '';
                        select.dispatchEvent(new Event('change'));
                    }
                });

                const dateInput = form.querySelector('input[name="created_at"]');
                if (dateInput && !dateInput.value) {
                    dateInput.value = new Date().toISOString().slice(0, 10);
                }

                form.querySelector('.js-scan-code').value = '';
                renderSelectedMuzaki(form);
                toggleProgramField(form.id);
                renderSplitPreview(form);
            }

            function setMuzakiForForm(form, muzakiId) {
                const select = form.querySelector('.js-muzaki-select');
                if (!select) return;

                select.value = muzakiId;

                if (window.jQuery && $(select).data('select2')) {
                    $(select).val(muzakiId).trigger('change');
                } else {
                    select.dispatchEvent(new Event('change'));
                }

                renderSelectedMuzaki(form);
            }

            function setKodeSetoranForForm(form, kodeId) {
                const select = form.querySelector('.js-jenis-setoran');
                if (!select) return;

                select.value = kodeId;
                if (window.jQuery && $(select).data('select2')) {
                    $(select).val(kodeId).trigger('change');
                } else {
                    select.dispatchEvent(new Event('change'));
                }
                toggleProgramField(form.id);
            }

            function setProgramForForm(form, programId) {
                const select = form.querySelector('.js-program-select');
                if (!select) return;

                select.value = programId;
                if (window.jQuery && $(select).data('select2')) {
                    $(select).val(programId).trigger('change');
                } else {
                    select.dispatchEvent(new Event('change'));
                }
            }

            function redirectToMuzaki(muzakiId) {
                if (!muzakiId) return;
                window.location.href = `${setoranIndexUrl}?muzaki_id=${encodeURIComponent(muzakiId)}`;
            }

            function renderSplitPreview(form) {
                const preview = form.querySelector('.js-split-preview');
                const jenisSelect = form.querySelector('.js-jenis-setoran');
                const nominalInput = form.querySelector('.js-nominal-input');

                if (!preview || !jenisSelect || !nominalInput) return;

                const jenis = (jenisSelect.options[jenisSelect.selectedIndex]?.dataset.jenis || '').trim().toLowerCase();
                const nominal = Number(nominalInput.value || 0);
                let label = 'Total setoran';

                if (jenis === 'zakat') {
                    label = 'Setoran zakat';
                } else if (jenis === 'infaq') {
                    label = 'Setoran infaq';
                } else if (jenis === 'program') {
                    label = 'Setoran program';
                }

                preview.innerHTML = `
                    <div class="split-preview">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-semibold">${label}</span>
                            <strong>${formatRupiah(nominal)}</strong>
                        </div>
                    </div>
                `;

                updateProgramProgress(form);
            }

            function bindSetoranForms(context) {
                const scope = context || document;

                scope.querySelectorAll('.js-jenis-setoran').forEach(function(select) {
                    if (select.dataset.boundJenis === '1') return;
                    select.dataset.boundJenis = '1';
                    select.addEventListener('change', function() {
                        toggleProgramField(this.form.id);
                    });

                    toggleProgramField(select.form.id);
                });

                scope.querySelectorAll('.js-nominal-input').forEach(function(input) {
                    if (input.dataset.boundNominal === '1') return;
                    input.dataset.boundNominal = '1';
                    input.addEventListener('input', function() {
                        renderSplitPreview(this.form);
                    });
                    renderSplitPreview(input.form);
                });

                scope.querySelectorAll('.js-program-select').forEach(function(select) {
                    if (select.dataset.boundProgram === '1') return;
                    select.dataset.boundProgram = '1';
                    select.addEventListener('change', function() {
                        const targetInput = this.form.querySelector('.js-target-program-input');
                        if (targetInput) {
                            const stats = getSelectedProgramStats(this.form);
                            targetInput.value = Number(stats.target || 0) > 0 ? stats.target : '';
                        }
                        updateProgramProgress(this.form);
                    });
                    updateProgramProgress(select.form);
                });

                scope.querySelectorAll('.js-target-program-input').forEach(function(input) {
                    if (input.dataset.boundTarget === '1') return;
                    input.dataset.boundTarget = '1';
                    input.addEventListener('input', function() {
                        updateProgramProgress(this.form);
                    });
                });

                scope.querySelectorAll('.js-update-target-btn').forEach(function(button) {
                    if (button.dataset.boundUpdateTarget === '1') return;
                    button.dataset.boundUpdateTarget = '1';
                    button.addEventListener('click', function() {
                        const input = this.form.querySelector('.js-target-program-input');
                        if (!input) return;

                        input.readOnly = false;
                        input.required = true;
                        input.focus();
                        this.classList.add('d-none');

                        const help = this.form.querySelector('.js-target-help');
                        if (help) {
                            help.textContent = 'Ubah nominal target, lalu simpan setoran untuk menyimpan target baru.';
                        }
                    });
                });

                scope.querySelectorAll('.js-muzaki-select').forEach(function(select) {
                    if (select.dataset.boundMuzaki === '1') return;
                    select.dataset.boundMuzaki = '1';
                    select.addEventListener('change', function() {
                        renderSelectedMuzaki(this.form);
                        const targetInput = this.form.querySelector('.js-target-program-input');
                        if (targetInput) {
                            const stats = getSelectedProgramStats(this.form);
                            targetInput.value = Number(stats.target || 0) > 0 ? stats.target : '';
                        }
                        updateProgramProgress(this.form);
                    });

                    renderSelectedMuzaki(select.form);
                });

                scope.querySelectorAll('.js-open-muzaki-picker').forEach(function(button) {
                    if (button.dataset.boundPicker === '1') return;
                    button.dataset.boundPicker = '1';
                    button.addEventListener('click', function() {
                        activeSetoranForm = this.closest('form');
                        const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('muzakiPickerModal'));
                        modal.show();
                    });
                });

                scope.querySelectorAll('.js-scan-code').forEach(function(input) {
                    if (input.dataset.boundScanCode === '1') return;
                    input.dataset.boundScanCode = '1';
                    input.addEventListener('change', function() {
                        selectMuzakiByCode(this.closest('form'), this.value);
                    });
                });

                scope.querySelectorAll('.js-scan-muzaki').forEach(function(button) {
                    if (button.dataset.boundScanButton === '1') return;
                    button.dataset.boundScanButton = '1';
                    button.addEventListener('click', function() {
                        startAdminScan(this.closest('form'));
                    });
                });
            }

            function selectMuzakiByCode(form, code) {
                const cleaned = String(code || '').trim();
                if (!cleaned) return;

                const select = form.querySelector('.js-muzaki-select');
                const option = Array.from(select.options).find(function(item) {
                    return item.dataset.code === cleaned || item.dataset.nik === cleaned;
                });

                if (!option) {
                    alert('Muzaki dengan ID/NIK tersebut tidak ditemukan di daftar.');
                    return;
                }

                select.value = option.value;
                if (window.jQuery && $(select).data('select2')) {
                    $(select).val(option.value).trigger('change');
                } else {
                    select.dispatchEvent(new Event('change'));
                }
                renderSelectedMuzaki(form);
            }

            function findMuzakiIdByCode(code) {
                const cleaned = String(code || '').trim();
                if (!cleaned) return null;

                const createSelect = document.querySelector('#createSetoranForm .js-muzaki-select');
                const option = Array.from(createSelect.options).find(function(item) {
                    return item.dataset.code === cleaned || item.dataset.nik === cleaned;
                });

                return option ? option.value : null;
            }

            async function startAdminScan(form) {
                if (!('BarcodeDetector' in window)) {
                    alert('Browser belum mendukung scan langsung. Ketik hasil barcode pada kolom scan.');
                    return;
                }

                const input = form.querySelector('.js-scan-code');
                const stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } });
                const video = document.createElement('video');
                video.setAttribute('playsinline', 'true');
                video.srcObject = stream;
                video.style.width = '100%';
                input.insertAdjacentElement('afterend', video);
                await video.play();

                const detector = new BarcodeDetector({ formats: ['qr_code', 'code_128', 'ean_13'] });
                const scan = async () => {
                    const codes = await detector.detect(video);
                    if (codes.length) {
                        input.value = codes[0].rawValue;
                        selectMuzakiByCode(form, input.value);
                        stream.getTracks().forEach(track => track.stop());
                        video.remove();
                        return;
                    }
                    requestAnimationFrame(scan);
                };
                scan();
            }

            async function startQuickScan() {
                if (!('BarcodeDetector' in window)) {
                    alert('Browser belum mendukung scan langsung. Ketik ID/NIK pada kolom scan.');
                    return;
                }

                const input = document.querySelector('.js-quick-code');
                const stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } });
                const video = document.createElement('video');
                video.setAttribute('playsinline', 'true');
                video.srcObject = stream;
                video.style.width = '100%';
                video.className = 'rounded border mt-2';
                input.closest('.col-lg-5').appendChild(video);
                await video.play();

                const detector = new BarcodeDetector({ formats: ['qr_code', 'code_128', 'ean_13'] });
                const scan = async () => {
                    const codes = await detector.detect(video);
                    if (codes.length) {
                        input.value = codes[0].rawValue;
                        stream.getTracks().forEach(track => track.stop());
                        video.remove();

                        const muzakiId = findMuzakiIdByCode(input.value);
                        if (!muzakiId) {
                            alert('Muzaki dengan ID/NIK tersebut tidak ditemukan.');
                            return;
                        }
                        redirectToMuzaki(muzakiId);
                        return;
                    }
                    requestAnimationFrame(scan);
                };
                scan();
            }

            document.addEventListener('DOMContentLoaded', function() {
                bindSetoranForms(document);

                const quickSelect = document.querySelector('.js-quick-muzaki-select');
                quickSelect?.addEventListener('change', function() {
                    if (this.value) {
                        redirectToMuzaki(this.value);
                    }
                });

                if (window.jQuery && quickSelect) {
                    $(quickSelect).on('select2:select', function(event) {
                        const muzakiId = event.params?.data?.id || this.value;
                        if (muzakiId) {
                            redirectToMuzaki(muzakiId);
                        }
                    });
                }

                document.querySelector('.js-quick-code')?.addEventListener('change', function() {
                    const muzakiId = findMuzakiIdByCode(this.value);
                    if (!muzakiId) {
                        alert('Muzaki dengan ID/NIK tersebut tidak ditemukan.');
                        return;
                    }
                    redirectToMuzaki(muzakiId);
                });

                document.querySelector('.js-quick-scan')?.addEventListener('click', startQuickScan);

                const historyJenisFilter = document.querySelector('.js-history-jenis-filter');
                const historyProgramFilter = document.querySelector('.js-history-program-filter');
                const historyProgramSelect = historyProgramFilter?.querySelector('select[name="program_id"]');
                const toggleHistoryProgramFilter = function() {
                    const isProgram = (historyJenisFilter?.value || '').toLowerCase() === 'program';
                    historyProgramFilter?.classList.toggle('d-none', !isProgram);
                    if (!isProgram && historyProgramSelect) {
                        historyProgramSelect.value = '';
                    }
                };
                historyJenisFilter?.addEventListener('change', toggleHistoryProgramFilter);
                toggleHistoryProgramFilter();

                document.querySelectorAll('.js-start-setoran').forEach(function(button) {
                    button.addEventListener('click', function() {
                        openCreateSetoranWithMuzaki(
                            this.dataset.muzaki,
                            this.dataset.kode,
                            this.dataset.program || null
                        );
                    });
                });

                document.querySelectorAll('.js-hidden-page-action').forEach(function(button) {
                    button.addEventListener('click', function() {
                        const iframe = document.createElement('iframe');
                        iframe.style.position = 'fixed';
                        iframe.style.width = '1px';
                        iframe.style.height = '1px';
                        iframe.style.opacity = '0';
                        iframe.style.pointerEvents = 'none';
                        iframe.src = this.dataset.url;
                        document.body.appendChild(iframe);
                        setTimeout(() => iframe.remove(), 15000);
                    });
                });

                document.querySelectorAll('.modal').forEach(function(modal) {
                    modal.addEventListener('shown.bs.modal', function() {
                        bindSetoranForms(modal);
                    });
                });

                document.querySelectorAll('.js-pilih-muzaki').forEach(function(button) {
                    button.addEventListener('click', function() {
                        if (!activeSetoranForm) return;

                        const select = activeSetoranForm.querySelector('.js-muzaki-select');
                        setMuzakiForForm(activeSetoranForm, this.dataset.id);
                        bootstrap.Modal.getInstance(document.getElementById('muzakiPickerModal')).hide();
                    });
                });
            });
        </script>
    </x-slot>
</x-app-layout>

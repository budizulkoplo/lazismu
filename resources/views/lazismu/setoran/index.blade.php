<x-app-layout>
    <x-slot name="pagetitle">Setoran</x-slot>

    <div class="app-content">
        <div class="container-fluid py-4">
            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                <div>
                    <h3 class="mb-1">Input Setoran</h3>
                    <p class="text-muted mb-0">Pilih jenis setoran zakat, infaq, atau program. Jika program dipilih, admin wajib memilih program aktif.</p>
                </div>
                <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#createSetoranModal">
                    <i class="bi bi-plus-circle"></i> Input Setoran
                </button>
            </div>

            @include('lazismu.partials.alert')

            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-striped align-middle js-lazismu-table w-100">
                            <thead class="table-light">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Muzaki</th>
                                    <th>Jenis</th>
                                    <th>Program</th>
                                    <th class="text-end">Nominal</th>
                                    <th class="text-end">Bisa Digunakan</th>
                                    <th class="text-end">PDM</th>
                                    <th width="160">Aksi</th>
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
                                        <td class="text-end">Rp {{ number_format($setoran->nominal_digunakan_calculated, 0, ',', '.') }}</td>
                                        <td class="text-end">Rp {{ number_format($setoran->nominal_pdm_calculated, 0, ',', '.') }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editSetoranModal{{ $setoran->id }}">Edit</button>
                                            <form action="{{ route('lazismu.setoran.destroy', $setoran) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus transaksi setoran ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-outline-danger">Hapus</button>
                                            </form>
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

    @include('lazismu.setoran.partials.create-modal', ['muzakis' => $muzakis, 'kodeSetorans' => $kodeSetorans, 'programs' => $programs])
    @foreach($setorans as $setoran)
        @include('lazismu.setoran.partials.edit-modal', ['setoran' => $setoran, 'muzakis' => $muzakis, 'kodeSetorans' => $kodeSetorans, 'programs' => $programs])
    @endforeach

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
        <script>
            let activeSetoranForm = null;

            function toggleProgramField(formId) {
                const form = document.getElementById(formId);
                if (!form) return;

                const jenisSelect = form.querySelector('.js-jenis-setoran');
                const programWrapper = form.querySelector('.js-program-wrapper');
                const programSelect = form.querySelector('.js-program-select');

                const selectedText = jenisSelect.options[jenisSelect.selectedIndex]?.dataset.jenis || '';
                const showProgram = selectedText === 'program';

                programWrapper.classList.toggle('d-none', !showProgram);
                programSelect.required = showProgram;

                if (!showProgram) {
                    programSelect.value = '';
                    if (window.jQuery && $(programSelect).data('select2')) {
                        $(programSelect).val('').trigger('change.select2');
                    }
                }
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
                    <div class="border rounded p-2 bg-light">
                        <div class="fw-semibold text-dark">${escapeHtml(nama)}</div>
                        <div>ID: ${escapeHtml(code)} &middot; NIK: ${escapeHtml(nik)}</div>
                        <div>Alamat: ${escapeHtml(alamat)}</div>
                        <div>No HP: ${escapeHtml(hp)} &middot; Email: ${escapeHtml(email)}</div>
                    </div>
                `;
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

                scope.querySelectorAll('.js-muzaki-select').forEach(function(select) {
                    if (select.dataset.boundMuzaki === '1') return;
                    select.dataset.boundMuzaki = '1';
                    select.addEventListener('change', function() {
                        renderSelectedMuzaki(this.form);
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

            document.addEventListener('DOMContentLoaded', function() {
                bindSetoranForms(document);

                document.querySelectorAll('.modal').forEach(function(modal) {
                    modal.addEventListener('shown.bs.modal', function() {
                        bindSetoranForms(modal);
                    });
                });

                document.querySelectorAll('.js-pilih-muzaki').forEach(function(button) {
                    button.addEventListener('click', function() {
                        if (!activeSetoranForm) return;

                        const select = activeSetoranForm.querySelector('.js-muzaki-select');
                        select.value = this.dataset.id;

                        if (window.jQuery && $(select).data('select2')) {
                            $(select).val(this.dataset.id).trigger('change');
                        } else {
                            select.dispatchEvent(new Event('change'));
                        }

                        renderSelectedMuzaki(activeSetoranForm);
                        bootstrap.Modal.getInstance(document.getElementById('muzakiPickerModal')).hide();
                    });
                });
            });
        </script>
    </x-slot>
</x-app-layout>

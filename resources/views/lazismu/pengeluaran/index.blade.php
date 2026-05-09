<x-app-layout>
    <x-slot name="pagetitle">Pengeluaran</x-slot>

    <x-slot name="csscustom">
        <style>
            .pengeluaran-editor {
                min-height: 260px;
                background: #fff;
            }

            .pengeluaran-editor .ql-editor {
                min-height: 220px;
                font-size: .95rem;
            }

            .pengeluaran-description img {
                max-width: 100%;
                height: auto;
                border-radius: .35rem;
            }
        </style>
    </x-slot>

    <div class="app-content">
        <div class="container-fluid py-4">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                <div>
                    <h3 class="mb-1">Pengeluaran</h3>
                    <p class="text-muted mb-0">Input nota pengeluaran untuk zakat, infaq, dan program.</p>
                </div>
                <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#createPengeluaranModal">
                    <i class="bi bi-file-earmark-plus"></i> Tambah Pengeluaran
                </button>
            </div>

            @include('lazismu.partials.alert')

            @if ($errors->any())
                <div class="alert alert-danger">
                    <div class="fw-semibold mb-1">Data belum bisa disimpan:</div>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-striped align-middle js-lazismu-table w-100">
                            <thead class="table-light">
                                <tr>
                                    <th>No Nota</th>
                                    <th>Nama Transaksi</th>
                                    <th>Tanggal</th>
                                    <th>Kelompok</th>
                                    <th>Kode Transaksi</th>
                                    <th class="text-end">Total</th>
                                    <th>Status</th>
                                    <th>User</th>
                                    <th width="210">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($notas as $item)
                                    <tr>
                                        <td class="fw-semibold">{{ $item->nota_no }}</td>
                                        <td>
                                            <div>{{ $item->namatransaksi }}</div>
                                            @if($item->bukti_url)
                                                <a href="{{ $item->bukti_url }}" target="_blank" class="small text-warning">Nota/Proposal</a>
                                            @endif
                                        </td>
                                        <td>{{ optional($item->tanggal)->format('d/m/Y') }}</td>
                                        <td>
                                            {{ $item->kelompok_label }}
                                            @if($item->program)
                                                <div class="small text-muted">{{ $item->program->nama_program }}</div>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $item->kodeTransaksi?->kodetransaksi ?? '-' }}
                                            @if($item->kodeTransaksi?->transaksi)
                                                <div class="small text-muted">{{ $item->kodeTransaksi->transaksi }}</div>
                                            @endif
                                        </td>
                                        <td class="text-end">Rp {{ number_format((float) $item->total, 0, ',', '.') }}</td>
                                        <td>
                                            <span class="badge text-bg-{{ $item->status === 'paid' ? 'success' : ($item->status === 'cancel' ? 'danger' : ($item->status === 'partial' ? 'warning' : 'secondary')) }}">
                                                {{ ucfirst($item->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $item->namauser ?? '-' }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#detailPengeluaranModal{{ $item->id }}">Detail</button>
                                            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editPengeluaranModal{{ $item->id }}">Edit</button>
                                            <form action="{{ route('lazismu.pengeluaran.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus pengeluaran ini?')">
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

    @include('lazismu.pengeluaran.partials.create-modal')
    @foreach($notas as $item)
        @include('lazismu.pengeluaran.partials.edit-modal', ['item' => $item])
        @include('lazismu.pengeluaran.partials.detail-modal', ['item' => $item])
    @endforeach

    <x-slot name="jscustom">
        @include('lazismu.partials.datatable-select2')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const editorUploadUrl = @json(route('lazismu.pengeluaran.editor-image'));
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                function getPengeluaranForms(scope) {
                    const root = scope || document;
                    if (root.classList && root.classList.contains('pengeluaran-form')) {
                        return [root];
                    }

                    return Array.from(root.querySelectorAll('.pengeluaran-form'));
                }

                function syncProgramVisibility(scope) {
                    const forms = getPengeluaranForms(scope);
                    forms.forEach(function(form) {
                        const kelompok = form.querySelector('.pengeluaran-kelompok');
                        const programWrapper = form.querySelector('.pengeluaran-program-wrapper');
                        const programSelect = form.querySelector('[name="idprogram"]');
                        if (!kelompok || !programWrapper || !programSelect) return;

                        const isProgram = kelompok.value === 'program';
                        programWrapper.style.display = isProgram ? '' : 'none';
                        programSelect.disabled = !isProgram;
                        programSelect.removeAttribute('required');
                        if (window.jQuery && $(programSelect).data('select2')) {
                            $(programSelect).prop('disabled', !isProgram).trigger('change');
                        }
                        if (!isProgram) {
                            programSelect.value = '';
                            if (window.jQuery && $(programSelect).data('select2')) {
                                $(programSelect).val('').trigger('change');
                            }
                        }
                    });
                }

                function uploadEditorImage(file, quill) {
                    const formData = new FormData();
                    formData.append('image', file);

                    fetch(editorUploadUrl, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: formData
                    })
                    .then(function(response) {
                        if (!response.ok) throw new Error('Upload gagal');
                        return response.json();
                    })
                    .then(function(result) {
                        const range = quill.getSelection(true);
                        quill.insertEmbed(range.index, 'image', result.url);
                    })
                    .catch(function() {
                        alert('Gagal upload gambar editor. Pastikan file gambar maksimal 1 MB.');
                    });
                }

                function initPengeluaranEditors(scope) {
                    if (!window.Quill) return;

                    (scope || document).querySelectorAll('.pengeluaran-editor').forEach(function(editor) {
                        if (editor.dataset.initialized === '1') return;

                        const quill = new Quill(editor, {
                            theme: 'snow',
                            modules: {
                                toolbar: [
                                    [{ header: [2, 3, false] }],
                                    ['bold', 'italic', 'underline'],
                                    [{ list: 'ordered' }, { list: 'bullet' }],
                                    ['link', 'image'],
                                    ['clean']
                                ]
                            }
                        });

                        const target = document.querySelector(editor.dataset.target);
                        const toolbar = quill.getModule('toolbar');
                        toolbar.addHandler('image', function() {
                            const input = document.createElement('input');
                            input.setAttribute('type', 'file');
                            input.setAttribute('accept', 'image/jpeg,image/png,image/webp');
                            input.click();

                            input.onchange = function() {
                                const file = input.files[0];
                                if (!file) return;
                                uploadEditorImage(file, quill);
                            };
                        });

                        quill.on('text-change', function() {
                            if (target) target.value = quill.root.innerHTML;
                        });

                        if (target) target.value = quill.root.innerHTML;
                        editor.dataset.initialized = '1';
                    });
                }

                document.querySelectorAll('.pengeluaran-kelompok').forEach(function(select) {
                    select.addEventListener('change', function() {
                        syncProgramVisibility(select.closest('.pengeluaran-form'));
                    });
                });

                document.querySelectorAll('.pengeluaran-form').forEach(function(form) {
                    form.addEventListener('submit', function(event) {
                        const kelompok = form.querySelector('.pengeluaran-kelompok');
                        const programSelect = form.querySelector('[name="idprogram"]');
                        const kodeTransaksiSelect = form.querySelector('[name="idkodetransaksi"]');
                        if (kelompok && programSelect && kelompok.value === 'program' && !programSelect.value) {
                            programSelect.disabled = false;
                            programSelect.removeAttribute('required');
                            if (window.jQuery && $(programSelect).data('select2')) {
                                $(programSelect).prop('disabled', false).select2('open');
                            }
                            alert('Program wajib dipilih jika kelompok pengeluaran adalah Program.');
                            event.preventDefault();
                            return;
                        }

                        if (kodeTransaksiSelect && !kodeTransaksiSelect.value) {
                            if (window.jQuery && $(kodeTransaksiSelect).data('select2')) {
                                $(kodeTransaksiSelect).select2('open');
                            }
                            alert('Kode transaksi wajib dipilih.');
                            event.preventDefault();
                            return;
                        }

                        form.querySelectorAll('.pengeluaran-editor').forEach(function(editor) {
                            const target = document.querySelector(editor.dataset.target);
                            const qlEditor = editor.querySelector('.ql-editor');
                            if (target && qlEditor) target.value = qlEditor.innerHTML;
                        });
                    });
                });

                document.querySelectorAll('.modal').forEach(function(modal) {
                    modal.addEventListener('shown.bs.modal', function() {
                        syncProgramVisibility(modal);
                        initPengeluaranEditors(modal);
                    });
                });

                syncProgramVisibility(document);
                initPengeluaranEditors(document);
            });
        </script>
    </x-slot>
</x-app-layout>

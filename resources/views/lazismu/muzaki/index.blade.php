<x-app-layout>
    <x-slot name="pagetitle">Data Muzaki</x-slot>

    <div class="app-content">
        <div class="container-fluid py-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h3 class="mb-1">Data Muzaki</h3>
                    <p class="text-muted mb-0">Kelola identitas muzaki yang dapat melakukan setoran.</p>
                </div>
                <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#createMuzakiModal">
                    <i class="bi bi-plus-circle"></i> Tambah Muzaki
                </button>
            </div>

            @include('lazismu.partials.alert')

            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-striped align-middle js-lazismu-table w-100">
                            <thead class="table-light">
                                <tr>
                                    <th>ID/NIK</th>
                                    <th>Nama</th>
                                    <th>Jenis</th>
                                    <th>Tgl Lahir</th>
                                    <th>Jenis Kelamin</th>
                                    <th>No HP</th>
                                    <th>Email</th>
                                    <th width="280">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($muzakis as $muzaki)
                                    <tr>
                                        <td>
                                            <div class="fw-semibold">{{ $muzaki->nomor_induk_muzaki ?: '-' }}</div>
                                            @if(($muzaki->jenis_muzaki ?? 'pribadi') === 'pribadi')
                                                <small class="text-muted">NIK: {{ $muzaki->nik ?: '-' }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <div>{{ $muzaki->nama }}</div>
                                            <small class="text-muted">
                                                {{ $muzaki->jenis_muzaki === 'aum' ? 'AUM' : ($muzaki->ranting ?: '-') }}
                                                @if($muzaki->alamat)
                                                    | {{ $muzaki->alamat }}
                                                @endif
                                            </small>
                                        </td>
                                        <td>{{ ($muzaki->jenis_muzaki ?? 'pribadi') === 'aum' ? 'AUM' : ucfirst($muzaki->jenis_muzaki ?? 'pribadi') }}</td>
                                        <td>{{ optional($muzaki->tgl_lahir)->format('d/m/Y') ?? '-' }}</td>
                                        <td>{{ $muzaki->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                                        <td>{{ $muzaki->no_hp ?: '-' }}</td>
                                        <td>{{ $muzaki->email ?: '-' }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editMuzakiModal{{ $muzaki->id }}">Edit</button>
                                            <a class="btn btn-sm btn-outline-success" href="{{ route('lazismu.muzaki.barcode', $muzaki) }}" target="_blank">Barcode</a>
                                            <a class="btn btn-sm btn-outline-warning" href="{{ route('lazismu.muzaki.card', $muzaki) }}" target="_blank">Kartu</a>
                                            <form action="{{ route('lazismu.muzaki.destroy', $muzaki) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus data muzaki ini?')">
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

    @include('lazismu.muzaki.partials.create-modal')
    @foreach($muzakis as $muzaki)
        @include('lazismu.muzaki.partials.edit-modal', ['muzaki' => $muzaki])
    @endforeach

    <x-slot name="jscustom">
        @include('lazismu.partials.datatable-select2')
        <script>
            function syncMuzakiNik(form) {
                const jenis = form.querySelector('.js-jenis-muzaki');
                const nikField = form.querySelector('.js-nik-field');
                const nik = form.querySelector('.js-nik-input');
                const rantingField = form.querySelector('.js-ranting-field');
                const ranting = form.querySelector('.js-ranting-input');
                const tglLahirField = form.querySelector('.js-tgl-lahir-field');
                const tglLahir = form.querySelector('.js-tgl-lahir-input');
                if (!jenis || !nik) return;

                const isPribadi = jenis.value === 'pribadi';
                const isAum = jenis.value === 'aum';
                if (nikField) {
                    nikField.classList.toggle('d-none', !isPribadi);
                }
                nik.required = isPribadi;
                nik.disabled = !isPribadi;
                nik.placeholder = isPribadi ? 'Wajib 16 digit untuk pribadi' : 'Boleh kosong untuk kelompok/AUM';
                if (!isPribadi) {
                    nik.value = '';
                }

                if (rantingField && ranting) {
                    rantingField.classList.toggle('d-none', isAum);
                    ranting.required = !isAum;
                    ranting.disabled = isAum;
                    if (isAum && window.jQuery) {
                        $(ranting).val(null).trigger('change.select2');
                    }
                }

                if (tglLahirField && tglLahir) {
                    tglLahirField.classList.toggle('d-none', isAum);
                    tglLahir.disabled = isAum;
                    if (isAum) {
                        tglLahir.value = '';
                    }
                }
            }

            document.addEventListener('DOMContentLoaded', function () {
                function bindMuzakiForms(context) {
                    (context || document).querySelectorAll('form').forEach(function (form) {
                        const jenis = form.querySelector('.js-jenis-muzaki');
                        if (!jenis || jenis.dataset.boundMuzakiType === '1') return;
                        jenis.dataset.boundMuzakiType = '1';

                        jenis.addEventListener('change', function () {
                            syncMuzakiNik(form);
                        });

                        if (window.jQuery) {
                            $(jenis).on('select2:select change.select2', function () {
                                syncMuzakiNik(form);
                            });
                        }

                        syncMuzakiNik(form);
                    });
                }

                bindMuzakiForms(document);

                document.querySelectorAll('.modal').forEach(function (modal) {
                    modal.addEventListener('shown.bs.modal', function () {
                        bindMuzakiForms(modal);
                        modal.querySelectorAll('form').forEach(syncMuzakiNik);
                    });
                });

                document.querySelectorAll('form').forEach(function (form) {
                    if (!form.querySelector('.js-jenis-muzaki')) return;
                    syncMuzakiNik(form);
                });
            });
        </script>
    </x-slot>
</x-app-layout>

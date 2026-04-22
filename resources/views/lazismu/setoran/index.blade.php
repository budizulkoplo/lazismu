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
                    <form class="row g-2 mb-3">
                        <div class="col-md-4">
                            <input type="text" name="search" class="form-control" placeholder="Cari muzaki / NIK" value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3">
                            <select name="jenis_setoran" class="form-select">
                                <option value="">Semua Jenis</option>
                                @foreach($kodeSetorans as $kode)
                                    <option value="{{ $kode->jenis_setoran }}" @selected(request('jenis_setoran') === $kode->jenis_setoran)>{{ ucfirst($kode->jenis_setoran) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select name="program_id" class="form-select">
                                <option value="">Semua Program</option>
                                @foreach($programs as $program)
                                    <option value="{{ $program->id }}" @selected((string) request('program_id') === (string) $program->id)>{{ $program->nama_program }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-auto">
                            <button class="btn btn-outline-warning">Filter</button>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-sm align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Muzaki</th>
                                    <th>Jenis</th>
                                    <th>Program</th>
                                    <th class="text-end">Nominal</th>
                                    <th width="160">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($setorans as $setoran)
                                    <tr>
                                        <td>{{ optional($setoran->created_at)->format('d/m/Y') }}</td>
                                        <td>
                                            <div>{{ $setoran->muzaki->nama ?? '-' }}</div>
                                            <small class="text-muted">{{ $setoran->muzaki->nik ?? '-' }}</small>
                                        </td>
                                        <td>{{ ucfirst($setoran->kodeSetoran->jenis_setoran ?? '-') }}</td>
                                        <td>{{ $setoran->program->nama_program ?? '-' }}</td>
                                        <td class="text-end">Rp {{ number_format($setoran->nominal, 0, ',', '.') }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editSetoranModal{{ $setoran->id }}">Edit</button>
                                            <form action="{{ route('lazismu.setoran.destroy', $setoran) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus transaksi setoran ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-outline-danger">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>

                                    @include('lazismu.setoran.partials.edit-modal', ['setoran' => $setoran, 'muzakis' => $muzakis, 'kodeSetorans' => $kodeSetorans, 'programs' => $programs])
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">Belum ada setoran.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{ $setorans->links() }}
                </div>
            </div>
        </div>
    </div>

    @include('lazismu.setoran.partials.create-modal', ['muzakis' => $muzakis, 'kodeSetorans' => $kodeSetorans, 'programs' => $programs])

    <x-slot name="jscustom">
        <script>
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
                }
            }

            document.querySelectorAll('.js-jenis-setoran').forEach(function(select) {
                select.addEventListener('change', function() {
                    toggleProgramField(this.form.id);
                });

                toggleProgramField(select.form.id);
            });
        </script>
    </x-slot>
</x-app-layout>

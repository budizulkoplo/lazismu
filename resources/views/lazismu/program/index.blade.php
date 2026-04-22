<x-app-layout>
    <x-slot name="pagetitle">Program</x-slot>

    <div class="app-content">
        <div class="container-fluid py-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h3 class="mb-1">Program Infaq</h3>
                    <p class="text-muted mb-0">Program aktif akan muncul saat admin input setoran bertipe program.</p>
                </div>
                <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#createProgramModal">
                    <i class="bi bi-plus-circle"></i> Tambah Program
                </button>
            </div>

            @include('lazismu.partials.alert')

            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form class="row g-2 mb-3">
                        <div class="col-md-4">
                            <input type="text" name="search" class="form-control" placeholder="Cari nama program atau lokasi" value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3">
                            <select name="status" class="form-select">
                                <option value="">Semua Status</option>
                                <option value="active" @selected(request('status') === 'active')>Active</option>
                                <option value="nonactive" @selected(request('status') === 'nonactive')>Non Active</option>
                                <option value="selesai" @selected(request('status') === 'selesai')>Selesai</option>
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
                                    <th>Nama Program</th>
                                    <th>Lokasi</th>
                                    <th>Periode</th>
                                    <th>Status</th>
                                    <th class="text-end">Terkumpul</th>
                                    <th width="160">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($programs as $program)
                                    <tr>
                                        <td>{{ $program->nama_program }}</td>
                                        <td>{{ $program->lokasi ?: '-' }}</td>
                                        <td>{{ optional($program->tgl_mulai)->format('d/m/Y') ?? '-' }} - {{ optional($program->tgl_selesai)->format('d/m/Y') ?? '-' }}</td>
                                        <td><span class="badge text-bg-{{ $program->status === 'active' ? 'success' : ($program->status === 'selesai' ? 'secondary' : 'warning') }}">{{ ucfirst($program->status) }}</span></td>
                                        <td class="text-end">Rp {{ number_format($program->terkumpul, 0, ',', '.') }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editProgramModal{{ $program->id }}">Edit</button>
                                            <form action="{{ route('lazismu.program.destroy', $program) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus program ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-outline-danger">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>

                                    @include('lazismu.program.partials.edit-modal', ['program' => $program])
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">Belum ada data program.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{ $programs->links() }}
                </div>
            </div>
        </div>
    </div>

    @include('lazismu.program.partials.create-modal')
</x-app-layout>

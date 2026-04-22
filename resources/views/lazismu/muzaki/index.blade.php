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
                    <form class="row g-2 mb-3">
                        <div class="col-md-4">
                            <input type="text" name="search" class="form-control" placeholder="Cari nama, NIK, atau no HP" value="{{ request('search') }}">
                        </div>
                        <div class="col-auto">
                            <button class="btn btn-outline-warning">Filter</button>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-sm align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>NIK</th>
                                    <th>Nama</th>
                                    <th>Tgl Lahir</th>
                                    <th>Jenis Kelamin</th>
                                    <th>No HP</th>
                                    <th>Email</th>
                                    <th width="160">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($muzakis as $muzaki)
                                    <tr>
                                        <td>{{ $muzaki->nik }}</td>
                                        <td>
                                            <div>{{ $muzaki->nama }}</div>
                                            <small class="text-muted">{{ $muzaki->alamat }}</small>
                                        </td>
                                        <td>{{ optional($muzaki->tgl_lahir)->format('d/m/Y') ?? '-' }}</td>
                                        <td>{{ $muzaki->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                                        <td>{{ $muzaki->no_hp ?: '-' }}</td>
                                        <td>{{ $muzaki->email ?: '-' }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editMuzakiModal{{ $muzaki->id }}">Edit</button>
                                            <form action="{{ route('lazismu.muzaki.destroy', $muzaki) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus data muzaki ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-outline-danger">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>

                                    @include('lazismu.muzaki.partials.edit-modal', ['muzaki' => $muzaki])
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">Belum ada data muzaki.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{ $muzakis->links() }}
                </div>
            </div>
        </div>
    </div>

    @include('lazismu.muzaki.partials.create-modal')
</x-app-layout>

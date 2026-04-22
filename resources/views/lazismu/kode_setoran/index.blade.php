<x-app-layout>
    <x-slot name="pagetitle">Kode Setoran</x-slot>

    <div class="app-content">
        <div class="container-fluid py-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h3 class="mb-1">Kode Setoran</h3>
                    <p class="text-muted mb-0">Jenis setoran utama yang dipakai di transaksi: zakat, infaq, dan program.</p>
                </div>
                <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#createKodeSetoranModal">
                    <i class="bi bi-plus-circle"></i> Tambah Kode
                </button>
            </div>

            @include('lazismu.partials.alert')

            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form class="row g-2 mb-3">
                        <div class="col-md-4">
                            <input type="text" name="search" class="form-control" placeholder="Cari jenis setoran" value="{{ request('search') }}">
                        </div>
                        <div class="col-auto">
                            <button class="btn btn-outline-warning">Filter</button>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-sm align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Jenis Setoran</th>
                                    <th width="160">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($kodeSetorans as $item)
                                    <tr>
                                        <td>{{ ucfirst($item->jenis_setoran) }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editKodeSetoranModal{{ $item->id }}">Edit</button>
                                            <form action="{{ route('lazismu.kode-setoran.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus kode setoran ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-outline-danger">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>

                                    @include('lazismu.kode_setoran.partials.edit-modal', ['item' => $item])
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center text-muted py-4">Belum ada kode setoran.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{ $kodeSetorans->links() }}
                </div>
            </div>
        </div>
    </div>

    @include('lazismu.kode_setoran.partials.create-modal')
</x-app-layout>

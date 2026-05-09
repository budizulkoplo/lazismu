<x-app-layout>
    <x-slot name="pagetitle">Kode Transaksi</x-slot>

    <div class="app-content">
        <div class="container-fluid py-4">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                <div>
                    <h3 class="mb-1">Kode Transaksi</h3>
                    <p class="text-muted mb-0">Master kode transaksi dan header pengelompokan transaksi.</p>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-warning" data-bs-toggle="modal" data-bs-target="#createKodetransaksiHeaderModal">
                        <i class="bi bi-folder-plus"></i> Tambah Header
                    </button>
                    <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#createKodetransaksiModal">
                        <i class="bi bi-plus-circle"></i> Tambah Kode
                    </button>
                </div>
            </div>

            @include('lazismu.partials.alert')

            <div class="row g-3">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white border-0 pb-0">
                            <h5 class="mb-0">Daftar Kode Transaksi</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm table-striped align-middle js-lazismu-table w-100">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Kode</th>
                                            <th>Transaksi</th>
                                            <th>Header</th>
                                            <th>Update</th>
                                            <th width="160">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($kodeTransaksis as $item)
                                            <tr>
                                                <td class="fw-semibold">{{ $item->kodetransaksi }}</td>
                                                <td>{{ $item->transaksi }}</td>
                                                <td>{{ $item->header?->keterangan ?? '-' }}</td>
                                                <td>{{ optional($item->updated_at)->format('d/m/Y H:i') ?? '-' }}</td>
                                                <td>
                                                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editKodetransaksiModal{{ $item->id }}">Edit</button>
                                                    <form action="{{ route('lazismu.kodetransaksi.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus kode transaksi ini?')">
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

                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white border-0 pb-0">
                            <h5 class="mb-0">Header Transaksi</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Keterangan</th>
                                            <th class="text-center">Kode</th>
                                            <th width="140">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($headers as $header)
                                            <tr>
                                                <td>{{ $header->keterangan }}</td>
                                                <td class="text-center">{{ $header->kode_transaksis_count }}</td>
                                                <td>
                                                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editKodetransaksiHeaderModal{{ $header->id }}">Edit</button>
                                                    <form action="{{ route('lazismu.kodetransaksi.header.destroy', $header) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus header transaksi ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-sm btn-outline-danger" @disabled($header->kode_transaksis_count > 0)>Hapus</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="text-muted text-center py-4">Belum ada header.</td>
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
    </div>

    @include('lazismu.kodetransaksi.partials.create-modal')
    @include('lazismu.kodetransaksi.partials.header-modal', [
        'modalId' => 'createKodetransaksiHeaderModal',
        'action' => route('lazismu.kodetransaksi.header.store'),
        'method' => 'POST',
        'title' => 'Tambah Header Transaksi',
        'buttonText' => 'Simpan',
    ])

    @foreach($kodeTransaksis as $item)
        @include('lazismu.kodetransaksi.partials.edit-modal', ['item' => $item])
    @endforeach

    @foreach($headers as $header)
        @include('lazismu.kodetransaksi.partials.header-modal', [
            'modalId' => 'editKodetransaksiHeaderModal' . $header->id,
            'action' => route('lazismu.kodetransaksi.header.update', $header),
            'method' => 'PUT',
            'title' => 'Edit Header Transaksi',
            'buttonText' => 'Update',
            'header' => $header,
        ])
    @endforeach

    <x-slot name="jscustom">
        @include('lazismu.partials.datatable-select2')
    </x-slot>
</x-app-layout>

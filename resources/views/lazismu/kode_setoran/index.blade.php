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
                    <div class="table-responsive">
                        <table class="table table-sm table-striped align-middle js-lazismu-table w-100">
                            <thead class="table-light">
                                <tr>
                                    <th>Jenis Setoran</th>
                                    <th width="160">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($kodeSetorans as $item)
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
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('lazismu.kode_setoran.partials.create-modal')
    @foreach($kodeSetorans as $item)
        @include('lazismu.kode_setoran.partials.edit-modal', ['item' => $item])
    @endforeach

    <x-slot name="jscustom">
        @include('lazismu.partials.datatable-select2')
    </x-slot>
</x-app-layout>

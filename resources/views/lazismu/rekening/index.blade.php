<x-app-layout>
    <x-slot name="pagetitle">Rekening</x-slot>

    <div class="app-content">
        <div class="container-fluid py-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h3 class="mb-1">Master Rekening</h3>
                    <p class="text-muted mb-0">Pantau saldo rekening zakat, infaq, wakaf, dan rekening program.</p>
                </div>
            </div>

            @include('lazismu.partials.alert')

            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-striped align-middle js-lazismu-table w-100">
                            <thead class="table-light">
                                <tr>
                                    <th>Nama Rekening</th>
                                    <th class="text-end">Saldo</th>
                                    <th class="text-center">Mutasi</th>
                                    <th width="170">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($rekenings as $rekening)
                                    <tr>
                                        <td class="fw-semibold">{{ $rekening->namarek }}</td>
                                        <td class="text-end">Rp {{ number_format((float) $rekening->saldo, 0, ',', '.') }}</td>
                                        <td class="text-center">{{ number_format($rekening->transaksis_count) }}</td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('lazismu.laporan.rekening', ['rekening_id' => $rekening->id]) }}" class="btn btn-outline-success">Laporan</a>
                                                <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editRekening{{ $rekening->id }}">Edit</button>
                                            </div>
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

    @foreach($rekenings as $rekening)
        <div class="modal fade" id="editRekening{{ $rekening->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <form action="{{ route('lazismu.rekening.update', $rekening) }}" method="POST" class="modal-content">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Rekening</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <label class="form-label">Nama Rekening</label>
                        <input type="text" name="namarek" class="form-control" value="{{ old('namarek', $rekening->namarek) }}" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button class="btn btn-warning">Update</button>
                    </div>
                </form>
            </div>
        </div>
    @endforeach

    <x-slot name="jscustom">
        @include('lazismu.partials.datatable-select2')
    </x-slot>
</x-app-layout>

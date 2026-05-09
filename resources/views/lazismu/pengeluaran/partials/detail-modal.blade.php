<div class="modal fade" id="detailPengeluaranModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Pengeluaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <div class="text-muted small">No Nota</div>
                        <div class="fw-semibold">{{ $item->nota_no }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small">Nama Transaksi</div>
                        <div class="fw-semibold">{{ $item->namatransaksi }}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-muted small">Tanggal</div>
                        <div>{{ optional($item->tanggal)->format('d/m/Y') }}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-muted small">Kelompok</div>
                        <div>{{ $item->kelompok_label }}{{ $item->program ? ' - ' . $item->program->nama_program : '' }}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-muted small">Total</div>
                        <div class="fw-semibold">Rp {{ number_format((float) $item->total, 0, ',', '.') }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small">Kode Transaksi</div>
                        <div>{{ $item->kodeTransaksi?->kodetransaksi }} - {{ $item->kodeTransaksi?->transaksi }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small">User Input</div>
                        <div>{{ $item->namauser ?? '-' }}</div>
                    </div>
                </div>

                @if($item->bukti_url)
                    <a href="{{ $item->bukti_url }}" target="_blank" class="btn btn-sm btn-outline-warning mb-3">
                        <i class="bi bi-paperclip"></i> Lihat Nota/Proposal
                    </a>
                @endif

                <div class="border rounded p-3 pengeluaran-description">
                    {!! $item->deskripsi ?: '<span class="text-muted">Tidak ada keterangan.</span>' !!}
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="createSetoranModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form action="{{ route('lazismu.setoran.store') }}" method="POST" class="modal-content" id="createSetoranForm">
            @csrf
            <div class="modal-header">
                <div>
                    <h5 class="modal-title mb-1">Input Setoran</h5>
                    <small class="text-muted">Lengkapi data dari atas ke bawah. Jika jenisnya program, pilih program tujuan.</small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                @php
                    $setoran = null;
                @endphp
                @include('lazismu.setoran.partials.form-fields')
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <button class="btn btn-warning">Simpan & Cetak Nota</button>
            </div>
        </form>
    </div>
</div>

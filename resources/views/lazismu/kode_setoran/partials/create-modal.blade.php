<div class="modal fade" id="createKodeSetoranModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('lazismu.kode-setoran.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Tambah Kode Setoran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                @php($item = null)
                @include('lazismu.kode_setoran.partials.form-fields')
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <button class="btn btn-warning">Simpan</button>
            </div>
        </form>
    </div>
</div>

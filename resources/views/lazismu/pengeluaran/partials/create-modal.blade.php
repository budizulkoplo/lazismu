<div class="modal fade" id="createPengeluaranModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <form action="{{ route('lazismu.pengeluaran.store') }}" method="POST" enctype="multipart/form-data" class="modal-content pengeluaran-form">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Tambah Pengeluaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                @include('lazismu.pengeluaran.partials.form-fields', ['item' => null])
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <button class="btn btn-warning">Simpan</button>
            </div>
        </form>
    </div>
</div>

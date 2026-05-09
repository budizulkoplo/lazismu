<div class="modal fade" id="editPengeluaranModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <form action="{{ route('lazismu.pengeluaran.update', $item) }}" method="POST" enctype="multipart/form-data" class="modal-content pengeluaran-form">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title">Edit Pengeluaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                @include('lazismu.pengeluaran.partials.form-fields', ['item' => $item])
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <button class="btn btn-warning">Update</button>
            </div>
        </form>
    </div>
</div>

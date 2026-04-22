<div class="modal fade" id="editKodeSetoranModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('lazismu.kode-setoran.update', $item) }}" method="POST" class="modal-content">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title">Edit Kode Setoran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                @include('lazismu.kode_setoran.partials.form-fields', ['item' => $item])
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <button class="btn btn-warning">Update</button>
            </div>
        </form>
    </div>
</div>

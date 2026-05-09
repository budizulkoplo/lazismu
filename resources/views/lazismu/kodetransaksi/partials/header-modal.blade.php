<div class="modal fade" id="{{ $modalId }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ $action }}" method="POST" class="modal-content">
            @csrf
            @if($method !== 'POST')
                @method($method)
            @endif
            <div class="modal-header">
                <h5 class="modal-title">{{ $title }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <label class="form-label">Keterangan Header</label>
                <input name="keterangan" class="form-control" maxlength="255" value="{{ old('keterangan', optional($header ?? null)->keterangan) }}" placeholder="Contoh: Penerimaan" required>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <button class="btn btn-warning">{{ $buttonText }}</button>
            </div>
        </form>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-4">
        <label class="form-label">Kode Transaksi</label>
        <input name="kodetransaksi" class="form-control" maxlength="255" value="{{ old('kodetransaksi', optional($item)->kodetransaksi) }}" placeholder="Contoh: KM-001" required>
    </div>
    <div class="col-md-4">
        <label class="form-label">Transaksi</label>
        <input name="transaksi" class="form-control" maxlength="255" value="{{ old('transaksi', optional($item)->transaksi) }}" placeholder="Nama transaksi" required>
    </div>
    <div class="col-md-4">
        <label class="form-label">Header</label>
        <select name="idheader" class="form-select js-select2" data-placeholder="Pilih header">
            <option value="">Tanpa header</option>
            @foreach($headers as $header)
                <option value="{{ $header->id }}" @selected((string) old('idheader', optional($item)->idheader) === (string) $header->id)>
                    {{ $header->keterangan }}
                </option>
            @endforeach
        </select>
    </div>
</div>

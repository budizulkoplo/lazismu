<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Nama Program</label>
        <input type="text" name="nama_program" class="form-control" value="{{ old('nama_program', optional($program)->nama_program) }}" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Lokasi</label>
        <input type="text" name="lokasi" class="form-control" value="{{ old('lokasi', optional($program)->lokasi) }}">
    </div>
    <div class="col-md-6">
        <label class="form-label">Tanggal Mulai</label>
        <input type="date" name="tgl_mulai" class="form-control" value="{{ old('tgl_mulai', optional(optional($program)->tgl_mulai)->format('Y-m-d')) }}">
    </div>
    <div class="col-md-6">
        <label class="form-label">Tanggal Selesai</label>
        <input type="date" name="tgl_selesai" class="form-control" value="{{ old('tgl_selesai', optional(optional($program)->tgl_selesai)->format('Y-m-d')) }}">
    </div>
    <div class="col-md-6">
        <label class="form-label">Target Dana</label>
        <input type="number" name="target" class="form-control" min="0" value="{{ old('target', isset($program) && $program ? (float) $program->target : 0) }}">
    </div>
    <div class="col-md-6">
        <label class="form-label">Status</label>
        <select name="status" class="form-select js-select2" required>
            <option value="active" @selected(old('status', optional($program)->status ?? 'active') === 'active')>Active</option>
            <option value="nonactive" @selected(old('status', optional($program)->status) === 'nonactive')>Non Active</option>
            <option value="selesai" @selected(old('status', optional($program)->status) === 'selesai')>Selesai</option>
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label">Rekening Program</label>
        <select name="idrek" class="form-select js-select2" data-placeholder="Otomatis sesuai nama program">
            <option value="">Buat otomatis</option>
            @foreach(($rekenings ?? collect()) as $rekening)
                <option value="{{ $rekening->id }}" @selected((string) old('idrek', optional($program)->idrek) === (string) $rekening->id)>
                    {{ $rekening->namarek }} - Rp {{ number_format((float) $rekening->saldo, 0, ',', '.') }}
                </option>
            @endforeach
        </select>
        <small class="text-muted">Kosongkan jika ingin dibuatkan rekening baru untuk program ini.</small>
    </div>
    <div class="col-12">
        <label class="form-label">Banner Program</label>
        <input type="file" name="banner" class="form-control" accept="image/*">
        @if(isset($program) && $program && $program->banner_path)
            <div class="mt-2">
                <img src="{{ asset('storage/' . $program->banner_path) }}" alt="{{ $program->nama_program }}" style="max-height: 90px;" class="rounded border">
            </div>
        @endif
    </div>
    @if(isset($program) && $program)
        <div class="col-md-6">
            <label class="form-label">Dana Terkumpul</label>
            <input type="text" class="form-control" value="Rp {{ number_format($program->terkumpul, 0, ',', '.') }}" readonly>
        </div>
    @endif
</div>

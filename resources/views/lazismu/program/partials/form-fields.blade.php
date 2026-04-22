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
        <label class="form-label">Status</label>
        <select name="status" class="form-select" required>
            <option value="active" @selected(old('status', optional($program)->status ?? 'active') === 'active')>Active</option>
            <option value="nonactive" @selected(old('status', optional($program)->status) === 'nonactive')>Non Active</option>
            <option value="selesai" @selected(old('status', optional($program)->status) === 'selesai')>Selesai</option>
        </select>
    </div>
    @if(isset($program) && $program)
        <div class="col-md-6">
            <label class="form-label">Dana Terkumpul</label>
            <input type="text" class="form-control" value="Rp {{ number_format($program->terkumpul, 0, ',', '.') }}" readonly>
        </div>
    @endif
</div>

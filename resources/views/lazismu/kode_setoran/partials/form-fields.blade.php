<div class="mb-3">
    <label class="form-label">Jenis Setoran</label>
    <input name="jenis_setoran" class="form-control" maxlength="100" value="{{ old('jenis_setoran', optional($item)->jenis_setoran) }}" placeholder="zakat, infaq, program, operasional, tasaruf zakat" required>
    <small class="text-muted">Zakat otomatis dibagi 70:30, infaq 80:20, program 100% ke program. Kategori lain masuk 100% bisa digunakan.</small>
</div>

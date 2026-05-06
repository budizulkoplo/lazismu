<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Jenis Muzaki</label>
        <select name="jenis_muzaki" class="form-select js-select2 js-jenis-muzaki" required>
            <option value="pribadi" @selected(old('jenis_muzaki', optional($muzaki)->jenis_muzaki ?? 'pribadi') === 'pribadi')>Pribadi</option>
            <option value="kelompok" @selected(old('jenis_muzaki', optional($muzaki)->jenis_muzaki) === 'kelompok')>Kelompok</option>
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label">Nomor Induk Muzaki</label>
        <input type="text" name="nomor_induk_muzaki" class="form-control" maxlength="30" value="{{ old('nomor_induk_muzaki', optional($muzaki)->nomor_induk_muzaki) }}" placeholder="Otomatis jika dikosongkan">
    </div>
    <div class="col-md-6">
        <label class="form-label">NIK</label>
        <input type="text" name="nik" class="form-control js-nik-input" maxlength="16" value="{{ old('nik', optional($muzaki)->nik) }}">
    </div>
    <div class="col-md-6">
        <label class="form-label">Nama</label>
        <input type="text" name="nama" class="form-control" value="{{ old('nama', optional($muzaki)->nama) }}" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Tanggal Lahir</label>
        <input type="date" name="tgl_lahir" class="form-control" value="{{ old('tgl_lahir', optional(optional($muzaki)->tgl_lahir)->format('Y-m-d')) }}">
    </div>
    <div class="col-md-6">
        <label class="form-label">Jenis Kelamin</label>
        <select name="jenis_kelamin" class="form-select js-select2" required>
            <option value="L" @selected(old('jenis_kelamin', optional($muzaki)->jenis_kelamin) === 'L')>Laki-laki</option>
            <option value="P" @selected(old('jenis_kelamin', optional($muzaki)->jenis_kelamin) === 'P')>Perempuan</option>
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label">No HP</label>
        <input type="text" name="no_hp" class="form-control" maxlength="20" value="{{ old('no_hp', optional($muzaki)->no_hp) }}">
    </div>
    <div class="col-md-6">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" value="{{ old('email', optional($muzaki)->email) }}">
    </div>
    <div class="col-12">
        <label class="form-label">Target Setoran</label>
        <input type="number" name="target_setoran" class="form-control" min="0" value="{{ old('target_setoran', isset($muzaki) && $muzaki ? (float) $muzaki->target_setoran : 0) }}">
    </div>
    <div class="col-12">
        <label class="form-label">Alamat</label>
        <textarea name="alamat" class="form-control" rows="3">{{ old('alamat', optional($muzaki)->alamat) }}</textarea>
    </div>
</div>

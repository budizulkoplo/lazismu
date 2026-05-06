<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">NIK</label>
        <input type="text" name="nik" class="form-control" maxlength="16" value="{{ old('nik', optional($muzaki)->nik) }}" required>
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
        <input type="text" name="no_hp" class="form-control" maxlength="15" value="{{ old('no_hp', optional($muzaki)->no_hp) }}">
    </div>
    <div class="col-md-6">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" value="{{ old('email', optional($muzaki)->email) }}">
    </div>
    <div class="col-12">
        <label class="form-label">Alamat</label>
        <textarea name="alamat" class="form-control" rows="3">{{ old('alamat', optional($muzaki)->alamat) }}</textarea>
    </div>
</div>

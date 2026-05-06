<div class="row g-3">
    <div class="col-12">
        <label class="form-label">Muzaki</label>
        <div class="input-group">
            <select name="idmuzaki" class="form-select js-select2 js-muzaki-select" required>
            <option value="">Pilih Muzaki</option>
            @foreach($muzakis as $muzakiOption)
                <option
                    value="{{ $muzakiOption->id }}"
                    data-nama="{{ $muzakiOption->nama }}"
                    data-nik="{{ $muzakiOption->nik }}"
                    data-alamat="{{ $muzakiOption->alamat }}"
                    data-hp="{{ $muzakiOption->no_hp }}"
                    data-email="{{ $muzakiOption->email }}"
                    @selected((string) old('idmuzaki', optional($setoran)->idmuzaki) === (string) $muzakiOption->id)
                >
                    {{ $muzakiOption->nama }} - {{ $muzakiOption->nik }}
                </option>
            @endforeach
            </select>
            <button type="button" class="btn btn-outline-warning js-open-muzaki-picker">
                <i class="bi bi-search"></i> Tampilkan Daftar Muzaki
            </button>
        </div>
        <div class="js-selected-muzaki mt-2 small text-muted">
            Pilih muzaki untuk melihat detail identitas.
        </div>
    </div>
    <div class="col-md-6">
        <label class="form-label">Jenis Setoran</label>
        <select name="idkode_setoran" class="form-select js-select2 js-jenis-setoran" required>
            <option value="">Pilih Jenis</option>
            @foreach($kodeSetorans as $kode)
                <option value="{{ $kode->id }}" data-jenis="{{ strtolower($kode->jenis_setoran) }}" @selected((string) old('idkode_setoran', optional($setoran)->idkode_setoran) === (string) $kode->id)>
                    {{ ucfirst($kode->jenis_setoran) }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6 js-program-wrapper d-none">
        <label class="form-label">Program Aktif</label>
        <select name="idprogram" class="form-select js-select2 js-program-select">
            <option value="">Pilih Program</option>
            @foreach($programs as $programOption)
                <option value="{{ $programOption->id }}" @selected((string) old('idprogram', optional($setoran)->idprogram) === (string) $programOption->id)>
                    {{ $programOption->nama_program }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label">Tanggal Setoran</label>
        <input type="date" name="created_at" class="form-control" value="{{ old('created_at', isset($setoran) && $setoran ? optional($setoran->created_at)->format('Y-m-d') : now()->format('Y-m-d')) }}" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Nominal</label>
        <input type="number" name="nominal" class="form-control" min="1" value="{{ old('nominal', isset($setoran) && $setoran ? (float) $setoran->nominal : '') }}" required>
    </div>
</div>

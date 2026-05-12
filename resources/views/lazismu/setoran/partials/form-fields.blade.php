<div class="row g-3">
    <div class="col-12">
        <div class="setoran-form-card">
            <label class="form-label fw-semibold mb-2">1. Pilih Muzaki</label>
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
                    data-code="{{ $muzakiOption->login_code }}"
                    @selected((string) old('idmuzaki', optional($setoran)->idmuzaki) === (string) $muzakiOption->id)
                >
                    {{ $muzakiOption->nama }} - {{ $muzakiOption->login_code }}
                </option>
            @endforeach
            </select>
            <button type="button" class="btn btn-outline-warning js-open-muzaki-picker">
                <i class="bi bi-search"></i> Tampilkan Daftar Muzaki
            </button>
            <button type="button" class="btn btn-outline-success js-scan-muzaki">
                <i class="bi bi-qr-code-scan"></i> Scan
            </button>
            </div>
            <input type="hidden" class="form-control mt-2 js-scan-code" placeholder="Hasil scan / ID muzaki" autocomplete="off">
            <div class="js-selected-muzaki mt-2 small text-muted">
                Pilih muzaki untuk melihat detail identitas.
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="row g-3">
            <div class="col-12">
                <label class="form-label fw-semibold">2. Jenis Setoran</label>
                <select name="idkode_setoran" class="form-select js-select2 js-jenis-setoran" required>
                    <option value="">Pilih Jenis</option>
                    @foreach($kodeSetorans as $kode)
                        <option value="{{ $kode->id }}" data-jenis="{{ strtolower($kode->jenis_setoran) }}" @selected((string) old('idkode_setoran', optional($setoran)->idkode_setoran) === (string) $kode->id)>
                            {{ ucfirst($kode->jenis_setoran) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-12">
                <label class="form-label fw-semibold">3. Nominal Setoran</label>
                <div class="nominal-input-box">
                    <div class="input-group input-group-lg">
                        <span class="input-group-text">Rp</span>
                        <input type="number" name="nominal" class="form-control js-nominal-input" min="1" value="{{ old('nominal', isset($setoran) && $setoran ? (float) $setoran->nominal : '') }}" placeholder="0" required>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <label class="form-label fw-semibold">4. Tanggal Setoran</label>
                <input type="date" name="created_at" class="form-control" value="{{ old('created_at', isset($setoran) && $setoran ? optional($setoran->created_at)->format('Y-m-d') : now()->format('Y-m-d')) }}" required>
            </div>
        </div>
    </div>
    <div class="col-lg-6 js-program-wrapper d-none">
        <div class="program-choice-panel">
            <label class="form-label fw-semibold">Detail Program</label>
            <select name="idprogram" class="form-select js-select2 js-program-select">
                <option value="">Pilih program aktif</option>
                @foreach($programs as $programOption)
                    <option value="{{ $programOption->id }}" @selected((string) old('idprogram', optional($setoran)->idprogram) === (string) $programOption->id)>
                        {{ $programOption->nama_program }} - Target Rp {{ number_format($programOption->target ?? 0, 0, ',', '.') }}
                    </option>
                @endforeach
            </select>
            <div class="small text-muted mt-2">Wajib dipilih jika jenis setoran adalah program.</div>
            <div class="mt-3 js-target-wrapper d-none">
                <label class="form-label fw-semibold">Target Setoran Muzaki</label>
                @php
                    $targetValue = '';
                    if (isset($setoran) && $setoran && $setoran->idmuzaki && $setoran->idprogram && isset($programTargets)) {
                        $targetValue = optional(optional($programTargets->get($setoran->idmuzaki))->get($setoran->idprogram))->target ?? '';
                    }
                @endphp
                <div class="input-group">
                    <input type="number" name="target_program" class="form-control js-target-program-input" min="0" value="{{ old('target_program', $targetValue !== '' ? (float) $targetValue : '') }}" placeholder="Masukkan target setoran">
                    <button type="button" class="btn btn-outline-warning js-update-target-btn d-none">Update Target</button>
                </div>
                <div class="small text-muted mt-1 js-target-help">Target wajib diisi untuk setoran program pertama muzaki.</div>
            </div>
            <div class="js-program-progress mt-3"></div>
        </div>
    </div>
    <div class="col-12">
        <div class="js-split-preview"></div>
    </div>
</div>

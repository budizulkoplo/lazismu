@php
    $item = $item ?? null;
    $editorId = 'pengeluaranEditor' . ($item?->id ?? 'Create');
    $deskripsiId = 'deskripsiInput' . ($item?->id ?? 'Create');
    $kelompokValue = old('kelompok', $item?->kelompok === '-' ? 'zakat' : ($item?->kelompok ?? 'zakat'));
@endphp

<div class="row g-3">
    <div class="col-md-4">
        <label class="form-label">No Nota</label>
        <input name="nota_no" class="form-control" maxlength="50" value="{{ old('nota_no', $item?->nota_no) }}" placeholder="Kosongi untuk otomatis">
    </div>
    <div class="col-md-4">
        <label class="form-label">Nama Transaksi</label>
        <input name="namatransaksi" class="form-control" maxlength="150" value="{{ old('namatransaksi', $item?->namatransaksi) }}" required>
    </div>
    <div class="col-md-4">
        <label class="form-label">Tanggal</label>
        <input type="date" name="tanggal" class="form-control" value="{{ old('tanggal', optional($item?->tanggal)->format('Y-m-d') ?? date('Y-m-d')) }}" required>
    </div>

    <div class="col-md-4">
        <label class="form-label">Kelompok</label>
        <select name="kelompok" class="form-select pengeluaran-kelompok" required>
            <option value="zakat" @selected($kelompokValue === 'zakat')>Zakat</option>
            <option value="infaq" @selected($kelompokValue === 'infaq')>Infaq</option>
            <option value="program" @selected($kelompokValue === 'program')>Program</option>
        </select>
    </div>
    <div class="col-md-4 pengeluaran-program-wrapper">
        <label class="form-label">Program</label>
        <select name="idprogram" class="form-select js-select2" data-placeholder="Pilih program">
            <option value="">Pilih program</option>
            @foreach($programs as $program)
                <option value="{{ $program->id }}" @selected((string) old('idprogram', $item?->idprogram) === (string) $program->id)>
                    {{ $program->nama_program }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label">Kode Transaksi</label>
        <select name="idkodetransaksi" class="form-select js-select2" data-placeholder="Pilih kode transaksi">
            <option value="">Pilih kode transaksi</option>
            @foreach($kodeTransaksis as $kode)
                <option value="{{ $kode->id }}" @selected((string) old('idkodetransaksi', $item?->idkodetransaksi) === (string) $kode->id)>
                    {{ $kode->kodetransaksi }} - {{ $kode->transaksi }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-4">
        <label class="form-label">Total Nominal</label>
        <div class="input-group">
            <span class="input-group-text">Rp</span>
            <input type="number" name="total" class="form-control text-end" min="1" step="0.01" value="{{ old('total', $item?->total) }}" required>
        </div>
    </div>
    <div class="col-md-4">
        <label class="form-label">Upload Nota/Proposal</label>
        <input type="file" name="bukti_nota" class="form-control pengeluaran-bukti" accept=".jpg,.jpeg,.png,.webp,.pdf" @required(!$item)>
        <small class="text-muted">JPG, PNG, WEBP, atau PDF. Gambar otomatis dikompres saat upload.</small>
        @if($item?->bukti_url)
            <div class="mt-1">
                <a href="{{ $item->bukti_url }}" target="_blank" class="small">Lihat file saat ini</a>
            </div>
        @endif
    </div>

    <div class="col-12">
        <label class="form-label">Keterangan</label>
        <textarea name="deskripsi" id="{{ $deskripsiId }}" class="d-none">{{ old('deskripsi', $item?->deskripsi) }}</textarea>
        <div id="{{ $editorId }}" class="pengeluaran-editor" data-target="#{{ $deskripsiId }}">{!! old('deskripsi', $item?->deskripsi) !!}</div>
        <small class="text-muted">Gunakan tombol gambar di editor untuk menyisipkan foto pendukung bila diperlukan.</small>
    </div>
</div>

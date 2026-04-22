<div class="mb-3">
    <label class="form-label">Jenis Setoran</label>
    <select name="jenis_setoran" class="form-select" required>
        @foreach(['zakat', 'infaq', 'program'] as $jenis)
            <option value="{{ $jenis }}" @selected(old('jenis_setoran', optional($item)->jenis_setoran) === $jenis)>{{ ucfirst($jenis) }}</option>
        @endforeach
    </select>
</div>

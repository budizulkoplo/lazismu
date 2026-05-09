<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registrasi Muzaki</title>
    @include('muzaki.partials.pwa-head')
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2/select2-bootstrap-5-theme.min.css') }}">
    <style>
        body { min-height: 100vh; background: linear-gradient(180deg, #fff7ed 0%, #ffffff 50%, #f7f9fb 100%); }
        .portal-card { max-width: 520px; border: 0; border-radius: 14px; box-shadow: 0 18px 45px rgba(252, 140, 4, .14); }
        .btn-brand { background: #fc8c04; border-color: #fc8c04; color: #fff; }
        .btn-brand:hover { background: #de7900; border-color: #de7900; color: #fff; }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center p-3">
    <div class="card portal-card w-100">
        <div class="card-body p-4 p-md-5">
            <div class="text-center mb-4">
                <img src="{{ asset($setting->path_logo ?? 'logo.png') }}" alt="Logo" style="height: 74px;" class="mx-auto mb-3">
                <h1 class="h3 mb-1">Registrasi Muzaki</h1>
                <p class="text-muted mb-0">Pilih pribadi atau kelompok. Nomor Induk Muzaki dibuat otomatis.</p>
            </div>

            @if($errors->any())
                <div class="alert alert-danger">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('muzaki.register.store') }}" class="row g-3">
                @csrf
                <div class="col-md-6">
                    <label class="form-label">Jenis Muzaki</label>
                    <select name="jenis_muzaki" id="jenisMuzaki" class="form-select form-select-lg" required>
                        <option value="pribadi" @selected(old('jenis_muzaki', 'pribadi') === 'pribadi')>Pribadi</option>
                        <option value="kelompok" @selected(old('jenis_muzaki') === 'kelompok')>Kelompok</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">NIK</label>
                    <input type="text" name="nik" id="nikInput" class="form-control form-control-lg" maxlength="16" value="{{ old('nik') }}" placeholder="Wajib untuk pribadi">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Nama</label>
                    <input type="text" name="nama" class="form-control form-control-lg" value="{{ old('nama') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">No HP</label>
                    <input type="text" name="no_hp" class="form-control form-control-lg" value="{{ old('no_hp') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Tanggal Lahir</label>
                    <input type="date" name="tgl_lahir" class="form-control form-control-lg" value="{{ old('tgl_lahir') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="form-select form-select-lg">
                        <option value="L" @selected(old('jenis_kelamin', 'L') === 'L')>Laki-laki / Perwakilan</option>
                        <option value="P" @selected(old('jenis_kelamin') === 'P')>Perempuan / Perwakilan</option>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control form-control-lg" value="{{ old('email') }}">
                </div>
                <div class="col-12">
                    <label class="form-label">Ranting</label>
                    <select name="ranting" id="rantingInput" class="form-select form-select-lg" required>
                        @php($selectedRanting = old('ranting'))
                        <option value=""></option>
                        @if($selectedRanting && !$rantings->contains($selectedRanting))
                            <option value="{{ $selectedRanting }}" selected>{{ $selectedRanting }}</option>
                        @endif
                        @foreach($rantings as $ranting)
                            <option value="{{ $ranting }}" @selected($selectedRanting === $ranting)>{{ $ranting }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">Alamat</label>
                    <textarea name="alamat" class="form-control form-control-lg" rows="3">{{ old('alamat') }}</textarea>
                </div>
                <div class="col-12 d-grid gap-2">
                    <button class="btn btn-brand btn-lg">Daftar dan Masuk</button>
                    <a href="{{ route('muzaki.login') }}" class="btn btn-light btn-lg">Kembali ke Login</a>
                </div>
            </form>
        </div>
    </div>
    <script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('plugins/select2/select2.full.min.js') }}"></script>
    <script>
        function syncNikRequirement() {
            const isPribadi = document.getElementById('jenisMuzaki').value === 'pribadi';
            const nik = document.getElementById('nikInput');
            nik.required = isPribadi;
            nik.placeholder = isPribadi ? 'Masukkan 16 digit NIK' : 'Boleh dikosongkan untuk kelompok';
        }
        document.getElementById('jenisMuzaki').addEventListener('change', syncNikRequirement);
        syncNikRequirement();

        if (window.jQuery && $.fn.select2) {
            $('#rantingInput').select2({
                theme: 'bootstrap-5',
                width: '100%',
                placeholder: 'Pilih ranting'
            });
        }
    </script>
    @include('muzaki.partials.pwa-install')
</body>
</html>

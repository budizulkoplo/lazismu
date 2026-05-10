<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Profil Muzaki</title>
    @include('muzaki.partials.pwa-head')
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/bootstrap-icons-1.13.1/bootstrap-icons.min.css') }}">
    <style>
        :root { --brand: #fc8c04; --brand-dark: #d97706; --ink: #172033; --muted: #64748b; }
        body { background: linear-gradient(180deg, #fff7ed 0%, #ffffff 50%, #f7f9fb 100%); color: var(--ink); }
        .page-shell { max-width: 540px; }
        .top-card { background: linear-gradient(135deg, var(--brand) 0%, #ffb14a 100%); color: #fff; border-radius: 18px; box-shadow: 0 14px 35px rgba(252, 140, 4, .22); }
        .form-card { border: 0; border-radius: 16px; box-shadow: 0 12px 30px rgba(15, 23, 42, .08); }
        .btn-orange { background: var(--brand); border-color: var(--brand); color: #fff; font-weight: 800; min-height: 48px; border-radius: 14px; }
        .btn-orange:hover { background: var(--brand-dark); border-color: var(--brand-dark); color: #fff; }
        .profile-photo { width: 84px; height: 84px; border-radius: 50%; object-fit: cover; border: 3px solid rgba(255,255,255,.85); background: #fff; }
    </style>
</head>
<body>
    @include('muzaki.partials.nav')
    @php
        $photoUrl = $muzaki->foto ? asset('storage/' . $muzaki->foto) : asset('assets/img/avatar1.jpg');
    @endphp
    <div class="container page-shell py-3 py-sm-4">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="top-card p-3 mb-3">
            <div class="d-flex align-items-center gap-3">
                <img src="{{ $photoUrl }}" class="profile-photo" alt="{{ $muzaki->nama }}">
                <div class="min-w-0">
                    <div class="small opacity-75">Profil Muzaki</div>
                    <h1 class="h5 fw-bold mb-1 text-truncate">{{ $muzaki->nama }}</h1>
                    <div class="small opacity-75">{{ $muzaki->login_code }}</div>
                </div>
            </div>
        </div>

        <form action="{{ route('muzaki.profile.update') }}" method="POST" enctype="multipart/form-data" class="card form-card">
            @csrf
            @method('PUT')
            <div class="card-body p-3">
                <div class="mb-3">
                    <label class="form-label">Foto Profil</label>
                    <input type="file" name="foto" class="form-control" accept=".jpg,.jpeg,.png,.webp">
                </div>
                <div class="mb-3">
                    <label class="form-label">Nama</label>
                    <input name="nama" class="form-control" value="{{ old('nama', $muzaki->nama) }}" required>
                </div>
                @if(($muzaki->jenis_muzaki ?? 'pribadi') !== 'aum')
                    <div class="mb-3">
                        <label class="form-label">Tanggal Lahir</label>
                        <input type="date" name="tgl_lahir" class="form-control" value="{{ old('tgl_lahir', optional($muzaki->tgl_lahir)->format('Y-m-d')) }}">
                    </div>
                @endif
                <div class="mb-3">
                    <label class="form-label">Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="form-select">
                        <option value="L" @selected(old('jenis_kelamin', $muzaki->jenis_kelamin) === 'L')>Laki-laki</option>
                        <option value="P" @selected(old('jenis_kelamin', $muzaki->jenis_kelamin) === 'P')>Perempuan</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">No HP</label>
                    <input name="no_hp" class="form-control" value="{{ old('no_hp', $muzaki->no_hp) }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $muzaki->email) }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Alamat</label>
                    <textarea name="alamat" class="form-control" rows="3">{{ old('alamat', $muzaki->alamat) }}</textarea>
                </div>
                <button class="btn btn-orange w-100">Simpan Profil</button>
            </div>
        </form>
    </div>
    @include('muzaki.partials.pwa-install')
</body>
</html>

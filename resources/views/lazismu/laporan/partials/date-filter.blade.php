<form method="GET" action="{{ $action }}" class="row g-2 align-items-end mb-3">
    <div class="col-md-3">
        <label class="form-label">Tanggal Awal</label>
        <input type="date" name="start_date" class="form-control" value="{{ $startDate->format('Y-m-d') }}">
    </div>
    <div class="col-md-3">
        <label class="form-label">Tanggal Akhir</label>
        <input type="date" name="end_date" class="form-control" value="{{ $endDate->format('Y-m-d') }}">
    </div>
    <div class="col-md-3">
        <button class="btn btn-warning"><i class="bi bi-funnel"></i> Filter</button>
    </div>
</form>

<div class="row g-3 mb-3">
    @foreach($items as $item)
        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">{{ $item['label'] }}</div>
                    <div class="h5 fw-bold mb-0">
                        @if(($item['money'] ?? true) === false)
                            {{ number_format((float) $item['value'], 0, ',', '.') }}
                        @else
                            @include('lazismu.laporan.partials.money', ['value' => $item['value']])
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

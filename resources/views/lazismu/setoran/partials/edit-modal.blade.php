<div class="modal fade" id="editSetoranModal{{ $setoran->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form action="{{ route('lazismu.setoran.update', $setoran) }}" method="POST" class="modal-content" id="editSetoranForm{{ $setoran->id }}">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title">Edit Setoran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                @include('lazismu.setoran.partials.form-fields', ['setoran' => $setoran, 'muzakis' => $muzakis, 'kodeSetorans' => $kodeSetorans, 'programs' => $programs])
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <button class="btn btn-warning">Update</button>
            </div>
        </form>
    </div>
</div>

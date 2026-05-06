<script>
    function initLazismuSelect2(context) {
        if (!window.jQuery || !$.fn.select2) return;

        const scope = context || document;
        $(scope).find('.js-select2').each(function() {
            if ($(this).data('select2')) return;

            const $modal = $(this).closest('.modal');
            $(this).select2({
                theme: 'bootstrap-5',
                width: '100%',
                dropdownParent: $modal.length ? $modal : $(document.body)
            });
        });
    }

    function initLazismuDataTables(context) {
        if (!window.jQuery || !$.fn.DataTable) return;

        const scope = context || document;
        $(scope).find('.js-lazismu-table').each(function() {
            if ($.fn.DataTable.isDataTable(this)) return;

            const modal = this.closest('.modal');
            if (modal && !modal.classList.contains('show')) return;

            $(this).DataTable({
                responsive: true,
                pageLength: 10,
                lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'Semua']],
                dom:
                    "<'row align-items-center gy-2 mb-3'<'col-md-6'l><'col-md-6'f>>" +
                    "<'row mb-3'<'col-12'B>>" +
                    "<'row'<'col-12'tr>>" +
                    "<'row align-items-center gy-2 mt-3'<'col-md-5'i><'col-md-7'p>>",
                buttons: [
                    { extend: 'copyHtml5', text: '<i class="bi bi-copy"></i> Copy', className: 'btn btn-sm btn-outline-secondary' },
                    { extend: 'excelHtml5', text: '<i class="bi bi-file-earmark-excel"></i> Excel', className: 'btn btn-sm btn-outline-success' },
                    { extend: 'print', text: '<i class="bi bi-printer"></i> Print', className: 'btn btn-sm btn-outline-primary' },
                    { extend: 'colvis', text: '<i class="bi bi-layout-three-columns"></i> Kolom', className: 'btn btn-sm btn-outline-warning' }
                ],
                language: {
                    search: 'Cari:',
                    lengthMenu: 'Tampilkan _MENU_ data',
                    info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ data',
                    infoEmpty: 'Tidak ada data',
                    infoFiltered: '(difilter dari _MAX_ total data)',
                    zeroRecords: 'Data tidak ditemukan',
                    emptyTable: 'Belum ada data',
                    paginate: {
                        first: 'Pertama',
                        last: 'Terakhir',
                        next: 'Selanjutnya',
                        previous: 'Sebelumnya'
                    },
                    buttons: {
                        colvis: 'Kolom'
                    }
                }
            });
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        initLazismuSelect2(document);
        initLazismuDataTables(document);

        document.querySelectorAll('.modal').forEach(function(modal) {
            modal.addEventListener('shown.bs.modal', function() {
                initLazismuSelect2(modal);
                initLazismuDataTables(modal);
            });
        });
    });
</script>

<style>
    .dataTables_wrapper,
    .dt-container {
        color: #1f2937;
    }

    .dataTables_wrapper .row,
    .dt-container .row {
        row-gap: .75rem;
    }

    .dt-buttons {
        display: flex;
        flex-wrap: wrap;
        gap: .5rem;
    }

    .dt-buttons .btn {
        border-radius: .45rem !important;
        font-weight: 600;
        min-height: 34px;
        padding: .35rem .7rem;
    }

    .dt-buttons .btn-outline-secondary,
    .dt-buttons .btn-outline-success,
    .dt-buttons .btn-outline-primary,
    .dt-buttons .btn-outline-warning {
        background-color: #fff;
    }

    .dt-buttons .btn-outline-warning {
        color: #de7900;
        border-color: #fc8c04;
    }

    .dt-buttons .btn-outline-secondary:hover,
    .dt-buttons .btn-outline-secondary:focus {
        background-color: #4b5563;
        border-color: #4b5563;
        color: #fff !important;
    }

    .dt-buttons .btn-outline-success:hover,
    .dt-buttons .btn-outline-success:focus {
        background-color: #198754;
        border-color: #198754;
        color: #fff !important;
    }

    .dt-buttons .btn-outline-primary:hover,
    .dt-buttons .btn-outline-primary:focus {
        background-color: #0d6efd;
        border-color: #0d6efd;
        color: #fff !important;
    }

    .dt-buttons .btn-outline-warning:hover,
    .dt-buttons .btn-outline-warning:focus {
        background-color: #fc8c04;
        border-color: #fc8c04;
        color: #fff !important;
    }

    .dataTables_filter,
    .dt-search {
        text-align: right;
    }

    .dataTables_filter label,
    .dt-search label {
        display: inline-flex;
        align-items: center;
        gap: .5rem;
        margin-bottom: 0;
    }

    .dataTables_filter input,
    .dt-search input {
        width: min(240px, 52vw) !important;
        margin-left: 0 !important;
    }

    .dataTables_length label,
    .dt-length label {
        display: inline-flex;
        align-items: center;
        gap: .5rem;
        margin-bottom: 0;
    }

    .dataTables_length select,
    .dt-length select {
        min-width: 86px;
    }

    .js-lazismu-table {
        border-collapse: separate !important;
        border-spacing: 0;
    }

    .js-lazismu-table thead th {
        background: #fff7ed !important;
        border-bottom: 1px solid #f2d4ad !important;
        color: #1f2937;
        font-weight: 700;
        vertical-align: middle;
        white-space: nowrap;
    }

    .js-lazismu-table tbody td {
        border-bottom: 1px solid #edf0f3;
        vertical-align: middle;
    }

    .js-lazismu-table tbody tr:hover td {
        background: #fffaf3;
    }

    .dataTables_info,
    .dt-info {
        padding-top: .35rem !important;
        color: #64748b;
    }

    .dataTables_paginate,
    .dt-paging {
        display: flex;
        justify-content: flex-end;
    }

    .pagination {
        margin-bottom: 0;
    }

    .page-item.active .page-link {
        background-color: #fc8c04;
        border-color: #fc8c04;
    }

    .page-link {
        color: #de7900;
    }

    .page-link:hover {
        color: #b86400;
    }

    @media (max-width: 767.98px) {
        .dataTables_filter,
        .dt-search,
        .dataTables_paginate,
        .dt-paging {
            justify-content: flex-start;
            text-align: left;
        }

        .dataTables_filter input,
        .dt-search input {
            width: 100% !important;
        }
    }
</style>

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
                    "<'row mb-3'<'col-12 d-flex justify-content-start'B>>" +
                    "<'row'<'col-12'tr>>" +
                    "<'row align-items-center gy-2 mt-3'<'col-md-5'i><'col-md-7 d-flex justify-content-md-end justify-content-start'p>>",
                buttons: [
                    { extend: 'copyHtml5', text: '<i class="bi bi-copy me-1"></i> Copy', className: 'btn btn-outline-secondary' },
                    { extend: 'excelHtml5', text: '<i class="bi bi-file-earmark-excel me-1"></i> Excel', className: 'btn btn-outline-success' },
                    { extend: 'print', text: '<i class="bi bi-printer me-1"></i> Print', className: 'btn btn-outline-primary' },
                    { extend: 'colvis', text: '<i class="bi bi-layout-three-columns me-1"></i> Kolom', className: 'btn btn-outline-warning' }
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

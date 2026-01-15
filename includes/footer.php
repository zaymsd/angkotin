</main>

<!-- Footer -->
<?php if (is_logged_in()): ?>
    <footer class="bg-light text-center text-muted py-3 mt-5">
        <div class="container">
            <p class="mb-0">
                &copy;
                <?php echo date('Y'); ?> Sistem Informasi Angkot - Angkotin
                <span class="mx-2">|</span>
                <small>Logged in as: <strong>
                        <?php echo get_username(); ?>
                    </strong></small>
            </p>
        </div>
    </footer>
<?php endif; ?>

<!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- jQuery (required for DataTables) -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

<!-- DataTables -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Chart.js (for reports) -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<!-- Custom JavaScript -->
<script src="<?php echo $base_path; ?>/assets/js/script.js"></script>

<!-- Initialize DataTables -->
<script>
    $(document).ready(function () {
        // Initialize DataTables with Indonesian language
        if ($.fn.DataTable) {
            $('.datatable').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
                },
                pageLength: 25,
                responsive: true,
                order: [[0, 'desc']]
            });
        }

        // Confirm delete action
        $('.btn-delete').on('click', function (e) {
            e.preventDefault();
            const href = $(this).attr('href');
            const itemName = $(this).data('name') || 'item ini';

            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: `Yakin ingin menghapus ${itemName}?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = href;
                }
            });
        });

        // Auto-hide alerts after 5 seconds
        setTimeout(function () {
            $('.alert').fadeOut('slow');
        }, 5000);
    });
</script>
</body>

</html>
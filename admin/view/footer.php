    Bootstrap JS
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Debug modal
        document.addEventListener('DOMContentLoaded', function() {
            var modalTriggers = document.querySelectorAll('[data-bs-toggle="modal"]');
            modalTriggers.forEach(function(trigger) {
                trigger.addEventListener('click', function(event) {
                    var targetModalId = this.getAttribute('data-bs-target');
                    var targetModal = document.querySelector(targetModalId);
                    
                    if (targetModal) {
                        var modal = new bootstrap.Modal(targetModal);
                        modal.show();
                    } else {
                        console.error('Modal tidak ditemukan: ' + targetModalId);
                    }
                });
            });

            // Format Rupiah
            var rupiahInputs = document.querySelectorAll('.rupiah');
            rupiahInputs.forEach(function(input) {
                input.addEventListener('input', function(e) {
                    // Hapus karakter selain angka
                    let value = this.value.replace(/[^\d]/g, '');
                    
                    // Format dengan titik sebagai pemisah ribuan
                    this.value = formatRupiah(value);
                });
            });

            function formatRupiah(angka) {
                if (angka === '') return '';
                
                let number_string = angka.toString();
                let split = number_string.split(',');
                let sisa = split[0].length % 3;
                let rupiah = split[0].substr(0, sisa);
                let ribuan = split[0].substr(sisa).match(/\d{3}/gi);

                if (ribuan) {
                    let separator = sisa ? '.' : '';
                    rupiah += separator + ribuan.join('.');
                }

                return rupiah;
            }

            // Fix modal backdrop issue
            var modals = document.querySelectorAll('.modal');
            modals.forEach(function(modal) {
                modal.addEventListener('hidden.bs.modal', function () {
                    // Remove any existing backdrops
                    var backdrops = document.querySelectorAll('.modal-backdrop');
                    backdrops.forEach(function(backdrop) {
                        backdrop.remove();
                    });
                    
                    // Remove body classes that might cause issues
                    document.body.classList.remove('modal-open');
                    document.body.style.overflow = '';
                    document.body.style.paddingRight = '';
                });
            });
        });
    </script>
</body>
</html>
    <?php if ($this->session->has_userdata('error')) { ?>
        <div class="alert-error" data-alert="<?= $this->session->flashdata('error') ?>"></div>
    <?php } else if ($this->session->has_userdata('success')) { ?>
        <div class="alert-success" data-alert="<?= $this->session->flashdata('success') ?>"></div>
    <?php } else if ($this->session->has_userdata('warning')) { ?>
        <div class="alert-warning" data-alert="<?= $this->session->flashdata('warning') ?>"></div>
    <?php } else if ($this->session->has_userdata('other')) { ?>
        <div class="alert-warning" data-alert="<?= $this->session->flashdata('other') ?>"></div>
    <?php } ?>

    <script>
        $(document).ready(function() {
            const other = $('.alert-other').data('alert');
            if (other) {
                $('#otp').modal({
                    backdrop: 'static',
                    keyboard: false
                })
            }
            const error = $('.alert-error').data('alert');
            if (error) {
                swal({
                    title: "Failed",
                    text: error,
                    type: "error",
                    showCancelButton: false,
                    confirmButtonClass: "btn-danger",
                    closeOnConfirm: true
                })
            }

            const success = $('.alert-success').data('alert');
            if (success) {
                swal({
                    title: "Success",
                    text: success,
                    type: "success",
                    showCancelButton: false,
                    confirmButtonClass: "btn-success",
                    closeOnConfirm: true
                })
            }

            const warning = $('.alert-warning').data('alert');
            if (warning) {
                swal({
                    title: "Warning",
                    text: warning,
                    type: "warning",
                    showCancelButton: false,
                    confirmButtonClass: "btn-warning",
                    closeOnConfirm: true
                })
            }
        })
        
        // confirm
        $(document).on("click", (function(e) {
            if ($(e.target).hasClass("confirm")) {
                const title = e.target.getAttribute("data-title");
                const alert = e.target.getAttribute("data-title");

                e.stopImmediatePropagation()
                e.preventDefault();
                
                const href = $(e.target).attr("data-href");
                swal({
                    title: `${title}`,
                    text: `${alert}`,
                    type: "warning",
                    showCancelButton: !0,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    cancelButtonText: "Tidak",
                    confirmButtonText: "Ya"
                }, result => {
                    result === true && (document.location.href = href);
                })
            }
        }))
    </script>
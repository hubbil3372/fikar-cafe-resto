 <?php if ($this->session->has_userdata('error')) { ?>
     <div class="alert-error" data-alert="<?= $this->session->flashdata('error') ?>"></div>
 <?php $this->view('modal/alert_modal_error');
    } else if ($this->session->has_userdata('success')) { ?>
     <div class="alert-success" data-alert="<?= $this->session->flashdata('success') ?>"></div>
 <?php $this->view('modal/alert_modal_success');
    } else if ($this->session->has_userdata('warning')) { ?>
     <div class="alert-warning" data-alert="<?= $this->session->flashdata('warning') ?>"></div>
 <?php $this->view('modal/alert_modal_warning');
    }
    // $this->view('modal/alert_modal_error');
    // $this->view('modal/alert_modal_success');
    // $this->view('modal/alert_modal_warning');
    ?>

 <script>
     const success = $('.alert-success').data('alert');
     const warning = $('.alert-warning').data('alert');
     const error = $('.alert-error').data('alert');
     if (success) {
         $('#modal-alert-success').modal('show');
         $('#text-alert').text(success);
     }
     if (warning) {
         $('#modal-alert-warning').modal('show');
         $('#text-alert').text(warning);
     }

     if (error) {
         $('#modal-alert-error').modal('show');
         $('#text-alert').text(error);
     }
 </script>
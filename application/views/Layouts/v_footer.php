<!-- CKEditor -->
<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/js/custom.js"></script>
<!-- charts -->
<script type="text/javascript" src="<?= base_url() ?>assets/plugins/charts/charts.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/plugins/charts/charts-call-graphs.js"></script>

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/rowreorder/1.5.0/js/dataTables.rowReorder.js"></script>
<script src="https://cdn.datatables.net/rowreorder/1.5.0/js/rowReorder.dataTables.js"></script>
<script src="https://cdn.datatables.net/responsive/3.0.3/js/dataTables.responsive.js"></script>
<script src="https://cdn.datatables.net/responsive/3.0.3/js/responsive.dataTables.js"></script>

<!-- Sweetalert -->
<script src="<?= base_url() ?>assets/vendor/sweetalert2/js/sweetalert2.all.min.js"></script>
<!-- Select2 -->
<script type="text/javascript" src="<?php echo base_url(); ?>assets/vendor/select2/js/select2.min.js"></script>
<!-- CKEDITOR -->
<script src="https://cdn.ckeditor.com/ckeditor5/41.2.1/classic/ckeditor.js"></script>
<!-- My Script -->
<script src="<?= base_url('assets/js/myscript.js') ?>"></script>


<script>
    ClassicEditor.create(document.querySelector("#isi_memo")).then(editor => instance = editor);
    $(document).ready(function() {
        $('.js-example-basic-multiple').select2();

        if ($('body').hasClass('theme-dark') == true) {
            $("img[id='logo-bandes']").attr('src', '<?= base_url('assets/images/logo-white.png') ?>')
            $("img[id='logo-bandes2']").attr('src', '<?= base_url('assets/images/logo-white.png') ?>')
        } else {
            $("img[id='logo-bandes']").attr('src', '<?= base_url('assets/images/logo-black.png') ?>')
            $("img[id='logo-bandes2']").attr('src', '<?= base_url('assets/images/logo-black.png') ?>')
        }

        <?php if ($this->session->flashdata('forbidden')) { ?>
            Swal.fire({
                title: "Error",
                text: "<?= $this->session->flashdata('forbidden') ?>",
                icon: "error",
            })
        <?php } ?>

        <?php if ($this->session->flashdata('success')) { ?>
            Swal.fire({
                title: "success",
                text: "<?= $this->session->flashdata('success') ?>",
                icon: "success",
            })
        <?php } ?>
    })

    function PageReload() {
        setTimeout(function() {
            location.reload();
        }, 2000);
    }

    new DataTable('#example', {
        responsive: true,
        rowReorder: {
            selector: 'td:nth-child(2)'
        }
    });
</script>
</body>

</html>
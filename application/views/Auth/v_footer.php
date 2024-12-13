<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/js/custom.js"></script>
<script>
    $(document).ready(function() {
        // if ($('body').hasClass('theme-dark') == true) {
        //     $("img[id='logo-bandes']").attr('src', '<?= base_url('assets/images/logo-white.png') ?>')
        //     $("img[id='logo-bandes2']").attr('src', '<?= base_url('assets/images/logo-white.png') ?>')
        //     $("img[id='logo-login']").attr('src', '<?= base_url('assets/images/logo_bdl.png') ?>')
        // } else {
        //     $("img[id='logo-bandes']").attr('src', '<?= base_url('assets/images/logo-black.png') ?>')
        //     $("img[id='logo-bandes2']").attr('src', '<?= base_url('assets/images/logo-black.png') ?>')
        //     $("img[id='logo-login']").attr('src', '<?= base_url('assets/images/logo2.png') ?>')
        // }

        $('#form-login').on('submit', function() {
            $('#btn-login').html('Loading...');
            $('#btn-login').attr('disabled', true);
        })
    })
</script>
</body>
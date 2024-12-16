<div id="page">

    <div class="page-content header-clear-medium">

        <div class="card card-style">

            <div class="content">

                <div class="text-center">

                    <img src="<?= base_url() ?>assets/images/kodesis_kotak.png" alt="logo" width="50%" class="mb-5" id="logo-login">

                </div>

                <?php if ($this->session->flashdata('msg')) {

                    echo $this->session->flashdata('msg');
                }

                ?>

                <form action="<?= base_url('auth/login') ?>" method="post" id="form-login">

                    <div class="input-style no-borders has-icon mt-2 p-0 mb-0">

                        <i class="fa fa-phone" aria-hidden="true"></i>
                        <input type="text" class="form-control" id="username" name="username" placeholder="No Handphone" value="<?= set_value('username') ?>" />

                        <label for="form1a" class="color-highlight">No Handphone</label>

                    </div>

                    <div class="input-style no-borders has-icon mt-2 p-0 mb-0">

                        <i class="fa fa-lock"></i>

                        <input type="password" class="form-control" id="password" name="password" placeholder="Password" />

                        <label for="form1a" class="color-highlight">Password</label>

                    </div>

                    <button class="btn btn-full btn-l font-600 font-13 gradient-highlight mt-4 rounded-s w-100" id="btn-login">Sign In</button>

                </form>

            </div>

        </div>

    </div>

</div>
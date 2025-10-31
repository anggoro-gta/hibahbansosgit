<?= $this->extend('auth/template/indexgta'); ?>

<?= $this->section('logincontentgta'); ?>

<div class="container">
    <div class="left-side"></div>
    <div class="right-side">
        <!-- <p class="login-box-msg"><?= lang('Auth.loginTitle') ?></p> -->

        <?= view('Myth\Auth\Views\_message_block') ?>

        <img class="logo" src="<?= base_url(); ?>/imageslogingta/logo.png" alt="" />

        <h2>Monitoring Musrenbang</h2>

        <form action="<?= route_to('login') ?>" method="post">
            <?= csrf_field() ?>

            <?php if ($config->validFields === ['email']) : ?>
                <div class="input-group mb-3">
                    <input type="email" class="form-input <?php if (session('errors.login')) : ?>is-invalid<?php endif ?>" name="login" placeholder="<?= lang('Auth.email') ?>">
                    <div class="input-group-append">
                        <!-- <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div> -->
                    </div>
                    <div class="invalid-feedback">
                        <?= session('errors.login') ?>
                    </div>
                </div>
            <?php else : ?>

                <div class="input-group mb-3">
                    <input type="text" class="form-input <?php if (session('errors.login')) : ?>is-invalid<?php endif ?>" name="login" placeholder="<?= lang('Auth.emailOrUsername') ?>">
                    <div class="input-group-append">
                        <!-- <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div> -->
                    </div>
                    <div class="invalid-feedback">
                        <?= session('errors.login') ?>
                    </div>
                </div>
            <?php endif; ?>

            <div class="input-group mb-3">
                <input type="password" id="password" name="password" class="form-input  <?php if (session('errors.password')) : ?>is-invalid<?php endif ?>" placeholder="<?= lang('Auth.password') ?>">
                <div class="input-group-append">
                    <input type="checkbox" onclick="showHide()"> Tampilkan Password
                    <!-- <div class="input-group-text">
                        <span class="fas fa-lock"></span>
                    </div> -->
                </div>
                <div class="invalid-feedback">
                    <?= session('errors.password') ?>
                </div>
            </div>

            <button class="button"><?= lang('Auth.loginAction') ?></button>

            <?php if ($config->allowRegistration) : ?>
                <p class="mb-0">
                    <a href="<?= route_to('register') ?>" class="text-center"><?= lang('Auth.needAnAccount') ?></a>
                </p>
            <?php endif; ?>
            <?php if ($config->activeResetter) : ?>
                <p class="mb-0">
                    <a href="<?= route_to('forgot') ?>" class="text-center"><?= lang('Auth.forgotYourPassword') ?></a>
                </p>
            <?php endif; ?>
        </form>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('javascriptkhusus'); ?>

<script>
    function showHide() {
        var inputan = document.getElementById("password");
        if (inputan.type === "password") {
            inputan.type = "text";
        } else {
            inputan.type = "password";
        }
    }
</script>

<?= $this->endSection(); ?>
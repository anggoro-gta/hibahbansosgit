<?= $this->extend('auth/template/index'); ?>

<?= $this->section('logincontent'); ?>
<div class="register-box">
    <div class="card card-outline card-primary">
        <div class="card-header text-center">
            <a href="#" class="h1">siDika<b>Register</b></a>
        </div>
        <div class="card-body">
            <p class="login-box-msg"><?= lang('Auth.register') ?></p>

            <?= view('Myth\Auth\Views\_message_block') ?>

            <form action="<?= route_to('register') ?>" method="post">
                <?= csrf_field() ?>

                <small id="emailHelp" class="form-text text-muted"><?= lang('Auth.weNeverShare') ?></small>
                <div class="input-group mb-3">
                    <input type="email" class="form-control <?php if (session('errors.email')) : ?>is-invalid<?php endif ?>" name="email" aria-describedby="emailHelp" placeholder="<?= lang('Auth.email') ?>" value="<?= old('email') ?>">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                </div>

                <div class="input-group mb-3">
                    <input type="text" class="form-control <?php if (session('errors.username')) : ?>is-invalid<?php endif ?>" name="username" placeholder="<?= lang('Auth.username') ?>" value="<?= old('username') ?>">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-user"></span>
                        </div>
                    </div>
                </div>

                <div class="input-group mb-3">
                    <input type="password" name="password" class="form-control <?php if (session('errors.password')) : ?>is-invalid<?php endif ?>" placeholder="<?= lang('Auth.password') ?>" autocomplete="off">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>

                <div class="input-group mb-3">
                    <input type="password" name="pass_confirm" class="form-control <?php if (session('errors.pass_confirm')) : ?>is-invalid<?php endif ?>" placeholder="<?= lang('Auth.repeatPassword') ?>" autocomplete="off">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <!-- <div class="col-8">
                        <div class="icheck-primary">
                            <input type="checkbox" id="agreeTerms" name="terms" value="agree">
                            <label for="agreeTerms">
                                I agree to the <a href="#">terms</a>
                            </label>
                        </div>
                    </div> -->
                    <!-- /.col -->
                    <div class="col-4">
                        <button type="submit" class="btn btn-primary btn-block"><?= lang('Auth.register') ?></button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>

            <!-- <div class="social-auth-links text-center">
                <a href="#" class="btn btn-block btn-primary">
                    <i class="fab fa-facebook mr-2"></i>
                    Sign up using Facebook
                </a>
                <a href="#" class="btn btn-block btn-danger">
                    <i class="fab fa-google-plus mr-2"></i>
                    Sign up using Google+
                </a>
            </div> -->

            <a href="<?= route_to('login') ?>" class="text-center">
                <p><?= lang('Auth.alreadyRegistered') ?> <?= lang('Auth.signIn') ?></p>
            </a>
        </div>
        <!-- /.form-box -->
    </div><!-- /.card -->
</div>
<!-- /.register-box -->
<?= $this->endSection(); ?>
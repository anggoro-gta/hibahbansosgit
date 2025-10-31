<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="icon" href="<?= base_url(); ?>/imageslogingta/logo.png">
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login</title>
    <link rel="stylesheet" href="<?= base_url(); ?>/assets/csslogingta/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet" />
            
    <!-- Theme style -->
    <link rel="stylesheet" href="<?= base_url(); ?>/assets/dist/css/adminlte.min.css">    
</head>

<body>
    <?= $this->renderSection('logincontentgta'); ?>

    <!-- jQuery -->
    <script src="<?= base_url(); ?>/assets/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="<?= base_url(); ?>/assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="<?= base_url(); ?>/assets/dist/js/adminlte.min.js"></script>

    <?= $this->renderSection('javascriptkhusus'); ?>
</body>

</html>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Trang web bán mỹ phẩm</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
        integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!--đường link bootstrap-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo asset('assets/web/css/styleindex.css') ?>">
    <!--fonts chữ cho logo-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">

    <link rel="stylesheet" href="<?php echo asset('assets/web/css/styleproduct.css') ?>">
    <link rel="stylesheet" href="<?php echo asset('assets/web/css/stylecart.css') ?>">
    <?php defineblock('css') ?>

</head>

<body>
    <?php include(_DIR_ROOT . '/app/views/web/layouts/includes/header.php') ?>
    <!-- /header -->

    <!-- main -->
    <?php defineblock('content') ?>
    <!-- /main -->
    <!--/footer-->
    <?php include(_DIR_ROOT . '/app/views/web/layouts/includes/footer.php') ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo asset('assets/web/js/jsindex.js') ?>" defer></script>
    <script src="<?php echo asset('assets/web/js/jsproduct.js') ?>"></script>
    <script src="<?php echo asset('assets/web/js/jsaddcart.js') ?>"></script>
     <script src="<?php echo asset('assets/web/js/jschatbot.js') ?>"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const CHATBOT_URL = "<?php echo url('ChatbotController/ask') ?>";
    </script>
    <?php defineblock('js') ?>

</body>

</html>
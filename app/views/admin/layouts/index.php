<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privia Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script>
    window.CURRENT_USER_ROLE = '<?php 
        if (isset($_SESSION['admin']['LoaiTaiKhoan'])) {
            echo $_SESSION['admin']['LoaiTaiKhoan'];
        } elseif (isset($_SESSION['staff']['LoaiTaiKhoan'])) {
            echo $_SESSION['staff']['LoaiTaiKhoan'];
        } elseif (isset($_SESSION['user']['LoaiTaiKhoan'])) {
            echo $_SESSION['user']['LoaiTaiKhoan'];
        } else {
            echo 'user';
        }
    ?>'; 
</script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <link href="<?= asset('assets/admin/css/style.css') ?>" rel="stylesheet">
    
    <?php defineblock('Acss') ?>
</head>
<body class="d-flex flex-column flex-md-row min-vh-100 overflow-x-hidden">

    <div id="sidebarOverlay" onclick="toggleSidebar()" class="position-fixed top-0 start-0 w-100 h-100 bg-dark bg-opacity-50 d-none z-3"></div>

    <?php include('app/views/admin/layouts/includes/sidebar.php') ?>

    <div class="flex-grow-1 d-flex flex-column min-vw-0 vh-100">
        
  
            <?php include('app/views/admin/layouts/includes/header.php') ?>

        
        <main class="flex-grow-1 p-3 p-md-4 overflow-y-auto custom-scrollbar">
        
            <?php defineblock('Acontent') ?>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <?php defineblock('Ajs') ?>
    <script src="<?= asset('assets/admin/js/index.js') ?>"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function toggleSidebar() {
            document.getElementById('adminSidebar').classList.toggle('show');
            const overlay = document.getElementById('sidebarOverlay');
            if(overlay.classList.contains('d-none')) {
                overlay.classList.remove('d-none');
            } else {
                overlay.classList.add('d-none');
            }
        }
    </script>
</body>
</html>
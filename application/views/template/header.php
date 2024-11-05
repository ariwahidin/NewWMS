<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="<?= ($this->uri->segment(1) == 'order' && $this->uri->segment(2) == 'planningOrder') ? 'sm' : 'lg' ?>" data-sidebar-image="none" data-preloader="disable">

<head>

    <meta charset="utf-8" />
    <title>Yusen Logistics</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Yamaha Visual Application System" name="description" />
    <meta content="Themesbrand" name="author" />

    <!-- jsvectormap css -->
    <link href="<?= base_url('jar/html/default/') ?>assets/libs/jsvectormap/css/jsvectormap.min.css" rel="stylesheet" type="text/css" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="<?= base_url('jar/html/default/') ?>assets/images/yusen-kotak.jpg">

    <!-- gridjs css -->
    <link rel="stylesheet" href="<?= base_url('jar/html/default/') ?>assets/libs/gridjs/theme/mermaid.min.css">
    <!-- Layout config Js -->
    <script src="<?= base_url('jar/html/default/') ?>assets/js/layout.js"></script>
    <!-- Bootstrap Css -->
    <link href="<?= base_url('jar/html/default/') ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="<?= base_url('jar/html/default/') ?>assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="<?= base_url('jar/html/default/') ?>assets/css/app.min.css" rel="stylesheet" type="text/css" />
    <!-- custom Css-->
    <link href="<?= base_url('jar/html/default/') ?>assets/css/custom.min.css" rel="stylesheet" type="text/css" />

    <link href="<?= base_url() ?>myassets/css/jquery.dataTables.min.css" rel="stylesheet" />


    <!-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" /> -->
    <!-- Sweet Alert css-->
    <link href="<?= base_url('jar/html/default/') ?>assets/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />
    <!-- <script src="<?= base_url() ?>myassets/js/jquery-3.7.0.js"></script> -->
    <script src="<?= base_url() ?>myassets/js/moment.js"></script>
    <script src="<?= base_url() ?>myassets/js/moment-time-zone.js"></script>

    <script src="<?= base_url() ?>myassets/js/jquery-3.7.0.js"></script>

    <!-- <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> -->
    <script src="<?= base_url() ?>myassets/js/jquery.dataTables.min.js"></script>



</head>

<style>
    /* CSS untuk latar belakang hitam transparan */
    .overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.7);
        /* Warna hitam dengan opasitas 0.7 */
        z-index: 9999;
        /* Menempatkan latar belakang di atas konten */
        display: flex;
        justify-content: center;
        align-items: center;
    }

    /* Kode CSS untuk animasi spinner yang Anda sebutkan sebelumnya */
    .lds-roller {
        display: inline-block;
        position: relative;
        width: 80px;
        height: 80px;
    }

    .lds-roller div {
        animation: lds-roller 1.2s cubic-bezier(0.5, 0, 0.5, 1) infinite;
        transform-origin: 40px 40px;
    }

    .lds-roller div:after {
        content: " ";
        display: block;
        position: absolute;
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: #fff;
        margin: -4px 0 0 -4px;
    }

    .lds-roller div:nth-child(1) {
        animation-delay: -0.036s;
    }

    .lds-roller div:nth-child(1):after {
        top: 63px;
        left: 63px;
    }

    .lds-roller div:nth-child(2) {
        animation-delay: -0.072s;
    }

    .lds-roller div:nth-child(2):after {
        top: 68px;
        left: 56px;
    }

    .lds-roller div:nth-child(3) {
        animation-delay: -0.108s;
    }

    .lds-roller div:nth-child(3):after {
        top: 71px;
        left: 48px;
    }

    .lds-roller div:nth-child(4) {
        animation-delay: -0.144s;
    }

    .lds-roller div:nth-child(4):after {
        top: 72px;
        left: 40px;
    }

    .lds-roller div:nth-child(5) {
        animation-delay: -0.18s;
    }

    .lds-roller div:nth-child(5):after {
        top: 71px;
        left: 32px;
    }

    .lds-roller div:nth-child(6) {
        animation-delay: -0.216s;
    }

    .lds-roller div:nth-child(6):after {
        top: 68px;
        left: 24px;
    }

    .lds-roller div:nth-child(7) {
        animation-delay: -0.252s;
    }

    .lds-roller div:nth-child(7):after {
        top: 63px;
        left: 17px;
    }

    .lds-roller div:nth-child(8) {
        animation-delay: -0.288s;
    }

    .lds-roller div:nth-child(8):after {
        top: 56px;
        left: 12px;
    }

    /* Sisipkan sisa kode CSS untuk animasi spinner di sini */

    @keyframes lds-roller {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }
</style>



<div class="pLoading" style="display: none;">
    <div class="overlay">
        <div class="lds-roller">
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
        </div>
    </div>
</div>

<?php
$ws_config = wsConfig();
$ws_port = $ws_config->port;
$ws_first_path =  $ws_config->first_path;
?>

<script>
    const hostnameWebsocket = window.location.hostname;
    let protocolHttp = window.location.protocol;
    let urlWebsocket = 'ws://' + hostnameWebsocket + ':' + '<?= $ws_port ?>';
    if (protocolHttp == 'https:') {
        urlWebsocket = 'wss://' + hostnameWebsocket + '<?= $ws_first_path ?>';
    }

    function formatTime(timeString) {
        var timeComponents = timeString.split(':');

        if (timeComponents.length !== 3) {
            return 'Invalid time format';
        }

        var hours = parseInt(timeComponents[0]);
        var minutes = parseInt(timeComponents[1]);
        var seconds = parseInt(timeComponents[2]);

        if (isNaN(hours) || isNaN(minutes) || isNaN(seconds)) {
            return 'Invalid time format';
        }

        hours = (hours < 10) ? '0' + hours : hours;
        minutes = (minutes < 10) ? '0' + minutes : minutes;
        seconds = (seconds < 10) ? '0' + seconds : seconds;

        var formattedTime = hours + ':' + minutes + ':' + seconds;

        return formattedTime;
    }

    function stopLoading() {
        var divLoading = document.querySelector(".pLoading");
        divLoading.style.display = "none";
    }

    function startLoading() {
        var divLoading = document.querySelector(".pLoading");
        divLoading.style.display = "block";
    }
    startLoading();
</script>


<body>
    <!-- Sweet Alerts js -->
    <script src="<?= base_url('jar/html/default/') ?>assets/libs/sweetalert2/sweetalert2.min.js"></script>

    <!-- Sweet alert init js-->
    <script src="<?= base_url('jar/html/default/') ?>assets/js/pages/sweetalerts.init.js"></script>



    <div id="layout-wrapper">

        <header id="page-topbar">
            <div class="layout-width">
                <div class="navbar-header">
                    <div class="d-flex">
                        <!-- LOGO -->
                        <div class="navbar-brand-box horizontal-logo">
                            <a href="index.html" class="logo logo-dark">
                                <span class="logo-sm">
                                    <img src="<?= base_url('jar/html/default/') ?>assets/images/Yusen_Logistics_White.png" alt="" height="22">
                                </span>
                                <span class="logo-lg">
                                    <img src="<?= base_url('jar/html/default/') ?>assets/images/Yusen_Logistics_White.png" alt="" height="17">
                                </span>
                            </a>

                            <a href="index.html" class="logo logo-light">
                                <span class="logo-sm">
                                    <img src="<?= base_url('jar/html/default/') ?>assets/images/yusen-logo.png" alt="" height="22">
                                </span>
                                <span class="logo-lg">
                                    <img src="<?= base_url('jar/html/default/') ?>assets/images/Yusen_Logistics_White.png" alt="" height="17">
                                </span>
                            </a>
                        </div>

                        <button type="button" class="btn btn-sm px-3 fs-16 header-item vertical-menu-btn topnav-hamburger" id="topnav-hamburger-icon">
                            <span class="hamburger-icon">
                                <span></span>
                                <span></span>
                                <span></span>
                            </span>
                        </button>

                        <div class="ms-1 header-item d-sm-flex">
                            <h4 class="mb-sm-0"><?= $title ??  '' ?></h4>
                        </div>

                    </div>

                    <div class="d-flex align-items-center">

                        <div class="ms-1 header-item d-none d-sm-flex">
                            <span><strong id="clock"></strong></span>
                        </div>

                        <div class="ms-1 header-item d-none d-sm-flex">
                            <button type="button" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle" data-toggle="fullscreen">
                                <i class='bx bx-fullscreen fs-22'></i>
                            </button>
                        </div>

                        <div class="ms-1 header-item d-none d-sm-flex">
                            <button type="button" id="btnTheme" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle light-dark-mode">
                                <i class='bx bx-moon fs-22'></i>
                            </button>
                        </div>

                        <div class="dropdown ms-sm-3 header-item topbar-user">
                            <button type="button" class="btn" id="page-header-user-dropdown btnProfile" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="d-flex align-items-center">
                                    <img class="rounded-circle header-profile-user" src="<?= base_url('jar/html/default/') ?>assets/images/users/user-dummy-img.jpg" alt="Header Avatar">
                                    <span class="text-start ms-xl-2">
                                        <span class="d-block d-xl-inline-block ms-1 fw-medium user-name-text"> <?= $this->session->userdata('user_data')['fullname'] ?></span>
                                    </span>
                                </span>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <h6 class="dropdown-header">Welcome <?= $this->session->userdata('user_data')['username'] ?></h6>
                                <a id="logoutLink" class="dropdown-item" href="#"><i class="mdi mdi-logout text-muted fs-16 align-middle me-1"></i> <span class="align-middle" data-key="t-logout">Logout</span></a>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </header>

        <!-- removeNotificationModal -->
        <div id="removeNotificationModal" class="modal fade zoomIn" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="NotificationModalbtn-close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mt-2 text-center">
                            <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop" colors="primary:#f7b84b,secondary:#f06548" style="width:100px;height:100px"></lord-icon>
                            <div class="mt-4 pt-2 fs-15 mx-4 mx-sm-5">
                                <h4>Are you sure ?</h4>
                                <p class="text-muted mx-4 mb-0">Are you sure you want to remove this Notification ?</p>
                            </div>
                        </div>
                        <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                            <button type="button" class="btn w-sm btn-light" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn w-sm btn-danger" id="delete-notification">Yes, Delete It!</button>
                        </div>
                    </div>

                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

        <!-- ========== App Menu ========== -->
        <div class="app-menu navbar-menu">
            <!-- LOGO -->
            <div class="navbar-brand-box">
                <!-- Dark Logo-->
                <a href="#" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="<?= base_url('jar/html/default/') ?>assets/images/yusen-logo.png" alt="" height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="<?= base_url('jar/html/default/') ?>assets/images/yusen-logo.png" alt="" height="17">
                    </span>
                </a>
                <!-- Light Logo-->
                <a href="#" class="logo logo-light">
                    <span class="logo-sm">
                        <img style="border-radius: 10%;" src="<?= base_url('jar/html/default/') ?>assets/images/Yusen_Logistics_White.png" alt="" height="30">
                    </span>
                    <span class="logo-lg">
                        <div class="">
                            <img style="border-radius: 5%; margin-top: 5px;" src="<?= base_url('jar/html/default/') ?>assets/images/Yusen_Logistics_White.png" alt="" height="80">
                            <br>
                            <!-- <p style="margin-top:-30px !important; color: white; font-size: 25px; font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;"><strong>YAMVAS</strong></p> -->
                        </div>
                    </span>
                </a>
                <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover" id="vertical-hover">
                    <i class="ri-record-circle-line"></i>
                </button>
            </div>

            <div id="scrollbar">
                <div class="container-fluid">

                    <div id="two-column-menu">
                    </div>
                    <ul class="navbar-nav" id="navbar-nav">
                        <li class="menu-title"><span data-key="t-menu">Menu</span></li>
                        <li class="nav-item">
                            <a class="nav-link menu-link" href="<?= base_url('dashboard/index') ?>">
                                <i class="ri-dashboard-2-line"></i> <span data-key="t-dashboard">Dashboards</span>
                            </a>
                        </li>

                        <?php
                        foreach (parentMenu()->result() as $parent) {
                        ?>

                            <li class="nav-item">
                                <a class="nav-link menu-link" href="#<?= $parent->name; ?>" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="<?= $parent->name ?>">
                                    <i class="ri-pages-line"></i> <span data-key="t-pages"><?= $parent->name; ?></span>
                                </a>
                                <div class="collapse menu-dropdown show" id="<?= $parent->name ?>">
                                    <ul class="nav nav-sm flex-column">
                                        <?php
                                        foreach (child_menu($parent->id)->result() as $child) {
                                        ?>
                                            <li class="nav-item">
                                                <a href="<?= base_url($child->url) ?>" class="nav-link" data-key="t-starter"> <?= $child->menu_name ?> </a>
                                            </li>
                                        <?php
                                        }
                                        ?>
                                    </ul>
                                </div>
                            </li>

                        <?php
                        }
                        ?>
                        <li class="menu-title"><span data-key="t-menu"></span></li>
                        <li class="menu-title"><span data-key="t-menu"></span></li>
                        <li class="menu-title"><span data-key="t-menu"></span></li>
                        <li class="menu-title"><span data-key="t-menu"></span></li>
                        <li class="menu-title"><span data-key="t-menu"></span></li>
                        <li class="menu-title"><span data-key="t-menu"></span></li>
                    </ul>
                </div>
            </div>

            <div class="sidebar-background"></div>
        </div>
        <!-- Left Sidebar End -->
        <!-- Vertical Overlay-->
        <div class="vertical-overlay"></div>

        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">

            <div class="page-content">
                <div class="container-fluid">
<?php
// Include config file
require_once "../config.php";

// Define variables and initialize with empty values
$Cliente_id = $producto_id = $cantidad_productos = $Precio_total = $Fecha_venta = $Usuario_id = "";
$Cliente_id_err = $producto_id_err = $cantidad_productos_err = $Precio_total_err = $Fecha_venta_err = $Usuario_id_err = "";


$code = $name = $description = $color = $stock = $price = $image = "";
$code_err = $name_err = $description_err = $color_err = $stock_err = $price_err = $image_err = "";


// Check existence of id parameter before processing further
if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    // Get URL parameter
    $id =  trim($_GET["id"]);
    
    // Prepare a select statement
    $sql = "SELECT * FROM productos WHERE id = ?";
    if($stmt = mysqli_prepare($link, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "i", $param_id);
        
        // Set parameters
        $param_id = $id;
        
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);

            if(mysqli_num_rows($result) == 1){
                /* Fetch result row as an associative array. Since the result set
                contains only one row, we don't need to use while loop */
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                
                // Retrieve individual field value
                $code = $row["code"];
                $name = $row["name"];
                $description = $row["description"];
                $color = $row["color"];
                $stock = $row["stock"];
                $image= $row["image"];
                $price= $row["price"];
                

                $Cliente_id = $Cliente_id;
                $producto_id = $producto_id;
                $cantidad_productos = $cantidad_productos;
                $Precio_total = $Precio_total;
                $Fecha_venta = date("Y-m-d");
                $Usuario_id = 1;


            } else{
                // URL doesn't contain valid id. Redirect to error page
                header("location: error.php");
                exit();
            }
            
        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }
    }


    
    // Close statement
    mysqli_stmt_close($stmt);
    
    // Close connection
    mysqli_close($link);
}  else{
    // URL doesn't contain id parameter. Redirect to error page
    header("location: error.php");
    exit();
}

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate Cliente_id
    $input_Cliente_id = trim($_POST["Cliente_id"]);
    if (empty($input_Cliente_id)) {
        $Cliente_id_err = "Por favor ingrese el ID del Cliente.";
    } else {
        $Cliente_id = $input_Cliente_id;
    }

    // Validate producto_id
    $input_producto_id = trim($_POST["producto_id"]);
    if (empty($input_producto_id)) {
        $producto_id_err = "Por favor ingrese el ID del Producto.";
    } else {
        $producto_id = $input_producto_id;
    }

    // Validate cantidad_productos
    $input_cantidad_productos = trim($_POST["cantidad_productos"]);
    if (empty($input_cantidad_productos)) {
        $cantidad_productos_err = "Por favor ingrese la cantidad de productos.";
    } elseif (!ctype_digit($input_cantidad_productos)) {
        $cantidad_productos_err = "Por favor ingrese un valor numérico positivo.";
    } else {
        $cantidad_productos = $input_cantidad_productos;
    }

    // Validate Precio_total
    $input_Precio_total = trim($_POST["Precio_total"]);
    if (empty($input_Precio_total)) {
        $Precio_total_err = "Por favor ingrese el precio total.";
    } elseif (!is_numeric($input_Precio_total) || $input_Precio_total <= 0) {
        $Precio_total_err = "Por favor ingrese un valor numérico positivo para el precio total.";
    } else {
        $Precio_total = $input_Precio_total;
    }

    // Validate Fecha_venta
    $input_Fecha_venta = trim($_POST["Fecha_venta"]);
    if (empty($input_Fecha_venta)) {
        $Fecha_venta_err = "Por favor ingrese la fecha de la venta.";
    } else {
        $Fecha_venta = $input_Fecha_venta;
    }

    // Validate Usuario_id
    $input_Usuario_id = trim($_POST["Usuario_id"]);
    if (empty($input_Usuario_id)) {
        $Usuario_id_err = "Por favor ingrese el ID del Usuario.";
    } else {
        $Usuario_id = $input_Usuario_id;
    }

    // Check input errors before inserting in database
    if (empty($Cliente_id_err) && empty($producto_id_err) && empty($cantidad_productos_err) && empty($Precio_total_err) && empty($Fecha_venta_err) && empty($Usuario_id_err)) {
        // Prepare an insert statement
        $sql = "INSERT INTO ventas (Cliente_id, producto_id, cantidad_productos, Precio_total, Fecha_venta, Usuario_id) VALUES (?, ?, ?, ?, ?, ?)";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssisss", $param_Cliente_id, $param_producto_id, $param_cantidad_productos, $param_Precio_total, $param_Fecha_venta, $param_Usuario_id);

            // Set parameters
            $param_Cliente_id = $Cliente_id;
            $param_producto_id = $producto_id;
            $param_cantidad_productos = $cantidad_productos;
            $param_Precio_total = $Precio_total;
            $param_Fecha_venta = $Fecha_venta;
            $param_Usuario_id = $Usuario_id;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Records created successfully. Redirect to landing page
                header("location: ../index_ventas.php");
                exit();
            } else {
                echo "Algo salió mal. Por favor, inténtelo de nuevo más tarde.";
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }

    // Close connection
    mysqli_close($link);
}
?>







<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<!-- BEGIN: Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description"
        content="Vuexy admin is super flexible, powerful, clean &amp; modern responsive bootstrap 4 admin template with unlimited possibilities.">
    <meta name="keywords"
        content="admin template, Vuexy admin template, dashboard template, flat admin template, responsive admin template, web app">
    <meta name="author" content="PIXINVENT">
    <title> comprashop </title>
    <link rel="apple-touch-icon" href="../../app-assets/images/ico/apple-icon-120.png">
    <link rel="shortcut icon" type="image/x-icon" href="../../app-assets/images/ico/favicon.ico">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;1,400;1,500;1,600"
        rel="stylesheet">

    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="../../app-assets/vendors/css/vendors.min.css">
    <link rel="stylesheet" type="text/css" href="../../app-assets/vendors/css/forms/select/select2.min.css">
    <link rel="stylesheet" type="text/css" href="../../app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css">
    <!-- END: Vendor CSS-->

    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="../../app-assets/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="../../app-assets/css/bootstrap-extended.css">
    <link rel="stylesheet" type="text/css" href="../../app-assets/css/colors.css">
    <link rel="stylesheet" type="text/css" href="../../app-assets/css/components.css">
    <link rel="stylesheet" type="text/css" href="../../app-assets/css/themes/dark-layout.css">
    <link rel="stylesheet" type="text/css" href="../../app-assets/css/themes/bordered-layout.css">

    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="../../app-assets/css/core/menu/menu-types/vertical-menu.css">
    <link rel="stylesheet" type="text/css" href="../../app-assets/css/plugins/forms/pickers/form-flat-pickr.css">
    <link rel="stylesheet" type="text/css" href="../../app-assets/css/plugins/forms/form-validation.css">
    <link rel="stylesheet" type="text/css" href="../../app-assets/css/pages/app-user.css">
    <!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="../../assets/css/style.css">
    <!-- END: Custom CSS-->

</head>
<!-- END: Head-->

<!-- BEGIN: Body-->

<body class="vertical-layout vertical-menu-modern  navbar-floating footer-static  " data-open="click"
    data-menu="vertical-menu-modern" data-col="">

    <!-- BEGIN: Header-->
    <nav class="header-navbar navbar navbar-expand-lg align-items-center floating-nav navbar-light navbar-shadow">
        <div class="navbar-container d-flex content">
            <div class="bookmark-wrapper d-flex align-items-center">
                <ul class="nav navbar-nav d-xl-none">
                    <li class="nav-item"><a class="nav-link menu-toggle" href="javascript:void(0);"><i class="ficon"
                                data-feather="menu"></i></a></li>
                </ul>
                
            </div>
            <!-- ventas -->
            <ul class="nav navbar-nav align-items-center ml-auto">
                
                <li class="nav-item d-none d-lg-block"><a class="nav-link nav-link-style"><i class="ficon"
                            data-feather="moon"></i></a></li>
                <li class="nav-item nav-search"><a class="nav-link nav-link-search"><i class="ficon"
                            data-feather="search"></i></a>
                    <div class="search-input">
                        <div class="search-input-icon"><i data-feather="search"></i></div>
                        <input class="form-control input" type="text" placeholder="Explore Vuexy..." tabindex="-1"
                            data-search="search">
                        <div class="search-input-close"><i data-feather="x"></i></div>
                        <ul class="search-list search-list-main"></ul>
                    </div>
                </li>
                <li class="nav-item dropdown dropdown-cart mr-25"><a class="nav-link" href="javascript:void(0);"
                        data-toggle="dropdown"><i class="ficon" data-feather="shopping-cart"></i><span
                            class="badge badge-pill badge-primary badge-up cart-item-count">6</span></a>
                    <ul class="dropdown-menu dropdown-menu-media dropdown-menu-right">
                        <li class="dropdown-menu-header">
                            <div class="dropdown-header d-flex">
                                <h4 class="notification-title mb-0 mr-auto">My Cart</h4>
                                <div class="badge badge-pill badge-light-primary">4 Items</div>
                            </div>
                        </li>
                        <li class="scrollable-container media-list">
                            <div class="media align-items-center"><img class="d-block rounded mr-1"
                                    src="../../app-assets/images/pages/eCommerce/1.png" alt="donuts" width="62">
                                <div class="media-body"><i class="ficon cart-item-remove" data-feather="x"></i>
                                    <div class="media-heading">
                                        <h6 class="cart-item-title"><a class="text-body"
                                                href="app-ecommerce-details.html"> Apple watch 5</a></h6><small
                                            class="cart-item-by">By Apple</small>
                                    </div>
                                    <div class="cart-item-qty">
                                        <div class="input-group">
                                            <input class="touchspin-cart" type="number" value="1">
                                        </div>
                                    </div>
                                    <h5 class="cart-item-price">$374.90</h5>
                                </div>
                            </div>
                            <div class="media align-items-center"><img class="d-block rounded mr-1"
                                    src="../../app-assets/images/pages/eCommerce/7.png" alt="donuts" width="62">
                                <div class="media-body"><i class="ficon cart-item-remove" data-feather="x"></i>
                                    <div class="media-heading">
                                        <h6 class="cart-item-title"><a class="text-body"
                                                href="app-ecommerce-details.html"> Google Home Mini</a></h6><small
                                            class="cart-item-by">By Google</small>
                                    </div>
                                    <div class="cart-item-qty">
                                        <div class="input-group">
                                            <input class="touchspin-cart" type="number" value="3">
                                        </div>
                                    </div>
                                    <h5 class="cart-item-price">$129.40</h5>
                                </div>
                            </div>
                            <div class="media align-items-center"><img class="d-block rounded mr-1"
                                    src="../../app-assets/images/pages/eCommerce/2.png" alt="donuts" width="62">
                                <div class="media-body"><i class="ficon cart-item-remove" data-feather="x"></i>
                                    <div class="media-heading">
                                        <h6 class="cart-item-title"><a class="text-body"
                                                href="app-ecommerce-details.html"> iPhone 11 Pro</a></h6><small
                                            class="cart-item-by">By Apple</small>
                                    </div>
                                    <div class="cart-item-qty">
                                        <div class="input-group">
                                            <input class="touchspin-cart" type="number" value="2">
                                        </div>
                                    </div>
                                    <h5 class="cart-item-price">$699.00</h5>
                                </div>
                            </div>
                            <div class="media align-items-center"><img class="d-block rounded mr-1"
                                    src="../../app-assets/images/pages/eCommerce/3.png" alt="donuts" width="62">
                                <div class="media-body"><i class="ficon cart-item-remove" data-feather="x"></i>
                                    <div class="media-heading">
                                        <h6 class="cart-item-title"><a class="text-body"
                                                href="app-ecommerce-details.html"> iMac Pro</a></h6><small
                                            class="cart-item-by">By Apple</small>
                                    </div>
                                    <div class="cart-item-qty">
                                        <div class="input-group">
                                            <input class="touchspin-cart" type="number" value="1">
                                        </div>
                                    </div>
                                    <h5 class="cart-item-price">$4,999.00</h5>
                                </div>
                            </div>
                            <div class="media align-items-center"><img class="d-block rounded mr-1"
                                    src="../../app-assets/images/pages/eCommerce/5.png" alt="donuts" width="62">
                                <div class="media-body"><i class="ficon cart-item-remove" data-feather="x"></i>
                                    <div class="media-heading">
                                        <h6 class="cart-item-title"><a class="text-body"
                                                href="app-ecommerce-details.html"> MacBook Pro</a></h6><small
                                            class="cart-item-by">By Apple</small>
                                    </div>
                                    <div class="cart-item-qty">
                                        <div class="input-group">
                                            <input class="touchspin-cart" type="number" value="1">
                                        </div>
                                    </div>
                                    <h5 class="cart-item-price">$2,999.00</h5>
                                </div>
                            </div>
                        </li>
                        <li class="dropdown-menu-footer">
                            <div class="d-flex justify-content-between mb-1">
                                <h6 class="font-weight-bolder mb-0">Total:</h6>
                                <h6 class="text-primary font-weight-bolder mb-0">$10,999.00</h6>
                            </div><a class="btn btn-primary btn-block" href="app-ecommerce-checkout.html">Checkout</a>
                        </li>
                    </ul>
                </li>
                
                <li class="nav-item dropdown dropdown-user"><a class="nav-link dropdown-toggle dropdown-user-link"
                        id="dropdown-user" href="javascript:void(0);" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                        <div class="user-nav d-sm-flex d-none"><span class="user-name font-weight-bolder">John
                                Doe</span><span class="user-status">Admin</span></div><span class="avatar"><img
                                class="round" src="../../app-assets/images/portrait/small/avatar-s-11.jpg" alt="avatar"
                                height="40" width="40"><span class="avatar-status-online"></span></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown-user"><a
                            class="dropdown-item" href="page-profile.html"><i class="mr-50" data-feather="user"></i>
                            Profile</a><a class="dropdown-item" href="app-email.html"><i class="mr-50"
                                data-feather="mail"></i> Inbox</a><a class="dropdown-item" href="app-todo.html"><i
                                class="mr-50" data-feather="check-square"></i> Task</a><a class="dropdown-item"
                            href="app-chat.html"><i class="mr-50" data-feather="message-square"></i> Chats</a>
                        <div class="dropdown-divider"></div><a class="dropdown-item"
                            href="page-account-settings.html"><i class="mr-50" data-feather="settings"></i>
                            Settings</a><a class="dropdown-item" href="page-pricing.html"><i class="mr-50"
                                data-feather="credit-card"></i> Pricing</a><a class="dropdown-item"
                            href="page-faq.html"><i class="mr-50" data-feather="help-circle"></i> FAQ</a><a
                            class="dropdown-item" href="page-auth-login-v2.html"><i class="mr-50"
                                data-feather="power"></i> Logout</a>
                    </div>
                </li>
            </ul>
            <!-- ventas fin -->
        </div>
    </nav>
    <ul class="main-search-list-defaultlist d-none">
        <li class="d-flex align-items-center"><a href="javascript:void(0);">
                <h6 class="section-label mt-75 mb-0">Files</h6>
            </a></li>
        <li class="auto-suggestion"><a class="d-flex align-items-center justify-content-between w-100"
                href="app-file-manager.html">
                <div class="d-flex">
                    <div class="mr-75"><img src="../../app-assets/images/icons/xls.png" alt="png" height="32"></div>
                    <div class="search-data">
                        <p class="search-data-title mb-0">Two new item submitted</p><small class="text-muted">Marketing
                            Manager</small>
                    </div>
                </div><small class="search-data-size mr-50 text-muted">&apos;17kb</small>
            </a></li>
        <li class="auto-suggestion"><a class="d-flex align-items-center justify-content-between w-100"
                href="app-file-manager.html">
                <div class="d-flex">
                    <div class="mr-75"><img src="../../app-assets/images/icons/jpg.png" alt="png" height="32"></div>
                    <div class="search-data">
                        <p class="search-data-title mb-0">52 JPG file Generated</p><small class="text-muted">FontEnd
                            Developer</small>
                    </div>
                </div><small class="search-data-size mr-50 text-muted">&apos;11kb</small>
            </a></li>
        <li class="auto-suggestion"><a class="d-flex align-items-center justify-content-between w-100"
                href="app-file-manager.html">
                <div class="d-flex">
                    <div class="mr-75"><img src="../../app-assets/images/icons/pdf.png" alt="png" height="32"></div>
                    <div class="search-data">
                        <p class="search-data-title mb-0">25 PDF File Uploaded</p><small class="text-muted">Digital
                            Marketing Manager</small>
                    </div>
                </div><small class="search-data-size mr-50 text-muted">&apos;150kb</small>
            </a></li>
        <li class="auto-suggestion"><a class="d-flex align-items-center justify-content-between w-100"
                href="app-file-manager.html">
                <div class="d-flex">
                    <div class="mr-75"><img src="../../app-assets/images/icons/doc.png" alt="png" height="32"></div>
                    <div class="search-data">
                        <p class="search-data-title mb-0">Anna_Strong.doc</p><small class="text-muted">Web
                            Designer</small>
                    </div>
                </div><small class="search-data-size mr-50 text-muted">&apos;256kb</small>
            </a></li>
        <li class="d-flex align-items-center"><a href="javascript:void(0);">
                <h6 class="section-label mt-75 mb-0">Members</h6>
            </a></li>
        <li class="auto-suggestion"><a class="d-flex align-items-center justify-content-between py-50 w-100"
                href="app-user-view.html">
                <div class="d-flex align-items-center">
                    <div class="avatar mr-75"><img src="../../app-assets/images/portrait/small/avatar-s-8.jpg" alt="png"
                            height="32"></div>
                    <div class="search-data">
                        <p class="search-data-title mb-0">John Doe</p><small class="text-muted">UI designer</small>
                    </div>
                </div>
            </a>
        </li>
        <li class="auto-suggestion"><a class="d-flex align-items-center justify-content-between py-50 w-100"
                href="app-user-view.html">
                <div class="d-flex align-items-center">
                    <div class="avatar mr-75"><img src="../../app-assets/images/portrait/small/avatar-s-1.jpg" alt="png"
                            height="32"></div>
                    <div class="search-data">
                        <p class="search-data-title mb-0">Michal Clark</p><small class="text-muted">FontEnd
                            Developer</small>
                    </div>
                </div>
            </a></li>
        <li class="auto-suggestion"><a class="d-flex align-items-center justify-content-between py-50 w-100"
                href="app-user-view.html">
                <div class="d-flex align-items-center">
                    <div class="avatar mr-75"><img src="../../app-assets/images/portrait/small/avatar-s-14.jpg"
                            alt="png" height="32"></div>
                    <div class="search-data">
                        <p class="search-data-title mb-0">Milena Gibson</p><small class="text-muted">Digital Marketing
                            Manager</small>
                    </div>
                </div>
            </a></li>
        <li class="auto-suggestion"><a class="d-flex align-items-center justify-content-between py-50 w-100"
                href="app-user-view.html">
                <div class="d-flex align-items-center">
                    <div class="avatar mr-75"><img src="../../app-assets/images/portrait/small/avatar-s-6.jpg" alt="png"
                            height="32"></div>
                    <div class="search-data">
                        <p class="search-data-title mb-0">Anna Strong</p><small class="text-muted">Web Designer</small>
                    </div>
                </div>
            </a></li>
    </ul>
    <ul class="main-search-list-defaultlist-other-list d-none">
        <li class="auto-suggestion justify-content-between"><a
                class="d-flex align-items-center justify-content-between w-100 py-50">
                <div class="d-flex justify-content-start"><span class="mr-75"
                        data-feather="alert-circle"></span><span>No results found.</span></div>
            </a></li>
    </ul>
    <!-- END: Header-->


    <!-- BEGIN: Main Menu-->
    <div class="main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true">
        <div class="navbar-header">
            <ul class="nav navbar-nav flex-row">
                <li class="nav-item mr-auto"><a class="navbar-brand"
                        href="../../html/ltr/vertical-menu-template/index.html"><span class="brand-logo">
                            <svg viewbox="0 0 139 95" version="1.1" xmlns="http://www.w3.org/2000/svg"
                                xmlns:xlink="http://www.w3.org/1999/xlink" height="24">
                                <defs>
                                    <lineargradient id="linearGradient-1" x1="100%" y1="10.5120544%" x2="50%"
                                        y2="89.4879456%">
                                        <stop stop-color="#000000" offset="0%"></stop>
                                        <stop stop-color="#FFFFFF" offset="100%"></stop>
                                    </lineargradient>
                                    <lineargradient id="linearGradient-2" x1="64.0437835%" y1="46.3276743%"
                                        x2="37.373316%" y2="100%">
                                        <stop stop-color="#EEEEEE" stop-opacity="0" offset="0%"></stop>
                                        <stop stop-color="#FFFFFF" offset="100%"></stop>
                                    </lineargradient>
                                </defs>
                                <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <g id="Artboard" transform="translate(-400.000000, -178.000000)">
                                        <g id="Group" transform="translate(400.000000, 178.000000)">
                                            <path class="text-primary" id="Path"
                                                d="M-5.68434189e-14,2.84217094e-14 L39.1816085,2.84217094e-14 L69.3453773,32.2519224 L101.428699,2.84217094e-14 L138.784583,2.84217094e-14 L138.784199,29.8015838 C137.958931,37.3510206 135.784352,42.5567762 132.260463,45.4188507 C128.736573,48.2809251 112.33867,64.5239941 83.0667527,94.1480575 L56.2750821,94.1480575 L6.71554594,44.4188507 C2.46876683,39.9813776 0.345377275,35.1089553 0.345377275,29.8015838 C0.345377275,24.4942122 0.230251516,14.560351 -5.68434189e-14,2.84217094e-14 Z"
                                                style="fill:currentColor"></path>
                                            <path id="Path1"
                                                d="M69.3453773,32.2519224 L101.428699,1.42108547e-14 L138.784583,1.42108547e-14 L138.784199,29.8015838 C137.958931,37.3510206 135.784352,42.5567762 132.260463,45.4188507 C128.736573,48.2809251 112.33867,64.5239941 83.0667527,94.1480575 L56.2750821,94.1480575 L32.8435758,70.5039241 L69.3453773,32.2519224 Z"
                                                fill="url(#linearGradient-1)" opacity="0.2"></path>
                                            <polygon id="Path-2" fill="#000000" opacity="0.049999997"
                                                points="69.3922914 32.4202615 32.8435758 70.5039241 54.0490008 16.1851325">
                                            </polygon>
                                            <polygon id="Path-21" fill="#000000" opacity="0.099999994"
                                                points="69.3922914 32.4202615 32.8435758 70.5039241 58.3683556 20.7402338">
                                            </polygon>
                                            <polygon id="Path-3" fill="url(#linearGradient-2)" opacity="0.099999994"
                                                points="101.428699 0 83.0667527 94.1480575 130.378721 47.0740288">
                                            </polygon>
                                        </g>
                                    </g>
                                </g>
                            </svg></span>
                        <h2 class="brand-text">CompraShop</h2>
                    </a></li>
                <li class="nav-item nav-toggle"><a class="nav-link modern-nav-toggle pr-0" data-toggle="collapse"><i
                            class="d-block d-xl-none text-primary toggle-icon font-medium-4" data-feather="x"></i><i
                            class="d-none d-xl-block collapse-toggle-icon font-medium-4  text-primary"
                            data-feather="disc" data-ticon="disc"></i></a></li>
            </ul>
        </div>
        <div class="shadow-bottom"></div>
        <div class="main-menu-content">
            <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
                
                <li class=" navigation-header"><span data-i18n="Apps &amp; Pages">Apps &amp; Pages</span><i
                        data-feather="more-horizontal"></i>
                </li>



               <!-- datos de los componentes  -->

               <li class=" nav-item"><a class="d-flex align-items-center" href="componentes/usuario/app-user.html"><i
                            data-feather="user"></i><span class="menu-title text-truncate"
                            data-i18n="User">Users</span></a>

                </li>
                <li class=" nav-item"><a class="d-flex align-items-center" href="componentes/clientes/app-cliente.html"><i
                            data-feather="truck"></i><span class="menu-title text-truncate"
                            data-i18n="User">clientes</span></a>

                </li>

                <li class=" nav-item"><a class="d-flex align-items-center" href="componentes/producto/app-producto.php"><i
                            data-feather="shopping-bag"></i><span class="menu-title text-truncate"
                            data-i18n="User">productos</span></a>
                </li>

                <li class=" nav-item"><a class="d-flex align-items-center" href="componentes/ventas/app-venta.php"><i
                            data-feather="shopping-cart"></i><span class="menu-title text-truncate"
                            data-i18n="User">ventas</span></a>
                </li>



            </ul>
        </div>
    </div>
    <!-- END: Main Menu-->

    <!-- BEGIN: Content-->
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
            </div>
            <div class="content-body">
                <!-- users edit start -->
                <section class="app-user-edit">
                    <div class="card">
                        <div class="card-body">
                            <ul class="nav nav-pills" role="tablist">
                                <li class="nav-item">
                                    
                                    <?php echo
                                        "<a class='nav-link d-flex align-items-center'
                                            href='read.php?id=". $row['id'] ."'
                                            aria-selected='false'>
                                            <i data-feather='edit'></i><span class='d-none d-sm-block'>editar</span>
                                        </a>";
                                    ?>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link d-flex align-items-center active" id="account-tab"
                                        data-toggle="tab" href="#account" 
                                        aria-controls="account" role="tab"
                                        aria-selected="false">
                                        <i data-feather="edit"></i><span class="d-none d-sm-block">agregar</span>
                                    </a>

                                </li>
                                <li class="nav-item">
                                    <?php echo
                                        "<a class='nav-link d-flex align-items-center' id='social-tab'
                                        href='delete.php?id=". $row['id'] ."' 
                                        aria-selected='false'>
                                        <i data-feather='delete'></i><span class='d-none d-sm-block'>eliminar</span>
                                    </a>";
                                    ?>
                                </li>
                            </ul>




                    
                            <!-- contenido pagina 1 ------------------------------------------- -->
                            <div class="tab-content">
                                <!-- Account Tab starts -->
                                <div class="tab-pane active" id="account" aria-labelledby="account-tab" role="tabpanel">
                                    <!-- users edit media object start -->
                                    


                    
                    <form class="form-validate" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype='multipart/form-data'>
                      <div class="row">
                        <div class="col-md-2 <?php echo (!empty($Cliente_id_err)) ? 'has-error' : ''; ?>">
                            <label>cliente id</label>
                            <input type="text" name="Cliente_id" class="form-control" value="<?php echo $code; ?>">
                            <span class="help-block"><?php echo $Cliente_id_err;?></span>
                        </div>
                        <div class="col-md-2 <?php echo (!empty($producto_id_err)) ? 'has-error' : ''; ?>">
                            <label>producto_id</label>
                            <input type="text" name="producto_id" class="form-control" value="<?php echo $id; ?>">
                            <span class="help-block"><?php echo $producto_id_err;?></span>
                        </div>
                        <div class="col-md-2 <?php echo (!empty($cantidad_productos_err)) ? 'has-error' : ''; ?>">
                            <label>cantidad_productos</label>
                            <input type="text" name="cantidad_productos" class="form-control" value="<?php echo $stock; ?>">
                            <span class="help-block"><?php echo $cantidad_productos_err;?></span>
                        </div>
                        <div class="col-md-2 <?php echo (!empty($price_err)) ? 'has-error' : ''; ?>">
                            <label>Precio_total</label>
                            <input type="text" name="price" class="form-control" value="<?php echo $price; ?>">
                            <span class="help-block"><?php echo $price_err;?></span>
                        </div>
                        <div class="col-md-2 <?php echo (!empty($Fecha_venta_err)) ? 'has-error' : ''; ?>">
                            <label>fecha</label>
                            <input type="text" name="Fecha_venta" class="form-control" value="<?php echo $Fecha_venta; ?>">
                            <span class="help-block"><?php echo $Fecha_venta_err;?></span>
                        </div>
                        
                        <div class="col-md-6 <?php echo (!empty($Usuario_id_err)) ? 'has-error' : ''; ?>">
                            <label>Usuario_id</label>
                            <input type="text" name="Usuario_id" class="form-control" value="<?php echo $Usuario_id; ?>">
                            <span class="help-block"><?php echo $Usuario_id_err;?></span>
                        </div>


                        
                        <div class="col-md-12">
        
                        <br>
                        <div class="media mb-2">
                            
                            <?php
                                if (!empty($image)) {
                                    echo '<img src="../producto/' . $image . '" class="user-avatar users-avatar-shadow rounded mr-2 my-25 cursor-pointer" alt="Uploaded Image"
                                        width="250">';
                                }
                            ?>

                                        <div class="media-body mt-50">
                                            <div class="col-12 d-flex mt-1 px-0">
                                                
                                                <label class="btn btn-secundary mr-75 mb-0" for="change-picture">
                                                    <span class="d-none d-sm-block">cambiar imagen</span>
                                                    <input class="form-control" type="file" id="change-picture" hidden
                                                    name='image'      accept="image/png, image/jpeg, image/jpg" />
                                                                        <span class="d-block d-sm-none">
                                                                            <i class="mr-0" data-feather="edit"></i>
                                                                        </span>
                                                </label>
                                                <span class="help-block"><?php echo $image_err;?></span>
                                            </div>
                                        </div>
                                    </div>



                        </div>

                        

                        <div class="col-12 d-flex flex-sm-row flex-column mt-2">
                            
                        <a href="index_ventas.php" class="btn btn-default">Cancelar</a>
                            <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                            <input type="submit" class="btn btn-primary" value="Submit">
                            <a href="app-producto.php" class="btn btn-default">Cancelar</a>

                            
                        </div>

                      </div>
                    </form>
                   
                                </div>
                                <!-- Account Tab ends -->
                               
                                

                                <br><br>


                            </div>
                        </div>
                    </div>
                </section>
                <!-- users edit ends -->

            </div>
        </div>




    </div>
    <!-- END: Content-->

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    <!-- BEGIN: Footer-->
    <footer class="footer footer-static footer-light">
        <p class="clearfix mb-0"><span class="float-md-left d-block d-md-inline-block mt-25">COPYRIGHT &copy; 2020<a
                    class="ml-25" href="https://1.envato.market/pixinvent_portfolio" target="_blank">Pixinvent</a><span
                    class="d-none d-sm-inline-block">, All rights Reserved</span></span><span
                class="float-md-right d-none d-md-block">Hand-crafted & Made with<i data-feather="heart"></i></span></p>
    </footer>
    <button class="btn btn-primary btn-icon scroll-top" type="button"><i data-feather="arrow-up"></i></button>
    <!-- END: Footer-->


    <!-- BEGIN: Vendor JS-->
    <script src="../../app-assets/vendors/js/vendors.min.js"></script>
    <!-- BEGIN Vendor JS-->

    <!-- BEGIN: Page Vendor JS-->
    <script src="../../app-assets/vendors/js/forms/select/select2.full.min.js"></script>
    <script src="../../app-assets/vendors/js/forms/validation/jquery.validate.min.js"></script>
    <script src="../../app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js"></script>
    <!-- END: Page Vendor JS-->

    <!-- BEGIN: Theme JS-->
    <script src="../../app-assets/js/core/app-menu.js"></script>
    <script src="../../app-assets/js/core/app.js"></script>
    <!-- END: Theme JS-->

    <!-- BEGIN: Page JS-->
    <script src="../../app-assets/js/scripts/pages/app-user-edit.js"></script>
    <script src="../../app-assets/js/scripts/components/components-navs.js"></script>
    <!-- END: Page JS-->

    <script>
        $(window).on('load', function () {
            if (feather) {
                feather.replace({
                    width: 14,
                    height: 14
                });
            }
        })
    </script>
</body>
<!-- END: Body-->

</html>

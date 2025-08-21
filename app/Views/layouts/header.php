<?php
/**
 * Header layout for all pages in the application
 */
?>
<!DOCTYPE html>
<html lang="<?= service('request')->getLocale() ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? lang('App.beaconstat') ?> | <?= lang('App.ham_beacon_database') ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="<?= base_url('assets/css/bootstrap.min.css') ?>" rel="stylesheet">
        
    <!-- Favicon -->
    <link rel="shortcut icon" href="<?= base_url('favicon.ico') ?>" type="image/x-icon">
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="<?= base_url() ?>">
                <img src="<?= base_url('assets/images/logo.png') ?>" alt="BeaconStat Logo" height="30" class="me-2">
                <?= lang('App.beaconstat') ?>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarMain">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link <?= current_url() == base_url() ? 'active' : '' ?>" href="<?= base_url() ?>"><?= lang('App.home') ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('reports') ?>"><?= lang('App.reports') ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('map') ?>"><?= lang('App.map') ?></a>
                    </li>
                </ul>
            </div>
            <!-- Language Switcher -->
            <div class="dropdown">
                <button class="btn btn-sm btn-light dropdown-toggle" type="button" id="languageDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <?php 
                        $locale = session()->get('locale') ?? 'it';
                        switch($locale) {
                            case 'it':
                                echo 'Italiano';
                                break;
                            case 'fr':
                                echo 'Français';
                                break;
                            case 'en':
                                echo 'English';
                                break;
                        }
                    ?>
                </button>
                <ul class="dropdown-menu" aria-labelledby="languageDropdown">
                    <li><a class="dropdown-item" href="<?= base_url('language/switch/it') ?>">Italiano</a></li>
                    <li><a class="dropdown-item" href="<?= base_url('language/switch/en') ?>">English</a></li>
                    <li><a class="dropdown-item" href="<?= base_url('language/switch/fr') ?>">Français</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    <?php if (session()->has('message')): ?>
        <div class="container mt-3">
            <div class="alert alert-<?= session('message_type') ?? 'info' ?> alert-dismissible fade show">
                <?= session('message') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    <?php endif; ?>

    <!-- Main Content Start -->
    <div class="main-content">
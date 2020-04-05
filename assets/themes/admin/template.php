<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Admin Template
 */
?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo base_url(); ?>/favicon.ico?v=<?php echo $this->settings->site_version; ?>">
    <link rel="icon" type="image/x-icon" href="<?php echo base_url(); ?>/favicon.ico?v=<?php echo $this->settings->site_version; ?>">
    <title><?php echo $this->settings->site_name; ?> | <?php echo $page_title; ?></title>

    <?php // CSS files ?>
    <?php if (isset($css_files) && is_array($css_files)): ?>
        <?php foreach ($css_files as $css): ?>
            <?php if (!is_null($css)): ?>
                <?php $separator = (strstr($css, '?')) ? '&' : '?';?>
                <link rel="stylesheet" href="<?php echo $css; ?><?php echo $separator; ?>v=<?php echo $this->settings->site_version; ?>"><?php echo "\n"; ?>
            <?php endif;?>
        <?php endforeach;?>
    <?php endif;?>

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>

    <?php // Fixed navbar ?>
    <nav class="navbar navbar-inverse navbar-fixed-top">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only"><?php echo lang('core button toggle_nav'); ?></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="<?php echo base_url(); ?>"><?php echo $this->settings->site_name; ?></a>
            </div>
            <div id="navbar" class="navbar-collapse collapse">
                <?php // Nav bar left ?>
                <ul class="nav navbar-nav">
                    <li class="<?php echo (uri_string() == 'admin' or uri_string() == 'admin/dashboard') ? 'active' : ''; ?>"><a href="<?php echo base_url('/admin'); ?>"><?php echo lang('admin button dashboard'); ?></a></li>
<!--
                    <li class="dropdown<?php echo (strstr(uri_string(), 'admin/users')) ? ' active' : ''; ?>">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><?php echo lang('admin button users'); ?> <span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <li class="<?php echo (uri_string() == 'admin/users') ? 'active' : ''; ?>"><a href="<?php echo base_url('/admin/users'); ?>"><?php echo lang('admin button users_list'); ?></a></li>
                            <li class="<?php echo (uri_string() == 'admin/users/add') ? 'active' : ''; ?>"><a href="<?php echo base_url('/admin/users/add'); ?>"><?php echo lang('admin button users_add'); ?></a></li>
                        </ul>
                    </li>
-->
                    <li class="dropdown<?php echo (strstr(uri_string(), 'admin/master')) ? ' active' : ''; ?>">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><?php echo lang('admin button master'); ?> <span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <li class="<?php echo (uri_string() == 'admin/master/users') ? 'active' : ''; ?>"><a href="<?php echo base_url('/admin/master/users'); ?>"><?php echo lang('admin button master_users'); ?></a></li>
                            <li class="<?php echo (uri_string() == 'admin/master/clients') ? 'active' : ''; ?>"><a href="<?php echo base_url('/admin/master/clients'); ?>"><?php echo lang('admin button master_clients'); ?></a></li>
                            <li class="<?php echo (uri_string() == 'admin/master/contacts') ? 'active' : ''; ?>"><a href="<?php echo base_url('/admin/master/contacts'); ?>"><?php echo lang('admin button master_contacts'); ?></a></li>
                            <!--<li class="<?php echo (uri_string() == 'admin/master/projects') ? 'active' : ''; ?>"><a href="<?php echo base_url('/admin/master/projects'); ?>"><?php echo lang('admin button master_projects'); ?></a></li> -->
                            <li class="<?php echo (uri_string() == 'admin/master/applications') ? 'active' : ''; ?>"><a href="<?php echo base_url('/admin/master/applications'); ?>"><?php echo lang('admin button master_applications'); ?></a></li>
                            <li class="<?php echo (uri_string() == 'admin/master/servers') ? 'active' : ''; ?>"><a href="<?php echo base_url('/admin/master/servers'); ?>"><?php echo lang('admin button master_servers'); ?></a></li>
                        </ul>
                    </li>
                    <li class="dropdown<?php echo (strstr(uri_string(), 'admin/work_packages')) ? ' active' : ''; ?>">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><?php echo lang('admin button work_packages'); ?> <span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <li class="<?php echo (uri_string() == 'admin/work_packages/projects') ? 'active' : ''; ?>"><a href="<?php echo base_url('/admin/work_packages/projects'); ?>"><?php echo lang('admin button master_projects'); ?></a></li>
                        </ul>
                    </li>
                    <li class="<?php echo (uri_string() == 'admin/contact') ? 'active' : ''; ?>"><a href="<?php echo base_url('/admin/contact'); ?>"><?php echo lang('admin button messages'); ?></a></li>
                    <li class="<?php echo (uri_string() == 'admin/settings') ? 'active' : ''; ?>"><a href="<?php echo base_url('/admin/settings'); ?>"><?php echo lang('admin button settings'); ?></a></li>
                </ul>
                <?php // Nav bar right ?>
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="<?php echo base_url('logout'); ?>"><?php echo lang('core button logout'); ?></a></li>
                    <li>
                        <span class="dropdown">
                            <button id="session-language" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn btn-default">
                                <i class="fa fa-language"></i>
                                <span class="caret"></span>
                            </button>
                            <ul id="session-language-dropdown" class="dropdown-menu" role="menu" aria-labelledby="session-language">
                                <?php foreach ($this->languages as $key => $name): ?>
                                    <li>
                                        <a href="#" rel="<?php echo $key; ?>">
                                            <?php if ($key == $this->session->language): ?>
                                                <i class="fa fa-check selected-session-language"></i>
                                            <?php endif;?>
                                            <?php echo $name; ?>
                                        </a>
                                    </li>
                                <?php endforeach;?>
                            </ul>
                        </span>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <?php // Main body ?>
    <div class="container-fluid theme-showcase" role="main">

        <?php // Page title ?>
        <div class="page-header"></div>

        <?php // System messages ?>
        <?php if ($this->session->flashdata('message')): ?>
            <div class="alert alert-success alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <?php echo $this->session->flashdata('message'); ?>
            </div>
        <?php elseif ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <?php echo $this->session->flashdata('error'); ?>
            </div>
        <?php elseif (validation_errors()): ?>
            <div class="alert alert-danger alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <?php echo validation_errors(); ?>
            </div>
        <?php elseif ($this->error): ?>
            <div class="alert alert-danger alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <?php echo $this->error; ?>
            </div>
        <?php endif;?>

        <?php // Main content ?>
        <?php echo $content; ?>

    </div>

    <?php // Footer ?>
    <footer class="sticky-footer">
        <div class="container-fluid">
            <p class="text-muted hidden-xs hidden-sm">
                <?php echo lang('core text page_rendered'); ?>
                | PHP v<?php echo phpversion(); ?>
                | MySQL v<?php echo mysqli_get_client_version(); ?>
                | CodeIgniter v<?php echo CI_VERSION; ?>
                | <?php echo $this->settings->site_name; ?> v<?php echo $this->settings->site_version; ?>
            </p>
            <p class="text-muted hidden-md hidden-lg">
                <?php echo $this->settings->site_name; ?> v<?php echo $this->settings->site_version; ?>
            </p>
        </div>
    </footer>

    <?php // Javascript files ?>
    <?php if (isset($js_files) && is_array($js_files)): ?>
        <?php foreach ($js_files as $js): ?>
            <?php if (!is_null($js)): ?>
                <?php $separator = (strstr($js, '?')) ? '&' : '?';?>
                <?php echo "\n"; ?><script type="text/javascript" src="<?php echo $js; ?><?php echo $separator; ?>v=<?php echo $this->settings->site_version; ?>"></script><?php echo "\n"; ?>
            <?php endif;?>
        <?php endforeach;?>
    <?php endif;?>
    <?php if (isset($js_files_i18n) && is_array($js_files_i18n)): ?>
        <?php foreach ($js_files_i18n as $js): ?>
            <?php if (!is_null($js)): ?>
                <?php echo "\n"; ?><script type="text/javascript"><?php echo "\n" . $js . "\n"; ?></script><?php echo "\n"; ?>
            <?php endif;?>
        <?php endforeach;?>
    <?php endif;?>

</body>
</html>

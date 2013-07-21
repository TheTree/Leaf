<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?= $template['title']; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="description" content="<?php if (isset($meta['description'])): ?><?= $meta['description']; ?><? else: ?>Halo 4 Stat Tracking provided by LeafApp, including Halo 4 CSR <? endif; ?>">
    <?= link_tag('assets/css/bootstrap/bootstrap.min.css'); ?>
    <?= link_tag('assets/css/bootstrap/bootstrap-responsive.min.css'); ?>
    <?= link_tag('assets/css/bootstrap/bootswatch.css'); ?>
    <?= link_tag('assets/css/default.css'); ?>
    <?= link_tag('assets/css/csr.css'); ?>
    <script>
        (function(b,o,i,l,e,r){b.GoogleAnalyticsObject=l;b[l]||(b[l]=
            function(){(b[l].q=b[l].q||[]).push(arguments)});b[l].l=+new Date;
            e=o.createElement(i);r=o.getElementsByTagName(i)[0];
            e.src='//www.google-analytics.com/analytics.js';
            r.parentNode.insertBefore(e,r)}(window,document,'script','ga'));
        ga('create','UA-3737795-14');ga('send','pageview');
    </script>
    <?= $template['_partials']['global_js_vars']; ?>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <!--[if lt IE 9]>
    <script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
    <script>
        document.createElement("header" );
        document.createElement("footer" );
        document.createElement("section");
        document.createElement("aside"  );
        document.createElement("nav"    );
        document.createElement("article");
        document.createElement("hgroup" );
        document.createElement("time"   );
    </script>
    <![endif]-->
    <script src="<?= base_url('assets/js/csrf_autoload.js'); ?>" type="text/javascript"></script>
    <script src="<?= base_url('assets/js/bootstrap/bootstrap.min.js'); ?>" type="text/javascript"></script>
    <script src="<?= base_url('assets/js/bootstrap/jquery.smooth-scroll.min.js'); ?>" type="text/javascript"></script>
    <script src="<?= base_url('assets/js/bootstrap/bootswatch.js'); ?>" type="text/javascript"></script>
</head>
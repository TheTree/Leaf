<head>
    <meta charset="utf-8">
    <title><?= $template['title']; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="description" content="<?php if (isset($meta['description'])): ?><?= $meta['description']; ?><? else: ?>Halo 4 Stat Tracking provided by LeafApp, including Halo 4 CSR <? endif; ?>">
    <?= link_tag('assets/css/bootstrap/bootstrap.min.css'); ?>
    <?= link_tag('assets/css/bootstrap/bootstrap-responsive.min.css'); ?>
    <?= link_tag('assets/css/bootstrap/bootswatch.css'); ?>
    <?= link_tag('assets/css/default.css'); ?>
    <?= link_tag('assets/css/csr.css'); ?>
    <script type="text/javascript">
        var _gaq = _gaq || [];
        _gaq.push(['_setAccount', 'UA-3737795-12']);
        _gaq.push(['_setDomainName', 'leafapp.co']);
        _gaq.push(['_trackPageview']);

        (function() {
            var ga = document.createElement('script');
            ga.type = 'text/javascript';
            ga.async = true;
            ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
            var s = document.getElementsByTagName('script')[0];
            s.parentNode.insertBefore(ga, s);
        })();
    </script>
    <?= $template['_partials']['global_js_vars']; ?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <script src="<?= base_url('assets/js/csrf_autoload.js'); ?>" type="text/javascript"></script>
    <script src="<?= base_url('assets/js/bootstrap/bootstrap.min.js'); ?>" type="text/javascript"></script>
    <script src="<?= base_url('assets/js/bootstrap/jquery.smooth-scroll.min.js'); ?>" type="text/javascript"></script>
    <script src="<?= base_url('assets/js/bootstrap/bootswatch.js'); ?>" type="text/javascript"></script>
</head>
<?= doctype() ?>
<html lang="en">
    <?= $template['_partials']['head_header']; ?>
    <body>
        <div class="navbar navbar-inverse navbar-fixed-top">
            <div class="navbar-inner">
                <div class="container">
                    <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </a>
                    <a class="brand" href="<?= base_url(); ?>">Halo 4 Stats</a>
                    <div class="nav-collapse collapse">
                        <ul class="nav">
                            <li><a href="#">News</a></li>
                            <li><a href="#">Stats</a></li>
                            <li><a href="<?= base_url('about'); ?>">About</a></li>
                        </ul>
                        <ul class="nav pull-right">
                            <li><a href="http://twitter.com/iBotPeaches">Made with &hearts; iBotPeaches</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <br /><br/><br/>
        <div class="container">
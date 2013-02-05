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
                    <a class="brand" href="<?= base_url(); ?>">Leaf</a>
                    <div class="nav-collapse collapse">
                        <ul class="nav">
                            <li class="<? if ($this->library->is_active("news")): ?> active<? endif; ?>"><a href="<?= base_url('news'); ?>">News</a></li>
                            <li class="<? if ($this->library->is_active("stats")): ?> active<? endif; ?>"><a href="<?= base_url('stats'); ?>">Leaderboards</a></li>
                            <li class="<? if ($this->library->is_active("compare")): ?> active<? endif; ?>"><a href="<?= base_url('compare'); ?>">Compare</a></li>
                            <li class="<? if ($this->library->is_active("about")): ?> active<? endif; ?>"><a href="<?= base_url('about'); ?>">About</a></li>
                        </ul>
                        <ul class="nav pull-right">
                            <li><a href="http://twitter.com/iBotPeaches" rel="nofollow" target="_blank">Made with &hearts; iBotPeaches</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <br /><br/><br/>
        <div class="container">
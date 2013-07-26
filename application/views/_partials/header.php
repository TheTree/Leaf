<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <?= $template['_partials']['head_header']; ?>
    <body id="top" data-spy="scroll" data-target=".subnav" data-offset="100">
    <header>
        <div class="navbar <? if ($this->uri->segment(1) != "backstage"): ?>navbar-inverse<? endif; ?> navbar-<? if ($this->uri->segment(2) == "leaderboards"): ?>fixed<? else: ?>static<? endif; ?>-top">
            <div class="navbar-inner">
                <div class="container">
                    <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </a>
                    <a class="brand" href="<?= base_url(); ?>">Leaf</a>
                    <div class="nav-collapse collapse">
                        <nav>
                            <ul class="nav">
                                <li class="<? if ($this->utils->is_active("news", "home", 1)): ?> active<? endif; ?>"><a href="<?= base_url('news'); ?>">News</a></li>
                                <li class="<? if ($this->utils->is_active("csr_leaderboards", "home", 2)): ?> active<? endif; ?>"><a href="<?= base_url('h4/csr_leaderboards'); ?>">CSR Leaderboards</a></li>
                                <li class="<? if ($this->utils->is_active("leaderboards", "home", 2)): ?> active<? endif; ?>"><a href="<?= base_url('h4/leaderboards'); ?>">Top Ten</a></li>
                                <li class="<? if ($this->utils->is_active("compare", "home", 2)):?> active<? endif; ?>"><a rel="help" href="<?= base_url('h4/compare'); ?>">Compare</a></li>
                                <li class="<? if ($this->utils->is_active("about", "home", 1)): ?> active<? endif; ?>"><a href="<?= base_url('about'); ?>">About</a></li>
                            </ul>
                        </nav>
                        <ul class="nav pull-right">
                            <? if (isset($starred) && is_array($starred)): ?>
                                <li><a href="<?= base_url('/gt/' . $starred['SeoGamertag']); ?>"><img src="<?= $starred['Emblem']; ?>" class="img-emblem" />&nbsp;<?= $starred['Gamertag']; ?></a></li>
                            <? else: ?>
                                <li><a href="http://auntiedot.net/" rel="nofollow" target="_blank">Powered by Auntiedot</a></li>
                            <? endif; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </header>
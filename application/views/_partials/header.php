<?= doctype() ?>
<html lang="en">
    <?= $template['_partials']['head_header']; ?>
    <body id="top" data-spy="scroll" data-target=".subnav" data-offset="100">
        <div class="navbar <? if ($this->uri->segment(1) != "backstage"): ?>navbar-inverse<? endif; ?> navbar-static-top">
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
                            <li class="<? if ($this->library->is_active("csr_leaderboards")): ?> active<? endif; ?>"><a href="<?= base_url('csr_leaderboards'); ?>">CSR Leaderboards</a></li>
                            <li class="<? if ($this->library->is_active("leaderboards")): ?> active<? endif; ?>"><a href="<?= base_url('leaderboards'); ?>">Top Ten</a></li>
                            <li class="<? if ($this->library->is_active("compare")): ?> active<? endif; ?>"><a rel="help" href="<?= base_url('compare'); ?>">Compare</a></li>
                            <li class="<? if ($this->library->is_active("about")): ?> active<? endif; ?>"><a href="<?= base_url('about'); ?>">About</a></li>
                        </ul>
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
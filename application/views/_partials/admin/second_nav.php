<nav class="navbar navbar-inverse navbar-static-top" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a href="<?= base_url('backstage/index'); ?>" class="navbar-brand">Sekrit Admin Panel</a>
        </div>
        <div class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li class="<?= $this->utils->is_active('index','index', 2); ?>"><a href="<?= base_url('backstage/index'); ?>"><?= lang('acp_home'); ?></a></li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?= lang('acp_news'); ?>
                        <b class="caret"></b></a>
                    <ul class="dropdown-menu <?= $this->utils->is_active('news', 'index', 2); ?>">
                        <li>
                            <a href="<?= base_url('backstage/news/list'); ?>"><?= lang('acp_news_list'); ?></a>
                            <a href="<?= base_url('backstage/news/create'); ?>"><?= lang('acp_news_create'); ?></a>
                        </li>
                    </ul>
                </li>
                <li class="dropdown <?= $this->utils->is_active('badges','index', 2); ?>">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?= lang('acp_badges'); ?>
                        <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="<?= base_url('backstage/badges/list'); ?>"><?= lang('acp_badges_list'); ?></a>
                            <a href="<?= base_url('backstage/badges/create'); ?>"><?= lang('acp_badges_create'); ?></a>
                        </li>
                    </ul>
                </li>
                <li class="<?= $this->utils->is_active('flagged', 'index', 2); ?>"><a href="<?= base_url('backstage/flagged'); ?>"><?= lang('acp_flagged'); ?></a></li>
                <li class="<?= $this->utils->is_active('keys', 'index', 2); ?>"><a href="<?= base_url('backstage/keys'); ?>"><?= lang('acp_keys'); ?></a></li>
                <li class="<?= $this->utils->is_active('find', 'index', 2); ?>"><a href="<?= base_url('backstage/find'); ?>"><?= lang('acp_find'); ?></a></li>
                <li><a href="<?= base_url('backstage/logout'); ?>"><?= lang('acp_logout'); ?></a></li>
            </ul>
        </div>
    </div>
</nav>

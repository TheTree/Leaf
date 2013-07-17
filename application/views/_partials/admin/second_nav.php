<div class="navbar navbar-inverse navbar-static-top">
    <div class="navbar-inner">
        <div class="container">
            <a href="<?= base_url('backstage/index'); ?>" class="brand">Sekrit Admin Panel</a>
            <a class="btn btn-navbar" data-toggle="collapse" data-target=".sub_menu">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>
            <div class="nav-collapse sub_menu collapse">
                <ul class="nav">
                    <li class="<?= $this->library->is_acp_active('index'); ?>"><a href="<?= base_url('backstage/index'); ?>"><?= lang('acp_home'); ?></a></li>
                    <li class="dropdown <?= $this->library->is_acp_active('news'); ?>">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?= lang('acp_news'); ?>
                            <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li>
                                <a href="<?= base_url('backstage/news/list'); ?>"><?= lang('acp_news_list'); ?></a>
                                <a href="<?= base_url('backstage/news/create'); ?>"><?= lang('acp_news_create'); ?></a>
                            </li>
                        </ul>
                    </li>
                    <li class="dropdown <?= $this->library->is_acp_active('badges'); ?>">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?= lang('acp_badges'); ?>
                            <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li>
                                <a href="<?= base_url('backstage/badges/list'); ?>"><?= lang('acp_badges_list'); ?></a>
                                <a href="<?= base_url('backstage/badges/create'); ?>"><?= lang('acp_badges_create'); ?></a>
                            </li>
                        </ul>
                    </li>
                    <li class="<?= $this->library->is_acp_active('flagged'); ?>"><a href="<?= base_url('backstage/flagged'); ?>"><?= lang('acp_flagged'); ?></a></li>
                    <li class="<?= $this->library->is_acp_active('keys'); ?>"><a href="<?= base_url('backstage/keys'); ?>"><?= lang('acp_keys'); ?></a></li>
                    <li class="<?= $this->library->is_acp_active('find'); ?>"><a href="<?= base_url('backstage/find'); ?>"><?= lang('acp_find'); ?></a></li>
                    <li><a href="<?= base_url('backstage/logout'); ?>"><?= lang('acp_logout'); ?></a></li>
                </ul>
            </div>
        </div>
    </div>
</div>

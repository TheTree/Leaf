<div class="container">
    <div class="row">
        <div class="panel panel-danger">
            <div class="panel-heading">
                <h5><?= lang('error_page_hero_unit'); ?></h5>
            </div>
            <div class="panel-body">
                <p>
                    <?= $error_msg; ?>
                </p>
                <br />
                <small>
                    <p>
                        If you are getting this page a lot.
                        This probably means your poor web browser caches redirects, so your always getting sent back here.
                        You can just force a refresh and it should fix itself :)
                    </p>
                </small>
            </div>
        </div>
    </div>
</div>
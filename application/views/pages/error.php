<div class="container-fluid">
    <div class="row-fluid">
        <div class="hero-unit">
            <h1><?= lang('error_page_hero_unit'); ?></h1>
        </div>
        <div class="alert alert-error">
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
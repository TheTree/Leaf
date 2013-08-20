<div class="row">
   <h1><?= isset($data[H4::BADGE]) ? $data[H4::BADGE] : ""; ?><?= urldecode($gamertag); ?><small><?= $data[H4::SERVICE_TAG]; ?></small>
        <? if ($data[H4::STATUS] == 0): ?>
            <a href="<?= base_url('h4/flag/' . $data[H4::SEO_GAMERTAG]); ?>" data-toggle="tooltop" data-html="true"
               title="Flag <strong><?= $data[H4::GAMERTAG]; ?></strong> as a Cheater/Booster?" rel="tooltip"
               data-placement="right" onclick="return confirm('Are you sure you wish to flag <?= $data[H4::GAMERTAG]; ?> ?');"><i class="icon-flag"></i></a>
        <? endif; ?>
            <a href="<?= base_url('h4/star/' . $data[H4::SEO_GAMERTAG]); ?>" data-toggle="tooltip" data-html="true"
               title="Star <strong><?= $data[H4::GAMERTAG]; ?></strong>" rel="tooltip" data-placement="right"><i class="icon-star"></i></a>
    </h1>
</div>
<div class="row">
    <div class="col-md-3">
        <div class="text-center">
            <?= $template['_partials']['block_photo']; ?>
            <?= $template['_partials']['block_specs']; ?>
            <div class="visible-lg">
                <?= $template['_partials']['block_cheatertest']; ?>
                <?= $template['_partials']['block_inactivetest']; ?>
            </div>
            <?= $template['_partials']['block_social']; ?>
        </div>
    </div>
    <div class="col-md-9">
        <? if ($msg != false): ?>
            <div class="alert alert-info alert-close margin3pxtop">
                <button type="button" class="close" data-dismiss="alert">×</button>
                    <? if ($msg == "enabled"): ?>
                    <strong>Hey</strong> We've updated your stats :) 
                    <? else: ?>
                    <strong>Hey</strong> Sorry. Your not ready for a stat update :(
                    <? endif; ?>
            </div>
        <? elseif ($general_msg != false): ?>
            <div class="alert alert-success alert-close">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>Hey</strong> We've flagged <?= $data[H4::GAMERTAG]; ?> for you :)
            </div>
        <? endif; ?>
        <?= $template['_partials']['block_cheatertest']; ?>
        <?= $template['_partials']['block_inactivetest']; ?>
        <?= $template['_partials']['block_progression']; ?>
    </div>
    <div class="col-md-5">
        <section>
            <?= $template['_partials']['block_basicstats']; ?>
        </section>
    </div>
    <div class="col-md-4">
        <aside>
            <?= $template['_partials']['block_favoriteweapon']; ?>
            <?= $template['_partials']['block_bestgame']; ?>
        </aside>
        <br />
    </div>
    <br />
    <div class="col-md-9">
        <?= $template['_partials']['block_csr']; ?>
        <?= $template['_partials']['block_medals']; ?>
        <?= $template['_partials']['block_cheatertest']; ?>
    </div>
</div>
<?= $template['_partials']['js_auto_close_alert']; ?>
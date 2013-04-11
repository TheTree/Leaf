<script type="text/javascript">
    // setup func
    function set(id, con, title) {
        $("#" + id).popover({
            title: title.toString(),
            content: con.toString(),
            trigger: 'hover',
            placement: 'top',
            delay: {show: 0, hide: 25}
        });
    }
</script>
<div class="row-fluid">
    <h1><?= $badge; ?><?= urldecode($gamertag); ?> <small><?= $data['ServiceTag']; ?></small></h1>
</div>
<div class="row-fluid">
    <div class="span3">
        <div class="pagination-centered">
            <?= $template['_partials']['block_photo']; ?>
        </div>
    </div>
    <div class="span9">
        <? if ($msg != false): ?>
            <div class="alert alert-info">
                <button type="button" class="close" data-dismiss="alert">×</button>
                    <? if ($msg == "enabled"): ?>
                    <strong>Hey</strong> We've updated your stats :) 
                    <? else: ?>
                    <strong>Hey</strong> Sorry. Your not ready for a stat update :(
                    <? endif; ?>
            </div>
        <? endif; ?>
        <?= $template['_partials']['block_progression']; ?>
    </div>
    <div class="span4">
        <?= $template['_partials']['block_basicstats']; ?>
    </div>
    <div class="span5">
        <? if (isset($data['FavoriteData'])): ?>
            <?= $template['_partials']['block_favoriteweapon']; ?>
        <? endif; ?>
    </div>
    <div class="span9">
        <?= $template['_partials']['block_csr']; ?>
        <?= $template['_partials']['block_medals']; ?>
    </div>
</div>
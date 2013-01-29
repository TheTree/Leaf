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
    <div class="span3">
        <h2><?= urldecode($gamertag); ?> <small><?= $data['ServiceTag']; ?></small></h2>
        <div class="pagination-centered">
            <?= $template['_partials']['block_photo']; ?>
        </div>
    </div>
    <div class="span9">
        <br /><br />
        <?= $template['_partials']['block_progression']; ?>
        <?= $template['_partials']['block_basicstats']; ?>
    </div>
    <div class="span9">
        <?= $template['_partials']['block_medals']; ?>
    </div>
</div>
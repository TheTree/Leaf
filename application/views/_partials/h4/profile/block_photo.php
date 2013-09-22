<? if (($data[H4::EXPIRATION] - SEVENDAYS_IN_SECONDS - time() + FIVEMIN_IN_SECONDS) > 0): ?>
<script type="text/javascript">
    function show_recache() {
        $('#recache_button').show();
    }
    $(document).ready(function() {
        setTimeout(show_recache, <?= (($data[H4::EXPIRATION] - SEVENDAYS_IN_SECONDS - time() + FIVEMIN_IN_SECONDS) * 1000) ?>);
    });
</script>
<? endif; ?>
<img src="<?= $data['SpartanURL']; ?>?v=<?= $data[H4::TOTAL_GAMEPLAY]; ?>" width="211px" height="418px" class="img-polaroid" />
<!-- InactiveCounter: <?= $data[H4::INACTIVE_COUNTER]; ?> -->
<!-- Status: <?= $data[H4::STATUS]; ?> -->
<!-- ApiVersion: <?= $data[H4::API_VERSION]; ?> -->
<div class="well well-large btn btn-primary pad10 w150">
    <div class="pagination-centered">
        <img src="<?= $this->h4_lib->return_image_url('Rank', $data[H4::RANK], 'large'); ?>" />
        <?= $data[H4::SPECIALIZATION]; ?> - <?= $data[H4::SPECIALIZATION_LEVEL]; ?>
    </div>
</div>
<br />
<? if ($data[H4::INACTIVE_COUNTER] < INACTIVE_COUNTER): ?>
    <? if ((($data[H4::EXPIRATION] - SEVENDAYS_IN_SECONDS + FIVEMIN_IN_SECONDS) < time()) && $data[H4::INACTIVE_COUNTER] < INACTIVE_COUNTER): ?>
        <a href="<?= base_url('h4/record/' . $data[H4::SEO_GAMERTAG] . "/recache"); ?>" class="btn btn-success pad10">Refresh</a>
    <? elseif (($data[H4::EXPIRATION] - SEVENDAYS_IN_SECONDS - time() + FIVEMIN_IN_SECONDS) > 0): ?>
        <a id="recache_button" href="<?= base_url('h4/record/' . $data[H4::SEO_GAMERTAG] . "/recache"); ?>" style="display:none;" class="btn btn-success pad10">Refresh</a>
    <? endif; ?>
<? endif; ?>
<? if ($data[H4::INACTIVE_COUNTER] >= INACTIVE_COUNTER): ?>
    <a href="<?= base_url('h4/removefreeze/' . $data[H4::SEO_GAMERTAG]); ?>" class="btn btn-success margin3pxtop">Unfreeze</a>
<? endif; ?>
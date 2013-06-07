<? if (($data['Expiration'] - TWENTYFOUR_HOURS_IN_SECONDS - time() + FIVEMIN_IN_SECONDS) > 0): ?>
<script type="text/javascript">
    function show_recache() {
        $('#recache_button').show();
    }
    $(document).ready(function() {
        setTimeout(show_recache, <?= (($data['Expiration'] - TWENTYFOUR_HOURS_IN_SECONDS - time() + FIVEMIN_IN_SECONDS) * 1000) ?>);
    });
</script>
<? endif; ?>
<img src="<?= $data['SpartanURL']; ?>" width="211px" height="418px" class="img-polaroid" />
<!-- InactiveCounter: <?= $data['InactiveCounter']; ?> -->
<div class="well well-large btn btn-primary pad10 w150">
    <div class="pagination-centered">
        <img src="<?= $data['RankImage']; ?>" />
        <?= $data['Specialization']; ?> - <?= $data['SpecializationLevel']; ?>
    </div>
</div>
<br />
<? if ($data['InactiveCounter'] < INACTIVE_COUNTER): ?>
    <? if ((($data['Expiration'] - TWENTYFOUR_HOURS_IN_SECONDS + FIVEMIN_IN_SECONDS) < time()) && $data['InactiveCounter'] < INACTIVE_COUNTER): ?>
        <a href="<?= base_url('gt/' . $data['SeoGamertag'] . "/recache"); ?>" class="btn btn-success pad10">Refresh</a>
    <? elseif (($data['Expiration'] - TWENTYFOUR_HOURS_IN_SECONDS - time() + FIVEMIN_IN_SECONDS) > 0): ?>
        <a id="recache_button" href="<?= base_url('gt/' . $data['SeoGamertag'] . "/recache"); ?>" style="display:none;" class="btn btn-success pad10">Refresh</a>
    <? endif; ?>
<? endif; ?>
<? if ($data['InactiveCounter'] >= INACTIVE_COUNTER): ?>
    <a href="<?= base_url('unfreeze/' . $data['SeoGamertag']); ?>" class="btn btn-success margin3pxtop">Unfreeze</a>
<? endif; ?>
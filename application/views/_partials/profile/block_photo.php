<img src="<?= $data['SpartanURL']; ?>" width="211px" height="418px" class="img-polaroid" />
<div class="well well-large btn btn-primary pad10 w150">
    <div class="pagination-centered">
        <img src="<?= $data['RankImage']; ?>" />
        <?= $data['Specialization']; ?> - <?= $data['SpecializationLevel']; ?>
    </div>
</div>
<br />
<? if ((($data['Expiration'] - TWELVE_HOURS_IN_SECONDS + FIVEMIN_IN_SECONDS) < time()) && $data['InactiveCounter'] < 40): ?>
    <a href="<?= base_url('gt/' . $data['SeoGamertag'] . "/recache"); ?>" class="btn btn-success pad10">Refresh</a>
<? endif; ?>
<? if ($data['InactiveCounter'] >= 40): ?>
    <a href="<?= base_url('unfreeze/' . $data['SeoGamertag']); ?>" class="btn btn-success margin3pxtop">Unfreeze</a>
<? endif; ?>
<img src="<?= $data['SpartanURL']; ?>" class="img-polaroid" />
<div class="well well-large btn btn-primary pad10 w150">
    <img src="<?= $data['RankImage']; ?>" />
    <?= $data['Specialization']; ?> - <?= $data['SpecializationLevel']; ?>
</div>
<br />
<? if ((($data['Expiration'] - THREE_HOURS_IN_SECONDS + HOUR_IN_SECONDS) < time()) && $data['InactiveCounter'] < 40): ?>
    <a href="<?= base_url('gt/' . str_replace(" ", "_",$data['Gamertag']) . "/recache"); ?>" class="btn btn-success btn-large">Recache</a>
<? endif; ?>
 
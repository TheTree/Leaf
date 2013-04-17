<img src="<?= $data['SpartanURL']; ?>" width="211px" height="418px" class="img-polaroid" />
<div class="well well-large btn btn-primary pad10 w150">
    <div class="pagination-centered">
        <img src="<?= $data['RankImage']; ?>" />
        <?= $data['Specialization']; ?> - <?= $data['SpecializationLevel']; ?>
    </div>
</div>
<br />
<? if ((($data['Expiration'] - THREE_HOURS_IN_SECONDS + FIVEMIN_IN_SECONDS) < time()) && $data['InactiveCounter'] < 40): ?>
    <a href="<?= base_url('gt/' . str_replace(" ", "_",$data['Gamertag']) . "/recache"); ?>" class="btn btn-success pad10">Refresh</a>
<? endif; ?>
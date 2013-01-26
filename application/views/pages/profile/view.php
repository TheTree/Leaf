<div class="row-fluid">
    <div class="span4">
        <h2><?= urldecode($gamertag); ?> <? if (urldecode($gamertag) == "iBotPeaches v5"): ?><span class="badge badge-success">Creator</span><? endif; ?></h2>
        <img src="https://spartans.svc.halowaypoint.com/players/<?= urlencode($gamertag); ?>/h4/spartans/fullbody?target=medium" class="img-polaroid" />
    </div>

    <div class="span8">
        <div class="well well-large">

        </div>
    </div>
</div>
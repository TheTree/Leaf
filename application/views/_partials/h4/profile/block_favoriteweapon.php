<div class="visible-desktop visible-tablet">
    <strong>Favorite Weapon</strong>
    <div class="well well-large">
        <div class="pagination-centered">
            <h3><?= $data['FavoriteData']['WeaponName']; ?></h3>
            <img data-toggle="tooltop" rel="tooltip" data-placement="bottom" title="<?= $data['FavoriteData']['WeaponDesc']; ?>" src="<?= $data['FavoriteData']['WeaponUrl']; ?>" class="img-polaroid" />
            <br />
            <br />
            <span class="badge badge-info">Kills: <?= number_format($data['FavoriteData']['WeaponTotalKills']); ?></span>
        </div>
    </div>
</div>
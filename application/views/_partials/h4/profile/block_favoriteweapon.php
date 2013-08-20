<div class="visible-lg visible-md">
    <strong>Favorite Weapon</strong>
    <div class="well well-lg">
        <div class="text-center">
            <h3><?= $data['FavoriteData']['WeaponName']; ?></h3>
            <img data-toggle="tooltop" rel="tooltip" data-placement="bottom" title="<?= $data['FavoriteData']['WeaponDesc']; ?>" src="<?= $data['FavoriteData']['WeaponUrl']; ?>" class="img-polaroid" />
            <br />
            <br />
            <span class="label label-info">Kills: <?= number_format($data['FavoriteData']['WeaponTotalKills']); ?></span>
        </div>
    </div>
</div>
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
            <img src="<?= $data['SpartanURL']; ?>" class="img-polaroid" />
            <div class="well well-large btn btn-primary pad10 w150">
                <img src="<?= $data['RankImage']; ?>" />
                <?= $data['Specialization']; ?> - <?= $data['SpecializationLevel']; ?>
            </div>
        </div>
    </div>

    <div class="span9">
        <br /><br />
        <? if ($data['NextRankStartXP'] == 0): ?>
            Specialization: <?= $data['Specialization']; ?> Completed
        <? else: ?>
            <?= number_format($data['NextRankStartXP'] - $data['Xp']); ?> XP till next level (<?= round(floatval(($data['Xp'] - $data['RankStartXP']) / ($data['NextRankStartXP'] - $data['RankStartXP'])) * 100); ?>% complete)
        <? endif; ?>
        <div class="progress progress-striped">
            <? if ($data['NextRankStartXP'] == 0): ?>
                <div class="bar" style="width: 100%;"></div>
            <? else: ?>
                <div class="bar" style="width: <?= floatval(($data['Xp'] - $data['RankStartXP']) / ($data['NextRankStartXP'] - $data['RankStartXP'])) * 100; ?>%;"></div>
            <? endif; ?>
        </div>
        <strong>Basic Stats</strong>
        <div class="well well-large">
            <ul class="unstyled">
                <li>
                    <span class="row_title">Total Wins</span>
                    <span class="row_data"><?= number_format($data['TotalGameWins']); ?></span>
                </li>
                <li>
                    <span class="row_title">Total Loses</span>
                    <span class="row_data"><?= number_format($data['TotalGamesStarted'] - $data['TotalGameQuits'] - $data['TotalGameWins']); ?></span>
                </li>
                <li>
                    <span class="row_title">Total Quits</span>
                    <span class="row_data"><?= number_format($data['TotalGameQuits']); ?></span>
                </li>
                <hr>
                <li>
                    <span class="row_title">Total Medals</span>
                    <span class="row_data"><?= number_format($data['TotalMedalsEarned']); ?></span>
                </li>
                <li>
                    <span class="row_title">Total Kills</span>
                    <span class="row_data"><?= number_format($data['TotalKills']); ?></span>
                </li>
                <li>
                    <span class="row_title">Total Deaths</span>
                    <span class="row_data"><?= number_format($data['TotalDeaths']); ?></span>
                </li>
                <li>
                    <span class="row_title">KD Ratio</span>
                    <span class="row_data"><?= round(floatval($data['KDRatio']), 3); ?></span>
                </li>
                <li>
                    <span class="row_title">Spartan Points</span>
                    <span class="row_data"><?= number_format($data['SpartanPoints']); ?></span>
                </li>
                <li>
                    <span class="row_title">Items Purchased</span>
                    <span class="row_data"><?= number_format($data['TotalLoadoutItemsPurchased']); ?></span>
                </li>
                <li>
                    <span class="row_title">Challenges Completed</span>
                    <span class="row_data"><?= number_format($data['TotalChallengesCompleted']); ?></span>
                </li>
            </ul>
        </div>
    </div>

    <div class="span9">
        <strong>Medals</strong>
        <div class="well well-large h150">
            <div class="pagination-centered">
                <? foreach ($data['MedalData'] as $medal): ?>
                        <img src="<?= $medal['ImageUrl']; ?>" class="img-polaroid " id="photo_<?= $medal['Id']; ?>" onMouseOver="set('photo_<?= $medal['Id']; ?>', '<?= addslashes($medal['Description']); ?>', '<?= $medal['Name']; ?> - Amt: <?= $medal['Count']; ?>');" />
                <? endforeach; ?>
            </div>
        </div>
    </div>
</div>
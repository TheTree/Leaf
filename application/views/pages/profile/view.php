<div class="row-fluid">
    <div class="span3">
        <h2><?= urldecode($gamertag); ?> <small><?= $data['ServiceTag']; ?></small></h2>
        <div class="pagination-centered">
        <img src="<?= $data['SpartanURL']; ?>" class="img-polaroid" />
        <div class="well well-large btn btn-primary pad10">
            <img src="<?= str_replace("{RANK}", $data['RankImage'], $rank_url); ?>" />
           <?= $data['Specialization']; ?> - <?= $data['SpecializationLevel']; ?>
        </div>
        </div>
    </div>

    <div class="span9">
        <br /><br />
        <div class="progress progress-striped">
            <div class="bar" style="width: <?= floatval($data['Xp'] / $data['NextRankStartXP']) * 100; ?>%;"></div>
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
            </ul>
        </div>
    </div>
</div>
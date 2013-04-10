<strong>Basic Stats</strong>
<div class="well well-large">
    <ul class="unstyled">
        <li>
            <span class="row_title">Total Wins</span>
            <span class="row_data"><?= number_format($data['TotalGameWins']); ?></span>
        </li>
        <li>
            <span class="row_title">Total Losses</span>
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
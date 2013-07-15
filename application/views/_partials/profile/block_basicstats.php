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
        <li>
            <span class="row_title">Total Games</span>
            <span class="row_data"><?= number_format($data['TotalGamesStarted']); ?></span>
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
            <span class="row_title">Total Assists</span>
            <span class="row_data"><?= number_format($data['TotalAssists']); ?></span>
        </li>
        <li>
            <span class="row_title">Total Headshots</span>
            <span class="row_data"><?= number_format($data['TotalHeadshots']); ?></span>
        </li>
        <li>
            <span class="row_title">Total Betrayals</span>
            <span class="row_data"><?= number_format($data['TotalBetrayals']); ?></span>
        </li>
        <li>
            <span class="row_title">Total Suicides</span>
            <span class="row_data"><?= number_format($data['TotalSuicides']); ?></span>
        </li>
        <li>
            <span class="row_title"><abbr title="Kills / Death">K/D</abbr> Ratio</span>
            <span class="row_data"><?= round(floatval($data['KDRatio']), 3); ?></span>
        </li>
        <li>
            <span class="row_title"><abbr title="Kills + Assists / Death">KA/D</abbr> Ratio</span>
            <span class="row_data"><?= round(floatval(($data['TotalKills'] + $data['TotalAssists']) / $data['TotalDeaths']), 2); ?></span>
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
        <li>
            <span class="row_title">Commendation Progress</span>
            <span class="row_data"><?= number_format($data['TotalCommendationProgress'] * 100); ?>%</span>
        </li>
        <li>
            <span class="row_title">Average Score</span>
            <span class="row_data"><?= number_format($data['AveragePersonalScore']); ?></span>
        </li>
    </ul>
</div>
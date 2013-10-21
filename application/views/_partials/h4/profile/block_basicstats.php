<strong>Basic Stats</strong>
<div class="well well-lg">
    <ul class="list-unstyled">
        <li>
            <span class="row_title">Total Wins</span>
            <span class="row_data"><?= number_format($data[H4::TOTAL_GAME_WINS]); ?></span>
        </li>
        <li>
            <span class="row_title">Total Losses</span>
            <span class="row_data"><?= number_format($data[H4::TOTAL_GAMES_STARTED] - $data[H4::TOTAL_GAME_QUITS] - $data[H4::TOTAL_GAME_WINS]); ?></span>
        </li>
        <li>
            <span class="row_title">Total Quits</span>
            <span class="row_data"><?= number_format($data[H4::TOTAL_GAME_QUITS]); ?></span>
        </li>
        <li>
            <span class="row_title">Total Games</span>
            <span class="row_data"><?= number_format($data[H4::TOTAL_GAMES_STARTED]); ?></span>
        </li>
        <li>
            <span class="row_title">Total Gameplay</span>
            <span class="row_data"><?= $this->utils->time_duration($data[H4::TOTAL_GAMEPLAY],'Mdhw'); ?></span>
        </li>
        <hr>
        <li>
            <span class="row_title">Total Medals</span>
            <span class="row_data"><?= number_format($data[H4::TOTAL_MEDALS]); ?></span>
        </li>
        <li>
            <span class="row_title">Total Kills</span>
            <span class="row_data"><?= number_format($data[H4::TOTAL_KILLS]); ?></span>
        </li>
        <li>
            <span class="row_title">Total Deaths</span>
            <span class="row_data"><?= number_format($data[H4::TOTAL_DEATHS]); ?></span>
        </li>
        <li>
            <span class="row_title">Total Assists</span>
            <span class="row_data"><?= number_format($data[H4::TOTAL_ASSISTS]); ?></span>
        </li>
        <li>
            <span class="row_title">Total Headshots</span>
            <span class="row_data"><?= number_format($data[H4::TOTAL_HEADSHOTS]); ?></span>
        </li>
        <li>
            <span class="row_title">Total Betrayals</span>
            <span class="row_data"><?= number_format($data[H4::TOTAL_BETRAYALS]); ?></span>
        </li>
        <li>
            <span class="row_title">Total Suicides</span>
            <span class="row_data"><?= number_format($data[H4::TOTAL_SUICIDES]); ?></span>
        </li>
        <li>
            <span class="row_title"><abbr title="Kills / Death">K/D</abbr> Ratio</span>
            <span class="row_data"><?= round(floatval($data[H4::KD_RATIO]), 2); ?></span>
        </li>
        <li>
            <span class="row_title"><abbr title="Kills + Assists / Death">KA/D</abbr> Ratio</span>
            <span class="row_data"><?= round(floatval($data[H4::KAD_RATIO]), 2); ?></span>
        </li>
        <li>
            <span class="row_title">Spartan Points</span>
            <span class="row_data"><?= number_format($data[H4::SPARTAN_POINTS]); ?></span>
        </li>
        <li>
            <span class="row_title">Items Purchased</span>
            <span class="row_data"><?= number_format($data[H4::TOTAL_LOADOUT_ITEMS_PURCHASED]); ?></span>
        </li>
        <li>
            <span class="row_title">Challenges Done</span>
            <span class="row_data"><?= number_format($data[H4::TOTAL_CHALLENGES_COMPLETED]); ?></span>
        </li>
        <li>
            <span class="row_title">Commendations</span>
            <span class="row_data"><?= number_format($data[H4::TOTAL_COMMENDATION_PROGRESS] * 100); ?>%</span>
        </li>
        <li>
            <span class="row_title">Average Score</span>
            <span class="row_data"><?= number_format($data[H4::AVERAGE_PERSONAL_SCORE]); ?></span>
        </li>
    </ul>
</div>
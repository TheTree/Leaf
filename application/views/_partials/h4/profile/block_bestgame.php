<div class="visible-lg visible-md">
    <strong>Most in a Game</strong>
    <div class="well well-lg">
        <ul class="list-unstyled">
            <li>
                <span class="row_title">Most Kills</span>
                <span class="row_data">
                    <?= number_format($data[H4::BEST_GAME_TOTAL_KILLS]); ?>
                    <? if (BRANCH): ?>
                        <a class="hidden-phone hidden-table"
                           href="https://branchapp.co/halo4/servicerecord/<?= $data['BranchGamertag']; ?>/game/<?= $data[H4::BEST_GAME_TOTAL_KILLS_GAMEID]; ?>?u=game"
                           data-toggle="tooltip" data-html="true" title="View Game on Branch"
                           rel="tooltip" data-placement="top"><i class="icon-tag"></i></a>
                    <? endif; ?>
                </span>
            </li>
            <li>
                <span class="row_title">Most Medals</span>
                <span class="row_data">
                    <?= number_format($data[H4::BEST_GAME_TOTAL_MEDALS]); ?>
                    <? if (BRANCH): ?>
                        <a class="hidden-phone hidden-table"
                           href="https://branchapp.co/halo4/servicerecord/<?= $data['BranchGamertag']; ?>/game/<?= $data[H4::BEST_GAME_TOTAL_MEDALS_GAMEID]; ?>?u=game"
                           data-toggle="tooltip" data-html="true" title="View Game on Branch"
                           rel="tooltip" data-placement="top"><i class="icon-tag"></i></a>
                    <? endif; ?>
                </span>
            </li>
            <li>
                <span class="row_title">Most Headshots</span>
                <span class="row_data">
                    <?= number_format($data[H4::BEST_GAME_HEADSHOT_TOTAL]); ?>
                    <? if (BRANCH): ?>
                        <a class="hidden-phone hidden-table"
                           href="https://branchapp.co/halo4/servicerecord/<?= $data['BranchGamertag']; ?>/game/<?= $data[H4::BEST_GAME_HEADSHOT_GAMEID]; ?>?u=game"
                           data-toggle="tooltip" data-html="true" title="View Game on Branch"
                           rel="tooltip" data-placement="top"><i class="icon-tag"></i></a>
                    <? endif; ?>
                </span>
            </li>
            <li>
                <span class="row_title">Most Assassinations</span>
                <span class="row_data">
                    <?= number_format($data[H4::BEST_GAME_ASSASSINATION_TOTAL]); ?>
                    <? if (BRANCH): ?>
                        <a class="hidden-phone hidden-table"
                           href="https://branchapp.co/halo4/servicerecord/<?= $data['BranchGamertag']; ?>/game/<?= $data[H4::BEST_GAME_ASSASSINATION_GAMEID]; ?>?u=game"
                           data-toggle="tooltip" data-html="true" title="View Game on Branch"
                           rel="tooltip" data-placement="top"><i class="icon-tag"></i></a>
                    <? endif; ?>
                </span>
            </li>
            <li>
                <span class="row_title">Longest Kill Distance</span>
                <span class="row_data">
                    <?= number_format($data[H4::BEST_GAME_KILL_DISTANCE]); ?><abbr title="meters">m</abbr>
                    <? if (BRANCH): ?>
                        <a class="hidden-phone hidden-table"
                           href="https://branchapp.co/halo4/servicerecord/<?= $data['BranchGamertag']; ?>/game/<?= $data[H4::BEST_GAME_KILL_DISTANCE_GAMEID]; ?>?u=game"
                           data-toggle="tooltip" data-html="true" title="View Game on Branch"
                           rel="tooltip" data-placement="top"><i class="icon-tag"></i></a>
                    <? endif; ?>
                </span>
            </li>
        </ul>
    </div>
</div>
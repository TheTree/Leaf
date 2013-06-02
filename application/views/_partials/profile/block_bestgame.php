<div class="visible-desktop visible-tablet">
    <strong>Most in a Game</strong>
    <div class="well well-large">
        <ul class="unstyled">
            <li>
                <span class="row_title">Most Kills</span>
                <span class="row_data">
                    <?= number_format($data['BestGameTotalKills']); ?>

                    <? if (BRANCH): ?>
                        <a class="hidden-phone hidden-table"
                           href="http://branchapp.co/halo4/servicerecord/<?= $data['BranchGamertag']; ?>/match/<?= $data['BestGameTotalKillsGameId']; ?>/"
                           data-toggle="tooltip" data-html="true" title="View Game on Branch"
                           rel="tooltip" data-placement="top"><i class="icon-tag"></i></a>
                    <? endif; ?>
                </span>
            </li>
            <li>
                <span class="row_title">Most Medals</span>
                <span class="row_data">
                    <?= number_format($data['BestGameTotalMedals']); ?>
                    <? if (BRANCH): ?>
                        <a class="hidden-phone hidden-table"
                           href="http://branchapp.co/halo4/servicerecord/<?= $data['BranchGamertag']; ?>/match/<?= $data['BestGameTotalMedalsGameId']; ?>/"
                           data-toggle="tooltip" data-html="true" title="View Game on Branch"
                           rel="tooltip" data-placement="top"><i class="icon-tag"></i></a>
                    <? endif; ?>
                </span>
            </li>
            <li>
                <span class="row_title">Most Headshots</span>
                <span class="row_data">
                    <?= number_format($data['BestGameHeadshotTotal']); ?>
                    <? if (BRANCH): ?>
                        <a class="hidden-phone hidden-table"
                           href="http://branchapp.co/halo4/servicerecord/<?= $data['BranchGamertag']; ?>/match/<?= $data['BestGameHeadshotTotalGameId']; ?>/"
                           data-toggle="tooltip" data-html="true" title="View Game on Branch"
                           rel="tooltip" data-placement="top"><i class="icon-tag"></i></a>
                    <? endif; ?>
                </span>
            </li>
            <li>
                <span class="row_title">Most Assassinations</span>
                <span class="row_data">
                    <?= number_format($data['BestGameAssassinationTotal']); ?>
                    <? if (BRANCH): ?>
                        <a class="hidden-phone hidden-table"
                           href="http://branchapp.co/halo4/servicerecord/<?= $data['BranchGamertag']; ?>/match/<?= $data['BestGameAssassinationTotalGameId']; ?>/"
                           data-toggle="tooltip" data-html="true" title="View Game on Branch"
                           rel="tooltip" data-placement="top"><i class="icon-tag"></i></a>
                    <? endif; ?>
                </span>
            </li>
            <li>
                <span class="row_title">Longest Kill Distance</span>
                <span class="row_data">
                    <?= number_format($data['BestGameKillDistance']); ?><abbr title="meters">m</abbr>
                    <? if (BRANCH): ?>
                        <a class="hidden-phone hidden-table"
                           href="http://branchapp.co/halo4/servicerecord/<?= $data['BranchGamertag']; ?>/match/<?= $data['BestGameKillDistanceGameId']; ?>/"
                           data-toggle="tooltip" data-html="true" title="View Game on Branch"
                           rel="tooltip" data-placement="top"><i class="icon-tag"></i></a>
                    <? endif; ?>
                </span>
            </li>
        </ul>
    </div>
</div>
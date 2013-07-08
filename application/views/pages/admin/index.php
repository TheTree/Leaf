<div class="row-fluid">
    <h3>Stats</h3>
    <div class="well well-large">
        <ul class="unstyled">
            <? if(isset($acp[0])): ?>
                <li><strong>Total Accounts</strong> <span class="badge badge-info"><?= $acp[0]; ?></span></li><br />
            <? endif; ?>
            <? if (isset($acp[BOOSTING_PLAYER])): ?>
                <li><strong>Boosted Accounts</strong> <span class="badge badge-warning"><?= $acp[BOOSTING_PLAYER]; ?></span></li><br />
            <? endif; ?>
            <? if (isset($acp[CHEATING_PLAYER])): ?>
                <li><strong>Cheated Accounts</strong> <span class="badge badge-important"><?= $acp[CHEATING_PLAYER]; ?></span></li><br />
            <? endif; ?>
            <? if (isset($acp[MISSING_PLAYER])): ?>
                <li><strong>Missing Accounts</strong> <span class="badge badge-inverse"><?= $acp[MISSING_PLAYER]; ?></span></li><br />
            <? endif; ?>
        </ul>
    </div>
</div>
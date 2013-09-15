<div class="row">
    <legend>Site Stats</legend>
    <div class="well well-lg">
        <ul class="list-unstyled">
            <? if(isset($acp[-1])): ?>
                <li><strong>Total Accounts</strong> <span class="label label-info"><?= number_format($acp[-1]); ?></span></li><br />
            <? endif; ?>
            <? if(isset($acp[0])): ?>
                <li><strong>Regular Accounts</strong> <span class="label label-success"><?= $acp[0]; ?></span></li><br />
            <? endif; ?>
            <? if (isset($acp[BOOSTING_PLAYER])): ?>
                <li><strong>Boosted Accounts</strong> <span class="label label-warning"><?= $acp[BOOSTING_PLAYER]; ?></span></li><br />
            <? endif; ?>
            <? if (isset($acp[CHEATING_PLAYER])): ?>
                <li><strong>Cheated Accounts</strong> <span class="label label-important"><?= $acp[CHEATING_PLAYER]; ?></span></li><br />
            <? endif; ?>
            <? if (isset($acp[MISSING_PLAYER])): ?>
                <li><strong>Missing Accounts</strong> <span class="label label-inverse"><?= $acp[MISSING_PLAYER]; ?></span></li><br />
            <? endif; ?>
        </ul>
    </div>
    <legend>API Stats</legend>
    <div class="well well-lg">
        <? if (is_array($acp['api']) && count($acp['api']) > 0): ?>
            <ul class="list-unstyled">
                <? foreach($acp['api'] as $pair): ?>
                    <li><strong>Version <?= $pair['_id']; ?></strong> - <span class="label label-info"><?= number_format($pair['amt']); ?></span></li>
                <? endforeach; ?>
            </ul>
        <? endif; ?>
    </div>
</div>
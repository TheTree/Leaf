<div class="row-fluid">
    <h3>Stats</h3>
    <div class="well well-large">
        <ul class="unstyled">
            <? if(isset($acp[-1])): ?>
                <li><strong>Total Accounts</strong> <span class="badge badge-info"><?= number_format($acp[-1]); ?></span></li><br />
            <? endif; ?>
            <? if(isset($acp[0])): ?>
                <li><strong>Regular Accounts</strong> <span class="badge badge-success"><?= $acp[0]; ?></span></li><br />
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
    <h3>API Version Stats</h3>
    <div class="well well-large">
        <? if (is_array($acp['api']) && count($acp['api']) > 0): ?>
            <ul class="unstyled">
                <? foreach($acp['api'] as $pair): ?>
                    <li><strong>Version <?= $pair['_id']; ?></strong> - <span class="badge badge-info"><?= number_format($pair['amt']); ?></span></li>
                <? endforeach; ?>
            </ul>
        <? endif; ?>
    </div>
</div>
<? if ($data['InactiveCounter'] >= INACTIVE_COUNTER): ?>
    <div class="alert alert-info"><strong>Warning </strong><?= $data['Gamertag']; ?> is inactive for not playing :/</div>
<? endif; ?>
<? if (isset($data['Status']) && ($data['Status'] == 1 || $data['Status'] == 2)): ?>
    <div class="alert alert-danger"><strong>Warning </strong><?= $data['Gamertag']; ?> has been marked as a <?= $this->library->get_banned_name($data['Status']); ?>.</div>
<? endif; ?>

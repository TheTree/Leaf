<? if (isset($data[H4::STATUS]) && ($data[H4::STATUS] == 1 || $data[H4::STATUS] == 2)): ?>
    <div class="alert alert-danger"><strong>Warning </strong><?= $data[H4::STATUS]; ?> has been marked as a <?= $this->library->get_banned_name($data[H4::STATUS]); ?>.</div>
<? endif; ?>

<? if ($data[H4::NEXT_RANK_START_XP] == 0): ?>
    <? if ($data[H4::RANK] == $this->config->item('h4_max_halo_rank')): ?>
        All Specializations Completed.
    <? else: ?>
        Specialization: <?= $data[H4::SPECIALIZATION]; ?> Completed
    <? endif; ?>
<? else: ?>
    <?= number_format($data[H4::NEXT_RANK_START_XP] - $data[H4::XP]); ?> XP till next level (<?= round(floatval(($data[H4::XP] - $data[H4::RANK_START_XP]) /
                                                                                                                ($data[H4::NEXT_RANK_START_XP] - $data[H4::RANK_START_XP])) * 100); ?>% complete)
<? endif; ?>
<div class="progress progress-striped">
    <? if ($data[H4::NEXT_RANK_START_XP] == 0): ?>
        <div class="progress-bar progress-bar-info" style="width: 100%;"
             role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
    <? else: ?>
        <div class="progress-bar progress-bar-info"
             style="width: <?= floatval(($data[H4::XP] - $data[H4::RANK_START_XP]) / ($data[H4::NEXT_RANK_START_XP] - $data[H4::RANK_START_XP])) * 100; ?>%;"
             role="progressbar"
             aria-valuenow="<?= floatval(($data[H4::XP] - $data[H4::RANK_START_XP]) / ($data[H4::NEXT_RANK_START_XP] - $data[H4::RANK_START_XP])) * 100; ?>"
             aria-valuemin="0" aria-valuemax="100"></div>
    <? endif; ?>
</div>
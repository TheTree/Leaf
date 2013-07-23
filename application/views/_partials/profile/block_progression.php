<? if ($data[H4::NEXT_RANK_START_XP] == 0): ?>
    <? if ($data[H4::RANK] == $this->config->item('h4_max_halo_rank')): ?>
        All Specializations Completed.
    <? else: ?>
        Specialization: <?= $data[H4::SPECIALIZATION]; ?> Completed
    <? endif; ?>
<? else: ?>
    <?= number_format($data['NextRankStartXP'] - $data['Xp']); ?> XP till next level (<?= round(floatval(($data['Xp'] - $data['RankStartXP']) / ($data['NextRankStartXP'] - $data['RankStartXP'])) * 100); ?>% complete)
<? endif; ?>
<div class="progress progress-striped">
    <? if ($data['NextRankStartXP'] == 0): ?>
        <div class="bar" style="width: 100%;"></div>
    <? else: ?>
        <div class="bar" style="width: <?= floatval(($data['Xp'] - $data['RankStartXP']) / ($data['NextRankStartXP'] - $data['RankStartXP'])) * 100; ?>%;"></div>
    <? endif; ?>
</div>
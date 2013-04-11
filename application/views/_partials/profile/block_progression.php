<? if ($data['NextRankStartXP'] == 0): ?>
    <? if ($data['Rank'] == MAX_HALO_RANK): ?>
        All Specializations Completed.
    <? else: ?>
        Specialization: <?= $data['Specialization']; ?> Completed
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
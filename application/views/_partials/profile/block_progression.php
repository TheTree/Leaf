<? if ($data['NextRankStartXP'] == 0): ?>
    Specialization: <?= $data['Specialization']; ?> Completed
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
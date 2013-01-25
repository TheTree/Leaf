<div class="row-fluid">
    <div class="span7">
        <h2>yer</h2>
        <div class="well well-large">
            test
        </div>
    </div>
    <div class="span5">
        <h3>Challenges</h3>
        <div class="well well-large">
            <? foreach($challenges['Challenges'] as $challenge): ?>
                <strong><?= $challenge['Name']; ?></strong>
                <span class="badge badge-info"><?= $challenge['CategoryName']; ?></span> 
                <span class="badge badge-success">Xp: <?= number_format($challenge['XpReward']); ?></span><br />
                <i><?= $challenge['Description']; ?></i><br />
                <small>Time left: <?= $this->library->time_duration($challenge['EndDate'] - time()); ?></small>
                <br /><br />
            <? endforeach; ?>
        </div>
    </div>
</div>
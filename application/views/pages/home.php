<div class="row-fluid">
    <div class="span7">
        <div class="well well-large">
            <p class="lead">
                Welcome to Leaf.
            </p><p>Halo 4 Stats that don't suck.</p>
        </div>
        <div class="well well-large">
            <?= form_open('home/index', array('class' => 'form-search')); ?>
            <div class="control-group <? if (form_error('gamertag') != null): ?>error<? endif; ?>">
                <input type="text" class="input-xlarge" name="gamertag" id="gamertag" placeholder="Enter your gamertag">
                <button type="submit" class="btn btn-primary">Load Stats</button>
                <? if (form_error('gamertag') != null): ?><br /><span class="help-inline"><?= form_error('gamertag') ?></span><? endif; ?>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
    <div class="span5">
        <h3>Challenges</h3>
        <div class="well well-large">
            <? foreach($challenges['Challenges'] as $challenge): ?>
                <strong><?= $challenge['Name']; ?></strong>
                <span class="badge badge-info"><?= $challenge['CategoryName']; ?></span> 
                <span class="badge badge-success">XP: <?= number_format($challenge['XpReward']); ?></span><br />
                <i><?= $challenge['Description']; ?></i><br />
                <small>Time left: <?= $this->library->time_duration($challenge['EndDate'] - time()); ?></small>
                <br /><br />
            <? endforeach; ?>
        </div>
    </div>
</div>
<div class="row-fluid">
    <div class="span7">
        <div class="well well-large">
            <p class="lead">
                Welcome to Leaf.
            </p><p>Halo 4 Stats that don't suck.</p>
        </div>
        <? if ($recent_news != false): ?>
            <blockquote><p>
                <?= character_limiter($recent_news['text'], 200); ?>
            </p>
            <small><a href="<?= base_url('news'); ?>">Posted by <?= $recent_news['author']; ?> at <cite title="Date"><?= date("F j, Y, g:i a", $recent_news['date_posted']); ?></cite></a></small>
            </blockquote>
        <? endif; ?>
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
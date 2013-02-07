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
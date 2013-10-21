<? if ($last_compared != FALSE): ?>
    <legend>Last Comparison</legend>
    <div class="well well-lg">
        <?= $last_compared['you_badge']; ?><span class="label label-default"><?= $last_compared['you_tag']; ?></span>
        <a href="<?= base_url('h4/record/' . $last_compared['you_seo']); ?>"><?= $last_compared['you_gt']; ?></a>
        &nbsp;<?= $last_compared['TweetWord']; ?>&nbsp;
        <?= $last_compared['them_badge']; ?><span class="label label-default"><?= $last_compared['them_tag']; ?></span>
        <a href="<?= base_url('h4/record/' . $last_compared['them_seo']); ?>"><?= $last_compared['them_gt']; ?></a>
        <span class="label label-success"><?= $last_compared['you_pts']; ?></span> to
        <span class="label label-success"><?= $last_compared['them_pts']; ?></span> points.
        <br /><br />
        <div class="pull-right">
            <small><a href="<?= base_url('h4/compare/' . $last_compared['you_seo'] . "/" . $last_compared['them_seo']); ?>">view comparison.</a></small>
        </div>
    </div>
<? endif; ?>
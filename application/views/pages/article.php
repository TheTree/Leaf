<? if ($article == false): ?>
    <div class="alert alert-info"><strong>Hey!</strong> We couldn't find this news article. </div>
<? else: ?>
    <blockquote class="pull-left">
        <p><?= $article['text']; ?></p>
        <small>Posted by <?= $article['author']; ?> at <cite title="Date"><?= date("F j, Y, g:i a", $article['date_posted']); ?></cite></small>
    </blockquote>
<? endif; ?>
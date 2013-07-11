<br />
<? if ($article == false): ?>
    <div class="alert alert-info"><strong>Hey!</strong> We couldn't find this news article. </div>
<? else: ?>
    <? if ($article['title'] != ""): ?>
        <h3><?= $article['title']; ?></h3>
    <? endif ?>
    <blockquote class="pull-left">
        <p><?= $article['text']; ?></p>
        <small>Posted by <?= $article['author']; ?> at <time datetime="<?= date('Y-m-d', $article['date_posted']); ?>"><?= date("F j, Y, g:i a", $article['date_posted']); ?></time></small>
    </blockquote>
<? endif; ?>

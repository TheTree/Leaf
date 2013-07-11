<div class="page-header">
    <h1>Leaf News</h1>
</div>
<div class="row-fluid">
<? if (is_array($news) && count($news) > 0): ?>
    <? foreach ($news as $article): ?>
        <blockquote class="pull-left">
            <p><?= character_limiter($article['text']); ?>
            <? if (strlen($article['text']) > 500): ?>
                <a href="<?= base_url('news/view/' . $article['id']); ?>">Read Full Story </a>
            <? endif; ?>
            </p>
            <small>Posted by <?= $article['author']; ?> at <time datetime="<?= date('Y-m-d', $article['date_posted']); ?>"><?= date("F j, Y, g:i a", $article['date_posted']); ?></time></small>
        </blockquote>
    <? endforeach; ?>
</div>
<?= $pagination; ?>
<? else: ?>
    <div class="alert alert-info"><strong>Sorry</strong> No news stories were found :( </div>
<? endif; ?>
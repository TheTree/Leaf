<div class="page-header">
    <h1>Leaf News</h1>
</div>
<div class="row">
<? if (is_array($news) && count($news) > 0): ?>
    <? foreach ($news as $article): ?>
        <article>
            <blockquote class="w100">
                <? if ($article['title'] != ""): ?>
                    <h3><a class="colur" href="<?= base_url('news/view/' . $article['slug']); ?>"><?= $article['title']; ?></a></h3>
                <? endif; ?>
                <p><?= character_limiter($article['text']); ?>
                <? if (strlen($article['text']) > 500): ?>
                    <? if ($article['slug'] != ""): ?>
                        <a href="<?= base_url('news/view/' . $article['slug']); ?>">Read Full Story </a>
                    <? else: ?>
                        <a href="<?= base_url('news/view/' . $article['id']); ?>">Read Full Story </a>
                    <? endif; ?>
                <? endif; ?>
                </p>
                <? if ($article['slug'] != ""): ?>
                    <small>
                        <a href="<?= base_url('news/view/' . $article['slug']); ?>">
                            Posted by <?= $article['author']; ?> at
                            <time datetime="<?= date('Y-m-d', $article['date_posted']); ?>"><?= date("F j, Y, g:i a", $article['date_posted']); ?>
                            </time>
                        </a>
                    </small>
                    <small>
                        <a data-disqus-identifier="<?= $article['slug']; ?>" href="<?= base_url('news/view/' . $article['slug']); ?>#disqus_thread"></a>
                    </small>
                <? else: ?>
                    <a href="<?= base_url('news/view/' . $article['id']); ?>"><small>Posted by <?= $article['author']; ?> at <time datetime="<?= date('Y-m-d', $article['date_posted']); ?>"><?= date("F j, Y, g:i a", $article['date_posted']); ?></time></small></a>
                <? endif; ?>
            </blockquote>
        </article>
        <div class="clearfix">&nbsp;</div>
    <? endforeach; ?>
</div>
<script type="text/javascript">
    var disqus_shortname = 'leafapp';
    (function () {
        var s = document.createElement('script'); s.async = true;
        s.type = 'text/javascript';
        s.src = 'http://' + disqus_shortname + '.disqus.com/count.js';
        (document.getElementsByTagName('HEAD')[0] || document.getElementsByTagName('BODY')[0]).appendChild(s);
    }());
</script>
<?= $pagination; ?>
<? else: ?>
    <div class="alert alert-info"><strong>Sorry</strong> No news stories were found :( </div>
<? endif; ?>
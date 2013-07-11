<br />
<div class="row-fluid">
    <div class="span12">
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
    </div>
</div>
<div class="row-fluid">
    <div class="pagination-centered">
        <div id="disqus_thread"></div>
        <script type="text/javascript">
            var disqus_shortname = 'leafapp';
            var disqus_identifier = '<?= ($article['slug'] == "") ? $article['id'] . "-news" : $article['slug']; ?>';

            (function() {
                var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
                dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
                (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
            })();
        </script>
        <noscript>Please enable JavaScript to view the <a href="http://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
        <a href="http://disqus.com" class="dsq-brlink">comments powered by <span class="logo-disqus">Disqus</span></a>
    </div>
</div>

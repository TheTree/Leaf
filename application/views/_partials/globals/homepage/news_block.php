<? if ($recent_news != false): ?>
    <article>
        <blockquote><p>
                <?= character_limiter($recent_news['text'], 200); ?>
            </p>
            <small>
                <a href="<?= base_url('news'); ?>">Posted by <?= $recent_news['author']; ?> at <time datetime="<?= date('Y-m-d', $recent_news['date_posted']); ?>"><?= date("F j, Y, g:i a", $recent_news['date_posted']); ?></time></a>
            </small>
            <small>
                <a href="<?= base_url('news/view/' . $recent_news['slug']); ?>#disqus_thread" data-disqus-identifier="<?= $recent_news['slug']; ?>"></a>
            </small>
        </blockquote>
    </article>
<? endif; ?>
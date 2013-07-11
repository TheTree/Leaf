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
<div class="well well-large">
    <?= form_open('home/index', array('class' => 'form-search')); ?>
    <div class="control-group <? if (form_error('gamertag') != null): ?>error<? endif; ?>">
        <input type="text" class="input-xlarge ajax-typeahead" autocomplete="off" name="gamertag" id="gamertag" placeholder="Enter your gamertag">
        <button type="submit" class="btn btn-primary">Load Stats</button>
        <? if (form_error('gamertag') != null): ?><br /><span class="help-inline"><?= form_error('gamertag') ?></span><? endif; ?>
    </div>
    <?= form_close(); ?>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#gamertag').typeahead({
                source: function(query, process) {
                    return $.post('<?= base_url('ajax/gt/'); ?>' + "/" + query, {}, function(data) {
                        return process(data);
                    });
                }
            });
        });
    </script>
    <script type="text/javascript">
        var disqus_shortname = 'leafapp';
        (function () {
            var s = document.createElement('script'); s.async = true;
            s.type = 'text/javascript';
            s.src = 'http://' + disqus_shortname + '.disqus.com/count.js';
            (document.getElementsByTagName('HEAD')[0] || document.getElementsByTagName('BODY')[0]).appendChild(s);
        }());
    </script>
</div>
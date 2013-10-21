<div class="well well-lg">
    <?= form_open('h4', array('class' => 'form-inline', 'role' => 'form')); ?>
    <div class="input-group <? if (form_error('gamertag') != null): ?>has-error<? endif; ?>">
        <input type="text" class="form-control" name="gamertag" id="gamertag" placeholder="Enter your gamertag">
        <span class="input-group-btn btn-group">
                <button type="submit" class="btn btn-primary" type="button">Load Stats</button>
        </span>
    </div>
    <? if (form_error('gamertag') != null): ?>
        <span class="help-block"><?= form_error('gamertag') ?></span>
    <? endif; ?>
    <?= form_close(); ?>
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
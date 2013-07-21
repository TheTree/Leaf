<div class="well well-large">
    <?= form_open('h4', array('class' => 'form-search')); ?>
    <div class="control-group <? if (form_error('gamertag') != null): ?>error<? endif; ?>">
        <input type="text" class="input-xlarge ajax-typeahead" autocomplete="off" name="gamertag" id="gamertag" placeholder="Enter your gamertag">
        <button type="submit" class="btn btn-primary">Load Stats</button>
        <? if (form_error('gamertag') != null): ?>
            <span class="help-block"><?= form_error('gamertag') ?></span>
        <? endif; ?>
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
<?= form_open('backstage/badges/create', array('class' => 'form-vertical', 'role' => 'form')); ?>
<?= form_hidden('submitted', TRUE); ?>
    <fieldset>
        <legend>Create Badge</legend>
        <div class="form-group <? if (form_error('gamertag') != NULL): ?>error<? endif; ?>">
            <label for="gamertag">Gamertag</label>
            <div class="controls">
                <input class="form-control input-xlarge ajax-typeahead" type="text" name="gamertag" id="gamertag" value="<?= set_value('gamertag'); ?>" autocomplete="off" autofocus="yes">
                <? if (form_error('gamertag') != NULL): ?><span class="help-inline"><?= form_error('gamertag') ?></span><? endif; ?>
            </div>
        </div>
        <div class="form-group <? if (form_error('badge') != NULL): ?>error<? endif; ?>">
            <label for="author">Badge</label>
            <div class="controls">
                <input class="form-control input-xlarge" type="text" name="badge" id="badge" value="<?= set_value('badge'); ?>">
                <? if (form_error('badge') != NULL): ?><span class="help-inline"><?= form_error('badge') ?></span><? endif; ?>
            </div>
        </div>
        <div class="form-group <? if (form_error('type') != NULL): ?>error<? endif; ?>">
            <label for="type">Type</label>
            <div class="controls">
                <input class="form-control input-xlarge" type="text" name="type" id="type" value="<?= set_value('type'); ?>">
                <? if (form_error('badge') != NULL): ?>
                    <span class="help-inline"><?= form_error('type') ?></span>
                <? else: ?>
                    <span class="help-block">Accepted values: success,warning,important,info,inverse</span>
                <? endif; ?>
            </div>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn">Add Badge</button>
        </div>
    </fieldset>
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
<?= form_close(); ?>
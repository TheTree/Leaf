<?= form_open('backstage/news/create', array('class' => 'form-horizontal')); ?>
<?= form_hidden('submitted', TRUE); ?>
    <fieldset>
        <legend>Create News Story</legend>
        <div class="control-group <? if (form_error('author') != null): ?>error<? endif; ?>">
            <label class="control-label" for="author">Author </label>
            <div class="controls">
                <input class="input-xlarge" type="text" name="author" id="author" value="<?= set_value('author'); ?>">
                <? if (form_error('author') != null): ?><span class="help-inline"><?= form_error('author') ?></span><? endif; ?>
            </div>
        </div>
        <div class="control-group <? if (form_error('article') != null): ?>error<? endif; ?>">
            <label class="control-label" for="article">Text Body </label>
            <div class="controls">
                <textarea class="span4" name="article" rows="8" cols="15" maxlength=5000><?= set_value('article'); ?></textarea>
                <? if (form_error('article') != null): ?><span class="help-inline"><?= form_error('article') ?></span><? endif; ?>
            </div>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn">Add News Story</button>
        </div>
    </fieldset>
<?= form_close(); ?>
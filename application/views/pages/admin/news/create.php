<?= form_open('backstage/news/create', array('class' => 'form-vertical', 'role' => 'form')); ?>
<?= form_hidden('submitted', TRUE); ?>
    <fieldset>
        <legend>Create News Story</legend>
        <div class="form-group <? if (form_error('title') != null): ?>error<? endif; ?>">
            <label for="title">Title</label>
            <div class="controls">
                <input class="form-control input-xlarge" type="text" name="title" id="title" value="<?= set_value('title'); ?>">
                <? if (form_error('title') != null): ?><span class="help-inline"><?= form_error('title') ?></span><? endif; ?>
            </div>
        </div>
        <div class="form-group <? if (form_error('author') != null): ?>error<? endif; ?>">
            <label for="author">Author</label>
            <div class="controls">
                <input class="form-control input-xlarge" type="text" name="author" id="author" value="<?= set_value('author'); ?>">
                <? if (form_error('author') != null): ?><span class="help-inline"><?= form_error('author') ?></span><? endif; ?>
            </div>
        </div>
        <div class="form-group <? if (form_error('article') != null): ?>error<? endif; ?>">
            <label for="article">Text Body</label>
            <div class="controls">
                <textarea class="form-control span4" name="article" rows="8" cols="15" maxlength=5000><?= set_value('article'); ?></textarea>
                <? if (form_error('article') != null): ?><span class="help-inline"><?= form_error('article') ?></span><? endif; ?>
            </div>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn">Add News Story</button>
        </div>
    </fieldset>
<?= form_close(); ?>
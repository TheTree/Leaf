<?= form_open('news/create'); ?>
<fieldset>
    <legend>Create News Story</legend>
    <div class="control-group <?php if (form_error('author') != null): ?>error<?php endif; ?>">
        <label class="control-label" for="author">Author </label>
        <div class="controls">
            <input type="text" name="author" id="author" value="<?= set_value('author'); ?>">
            <?php if (form_error('author') != null): ?><span class="help-inline"><?= form_error('author') ?></span><?php endif; ?>
        </div>
    </div>
    <div class="control-group <?php if (form_error('article') != null): ?>error<?php endif; ?>">
        <label class="control-label" for="article">Text Body: </label>
        <div class="controls">
            <textarea class="span4" name="article" rows="5" cols="10" maxlength=5000><?= set_value('article'); ?></textarea>
            <?php if (form_error('article') != null): ?><span class="help-inline"><?= form_error('article') ?></span><?php endif; ?>
        </div>
    </div>
    <div class="control-group <?php if (form_error('pass') != null): ?>error<?php endif; ?>">
        <label class="control-label" for="pass">Orange? Green? </label>
        <div class="controls">
            <input type="password" name="pass" id="pass" value="<?= set_value('pass'); ?>">
            <?php if (form_error('pass') != null): ?><span class="help-inline"><?= form_error('pass') ?></span><?php endif; ?>
        </div>
    </div>
    <div class="form-actions">
        <button type="submit" class="btn">Add News Story</button>
    </div>
</fieldset>
<?= form_close(); ?>
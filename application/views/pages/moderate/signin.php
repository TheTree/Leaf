<div class="row-fluid">
    <div class="span6 offset3">
        <?= form_open('moderate/home/login'); ?>
        <fieldset>
            <legend>Sign In</legend>
            <div class="control-group <?php if (form_error('username') != null): ?>error<?php endif; ?>">
                <label class="control-label" for="author">Username </label>
                <div class="controls">
                    <input type="text" name="username" id="username" value="<?= set_value('username'); ?>">
                    <?php if (form_error('username') != null): ?><span class="help-inline"><?= form_error('username') ?></span><?php endif; ?>
                </div>
            </div>
            <div class="control-group <?php if (form_error('password') != null): ?>error<?php endif; ?>">
                <label class="control-label" for="pass">Password</label>
                <div class="controls">
                    <input type="password" name="password" id="password">
                    <?php if (form_error('password') != null): ?><span class="help-inline"><?= form_error('password') ?></span><?php endif; ?>
                </div>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn">Sign In</button>
            </div>
        </fieldset>
        <?= form_close(); ?>
    </div>
</div>
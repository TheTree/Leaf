<div class="row-fluid">
    <div class="span6 offset3">
        <?= form_open('backstage', array('class' => 'form-horizontal')); ?>
        <fieldset>
            <legend>Welcome to the Sandbox</legend>
            <div class="control-group <? if (form_error('username') != null): ?>error<? endif; ?>">
                <label class="control-label" for="author">Username </label>
                <div class="controls">
                    <input type="text" name="username" id="username" value="<?= set_value('username'); ?>">
                    <? if (form_error('username') != null): ?><span class="help-inline"><?= form_error('username') ?></span><? endif; ?>
                </div>
            </div>
            <div class="control-group <? if (form_error('password') != null): ?>error<? endif; ?>">
                <label class="control-label" for="pass">Password</label>
                <div class="controls">
                    <input type="password" name="password" id="password">
                    <? if (form_error('password') != null): ?><span class="help-inline"><?= form_error('password') ?></span><? endif; ?>
                </div>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn">Sign In</button>
            </div>
            <span class="badge badge-inverse">Pikmin 3 ♥</span>
        </fieldset>
        <?= form_close(); ?>
    </div>
</div>
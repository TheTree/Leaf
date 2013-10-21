<div class="row">
    <div class="col-md-offset-3 col-md-6">
        <?= form_open('backstage', array('class' => 'form-horizontal', 'role' => 'form')); ?>
            <legend>Welcome to the Sandbox</legend>
            <div class="form-group <? if (form_error('username') != null): ?>error<? endif; ?>">
                <label class="control-label" for="author">Username </label>
                <div class="col-lg-12">
                    <input class="form-control" type="text" name="username" id="username" value="<?= set_value('username'); ?>">
                    <? if (form_error('username') != null): ?><span class="help-inline"><?= form_error('username') ?></span><? endif; ?>
                </div>
            </div>
            <div class="form-group <? if (form_error('password') != null): ?>error<? endif; ?>">
                <label class="control-label" for="pass">Password</label>
                <div class="col-lg-12">
                    <input type="password" class="form-control" name="password" id="password">
                    <? if (form_error('password') != null): ?><span class="help-inline"><?= form_error('password') ?></span><? endif; ?>
                </div>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Sign In</button>
            </div>
            <span class="badge badge-inverse">Pikmin 3 ♥</span>
        <?= form_close(); ?>
    </div>
</div>
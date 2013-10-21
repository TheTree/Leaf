<h3>API Keys</h3>
<? if (isset($key_msg) && strlen($key_msg) > 5): ?>
    <div class="alert alert-info">
        <p><?= $key_msg; ?></p>
    </div>
    <script type="text/javascript">
        $(".alert").fadeOut(3000 );
    </script>
<? endif; ?>
<? if (isset($keys) && count($keys) > 0): ?>
    <table class="table table-hover">
        <thead>
        <tr>
            <th>User</th>
            <th>Key</th>
            <th>Last API Hit</th>
            <th>Delete</th>
        </tr>
        </thead>
        <tbody>
        <? foreach($keys as $key): ?>
            <tr>
                <td><?= $key['user']; ?></td>
                <td><?= $key['pass']; ?></td>
                <td>
                    <? if ($key['last_hit'] == 0): ?>
                        <span class="label label-warning">not used yet</span>
                    <? else: ?>
                        <?= timespan($key['last_hit']); ?>
                    <? endif; ?>
                </td>
                <td>
                    <a href="<?= base_url('backstage/key_delete/' . intval($key['id'])); ?>" class="btn btn-sm btn-danger"  onclick="return confirm('CONFIRM: API Key will cease to function. Are you sure?');" >Delete</a>
                </td>
            </tr>
        <? endforeach; ?>
        </tbody>
    </table>
<? else: ?>
    <div class="alert alert-info">
        <strong>oooh </strong> We have no api keys.
    </div>
<? endif; ?>

<h4>Create API Key</h4>
<div class="well well-lg">
    <?= form_open('backstage/keys', array('class' => 'form-horizontal', 'role' => 'form')); ?>
        <?= form_hidden('submitted', TRUE); ?>
        <div class="form-group <? if (form_error('user') != null): ?>has-error<? endif; ?>">
            <div class="input-group">
                <input class="form-control col-lg-11 input-xlarge" type="text" name="user" id="user" placeholder="user">
                <span class="input-group-btn">
                    <button class="btn btn-primary" type="submit">Add key</button>
                </span>
            </div>
            <? if (form_error('user') != null): ?><span class="help-block"><?= form_error('user') ?></span><? endif; ?>
        </div>
    <?= form_close(); ?>
</div>

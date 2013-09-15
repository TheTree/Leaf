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
                        <span class="badge badge-warning">not used yet</span>
                    <? else: ?>
                        <?= timespan($key['last_hit']); ?>
                    <? endif; ?>
                </td>
                <td>
                    <a href="<?= base_url('backstage/key_delete/' . intval($key['id'])); ?>" class="btn btn-small btn-danger"  onclick="return confirm('CONFIRM: API Key will cease to function. Are you sure?');" >Delete</a>
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
    <?= form_open('backstage/keys', array('class' => 'form-horizontal')); ?>
        <?= form_hidden('submitted', TRUE); ?>
        <div class="control-group <? if (form_error('user') != null): ?>error<? endif; ?>">
            <div class="controls">
                <div class="input-append">
                    <input class="input-xxlarge" type="text" name="user" id="user" placeholder="user">
                    <button class="btn btn-primary" type="submit">Add key</button>
                </div>
                <? if (form_error('user') != null): ?><span class="help-inline"><?= form_error('user') ?></span><? endif; ?>
            </div>
        </div>

    <?= form_close(); ?>
</div>

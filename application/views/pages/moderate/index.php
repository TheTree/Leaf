<div class="row-fluid">
    <div class="hero-unit">
        <h1>343 Guilty Spark</h1>
        <br />
        <p>immma charging mah lazor</p>
    </div>

    <? if ($banned_users != false): ?>
        <h2>Recently Banned</h2>
        <div class="well">
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>Gamertag</th>
                    <th>Why</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <? $x = 1; foreach ($banned_users as $user): ?>
                    <tr>
                        <td><a href="<?= base_url('gt/' . $user['SeoGamertag']); ?>"><?= $user['Gamertag']; ?></a></td>
                        <td><?= $this->library->get_banned_type($user['Status']); ?></td>
                        <td></td>
                    </tr>
                    <? $x++; endforeach; ?>
                </tbody>
            </table>
        </div>
    <? endif; ?>
    <? if ($flagged_users != false): ?>
        <h2>Recently Flagged</h2>
        <div class="well">
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>Gamertag</th>
                    <th>Times Reported</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <? $x = 1; foreach ($flagged_users as $user): ?>
                    <tr>
                        <td><a href="<?= base_url('gt/' . $user['SeoGamertag']); ?>"><?= $user['Gamertag']; ?></a></td>
                        <td><?= number_format($user['amt']); ?></td>
                        <td><?php if ($mod): ?>
                             <a class="btn btn-small btn-danger" href="<?= base_url('guilty_spark/mod/' . $user['SeoGamertag']  . "/" . CHEATING_PLAYER); ?>">Mark as Cheater</a>
                             <a class="btn btn-small btn-warning" href="<?= base_url('guilty_spark/mod/' . $user['SeoGamertag']  . "/" . BOOSTING_PLAYER); ?>">Mark as Booster</a>
                             <a class="btn btn-small" href="<?= base_url('guilty_spark/mod/' . $user['SeoGamertag']  . "/" . 0); ?>">Neither</a>
                            <? else: ?>
                            <? endif; ?></td>
                    </tr>
                    <? $x++; endforeach; ?>
                </tbody>
            </table>
        </div>
    <? endif; ?>
</div>
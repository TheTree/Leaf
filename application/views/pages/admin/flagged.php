<? if (isset($flagged_users)): ?>
    <h2>Recently Flagged</h2>
    <? if (is_array($flagged_users) && count($flagged_users) > 0): ?>
        <table class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>Gamertag</th>
                <th>Times Reported</th>
                <th>How should we deal with these boys?</th>
            </tr>
            </thead>
            <tbody>
            <? $x = 1; foreach ($flagged_users as $user): ?>
                <tr>
                    <td><a href="<?= base_url('gt/' . $user['SeoGamertag']); ?>"><?= $user['Gamertag']; ?></a></td>
                    <td><?= number_format($user['amt']); ?></td>
                    <td>
                        <a class="btn btn-small btn-danger" href="<?= base_url('backstage/flagged/mod/' . $user['SeoGamertag']  . "/" . CHEATING_PLAYER); ?>">Mark as Cheater</a>
                        <a class="btn btn-small btn-warning" href="<?= base_url('backstage/flagged/mod/' . $user['SeoGamertag']  . "/" . BOOSTING_PLAYER); ?>">Mark as Booster</a>
                        <a class="btn btn-small" href="<?= base_url('backstage/flagged/mod/' . $user['SeoGamertag']  . "/" . 0); ?>">Neither</a>
                    </td>
                </tr>
                <? $x++; endforeach; ?>
            </tbody>
        </table>
    <? else: ?>
        <div class="alert alert-success">
            <strong>Congrats! </strong> There are 0 flagged players :)
        </div>
    <? endif; ?>
<? endif; ?>
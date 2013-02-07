<? if ($recent_players != false): ?>
        <strong>New Accounts</strong><br />
        <div class="well">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Gamertag</th>
                        <th>Rank</th>
                    </tr>
                </thead>
                <tbody>
                    <? foreach ($recent_players as $item): ?>
                        <tr>
                            <td><a href="<?= base_url('gt/' . str_replace(" ", "_", $item['Gamertag'])); ?>"><?= $item['Gamertag']; ?></a> - <small><?= $item['ServiceTag']; ?></small></td>
                            <td><?= number_format($item['Rank']); ?></td>
                        </tr>
                    <? endforeach; ?>
                </tbody>
            </table>
        </div>
<? endif; ?>
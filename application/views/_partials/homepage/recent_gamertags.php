<? if (isset($recent_players) && $recent_players != FALSE): ?>
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
                            <td><a href="<?= base_url('h4/' . str_replace(" ", "_", $item['SeoGamertag'])); ?>"><?= $item['Gamertag']; ?></a> - <small><?= $item['ServiceTag']; ?></small></td>
                            <td>SR-<?= number_format($item['Rank']); ?></td>
                        </tr>
                    <? endforeach; ?>
                </tbody>
            </table>
        </div>
<? endif; ?>
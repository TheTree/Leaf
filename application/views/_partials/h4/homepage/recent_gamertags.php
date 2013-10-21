<? if (isset($recent_players) && $recent_players != FALSE): ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">New Accounts</h3>
        </div>
        <div class="pane-body">

            <table class="table table-responsive table-hover">
                <thead>
                <tr>
                    <th>Gamertag</th>
                    <th>Rank</th>
                </tr>
                </thead>
                <tbody>
                <? foreach ($recent_players as $item): ?>
                    <tr>
                        <td><a href="<?= base_url('h4/record/' . str_replace(" ", "_", $item[H4::SEO_GAMERTAG])); ?>"><?= $item[H4::GAMERTAG]; ?></a> - <small><?= $item[H4::SERVICE_TAG]; ?></small></td>
                        <td>SR-<?= number_format($item[H4::RANK]); ?></td>
                    </tr>
                <? endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<? endif; ?>
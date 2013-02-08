<? if ($stats['TotalKills'] != false): ?>
    <div class="span3">
        <strong>Total Kills</strong><br />
        <div class="well">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th></th>
                        <th>Gamertag</th>
                        <th>Kills</th>
                    </tr>
                </thead>
                <tbody>
                    <? $x = 1; foreach ($stats['TotalKills'] as $item): ?>
                        <tr>
                            <td><?= $this->library->get_trophy($x); ?></td>
                            <td><a href="<?= base_url('gt/' . str_replace(" ", "_", $item['Gamertag'])); ?>"><?= $item['Gamertag']; ?></a></td>
                            <td><?= number_format($item['TotalKills']); ?></td>
                        </tr>
                    <? $x++; endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<? endif; ?>
<? if ($stats['KDRatio'] != false): ?>
    <div class="span3">
        <strong><abbr title="Kill Death">KD</abbr> Ratio</strong><br />
        <div class="well">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th></th>
                        <th>Gamertag</th>
                        <th><abbr title="Kill Death">KD</abbr></th>
                    </tr>
                </thead>
                <tbody>
                    <? $x = 1; foreach ($stats['KDRatio'] as $item): ?>
                        <tr>
                            <td><?= $this->library->get_trophy($x); ?></td>
                            <td><a href="<?= base_url('gt/' . str_replace(" ", "_", $item['Gamertag'])); ?>"><?= $item['Gamertag']; ?></a></td>
                            <td><?= floatval($item['KDRatio']); ?></td>
                        </tr>
                    <? $x++; endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<? endif; ?>
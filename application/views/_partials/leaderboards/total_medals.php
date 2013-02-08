<? if ($stats['TotalMedals'] != false): ?>
    <div class="span3">
        <strong>Total Medals</strong><br />
        <div class="well">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th></th>
                        <th>Gamertag</th>
                        <th>Medals</th>
                    </tr>
                </thead>
                <tbody>
                    <? $x = 1; foreach ($stats['TotalMedals'] as $item): ?>
                        <tr>
                            <td><?= $this->library->get_trophy($x); ?></td>
                            <td><a href="<?= base_url('gt/' . str_replace(" ", "_", $item['Gamertag'])); ?>"><?= $item['Gamertag']; ?></a></td>
                            <td><?= number_format($item['TotalMedalsEarned']); ?></td>
                        </tr>
                    <? $x++; endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<? endif; ?>
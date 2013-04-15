<? if ($stats['Data']['total_deaths'] != false): ?>
        <div class="well">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th></th>
                        <th>Gamertag</th>
                        <th>Deaths</th>
                    </tr>
                </thead>
                <tbody>
                    <? $x = 1; foreach ($stats['Data']['total_deaths'] as $item): ?>
                        <tr>
                            <td><?= $this->library->get_trophy($x); ?></td>
                            <td><a href="<?= base_url('gt/' . str_replace(" ", "_", $item['Gamertag'])); ?>"><?= $item['Gamertag']; ?></a></td>
                            <td><?= number_format($item['TotalDeaths']); ?></td>
                        </tr>
                    <? $x++; endforeach; ?>
                </tbody>
            </table>
        </div>
<? endif; ?>
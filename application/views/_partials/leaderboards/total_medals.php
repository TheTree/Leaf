<? if ($stats['Data']['total_medals'] != false): ?>
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
            <? $x = 1; foreach ($stats['Data']['total_medals'] as $item): ?>
                <tr>
                    <td><?= $this->library->get_trophy($x); ?></td>
                    <td><a href="<?= base_url('gt/' . str_replace(" ", "_", $item['Gamertag'])); ?>">
                            <?= $item['Gamertag']; ?>
                            <? if (isset($item['title'])): ?>
                                - <span class="badge badge-<?= $item['colour']; ?>"><?= $item['title']; ?></span>
                            <? endif; ?>
                        </a></td>
                    <td><?= number_format($item['TotalMedalsEarned']); ?></td>
                </tr>
                <? $x++; endforeach; ?>
            </tbody>
        </table>
    </div>
<? endif; ?>
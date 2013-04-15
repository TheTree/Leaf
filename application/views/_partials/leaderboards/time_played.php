<? if ($stats['Data']['time_played'] != false): ?>
    <div class="well">
    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th></th>
            <th>Gamertag</th>
            <th>Time Played</th>
        </tr>
        </thead>
        <tbody>
        <? $x = 1; foreach ($stats['Data']['time_played'] as $item): ?>
            <tr>
                <td><?= $this->library->get_trophy($x); ?></td>
                <td><a href="<?= base_url('gt/' . str_replace(" ", "_", $item['Gamertag'])); ?>"><?= $item['Gamertag']; ?></a></td>
                <td><?= $this->library->time_duration($item['TotalGameplay'], 'yMdmh'); ?></td>
            </tr>
            <? $x++; endforeach; ?>
        </tbody>
    </table>
<? endif; ?>
<? if ($stats['Data']['challenges_completed'] != false): ?>
    <div class="well">
        <table class="table table-bordered table-striped">
            <thead>
            <tr>
                <th></th>
                <th>Gamertag</th>
                <th>Challenges</th>
            </tr>
            </thead>
            <tbody>
            <? $x = 1; foreach ($stats['Data']['challenges_completed'] as $item): ?>
                <tr>
                    <td><?= $this->library->get_trophy($x); ?></td>
                    <td><a href="<?= base_url('gt/' . str_replace(" ", "_", $item['Gamertag'])); ?>"><?= $item['Gamertag']; ?></a></td>
                    <td><?= number_format($item['TotalChallengesCompleted']); ?></td>
                </tr>
                <? $x++; endforeach; ?>
            </tbody>
        </table>
    </div>
<? endif; ?>
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
                <td><?= $this->h4_lib->get_trophy($x); ?></td>
                <td><a href="<?= base_url('h4/record/' . $item[H4::SEO_GAMERTAG]); ?>">
                        <?= $item[H4::GAMERTAG]; ?>
                        <? if (isset($item[H4::BADGE])): ?>
                            - <span class="badge badge-<?= $item[H4::BADGE_COLOR]; ?>"><?= $item[H4::BADGE]; ?></span>
                        <? endif; ?>
                    </a></td>
                <td><?= $this->utils->time_duration($item[H4::TOTAL_GAMEPLAY], 'yMdmh'); ?></td>
            </tr>
            <? $x++; endforeach; ?>
        </tbody>
    </table>
<? endif; ?>
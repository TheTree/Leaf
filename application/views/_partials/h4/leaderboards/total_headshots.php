<? if ($stats['Data']['headshots_per_game'] != false): ?>
    <div class="well">
        <table class="table table-bordered table-striped">
            <thead>
            <tr>
                <th></th>
                <th>Gamertag</th>
                <th>Headshots per Game</th>
            </tr>
            </thead>
            <tbody>
            <? $x = 1; foreach ($stats['Data']['headshots_per_game'] as $item): ?>
                <tr>
                    <td><?= $this->h4_lib->get_trophy($x); ?></td>
                    <td><a href="<?= base_url('h4/' . $item[H4::SEO_GAMERTAG]); ?>">
                            <?= $item[H4::GAMERTAG]; ?>
                            <? if (isset($item[H4::BADGE])): ?>
                                - <span class="badge badge-<?= $item[H4::BADGE_COLOR]; ?>"><?= $item[H4::BADGE]; ?></span>
                            <? endif; ?>
                        </a></td>
                    <td><?= number_format($item[H4::HEADSHOTS_PER_GAME_RATIO],2); ?></td>
                </tr>
                <? $x++; endforeach; ?>
            </tbody>
        </table>
    </div>
<? endif; ?>
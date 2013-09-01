<br />
<div class="row">
    <div class="col-md-3">
        <div class="">
            <ul class="nav nav-pills nav-stacked nav-sidebar">
                <? foreach($csr_team as $item): ?>
                    <? if (isset($playlists[$item])) : ?>
                        <li class="<?= $this->utils->is_active($item . "_T", "100_I", 3); ?>"><a href="<?= base_url('h4/csr_leaderboards/' . $item . "_T"); ?>"><?= $playlists[$item]['Name'] ?></a></li>
                    <? endif; ?>
                <? endforeach; ?>
                <li class="nav-divider"></li>
                <? foreach($csr_ind as $item): ?>
                    <? if (isset($playlists[$item])) : ?>
                        <li class="<?= $this->utils->is_active($item . "_I", "100_I", 3); ?>"><a href="<?= base_url('h4/csr_leaderboards/' . $item . "_I"); ?>"><?= $playlists[$item]['Name'] ?></a></li>
                    <? endif; ?>
                <? endforeach; ?>
            </ul>
        </div>
        <? if ($my == FALSE): ?>
            <div class="alert alert-info">
                `Star` your gamertag, to get personalized leaderboards!
            </div>
        <? endif; ?>
    </div>
    <div class="col-md-9">
        <? if (count($leaderboards) < 1): ?>
            <div class="alert alert-info">
                <strong>Strange! </strong> We have 0 records for this playlist :(
            </div>
        <? else: ?>
        <legend><span class="label label-info label-static-size"><? if (in_array($playlist, $csr_team)): ?> Team<? else: ?> Individual<? endif; ?></span> <?= $playlist_name; ?></legend>
        <div class="">
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>Place</th>
                    <th>Gamertag</th>
                    <th><abbr title="Competitive Skill Rank">CSR</abbr></th>
                    <th><abbr title="Kill/Death">KD</abbr> Ratio</th>
                </tr>
                </thead>
                <tbody>
                <? $x = 1 * $page; foreach($leaderboards as $player): ?>
                    <tr class="<? if ($player['SeoGamertag'] == $my[H4::SEO_GAMERTAG]): ?>success<? endif; ?>">
                        <td><?= $this->h4_lib->get_trophy($x); ?></td>
                        <td>
                            <a style="color: #000; " href="<?= base_url('h4/record/' . $player['SeoGamertag']); ?>">
                                <?= $player['Gamertag']; ?>
                                <? if (isset($player['title'])): ?>
                                    - <span class="badge badge-<?= $player['colour']; ?>"><?= $player['title']; ?></span>
                                <? endif; ?></a>
                        </td>
                        <td><span class="flair flair-CSR-<?= $player[$playlist]; ?>"></span></td>
                        <td><?= $player['KDRatio']; ?></td>
                    </tr>
                    <? $x++; endforeach; ?>
                <? if (!search_big($leaderboards,$my[H4::GAMERTAG])): ?>
                    <? if (isset($my[$playlist]['Rank']) && $my[$playlist][$playlist] > 0): ?>
                        <tr class="info">
                            <td><?= $this->h4_lib->get_trophy($my[$playlist]['Rank']); ?></td>
                            <td><a style="color: #000; " href="<?= base_url('h4/record/' . $my[H4::SEO_GAMERTAG]); ?>"><?= $my[H4::GAMERTAG]; ?></a></td>
                            <td><span class="flair flair-CSR-<?= $my[$playlist][$playlist]; ?>"></span></td>
                            <td><?= $my[H4::KD_RATIO]; ?></td>
                        </tr>
                    <? endif; ?>
                <? endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <? endif; ?>
</div>
<div class="row-fluid">
    <?= $pagination; ?>
</div>
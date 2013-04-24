<div class="row-fluid">
    <div class="span3">
        <div class="well">
            <ul class="nav nav-list">
                <li class="nav-header">Team CSR</li>
                <li class="divider"></li>
                    <? foreach($csr_team as $item): ?>
                        <? if (isset($playlists[$item])) : ?>
                            <li class="<?= $this->library->is_csr_active($item . "_T"); ?>"><a href="<?= base_url('csr_leaderboards/' . $item . "_T"); ?>"><?= $playlists[$item]['Name'] ?></a></li>
                        <? endif; ?>
                    <? endforeach; ?>
                <li class="nav-header">Individual CSR</li>
                <li class="divider"></li>
                <? foreach($csr_ind as $item): ?>
                    <? if (isset($playlists[$item])) : ?>
                        <li class="<?= $this->library->is_csr_active($item . "_I"); ?>"><a href="<?= base_url('csr_leaderboards/' . $item . "_I"); ?>"><?= $playlists[$item]['Name'] ?></a></li>
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
    <div class="span9">
        <div class="well">
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
                    <tr class="<? if ($player['SeoGamertag'] == $my['SeoGamertag']): ?>success<? endif; ?>">
                        <td><?= $this->library->get_trophy($x); ?></td>
                        <td><a style="color: #000; " href="<?= base_url('gt/' . $player['SeoGamertag']); ?>"><?= $player['Gamertag']; ?></a></td>
                        <td><span class="flair flair-CSR-<?= $player[($this->uri->segment(2) == FALSE) ? "100_I" : $this->uri->segment(2)]; ?>"></span></td>
                        <td><?= $player['KDRatio']; ?></td>
                    </tr>
                <? $x++; endforeach; ?>
                <? if (!search_big($leaderboards,$my['Gamertag'])): ?>
                    <? if (isset($my[$this->uri->segment(2)]) && $my[$this->uri->segment(2)][$this->uri->segment(2)] > 0): ?>
                        <tr class="info">
                            <td><?= $this->library->get_trophy($my[$this->uri->segment(2)]['Rank']); ?></td>
                            <td><a style="color: #000; " href="<?= base_url('gt/' . $my['SeoGamertag']); ?>"><?= $my['Gamertag']; ?></a></td>
                            <td><span class="flair flair-CSR-<?= $my[($this->uri->segment(2) == FALSE) ? "100_I" : $this->uri->segment(2)]
                                [($this->uri->segment(2) == FALSE) ? "100_I" : $this->uri->segment(2)]; ?>"></span></td>
                            <td><?= $my['KDRatio']; ?></td>
                        </tr>
                    <? endif; ?>
                <? endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="row-fluid">
    <?= $pagination; ?>
</div>
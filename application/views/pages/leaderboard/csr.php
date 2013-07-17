<br />
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
        <a href="https://twitter.com/share" class="twitter-share-button" data-size="large" data-text="Leafapp - <?= $playlist_name; ?> Halo 4 Leaderboards - <?= base_url('csr_leaderboards/' . $playlist); ?>" data-hashtags="Halo4">Tweet</a>
        <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
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
                        <td>
                            <a style="color: #000; " href="<?= base_url('gt/' . $player['SeoGamertag']); ?>">
                                <?= $player['Gamertag']; ?>
                                <? if (isset($player['title'])): ?>
                                    - <span class="badge badge-<?= $player['colour']; ?>"><?= $player['title']; ?></span>
                                <? endif; ?></a>
                        </td>
                        <td><span class="flair flair-CSR-<?= $player[$playlist]; ?>"></span></td>
                        <td><?= $player['KDRatio']; ?></td>
                    </tr>
                <? $x++; endforeach; ?>
                <? if (!search_big($leaderboards,$my['Gamertag'])): ?>
                    <? if (isset($my[$playlist]['Rank']) && $my[$playlist][$playlist] > 0): ?>
                        <tr class="info">
                            <td><?= $this->library->get_trophy($my[$playlist]['Rank']); ?></td>
                            <td><a style="color: #000; " href="<?= base_url('gt/' . $my['SeoGamertag']); ?>"><?= $my['Gamertag']; ?></a></td>
                            <td><span class="flair flair-CSR-<?= $my[$playlist][$playlist]; ?>"></span></td>
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
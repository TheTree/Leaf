<? if ($data['SkillData'] != false && count($data['SkillData']) > 0): ?>
    <strong>Playlist Ranks</strong>
    <div class="well well-large">
        <div id="csr_switch" class="top_right">
            <div class="hidden">
                <ul class="nav nav-tabs" id="csr-tabs">
                    <li class="active"><a href="#new-csr">New CSR</a></li>
                    <li class=""><a href="#old-csr">Old CSR</a></li>
                </ul>
            </div>
            <i class="icon-th-large" data-toggle="tooltop" data-html="true" title="Switch to Old CSR look?" rel="tooltip" data-placement="top"></i>
        </div>
        <br />
        <div class="tab-content">
            <div class="tab-pane active" id="new-csr">
                <table class="table table-hover table-bordered table-condensed table-striped">
                    <thead>
                        <tr>
                            <th>Playlist</th>
                            <th><abbr title="Competitive SKill Rank">CSR</abbr></th>
                            <th>Rank</th>
                        </tr>
                    </thead>
                    <tbody>
                        <? foreach($data['CSRPlaylist'] as $playlist): ?>
                            <? if ($playlist['SkillRank'] > 0): ?>
                                <tr>
                                    <td><?= $playlist['Name']; ?> - <small>(<?= (substr($playlist['Id'], -1)) == "T" ? "Team" : "Invididual" ?>)</small>&nbsp;<a class="hidden-phone hidden-tablet" href="<?= base_url('csr_leaderboards/' . $playlist['Id']); ?>"><i class="icon-leaf"></i></a></td>
                                    <td><span class="flair flair-CSR-<?= ($playlist['SkillRank'] == 0) ? "Null" : $playlist['SkillRank']; ?>"></span></td>
                                    <td><?= $this->library->get_trophy($playlist['Rank']); ?></td>
                                </tr>
                            <? endif; ?>
                        <? endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="tab-pane" id="old-csr">
                <div class="pagination-centered">
                    <? foreach ($data['SkillData'] as $csr): ?>
                        <? if ($csr['SkillRank'] > 0): ?>
                            <span data-toggle="tooltop" data-html="true"
                                  title="<strong><?= $csr['Playlist']; ?></strong><br />(<?= $csr['Type']; ?>)" rel="tooltip"
                                  data-placement="top"> <img class="" src="<?= $csr['ImageUrl']; ?>" /></span>
                        <? endif; ?>
                    <? endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">

        // bind csr_switch to button
        $(document).ready(function() {
            $("#csr_switch").click(function() {
                $('#csr-tabs a:last').tab('show');
                $('#csr_switch').hide();
            });
        });
        $(function () {
            $("[rel='tooltip']").tooltip();
            $('#csr-tabs a:first').tab('show');
        });
    </script>
<? endif; ?>
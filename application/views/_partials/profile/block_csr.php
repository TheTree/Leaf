<? if ($data['SkillData'] != false && count($data['SkillData']) > 0): ?>
    <strong>Playlist Ranks</strong>
    <div class="well well-large">
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
    <script type="text/javascript">
        $(function () {
            $("[rel='tooltip']").tooltip();
        });
    </script>
<? endif; ?>
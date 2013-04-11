<? if ($data['SkillData'] != false): ?>
    <strong>Playlist Ranks</strong>
    <div class="well well-large">
        <div class="pagination-centered">
                <? foreach ($data['SkillData'] as $csr): ?>
                        <? if ($csr['SkillRank'] > 0): ?>
                            <span data-toggle="tooltop" title="<?= $csr['Playlist']; ?>" rel="tooltip" data-placement="top"> <img class="" src="<?= $csr['ImageUrl']; ?>" /></span>
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
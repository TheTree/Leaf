<strong>Competitive Skill Rank</strong>
<div class="well well-large h400">
    <div class="pagination-centered">
            <? foreach ($data['SkillData'] as $csr): ?>
                <a href="#" data-toggle="tooltop" title="<?= $csr['Playlist']; ?>" rel="tooltip" data-placement="top"> <img class="" src="<?= $csr['ImageUrl']; ?>" /></a>
            <? endforeach; ?>
    </div>
</div>
<script type="text/javascript">
    $(function () {
        $("[rel='tooltip']").tooltip();
    });
</script>
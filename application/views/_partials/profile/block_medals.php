<strong>Medals</strong>
<div class="well well-large medal_height">
    <div class="pagination-centered">
        <ul class="nav nav-tabs" id="MedalTabs">
            <? foreach ($data['MedalData'] as $medal): ?>
                <li class=""><a href="#Medal_<?= $medal['Name']; ?>" data-toggle="tab"><?= $medal['Name']; ?></a></li>
            <? endforeach; ?>
        </ul>

        <div class="tab-content">
            <? foreach($data['MedalData'] as $medal): ?>
                <div class="tab-pane" id="Medal_<?= $medal['Name']; ?>">
                    <h6><?= $medal['Description']; ?></h6>
                    <? foreach($medal as $inv): ?>
                        <?php if (is_array($inv)): ?>
                            <img src="<?= $inv['ImageUrl']; ?>" class="img-polaroid margin3pxbottom" data-toggle="tooltop" title="<?= $inv['Name']; ?> - <?= number_format($inv['Count']); ?>" rel="tooltip" data-placement="top" />
                        <? endif; ?>
                    <? endforeach; ?>
                </div>
            <? endforeach; ?>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $('#MedalTabs a:last').tab('show');
    });
    $('a[data-toggle="tab"]').on('shown', function (e) {
        e.target;
        e.relatedTarget;
    });
</script>
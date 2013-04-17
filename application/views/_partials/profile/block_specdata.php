<br /><br />
<? if ($data['SpecData'] != false): ?>
    <strong>Completed Specializations</strong>
    <div class="well well-large">
        <div class="pagination-centered">
            <? foreach ($data['SpecData'] as $spec): ?>
                    <span class="badge badge-inverse margin3pxbottom" data-toggle="tooltop" data-html="true" title="<strong><?= $spec['Name']; ?></strong><br /><br /> <?= $spec['Description']; ?>" rel="tooltip" data-placement="top"> <img src="<?= $spec['ImageUrl']; ?>" /></span>
            <? endforeach; ?>
        </div>
    </div>
<? endif; ?>
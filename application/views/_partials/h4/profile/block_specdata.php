<div class="visible-lg visible-md">
    <br /><br />
    <? if ($data[H4::SPEC_DATA] != false): ?>
        <strong>Completed Specializations</strong>
        <div class="well well-lg">
            <div class="text-center">
                <? foreach ($data[H4::SPEC_DATA] as $spec): ?>
                    <span class="badge margin3pxbottom" data-toggle="tooltop" data-html="true"
                          title="<strong><?= $spec['Name']; ?></strong><br /><br /> <?= $spec['Description']; ?>"
                          rel="tooltip" data-placement="top"><img src="<?= $spec['ImageUrl']; ?>" /></span>
                <? endforeach; ?>
            </div>
        </div>
    <? endif; ?>
</div>
<br />
<div class="subnav text-center">
    <? foreach($stats['Items'] as $item): ?>
        <a class="label label-static-size label-primary" href="#<?= strtolower(str_replace(" ", "_",$item)); ?>"><?= $item; ?></a>
    <? endforeach; ?>
</div>
<div class="row">
    <? foreach($stats['Items'] as $item): ?>
        <section id="<?= strtolower(str_replace(" ", "_",$item)); ?>">
            <div class="page-header"><h1><?= $item; ?></h1></div>
            <div class="fieldset-content">
                <p>
                    <?= $template['_partials'][strtolower(str_replace(" ", "_",$item))]; ?>
                </p>
            </div>
        </section>
    <? endforeach; ?>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $('.subnav a').smoothScroll();
    });
</script>
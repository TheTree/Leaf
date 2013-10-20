<br />
<div class="container">
    <div id="smoothy" class="col-lg-9 centered">
        <ul class="nav nav-pills">
            <? foreach($stats['Items'] as $item): ?>
                <li><a href="#<?= strtolower(str_replace(" ", "_",$item)); ?>"><?= $item; ?></a></li>
            <? endforeach; ?>
        </ul>
    </div>
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
        $('#smoothy a').smoothScroll();
    });
</script>
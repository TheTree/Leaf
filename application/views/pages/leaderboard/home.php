<br /><br /><br />
<header class="jumbotron subhead" id="overview">
<div class="subnav">
    <ul class="nav nav-pills">
        <?php foreach($stats['Items'] as $item): ?>
            <li><a href="#<?= strtolower(str_replace(" ", "_",$item)); ?>"><?= $item; ?></a></li>
        <? endforeach; ?>
        <li><a</li>
    </ul>
</div>
</header>
<div class="row-fluid"
<?php foreach($stats['Items'] as $item): ?>
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
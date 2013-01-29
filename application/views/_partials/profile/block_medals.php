<strong>Medals</strong>
<div class="well well-large h150">
    <div class="pagination-centered">
        <? foreach ($data['MedalData'] as $medal): ?>
            <img src="<?= $medal['ImageUrl']; ?>" class="img-polaroid " id="photo_<?= $medal['Id']; ?>" onMouseOver="set('photo_<?= $medal['Id']; ?>', '<?= addslashes($medal['Description']); ?>', '<?= $medal['Name']; ?> - Amt: <?= $medal['Count']; ?>');" />
        <? endforeach; ?>
    </div>
</div>
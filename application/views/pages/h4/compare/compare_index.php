<div class="hero-unit">
    <h1>Leaf Compare</h1>
    <p>Our system grades on a variety of stats that all correspond to specific point values. Whoever gets the most points wins.</p>
    <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#compareInformation">What is compared?</button>
</div>
<div class="alert alert-info"><b>Step 1:</b> Enter your gamertag and the gamertag you want to compare against.</div>
<?= form_open(base_url("h4/compare")); ?>
<div class="row-fluid">
    <div class="span6">
        <h3>You</h3>
        <div class="well">
            <span class="<?php if (form_error('you_name') != null): ?>control-group error<?php endif; ?>">
                <input class="span8 ajax-typeahead" name="you_name" id="you-typeahead" type="text" placeholder="Gamertag" autocomplete="off" value="<?php if (isset($you)): ?><?= $you; ?><? else: ?><?= set_value('you_name'); ?><? endif; ?>">
                <?php if (form_error('you_name') != null): ?><br /><span class="help-inline"><?= form_error('you_name') ?></span><?php endif; ?>
            </span>
        </div>
    </div>
    <div class="span6">
        <h3>Them</h3>
        <div class="well">
            <span class="<?php if (form_error('them_name') != null): ?>control-group error<?php endif; ?>">
                <input class="span8 ajax-typeahead" name="them_name" id="them-typeahead" type="text" placeholder="Gamertag" autocomplete="off" value="<?= set_value('them_name'); ?>">
                <?php if (form_error('them_name') != null): ?><br /><span class="help-inline"><?= form_error('them_name') ?></span><?php endif; ?>
            </span>
        </div>
    </div>
</div>
<div class="alert alert-info"><b>Step 2:</b> Start the Comparison!</div>
<div class="row-fluid">
    <div class="pagination-centered">
        <div class="well well-large">
            <button class="btn btn-primary btn-large" type="submit">Compare Now!</button>
        </div>
    </div>
</div>
<?= form_close(); ?>
<script type="text/javascript">
    $(document).ready(function() {
        $('#them-typeahead,#you-typeahead').typeahead({
            source: function(query, process) {
                return $.post('<?= base_url('ajax/gt/'); ?>' + "/" + query, {}, function(data) {
                    return process(data);
                });
            }
        });
    });
</script>
<?= $template['_partials']['modal_compare']; ?>
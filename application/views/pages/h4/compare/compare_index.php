<div class="jumbotron">
    <h1>Leaf Compare</h1>
    <p>Our system grades on a variety of stats that all correspond to specific point values. Whoever gets the most points wins.</p>
    <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#compareInformation">What is compared?</button>
</div>
<div class="alert alert-info"><b>Step 1:</b> Enter your gamertag and the gamertag you want to compare against.</div>
<?= form_open(base_url("h4/compare"), array("class" => "form-horizontal", "role" => "form")); ?>
<div class="row">
    <div class="col-md-6">
        <h3>You</h3>
        <div class="well">
            <div class="form-group <? if (form_error('you_name') != null): ?>error<? endif; ?>">
                <div class="col-lg-12">
                    <input class="form-control" name="you_name" id="you-typeahead" type="text" placeholder="Gamertag" autocomplete="off" value="<? if (isset($you)): ?><?= $you; ?><? else: ?><?= set_value('you_name'); ?><? endif; ?>">
                    <? if (form_error('you_name') != null): ?><br /><span class="help-inline"><?= form_error('you_name') ?></span><? endif; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <h3>Them</h3>
        <div class="well">
            <div class="form-group <? if (form_error('them_name') != null): ?>error<? endif; ?>">
                <div class="col-lg-12">
                    <input class="form-control" name="them_name" id="them-typeahead" type="text" placeholder="Gamertag" autocomplete="off" value="<?= set_value('them_name'); ?>">
                    <? if (form_error('them_name') != null): ?><br /><span class="help-inline"><?= form_error('them_name') ?></span><?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="alert alert-info"><b>Step 2:</b> Start the Comparison!</div>
<div class="text-center">
    <div class="well">
        <button class="btn btn-primary btn-large" type="submit">Compare Now!</button>
    </div>
</div>
<?= form_close(); ?>
<script type="text/javascript">
    $(document).ready(function() {
        $('#them-typeahead,#you-typeahead').typeahead([
            {
                remote:'<?= base_url('ajax/gt/'); ?>' + "/%QUERY"
            }
        ]);
    });
</script>
<?= $template['_partials']['modal_compare']; ?>
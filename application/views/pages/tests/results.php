<div class="container">
    <div class="row-fluid">
        <div class="well span12">
            <legend>Test Results</legend>
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Test Name</th>
                    <th>Expected</th>
                    <th>Result</th>
                    <th>Outcome</th>
                </tr>
                </thead>
                <tbody>
                <? foreach($test_results as $key => $test): ?>
                    <? if($test['Result'] == 'Passed'): ?>
                        <tr class="success">
                    <? else: ?>
                        <tr class="danger">
                    <? endif; ?>
                    <td>
                        <span data-toggle="tooltip" data-placement="right" title="<?= $test['Notes']; ?>"
                           rel="tooltip" ><?= $test['Test Name']; ?></span>
                    </td>
                    <td><?= $test['Expected Value']; ?></td>
                    <td><?= $test['Test Value']; ?></td>
                    <td><?= $test['Result']; ?></td>
                    </tr>
                <? endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(function () {
        $("[rel='tooltip']").tooltip();
    });
</script>
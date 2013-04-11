<div class="container">
    <div class="row-fluid">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th><a href="<?= base_url('gt/' . $data['you']['SeoGamertag']); ?>"><?= $data['you']['Gamertag']; ?></a> - <small><?= $data['you']['ServiceTag']; ?></small></th>
                    <th><a href="<?= base_url('gt/' . $data['them']['SeoGamertag']); ?>"><?= $data['them']['Gamertag']; ?></a> - <small><?= $data['them']['ServiceTag']; ?></small></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <div class="pagination-centered">
                            <img src="<?= $data['you']['SpartanSmallUrl']; ?>" />
                        </div>
                    </td>
                    <td>
                        <div class="pagination-centered">
                            <img src="<?= $data['them']['SpartanSmallUrl']; ?>" />
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>Task</th>
                    <th><?= $data['you']['Gamertag']; ?></th>
                    <th>Points</th>
                    <th><?= $data['them']['Gamertag']; ?></th>
                    <th>Points</th>
                </tr>
            </thead>
            <tbody>
                <? foreach($data['stats'] as $item): ?>
                    <? if (is_array($item)): ?>
                        <tr>
                            <td><?= $item['Name']; ?></td>
                            <td><?= $data['you'][$item['YouField']]; ?></td>
                            <td><span class="badge <?= $item['you']['style']; ?>"><?= $item['you']['pts']; ?></span></td>
                            <td><?= $data['them'][$item['ThemField']]; ?></td>
                            <td><span class="badge <?= $item['them']['style']; ?>"><?= $item['them']['pts']; ?></span></td>
                        </tr>
                    <? endif; ?>
                <? endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

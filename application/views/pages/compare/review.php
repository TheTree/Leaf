<div class="container-fluid">
    <div class="row-fluid">
        <? if (isset($error_msg)): ?>
            <div class="hero-unit">
                <h1>Welp! Error!</h1>
            </div>
            <div class="alert alert-error"><?= $error_msg; ?></div>
        <? else: ?>
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
        </div>
        <div class="row-fluid">
        <div class="span8">
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
                                <td><?= $data['you'][$item['Field']]; ?></td>
                                <td><span class="badge <?= $item['you']['style']; ?>"><?= $item['you']['pts']; ?></span></td>
                                <td><?= $data['them'][$item['Field']]; ?></td>
                                <td><span class="badge <?= $item['them']['style']; ?>"><?= $item['them']['pts']; ?></span></td>
                            </tr>
                        <? endif; ?>
                    <? endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="span4">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Gamertag</th>
                        <th>Total Points</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?= $data['you']['Gamertag']; ?></td>
                        <td><span class="badge <?= $data['you']['TotalStyle']; ?>"><?= $data['you']['TotalPoints']; ?></span></td>
                    </tr>
                    <tr>
                        <td><?= $data['them']['Gamertag']; ?></td>
                        <td><span class="badge <?= $data['them']['TotalStyle']; ?>"><?= $data['them']['TotalPoints']; ?></span></td>
                    </tr>
                </tbody>
            </table>
            <? if ($data['final']['Status']): ?>
                <div class="alert <?= $data['final']['Style'] ?>">Winner: <strong><?= $data['you']['Gamertag']; ?></strong></div>
                <span class="badge badge-inverse">Loser: <strong><?= $data['them']['Gamertag']; ?></strong></span>
            <? else: ?>
                <div class="alert <?= $data['final']['Style'] ?>">Loser: <strong><?= $data['you']['Gamertag']; ?></strong></div>
                <span class="badge badge-success">Winner: <strong><?= $data['them']['Gamertag']; ?></strong></span>
            <? endif; ?>
        </div>
        <? endif; ?>
    </div>
</div>

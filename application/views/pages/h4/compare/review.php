<div class="container">
    <div class="row">
        <br />
        <? if (isset($error_msg)): ?>
            <div class="jumbotron">
                <h1>Welp! Error!</h1>
            </div>
            <div class="alert alert-error"><?= $error_msg; ?></div>
        <? else: ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th><?= $data['you'][H4::BADGE]; ?><a href="<?= base_url('h4/record/' . $data['you'][H4::SEO_GAMERTAG]); ?>"><?= $data['you'][H4::GAMERTAG]; ?></a> - <small><?= $data['you'][H4::SERVICE_TAG]; ?></small></th>
                    <th><?= $data['them'][H4::BADGE]; ?><a href="<?= base_url('h4/record/' . $data['them'][H4::SEO_GAMERTAG]); ?>"><?= $data['them'][H4::GAMERTAG]; ?></a> - <small><?= $data['them'][H4::SERVICE_TAG]; ?></small></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <div class="text-center">
                            <img src="<?= $data['you']['SpartanSmallUrl']; ?>" />
                        </div>
                    </td>
                    <td>
                        <div class="text-center">
                            <img src="<?= $data['them']['SpartanSmallUrl']; ?>" />
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        </div>
        <div class="row">
        <div class="col-lg-8">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Task</th>
                        <th><?= $data['you'][H4::GAMERTAG]; ?></th>
                        <th>Points</th>
                        <th><?= $data['them'][H4::GAMERTAG]; ?></th>
                        <th>Points</th>
                    </tr>
                </thead>
                <tbody>
                    <? foreach($data['stats'] as $item): ?>
                        <? if (is_array($item)): ?>
                            <tr>
                                <td><?= $item['Name']; ?></td>
                                <td><?= $data['you'][$item['Field']]; ?></td>
                                <td><span class="label label-default <?= $item['you']['style']; ?>"><?= $item['you']['pts']; ?></span></td>
                                <td><?= $data['them'][$item['Field']]; ?></td>
                                <td><span class="label label-default <?= $item['them']['style']; ?>"><?= $item['them']['pts']; ?></span></td>
                            </tr>
                        <? endif; ?>
                    <? endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="col-lg-4">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Gamertag</th>
                        <th>Total Points</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?= $data['you'][H4::GAMERTAG]; ?></td>
                        <td><span class="label <?= $data['you']['TotalStyle']; ?>"><?= $data['you']['TotalPoints']; ?></span></td>
                    </tr>
                    <tr>
                        <td><?= $data['them'][H4::GAMERTAG]; ?></td>
                        <td><span class="label <?= $data['them']['TotalStyle']; ?>"><?= $data['them']['TotalPoints']; ?></span></td>
                    </tr>
                </tbody>
            </table>
            <? if ($data['final']['Status'] == 'W'): ?>
                <div class="alert <?= $data['final']['Style'] ?>">Winner: <strong><?= $data['you'][H4::GAMERTAG]; ?></strong></div>
                <span class="label label-inverse">Loser: <strong><?= $data['them'][H4::GAMERTAG]; ?></strong></span>
            <? elseif ($data['final']['Status'] == 'L'): ?>
                <div class="alert <?= $data['final']['Style'] ?>">Loser: <strong><?= $data['you'][H4::GAMERTAG]; ?></strong></div>
                <span class="label label-success">Winner: <strong><?= $data['them'][H4::GAMERTAG]; ?></strong></span>
            <? else: ?>
                <div class="alert alert-info">Tie: <strong><?= $data['you'][H4::GAMERTAG]; ?></strong></div>
                <div class="alert alert-info">Tie: <strong><?= $data['them'][H4::GAMERTAG]; ?></strong></div>
            <? endif; ?>
            <br /><br />
            <a href="https://twitter.com/share" class="twitter-share-button" data-size="large" data-text="I <?= $data['final']['TweetWord'] ?> <?= $data['them'][H4::GAMERTAG] ?> (<?= $template['title']; ?>)" data-hashtags="Halo4">Tweet</a>
            <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
        </div>
        <? endif; ?>
    </div>
</div>

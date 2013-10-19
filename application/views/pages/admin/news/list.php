<h3>Leaf News</h3>
<div class="container">
    <div class="row-fluid">
        <div class="span12">
            <? if ($news != FALSE): ?>
                <table class="table table-bordered table-hover">
                    <thead>
                    <tr>
                        <th>Description</th>
                        <th>Author</th>
                        <th>Posted</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <? foreach($news as $news_item): ?>
                        <tr>
                            <td><?= word_limiter($news_item['text'], 15); ?></td>
                            <td><?= $news_item['author']; ?></td>
                            <td><?= unix_to_human($news_item['date_posted']); ?></td>
                            <td>
                                <!-- todo / actually make edit/delete work -->
                                <a href="#" class="btn btn-sm btn-info">Edit</a>
                                <a href="#" class="btn btn-sm btn-danger" onclick="return confirm('CONFIRM: Delete News Story?');">Delete</a>
                            </td>
                        </tr>
                    <? endforeach; ?>
                    </tbody>
                </table>
                <?= $news_pagination; ?>
            <? else: ?>
                <div class="alert alert-danger">
                    <strong>Heads Up!</strong> We could not find any news stories :(
                </div>
            <? endif; ?>
        </div>
    </div>
</div>

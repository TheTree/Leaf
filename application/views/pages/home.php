<div class="row">
    <div class="col-md-7">
        <div class="well well-lg">
            <section>
                <p class="lead">
                    Welcome to Leaf.
                </p>
                <p>Halo 4 stats, that don't suck.</p>
            </section>
        </div>
        <?= $template['_partials']['news_block']; ?>
        <?= $template['_partials']['gamertag_add']; ?>
        <?= $template['_partials']['last_compared']; ?>
        <br />
        <?= $template['_partials']['recent_gamertags']; ?>
        <a href="https://twitter.com/share" class="twitter-share-button" data-size="large" data-hashtags="Halo4">Tweet</a>
        <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
    </div>
    <div class="col-md-5">
        <aside>
            <?= $template['_partials']['challenges']; ?>
        </aside>
    </div>
</div>
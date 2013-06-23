<div class="row-fluid">
    <div class="span7">
        <div class="well well-large">
            <p class="lead">
                Welcome to Leaf.
            </p>
            <p>Halo 4 stats, that don't suck.</p>
        </div>
        <?= $template['_partials']['news_block']; ?>
        <br />
        <?= $template['_partials']['recent_gamertags']; ?>
        <a href="https://twitter.com/share" class="twitter-share-button" data-size="large" data-hashtags="Halo4">Tweet</a>
        <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
    </div>
    <div class="span5">
        <?= $template['_partials']['challenges']; ?>
    </div>
</div>
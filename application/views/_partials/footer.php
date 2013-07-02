<div class="contained-fluid navbar navbar-fixed-bottom">
    <span class="pull-left">
        <? if (ENVIRONMENT == "development"): ?>
            Report Problems <a href="https://github.com/iBotPeaches/Leaf"> Here.</a>
        <? else: ?>
            <small><a href="#legalStuff" role="button" class="btn" data-toggle="modal">Leaf</a></small>
        <? endif; ?>
    </span>
</div>
</body>
<div id="legalStuff" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="legalStuff" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="legalStuff">Legal Stuff</h3>
    </div>
    <div class="modal-body">
        <p>Halo © Microsoft Corporation. Leaf was created under Microsoft's 
            "<a href="http://www.xbox.com/en-US/community/developer/rules">Game Content Usage Rules</a>"
            using assets from Halo. It is not endorsed by Microsoft and does not reflect the views or opinions of Microsoft or anyone officially involved in producing or managing Halo.  
            As such, it does not contribute to the official narrative of the fictional universe, if applicable.</p>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Thanks</button>
    </div>
</div>
</html>
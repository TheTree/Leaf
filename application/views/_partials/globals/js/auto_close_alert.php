<script type="text/javascript">
    $(document).ready(function() {
        window.setTimeout(function() {
            $(".alert-close").fadeTo(500, 0).slideUp(500, function(){
                $(this).remove();
            });
        }, 5000);
    });
</script>
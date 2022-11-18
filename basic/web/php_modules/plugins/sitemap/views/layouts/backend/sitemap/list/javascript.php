<script>
    document.getElementById('clear_filter').onclick =  function() {
        document.getElementById("search").value = "";
        document.getElementById("published").value = "";
        document.getElementById("limit").value = "";
        document.getElementById("plugin").value = "";
        document.getElementsByClassName('filterForm')[0].submit();
    };
    document.getElementById("limit").onchange = function() {
        document.getElementsByClassName('filterForm')[0].submit();
    };
    document.getElementById("published").onchange = function() {
        document.getElementsByClassName('filterForm')[0].submit();
    };
    jQuery(document).ready(function() {
        jQuery('.js-example-basic-multiple').select2();
    });
</script>
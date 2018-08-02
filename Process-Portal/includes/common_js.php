<script src="assets/global/scripts/app.min.js" type="text/javascript"></script>
<!-- END THEME GLOBAL SCRIPTS -->
<!-- BEGIN THEME LAYOUT SCRIPTS -->
<script src="assets/layouts/layout/scripts/layout.min.js" type="text/javascript"></script>
<script src="assets/layouts/layout/scripts/demo.min.js" type="text/javascript"></script>
<script src="assets/layouts/global/scripts/quick-sidebar.min.js" type="text/javascript"></script>
<script src="assets/layouts/global/scripts/quick-nav.min.js" type="text/javascript"></script>
<script src="assets/global/plugins/jquery-validation/js/jquery.validate.js" type="text/javascript"></script>
<!-- END THEME LAYOUT SCRIPTS -->
<script>
var hr = "<?php echo $activeHref;?>"
$(".nav-item").each(function(){
    var href = $(this).find("a.nav-link").attr("href");
    if(hr == href){
        $(this).addClass("active open");
        $(this).find("a.nav-link").append('<span class="selected"></span>');
    }
})

$("ul.sub-menu li.nav-item").each(function(){
    var href = $(this).find("a.nav-link").attr("href");
    if(hr == href){
        $(this).addClass("active open");
        $(this).find("a.nav-link").append('<span class="selected"></span>');
        var parentLi = $(this).parent("ul.sub-menu").parent("li.nav-item");
        parentLi.addClass("active open");
        parentLi.find("a.nav-toggle").append('<span class="selected"></span>');
        parentLi.find("a.nav-toggle").find("span.arrow").addClass("open");
    }

})
</script>

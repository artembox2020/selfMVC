<div class="<?= $class ?>-coverage" style="position : fixed; top : 0%; left: 0%; width : 100%; height : 100%; display : none; opacity: 0.7; z-index:999; background-color : black;"></div>
<div class="panel <?= $class ?> panel-default" style="position : fixed; top: 4%; z-index : 1000; left : 4%; width : 92%; height : 92%; overflow: auto!important; display : none;">
        <div class="panel-heading">
            <div style="float : right; cursor : pointer;"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></div>
            <h3 align=center><?= $title ?></h3>
        </div>
        <div class="panel-body" style="overflow: auto!important;">
            <?= $body ?>
        </div>
</div>
<script>
    jQuery(function($){
         $("<?= $clickSelector ?>").on('click', function(e) {
            e.preventDefault(); 
            $(".<?= $class ?> .panel-body").html(AesCtr.decrypt($(this).data('desc'),'abcdefgh',128));
            $(".<?= $class ?> .panel-heading h3").html($(this).data('title'));
            $(".<?= $class ?>, .<?= $class ?>-coverage").toggle();
        });
        
        $(".<?= $class ?> .panel-heading").on('click', function() { 
            
            $(this).closest(".panel").toggle(); 
            $(".<?= $class ?>-coverage").toggle(); 
            
        });
        
        $(".<?= $class ?>-coverage").on('click', function(){ $(".<?= $class ?>, .<?= $class ?>-coverage").toggle(); });
 
    })
</script>
<?php
$params = shortcode_atts(array('limit' => '12', 'filters'=>'0'), $args);
$filter_ids = explode( ',',
                      $params['filters']);

$attributes = [];
$atts = wc_get_attribute_taxonomies();
foreach ($atts as $att){
    $attributes[$att->attribute_id] = $att;
}


$a_filters = [];
$c_filter = false;

foreach($filter_ids as $filter_id){
    if($filter_id == 0){
        $c_filter = true;
    }
    else{
        if(array_key_exists( $filter_id,
                             $attributes)){
            $a_filters[$filter_id] = $attributes[$filter_id];
        }
    }
}

?>

<div class="wa-waf-list woocommerce">
    <div class="wa-waf-list-top">
        <div class="wa-waf-filters wa-waf-list-top-section">
                <ul>
				    <?php if($c_filter && !is_product_taxonomy()):?>
                        <li class="wa-waf-filter">
                            <div class="wa-waf-filter-label">
                                <span><?php _e( 'Category', 'wa'); ?></span>
                                <i class="fa fa-lg fa-sort-desc"></i>
                            </div>
                            <div class="wa-waf-filter-selected-term"></div>
                            <div class="wa-waf-filter-terms">
                                <ul>
                                    <?php
                                    /** @var WP_Term[] $cats */
                                    $cats = get_terms(array (
                                                            'taxonomy' => 'product_cat',
                                                            'hide_empty' => false,
                                                            'parent' => 0
                                                      ));
                                    ?>
                                    <?php if($cats):?>
                                        <?php foreach ($cats as $cat):?>
                                            <li data-name="cat" data-label="<?php echo $cat->name ?>" data-type="cat" data-value="<?php echo $cat->term_id ?>" class="wa-waf-filter-term wa-waf-filter-term-text">
                                                <span><?php echo $cat->name ?></span>
                                            </li>
                                        <?php endforeach;?>
                                        <div class="clear" data-term="cat-cat">
		                                    <?php _e('Clear', 'wa') ?>
                                        </div>
                                    <?php endif;?>
                                </ul>
                            </div>
                        </li>
				    <?php endif;?>
				    <?php foreach ($a_filters as $a_filter):
					    if($a_filter->attribute_type == 'select'){
						    $type = 'text';
					    }
					    else{
						    $type = $a_filter->attribute_type;
					    }
                        ?>
                        <li class="wa-waf-filter">
                            <div class="wa-waf-filter-label">
                                <span><?php echo $a_filter->attribute_label; ?></span>
                                <i class="fa fa-lg fa-sort-desc"></i>
                            </div>
                            <div class="wa-waf-filter-selected-term"></div>
                            <div class="wa-waf-filter-terms">
                                <?php
                                /** @var WP_Term[] $terms */
                                $terms = get_terms(array(
	                                                   'taxonomy' => 'pa_'.$a_filter->attribute_name,
	                                                   'hide_empty' => false,
	                                                   'parent' => 0
                                                   ));
                                ?>
                                <?php if($terms):?>
                                    <ul>
                                    <?php foreach ($terms as $term):?>
                                        <li data-name="<?php echo $a_filter->attribute_name ?>" data-label="<?php echo $term->name ?>" data-type="att" data-value="<?php echo $term->term_id ?>" class="wa-waf-filter-term wa-waf-filter-term-<?php echo $type; ?>">
                                            <?php if($type == 'color'):
                                            $color = get_term_meta( $term->term_id, 'color', true);
                                            if(!$color){
                                                $color = 'white';
                                            }
                                            ?>
                                            <span class="color" style="background-color: <?php echo $color ?>"></span>
                                            <?php endif;?>
                                            
                                            <?php if($type == 'image'):
                                                $image = get_term_meta( $term->term_id, 'image', true);
                                                $image = wp_get_attachment_thumb_url($image);
                                            ?>
                                            <span class="img" style="display: inline-block; background-image: url('<?php echo $image ?>')"></span>
                                            <?php endif;?>
                                            <?php if($type == 'label'):
	                                            $label = get_term_meta( $term->term_id, 'label', true);
                                            ?>
                                            <span class="label"><?php echo $label ?></span>
                                            <?php endif;?>
                                            <span><?php echo $term->name; ?></span>
                                        </li>
                                    <?php endforeach;?>
                                        <div class="clear" data-term="att-<?php echo $a_filter->attribute_name ?>">
		                                    <?php _e('Clear', 'wa') ?>
                                        </div>
                                    </ul>
                                <?php endif;?>
                            </div>
                        </li>
				    <?php endforeach;?>
                </ul>
        </div>
       
        <div class="wa-waf-list-order wa-waf-list-top-section">
            <ul>
                <li class="wa-waf-filter">
                    <div class="wa-waf-filter-label">
                        <span><?php _e( 'Order by', 'wa'); ?></span>
                        <i class="fa fa-lg fa-sort-desc"></i>
                    </div>
                    <div class="wa-waf-filter-selected-term"></div>
                    <div class="wa-waf-filter-terms">
                        <ul>
                            <li data-name="order" data-label="<?php _e( 'Default', 'wa') ?>" data-type="order" data-value="default" class="wa-waf-filter-term wa-waf-filter-term-text active">
                                <span><?php _e( 'Default', 'wa') ?></span>
                            </li>
                            <li data-name="order" data-label="<?php _e( 'Popularity', 'wa') ?>" data-type="order" data-value="popularity" class="wa-waf-filter-term wa-waf-filter-term-text">
                                <span><?php _e( 'Popularity', 'wa') ?></span>
                            </li>
                            <li data-name="order" data-label="<?php _e( 'Rating', 'wa') ?>" data-type="order" data-value="rating" class="wa-waf-filter-term wa-waf-filter-term-text">
                                <span><?php _e( 'Average rating', 'wa') ?></span>
                            </li>
                            <li data-name="order" data-label="<?php _e( 'Newness', 'wa') ?>" data-type="order" data-value="newness" class="wa-waf-filter-term wa-waf-filter-term-text">
                                <span><?php _e( 'Newness', 'wa') ?></span>
                            </li>
                            <li data-name="order" data-label="<?php _e( 'Price (Asc)', 'wa') ?>" data-type="order" data-value="low_to_high" class="wa-waf-filter-term wa-waf-filter-term-text">
                                <span><?php _e( 'Price: low to high', 'wa') ?></span>
                            </li>
                            <li data-name="order" data-label="<?php _e( 'Price (Desc)', 'wa') ?>" data-type="order" data-value="high_to_low" class="wa-waf-filter-term wa-waf-filter-term-text">
                                <span><?php _e( 'Price: high to low', 'wa') ?></span>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
    </div>
    <div class="wa-waf-list-top-nav">
        <div class="wa-waf-selected-rs">
            <label><?php _e('Filters: ') ?></label>
            <div class="wa-waf-selected-rs-clear-all">[<?php _e('Clear All') ?>]</div>
        </div>
        <div class="wa-waf-list-top-paging">
        
        </div>
    </div>
    <div class="wa-waf-product-list">
    
    </div>
</div>
<script type="text/javascript">
    var wa_doing_ajax = false;
    var view_all = false;
    
    function wa_waf_doing_ajax(offset) {
        wa_doing_ajax = true;
        var data = {
            action: 'wa_waf_get',
            offset: offset,
            limit: <?php echo $params['limit'] ?>
        };
        if(offset == -1){
            view_all = true;
        }
        if(view_all){
            data.offset = -1;
        }
        var order = jQuery('.wa-waf-list-order .wa-waf-filter-term.active').data('value');
        data.order = order;
        var cat_node = jQuery('.wa-waf-filter-terms .wa-waf-filter-term.active[data-type=cat]');
        var ct = 0;
        data.tax_query = [];
        if(cat_node.length>0){
            var cat_id = jQuery(cat_node).data('value');
            data.tax_query[ct] = {
              'taxonomy': 'product_cat',
                'field' : 'term_id',
                'terms' : cat_id
            };
            ct = ct + 1;
        }
        jQuery('.wa-waf-list').addClass('doing-ajax');
        jQuery('.wa-waf-filter-terms .wa-waf-filter-term.active[data-type=att]').each(function(){
            var pa_name = 'pa_'+jQuery(this).data('name');
            var pa_val = jQuery(this).data('value');
            data.tax_query[ct] = {
                'taxonomy': pa_name,
                'field' : 'term_id',
                'terms' : pa_val
            };
            ct = ct + 1;
        });
        <?php if(is_product_taxonomy()): ?>
        <?php
            /** @var WP_Term $cterm */
            $cterm = get_queried_object();
        ?>
        data.tax_query[ct] = {
            'taxonomy': '<?php echo $cterm->taxonomy ?>',
            'field' : 'term_id',
            'terms' : <?php echo $cterm->term_id ?>
        };
        ct = ct + 1;
        <?php endif;?>
        jQuery.ajax({
            url: '<?php echo admin_url();?>/admin-ajax.php',
            method: 'post',
            data: data,
            success: function (data) {
                jQuery('.wa-waf-product-list').html(data);
                if(jQuery('.wa-waf-paging-sect-info').length>0){
                    jQuery('.wa-waf-list-top-paging').html(jQuery('.wa-waf-paging-sect-info').html());
                }
                else{
                    jQuery('.wa-waf-list-top-paging').html('');
                }
                jQuery('.wa-waf-product-list').css('opacity', 0);
                jQuery('.wa-waf-product-list').animate({
                    opacity: 1
                });
            },
            complete: function (data) {
                wa_doing_ajax = false;
                jQuery('.wa-waf-list').removeClass('doing-ajax');
            }
        });
    }
    
    jQuery(document).ready(function () {
        jQuery('.wa-waf-list-order .wa-waf-filter-term[data-value=newness]').click();
    });
    
    function wa_add_filter(target){
        if(jQuery(target).hasClass('active')){
            return;
        }
        jQuery(target).parent().find('.active').removeClass('active');
        jQuery(target).parent().find('.clear').show();
        jQuery(target).addClass('active');
        var slabel = jQuery(target).data('label');
        var stype = jQuery(target).data('type');
        var sname = jQuery(target).data('name');
        jQuery(target).parent().parent().parent().find('.wa-waf-filter-selected-term').html(slabel);
        if(stype != 'order'){
            jQuery('.wa-waf-selected-rs').show();
            jQuery('.wa-waf-selected-rs div[data-term='+stype+'-'+sname+']').remove();
            jQuery('<div data-term="'+stype+'-'+sname+'" class="wa-waf-f-item"><span>'+slabel+'</span> <i onclick="wa_clear_me(this)" class="fa fa-times-circle" aria-hidden="true"></i></div>').insertBefore('.wa-waf-selected-rs .wa-waf-selected-rs-clear-all');
        }
    }
    
    function wa_clear_filter(target) {
        jQuery(target).parent().find('.active').removeClass('active');
        jQuery(target).hide();
        jQuery(target).parent().parent().parent().find('.wa-waf-filter-selected-term').html('');
        var sterm = jQuery(target).data('term');
        jQuery('.wa-waf-selected-rs div[data-term='+sterm+']').remove();
        if(jQuery('.wa-waf-selected-rs .wa-waf-f-item').length == 0){
            jQuery('.wa-waf-selected-rs').hide();
        }
    }
    
    function wa_clear_me(_target) {
        if(wa_doing_ajax){
            return;
        }
        var target = jQuery(_target).parent();
        var sterm = jQuery(target).data('term');
        var t =jQuery('.wa-waf-filter-terms .clear[data-term='+sterm+']');
        wa_clear_filter(t);
        wa_waf_doing_ajax(0);
    }
    
    jQuery('.wa-waf-filter-terms>ul>li').click(function () {
        if(wa_doing_ajax){
            return;
        }
        wa_add_filter(this);
        wa_waf_doing_ajax(0);
    });

    jQuery('.wa-waf-filter-terms>ul>.clear').click(function () {
        if(wa_doing_ajax){
            return;
        }
        wa_clear_filter(this);
        wa_waf_doing_ajax(0);
    });
    
    jQuery('.wa-waf-selected-rs-clear-all').click(function () {
        if(wa_doing_ajax){
            return;
        }
        jQuery('.wa-waf-selected-rs .wa-waf-f-item').each(function () {
            var sterm = jQuery(this).data('term');
            var t =jQuery('.wa-waf-filter-terms .clear[data-term='+sterm+']');
            wa_clear_filter(t);
        });
        wa_waf_doing_ajax(0);
    });
</script>
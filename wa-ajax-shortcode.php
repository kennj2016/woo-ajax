<ul class="products">
	<?php
	switch($_POST['order']){
		case 'default':
			$meta_key = '';
			$order = 'asc';
			$orderby = 'menu_order title';
			break;
		case 'popularity':
			$meta_key = '';
			$order = 'desc';
			$orderby = 'total_sales';
			break;
		case 'low_to_high':
			$meta_key = '_price';
			$order = 'asc';
			$orderby = 'meta_value_num';
			break;
		case 'high_to_low':
			$meta_key = '_price';
			$order = 'desc';
			$orderby = 'meta_value_num';
			break;
		case 'newness':
			$meta_key = '';
			$order = 'desc';
			$orderby = 'date';
			break;
		case 'rating':
			$meta_key = '_wc_average_rating';
			$order = 'desc';
			$orderby = 'meta_value_num';
			break;
	}
	
	$args = array(
		'post_type' => 'product',
		'posts_per_page' => $_POST['limit'],
		'offset' => $_POST['offset'],
		'orderby' => $orderby,
		'order' => $order,
		'meta_key' => $meta_key
	);
	if($_POST['offset'] == -1){
		$args['posts_per_page'] = -1;
		$args['offset'] = 0;
    }
	//exit(json_encode($args));
    if($_POST['tax_query']){
        $args['tax_query'] = $_POST['tax_query'];
    }
	$loop = new WP_Query( $args );
    $c = 0;
	if ( $loop->have_posts() ) {
		while ( $loop->have_posts() ) : $loop->the_post();
			wc_get_template_part( 'content', 'product' );
			$c++;
		endwhile;
	} else {
	    ?>
        <div class="wa-waf-product-not-found">
        <?php
		echo __( 'No products found' );
		?>
        </div>
        <?php
	}
	wp_reset_postdata();
	?>
</ul><!--/.products-->
<?php
$current_page = ($_POST['offset'] / $_POST['limit'])+1;
$max_pages = $loop->max_num_pages;
$next_offset = -1;
$prev_offset = -1;
if($current_page != $max_pages){
    $next_offset = $_POST['limit'] * ($current_page);
}
if($current_page != 1){
	$prev_offset = ($_POST['limit'] * ($current_page - 1)) - $_POST['limit'];
}
?>
<?php if($c>0 && $_POST['offset']!=-1 && $max_pages>1):?>
<div class="wa-waf-paging-sect">
        <div class="wa-waf-paging-sect-info">
            <?php printf( __('Page %s of %s', 'wa'),$current_page, $max_pages );?>
        </div>
        <div class="wa-waf-paging-sect-nav">
            <div <?php if($prev_offset !=-1) echo 'onclick="wa_waf_doing_ajax('.$prev_offset.')"'; ?> class="wa-waf-paging-sect-prev <?php if($current_page==1)echo 'no-click';?>"><i class="fa fa-lg fa-chevron-left"></i> <?php _e( 'Previous', 'wa') ?></div>
            <div <?php if($max_pages > 1) echo 'onclick="wa_waf_doing_ajax(-1)"'; ?> class="wa-waf-paging-sect-all <?php if($max_pages <= 1)echo 'no-click';?>"><?php _e( 'View All', 'wa') ?></div>
            <div <?php if($next_offset !=-1) echo 'onclick="wa_waf_doing_ajax('.$next_offset.')"'; ?> class="wa-waf-paging-sect-next <?php if($current_page==$max_pages)echo 'no-click';?>"><?php _e( 'Next', 'wa') ?> <i class="fa fa-lg fa-chevron-right"></i></div>
        </div>
</div>
<div class="wa-paging-info" style="display: none" data-current="<?php echo $current_page; ?>" data-total="<?php echo $max_pages; ?>"></div>
<?php endif;?>
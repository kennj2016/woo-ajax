<link rel="stylesheet" href="<?php echo plugins_url('/assets/css/jquery-ui.css', __FILE__) ?>">
<script type="text/javascript" src="<?php echo plugins_url('/assets/js/jquery-ui.js', __FILE__) ?>"></script>
<div class="wrap">
	<h1 class="wp-heading-inline">Woo Ajax Filter Shortcode Builder</h1>
	<hr class="wp-header-end"/>
	<div class="note" style="background: aliceblue; color: cornflowerblue; border: 1px solid cornflowerblue;margin-top: 15px;margin-bottom: 15px; text-align: left; padding: 15px">
		Select your filter based on categories or supported attributes.
	</div>
	
	<fieldset>
		<legend><h4>Enable filter for (Drag & drop to reorder filter)</h4></legend>
		<div id="ss">
			<div style="margin: 5px 0; border: 1px solid gainsboro; padding: 10px; background: whitesmoke">
				<label><input checked="checked" onchange="get_shortcode()" name="waf-cat" value="0" class="waf-att" type="checkbox"> Product categories</label>
			</div>
			
			<pre style="display: none;">
	<?php
	$attributes = wc_get_attribute_taxonomies();
	$types = array (
		'select',
		'color',
		'image',
		'label'
	);
	?>
	</pre>
			
				<?php foreach ($attributes as $attribute):?>
                    <?php if(!in_array( $attribute->attribute_type,
                                        $types)){
                        continue;
                    }
                    ;?>
					<div style="margin: 5px 0; border: 1px solid gainsboro; padding: 10px; background: whitesmoke">
						<label><input value="<?php echo $attribute->attribute_id ?>" class="waf-att" onchange="get_shortcode()" name="waf-att[]" type="checkbox"> Attribute: <?php echo $attribute->attribute_label ?></label>
					</div>
				<?php endforeach;?>
		</div>
	</fieldset>

    <fieldset>
        <legend><h4>Display options</h4></legend>
        <div>
            <div style="margin: 5px">
                <label style="display: inline-block; width: 150px">Item per page</label>
                <input id="ipp" type="number" value="12">
            </div>
        </div>
    </fieldset>
    
	<fieldset>
		<legend><h4>Copy your shortcode here</h4></legend>
		<div>
			<div style="display: block; padding: 15px; background-color: lightgrey; font-size: 20px;" id="get-shortcode">
			
			</div>
		</div>
	</fieldset>

    <fieldset>
        <legend><h4>Shortcode for woo archive</h4></legend>
        <div>
            <form method="post">
                <?php
                if(isset( $_POST['sca'])){
                    update_option( 'waf_for_archive',
                                   $_POST['sca']);
                }
                $sca = get_option( 'waf_for_archive', '');
                ?>
                <input name="sca" value=""  id="sca" type="text" style="width: 400px"> <button class="button-primary woocommerce-save-button">Save</button>
                <script type="text/javascript">
                    jQuery('#sca').val('<?php echo $sca ?>');
                </script>
            </form>
        </div>
    </fieldset>
	
	<script>
        
        jQuery('#ipp, #ccr').change(function () {
            get_shortcode();
        });
        
        jQuery( "#ss" ).sortable({
            update: function( event, ui ) {
                get_shortcode();
            }
        });
        get_shortcode();
		function get_shortcode() {
			var html = '[wa_waf';
			var atts = '';
			var c = 0;
			jQuery('.waf-att').each(function () {
				if(jQuery(this).is(':checked')){
				    if(c>0){
				        atts = atts + ',';
				    }
				    atts = atts + jQuery(this).val();
					c = c +1;
				}
            });
			if(c>0){
			    html = html + ' filters="'+atts+'"';
			}
			html = html + ' limit='+jQuery('#ipp').val();
			html = html + ']';
			jQuery('#get-shortcode').html(html);
        }
	</script>
</div>
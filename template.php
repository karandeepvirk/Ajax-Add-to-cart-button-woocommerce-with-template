<?php
/**
 * Template Name: Products Home
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since 1.0
 * @version 1.0
 */
get_header();
function get_products_from_category_by_ID( $category_id ) {

    $products_IDs = new WP_Query( array(
        'post_type' => 'product',
        'post_status' => 'publish',
        'fields' => 'ids', 
        'tax_query' => array(
            array(
                'taxonomy' => 'product_cat',
                'field' => 'term_id',
                'terms' => $category_id,
                'operator' => 'IN',
            )
        )
    ) );

    return $products_IDs;
}
?>

<?php
  $taxonomy     = 'product_cat';
  $orderby      = 'name';  
  $show_count   = 1;      // 1 for yes, 0 for no
  $pad_counts   = 0;      // 1 for yes, 0 for no
  $hierarchical = 1;      // 1 for yes, 0 for no  
  $title        = '';  
  $empty        = 0;

  $args = array(
         'taxonomy'     => $taxonomy,
         'orderby'      => $orderby,
         'show_count'   => $show_count,
         'pad_counts'   => $pad_counts,
         'hierarchical' => $hierarchical,
         'title_li'     => $title,
         'hide_empty'   => $empty
  );
 $all_categories = get_categories( $args );
?>
<body class="body-main">
	<div class="container main-container">
		<div class="row head-row">
			<div class="col-md-9">
				<div class="row row-main">
			<?php
			 foreach ($all_categories as $cat) {
			    if($cat->category_parent == 0) {
			        $category_id = $cat->term_id;
			        $catlinkStr = get_term_link($cat->slug, 'product_cat');    
			        $thumbnail_id = get_woocommerce_term_meta( $cat->term_id, 'thumbnail_id', true );
				    $imageurlStr = wp_get_attachment_url( $thumbnail_id );
			      	?>
				 	<div class="col-md-3">
				 		<a href="<?php echo $catlinkStr;?>">
					 		<div class="row">
					 			<div class="col-md-12-main col-md-12 cat-image" style="background-image: url(<?php echo $imageurlStr;?>);"></div>
					 		</div>
				 		</a>
				 		<div class="row row-cat-title bg-info">
				 			<div class="col-md-12"><span class="row-cat-title-span text-center"><?php echo $cat->name;?></span></div>
				 		</div>
				 	</div>
			      	<?php
			    }       
			}?>
				</div>
			</div>
			<!--SECOND PART -->
			<div class="col-md-3 bg-white side-col-main">
				<?php
				$arrProduct = array(4);
				// assuming the list of product IDs is are stored in an array called IDs;
				$_pf = new WC_Product_Factory();  
				foreach ($arrProduct as $id) {	
					$titleStr = get_the_title($id);
					$descriptionStr =  apply_filters('the_content', get_post_field('post_content', $id));
					$post_thumbnail_id = get_post_thumbnail_id( $id );
					$pictureStr = get_the_post_thumbnail_url( $id, 'full');
					$priceStr = get_post_meta($id,'_regular_price',true);
				?>
				<div class="row">
					<div class="col-md-12 cat-image" style="background-image: url(<?php echo $pictureStr;?>);"></div>
				</div>
				<div class="row">
					<div class="col-md-12 side-product-title"><?php echo $titleStr;?></div>
				</div>
				<form action="" method="post" name="form" id="form">
					<div class="row">
						<div class="col-md-3 top-small padding-1 price-wrapper">&nbsp;&nbsp;&nbsp;$&nbsp;&nbsp;
							<span class="price" id="price"></span>
						</div>
						<div class="col-md-6 top-small padding-1">
							<select name="size" class="form-control">
								<option>Size</option>
							</select>
						</div>
						<div class="col-md-3 top-small padding-1">	
							<input type="number" class="form-control" name="number_of_items" value="1" id="number_of_items">
						</div>
					</div>
					<div class="row">
						<div class="col-md-12 top-small">
							<input type="hidden" name="product_id_front" value="<?php echo $id?>">
							<button type="button" id="add_to_cart_ajax_button" name ="add_to_cart_ajax_button" class="btn btn-warning btn-block"><i class="fas fa-shopping-cart"></i> Add to cart <span id="fa-added"></span></button>
						</div>
					</div>
				</form>
				<script>
				jQuery(document).ready(function($){
					var price = <?php echo $priceStr;?>;
					$('#price').html(price);
					$( "#number_of_items" ).change(function() {
						var number_of_items = $('#number_of_items').val();
						$('#price').html(price*number_of_items);	
					});
				});
				</script>
				<?}?>
			</div>
			
		</div>
	</div>
</body>
<script>
jQuery(document).ready(function($) {
    $('#form :input[name="add_to_cart_ajax_button"]').on('click', function(event) {
        event.preventDefault();
        $('#fa-added').html('<i class="fas fa-spinner fa-spin"></i>');
        var objData = {
            action: 'add_to_cart_from_front',
            data: $(this).closest('form').serialize(),
        };
        $.getJSON('<?php echo admin_url('admin-ajax.php'); ?>', objData, function(objResponse) {           
            
            if (objResponse.output.length > 0) {
                $('#fa-added').html('<i class="far fa-check-circle"></i>');
            }
        });
    });
})
</script>
<?php
get_footer();
?>

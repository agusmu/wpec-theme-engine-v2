<?php
/**
 * Whether current product loop has results to loop over.
 *
 * @see   WP_Query::have_posts()
 * @since 4.0
 *
 * @return bool
 */
function wpsc_have_products() {
	return have_posts();
}

/**
 * Iterate the product index of the loop.
 *
 * @see   WP_Query::the_post()
 * @since 4.0
 */
function wpsc_the_product() {
	the_post();
}

/**
 * Return the current product ID in the loop.
 *
 * @since 4.0
 * @uses  get_the_ID()
 *
 * @return int The Product ID
 */
function wpsc_get_product_id() {
	return get_the_ID();
}

/**
 * Output the current product ID in the loop.
 *
 * @since 4.0
 * @uses  the_ID()
 */
function wpsc_product_id() {
	the_ID();
}

/**
 * Output the class attribute of the current product in the loop.
 *
 * @since 4.0
 * @uses  post_class()
 */
function wpsc_product_class( $class = '', $post_id = null ) {
	echo 'class="' . join( ' ', wpsc_get_product_class( $class, $post_id ) ) . '"';
}

function wpsc_get_product_class( $class, $post_id = null ) {
	if ( ! $post_id )
		$post_id = wpsc_get_product_id();

	return get_post_class( $class, $post_id );
}

/**
 * Return the product permalink.
 *
 * @since 4.0
 * @uses  apply_filters() Applies 'wpsc_get_product_permalink' filter
 * @uses  get_permalink()
 *
 * @param  int    $id        Optional. The product ID. Defaults to the current post in the loop.
 * @param  bool   $leavename Optional. Whether to keep product name. Defaults to false.
 * @return string
 */
function wpsc_get_product_permalink( $id = 0, $leavename = false ) {
	if ( ! $id )
		$id = wpsc_get_product_id();

	return apply_filters( 'wpsc_get_product_permalink', get_permalink( $id ) );
}

/**
 * Output the permalink of the current product in the loop.
 *
 * @since 4.0
 * @uses  wpsc_get_product_permalink()
 */
function wpsc_product_permalink( $id = 0 ) {
	echo wpsc_get_product_permalink( $id );
}

/**
 * Sanitize the current title when retrieving or displaying.
 *
 * Works like {@link wpsc_product_title()}, except the parameters can be in a string or
 * an array. See the function for what can be override in the $args parameter.
 *
 * The title before it is displayed will have the tags stripped and {@link
 * esc_attr()} before it is passed to the user or displayed. The default
 * as with {@link wpsc_product_title()}, is to display the title.
 *
 * @since 4.0
 * @uses  esc_attr()
 * @uses  wp_parse_args()
 * @uses  wpsc_get_product_title()
 *
 * @param  string|array $args Optional. Override the defaults.
 * @return string|null  Null on failure or display. String when echo is false.
 */
function wpsc_product_title_attribute( $args = '' ) {
	$title = wpsc_get_product_title();

	if ( strlen($title) == 0 )
		return;

	$defaults = array('before' => '', 'after' =>  '', 'echo' => true);
	$r = wp_parse_args($args, $defaults);
	extract( $r, EXTR_SKIP );

	$title = $before . $title . $after;
	$title = esc_attr(strip_tags($title));

	if ( $echo )
		echo $title;
	else
		return $title;
}

/**
 * Return the title a product.
 *
 * @since 4.0
 * @uses apply_filters() Applies 'wpsc_get_product_title' filter
 * @uses get_the_title()
 *
 * @param  int    $id Optional. The product ID. Defaults to the current post in the loop.
 * @return string
 */
function wpsc_get_product_title( $id = 0 ) {
	return apply_filters( 'wpsc_get_product_title', get_the_title( $id ), $id );
}

/**
 * Output the title of the current product in the loop.
 *
 * @since 4.0
 * @uses  apply_filters()          Applies 'wpsc_product_title' filter.
 * @uses  wpsc_get_product_title()
 *
 * @param  string      $before Optional. Specify HTML before the title. Defaults to ''.
 * @param  string      $after  Optional. Specify HTML after the title. Defaults to ''.
 * @param  bool        $echo   Optional. Whether to output or return the title. Defaults to true.
 * @return null|string
 */
function wpsc_product_title( $before = '', $after = '', $id = 0, $echo = true ) {
	$title = wpsc_get_product_title( $id );

	if ( strlen( $title ) == 0 )
		return;

	$title = $before . $title . $after;

	if ( $echo )
		echo $title;
	else
		return $title;
}

/**
 * Return HTML for the list of product categories.
 *
 * This function accepts a query string or array containing arguments to further customize the HTML
 * output of the list:
 *
 *     'id'        - The product ID for which you want to get the category list. Defaults to current product in the loop.
 *     'before'    - HTML before the list. Defaults to ''.
 *     'after'     - HTML after the list. Defaults to ''.
 *     'separator' - The separator of list items. Defaults to ', '.
 *
 * @since 4.0
 * @uses  get_the_term_list()
 * @uses  wp_parse_args()
 *
 * @param  string|array $args Optional. Specify custom arguments for this function.
 * @return string
 */
function wpsc_get_product_category_list( $args = '' ) {
	$defaults = array(
		'id'        => 0,
		'before'    => '',
		'after'     => '',
		'separator' => __( ', ', 'category list separator', 'wpsc' ),
	);

	$r = wp_parse_args( $args, $defaults );
	extract( $r );

	return get_the_term_list( $id, 'wpsc_product_category', $before, $separator, $after );
}

/**
 * Output the category list of the current product in the loop.
 *
 * @since 4.0
 * @uses  wpsc_get_product_category_list()
 *
 * @param string $args Optional. Defaults to ''. See {@link wpsc_get_product_category_list()} for the full list of arguments you can use to customize the output.
 */
function wpsc_product_category_list( $args = '' ) {
	echo wpsc_get_product_category_list( $args );
}

/**
 * Return HTML for the list of product tags.
 *
 * @since 4.0
 * @uses  get_the_term_list()
 * @uses  wp_parse_args()
 *
 * @param  string|array $args Optional. Defaults to ''. See {@link wpsc_get_product_category_list()} for the full list of arguments you can use to customize the output.
 * @return string
 */
function wpsc_get_product_tag_list( $args = '' ) {
	$defaults = array(
		'id'        => 0,
		'before'    => '',
		'after'     => '',
		'separator' => __( ', ', 'tag list separator', 'wpsc' ),
	);

	$r = wp_parse_args( $args, $defaults );
	extract( $r );
	return get_the_term_list( $id, 'product_tag', $before, $separator, $after );
}

/**
 * Return the number of categories associated with a product.
 *
 * @since 4.0
 * @uses  get_the_terms()
 *
 * @param  int $id Optional. Product ID. Defaults to current product in the loop.
 * @return int
 */
function wpsc_get_product_category_count( $id = 0 ) {
	$cats = get_the_terms( $id, 'wpsc_product_category' );

	if ( $cats === false )
		return 0;

	return count( $cats );
}

/**
 * Return the number of tags associated with a product.
 *
 * @since 4.0
 * @uses  get_the_terms()
 *
 * @param  int $id Optional. Product ID. Defaults to current product in the loop.
 * @return int
 */
function wpsc_get_product_tag_count( $id = 0 ) {
	$tags = get_the_terms( $id, 'product_tag' );

	if ( $tags === false )
		return 0;

	return count( $tags );
}

/**
 * Output the edit link of a product.
 *
 * @since 4.0
 * @uses  apply_filters() Applies 'wpsc_edit_product_link' filter.
 * @uses  edit_post_link()
 * @uses  wp_parse_args()
 *
 * @param  string $args Optional. Defaults to ''.
 */
function wpsc_edit_product_link( $args = '' ) {
	$defaults = array(
		'id'     => 0,
		'before' => '<span class="edit-link">',
		'after'  => '</span>',
		'title'  => _x( 'Edit This Product', 'product edit link template tag', 'wpsc' ),
	);

	$defaults = apply_filters( 'wpsc_edit_product_link_default_args', $defaults );

	$r = wp_parse_args( $args, $defaults );
	extract( $r );
	ob_start();
	edit_post_link( $title, $before, $after, $id );
	$link = ob_get_clean();
	echo apply_filters( 'wpsc_edit_product_link', $link, $id );
}

/**
 * Return the ID of the product thumbnail of a product.
 *
 * @since 4.0
 * @uses  get_post_thumbnail_id()
 *
 * @param  null|int $product_id Optional. The product ID. Defaults to the current product ID in the loop.
 * @return int
 */
function wpsc_get_product_thumbnail_id( $product_id = null ) {
	$parent = get_post_field( 'post_parent', $product_id );

	if ( $parent )
		return wpsc_get_product_thumbnail_id( $parent );

	$thumbnail_id = null;

	// Use product thumbnail
	if ( has_post_thumbnail( $product_id ) ) {
		$thumbnail_id = get_post_thumbnail_id( $product_id  );
	// Use first product image
	} else {
		// Get all attached images to this product
		$attached_images = (array) get_posts( array(
			'post_type'   => 'attachment',
			'numberposts' => 1,
			'post_status' => null,
			'post_parent' => $product_id,
			'order'       => 'ASC'
		) );
		if ( ! empty( $attached_images ) )
			$thumbnail_id = $attached_images[0]->ID;
	}
	return $thumbnail_id;
}

/**
 * Return the HTML of a product's featured thumbnail.
 *
 * Note that the $size argument of this function is different from that of get_the_post_thumbnail().
 * For this function, you can only use these three sizes that correspond to the sizes specified in
 * your Settings -> Store -> Presentation option page:
 *     'single'   - corresponds to "Single Product Image Size" option.
 *     'archive'  - corresponds to "Default Product Thumbnail Size" option.
 *     'taxonomy' - corresponds to "Default Product Group Thumbnail Size" option.
 *
 * @see   wpsc_check_thumbnail_support() Where the thumbnail sizes are registered.
 * @since 4.0
 * @uses  $_wp_additional_image_sizes The array holding registered thumbnail sizes.
 * @uses  get_attached_file()
 * @uses  get_post_meta()
 * @uses  get_the_post_thumbnail()
 * @uses  wp_get_attachment_metadata()
 * @uses  wp_update_attachment_metadata()
 * @uses  wpsc_get_product_thumbnail_id()
 * @uses  wpsc_has_product_thumbnail()
 * @uses  update_post_meta()
 *
 * @param  null|int $id   Optional. The product ID. Defaults to the current product in the loop.
 * @param  string   $size Optional. Size of the product thumbnail. Defaults to 'single'.
 * @param  string   $attr Optional. Query string or array of attributes. Defaults to ''.
 * @return string
 */
function wpsc_get_product_thumbnail( $id = null, $size = false, $attr = '' ) {
	global $_wp_additional_image_sizes;

	$parent = get_post_field( 'post_parent', $id );
	if ( $parent )
		return wpsc_get_product_thumbnail( $parent, $size, $attr );

	if ( ! $size || ! in_array( $size, array( 'archive', 'taxonomy', 'single', 'cart', 'widget' ) ) ) {
		if ( is_archive() )
			$size = 'archive';
		elseif ( is_tax() )
			$size = 'taxonomy';
		elseif ( wpsc_is_cart() )
			$size = 'cart';
		else
			$size = 'single';
	}

	$wp_size = 'wpsc_product_' . $size . '_thumbnail';

	if ( wpsc_has_product_thumbnail( $id ) ) {
		$thumb_id = wpsc_get_product_thumbnail_id( $id );

		// Get the size metadata registered in wpsc_check_thumbnail_support()
		$size_metadata = $_wp_additional_image_sizes[$wp_size];

		// Get the current size metadata that has been generated for this product
		$current_size_metadata = get_post_meta( $thumb_id, '_wpsc_current_size_metadata', true );
		if ( empty( $current_size_metadata ) )
			$current_size_metadata = array();

		// If this thumbnail for the current size was not generated yet, or generated with different
		// parameters (crop, for example), we need to regenerate the thumbnail
		if ( ! array_key_exists( $size, $current_size_metadata ) || $current_size_metadata[$size] != $size_metadata ) {
			_wpsc_regenerate_thumbnail_size( $thumb_id, $wp_size );
		}
	}

	return get_the_post_thumbnail( $id, $wp_size, $attr );
}

/**
 * Output the thumbnail for the current product in the loop.
 *
 * @since 4.0
 * @uses  wpsc_get_product_thumbnail()
 *
 * @param  string $size Optional. Defaults to 'single'. See {@link wpsc_get_product_thumbnail()} for a list of available sizes you can use.
 * @param  string $attr Optional. Query string or array of attributes. Defaults to ''.
 */
function wpsc_product_thumbnail( $size = false, $attr = '' ) {
	echo wpsc_get_product_thumbnail( null, $size, $attr );
}

/**
 * Output a dummy thumbnail image in case the current product in the loop does not have a specified
 * featured thumbnail.
 *
 * @since 0.1
 * @uses  $_wp_additional_image_size The array containing registered image sizes
 * @uses  apply_filters() Applies 'wpsc_product_no_thumbnail_url' filter
 * @uses  apply_filters() Applies 'wpsc_product_no_thumbnail_html' filter
 *
 * @param string $size Optional. If this is not specified, the appropriate size will be detected based on the current page being viewed. See {@link wpsc_get_product_thumbnail()} for a list of available sizes you can use.
 * @param string $attr Optional. Query string or array of attributes. Defaults to ''.
 */
function wpsc_product_no_thumbnail_image( $size = false, $attr = '' ) {
	global $_wp_additional_image_sizes;

	// automatically detect the correct $size if it's not specified
	if ( ! $size ) {
		if ( is_singular( 'wpsc-product' ) )
			$size = 'single';
		elseif ( is_tax( 'wpsc_product_category' ) || is_tax( 'product_tag' ) )
			$size = 'taxonomy';
		elseif ( wpsc_is_cart() || wpsc_is_customer_account() || wpsc_is_checkout() )
			$size = 'cart';
		else
			$size = 'archive';
	}

	$wp_size    = 'wpsc_product_' . $size . '_thumbnail';
	$dimensions = $_wp_additional_image_sizes[$wp_size];

	$title      = wpsc_product_title_attribute( array( 'echo' => false ) );
	$src        = apply_filters( 'wpsc_product_no_thumbnail_url', wpsc_locate_asset_uri( 'images/noimage.png' ), $size, $attr );
	$html       = '<img alt="' . $title . '" src="' . $src . '" title="' . $title . '" width="' . $dimensions['width'] . '" height="' . $dimensions['height'] . '" />';
	$html       = apply_filters( 'wpsc_product_no_thumbnail_html', $html, $size, $attr );

	echo $html;
}

/**
 * Output the description of the current product in the loop.
 *
 * @see   wpsc_get_product_description()
 * @since 4.0
 * @uses  apply_filters() Applies 'the_content' filter
 * @uses  apply_filters() Applies 'wpsc_product_description' filter
 * @uses  wpsc_get_product_description()
 *
 * @param null|string $more_link_text Optional. Content for when there is more text.
 * @param string      $mode           Optional. See {@link wpsc_get_product_description} for a full list of options you can use to customize the output.
 */
function wpsc_product_description( $more_link_text = null, $mode = 'with-teaser' ) {
	$content = wpsc_get_product_description( $more_link_text, $mode );
	$content = apply_filters( 'the_content', $content );
	$content = apply_filters( 'wpsc_product_description', $content );
	$content = str_replace( ']]>', ']]&gt;', $content );
	echo $content;
}

/**
 * Remove the "more" link by hooking into 'the_content_more_link' and return an empty string.
 *
 * @since 4.0
 *
 * @param  string $link
 * @return string An empty string
 */
function wpsc_filter_remove_the_content_more_link( $link ) {
	return '';
}

/**
 * Return the description of the current product in the loop.
 *
 * If your product description has a <!--more--> tag, then only the teaser will be displayed on
 * product listing pages (product catalog, taxonomy etc.). On single product view only the teaser
 * will be displayed.
 *
 * The $more_link_text argument lets you customize the "more" text.
 *
 * The $mode argument can have the following values:
 *     'with-teaser' - The teaser is displayed along with the main description
 *     'only-teaser' - Only the teaser is displayed, the text after <!--more--> tag will be ignored
 *     'no-teaser'   - The teaser is stripped out.
 *
 * @since 4.0
 * @uses  add_filter()      Adds 'wpsc_filter_remove_content_more_link' to 'the_content_more_link' filter
 * @uses  apply_filters()   Applies 'wpsc_get_product_description' filter hook
 * @uses  get_the_content() Retrieves product's description
 * @uses  remove_filter()   Removes 'wpsc_filter_remove_content_more_link' from 'the_content_more_link' filter
 *
 * @param  null|string $more_link_text Optional. The customized text for the "read more" link. Defaults to 'more'.
 * @param  string      $mode           Optional. Specify how to deal with teaser. Defaults to 'with-teaser'.
 * @return string
 */
function wpsc_get_product_description( $more_link_text = null, $mode = 'with-teaser' ) {
	$stripteaser = $mode == 'no-teaser';

	if ( $mode == 'only-teaser' )
		add_filter( 'the_content_more_link', 'wpsc_filter_remove_the_content_more_link', 99 );

	if ( ! $more_link_text )
		$more_link_text = __( 'More details &raquo;', 'wpsc' );

	$content = get_the_content( $more_link_text, $stripteaser );

	if ( $mode == 'only-teaser' ) {
		remove_filter( 'the_content_more_link', 'wpsc_filter_remove_the_content_more_link', 99 );
		$sub = '<span id="more-' . get_the_ID() . '"></span>';
		$pos = strpos( $content, $sub );
		if ( $pos !== false )
			$content = substr( $content, 0, $pos );
	}

	return apply_filters( 'wpsc_get_product_description', $content, $mode );
}

/**
 * Display the drop down listing child variation terms of a variation set associated with a certain
 * product.
 *
 * @since 4.0
 * @uses  wpsc_get_product_variation_set_dropdown()
 *
 * @param  int $variation_set_id The term_id of the variation set.
 * @param  int $product_id       Optional. The product ID. Defaults to the current product in the loop.
 */
function wpsc_product_variation_set_dropdown( $variation_set_id, $product_id = null ) {
	echo wpsc_get_product_variation_set_dropdown( $variation_set_id, $product_id );
}

function wpsc_get_product_variation_dropdown( $args = '' ) {
	$defaults = array(
		'id'               => false,
		'before'           => '',
		'after'            => '',
		'before_variation' => '',
		'after_variation'  => '<br />',
		'before_label'     => '',
		'after_label'      => '',
		'before_dropdown'  => '',
		'after_dropdown'   => '',
	);

	$defaults = apply_filters( 'wpsc_product_variation_dropdown_default_args', $defaults );

	$r = wp_parse_args( $args, $defaults );
	extract( $r, EXTR_SKIP );

	if ( ! empty( $id ) )
		$id = wpsc_get_product_id();

	$product = WPSC_Product::get_instance( $id );

	$variation_sets = $product->variation_sets;
	$output = $before;
	foreach ( $variation_sets as $variation_set_id => $title ) {
		$output .= $before_variation . $before_label;
		$output .= '<label for="wpsc-product-' . $id . '-variation-' . esc_attr( $variation_set_id ) . '">';
		$output .= esc_html( $title );
		$output .= '</label>' . $after_label;
		$output .= $before_dropdown . wpsc_get_product_variation_set_dropdown( $variation_set_id ) . $after_dropdown;
		$output .= $after_variation;
	}
	$output .= $after;

	return $output;
}

function wpsc_product_variation_dropdown( $args = '' ) {
	echo wpsc_get_product_variation_dropdown( $args );
}

/**
 * Return the HTML for variation set dropdown of a certain product.
 *
 * @since 4.0
 * @uses  wpsc_get_product_id()
 *
 * @param  int $variation_set_id The term_id of the variation set.
 * @param  int $product_id       Optional. The product ID. Defaults to the current product in the loop.
 * @return string
 */
function wpsc_get_product_variation_set_dropdown( $variation_set_id, $product_id = null ) {
	if ( empty( $product_id ) )
		$product_id = wpsc_get_product_id();

	$product = WPSC_Product::get_instance( $product_id );

	$classes = apply_filters( 'wpsc_get_product_variation_set_dropdown_classes', array( 'wpsc-product-variation-dropdown' ), $variation_set_id, $this->product_id );
	$classes = implode( ' ', $classes );
	$output = "<select name='wpsc_product_variations[{$variation_set_id}]' id='wpsc-product-{$product_id}-{$variation_set_id}' class='{$classes}'>";
	foreach ( $product->variation_terms[$variation_set_id] as $variation_term_id => $variation_term_title ) {
		$label = esc_attr( $variation_term_title );
		$output .= "<option value='{$variation_term_id}'>{$label}</option>";
	}
	$output .= "</select>";

	return apply_filters( 'wpsc_get_product_variation_set_dropdown', $output, $variation_set_id, $this->product_id );
}

/**
 * Output the original price of a product.
 *
 * See {@link wpsc_get_product_original_price()} for more information about the $format
 * argument.
 *
 * @since 4.0
 * @uses  apply_filters() Applies 'wpsc_product_original_price' filter.
 * @uses  wpsc_get_product_original_price()
 *
 * @param null|int $product_id     Optional. The product ID. Defaults to current product in the loop.
 * @param string   $format Optional. The format of the price. Defaults to 'string'.
 */
function wpsc_product_original_price( $product_id = null, $from_text = true ) {
	echo wpsc_get_product_original_price( $product_id, $from_text );
}

/**
 * Return the original price of a product.
 *
 * The $return_type can be one of the following values:
 *     'string' - Return the price with currency symbol and 2 decimal places (e.g. $10.26)
 *     'float'  - Return an unformatted numeric value with no currency symbol (e.g. 10.259)
 *
 * @since 4.0
 * @uses  apply_filters() Applies 'wpsc_get_product_original_price' filter.
 * @uses  get_post_meta()
 * @uses  wpsc_format_currency()
 * @uses  wpsc_get_product_id()
 * @uses  wpsc_has_product_variations()
 *
 * @param  null|int $product_id  Optional. The product ID. Defaults to the current product in the loop.
 * @param  string   $format      Optional. The format of the price. Defaults to 'string'.
 * @return float|string
 */
function wpsc_get_product_original_price( $product_id = null, $from_text = true ) {
	if ( empty( $product_id ) )
		$product_id = wpsc_get_product_id();

	$product = WPSC_Product::get_instance( $product_id );
	$price = wpsc_format_currency( $product->price );

	if ( $from_text && $product->has_various_prices )
		$price = _wpsc_get_from_text( $price );

	return apply_filters( 'wpsc_get_product_original_price', $price, $product_id, $from_text );
}

function _wpsc_get_from_text( $price ) {
	$from_text = apply_filters( 'wpsc_from_text', __( 'from %s', 'wpsc' ) );
	$from_text = sprintf( $from_text, $price );
	return $from_text;
}

/**
 * Output the sale price of a product.
 *
 * See {@link wpsc_get_product_sale_price()} for more information about the $format argument.
 *
 * @since 4.0
 * @uses  apply_filters() Applies 'wpsc_product_sale_price' filter.
 * @uses  wpsc_get_product_sale_price()
 *
 * @param int    $product_id Optional. The product ID. Defaults to the current product in the loop.
 * @param string $format     Optional. The format of the price. Defaults to 'string'.
 */
function wpsc_product_sale_price( $product_id = null, $from_text = true ) {
	echo wpsc_get_product_sale_price( $product_id, $from_text );
}

function wpsc_product_you_save( $product_id = null, $format = false, $from_text = true ) {
	echo wpsc_get_product_you_save( $product_id, $format, $from_text );
}

/**
 * Return the sale price of a product.
 *
 * The $return_type can be one of the following values:
 *     'string' - Return the price with currency symbol and 2 decimal places (e.g. $10.26)
 *     'float'  - Return an unformatted numeric value with no currency symbol (e.g. 10.259)
 *
 * @since 4.0
 * @uses  apply_filters() Applies 'wpsc_get_product_sale_price' filter.
 * @uses  get_post_meta()
 * @uses  wpsc_format_currency()
 * @uses  wpsc_get_product_id()
 * @uses  wpsc_has_product_variations()
 *
 * @param  null|int $product_id  Optional. The product ID. Defaults to the current product in the loop.
 * @param  string   $format      Optional. The format of the price. Defaults to 'string'.
 * @return float|string
 */
function wpsc_get_product_sale_price( $product_id = null, $from_text = true ) {
	if ( empty( $product_id ) )
		$product_id = wpsc_get_product_id();

	$product = WPSC_Product::get_instance( $product_id );
	$sale_price = wpsc_format_currency( $product->sale_price );

	if ( $from_text && $product->has_various_sale_prices )
		$sale_price = _wpsc_get_from_text( $sale_price );

	return apply_filters( 'wpsc_get_product_sale_price', $sale_price, $product_id, $from_text );
}

function wpsc_get_product_you_save( $product_id = null, $format = false, $from_text = true ) {
	if ( empty( $product_id ) )
		$product_id = wpsc_get_product_id();

	if ( ! $format )
		/* translators: %1$s: saving amount, %2$s: saving percent */
		$format = _x( '%1$s (%2$s)', 'product saving format', 'wpsc' );

	$product = WPSC_Product::get_instance( $product_id );

	$saving = wpsc_format_currency( $product->saving );
	$saving_percent = sprintf(
		/* translators: %1$s: saving percent, %%: percentage sign */
		_x( '%1$s%%', 'product saving percent', 'wpsc' ),
		$product->saving_percent
	);

	$saving_text = sprintf( $format, $saving, $saving_percent );

	if ( $from_text && $product->has_various_savings )
		$saving_text = _wpsc_get_from_text( $saving_text );

	return apply_filters( 'wpsc_get_product_you_save', $saving_text, $product_id, $format, $from_text );
}


/**
 * Wraps the read more link with a custom class.
 *
 * @since 4.0
 * @uses  get_post_type()
 *
 * @param  string $link
 * @return string
 */
function wpsc_filter_content_more_link( $link ) {
	if ( get_post_type( 'post_type' ) == 'wpsc-product' )
		$link = '<p class="wpsc-more-link">' . $link . '</p>';
	return $link;
}
add_filter( 'the_content_more_link', 'wpsc_filter_content_more_link' );

/**
 * Output pagination for the current loop.
 *
 * @since 4.9
 * @uses  wpsc_is_pagination_enabled()
 * @uses  wpsc_get_template_part()
 *
 * @param string $position Position of the pagination div.
 */
function wpsc_product_pagination( $position = 'bottom' ) {
	if ( ! wpsc_is_pagination_enabled( $position ) )
		return;

	echo '<div class="wpsc-pagination wpsc-pagination-' . esc_attr( $position ) . '">';
	wpsc_get_template_part( 'product-pagination', $position );
	echo '</div>';
}

/**
 * Return the number of pages for the current loop.
 *
 * @since 4.0
 *
 * @return int
 */
function wpsc_product_pagination_page_count() {
	global $wp_query;
	return $wp_query->max_num_pages;
}

/**
 * Output the pagination count.
 *
 * @since 4.0
 * @uses apply_filters() Applies 'wpsc_product_pagination_count' filter.
 * @uses get_query_var()
 * @uses wpsc_get_current_page_number()
 */
function wpsc_product_pagination_count() {
	global $wp_query;

	$total        = empty( $wp_query->found_posts ) ? $wp_query->post_count : $wp_query->found_posts;
	$total_pages  = $wp_query->max_num_pages;
	$per_page     = get_query_var( 'posts_per_page' );
	$current_page = wpsc_get_current_page_number();
	$from         = ( $current_page - 1 ) * $per_page + 1;
	$to           = $from + $per_page - 1;
	$post_count   = $wp_query->post_count;

	if ( $to > $total )
		$to = $total;

	if ( $total > 1 ) {
		if ( $from == $to )
			$output = sprintf( __( 'Viewing product %1$s (of %2$s total)', 'wpsc' ), $from, $total );
		elseif ( $total_pages === 1 )
			$output = sprintf( __( 'Viewing %1$s products', 'wpsc' ), $total );
		else
			$output = sprintf( __( 'Viewing %1$s products - %2$s through %3$s (of %4$s total)', 'wpsc' ), $post_count, $from, $to, $total );
	} else {
		$output = sprintf( __( 'Viewing %1$s product', 'wpsc' ), $total );
	}

	// Filter and return
	echo apply_filters( 'wpsc_product_pagination_count', $output );
}

/**
 * Return the current page number of the current loop.
 *
 * @since 4.0
 * @uses  get_query_var()
 *
 * @return int
 */
function wpsc_get_current_page_number() {
	$current = get_query_var( 'paged' );
	if ( $current )
		return $current;

	return 1;
}

/**
 * Return the pagination links for the current loop.
 *
 * See {@link paginate_links()} for the available options that you can use with this function.
 *
 * @since 4.0
 * @uses  $wp_rewrite
 * @uses  apply_filters() Applies 'wpsc_product_pagination_links'      filter.
 * @uses  apply_filters() Applies 'wpsc_product_pagination_links_args' filter.
 * @uses  home_url()
 * @uses  is_rtl()
 * @uses  paginate_links()
 * @uses  wp_parse_args()
 * @uses  WP_Rewrite::using_permalinks()
 * @uses  wpsc_get_current_page_number()
 *
 * @param  string|array $args Query string or an array of options.
 */
function wpsc_get_product_pagination_links( $args = '' ) {
	global $wp_rewrite, $wp_query;

	$base = '';

	if ( wpsc_is_store() )
		$base = home_url( wpsc_get_option( 'store_slug' ) );
	elseif ( wpsc_is_product_category() )
		$base = wpsc_get_product_category_permalink();
	elseif ( wpsc_is_product_tag() )
		$base = wpsc_get_product_tag_permalink();

	if ( $wp_rewrite->using_permalinks() )
		$format = 'page/%#%';
	else
		$format = '?page=%#%';

	$defaults = array(
		'base'      => trailingslashit( $base ) . '%_%',
		'format'    => $format,
		'total'     => $wp_query->max_num_pages,
		'current'   => wpsc_get_current_page_number(),
		'prev_text' => is_rtl() ? __( '&rarr;', 'wpsc' ) : __( '&larr;', 'wpsc' ),
		'next_text' => is_rtl() ? __( '&larr;', 'wpsc' ) : __( '&rarr;', 'wpsc' ),
		'end_size'  => 3,
		'mid_size'  => 2,
	);

	$defaults = apply_filters( 'wpsc_get_product_pagination_links', $defaults );
	$r = wp_parse_args( $args, $defaults );

	return apply_filters( 'wpsc_get_product_pagination_links', paginate_links( $r ) );
}

function wpsc_product_pagination_links( $args = '' ) {
	echo wpsc_get_product_pagination_links( $args );
}

function wpsc_get_category_archive_title() {
	$title = sprintf( __( 'Product Category: %s', 'wpsc' ), '<span>' . single_term_title( '', false ) . '</span>' );
	return apply_filters( 'wpsc_get_category_archive_title', $title );
}

function wpsc_category_archive_title() {
	echo wpsc_get_category_archive_title();
}
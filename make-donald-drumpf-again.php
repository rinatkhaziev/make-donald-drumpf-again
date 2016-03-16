<?php
/*
Plugin Name: Make Donald Drumpf Again
Plugin URI: http:/github.com/rinatkhaziev/make-donald-drumpf-again/
Description: Replaces all mentions of Donald Trump on your website with Donald Drumpf
Version: 0.1
Author: Rinat Khaziev
Author URI:
*/

/**
 * Copyright (c) `2016` Rinat Khaziev. All rights reserved.
 *
 * Released under the GPL license
 * http://www.opensource.org/licenses/gpl-license.php
 *
 * This is an add-on for WordPress
 * http://wordpress.org/
 *
 * **********************************************************************
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * **********************************************************************
 */
add_action( 'init', 'make_donald_drumpf_init' );
function make_donald_drumpf_init() {
	// Let's not make donald drumpf in admin
	// To avoid confusion
	if ( is_admin() )
		return;

	$trumps = array(
		'the_content',
		'the_title',
		'get_comment_excerpt',
		'comment_text_rss',
		'get_comment_text',
		'category_description',
		'single_cat_title',
		'single_tag_title',
		'single_term_title',
		'the_category',
		'get_the_excerpt',
		'single_post_title',
		'wp_title',
		'wp_list_categories',
	);

	// First, let's attach simple string filters
	foreach( $trumps as $drumpf_hook ) {
		add_filter( $drumpf_hook, 'make_donald_drumpf_again', 1787 );
	}

	// Now let's do taxonomies/other arrays
	add_filter( 'get_the_terms', 'make_donald_drumpf_again_arr', 1787 );
}

/**
 * Handle arrays (for taxonomies and such)
 * @param  array $trumps [description]
 * @return [type]         [description]
 */
function make_donald_drumpf_again_arr( $trumps = false ) {
	// Bail if not an array passed
	if ( ! is_array( $trumps ) )
		return $trumps;

	// Iterate over each item
	// And either run array map
	foreach( $trumps as $index => $trump ) {
		if ( is_array( $trump ) )
			$trumps[ $index ] = array_map( 'make_donald_drumpf_again', $trump );
		elseif( is_object( $trump ) ) {
			$trumps[ $index ] = make_donald_drumpf_obj( $trump );
		}
	}

	return $trumps;
}

/**
 * Bread and butter of the plugin
 * @param  string $trump Trump
 * @return string        Drumpf!
 */
function make_donald_drumpf_again( $trump = '' ) {
	// Not a string, not a Trump
	if ( ! is_string( $trump ) )
		return $trump;

	return str_replace( 'Trump', 'Drumpf', $trump );
}

/**
 * Iterate over object properties, and run replace on string values
 * @param  object &$donald Trump object to be filtered
 * @return object          Drumpf
 */
function make_donald_drumpf_obj( &$donald ) {
	foreach( $donald as $prop => $val ) {
		// Type checking is handled in make_donald_drumpf_again
		$donald->{$prop} = make_donald_drumpf_again( $val );
	}

	return $donald;
}
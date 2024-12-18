<?php
// Register ACF blocks.
add_action('init', 'register_acf_blocks');
function register_acf_blocks() {
    register_block_type(__DIR__ . '/blocks/card');
}

// Create custom page endpoint to convert blocks to components.
add_action('rest_api_init', function() {
	register_rest_route('starlight/v1', '/pages', array(
		'methods' => 'GET',
		'callback' => 'starlight_get_pages',
	));
	register_rest_route('starlight/v1', '/page/(?P<slug>\S+)', array(
		'methods' => 'GET',
		'callback' => 'starlight_get_page',
	));
});

function starlight_get_page($data) {
	$slug = $data['slug'];
	$page = get_posts(
		array(
			'name' 				=> $slug,
			'post_type' 		=> 'page',
			'post_status' 		=> 'publish',
			'posts_per_page' 	=> 1
		)
	);

	if (empty($page) || is_wp_error($page)) return null;

	$post_content = $page[0]->post_content;

	$data = [
		'id' => $page[0]->ID,
		'title' => $page[0]->post_title,
		'slug' => $page[0]->post_name
	];

	if (has_blocks($post_content)) {
		$data['content'] = parse_blocks($post_content);
	}

	return $data;
}

function starlight_get_pages($data) {
	$pages = get_posts(
		array(
			'post_type' 		=> 'page',
			'post_status' 		=> 'publish',
			'posts_per_page' 	=> -1
		)
	);

	if (empty($pages) || is_wp_error($pages)) return null;

	$data = [];

	foreach ($pages as $index => $page) {
		$post_content = $page->post_content;
		if (has_blocks($post_content)) {
			$data[] = [
				'id' => $page->ID,
				'slug' => $page->post_name,
				'content' => parse_blocks($post_content)
			];
		}
	}

	return $data;
}
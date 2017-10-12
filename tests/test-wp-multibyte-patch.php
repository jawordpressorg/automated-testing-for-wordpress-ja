<?php

class WP_Multibyte_Patch_Test extends WP_UnitTestCase
{
    public function setUp()
    {
		parent::setUp();
		switch_to_locale( 'ja' );
	}

	/**
	 * The length of the excerpt should be 110.
	 */
	function test_length_of_excerpt_should_be_110()
	{
        $args = array(
            'post_title' => 'Hello',
            'post_author' => 1,
            'post_content' => str_repeat( 'あ', 200 ),
            'post_excerpt' => '',
            'post_status' => 'publish',
            'post_date' => '2014-01-01 00:00:00',
        );
        $this->setup_postdata( $args );

        $this->expectOutputString( "<p>" . str_repeat( 'あ', 110 ) . " [&hellip;]</p>\n" );
        the_excerpt();
	}

	/**
	 * The length of the excerpt should be 110.
	 */
	function test_length_of_excerpt_rss_should_be_110()
	{
        $args = array(
            'post_title' => 'Hello',
            'post_author' => 1,
            'post_content' => str_repeat( 'あ', 200 ),
            'post_excerpt' => '',
            'post_status' => 'publish',
            'post_date' => '2014-01-01 00:00:00',
        );
        $this->setup_postdata( $args );

        $this->expectOutputString( str_repeat( 'あ', 110 ) . " [&#8230;]" );
        the_excerpt_rss();
	}

    /**
     * Add post and post be set to current.
     *
     * @param  array $args A hash array of the post object.
     * @return none
     */
    public function setup_postdata( $args )
    {
        global $post;
        global $wp_query;

        $wp_query->is_singular = true;

        $post_id = $this->factory->post->create( $args );
        $post = get_post( $post_id );
        setup_postdata( $post );
    }
}

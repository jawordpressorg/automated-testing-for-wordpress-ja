<?php

class WP_Multibyte_Patch_Test extends WP_UnitTestCase
{
    public function setUp()
    {
		parent::setUp();
	}

	/**
	 * The length of the excerpt should be 110.
	 */
	public function test_length_of_excerpt_should_be_110()
	{
        $args = array(
            'post_content' => str_repeat( 'あ', 200 ),
            'post_excerpt' => '',
        );
        $this->setup_postdata( $args );

		$expect = "<p>" . str_repeat( 'あ', 110 ) . " [&hellip;]</p>\n";

        $this->expectOutputString( $expect );
        the_excerpt();
	}

	/**
	 * The length of the excerpt should be 110.
	 */
	public function test_length_of_excerpt_rss_should_be_110()
	{
        $args = array(
            'post_content' => str_repeat( 'あ', 200 ),
            'post_excerpt' => '',
        );
        $this->setup_postdata( $args );

		$expect = str_repeat( 'あ', 110 ) . " [&#8230;]";

        $this->expectOutputString( $expect );
        the_excerpt_rss();
	}

	/**
	 * Double-width space should be used as search delimiter.
	 */
	public function test_double_width_space_should_be_used_as_search_delimiter()
	{
		$_GET['s'] = 'こんにちは　ようこそ';

		$this->factory->post->create( array(
            'post_content' => 'こんにちは。WordPressへようこそ。',
		) );

		do_action( 'sanitize_comment_cookies' );

		$q = new WP_Query( array(
			's' => $_GET['s']
		) );

		$this->assertSame( 1, count( $q->posts ) );
	}

	/**
	 * The length of the draft's summary should be 40.
	 *
     * @runInSeparateProcess
     * @preserveGlobalState disabled
	 */
	public function test_wp_dashboard_recent_drafts_length_should_be_40() {
		define( 'WP_ADMIN', true );

		$content = str_repeat( 'あ', 50 );
		$content_summary = wp_trim_words( $content, 10,  '&hellip;' );

		$expect = str_repeat( 'あ', 40 ) . '&hellip;';
		$this->assertEquals( $expect, $content_summary );
	}

	/**
	 * The length of the comment on dashboard should be 40.
	 */
	public function test_length_of_comment_excerpt_should_be_40()
	{
		$post_id = self::factory()->post->create();
		$args = array(
			'comment_content' => str_repeat( 'あ', 100 ),
		);

		$comment_id = self::factory()->comment->create_post_comments( $post_id, 1 , $args );
		$comment = self::factory()->comment->get_object_by_id( $comment_id[0] );
		$this->expectOutputString( str_repeat( 'あ', 40 ) . '&hellip;' );
		comment_excerpt( $comment );
	}

	/**
	 * The CSS of the WP multibyte patch should be loaded.
	 */
	public function test_css_of_wpmp_should_be_loaded()
	{
        do_action( 'admin_enqueue_scripts' );

		$this->assertTrue( wp_style_is( 'wpmp-admin-custom' ) );
		$this->assertContains( 'wpmp-admin-custom', get_echo( 'wp_print_styles' ) );
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

        $post = $this->factory->post->create_and_get( $args );
        setup_postdata( $post );
    }
}

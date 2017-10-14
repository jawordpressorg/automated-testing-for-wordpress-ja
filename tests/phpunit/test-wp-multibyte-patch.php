<?php

define( 'WP_ADMIN', true );
class WP_Multibyte_Patch_Test extends WP_UnitTestCase
{
    public function setUp()
    {
		parent::setUp();
	}

	/**
	 * The length of the excerpt should be 110.
	 */
	function test_length_of_excerpt_should_be_110()
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
	function test_length_of_excerpt_rss_should_be_110()
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
	 * The length of the draft's summary should be 40.
	 */
	function test_wp_dashboard_recent_drafts_length_should_be_40() {
		$content = str_repeat( 'あ', 50 );
		$content_summary = wp_trim_words( $content, 10,  '&hellip;' );

		$expect = str_repeat( 'あ', 40 ) . '&hellip;';
		$this->assertEquals( $expect, $content_summary );
	}

	/**
	 * The length of the comment on dashboard should be 40.
	 */
	function test_length_of_comment_excerpt_should_be_40()
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
	 * Confirm if italic font has been disabled.
	 * Just check if it's imported css file.
	 */
	function test_if_disabled_italic_font_style()
	{
		$multibyte_patch_ext = new multibyte_patch_ext();
		$multibyte_patch_ext->admin_custom_css();

		$expect = "/wordpress-tests-lib/data/wp-multibyte-patch/ext/ja/admin.css?ver=20131223'";

		$this->assertContains( $expect, get_echo( 'wp_print_styles' ) );
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

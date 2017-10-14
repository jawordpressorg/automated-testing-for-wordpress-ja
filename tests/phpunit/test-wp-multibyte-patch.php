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

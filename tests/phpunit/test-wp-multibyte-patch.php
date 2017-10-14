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
	function test_length_of_excerpt_should_be_110()
	{
        $args = array(
            'post_content' => str_repeat( 'あ', 200 ),
            'post_excerpt' => '',
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
            'post_content' => str_repeat( 'あ', 200 ),
            'post_excerpt' => '',
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

        $post = $this->factory->post->create_and_get( $args );
        setup_postdata( $post );
    }
}

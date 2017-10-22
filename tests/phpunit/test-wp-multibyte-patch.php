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

		$this->assertTrue( !! count( $q->posts ) );
	}

	/**
	 * `wp_mail` should encode as expected
	 */
	public function test_wp_mail_should_be_sent_as_iso_2022_jp()
	{
		$to = 'hello@example.com';
		$subject = "こんにちは";
		$message = "日本語のメール";

		wp_mail( $to, $subject, $message );
		$mailer = tests_retrieve_phpmailer_instance();

		$this->assertSame( 'hello@example.com', $mailer->get_recipient( 'to' )->address );

		// Subject should be encoded as MIME header field
		$this->assertSame(
			"=?ISO-2022-JP?B?GyRCJDMkcyRLJEEkTxsoQg==?=",
			$mailer->get_sent()->subject
		);
		$this->assertSame(
			"こんにちは",
			iconv_mime_decode( $mailer->get_sent()->subject, 0, 'UTF-8' )
		);

		// Body should be encoded as ISO-2022-JP
		$this->assertSame(
			"日本語のメール\n",
			mb_convert_encoding( $mailer->get_sent()->body, 'UTF-8', 'ISO-2022-JP' )
		);
	}

	/**
	 * Japanese filename should be sanitized as expected.
	 */
	public function test_filename_should_be_sanitized_by_md5()
	{
		$filename = '日本語.png';

		$sanitized = sanitize_file_name( '日本語.png' );
		$this->assertSame( "00110af8b4393ef3f72c50be5b332bec.png", $sanitized );

		$dir = wp_upload_dir();
		$upload_dir = $dir['basedir'];
		file_put_contents( $upload_dir . '/' . $sanitized, '' );

		// The filename should be unique
		$filename = wp_unique_filename( $upload_dir, $filename );
		$this->assertSame( "00110af8b4393ef3f72c50be5b332bec-1.png", $filename );
	}

	/**
	 * The length of the draft's summary should be 40.
	 *
     * @runInSeparateProcess
     * @preserveGlobalState disabled
	 */
	public function test_wp_dashboard_recent_drafts_length_should_be_40()
	{
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
    private function setup_postdata( $args )
    {
        global $post;

        $post = $this->factory->post->create_and_get( $args );
        setup_postdata( $post );
    }
}

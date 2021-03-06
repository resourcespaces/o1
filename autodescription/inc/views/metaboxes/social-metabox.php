<?php

defined( 'ABSPATH' ) and $_this = the_seo_framework_class() and $this instanceof $_this or die;

//* Fetch the required instance within this file.
$instance = $this->get_view_instance( 'the_seo_framework_social_metabox', $instance );

switch ( $instance ) :
	case 'the_seo_framework_social_metabox_main' :
		/**
		 * Parse tabs content.
		 *
		 * @since 2.2.2
		 *
		 * @param array $default_tabs { 'id' = The identifier =>
		 *			array(
		 *				'name' 		=> The name
		 *				'callback' 	=> The callback function, use array for method calling (accepts $this, but isn't used here for optimization purposes)
		 *				'dashicon'	=> Desired dashicon
		 *			)
		 * }
		 */
		$default_tabs = array(
			'general' => array(
				'name' 		=> __( 'General', 'autodescription' ),
				'callback'	=> array( $this, 'social_metabox_general_tab' ),
				'dashicon'	=> 'admin-generic',
			),
			'facebook' => array(
				'name'		=> 'Facebook',
				'callback'	=> array( $this, 'social_metabox_facebook_tab' ),
				'dashicon'	=> 'facebook-alt',
			),
			'twitter' => array(
				'name'		=> 'Twitter',
				'callback'	=> array( $this, 'social_metabox_twitter_tab' ),
				'dashicon'	=> 'twitter',
			),
			'postdates' => array(
				'name'		=> __( 'Post Dates', 'autodescription' ),
				'callback'	=> array( $this, 'social_metabox_postdates_tab' ),
				'dashicon'	=> 'backup',
			),
			'relationships' => array(
				'name'		=> __( 'Link Relationships', 'autodescription' ),
				'callback'	=> array( $this, 'social_metabox_relationships_tab' ),
				'dashicon'	=> 'leftright',
			),
		);

		/**
		 * Applies filters the_seo_framework_social_settings_tabs : array see $default_tabs
		 *
		 * Used to extend Social tabs
		 */
		$defaults = (array) apply_filters( 'the_seo_framework_social_settings_tabs', $default_tabs, $args );

		$tabs = wp_parse_args( $args, $defaults );

		$this->nav_tab_wrapper( 'social', $tabs, '2.2.2' );
		break;

	case 'the_seo_framework_social_metabox_general' :

		?><h4><?php esc_html_e( 'Site Shortlink Settings', 'autodescription' ); ?></h4><?php
		$this->description( __( 'The shortlink tag might have some use for 3rd party service discoverability, but it has little to no SEO value whatsoever.', 'autodescription' ) );

		//* Echo checkboxes.
		$this->wrap_fields(
			$this->make_checkbox(
				'shortlink_tag',
				__( 'Output shortlink tag?', 'autodescription' ),
				'',
				true
			),
			true
		);

		?>
		<hr>

		<h4><?php esc_html_e( 'Social Meta Tags Settings', 'autodescription' ); ?></h4>
		<?php
		$this->description( __( 'Output various meta tags for social site integration, among other 3rd party services.', 'autodescription' ) );

		?><hr><?php

		//* Echo Open Graph Tags checkboxes.
		$this->wrap_fields(
			$this->make_checkbox(
				'og_tags',
				__( 'Output Open Graph meta tags?', 'autodescription' ),
				__( 'Facebook, Twitter, Pinterest and many other social sites make use of these tags.', 'autodescription' ),
				true
			),
			true
		);

		if ( $this->detect_og_plugin() )
			$this->description( __( 'Note: Another Open Graph plugin has been detected.', 'autodescription' ) );

		?><hr><?php

		//* Echo Facebook Tags checkbox.
		$this->wrap_fields(
			$this->make_checkbox(
				'facebook_tags',
				__( 'Output Facebook meta tags?', 'autodescription' ),
				sprintf( __( 'Output various tags targetted at %s.', 'autodescription' ), 'Facebook' ),
				true
			),
			true
		);

		?><hr><?php

		//* Echo Twitter Tags checkboxes.
		$this->wrap_fields(
			$this->make_checkbox(
				'twitter_tags',
				__( 'Output Twitter meta tags?', 'autodescription' ),
				sprintf( __( 'Output various tags targetted at %s.', 'autodescription' ), 'Twitter' ),
				true
			),
			true
		);

		if ( $this->detect_twitter_card_plugin() ) {
			$this->description( __( 'Note: Another Twitter Card plugin has been detected.', 'autodescription' ) );
		}
		break;

	case 'the_seo_framework_social_metabox_facebook' :

		$fb_author = $this->get_field_value( 'facebook_author' );
		$fb_author_placeholder = empty( $fb_publisher ) ? _x( 'http://www.facebook.com/YourPersonalProfile', 'Example Facebook Personal URL', 'autodescription' ) : '';

		$fb_publisher = $this->get_field_value( 'facebook_publisher' );
		$fb_publisher_placeholder = empty( $fb_publisher ) ? _x( 'http://www.facebook.com/YourVerifiedBusinessProfile', 'Example Verified Facebook Business URL', 'autodescription' ) : '';

		$fb_appid = $this->get_field_value( 'facebook_appid' );
		$fb_appid_placeholder = empty( $fb_appid ) ? '123456789012345' : '';

		?><h4><?php esc_html_e( 'Default Facebook Integration Settings', 'autodescription' ); ?></h4><?php
		$this->description( __( 'Facebook post sharing works mostly through Open Graph. However, you can also link your Business and Personal Facebook pages, among various other options.', 'autodescription' ) );
		$this->description( __( 'When these options are filled in, Facebook might link your Facebook profile to be followed and liked when your post or page is shared.', 'autodescription' ) );

		?>
		<hr>

		<p>
			<label for="<?php $this->field_id( 'facebook_author' ); ?>">
				<strong><?php esc_html_e( 'Article Author Facebook URL', 'autodescription' ); ?></strong>
				<a href="<?php echo esc_url( 'https://facebook.com/me' ); ?>" class="description" target="_blank" title="<?php esc_attr_e( 'Your Facebook Profile', 'autodescription' ); ?>">[?]</a>
			</label>
		</p>
		<p>
			<input type="text" name="<?php $this->field_name( 'facebook_author' ); ?>" class="large-text" id="<?php $this->field_id( 'facebook_author' ); ?>" placeholder="<?php echo esc_attr( $fb_author_placeholder ); ?>" value="<?php echo esc_attr( $fb_author ); ?>" />
		</p>

		<p>
			<label for="<?php $this->field_id( 'facebook_publisher' ); ?>">
				<strong><?php esc_html_e( 'Article Publisher Facebook URL', 'autodescription' ); ?></strong>
				<a href="<?php echo esc_url( 'https://instantarticles.fb.com/' ); ?>" class="description" target="_blank" title="<?php esc_html_e( 'To use this, you need to be a verified business', 'autodescription' ); ?>">[?]</a>
			</label>
		</p>
		<p>
			<input type="text" name="<?php $this->field_name( 'facebook_publisher' ); ?>" class="large-text" id="<?php $this->field_id( 'facebook_publisher' ); ?>" placeholder="<?php echo esc_attr( $fb_publisher_placeholder ); ?>" value="<?php echo esc_attr( $fb_publisher ); ?>" />
		</p>

		<p>
			<label for="<?php $this->field_id( 'facebook_appid' ); ?>">
				<strong><?php esc_html_e( 'Facebook App ID', 'autodescription' ); ?></strong>
				<a href="<?php echo esc_url( 'https://developers.facebook.com/apps' ); ?>" target="_blank" class="description" title="<?php esc_html_e( 'Get Facebook App ID', 'autodescription' ); ?>">[?]</a>
			</label>
		</p>
		<p>
			<input type="text" name="<?php $this->field_name( 'facebook_appid' ); ?>" class="large-text" id="<?php $this->field_id( 'facebook_appid' ); ?>" placeholder="<?php echo esc_attr( $fb_appid_placeholder ); ?>" value="<?php echo esc_attr( $fb_appid ); ?>" />
		</p>
		<?php
		break;

	case 'the_seo_framework_social_metabox_twitter' :

		$tw_site = $this->get_field_value( 'twitter_site' );
		$tw_site_placeholder = empty( $tw_site ) ? _x( '@your-site-username', 'Twitter @username', 'autodescription' ) : '';

		$tw_creator = $this->get_field_value( 'twitter_creator' );
		$tw_creator_placeholder = empty( $tw_creator ) ? _x( '@your-personal-username', 'Twitter @username', 'autodescription' ) : '';

		$twitter_card = $this->get_twitter_card_types();

		?><h4><?php esc_html_e( 'Default Twitter Integration Settings', 'autodescription' ); ?></h4><?php
		$this->description( __( 'Twitter post sharing works mostly through Open Graph. However, you can also link your Business and Personal Twitter pages, among various other options.', 'autodescription' ) );

		?>
		<hr>

		<fieldset id="tsf-twitter-cards">
			<legend><h4><?php esc_html_e( 'Twitter Card Type', 'autodescription' ); ?></h4></legend>
			<?php $this->description_noesc( sprintf( esc_html__( 'What kind of Twitter card would you like to use? It will default to %s if no image is found.', 'autodescription' ), $this->code_wrap( 'summary' ) ) ); ?>

			<p class="tsf-fields">
			<?php
				foreach ( $twitter_card as $type => $name ) {
					?>
					<span class="tsf-toblock">
						<input type="radio" name="<?php $this->field_name( 'twitter_card' ); ?>" id="<?php $this->field_id( 'twitter_card_' . $type ); ?>" value="<?php echo esc_attr( $type ); ?>" <?php checked( $this->get_field_value( 'twitter_card' ), $type ); ?> />
						<label for="<?php $this->field_id( 'twitter_card_' . $type ); ?>">
							<span><?php echo $this->code_wrap( $name ); ?></span>
							<a class="description" href="<?php echo esc_url( 'https://dev.twitter.com/cards/types/' . $name ); ?>" target="_blank" title="Twitter Card <?php echo esc_attr( $name ) . ' ' . esc_attr__( 'Example', 'autodescription' ); ?>"><?php esc_html_e( 'Example', 'autodescription' ); ?></a>
						</label>
					</span>
					<?php
				}
			?>
			</p>
		</fieldset>

		<hr>

		<?php $this->description( __( 'When the following options are filled in, Twitter might link your Twitter Site or Personal Profile when your post or page is shared.', 'autodescription' ) ); ?>

		<p>
			<label for="<?php $this->field_id( 'twitter_site' ); ?>" class="tsf-toblock">
				<strong><?php esc_html_e( "Your Website's Twitter Profile", 'autodescription' ); ?></strong>
				<a href="<?php echo esc_url( 'https://twitter.com/home' ); ?>" target="_blank" class="description" title="<?php esc_html_e( 'Find your @username', 'autodescription' ); ?>">[?]</a>
			</label>
		</p>
		<p>
			<input type="text" name="<?php $this->field_name( 'twitter_site' ); ?>" class="large-text" id="<?php $this->field_id( 'twitter_site' ); ?>" placeholder="<?php echo esc_attr( $tw_site_placeholder ); ?>" value="<?php echo esc_attr( $tw_site ); ?>" />
		</p>

		<p>
			<label for="<?php $this->field_id( 'twitter_creator' ); ?>" class="tsf-toblock">
				<strong><?php esc_html_e( 'Your Personal Twitter Profile', 'autodescription' ); ?></strong>
				<a href="<?php echo esc_url( 'https://twitter.com/home' ); ?>" target="_blank" class="description" title="<?php esc_attr_e( 'Find your @username', 'autodescription' ); ?>">[?]</a>
			</label>
		</p>
		<p>
			<input type="text" name="<?php $this->field_name( 'twitter_creator' ); ?>" class="large-text" id="<?php $this->field_id( 'twitter_creator' ); ?>" placeholder="<?php echo esc_attr( $tw_creator_placeholder ); ?>" value="<?php echo esc_attr( $tw_creator ); ?>" />
		</p>
		<?php
		break;

	case 'the_seo_framework_social_metabox_postdates' :

		$pages_i18n = esc_html__( 'Pages', 'autodescription' );
		$posts_i18n = esc_html__( 'Posts', 'autodescription' );
		$home_i18n = esc_html__( 'Home Page', 'autodescription' );

		?><h4><?php esc_html_e( 'Post Date Settings', 'autodescription' ); ?></h4><?php
		$this->description( __( 'Some Search Engines output the publishing date and modified date next to the search results. These help Search Engines find new content and could impact the SEO value.', 'autodescription' ) );
		$this->description( __( "It's recommended on posts, but it's not recommended on pages unless you modify or create new pages frequently.", 'autodescription' ) );

		/* translators: 1: Option, 2: Post Type */
		$post_publish_time_label = sprintf( esc_html__( 'Add %1$s to %2$s?', 'autodescription' ), $this->code_wrap( 'article:published_time' ), $posts_i18n );
		$post_publish_time_checkbox = $this->make_checkbox( 'post_publish_time', $post_publish_time_label, '', false );

		/* translators: 1: Option, 2: Post Type */
		$page_publish_time_label = sprintf( esc_html__( 'Add %1$s to %2$s?', 'autodescription' ), $this->code_wrap( 'article:published_time' ), $pages_i18n );
		$page_publish_time_checkbox = $this->make_checkbox( 'page_publish_time', $page_publish_time_label, '', false );

		//* Echo checkboxes.
		$this->wrap_fields( $post_publish_time_checkbox . $page_publish_time_checkbox, true );

		/* translators: 1: Option, 2: Post Type */
		$post_modify_time_label = sprintf( esc_html__( 'Add %1$s to %2$s?', 'autodescription' ), $this->code_wrap( 'article:modified_time' ), $posts_i18n );
		$post_modify_time_checkbox = $this->make_checkbox( 'post_modify_time', $post_modify_time_label, '', false );

		/* translators: 1: Option, 2: Post Type */
		$page_modify_time_label = sprintf( esc_html__( 'Add %1$s to %2$s?', 'autodescription' ), $this->code_wrap( 'article:modified_time' ), $pages_i18n );
		$page_modify_time_checkbox = $this->make_checkbox( 'page_modify_time', $page_modify_time_label, '', false );

		//* Echo checkboxes.
		$this->wrap_fields( $post_modify_time_checkbox . $page_modify_time_checkbox, true );

		?>
		<hr>

		<h4><?php esc_html_e( 'Home Page', 'autodescription' ); ?></h4>
		<?php
		$this->description( __( 'Because you only publish the Home Page once, Search Engines might think your website is outdated. This can be prevented by disabling the following options.', 'autodescription' ) );

		/* translators: 1: Option, 2: Post Type */
		$home_publish_time_label = sprintf( esc_html__( 'Add %1$s to %2$s?', 'autodescription' ), $this->code_wrap( 'article:published_time' ), $home_i18n );
		$home_publish_time_checkbox = $this->make_checkbox( 'home_publish_time', $home_publish_time_label, '', false );

		/* translators: 1: Option, 2: Post Type */
		$home_modify_time_label = sprintf( esc_html__( 'Add %1$s to %2$s?', 'autodescription' ), $this->code_wrap( 'article:modified_time' ), $home_i18n );
		$home_modify_time_checkbox = $this->make_checkbox( 'home_modify_time', $home_modify_time_label, '', false );

		//* Echo checkboxes.
		$this->wrap_fields( $home_publish_time_checkbox . $home_modify_time_checkbox, true );
		break;

	case 'the_seo_framework_social_metabox_relationships' :

		?><h4><?php esc_html_e( 'Link Relationship Settings', 'autodescription' ); ?></h4><?php
		$this->description( __( 'Some Search Engines look for relations between the content of your pages. If you have multiple pages for a single Post or Page, or have archives indexed, this option will help Search Engines look for the right page to display in the Search Results.', 'autodescription' ) );
		$this->description( __( "It's recommended to turn this option on for better SEO consistency and to prevent duplicate content errors.", 'autodescription' ) );

		?><hr><?php

		/* translators: %s = <code>rel</code> */
		$prev_next_posts_label = sprintf( esc_html__( 'Add %s link tags to Posts and Pages?', 'autodescription' ), $this->code_wrap( 'rel' ) );
		$prev_next_posts_checkbox = $this->make_checkbox( 'prev_next_posts', $prev_next_posts_label, '', false );

		/* translators: %s = <code>rel</code> */
		$prev_next_archives_label = sprintf( esc_html__( 'Add %s link tags to Archives?', 'autodescription' ), $this->code_wrap( 'rel' ) );
		$prev_next_archives_checkbox = $this->make_checkbox( 'prev_next_archives', $prev_next_archives_label, '', false );

		/* translators: %s = <code>rel</code> */
		$prev_next_frontpage_label = sprintf( esc_html__( 'Add %s link tags to the Home Page?', 'autodescription' ), $this->code_wrap( 'rel' ) );
		$prev_next_frontpage_checkbox = $this->make_checkbox( 'prev_next_frontpage', $prev_next_frontpage_label, '', false );

		//* Echo checkboxes.
		$this->wrap_fields( $prev_next_posts_checkbox . $prev_next_archives_checkbox . $prev_next_frontpage_checkbox, true );
		break;

	default :
		break;
endswitch;

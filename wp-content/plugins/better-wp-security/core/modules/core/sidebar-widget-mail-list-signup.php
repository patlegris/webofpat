<?php

class ITSEC_Settings_Page_Sidebar_Widget_Mail_List_Signup extends ITSEC_Settings_Page_Sidebar_Widget {
	public function __construct() {
		$this->id = 'mail-list-signup';
		$this->title = __( 'Download Our WordPress Security Pocket Guide', 'better-wp-security' );
		$this->priority = 6;
		$this->settings_form = false;

		parent::__construct();
	}

	public function render( $form ) {
		?>

		<div id="mc_embed_signup">
			<form
				action="https://ithemes.us2.list-manage.com/subscribe/post?u=7acf83c7a47b32c740ad94a4e&amp;id=5176bfed9e"
				method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate"
				target="_blank" novalidate>
				<div style="text-align: center;">
					<img src="<?php echo plugins_url( 'img/security-ebook.png', __FILE__ ) ?>" width="145" height="187" alt="WordPress Security - A Pocket Guide">
				</div>
				<p><?php _e( 'Get tips for securing your site + the latest WordPress security updates, news and releases from iThemes.', 'better-wp-security' ); ?></p>

				<div id="mce-responses" class="clear">
					<div class="response" id="mce-error-response" style="display:none"></div>
					<div class="response" id="mce-success-response" style="display:none"></div>
				</div>
				<label for="mce-EMAIL" style="display: block;margin-bottom: 3px;"><?php _e( 'Email Address', 'better-wp-security' ); ?></label>
				<input type="email" value="" name="EMAIL" class="required email" id="mce-EMAIL" placeholder="email@domain.com"> <br/><br/>
				<input type="submit" value="<?php _e( 'Subscribe', 'better-wp-security' ); ?>" name="subscribe" id="mc-embedded-subscribe" class="button button-secondary">
			</form>
		</div>
		<script type='text/javascript' src='//s3.amazonaws.com/downloads.mailchimp.com/js/mc-validate.js'></script><script type='text/javascript'>(function($) {window.fnames = new Array(); window.ftypes = new Array();fnames[0]='EMAIL';ftypes[0]='email';fnames[1]='FNAME';ftypes[1]='text';fnames[2]='LNAME';ftypes[2]='text';}(jQuery));var $mcj = jQuery.noConflict(true);</script>

		<?php
	}

}
new ITSEC_Settings_Page_Sidebar_Widget_Mail_List_Signup();

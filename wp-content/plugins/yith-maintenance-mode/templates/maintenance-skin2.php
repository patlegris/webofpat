<?php
/**
 * Maintenance Mode page - Skin 2
 *
 * @author Your Inspiration Themes
 * @package YITH Maintenance Mode
 * @version 1.1.2
 */

    $background_role = array();
    if ( ! empty( $background['color'] ) )      $background_role[] = "background-color: {$background['color']};";
    if ( ! empty( $background['image'] ) )      $background_role[] = "background-image: url('{$background['image']}');";
    if ( ! empty( $background['repeat'] ) )     $background_role[] = "background-repeat: {$background['repeat']};";
    if ( ! empty( $background['position'] ) )   $background_role[] = "background-position: {$background['position']};";
    if ( ! empty( $background['attachment'] ) ) $background_role[] = "background-attachment: {$background['attachment']};";

?><!DOCTYPE html>
<!--[if IE 6]>
<html id="ie6" class="ie"<?php language_attributes() ?>>
<![endif]-->
<!--[if IE 7]>
<html id="ie7" class="ie"<?php language_attributes() ?>>
<![endif]-->
<!--[if IE 8]>
<html id="ie8" class="ie"<?php language_attributes() ?>>
<![endif]-->
<!--[if IE 9]>
<html id="ie9" class="ie"<?php language_attributes() ?>>
<![endif]-->
<!--[if gt IE 9]>
<html class="ie"<?php language_attributes() ?>>
<![endif]-->
<!--[if !IE]>
<html <?php language_attributes() ?>>
<![endif]-->

<!-- START HEAD -->
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>" />
    <meta name="viewport" content="width=device-width" />
    <title><?php wp_title( '|', true, 'right' ); ?></title>
    <link rel="profile" href="http://gmpg.org/xfn/11" />
    <link rel="Shortcut Icon" type="image/x-icon" href="<?php echo home_url(); ?>/favicon.ico" />
    <?php //wp_head(); ?>

    <link rel="stylesheet" href="<?php echo yith_google_fonts_url() ?>" type="text/css" />
    <link rel="stylesheet" href="<?php echo $this->stylesheet_url() ?>" type="text/css" />
    
    <style type="text/css">
        body {
            <?php echo implode( "\n", $background_role ) ?>
        }

        .logo .tagline {
            <?php echo $logo['tagline_font']; ?>
        }

        h1, h2, h3, h4, h5, h6 {
            <?php echo $title_font; ?>
        }

        p, li {
            <?php echo $p_font; ?>
        }


       .yit-box h3 {<?php echo $title_font ?> }

        form.newsletter input.text-field {
            <?php echo $newsletter['email_font']; ?>
        }

        form.newsletter input.submit-field {
            background: <?php echo $newsletter['submit']['color'] ?>;
            <?php echo $newsletter['submit']['font']; ?>
        }

        form.newsletter .submit:hover input.submit-field {
            background: <?php echo $newsletter['submit']['hover'] ?>;
        }


    	<?php echo $custom ?>
    </style>
</head>
<!-- END HEAD -->
<!-- START BODY -->
<body <?php body_class() ?>>
	
    <div class="container">
        <a class="logo" href="<?php echo site_url() ?>">
            <img src="<?php echo $logo['image'] ?>" alt="Logo" />
            <?php if ( ! empty( $logo['tagline'] ) ) : ?><p class="tagline"><?php echo $logo['tagline'] ?></p><?php endif; ?>
        </a>

        <div class="yit-box">

            <div class="message">
                <?php echo $message; ?>
            </div>



            <?php if ( $newsletter['enabled'] ) : ?>

                <?php if ( $title ) : ?>
                    <h1><?php echo $title ?></h1>
                <?php endif ?>

                <form method="<?php echo $newsletter['form_method'] ?>" action="<?php echo $newsletter['form_action'] ?>" class="newsletter">
                    <fieldset>
                        <input type="text" name="<?php echo $newsletter['email_name'] ?>" id="<?php echo $newsletter['email_name'] ?>" class="email-field text-field" placeholder="<?php echo $newsletter['email_label'] ?>" />
                        <div class="submit"><input type="submit" value="<?php echo $newsletter['submit']['label'] ?>" class="submit-field" /></div>
                        <?php foreach( $newsletter['hidden_fields'] as $field_name => $field_value ) : ?>
                            <input type="hidden" id="<?php echo $field_name ?>" name="<?php echo $field_name ?>" value="<?php echo $field_value ?>" />
                        <?php endforeach; ?>
                    </fieldset>
                </form>
            <?php endif; ?>

        </div>

        <div class="socials">
            <?php foreach( $socials as $social => $url ) :
                if ( empty( $url ) ) continue;

                if ( $social == 'email' ) $url = 'mailto:' . $url;
                if ( $social == 'skype' ) $url = 'http://myskype.info/' . str_replace( '@', '', $url );
            ?>
            <a class="social <?php echo $social ?>" href="<?php echo esc_url( $url ) ?>" target="_blank"><?php echo ucfirst($social) ?></a>
            <?php endforeach; ?>
        </div>

    </div>
	
	<?php wp_footer() ?>
</body>
</html>
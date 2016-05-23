<?php
/**
 * Main admin class
 *
 * @author Your Inspiration Themes
 * @package YITH Maintenance Mode
 * @version 1.1.2
 */

if ( !defined( 'YITH_MAINTENANCE' ) ) { exit; } // Exit if accessed directly

global $yith_maintenance_options;

$yith_maintenance_options = array(
    //tab general
    'general' => array(
        'label' => __('General', 'yith-maintenance-mode'),
        'sections' => array(
            'general' => array(
                'title' =>  __('General Settings', 'yith-maintenance-mode'),
                'description' => '',
                'fields' => array(
                    'yith_maintenance_enable' => array(
                        'title' => __('Enable Maintenance Mode', 'yith-maintenance-mode'),
                        'description' => __( 'Enable the splash page to lets users to know the blog is down for maintenance. (Default: Off) ', 'yith-maintenance-mode' ),
                        'type' => 'checkbox',
                        'std' => false
                    ),

                    'yith_maintenance_roles' => array(
                        'title' => __('Roles', 'yith-maintenance-mode'),
                        'description' => __( 'The user roles enabled to see the frontend. Check a role to enable it to show the website with maintenance mode active.', 'yith-maintenance-mode' ),
                        'type' => 'checkboxes',
                        'options' => yit_wp_roles(),
                        'std' => array( 'administrator' )
                    ),

                    'yith_maintenance_http_status' => array(
                        'title' => __('HTTP Status Code', 'yith-maintenance-mode'),
                        'description' => __( "You can set to send the HTTP status code, to say the status of the page and can tell to Google that this page isn't ready yet and cause your site not to be listed until this plugin is disabled.", 'yith-maintenance-mode' ),
                        'type' => 'select',
                        'options' => array(
                            200 => 'HTTP 200 - Ok',
                            301 => 'HTTP 301 - Redirect',
                            403 => 'HTTP 403 - Forbidden',
                            503 => 'HTTP 503 - Service Unavailable',
                        ),
                        'std' => 200
                    ),

                    'yith_maintenance_skin' => array(
                        'title' => __('Choose a skin for the maintenance mode', 'yith-maintenance-mode'),
                        'description' => __( "You can choose from 4 different skins to make your maintenance mode unique.", 'yith-maintenance-mode' ),
                        'type' => 'skin',
                        'path' => YITH_MAINTENANCE_URL . '/assets/images/',
                        'options' => apply_filters( 'yit_maintenance_mode_skin_options', array(
                            'skin1' => __('Skin 1', 'yith-maintenance-mode'),
                            'skin2' => __('Skin 2', 'yith-maintenance-mode'),
                            'skin3' => __('Skin 3', 'yith-maintenance-mode'),
                            'skin4' => __('Skin 4', 'yith-maintenance-mode'),
                        ) ),
                        'std' => apply_filters( 'yit_maintenance_mode_skin_std', 'skin1' )
                    ),

                    'yith_maintenance_message' => array(
                        'title' => __('Message', 'yith-maintenance-mode'),
                        'description' => __( 'The message displayed. You can also use HTML code.', 'yith-maintenance-mode' ),
                        'type' => 'textarea',
                        'std' => '<h3>' . __( 'OOPS! WE ARE NOT READY YET!', 'yith-maintenance-mode' ) . '</h3>
<p>' . __( "Hello there! We are not ready yet, but why don't you leave your email address  and we'll let you know  as soon as we're in business!", 'yith-maintenance-mode' ) . '</p>'
                    ),

                    'yith_maintenance_custom_style' => array(
                        'title' => 'Custom style',
                        'description' => __( 'Insert here your custom CSS style.', 'yith-maintenance-mode' ),
                        'type' => 'textarea',
                        'std' => '',
                        'in_skin' => false
                    ),

                    'yith_maintenance_mascotte' => array(
                        'title' => 'Mascotte',
                        'description' => __( 'If you want, you can set here a mascotte image to show above the main box, in the right side. This option is only available for skin 1.', 'yith-maintenance-mode' ),
                        'type' => 'upload',
                        'std' => YITH_MAINTENANCE_URL . 'assets/images/mascotte.png'
                    ),
                )
            ),
            'typography' => array(
                'title' =>  __('Typography', 'yith-maintenance-mode'),
                'description' => '',
                'fields' => array(
                    'yith_maintenance_title_font' => array(
                        'title' =>  __('Title font of message', 'yith-maintenance-mode'),
                        'description' => __('Choose the font type, size and color for the titles inside the message text.', 'yith-maintenance-mode'),
                        'type' => 'typography',
                        'std' => array(
                            'size' => 18,
                            'unit' => 'px',
                            'family' => 'Open Sans',
                            'style' => 'bold',
                            'color' => '#666666',
                        ),
                    ),
                    'yith_maintenance_paragraph_font' => array(
                        'title' =>  __('Paragraph font of message', 'yith-maintenance-mode'),
                        'description' => __('Choose the font type, size and color for the paragraphs inside the message text.', 'yith-maintenance-mode'),
                        'type' => 'typography',
                        'std' => array(
                            'size' => 13,
                            'unit' => 'px',
                            'family' => 'Open Sans',
                            'style' => 'regular',
                            'color' => '#666666',
                        ),
                    )
                )
            ),
            'colors' => array(
                'title' =>  __('Colors', 'yith-maintenance-mode'),
                'description' => '',
                'fields' => array(
                    'yith_maintenance_border_top' => array(
                        'title' =>  __('Border top color', 'yith-maintenance-mode'),
                        'description' => __('Choose the color for the big border top of the main box. This option is only available for skin 1.', 'yith-maintenance-mode'),
                        'type' => 'colorpicker',
                        'std' => '#fcd358',
                    )
                )
            ),
        )
    ),

    //tab background
    'background' => array(
        'label' => __('Background', 'yith-maintenance-mode'),
        'sections' => array(
            'background' => array(
                'title' =>  __('Background Settings', 'yith-maintenance-mode'),
                'description' => __('Customize the background of the page', 'yith-maintenance-mode'),
                'fields' => array(
                    'yith_maintenance_background_image' => array(
                        'title' =>  __('Background image', 'yith-maintenance-mode'),
                        'description' => __('Upload or choose from your media library the background image', 'yith-maintenance-mode'),
                        'type' => 'upload',
                        'std' => YITH_MAINTENANCE_URL . 'assets/images/bg-pattern.png',
                    ),
                    'yith_maintenance_background_color' => array(
                        'title' =>  __('Background Color', 'yith-maintenance-mode'),
                        'description' => __('Choose a background color', 'yith-maintenance-mode'),
                        'type' => 'colorpicker',
                        'std' => '',
                    ),
                    'yith_maintenance_background_repeat' => array(
                        'title' =>  __('Background Repeat', 'yith-maintenance-mode'),
                        'description' => __( 'Select the repeat mode for the background image.', 'yith-maintenance-mode' ),
                        'type' => 'select',
                        'std' => apply_filters( 'yith_maintenance_background_repeat_std', 'repeat' ),
                        'options' => array(
                            'repeat' => __( 'Repeat', 'yith-maintenance-mode' ),
                            'repeat-x' => __( 'Repeat Horizontally', 'yith-maintenance-mode' ),
                            'repeat-y' => __( 'Repeat Vertically', 'yith-maintenance-mode' ),
                            'no-repeat' => __( 'No Repeat', 'yith-maintenance-mode' ),
                        )
                    ),
                    'yith_maintenance_background_position' => array(
                        'title' =>  __('Background Position', 'yith-maintenance-mode'),
                        'description' =>  __( 'Select the position for the background image.', 'yith-maintenance-mode' ),
                        'type' => 'select',
                        'options' => array(
                            'center' => __( 'Center', 'yith-maintenance-mode' ),
                            'top left' => __( 'Top left', 'yith-maintenance-mode' ),
                            'top center' => __( 'Top center', 'yith-maintenance-mode' ),
                            'top right' => __( 'Top right', 'yith-maintenance-mode' ),
                            'bottom left' => __( 'Bottom left', 'yith-maintenance-mode' ),
                            'bottom center' => __( 'Bottom center', 'yith-maintenance-mode' ),
                            'bottom right' => __( 'Bottom right', 'yith-maintenance-mode' ),
                        ),
                        'std' => apply_filters( 'yith_maintenance_background_position_std', 'top left' )
                    ),
                    'yith_maintenance_background_attachment' => array(
                        'title' =>  __('Background Attachment', 'yith-maintenance-mode'),
                        'description' => __( 'Select the attachment for the background image.', 'yith-maintenance-mode' ),
                        'type' => 'select',
                        'options' => array(
                            'scroll' => __( 'Scroll', 'yith-maintenance-mode' ),
                            'fixed' => __( 'Fixed', 'yith-maintenance-mode' ),
                        ),
                        'std' => apply_filters( 'yith_maintenance_background_attachment_std', 'scroll' )
                    )
                )
            )
        )
    ),

    //tab logo
    'logo' => array(
        'label' => __('Logo', 'yith-maintenance-mode'),
        'sections' => array(
            'logo' => array(
                'title' =>  __('Logo Settings', 'yith-maintenance-mode'),
                'description' => __('Customize the logo', 'yith-maintenance-mode'),
                'fields' => array(
                    'yith_maintenance_logo_image' => array(
                        'title' =>  __('Logo image', 'yith-maintenance-mode'),
                        'description' => __('Upload or choose from your media library the logo image', 'yith-maintenance-mode'),
                        'type' => 'upload',
                        'std' => YITH_MAINTENANCE_URL . 'assets/images/logo.png',
                    ),
                    'yith_maintenance_logo_tagline' => array(
                        'title' =>  __('Logo tagline', 'yith-maintenance-mode'),
                        'description' => __('Set the tagline to show below the logo image', 'yith-maintenance-mode'),
                        'type' => 'text',
                        'std' => '',
                    ),
                    'yith_maintenance_logo_tagline_font' => array(
                        'title' =>  __('Logo tagline font', 'yith-maintenance-mode'),
                        'description' => __('Choose the font type, size and color for the tagline text.', 'yith-maintenance-mode'),
                        'type' => 'typography',
                        'std' => array(
                            'size' => 15,
                            'unit' => 'px',
                            'family' => 'Open Sans',
                            'style' => 'regular',
                            'color' => '#999999',
                        ),
                    )
                )
            )
        )
    ),

    //tab container
    'newsletter' => array(
        'label' => __('Newsletter', 'yith-maintenance-mode'),
        'sections' => array(
            'newsletter' => array(
                'title' =>  __('Newsletter', 'yith-maintenance-mode'),
                'description' => __('Add a newsletter form in your maintenance mode page.', 'yith-maintenance-mode'),
                'fields' => array(
                    'yith_maintenance_enable_newsletter_form' => array(
                        'title' =>  __('Enable Newsletter form', 'yith-maintenance-mode'),
                        'description' => __('Choose if you want to enable the newsletter form in the maintenance mode page.', 'yith-maintenance-mode'),
                        'type' => 'checkbox',
                        'std' => true,

                    ),
                    'yith_maintenance_newsletter_email_font' => array(
                        'title' =>  __('Newsletter Email Font', 'yith-maintenance-mode'),
                        'description' => __('Choose the font type, size and color for the email input field.', 'yith-maintenance-mode'),
                        'type' => 'typography',
                        'std' => array(
                            'size' => 12,
                            'unit' => 'px',
                            'family' => 'Open Sans',
                            'style' => 'bold',
                            'color' => '#a3a3a3',
                        ),

                    ),
                    'yith_maintenance_newsletter_submit_font' => array(
                        'title' =>  __('Newsletter Submit Font', 'yith-maintenance-mode'),
                        'description' => __('Choose the font type, size and color for the email submit.', 'yith-maintenance-mode'),
                        'type' => 'typography',
                        'std' => array(
                            'size' => 16,
                            'unit' => 'px',
                            'family' => 'Open Sans',
                            'style' => 'extra-bold',
                            'color' => '#fff',
                        ),

                    ),
                    'yith_maintenance_newsletter_submit_background' => array(
                        'title' =>  __('Newsletter submit background', 'yith-maintenance-mode'),
                        'description' => __('The submit button background.', 'yith-maintenance-mode'),
                        'type' => 'colorpicker',
                        'std' => '#617291',

                    ),
                    'yith_maintenance_newsletter_submit_background_hover' => array(
                        'title' =>  __('Newsletter submit hover background', 'yith-maintenance-mode'),
                        'description' => __('The submit button hover background.', 'yith-maintenance-mode'),
                        'type' => 'colorpicker',
                        'std' => '#3c5072',

                    ),
                    'yith_maintenance_newsletter_title' => array(
                        'title' =>  __('Title', 'yith-maintenance-mode'),
                        'description' => __('The title displayed above the newsletter form.', 'yith-maintenance-mode'),
                        'type' => 'text',
                        'std' => '',

                    )
                )
            ),
            'newsletter_configuration' => array(
                'title' =>  __('Form configuration', 'yith-maintenance-mode'),
                'description' => __('Configure the form and each field, to integrate the newsletter features of an external service.', 'yith-maintenance-mode'),
                'fields' => array(
                    'yith_maintenance_newsletter_action' => array(
                        'title' =>  __('Action URL', 'yith-maintenance-mode'),
                        'description' => __('Set the action url of the form.', 'yith-maintenance-mode'),
                        'type' => 'text',
                        'std' => '',
                        'in_skin' => false
                    ),
                    'yith_maintenance_newsletter_method' => array(
                        'title' =>  __('Form method', 'yith-maintenance-mode'),
                        'description' => __('Set the method for the form request.', 'yith-maintenance-mode'),
                        'type' => 'select',
                        'options' => array(
                            'POST' => 'POST',
                            'GET'  => 'GET',
                        ),
                        'std' => 'POST',
                        'in_skin' => false
                    ),
                    'yith_maintenance_newsletter_email_label' => array(
                        'title' =>  __('"Email" field label', 'yith-maintenance-mode'),
                        'description' => __('The label for the email field', 'yith-maintenance-mode'),
                        'type' => 'text',
                        'std' => 'Enter your email address',

                    ),
                    'yith_maintenance_newsletter_email_name' => array(
                        'title' =>  __('"Email" field name', 'yith-maintenance-mode'),
                        'description' => __('The "name" attribute for the email field', 'yith-maintenance-mode'),
                        'type' => 'text',
                        'std' => 'email',

                    ),
                    'yith_maintenance_newsletter_submit_label' => array(
                        'title' =>  __('Submit button label', 'yith-maintenance-mode'),
                        'description' => __('The label for the submit button', 'yith-maintenance-mode'),
                        'type' => 'text',
                        'std' => __( 'NOTIFY ME', 'yith-maintenance-mode' ),

                    ),
                    'yith_maintenance_newsletter_hidden_fields' => array(
                        'title' =>  __('Newsletter Hidden fields', 'yith-maintenance-mode'),
                        'description' => __('Set the hidden fields to include in the form. Use the form: field1=value1&field2=value2&field3=value3', 'yith-maintenance-mode'),
                        'type' => 'text',
                        'std' => '',

                    )
                )
            )
        )
    ),

    //tab logo
    'socials' => array(
        'label' => __('Socials', 'yith-maintenance-mode'),
        'sections' => array(
            'logo' => array(
                'title' =>  __('Set the socials', 'yith-maintenance-mode'),
                'description' => __( "You can set here the url of your social accounts (you can leave empty if you don't want to show the social icon)", 'yith-maintenance-mode' ),
                'fields' => array(
                    'yith_maintenance_socials_facebook' => array(
                        'title' =>  __('Facebook', 'yith-maintenance-mode'),
                        'description' => __('Set the URL of your facebook profile', 'yith-maintenance-mode'),
                        'type' => 'text',
                        'std' => '',
                        'in_skin' => false,
                    ),
                    'yith_maintenance_socials_twitter' => array(
                        'title' =>  __('Twitter', 'yith-maintenance-mode'),
                        'description' => __('Set the URL of your twitter profile', 'yith-maintenance-mode'),
                        'type' => 'text',
                        'std' => '',
                        'in_skin' => false,
                    ),
                    'yith_maintenance_socials_gplus' => array(
                        'title' =>  __('Google+', 'yith-maintenance-mode'),
                        'description' => __('Set the URL of your Google+ profile', 'yith-maintenance-mode'),
                        'type' => 'text',
                        'std' => '',
                        'in_skin' => false,
                    ),
                    'yith_maintenance_socials_youtube' => array(
                        'title' =>  __('Youtube', 'yith-maintenance-mode'),
                        'description' => __('Set the URL of your youtube profile', 'yith-maintenance-mode'),
                        'type' => 'text',
                        'std' => '',
                        'in_skin' => false,
                    ),
                    'yith_maintenance_socials_rss' => array(
                        'title' =>  __('RSS', 'yith-maintenance-mode'),
                        'description' => __('Set the URL of your RSS feed', 'yith-maintenance-mode'),
                        'type' => 'text',
                        'std' => '',
                        'in_skin' => false,
                    ),
                    'yith_maintenance_socials_skype' => array(
                        'title' =>  __('Skype', 'yith-maintenance-mode'),
                        'description' => __('Set the username of your skype account', 'yith-maintenance-mode'),
                        'type' => 'text',
                        'std' => '',
                        'in_skin' => false,
                    ),
                    'yith_maintenance_socials_email' => array(
                        'title' =>  __('Email', 'yith-maintenance-mode'),
                        'description' => __('Write here your email address', 'yith-maintenance-mode'),
                        'type' => 'text',
                        'std' => '',
                        'in_skin' => false,
                    ),
                    'yith_maintenance_socials_behance' => array(
                        'title' =>  __('Behance', 'yith-maintenance-mode'),
                        'description' => __('Set the URL of your Behance profile', 'yith-maintenance-mode'),
                        'type' => 'text',
                        'std' => '',
                        'in_skin' => false,
                    ),
                    'yith_maintenance_socials_dribble' => array(
                        'title' =>  __('Dribble', 'yith-maintenance-mode'),
                        'description' => __('Set the URL of your dribble profile', 'yith-maintenance-mode'),
                        'type' => 'text',
                        'std' => '',
                        'in_skin' => false,
                    ),
                    'yith_maintenance_socials_flickr' => array(
                        'title' =>  __('FlickR', 'yith-maintenance-mode'),
                        'description' => __('Set the URL of your Flickr profile', 'yith-maintenance-mode'),
                        'type' => 'text',
                        'std' => '',
                        'in_skin' => false,
                    ),
                    'yith_maintenance_socials_instagram' => array(
                        'title' =>  __('Instagram', 'yith-maintenance-mode'),
                        'description' => __('Set the URL of your instagram profile', 'yith-maintenance-mode'),
                        'type' => 'text',
                        'std' => '',
                        'in_skin' => false,
                    ),
                    'yith_maintenance_socials_pinterest' => array(
                        'title' =>  __('Pinterest', 'yith-maintenance-mode'),
                        'description' => __('Set the URL of your Pinterest profile', 'yith-maintenance-mode'),
                        'type' => 'text',
                        'std' => '',
                        'in_skin' => false,
                    ),
                    'yith_maintenance_socials_tumblr' => array(
                        'title' =>  __('Tumblr', 'yith-maintenance-mode'),
                        'description' => __('Set the URL of your Tumblr profile', 'yith-maintenance-mode'),
                        'type' => 'text',
                        'std' => '',
                        'in_skin' => false,
                    ),
                    'yith_maintenance_socials_linkedin' => array(
                        'title' =>  __('LinkedIn', 'yith-maintenance-mode'),
                        'description' => __('Set the URL of your LinkedIn profile', 'yith-maintenance-mode'),
                        'type' => 'text',
                        'std' => '',
                        'in_skin' => false,
                    ),
                )
            )
        )
    ),
);

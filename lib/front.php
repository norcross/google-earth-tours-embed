<?php

class GTour_Embed_Front
{

    /**
     * This is our constructor
     *
     * @return GTour_Embed_Front
     */
    public function __construct() {
        add_action( 'wp_enqueue_scripts',   array(  $this,  'scripts_styles'        ),  10, 2   );
        add_shortcode( 'gearthtour',        array(  $this,  'shortcode'             )           );
    }

    /**
     * register our JS files for use when embedding
     * shortcode is used
     *
     * @return null
     */
    public function scripts_styles() {
        wp_register_style( 'gtour-embed', plugins_url( '/css/gtour.embed.css', __FILE__ ), array(), RKV_GETE_VER, 'all' );
        wp_register_script( 'jsapi', 'https://www.google.com/jsapi', array(), null, true );
        wp_register_script( 'kmldomwalk', plugins_url( '/js/kmldomwalk.js', __FILE__ ), array(), null, true );
        wp_register_script( 'gtour-embed', plugins_url( '/js/gtour.embed.js', __FILE__ ), array(), RKV_GETE_VER, true );
    }

    /**
     * the actual shortcode function, which pulls the file
     * and loads the required markup
     *
     * @param  [type] $atts    [description]
     * @param  [type] $content [description]
     * @return [type]          [description]
     */
    public function shortcode( $atts, $content = null ) {
        // fetch our attributes
        extract( shortcode_atts( array(
            'file'      => ''
        ), $atts ) );
        // bail without a file
        if ( empty( $file ) ) {
            return;
        }
        // load our CSS if not filtered out
        if ( true === apply_filters( 'gtour_embed_enable_css', true ) ) {
            wp_enqueue_style( 'gtour-embed' );
        }
        // now load our registered scripts
        wp_enqueue_script( 'jsapi' );
        wp_enqueue_script( 'kmldomwalk' );
        wp_enqueue_script( 'gtour-embed' );
        // set up variables for later use
        wp_localize_script( 'gtour-embed', 'gtourEmbedVars', array(
                'file'      => esc_url( $file ),
                'notfound'  => __( 'No tour found!', 'google-earth-tour-embed' ),
            )
        );
        // handle my markup
        $display    = '';
        $display    .= '<div class="gtour-embed-wrapper">';
        // the display portion to be loaded via JS
        $display    .= '<div id="gtour-embed" class="gtour-embed-display"></div>';
        // the control buttons
        $display    .= self::get_control_buttons();
        // close up the markup
        $display    .= '</div>';
        // send it back
        return $display;
    }

    /**
     * get the control button configuration for
     * the embedded tour
     *
     * @return [array]  $buttons        the buttons to display
     */
    static function get_control_buttons() {
        // set an array for the buttons
        $actions  = array(
            array(
                'class' => 'gtour-embed-enter',
                'click' => 'enterTour()',
                'text'  => __( 'Enter Tour', 'google-earth-tour-embed' )
            ),
            array(
                'class' => 'gtour-embed-play',
                'click' => 'playTour()',
                'text'  => __( 'Play Tour', 'google-earth-tour-embed' )
            ),
            array(
                'class' => 'gtour-embed-pause',
                'click' => 'pauseTour()',
                'text'  => __( 'Pause Tour', 'google-earth-tour-embed' )
            ),
            array(
                'class' => 'gtour-embed-reset',
                'click' => 'resetTour()',
                'text'  => __( 'Reset Tour', 'google-earth-tour-embed' )
            ),
            array(
                'class' => 'gtour-embed-exit',
                'click' => 'exitTour()',
                'text'  => __( 'Exit Tour', 'google-earth-tour-embed' )
            ),
        );
        // optional filter
        $actions    = apply_filters( 'gtour_embed_button_actions', $actions );
        // filter them
        $actions    = array_filter( $actions );
        // bail if no buttons
        if ( empty( $actions ) ) {
            return;
        }
        // start markup
        $buttons    = '';
        $buttons    .= '<div class="gtour-embed-controls">';
        $buttons    .= '<ul class="gtour-embed-button-row">';
        // loop them
        foreach( $actions as $action ) {
            $buttons    .= '<li class="gtour-embed-button-single">';
            $buttons    .= '<span onclick="' . $action['click'] . '" class="gtour-embed-button ' . sanitize_html_class( $action['class'] ) . '">' . esc_attr( $action['text'] ) . '</span>';
            $buttons    .= '</li>';
        }
        // close up
        $buttons    .= '</ul>';
        $buttons    .= '</div>';
        // return them
        return $buttons;
    }

/// end class
}

new GTour_Embed_Front();
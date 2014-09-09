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
            'file'      => '',
            'title'     => '',
            'height'    => '',
            'width'     => ''
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
        // setup for container width
        $wrap   = ! empty( $width ) ? ' style="width:' . self::sanitize_numeric( $width ). 'px;"' : '';
        // handle my markup
        $display    = '';
        $display    .= '<div class="gtour-embed-wrapper"' . $wrap . '>';
        // our optional title
        if ( ! empty( $title ) ) {
            $display    .= '<h4 class="gtour-embed-title">' . esc_attr( $title ) . '</h4>';
        }
        // check for width and / or height
        $style  = '';
        if ( ! empty( $height ) || ! empty( $width ) ) {
            $style  .= ' style="';
            // height check
            if ( ! empty( $height ) ) {
                $style  .= 'height:' . self::sanitize_numeric( $height ). 'px;';
            }
            // width check
            if ( ! empty( $width ) ) {
                $style  .= 'width:' . self::sanitize_numeric( $width ). 'px;';
            }
            $style  .= '"';
        }
        // the display portion to be loaded via JS
        $display    .= '<div id="gtour-embed" class="gtour-embed-display"' . $style . '></div>';
        // the control buttons
        $display    .= self::get_control_button_display();
        // close up the markup
        $display    .= '</div>';
        // send it back
        return $display;
    }

    /**
     * get the control button configuration for
     * the embedded tour
     *
     * @return [array]  $buttons        the array of data for the buttons
     */
    static function get_control_button_actions() {
        // set an array for the buttons
        $buttons  = array(
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
        $buttons    = apply_filters( 'gtour_embed_button_actions', $buttons );
        // filter them
        $buttons    = array_filter( $buttons );
        // bail if no buttons
        if ( empty( $buttons ) ) {
            return;
        }
        // return it
        return $buttons;
    }

    /**
     * get the markup for the control buttons in the
     * the embedded tour
     *
     * @return [html]  $buttons        the buttons to display
     */
    static function get_control_button_display() {
        // fetch the actions
        $actions    = self::get_control_button_actions();
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
        // filter
        $buttons    = apply_filters( 'gtour_embed_button_display', $buttons );
        // bail if no buttons
        if ( empty( $buttons ) ) {
            return;
        }
        // return them
        return $buttons;
    }

    /**
     * strip all non-numeric values from a string
     * @param string $number
     * @return int
     */
    static function sanitize_numeric( $number ) {
        return preg_replace( '/\D/', '', $number );
    }

/// end class
}

new GTour_Embed_Front();
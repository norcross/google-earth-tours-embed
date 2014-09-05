<?php

class GTour_Embed_Admin
{

    /**
     * This is our constructor
     *
     * @return GTour_Embed_Admin
     */
    public function __construct() {
        add_filter( 'upload_mimes',         array(  $this,  'kmz_mime_type'         )           );
    }

    /**
     * allow our Google Earth files to be uploaded in the native
     * WP media manager
     *
     * @param  [array]  $mimes  the currently allowed MIME types
     * @return [array]  $mimes  the updated array of allowed MIME types
     */
    public function kmz_mime_type( $mimes ){
        $mimes['kmz'] = 'application/vnd.google-earth.kmz';
        return $mimes;
    }

/// end class
}

new GTour_Embed_Admin();
    var ge;
    var tour;
    google.load( 'earth', '1' );

    function init() {
        google.earth.createInstance( 'gtour-embed', initCB, failureCB );
    }

    function initCB( instance ) {
        ge  = instance;
        ge.getWindow().setVisibility( true );
        ge.getNavigationControl().setVisibility( ge.VISIBILITY_SHOW );

        var href    = gtourEmbedVars.file;
        // bail without a URL
        if ( ! href ) {
            setTimeout(function() {
                alert( gtourEmbedVars.notfound );
            }, 0 );
            return;
        }
        google.earth.fetchKml( ge, href, fetchCallback );

        function fetchCallback( fetchedKml ) {
            // Alert if no KML was found at the specified URL.
            if ( ! fetchedKml ) {
                setTimeout(function() {
                    alert( gtourEmbedVars.notfound );
                }, 0 );
                return;
            }

            // Add the fetched KML into this Earth instance.
            ge.getFeatures().appendChild( fetchedKml );

            // Walk through the KML to find the tour object; assign to variable 'tour.'
            walkKmlDom( fetchedKml, function() {
                if ( this.getType() == 'KmlTour' ) {
                    tour    = this;
                    return false;
                }
            });
        }
    }

    function failureCB( errorCode ) {
    }

    // Tour control functions.
    function enterTour() {
        if ( ! tour ) {
            alert( gtourEmbedVars.notfound );
            return;
        }
        ge.getTourPlayer().setTour( tour );
    }

    function playTour() {
        ge.getTourPlayer().play();
    }

    function pauseTour() {
        ge.getTourPlayer().pause();
    }

    function resetTour() {
        ge.getTourPlayer().reset();
    }

    function exitTour() {
        ge.getTourPlayer().setTour( null );
    }

    google.setOnLoadCallback( init );

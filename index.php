<html>
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/semantic-ui/2.2.10/semantic.min.css">
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/semantic-ui/2.2.10/semantic.min.js"></script>
    <script type="text/javascript"
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCAvuL8G_jKCOKoxrdoPVgfdK7JS_Q2DIo&libraries=places"></script>
    <title>Single Mom Finder</title>
    <style>
        .hidden {
            display: none;
        }

        .site.header {
            margin-bottom: 1rem;
        }

        .site.header h1 {
            margin-bottom: 0;
        }

        .site.header h2 {
            margin-top: 0;
        }

        .action.input {
            text-align: center;
        }

        .setup.input {
            font-size: x-large;
            text-align: center;
            padding-bottom: 5rem;
        }

        .google.map {
            height: 100%;
            margin: 1rem;
        }
    </style>
</head>
<body>
<div class="ui three column grid">
    <div class="column"></div>
    <div class="column">
        <div class="site header">
            <h1 class="ui header">Welcome to SingleMomFinder.com</h1>
            <h2 class="ui sub header">Find single moms near you!</h2>
            <div class="ui divider"></div>
        </div>
        <div class="form blurring">
            <div class="ui inverted dimmer">
                <div class="ui text loader">Searching</div>
            </div>
            <div class="setup input">
                Search for single moms within
                <div class="ui inline dropdown">
                    <input type="hidden" name="radius" value="16093.4">
                    <div class="text">10</div>
                    <i class="dropdown icon"></i>
                    <div class="menu">
                        <div class="active item" data-value="16093.4">10</div>
                        <div class="item" data-value="40233.6">25</div>
                        <div class="item" data-value="80467.2">50</div>
                    </div>
                </div>
                miles of
            </div>
            <div class="action input">
                <div class="ui action input">
                    <input placeholder="Enter your zip code" name="zipcode">
                    <button class="ui green zipcode search button"><i class="search icon"></i>Search</button>
                </div>
            </div>
            <div class="geolocation hidden action input">
                <div class="ui horizontal divider">OR</div>
                <button class="ui labeled icon green geolocation search button">
                    <i class="marker icon"></i>
                    Use Current Location
                </button>
            </div>
        </div>
        <div class="hidden map">
            <div class="google map"></div>
            <div class="ui relaxed divided place list"></div>
        </div>
    </div>
    <div class="column"></div>
</div>
<script type="text/javascript">
    $(function () {
        let form = $('.form');

        $('.dropdown').dropdown();

        $('.search.button').on('click', function () {
            form.dimmer('show');
        });

        let createMarkers = (map, places) => {
            console.log(places);
            let $placeList = $('.place.list');
            $placeList.empty();
            let infoWindow = new google.maps.InfoWindow();
            for (let i = 0; i < places.length; i++) {
                let marker = new google.maps.Marker({
                    map: map,
                    position: places[i].geometry.location
                });
                let photo = undefined;

                let $infoElement = $('<div>', {'class': 'ui items'})
                    .append($('<div></div>', {'class': 'item'})
                        .append($('<div>', {'class': 'ui mini image'})
                            .append($('<img>', {
                                    'src': () => {
                                        if (places[i].photos) {
                                            photo = places[i].photos[0].getUrl({maxWidth: 35});
                                        } else {
                                            photo = places[i].icon;
                                        }

                                        return photo;
                                    }
                                }
                            ))
                        )
                        .append($('<div>', {'class': 'content'})
                            .append($('<div>', {'class': 'header'}).text(places[i].name))
                            .append($('<div>', {'class': 'meta'}).text('Rating: ' + places[i].rating))
                        )
                    );

                $infoElement.find('.content').append($('<div>', {'class': 'description'}).text(places[i].vicinity));
                $placeList.append($('<div>', {'class': 'item'}).append($infoElement));

                google.maps.event.addListener(marker, 'click', function () {
                    infoWindow.setContent($infoElement.get(0));
                    infoWindow.open(map, this);
                });
            }
        };

        let showMap = (position) => {
            $('.map').show();
            let map = new google.maps.Map($('.google.map').get(0), {
                center: position,
                zoom: 11
            });
            let service = new google.maps.places.PlacesService(map);

            service.nearbySearch({
                location: position,
                radius: $('input[name="radius"]').val(),
                keyword: 'strip club'
            }, function (results, status) {
                form.dimmer('hide');

                if (status === google.maps.places.PlacesServiceStatus.OK) {
                    createMarkers(map, results);
                }
            })
        };

        $('.zipcode.search.button').on('click', function () {
            let geocoder = new google.maps.Geocoder();
            geocoder.geocode({
                address: $('input[name="zipcode"]').val()
            }, (results, status) => {
                if (status === 'OK') {
                    showMap(results[0].geometry.location);
                }
            });
        });

        if ("geolocation" in navigator) {
            $('.geolocation').show();
            $('.geolocation.button').on('click', function () {
                navigator.geolocation.getCurrentPosition((returnedPosition) => {
                    let position = new google.maps.LatLng(returnedPosition.coords.latitude, returnedPosition.coords.longitude);

                    showMap(position);
                });
            });
        }
    });
</script>
<script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

    ga('create', 'UA-104382972-1', 'auto');
    ga('send', 'pageview');

</script>
</body>
</html>

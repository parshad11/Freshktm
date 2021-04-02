@extends('layouts.app')

@section('title', __( 'delivery.track_delivery_people' ))

@section('content')

     <!-- Main content -->
     <section class="content">
          <div class="row">
               <div class="col-md-4">
                    <h3>@lang( 'delivery.track_delivery_people' )</h3>
               </div>
          </div>
          <br>
          <div class="row">
               <div class="col-md-12">
                    <div id='map' style="min-height:80vh;width:100%">

                    </div>
               </div>
          </div>
     </section>
@endsection
@section('javascript')

     <script type="text/javascript">
          $(document).ready(function () {

              mapboxgl.accessToken = 'pk.eyJ1IjoicHJhbW9kbGFtc2FsIiwiYSI6ImNqenp2d25xZjIyZnozbG1saXJvdzY4encifQ.JnhenWIopEkt6RAp5ukfCA';
              var map = new mapboxgl.Map({
                  container: 'map',
                  style: 'mapbox://styles/mapbox/outdoors-v11',
                  center: [85.43957176317652, 27.659904811332666],
                  zoom: 11
              });

              map.on('load', function () {











                  // Add an image to use as a custom marker
                  map.loadImage(
                      'https://docs.mapbox.com/mapbox-gl-js/assets/custom_marker.png',
                      function (error, image) {
                          if (error) throw error;
                          map.addImage('custom-marker', image);
                          // Add a GeoJSON source with 2 points
                          map.addSource('points', {
                              'type': 'geojson',
                              'data': {
                                  'type': 'FeatureCollection',
                                  'features': [
                                            @foreach($delivery_people as $key => $location)
                                      {
                                          @php
                                             $location=explode(',',$location);

                                          @endphp
                                          // feature for Mapbox DC
                                          'type': 'Feature',
                                          'geometry': {
                                              'type': 'Point',
                                              'coordinates': [{{$location['1']}}, {{$location['0']}}]
                                          },
                                          'properties': {
                                              'title': '{{$key}}'
                                          }
                                      },
                                       @endforeach
                                  ]
                              }
                          });
                          // Add a symbol layer
                          map.addLayer({
                              'id': 'points',
                              'type': 'symbol',
                              'source': 'points',
                              'layout': {
                                  'icon-image': 'custom-marker',
                                  // get the title name from the source's "title" property
                                  'text-field': ['get', 'title'],
                                  'text-font': [
                                      'Open Sans Semibold',
                                      'Arial Unicode MS Bold'
                                  ],
                                  'text-offset': [0, 1.25],
                                  'text-anchor': 'top'
                              }
                          });
                      }
                  );

              });


              //  var marker = new mapboxgl.Marker()
              //      .setLngLat([delivery_shipping_longitude, delivery_shipping_latitude])
              //      .addTo(delivery_shipping_map);

              //  delivery_shipping_map.addControl(new mapboxgl.NavigationControl());

          });
          /*var request = new XMLHttpRequest();
          window.setInterval(function () {
// make a GET request to parse the GeoJSON at the url
              request.open('GET','live/track/all-delivery-people/', true);
              request.send();
          }, 15000);*/

     </script>
@endsection


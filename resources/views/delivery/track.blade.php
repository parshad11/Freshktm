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
            <div id='map'>

            </div>
          </div>
          </div>
     </section>
@endsection
@section('javascript')
     <!-- document & note.js -->
     @include('documents_and_notes.document_and_note_js')

     <script type="text/javascript">
         $(document).ready(function () {
            
         

         mapboxgl.accessToken = 'pk.eyJ1IjoicHJhbW9kbGFtc2FsIiwiYSI6ImNqenp2d25xZjIyZnozbG1saXJvdzY4encifQ.JnhenWIopEkt6RAp5ukfCA';
        //  const delivery_pickup_latitude = $('input#pickup_latitude').val();
        //  const delivery_pickup_longitude = $('input#pickup_longitude').val();
        //  const delivery_shipping_latitude = $('input#shipping_latitude').val();
        //  const delivery_shipping_longitude = $('input#shipping_longitude').val();

        //  const delivery_pickup_map = new mapboxgl.Map({
        //      container: 'pickup_map',
        //      style: 'mapbox://styles/mapbox/streets-v11',
        //      center: [delivery_pickup_longitude, delivery_pickup_latitude],
        //      zoom: 13
        //  });

        //  var marker = new mapboxgl.Marker()
        //      .setLngLat([delivery_pickup_longitude, delivery_pickup_latitude])
        //      .addTo(delivery_pickup_map);

        //  delivery_pickup_map.addControl(new mapboxgl.NavigationControl());


        //  const delivery_shipping_map = new mapboxgl.Map({
        //      container: 'shipping_map',
        //      style: 'mapbox://styles/mapbox/streets-v11',
        //      center: [delivery_shipping_longitude, delivery_shipping_latitude],
        //      zoom: 13
        //  });

        //  var marker = new mapboxgl.Marker()
        //      .setLngLat([delivery_shipping_longitude, delivery_shipping_latitude])
        //      .addTo(delivery_shipping_map);

        //  delivery_shipping_map.addControl(new mapboxgl.NavigationControl());

        });

     </script>
@endsection


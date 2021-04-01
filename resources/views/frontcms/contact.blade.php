@extends('frontcms.layouts.master')
@section('title', 'Freshktm | Fresh Produce B to B Supply Chain' )
@section('content')
    <!-- Page heading Start -->
    <section class="page-heading-area  overlay-black" id="water-animation">
        @if(isset($about_info))
            <img class="jarallax-img" src="{{ asset('uploads/img/home/about/'.$about_info->banner_image) }}" alt="">
        @endif
    </section>

    <!-- Contact Start -->
    <section class="contact-area">
        <div class="container">
            <div class="row">
                <div class="col-md-4 col-sm-6">
                    <div class="contact-col contact-infobox">
                        <i class="fa fa-envelope-o" aria-hidden="true"></i>
                        <p>{{ isset($contact) ? $contact->email : '' }}</p>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6">
                    <div class="contact-col contact-infobox">
                        <i class="fa fa-phone" aria-hidden="true"></i>
                        <p>{{ isset($contact) ? $contact->phone : '' }}</p>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6">
                    <div class="contact-col contact-infobox">
                        <i class="fa fa-map-marker" aria-hidden="true"></i>
                        <p>Address: {{ isset($contact) ? $contact->address : '' }}</p>
                    </div>
                </div>
            </div>
            <div class="row contact-form-row">
                <div class="col-md-8 col-md-offset-2 col-sm-12">
                    <div class="row">
                        <div class="contact-col">
                            <div id="form-messages"></div>
                            <form id="ajax-contact" method="post" action="{{action('Front\FrontendController@mailRequest')}}">
                            @csrf
                                <div class="col-md-6">
                                    <input type="text" name="name" id="name" class="form-control"
                                           placeholder="Your Name" required>
                                </div>
                                <div class="col-md-6">
                                    <input type="email" name="email" id="email" class="form-control"
                                           placeholder="Your Email">
                                </div>

                                <div class="col-md-6">
                                    <input type="text" name="phone" class="form-control" placeholder="Phone Number"
                                           required>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" name="subject" class="form-control" placeholder="Subject"
                                           id="subject">
                                </div>

                                <div class="col-md-12">
                                    <input type="text" name="address" class="form-control" placeholder="Address"
                                           required>
                                </div>

                                <div class="col-md-12">
                                    <div class="contact-textarea">
                                        <textarea name="message" class="form-control" rows="6"
                                                  placeholder="Write Message"
                                                  id="message" name="message"></textarea>
                                        <button class="btn btn-default theme-btn btn-hover" type="submit"
                                                value="Submit Form">Send Message
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('scripts')
@if (session()->has('success'))
        <script>
            $(document).ready(function () {
                Swal.fire({
                    icon: 'success',
                    title: "{{Session::get('success')}}",
                    showConfirmButton: false,
                    timer: 3000
                })
            })
        </script>
    @endif
    @if (session()->has('error'))
        <script>
            $(document).ready(function () {
                Swal.fire({
                    icon: 'error',
                    title: "{{Session::get('error')}}",
                    showConfirmButton: false,
                    timer: 3000
                })
            })
        </script>
    @endif
@endsection
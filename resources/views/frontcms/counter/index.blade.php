@extends('layouts.app')
@section('title','Counter Data Setting')
@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>Counter Data</h1>
</section>

<!-- Main content -->
<section class="content">
<form action="{{ route('counter.store')}}" class="form" method="POST" enctype="multipart/form-data">
    @csrf
    {{-- <input type="hidden" name="setting_id"> --}}
    @component('components.widget', ['class' => 'box-primary'])
    <div class="row form-group">
        <div class="col-md-2">
            <label for="" class="control-label">Info :</label>
        </div>
        <div class="col-md-10">
            <div class="row" style="margin-bottom: 10px;">
                <div class="col-md-8" style="padding:0 10px 0 0;">
                    <label for="farmers" class="control-label">Happy Farmers:</label>
                    <input type="number" min=0 name="farmers" class="form-control">
                </div>
            </div>
            <div class="row" style="margin-bottom: 10px;">
                <div class="col-md-8" style="padding:0 10px 0 0;">
                    <label for="clients" class="control-label">Happy Clients:</label>
                    <input type="number" min=0 name="clients" class="form-control">
                </div>
            </div>
            <div class="row" style="margin-bottom: 10px;">
                <div class="col-md-8" style="padding:0 10px 0 0;">
                    <label for="staffs" class="control-label">Staffs:</label>
                    <input type="number" min=0 name="staffs" class="form-control">
                </div>
            </div>
            <div class="row" style="margin-bottom: 10px;">
                <div class="col-md-8" style="padding:0 10px 0 0;">
                    <label for="awards" class="control-label">Awards won:</label>
                    <input type="number" min=0 name="awards" class="form-control">
                </div>
            </div>
        </div>
    </div>
    @endcomponent

    <div class="row">
        <div class="col-sm-12">
            <div class="text-center">  
                <button type="submit" value="submit" class="btn btn-primary submit_product_form">Save</button>
            </div>
        </div>
    </div>


</form>  
</section>
<!-- /.content -->

@endsection
@section('javascript')
    <script src="{{ asset('cms/spartan/dist/js/spartan-multi-image-picker-min.js') }}"></script>
    <script>
         $(document).ready(function(){

            $("#member").spartanMultiImagePicker({
                fieldName:        'member_image[]',
                maxCount:         1,
                rowHeight:        '200px',
                groupClassName:   'col-md-3 col-sm-4 col-xs-6',
                maxFileSize:      '',
                dropFileLabel: 'Drop Here',
                allowedExt: 'png|jpeg|jpg|bmp|gif',
                onExtensionErr : function(index, file){
                    console.log(index, file,  'extension err');
                    alert('Please only input png Type file')
                }
            });
            $("#about_image_1").spartanMultiImagePicker({
                fieldName:        'what_image[]',
                maxCount:         1,
                rowHeight:        '200px',
                groupClassName:   'col-md-3 col-sm-4 col-xs-6',
                maxFileSize:      '',
                dropFileLabel : "Drop Here",
                allowedExt: 'png|jpeg|jpg|bmp|gif',
                onExtensionErr : function(index, file){
                    console.log(index, file,  'extension err');
                    alert('Please only input png or jpg type file')
                },
                onSizeErr : function(index, file){
                    console.log(index, file,  'file size too big');
                    alert('File size too big');
                }
            });
            $('.remove-files').on('click', function(){
                $(this).parents(".col-md-3").remove();
            });
         });
    </script>
    <script>
        $(document).ready(function() {
            // points Add/Remove
            var wrapper = $(".points_content_wrapper");
            var add_button = $(".points_add_btn");

            var x = 0;
            $(add_button).click(function(e) {
                e.preventDefault();
                x++;
                $(wrapper).append(`
                    <div class="row" style="margin-bottom: 10px">
                        <div class="col-md-10" style="padding-left: 0;">
                            <input type="text" name="why_short_points[]"class="form-control" placeholder="Short Points...?">
                        </div>
                        <a href="javascript:void(0);" class="col-md-1 btn btn-sm btn-danger points_remove_btn"><i class="fa fa-minus"></i>&nbsp;Remove</a>
                    </div>`);
            });

            $(wrapper).on("click", ".points_remove_btn", function(e) {
                e.preventDefault();
                $(this).parent('div').remove();
                x--;
            })
        });
    </script>
@endsection

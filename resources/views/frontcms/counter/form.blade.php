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

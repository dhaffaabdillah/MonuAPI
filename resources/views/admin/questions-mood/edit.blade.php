@extends('layouts.admin')
@section('title', 'Edit Soal Mood')
@section('content')
<!-- end page title end breadcrumb -->

<div class="container-fluid">

    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <div class="btn-group float-right">
                    <ol class="breadcrumb hide-phone p-0 m-0">
                        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                        <li class="breadcrumb-item active">Edit Soal Mood</li>
                    </ol>
                </div>
                <h4 class="page-title">Edit Soal Mood</h4>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body bootstrap-select-1">
                    <h4 class="mt-0 header-title">Edit</h4>
                    <p class="text-muted mb-4 font-13">Edit Soal Mood</p>
                    <div class="row col-lg-12">
                        <form action="{{ route('qm-update', $data->id) }}" method="POST">
                            @method('PUT')
                            @csrf
                            {{-- <div class="col-md-12"> --}}
                                <h6 class=" input-title mb-2 mt-0">Soal</h6>   
                                
                                <input type="text" class="form-control"name="question" value="{{ $data->question }}">
                            {{-- </div>                                     --}}
                            
                            <div class="mt-2 justify-content-end">
                                {{-- <button class="btn btn-md-primary" type="submit">Submit</button>    --}}
                                <button type="submit" class="btn btn-gradient-success waves-effect waves-light">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>                                
        </div> <!-- end col -->
    </div>
</div>




@endsection
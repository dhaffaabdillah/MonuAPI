@extends('layouts.admin')
@section('title', 'Edit Paket Ujian')
@section('content')
<!-- end page title end breadcrumb -->

<div class="container-fluid">

    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <div class="btn-group float-right">
                    <ol class="breadcrumb hide-phone p-0 m-0">
                        <li class="breadcrumb-item"><a href="#">Zoogler</a></li>
                        <li class="breadcrumb-item"><a href="#">Forms</a></li>
                        <li class="breadcrumb-item active">Tambah Soal ke Ujian</li>
                    </ol>
                </div>
                <h4 class="page-title">Tambah Soal ke Ujian</h4>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body bootstrap-select-1">
                    <h4 class="mt-0 header-title">Tambah</h4>
                    <p class="text-muted mb-4 font-13">Tambah Soal Soal yang dibutuhkan ke Paket ujian</p>
                    <div class="row">
                        <form action="{{ route('ep-update', $data->id) }}" method="POST">
                            @method('PUT')
                            @csrf
                            <div class="col-md-12">
                                <h6 class=" input-title mb-2 mt-0">Soal</h6>   
                                
                                <select class="select2 form-control mb-3" style="width: 100%; height:36px;" name="question_id">
                                    <option>Select</option>
                                    @foreach ($question as $q)
                                        <option value="{{ $q->id }}" @if ($data->question_id == $q->id) selected @endif>{{ Str::limit($q->question, 100, '...') }}</option>
                                    @endforeach                       
                                    {{-- <option value="{{ $aj->id }}"@if ($data->jurusanId == $aj->id) selected @endif>{{ $aj->namaJurusan }}</option>                   --}}
                                </select>
                                
                            </div>                                    
                            <div class="col-md-12">
                                <h6 class=" input-title mb-2 mt-2 mt-lg-0">Ujian</h6>    
                                
                                    <select class="select2 form-control mb-3" style="width: 100%; height:36px;" name="exam_id">
                                        <option>Select</option>

                                        @foreach ($exam as $e)
                                        <option value="{{ $e->id }}" @if ($data->exam_id == $e->id) selected @endif>{{ $e->exam_name }}</option>
                                        @endforeach
                                    </select>
                            </div>
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
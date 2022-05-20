{{-- exam  viw gan --}}
@extends('layouts.admin')
@section('title', 'Exam')
@section('content')
<!-- end page title end breadcrumb -->
<div class="page-content-wrapper ">

    <div class="container-fluid">

        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box">
                    <div class="btn-group float-right">
                        <ol class="breadcrumb hide-phone p-0 m-0">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Exam</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Exam</h4>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                        <h4 class="mt-0 header-title">Ujian </h4>
                        <p class="text-muted mb-4 font-13">Buat ujian kamu disini.
                        </p>

                        <table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                            <tr>
                                <th>Name Ujian</th>
                                <th>id Guru</th>
                                <th>Mapel</th>
                                <th>Total Pertanyaan</th>
                                <th>Durasi</th>
                                <th>Action</th>
                            </tr>
                            </thead>


                            <tbody>
                                @foreach ($data as $key)
                                    <tr>
                                        <td>{{ $key->exam_name }}</td>
                                        <td>{{ $key->teacher_id }}</td>
                                        <td>{{ $key->subject_id }}</td>
                                        <td>{{ $key->total_question }}</td>
                                        <td>{{ $key->duration }}</td>
                                        <td>
                                            <form action="{{ route('e-destroy', $key->id) }}">
                                                @method('DELETE')
                                                <a href="{{ route('e-edit', $key->id) }}" class="btn btn-sm btn btn-primary">Edit</a>
                                                <button class="btn btn-sm-danger" onclick="confirm('Apakah anda yakin ingin menghapus?')">Hapus</button>
                                            </form>
                                        </td>

                                    </tr>
                                @endforeach
                            
                            
                            </tbody>
                        </table>

                    </div>
                </div>
            </div> <!-- end col -->
        </div> <!-- end row -->

    </div>
</div>
@endsection
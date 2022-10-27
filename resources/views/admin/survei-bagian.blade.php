@extends('template')

@section('content')

<!-- Form Row Start -->
<section class="scroll-section" id="formRow">
    <div class="col-auto d-flex mb-2">
        <a href="{{route('admin.bagian.add',$data->id)}}" class="btn btn-primary btn-icon btn-icon-start ms-1">
            <i data-cs-icon="plus"></i>
            <span>Tambah Bagian</span>
        </a>
        <a href="{{route('admin.bagian.awal.akhir',$data->id)}}" class="btn btn-secondary btn-icon btn-icon-start ms-1">
            <i data-cs-icon="gear"></i>
            <span>Pengaturan Awal Akhir</span>
        </a>
        <a href="{{route('admin.bagian.direct',$data->id)}}" class="btn btn-secondary btn-icon btn-icon-start ms-1">
            <i data-cs-icon="gear"></i>
            <span>Pengaturan Direct</span>
        </a>
    </div>
    <div class="card mb-5">
        <div class="card-body">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Kode</th>
                        <th scope="col">Bagian</th>
                        <!-- <th scope="col">Urutan</th> -->
                        <th scope="col">Pertanyaan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data->bagian as $index => $bagian)
                    <tr>
                        <th scope="row">{{$index+1}}</th>
                        <td>{{$bagian->bagian_kode}}</td>
                        <td>{{$bagian->bagian_nama}}</td>
                        <!-- <td>{{$bagian->bagian_urutan}}</td> -->
                        <td>
                            <a href="{{route('admin.pertanyaan.data',[$data->id,$bagian->id])}}" class="btn btn-light btn-sm">Pertanyaan</a>
                            <a href="{{route('admin.bagian.edit',[$data->id,$bagian->id])}}" class="btn btn-warning btn-sm">Ubah</a>
                            <a href="{{route('admin.bagian.delete',[$data->id,$bagian->id])}}" onclick="return confirm('Yakin Hapus')" class="btn btn-danger btn-sm">Hapus</a>
                        </td>
                    </tr>

                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>
<!-- Form Row End -->

@endsection
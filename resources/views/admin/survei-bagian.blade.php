@extends('template')

@section('content')

<!-- Form Row Start -->

<section class="scroll-section" id="textContent">
    <div class="card mb-5">
        <div class="card-body d-flex flex-column">
            <h3 class="card-title mb-4">Informasi Survei</h3>
            <ul>
                <li>Nama Survei : {{$data->survei_nama}}</li>
                <li>Deksiprsi : {{$data->survei_deskripsi}}</li>
                <li>Untuk : {{$data->survei_untuk}}</li>
            </ul>
            <div class="col-2">
                <a href="{{route('admin.survei.data')}}" class="btn btn-dark btn-sm mb-2">
                    <i data-cs-icon="arrow-left" class="icon" data-cs-size="10"></i>
                    <span class="label">Kembali</span>
                </a>
            </div>
        </div>
    </div>
</section>
<section class="scroll-section" id="formRow">
    <div class="col-auto d-flex mb-2">
        <a href="{{route('admin.bagian.add',$data->id)}}" class="btn btn-primary btn-icon btn-icon-start ms-1">
            <i data-cs-icon="plus"></i>
            <span>Tambah Bagian</span>
        </a>
        <a href="{{route('admin.bagian.awal.akhir',$data->id)}}" class="btn btn-quaternary btn-icon btn-icon-start ms-1">
            <i data-cs-icon="gear"></i>
            <span>Set Pertanyaan Awal & Akhir</span>
        </a>
        <a href="{{route('admin.bagian.direct',$data->id)}}" class="btn btn-dark btn-icon btn-icon-start ms-1">
            <i data-cs-icon="gear"></i>
            <span>Pengaturan Direct Bagian</span>
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
                            <a href="{{route('admin.pertanyaan.data',[$data->id,$bagian->id])}}" class="btn btn-info btn-sm">Pertanyaan</a>
                            <button class="btn btn-icon btn-icon-only btn-sm btn-background shadow" type="button" data-bs-toggle="dropdown" aria-expanded="false" aria-haspopup="true">
                                <i data-cs-icon="more-horizontal" data-acorn-size="15"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-sm dropdown-menu-end shadow">
                                <a class="dropdown-item" href="{{route('admin.bagian.edit',[$data->id,$bagian->id])}}">Ubah</a>
                                <a class="dropdown-item" href="{{route('admin.bagian.delete',[$data->id,$bagian->id])}}" onclick="return confirm('Yakin Hapus')">Hapus</a>
                            </div>
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
@extends('template')

@section('content')

<!-- Form Row Start -->
<!-- Text Content Start -->
<section class="scroll-section" id="textContent">
    <div class="card mb-5">
        <div class="card-body d-flex flex-column">
            <h3 class="card-title mb-4">Bagian / Section</h3>
            <ul>
                <li>Kode bagian : {{$bagian->bagian_kode}}</li>
                <li>Nama bagian : {{$bagian->bagian_nama}}</li>
            </ul>
        </div>
    </div>
</section>
<!-- Text Content End -->
<section class="scroll-section" id="formRow">
    <!-- <h2 class="small-title">Daftar Pertanyaan</h2> -->
    <div class="col-auto d-flex mb-2">
        <a href="{{route('admin.pertanyaan.add',[$data->id,$bagian->id])}}" class="btn btn-primary btn-icon btn-icon-start ms-1">
            <i data-cs-icon="plus"></i>
            <span>Tambah Pertanyaan</span>
        </a>
    </div>
    <div class="card mb-5">
        <div class="card-body">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Pertanyaan</th>
                        <th scope="col">Urutan</th>
                        <th scope="col">Jenis Jawaban</th>
                        <th scope="col">Required</th>
                        <th scope="col">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($bagian->pertanyaan as $index => $item)
                    <tr>
                        <th scope="row">{{$index+1}}</th>
                        <td>{{$item->pertanyaan}}</td>
                        <td>{{$item->pertanyaan_urutan}}</td>
                        <td>{{$item->pertanyaan_jenis_jawaban}}</td>
                        <td>
                            @if($item->required=="1")
                            Ya
                            @else
                            Tidak
                            @endif
                        </td>
                        <td>
                            <a href="{{route('admin.set.jawaban.redirect',[$data->id,$bagian->id,$item->id])}}" class="btn btn-light btn-sm">Kelola</a>
                            <a href="{{route('admin.pertanyaan.edit',[$bagian->id,$item->id])}}" class="btn btn-warning btn-sm">Ubah</a>
                            <a href="{{route('admin.pertanyaan.delete',[$data->id, $bagian->id,$item->id])}}" class="btn btn-danger btn-sm" onclick="return confirm('Yakin Hapus?')">Hapus</a>
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
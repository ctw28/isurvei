@extends('template')

@section('content')

<!-- Form Row Start -->
<section class="scroll-section" id="formRow">
    <div class="card mb-5">
        <div class="card-body">
            <form action="{{route('admin.survei.update',$data->id)}}" method="post" enctype="multipart/form" class="row g-3">
                @csrf
                <div class="col-md-12">
                    <label for="step_kode" class="form-label">Nama Survei</label>
                    <input type="text" class="form-control" id="survei_nama" name="survei_nama" value="{{$data->survei_nama}}" required />
                </div>
                <div class="col-md-9">
                    <label for="step_nama" class="form-label">Deskripsi</label>
                    <textarea class="form-control" name="survei_deskripsi" id="survei_deskripsi" rows="3" required>{{$data->survei_deskripsi}}</textarea>
                </div>
                <div class="col-md-4">
                    <label for="inputState" class="form-label">Untuk</label>
                    <select id="survei_untuk" class="form-select" name="survei_untuk">
                        <option value="mahasiswa" @if($data->survei_untuk=='mahasiswa')selected @endif>Mahasiswa</option>
                        <option value="dosen" @if($data->survei_untuk=='dosen')selected @endif>Pendidik (Dosen)</option>
                        <option value="pegawai" @if($data->survei_untuk=='pegawai')selected @endif>Tenaga Kependidikan (Pegawai)</option>
                        <option value="mitra" @if($data->survei_untuk=='mitra')selected @endif>Mitra / Eksternal</option>

                    </select>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Edit</button>
                </div>
            </form>
        </div>
    </div>
</section>
<!-- Form Row End -->

@endsection
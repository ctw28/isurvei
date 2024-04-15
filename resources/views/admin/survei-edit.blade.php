@extends('template')
@section('css')
<!-- <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script> -->
@endsection

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
                    <textarea class="form-control" name="survei_deskripsi" id="survei_deskripsi" rows="5">{{$data->survei_deskripsi}}</textarea>

                </div>

                <div class="col-md-12">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="is_wajib" name="is_wajib" value="1" @if($data->is_wajib==true)checked @endif>
                        <label class="form-check-label" for="is_wajib">Wajib diisi</label>
                    </div>
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


@section('js')
<script>

</script>

@endsection
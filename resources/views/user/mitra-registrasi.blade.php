@extends('template')

@section('content')

<div class="card mb-5">
    <div class="card-body">
        <h1>{{$data->survei_nama}}</h1>
    </div>
</div>
<div class="card mb-5">
    <div class="card-body">
        <h2>Isi Identitas</h2>
        <form action="{{route('mitra.store')}}" method="post" enctype="multipart/form" class="row g-3">
            @csrf
            <div class="col-md-12">
                <label for="step_kode" class="form-label">Nama</label>
                <input type="hidden" class="form-control" id="survei_id" name="survei_id" value="{{$data->id}}" required />
                <input type="text" class="form-control" id="mitra_nama" name="mitra_nama" value="" required />
            </div>
            <div class="col-md-9">
                <label for="step_nama" class="form-label">Instansi</label>
                <input type="text" class="form-control" id="mitra_instansi" name="mitra_instansi" value="" required />
            </div>
            <div class="col-md-9">
                <label for="step_nama" class="form-label">Jabatan</label>
                <input type="text" class="form-control" id="mitra_jabatan" name="mitra_jabatan" value="" required />
            </div>

            <div class="col-12">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>

    </div>
</div>

@endsection
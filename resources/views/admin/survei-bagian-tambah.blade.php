@extends('template')

@section('content')

<!-- Form Row Start -->
<section class="scroll-section" id="formRow">
    <h2 class="small-title">Form Row</h2>
    <div class="card mb-5">
        <div class="card-body">
            <form action="{{route('admin.bagian.store',$data->id)}}" method="post" enctype="multipart/form" class="row g-3">
                @csrf
                <input type="hidden" class="form-control" id="survei_id" name="survei_id" value="{{$data->id}}" />
                <div class="col-md-2">
                    <label for="bagian_kode" class="form-label">Kode bagian</label>
                    <input type="text" class="form-control" id="bagian_kode" name="bagian_kode" required />
                </div>
                <div class="col-md-1">
                    <label for="bagian_urutan" class="form-label">Urutan</label>
                    <input type="text" class="form-control" id="bagian_urutan" name="bagian_urutan" required />
                </div>
                <div class="row">
                </div>
                <div class="col-md-9">
                    <label for="bagian_nama" class="form-label">Deskripsi bagian</label>
                    <textarea class="form-control" placeholder="Deskripsi" name="bagian_nama" id="bagian_nama" rows="3" required></textarea>
                </div>
                <div class="col-md-4">
                    <label for="inputState" class="form-label">Pilih Parent</label>
                    <select id="inputState" class="form-select" name="bagian_parent">
                        <option value="">Pilih</option>
                        @if($bagian)
                        @foreach($bagian as $item)
                        <option value="{{$item->id}}">{{$item->bagian_kode}} - {{$item->bagian_nama}}</option>
                        @endforeach
                        @endif
                    </select>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Tambah Bagian</button>
                </div>
            </form>
        </div>
    </div>
</section>
<!-- Form Row End -->

@endsection
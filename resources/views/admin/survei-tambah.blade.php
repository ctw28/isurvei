@extends('template')

@section('content')

<!-- Form Row Start -->
<section class="scroll-section" id="formRow">
    <div class="card mb-5">
        <div class="card-body">
            <form action="{{route('admin.survei.store')}}" method="post" enctype="multipart/form" class="row g-3">
                @csrf
                <div class="col-md-12">
                    <label for="step_kode" class="form-label">Nama Survei</label>
                    <input type="text" class="form-control" id="survei_nama" name="survei_nama" value="" required />
                </div>
                <div class="col-md-9">
                    <label for="step_nama" class="form-label">Deskripsi</label>
                    <textarea class="form-control" name="survei_deskripsi" id="survei_deskripsi" rows="3" required></textarea>
                </div>


                <div class="col-md-4">
                    <label for="inputState" class="form-label">Untuk</label>
                    <select id="survei_untuk" class="form-select" name="survei_untuk">
                        <option value="mahasiswa">Mahasiswa</option>
                        <option value="dosen">Pendidik (Dosen)</option>
                        <option value="pegawai">Tenaga Kependidikan (Pegawai)</option>
                        <option value="mitra">Mitra / Eksternal</option>
                    </select>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Tambah</button>
                </div>
            </form>
        </div>
    </div>
</section>
<!-- Form Row End -->

@endsection
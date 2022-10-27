@extends('template')

@section('content')

<div class="card mb-5">
    <div class="card-body">
        <h2>Selamat Datang di I-Survei</h2>
        <!-- <p><b>SI-LANNI atau Sistem Informasi Pelacakan Alumni</b> merupakan metode yang digunakan oleh IAIN Kendari untuk menerima umpan balik dari para alumninya. Umpan balik yang diperoleh dari alumni tersebut digunakan oleh program studi di IAIN Kendari sebagai evaluasi untuk pengembangan kualitas dan sistem Pendidikan yang dilaksanakan di perguruan tinggi. Umpan balik ini dapat bermanfaat pula bagi program studi di IAIN Kendari untuk memetakan lapangan kerja dan usaha agar sesuai dengan tuntutan dunia kerja.</p> -->
    </div>
</div>
<h2 class="small-title">Daftar Kuisioner</h2>
<div class="card mb-5">
    <div class="card-body">
        <table class="table table-striped table-hover">
            <thead>
                <th>No</th>
                <th>Survei</th>
                <th>Deskripsi</th>
                <th>Aksi</th>
            </thead>
            <tbody>
                @foreach ($survei as $index => $item)
                <tr>
                    <td>{{$index+1}}</td>
                    <td>{{$item->survei_nama}}</td>
                    <td>{{$item->survei_deskripsi}}</td>
                    <td>
                        <button data-bs-toggle="modal" data-bs-target="#exampleModal" class="btn btn-info" data-id="" onclick="surveiSet({{$item->id}})">
                            Isi Kuisioner
                        </button>
                        <!-- <a href="{{route('user.show.pertanyaan',$item->bagianAwalAkhir->bagian_id_first)}}" class="btn btn-info">
                            Isi Kuisioner
                        </a> -->

                    </td>
                </tr>
                @endforeach

                </body>
        </table>
        <ul>
        </ul>
        <!-- <p><b>Petunjuk</b> : <br>

            Berikan jawaban pada tiap-tiap pertanyaan yang telah disediakan berikut ini sesuai dengan keadaan Anda.</p>
        <a href="{{route('user.show.pertanyaan',$first->bagian_id_first)}}" class="btn btn-primary">Isi Kuisioner</a> -->
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabelDefault" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <div class="card mb-5">
                    <div class="card-body">
                        <h2>Isi Identitas</h2>
                        <form action="{{route('mitra.store')}}" method="post" enctype="multipart/form" class="row g-3">
                            @csrf
                            <div class="col-md-12">
                                <label for="step_kode" class="form-label">Nama</label>
                                <input type="hidden" class="form-control" id="survei_id" name="survei_id" value="" required />
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
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    function surveiSet(id) {
        // alert(id)
        document.querySelector('#survei_id').value = id;
    }
</script>
@endsection
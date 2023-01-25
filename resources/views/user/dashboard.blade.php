@extends('template')

@section('content')

<div class="card mb-5">
    <div class="card-body">
        <h2>Selamat Datang di I-Survei</h2>
        <!-- <p><b>SI-LANNI atau Sistem Informasi Pelacakan Alumni</b> merupakan metode yang digunakan oleh IAIN Kendari untuk menerima umpan balik dari para alumninya. Umpan balik yang diperoleh dari alumni tersebut digunakan oleh program studi di IAIN Kendari sebagai evaluasi untuk pengembangan kualitas dan sistem Pendidikan yang dilaksanakan di perguruan tinggi. Umpan balik ini dapat bermanfaat pula bagi program studi di IAIN Kendari untuk memetakan lapangan kerja dan usaha agar sesuai dengan tuntutan dunia kerja.</p> -->
    </div>
</div>
<h2 class="small-title">Daftar Survei / Kuisioner</h2>
<div class="card mb-5">
    <div class="card-body">
        <table class="table table-striped table-hover">
            <thead class="text-center">
                <th width="2%">No</th>
                <th width="10%">Status</th>
                <th width="20%">Survei</th>
                <th width="30%">Deskripsi</th>
                <th width="10%">Wajib</th>
                <th width="5%">Aksi</th>
            </thead>
            <tbody>
                @foreach ($survei as $index => $item)
                <tr>
                    <td class="text-center">{{$index+1}}</td>
                    <td class="text-center">
                        @if(count($item->sesi)==0)
                        <span class="badge bg-danger text-uppercase">Belum Mulai</span>
                        @elseif($item->sesi[0]->sesi_status==false)
                        <span class="badge bg-warning text-uppercase">Belum Selesai</span>
                        @else
                        <span class="badge bg-success text-uppercase">Selesai</span>
                        @endif
                    </td>
                    <td>{{$item->survei_nama}}</td>
                    <td>{{$item->survei_deskripsi}}</td>
                    <td class="text-center">
                        @if($item->harus_diisi==true)
                        <span class="badge bg-danger text-uppercase">Wajib diisi</span>
                        @else
                        -
                        @endif
                    </td>
                    <td class="text-center">
                        <a href=" {{route('user.show.pertanyaan',[$item->id,$item->bagianAwalAkhir->bagian_id_first])}}" class="btn btn-info">
                            @if(count($item->sesi)==0)
                            Mulai
                            @elseif($item->sesi[0]->sesi_status==false)
                            Lanjutkan
                            @else
                            Edit
                            @endif

                            Survei
                        </a>

                    </td>
                </tr>
                @endforeach

                </body>
        </table>
    </div>
</div>
@endsection
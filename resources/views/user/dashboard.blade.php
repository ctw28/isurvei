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
                        <a href="{{route('user.show.pertanyaan',$item->bagianAwalAkhir->bagian_id_first)}}" class="btn btn-info">
                            Isi Kuisioner
                        </a>

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
@endsection
@extends('template')

@section('style')
<style>
    @keyframes blink {

        0%,
        100% {
            opacity: 1;
        }

        50% {
            opacity: 0;
        }
    }

    .attention {
        font-size: 40px;
        font-weight: bold;
        color: red;
        text-align: center;
        animation: blink 1s infinite;
    }
</style>
@endsection
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
        @if($role=="pegawai")
        <div class="alert alert-warning" role="alert">
            <h1><strong><span class="attention">PERHATIAN!!</span><br>JIKA ANDA <span style="color:red">DOSEN</span> TAPI SURVEI YANG MUNCUL ADALAH SURVEI TENDIK, SILAHKAN UBAH DENGAN KLIK TOMBOL
                    "UBAH KE SURVEI DOSEN" DI BAWAH INI
                    AGAR SURVEI DIALIHKAN KE SURVEI DOSEN. TERIMA KASIH</strong></h1>
            <button onclick="gantiKeDosen()" class="btn btn-dark btn-lg">Ubah ke survei Dosen</button>
        </div>
        @endif
        @if($role=="dosen")
        <div class="alert alert-warning" role="alert">
            <h1><strong>PERHATIAN!! <br>JIKA ANDA <span style="color:red">TENDIK</span> TAPI SURVEI YANG MUNCUL ADALAH SURVEI DOSEN, SILAHKAN UBAH DENGAN KLIK TOMBOL
                    "UBAH KE SURVEI TENDIK" DI BAWAH INI
                    AGAR SURVEI DIALIHKAN KE SURVEI TENDIK. TERIMA KASIH</strong></h1>
            <button onclick="gantiKePegawai()" class="btn btn-dark btn-lg">Ubah ke survei Tendik</button>
        </div>
        @endif
        <table class="table table-striped table-hover">
            <thead class="text-center">
                <th width="2%">No</th>
                <th width="20%">Survei</th>
                <th width="30%">Deskripsi</th>
                <th width="10%">Wajib SIA</th>
                <th width="10%">Untuk</th>
                <th width="10%">Jumlah Pengisian</th>
                <th width="5%">Aksi</th>
            </thead>
            <tbody>
                @foreach ($survei as $index => $item)
                <tr>
                    <td class="text-center">{{$index+1}}</td>
                    <!-- <td class="text-center">
                        @if(count($item->sesi)==0)
                        <span class="badge bg-danger text-uppercase">Belum Mulai</span>
                        @elseif($item->sesi[0]->sesi_status==false)
                        <span class="badge bg-warning text-uppercase">Belum Selesai</span>
                        @else
                        <span class="badge bg-success text-uppercase">Selesai</span>
                        @endif
                    </td> -->
                    <td>{{$item->survei_nama}}</td>
                    <td>{{$item->survei_deskripsi}}</td>
                    <!-- <td class="text-center">
                        @if($item->is_wajib==true)
                        <span class="badge bg-danger text-uppercase">Wajib diisi</span>
                        @else
                        -
                        @endif
                    </td> -->
                    <td class="text-center">
                        @if($item->is_sia==true)
                        <span class="badge bg-danger text-uppercase">WAJIB diisi agar SIA Terbuka</span>
                        @else
                        -
                        @endif
                    </td>
                    <td class="text-center"><b>
                            @if($item->survei_untuk=="pegawai")
                            TENDIK
                            @elseif ($item->survei_untuk=="dosen")
                            DOSEN
                            @else
                            MAHASISWA
                            @endif
                        </b></td>
                    <td class="text-center">
                        @if($item->is_multiple==true)

                        <span class="badge bg-info text-uppercase">Multiple</span>

                        @else
                        <span class="badge bg-success text-uppercase">Sekali pengisian</span>

                        @endif
                    </td>
                    <td class="text-center">
                        <button onclick="showSesi('{{$item->id}}','{{$item->bagianAwalAkhir->bagian_id_first}}')" data-bs-toggle="modal" data-bs-target="#exampleModal" class="btn btn-warning">Isi Survei</button>
                        <!-- @if($item->bagianAwalAkhir->bagian_id_first ==null) -->

                        <!-- @else
                        <a href=" {{route('user.show.pertanyaan',[$item->id,$item->bagianAwalAkhir->bagian_id_first,'baru'])}}" class="btn btn-info">
                            @if(count($item->sesi)==0)
                            Mulai
                            @elseif($item->sesi[0]->sesi_status==false)
                            Lanjutkan
                            @else
                            Edit
                            @endif

                            Survei
                        </a>
                        @endif -->

                    </td>
                </tr>
                @endforeach

                </body>
        </table>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabelDefault" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <div class="card mb-5">
                    <div class="card-body">
                        <h2>Sesi Survei Anda</h2>
                        <button id="addSesi" class="btn btn-primary btn-sm">+ Pengisian Baru</button>
                        <!-- <p>Mohon buat sesi survei anda</p> -->
                        <table class="table table-bordered table-hover mt-4">
                            <thead>
                                <tr class="text-center">
                                    <th>No</th>
                                    <th>Tanggal Sesi</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="show-sesi">

                            </tbody>
                        </table>

                    </div>

                </div>
            </div>
        </div>
    </div>
</div>


@endsection

@section('js')
<script>
    async function gantiKeDosen() {
        let konfirm = confirm('Anda akan berganti ke dosen, yakin ubah status dari tendik ke dosen?')
        if (!konfirm)
            return
        let url = "{{route('change.pegawai',':id')}}"
        url = url.replace(':id', "{{$pegawai_id}}")
        let send = await fetch(url);
        let response = await send.json()
        console.log(response);
        if (response.status == true)
            window.location.reload('{{route("user.dashboard")}}')
    }
    async function gantiKePegawai() {
        let konfirm = confirm('Anda akan berganti ke pegawai, yakin ubah status dari dosen ke tendik?')
        if (!konfirm)
            return
        let url = "{{route('change.dosen',':id')}}"
        url = url.replace(':id', "{{$pegawai_id}}")
        let send = await fetch(url);
        let response = await send.json()
        console.log(response);
        if (response.status == true)
            window.location.reload('{{route("user.dashboard")}}')
    }
    async function showSesi(id, bagian) {
        // alert(id)
        // document.querySelector('#survei_id').value = id;
        let url = "{{route('get.sesi',[':surveiId',':userId'])}}"
        url = url.replace(':surveiId', id)
        url = url.replace(':userId', '{{$user_id}}')
        let send = await fetch(url);
        let response = await send.json()
        console.log(response);
        let html = ''
        const addSesiButton = document.querySelector('#addSesi')
        addSesiButton.addEventListener('click', () => {
            let konfirm = confirm('yakin buat sesi pengisian survei baru?')
            if (!konfirm)
                return
            let link = "{{route('user.show.pertanyaan',[':surveiId',':bagianId',':sesiId'])}}"
            link = link.replace(':surveiId', id)
            link = link.replace(':bagianId', bagian)
            link = link.replace(':sesiId', 'baru')
            window.location.href = link
        })
        // addSesiButton.href = link
        response.data.map((data, index) => {

            let status = (data.sesi_status == 1) ? '<span class="badge bg-success">Selesai</span>' : '<span class="badge bg-warning">Belum Selesai</span>'
            let tombol = (data.sesi_status == 1) ? 'Selesai' : 'Lanjut'
            let link = "{{route('user.show.pertanyaan',[':surveiId',':bagianId',':sesiId'])}}"
            link = link.replace(':surveiId', id)
            link = link.replace(':bagianId', bagian)
            link = link.replace(':sesiId', data.id)
            // let edit = (data.status == 1) ? 'Ub' : 'belum selesai'
            html += `<tr class="text-center">
                <td>${index+1}</td>
                <td>${formatDate(data.created_at)}</td>
                <td>${status}</td>
                <td ><a class="btn btn-info btn-sm" href="${link}">${tombol}</a></td>
            </tr>`
        })
        document.querySelector('#show-sesi').innerHTML = html
    }

    function formatDate(dateString) {
        // Buat objek Date dari string tanggal
        const date = new Date(dateString);

        // Daftar nama bulan dalam bahasa Indonesia
        const months = [
            "Januari", "Februari", "Maret", "April", "Mei", "Juni",
            "Juli", "Agustus", "September", "Oktober", "November", "Desember"
        ];

        // Dapatkan bagian tanggal, bulan, dan tahun
        const day = date.getDate();
        const month = months[date.getMonth()];
        const year = date.getFullYear();

        // Dapatkan bagian jam dan menit
        const hours = String(date.getHours()).padStart(2, '0');
        const minutes = String(date.getMinutes()).padStart(2, '0');

        // Format tanggal sesuai kebutuhan
        return `${day} ${month} ${year} Jam ${hours}:${minutes}`;
    }
</script>
@endsection
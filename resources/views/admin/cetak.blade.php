<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <style>
        table {
            border-collapse: collapse;
            table-layout: auto;
            width: 100%;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 10px;
        }

        th {
            /* background-color: #4CAF50; */
            /* color: white; */
        }

        .text-center {
            text-align: center;
        }
    </style>
</head>

<body>
    <h4 class="text-center">Jawaban {{$survei->survei_nama}}</h4>
    <button class="btn btn-success btn-sm" onclick="copyTable()">Copy Tabel</button>
    <table style="margin: 10px 0;" id="data">
        <thead>
            <tr class="text-center">
                <th rowspan="3">No</th>
                <th rowspan="3">Tanggal</th>
                @if($survei->survei_untuk=="mahasiswa")

                <th rowspan="3">NIM</th>
                <th rowspan="3">Nama</th>
                <th rowspan="3">Prodi</th>
                @elseif($survei->survei_untuk=="dosen" || $survei->survey_untuk=="pegawai")
                <th rowspan="3">NIP</th>
                <th rowspan="3">Nama</th>
                @else
                <th rowspan="3">Mitra / Eksternal</th>
                @endif

            </tr>
            <tr>
                @foreach ($bagian as $row)
                <th colspan=" {{count($row->pertanyaan)}}" style="text-align:center;
                white-space: nowrap;">{{$row->bagian_kode}}. {{$row->bagian_nama}}</th>
                @endforeach
            </tr>
            <tr>
                @foreach ($bagian as $row)
                @foreach ($row->pertanyaan as $tanya)
                <th>{{$tanya->pertanyaan}}</th>
                @endforeach
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($jawaban as $index => $jawab)
            <tr>
                <td>{{$jawaban->firstItem() + $index}}</td>
                <td>{{\Carbon\Carbon::parse($jawab->sesi_tanggal)->format('d-m-Y');}}</td>

                @if($survei->survei_untuk=="mahasiswa")
                <td>{{$jawab->user->userMahasiswa->mahasiswa->nim}}</td>
                <td>{{$jawab->user->userMahasiswa->mahasiswa->dataDiri->nama_lengkap}}</td>
                <td>{{$jawab->user->userMahasiswa->mahasiswa->prodi->organisasi_singkatan}}</td>
                @elseif($survei->survei_untuk=="dosen" || $survei->survey_untuk=="pegawai")
                <td>{{$jawab->user->userPegawai->pegawai->pegawai_nomor_induk}}</td>
                <td>{{$jawab->user->userPegawai->pegawai->dataDiri->nama_lengkap}}</td>
                @else
                <td>{{$jawab->mitra->mitra_nama}} / {{$jawab->mitra->mitra_instansi}}</td>
                @endif
                @foreach ($jawab->jawaban as $row)
                <td>{{$row}}</td>
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $jawaban->links() }}

    <script>
        getdata()
        async function getdata() {
            let dataUser = @json($jawaban);
            let dataId = []
            // return console.log(dataUser);
            dataUser.data.forEach(function(data) {
                dataId.push(data.user.name);
            });
            // console.log(dataId);
            let dataSend = new FormData()
            dataSend.append('iddata', JSON.stringify(dataId))
            let response = await fetch('https://sia.iainkendari.ac.id/alumni/tracer/data-alumni', {
                method: "POST",
                body: dataSend
            })
            let responseMessage = await response.json()
            console.log(responseMessage);
            responseMessage.data.forEach(function(data) {
                document.querySelector("#nim" + data.iddata).innerText = data.nim;
                document.querySelector("#nama" + data.iddata).innerText = data.nama;
                document.querySelector("#prodi" + data.iddata).innerText = data.prodi;
            })
        }

        function copyTable() {
            var table = document.getElementById("data");
            var range = document.createRange();
            range.selectNode(table);
            window.getSelection().removeAllRanges();
            window.getSelection().addRange(range);
            document.execCommand("copy");
            window.getSelection().removeAllRanges();
            alert("Tabel telah disalin!");
        }
    </script>

</body>

</html>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak - Hasil Tracer</title>
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
            background-color: #4CAF50;
            color: white;
        }
    </style>
</head>


<body>
    <h1>{{$bagian[0]->survei->survei_untuk}} : Data Survei {{$bagian[0]->survei->survei_nama}}</h1>
    <table border="1">
        <thead>
            <tr>
                <th rowspan="3">No</th>
                <th rowspan="3">Tanggal</th>
                @if($bagian[0]->survei->survei_untuk!="mahasiswa")
                <th rowspan="3">NIP</th>
                <th rowspan="3">Nama</th>
                @endif
                {!! ($bagian[0]->survei->survei_untuk == "mahasiswa" ? "<th rowspan='3'>Prodi</th>" : "") !!}
            </tr>
            <tr>
                @foreach ($bagian as $row)
                <th colspan="{{ count($row->pertanyaan) }}" style="text-align:center; white-space: nowrap;">
                    {{ $row->bagian_kode }}. {{ $row->bagian_nama }}
                </th>
                @endforeach
            </tr>
            <tr>
                @foreach ($bagian as $row)
                @foreach ($row->pertanyaan as $tanya)
                <th>{{ $tanya->pertanyaan }}</th>
                @endforeach
                @endforeach
            </tr>
        </thead>
        <tbody id="sesi-container">
            <!-- Data sesi akan dimuat menggunakan JavaScript -->
        </tbody>
    </table>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Ambil path URL
            const untuk = "{{$bagian[0]->survei->survei_untuk}}"
            const pathSegments = window.location.pathname.split("/");

            // Cari posisi "periode" dan "fakultas" dalam URL
            const surveiIndex = pathSegments.indexOf("survei");

            // Ambil nilai periode dan fakultas
            const surveiId = surveiIndex !== -1 ? pathSegments[surveiIndex + 1] : null;

            if (!surveiId) {
                console.error("Survei tidak ditemukan dalam URL!");
                return;
            }

            console.log(`Periode: ${surveiId}`); // Debugging

            const sesiContainer = document.getElementById("sesi-container");

            // Ambil daftar pertanyaan dari backend untuk menentukan urutan kolom
            fetch(`/api/survei/${surveiId}/get-pertanyaan`)
                .then(response => response.json())
                .then(pertanyaanList => {
                    console.log(pertanyaanList)
                    // return
                    const pertanyaanIds = pertanyaanList.map(p => p.id); // Urutan ID pertanyaan
                    console.log("Pertanyaan:", pertanyaanIds);

                    // Ambil sesi dengan AJAX
                    fetch(`/api/survei/${surveiId}/sesi`)
                        .then(response => response.json())
                        .then(sesiList => {
                            console.log(sesiList);

                            sesiList.forEach((sesi, index) => {
                                let row = document.createElement("tr");
                                if (untuk == "mahasiswa") {
                                    row.innerHTML = `
                                        <td>${index + 1}</td>
                                        <td>${new Date(sesi.sesi_tanggal).toLocaleDateString("id-ID")}</td>
                                        <td>${sesi.user?.user_mahasiswa?.mahasiswa?.prodi?.organisasi_singkatan ?? '-'}</td>
                                    `;
                                    // row.innerHTML = `
                                    //     <td>${index + 1}</td>
                                    //     <td>${new Date(sesi.sesi_tanggal).toLocaleDateString("id-ID")}</td>
                                    //     <td>${sesi.user?.user_mahasiswa?.mahasiswa?.nim ?? '-'}</td>
                                    //     <td>${sesi.user?.user_mahasiswa?.mahasiswa?.data_diri?.nama_lengkap ?? '-'}</td>
                                    //     <td>${sesi.user?.user_mahasiswa?.mahasiswa?.prodi?.organisasi_singkatan ?? '-'}</td>
                                    // `;
                                } else {
                                    row.innerHTML = `
                                        <td>${index + 1}</td>
                                        <td>${new Date(sesi.sesi_tanggal).toLocaleDateString("id-ID")}</td>
                                        <td>${sesi.user?.user_pegawai?.pegawai?.pegawai_nomor_induk ?? '-'}</td>
                                        <td>${sesi.user?.user_pegawai?.pegawai?.data_diri?.nama_lengkap ?? '-'}</td>
                                    `;

                                }

                                // Tambahkan kolom kosong untuk jawaban, sesuai dengan urutan pertanyaan
                                pertanyaanIds.forEach(pertanyaanId => {
                                    let cell = document.createElement("td");
                                    cell.id = `jawaban-${sesi.id}-${pertanyaanId}`;
                                    cell.innerHTML = "Loading...";
                                    row.appendChild(cell);
                                });

                                sesiContainer.appendChild(row);

                                // Ambil jawaban dari backend
                                fetch(`/api/get-jawaban?sesi_id=${sesi.id}`)
                                    .then(response => response.json())
                                    .then(jawabanData => {
                                        pertanyaanIds.forEach(pertanyaanId => {
                                            let jawabanCell = document.getElementById(`jawaban-${sesi.id}-${pertanyaanId}`);
                                            let jawaban = jawabanData[pertanyaanId]?.map(j => j.jawaban).join(", ") ?? "-";
                                            jawabanCell.innerHTML = jawaban;
                                        });
                                    });
                            });
                        });
                });
        });
    </script>
</body>

</html>
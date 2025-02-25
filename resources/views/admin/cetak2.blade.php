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
    <table border="1">
        <thead>
            <tr>
                <th rowspan="3">No</th>
                <th rowspan="3">Tanggal</th>
                <th rowspan="3">NIM</th>
                <th rowspan="3">Nama</th>
                <th rowspan="3">Prodi</th>
            </tr>
            <tr>
                @foreach ($bagian as $row)
                <th colspan="{{ count($row->pertanyaan) }}" style="text-align:center; white-space: nowrap;">
                    {{ $row->step_kode }}. {{ $row->step_nama }}
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
            const pathSegments = window.location.pathname.split("/");

            // Cari posisi "periode" dan "fakultas" dalam URL
            const periodeIndex = pathSegments.indexOf("periode");
            const fakultasIndex = pathSegments.indexOf("fakultas");

            // Ambil nilai periode dan fakultas
            const periode = periodeIndex !== -1 ? pathSegments[periodeIndex + 1] : null;
            const fakultas = fakultasIndex !== -1 ? pathSegments[fakultasIndex + 1] : null;

            if (!periode || !fakultas) {
                console.error("Periode atau fakultas tidak ditemukan dalam URL!");
                return;
            }

            console.log(`Periode: ${periode}, Fakultas: ${fakultas}`); // Debugging

            const sesiContainer = document.getElementById("sesi-container");

            // Ambil daftar pertanyaan dari backend untuk menentukan urutan kolom
            fetch(`/api/get-pertanyaan`)
                .then(response => response.json())
                .then(pertanyaanList => {
                    const pertanyaanIds = pertanyaanList.map(p => p.id); // Urutan ID pertanyaan
                    console.log("Pertanyaan:", pertanyaanIds);

                    // Ambil sesi dengan AJAX
                    fetch(`/api/get-sesi?periode=${periode}&fakultas=${fakultas}`)
                        .then(response => response.json())
                        .then(sesiList => {
                            sesiList.forEach((sesi, index) => {
                                let row = document.createElement("tr");
                                row.innerHTML = `
                            <td>${index + 1}</td>
                            <td>${new Date(sesi.sesi_tanggal).toLocaleDateString("id-ID")}</td>
                            <td>${sesi.user?.mahasiswa?.nim ?? '-'}</td>
                            <td>${sesi.user?.mahasiswa?.data_diri?.nama_lengkap ?? '-'}</td>
                            <td>${sesi.user?.mahasiswa?.prodi?.organisasi_singkatan ?? '-'}</td>
                        `;

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
@extends('template')

@section('css')

<!-- <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script> -->
<style>
    .link:hover {
        cursor: pointer;
    }
</style>
@endsection
@section('content')
<!-- Text Content Start -->
<section class="scroll-section" id="textContent">
    <div class="card mb-5">
        <div class="card-body">

            <h4>Pilih Survei</h4>
            <div class="row">
                <div class="col-md-12">
                    <select class="form-select" id="survei">
                        <option value="">-- Pilih Survei --</option>
                        @foreach($data as $item)
                        <option value="{{$item->id}}">{{$item->survei_nama}} ({{$item->survei_untuk}})</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-12 mt-4">
                    <button class="btn btn-primary btn-icon btn-icon-start ms-1" id="partisipan-button">
                        <i data-cs-icon="database"></i>
                        <span>Jawaban</span>
                    </button>
                    <button class="btn btn-info btn-icon btn-icon-start ms-1" id="statistik-button">
                        <i data-cs-icon="chart-4"></i>
                        <span>Statistik</span>
                    </button>
                    <button class="btn btn-success btn-icon btn-icon-start ms-1" onclick="cetakPertanyaan(this)">
                        <i data-cs-icon="print"></i>
                        <span>Lihat Semua Data</span>
                    </button>
                </div>

            </div>
        </div>
    </div>
</section>
<!-- Text Content End -->

<section class="scroll-section" id="formRow">
    <div class="card mb-5">
        <div class="card-body" id="content">

        </div>
    </div>
</section>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabelDefault" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <div class="card mb-5">
                    <div class="card-body">
                        <h2>Detail Jawaban</h2>
                        <table class="table table-bordered table-hover mt-4">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Bagian</th>
                                    <th>Pertanyaan</th>
                                    <th>Jawaban</th>
                                </tr>
                            </thead>
                            <tbody id="show-jawaban">

                            </tbody>
                        </table>

                    </div>

                </div>
            </div>
        </div>
    </div>
</div>


<!-- Template -->
<template id="partisipan-template">
    <h2>Daftar Partisipan Survei</h2>

    <table class="table table-striped table-hover mt-4">
        <thead>
            <tr>
                <th scope="col">No</th>
                <th scope="col">TANGGAL SURVEI</th>
                <th scope="col">PENGISIAN</th>
                <th scope="col">DETAIL JAWABAN</th>
            </tr>
        </thead>
        <tbody id="show-data">

        </tbody>
    </table>
    <div>
        <nav>
            <ul class="pagination" id="pagination">
            </ul>
        </nav>
    </div>
</template>

<template id="statistik-template">
    <h2>Statistik Survei</h2>

    <div id="show-bagian">

    </div>
    <div id="show-pertanyaan">

    </div>

    <div id="show-filter" style="display:none">
        <h4 class="mt-5">Filter</h4>
        <div class="row mb-5">

            <div class="col-md-3">
                <select class="form-select" id="fakultas">
                </select>
            </div>
            <div class="col-md-3">
                <select class="form-select" id="prodi">
                </select>
            </div>
            <div class="col-md-3">
                <button class="btn btn-warning" id="filter">Filter</button>
            </div>

        </div>
    </div>

    <div id="show-statistik" style="display:none">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th scope="col">No</th>
                    <th scope="col">Pilihan Jawaban</th>
                    <th scope="col">Total</th>
                </tr>
            </thead>
            <tbody id="show-data-table">

            </tbody>
        </table>
        <div id="show-diagram">
            <h2 class="mt-5">Diagram</h2>
            <div>
                <label class="form-label d-block">Pilih Diagram</label>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="diagram" id="pie" value="pie" />
                    <label class="form-check-label" for="pie">Pie</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="diagram" id="bar" value="bar" />
                    <label class="form-check-label" for="bar">Bar</label>
                </div>
                <div class="form-check form-check-inline">
                    <button class="btn btn-primary" id="terapkan">Terapkan</button>
                </div>
            </div>
            <div id="showDiagram" class="col-sm-12" style="height: 500px;"></div>
        </div>
    </div>
</template>

@endsection
@section('js')
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<script>
    const content = document.querySelector('#content')
    const partisipanButton = document.querySelector('#partisipan-button')
    const statistikButton = document.querySelector('#statistik-button')
    const eksportButton = document.querySelector('#eksport-button')

    document.querySelector("#survei").addEventListener("change", async function(e) {
        let surveiId = e.target.options[e.target.selectedIndex].value
        // alert(surveiId)
        showPartisipan(surveiId)
        partisipanButton.setAttribute('onclick', `showPartisipan(${surveiId})`)
        statistikButton.setAttribute('onclick', `showStatistik(${surveiId})`)
        // eksportButton.setAttribute('onclick', `showEksport(${surveiId})`)

    })


    function cetakPertanyaan(button) {
        if (document.querySelector("#survei").value == "")
            return alert('pilih survei dulu')
        let url = "{{ route('admin.survei.cetak', ':id') }}";
        url = url.replace(":id", document.querySelector("#survei").value);
        window.open(url, "_blank");
    }

    function showTemplatePartisipan() {
        content.innerHTML = ""
        const template = document.querySelector("#partisipan-template")
        const templateContent = template.content.cloneNode(true);
        content.appendChild(templateContent)
    }

    function showTemplateStatistik() {
        content.innerHTML = ""
        const template = document.querySelector("#statistik-template")
        const templateContent = template.content.cloneNode(true);
        content.appendChild(templateContent)
    }

    function showTemplateEksport() {
        content.innerHTML = ""
        const template = document.querySelector("#partisipan")
        const templateContent = template.content.cloneNode(true);
        content.appendChild(templateContent)
    }
    async function showStatistik(id) {
        showTemplateStatistik()
        let url = "{{route('bagian.by.survei',':surveiId')}}"
        url = url.replace(':surveiId', `${id}`)
        fetchData = await fetch(url)
        response = await fetchData.json()
        console.log(response);

        let showBagian = document.querySelector("#show-bagian")
        showBagian.innerHTML = ""

        let select = document.createElement('select');
        select.className = "form-select mt-3"
        select.id = "bagian"

        let option = document.createElement('option')
        option.innerText = "Pilih Bagian"
        option.value = ""

        select.appendChild(option)

        response.data.forEach(function(data, i) {
            option = document.createElement('option')
            option.innerText = `${data.bagian_kode} - ${data.bagian_nama}`
            option.value = data.id
            select.appendChild(option)
        });
        showBagian.appendChild(select)
        let bagianSelect = document.querySelector('#bagian')
        bagianSelect.addEventListener('change', async function() {
            document.querySelector('#show-filter').style.display = "none";

            let showPertanyaan = document.querySelector("#show-pertanyaan")
            showPertanyaan.innerHTML = ""
            let showDataTable = document.querySelector("#show-data-table")
            let show = document.querySelector("#show-statistik")
            show.style.display = 'none'
            url = "{{route('pertanyaan.by.bagian',':bagianId')}}"
            url = url.replace(':bagianId', `${bagianSelect.value}`)
            fetchData = await fetch(url)
            response = await fetchData.json()
            console.log(response);

            select = document.createElement('select');
            select.className = "form-select mt-3"
            select.id = "pertanyaan"

            option = document.createElement('option')
            option.innerText = "Pilih Pertanyaan"
            option.value = ""

            select.appendChild(option)

            response.data.forEach(function(data, i) {
                option = document.createElement('option')
                option.innerText = `${data.pertanyaan}`
                option.value = data.id
                select.appendChild(option)
            });
            showPertanyaan.appendChild(select)
            let pertanyaanSelect = document.querySelector('#pertanyaan')
            pertanyaanSelect.addEventListener('change', async function() {
                document.querySelector('#show-filter').style.display = "none";
                show.style.display = "none";

                url = "{{route('jawaban.count.by.survei.and.pertanyaan',[':surveiId',':pertanyaanId'])}}"
                url = url.replace(':surveiId', `${id}`)
                url = url.replace(':pertanyaanId', `${pertanyaanSelect.value}`)
                fetchData = await fetch(url)
                response = await fetchData.json()
                console.log(response);
                // response.data.forEach(function(data, i) {

                // });
                let fakultasSelect = document.querySelector('#fakultas')

                let prodiSelect = document.querySelector('#prodi')
                if (content.dataset.surveiUntuk == "mahasiswa") {
                    document.querySelector('#show-filter').style.display = "block";
                    fakultasSelect.innerHTML = "";
                    prodiSelect.innerHTML = "";
                    let url = "{{route('get.organisasi','fakultas')}}"
                    let fetchData = await fetch(url)
                    let response = await fetchData.json()
                    console.log(response);
                    let contents = `<option value="semua-fakultas">Semua Fakultas</option>`
                    response.data[0].organisasi.forEach(fakultas => {
                        contents += `<option value="${fakultas.id}">${fakultas.organisasi_singkatan}</option>`
                    })
                    fakultasSelect.innerHTML = contents

                    fakultasSelect.addEventListener('change', async function(e) {
                        // alert(e.target.value);
                        prodiSelect.innerHTML = ""
                        if (e.target.value == "semua-fakultas")
                            return prodiSelect.setAttribute('disabled', 'disabled')
                        prodiSelect.removeAttribute('disabled')
                        let url = "{{route('get.prodi',':id')}}"
                        url = url.replace(':id', e.target.value)
                        let fetchData = await fetch(url)
                        let response = await fetchData.json()
                        console.log(response);
                        let contents = `<option value="semua-prodi">Semua Prodi</option>`
                        response.data.forEach(prodi => {
                            contents += `<option value="${prodi.id}">${prodi.organisasi_singkatan}</option>`
                        })
                        prodiSelect.innerHTML = contents
                    })
                    document.querySelector("#filter").addEventListener('click', async function(event) {
                        // alert(fakultasSelect.value)
                        show.style.display = 'none'
                        let url = "{{route('jawaban.count.by.survei.and.pertanyaan.filter',[':pertanyaanId'])}}"
                        url = url.replace(':pertanyaanId', `${pertanyaanSelect.value}`)
                        let dataSend = new FormData()
                        if (fakultasSelect.value === "semua-fakultas") {
                            dataSend.append('filter', "semua")
                            dataSend.append('id', fakultasSelect.value)
                        } else if (prodiSelect.value == "semua-prodi") {
                            dataSend.append('filter', "fakultas")
                            dataSend.append('id', fakultasSelect.value)
                        } else {
                            dataSend.append('filter', "prodi")
                            dataSend.append('id', prodiSelect.value)
                        }
                        let fetchData = await fetch(url, {
                            method: "POST",
                            body: dataSend
                        })
                        let response = await fetchData.json()
                        // return;
                        show.style.display = 'block'
                        showDataTable.innerHTML = ""

                        let fragment = document.createDocumentFragment();
                        let jenisJawaban = response.data.pertanyaan_jenis_jawaban
                        fragment.innerHTML = '';
                        if (jenisJawaban == "Text" || jenisJawaban == "Text Panjang") {
                            show.querySelector('#show-diagram').style.display = 'none';
                            if (response.data.jawaban.length == 0) {
                                let tr = document.createElement('tr');
                                let pilihanJawaban = document.createElement('td');
                                pilihanJawaban.setAttribute('colspan', '3')
                                pilihanJawaban.className = "text-center"
                                pilihanJawaban.innerText = "Data tidak ada"
                                tr.appendChild(pilihanJawaban)
                                fragment.appendChild(tr);
                            } else {
                                response.data.jawaban.forEach(function(data, i) {
                                    let tr = document.createElement('tr');
                                    let nomor = document.createElement('td');
                                    nomor.innerText = i + 1
                                    let pilihanJawaban = document.createElement('td');
                                    pilihanJawaban.innerText = data.jawaban
                                    let total = document.createElement('td');
                                    total.innerText = ''
                                    tr.appendChild(nomor)
                                    tr.appendChild(pilihanJawaban)
                                    tr.appendChild(total)
                                    fragment.appendChild(tr);
                                });

                            }
                        } else {
                            show.querySelector('#show-diagram').style.display = 'block';
                            if (response.data.jawaban.length == 0) {
                                let tr = document.createElement('tr');
                                let pilihanJawaban = document.createElement('td');
                                pilihanJawaban.setAttribute('colspan', '3')
                                pilihanJawaban.className = "text-center"
                                pilihanJawaban.innerText = "Data tidak ada"
                                tr.appendChild(pilihanJawaban)
                                fragment.appendChild(tr);
                            } else {
                                response.data.pilihan_jawaban.forEach(function(data, i) {
                                    let tr = document.createElement('tr');
                                    let nomor = document.createElement('td');
                                    nomor.innerText = i + 1
                                    let pilihanJawaban = document.createElement('td');
                                    pilihanJawaban.innerText = data.pilihan_jawaban
                                    let total = document.createElement('td');
                                    total.innerText = data.total
                                    tr.appendChild(nomor)
                                    tr.appendChild(pilihanJawaban)
                                    tr.appendChild(total)
                                    fragment.appendChild(tr);
                                });
                            }
                        }

                        showDataTable.appendChild(fragment)

                        let daftarJawaban = response.data.pilihan_jawaban
                        google.charts.load('current', {
                            'packages': ['corechart', 'bar']
                        });
                        google.charts.setOnLoadCallback(drawChart);

                        function drawChart() {
                            let jawaban = [
                                ['Task', pertanyaan.options[pertanyaan.selectedIndex].innerText]
                            ]
                            daftarJawaban.map(function(data) {
                                jawaban.push([data.pilihan_jawaban, data.total])
                            });
                            console.log(jawaban);
                            var data = google.visualization.arrayToDataTable(
                                jawaban
                            );
                            var options = {
                                title: pertanyaan.options[pertanyaan.selectedIndex].innerText,
                                sliceVisibilityThreshold: 0,
                                pieHole: 0.4,
                            };
                            var chart
                            document.querySelector("#formRow").style.display = 'block'
                            document.getElementById('showDiagram').innerHTML = ""
                            document.querySelector("#pie").checked = true;
                            chart = new google.visualization.PieChart(document.getElementById('showDiagram')); //default tampilkan pie chart
                            chart.innerHTML = ""
                            chart.draw(data, options);
                            document.querySelector("#terapkan").addEventListener("click", function() {
                                let diagram = document.querySelector('input[name="diagram"]:checked').value;
                                if (diagram == "pie")
                                    chart = new google.visualization.PieChart(document.getElementById('showDiagram'));
                                if (diagram == "bar")
                                    chart = new google.visualization.BarChart(document.getElementById('showDiagram'));
                                chart.innerHTML = ""
                                chart.draw(data, options);
                            });
                        }

                    })
                } else if (content.dataset.surveiUntuk == "pegawai" || content.dataset.surveiUntuk == "dosen") { //dosen
                    show.style.display = 'none'
                    let url = "{{route('jawaban.count.by.survei.and.pertanyaan.filter',[':pertanyaanId'])}}"
                    url = url.replace(':pertanyaanId', `${pertanyaanSelect.value}`)
                    let dataSend = new FormData()
                    dataSend.append('filter', "semua")
                    // dataSend.append('id', fakultasSelect.value)
                    let fetchData = await fetch(url, {
                        method: "POST",
                        body: dataSend
                    })
                    let response = await fetchData.json()
                    // return;
                    show.style.display = 'block'
                    showDataTable.innerHTML = ""

                    let fragment = document.createDocumentFragment();
                    let jenisJawaban = response.data.pertanyaan_jenis_jawaban
                    fragment.innerHTML = '';
                    if (jenisJawaban == "Text" || jenisJawaban == "Text Panjang") {
                        show.querySelector('#show-diagram').style.display = 'none';
                        if (response.data.jawaban.length == 0) {
                            let tr = document.createElement('tr');
                            let pilihanJawaban = document.createElement('td');
                            pilihanJawaban.setAttribute('colspan', '3')
                            pilihanJawaban.className = "text-center"
                            pilihanJawaban.innerText = "Data tidak ada"
                            tr.appendChild(pilihanJawaban)
                            fragment.appendChild(tr);
                        } else {
                            response.data.jawaban.forEach(function(data, i) {
                                let tr = document.createElement('tr');
                                let nomor = document.createElement('td');
                                nomor.innerText = i + 1
                                let pilihanJawaban = document.createElement('td');
                                pilihanJawaban.innerText = data.jawaban
                                let total = document.createElement('td');
                                total.innerText = ''
                                tr.appendChild(nomor)
                                tr.appendChild(pilihanJawaban)
                                tr.appendChild(total)
                                fragment.appendChild(tr);
                            });

                        }
                    } else {
                        show.querySelector('#show-diagram').style.display = 'block';
                        if (response.data.jawaban.length == 0) {
                            let tr = document.createElement('tr');
                            let pilihanJawaban = document.createElement('td');
                            pilihanJawaban.setAttribute('colspan', '3')
                            pilihanJawaban.className = "text-center"
                            pilihanJawaban.innerText = "Data tidak ada"
                            tr.appendChild(pilihanJawaban)
                            fragment.appendChild(tr);
                        } else {
                            response.data.pilihan_jawaban.forEach(function(data, i) {
                                let tr = document.createElement('tr');
                                let nomor = document.createElement('td');
                                nomor.innerText = i + 1
                                let pilihanJawaban = document.createElement('td');
                                pilihanJawaban.innerText = data.pilihan_jawaban
                                let total = document.createElement('td');
                                total.innerText = data.total
                                tr.appendChild(nomor)
                                tr.appendChild(pilihanJawaban)
                                tr.appendChild(total)
                                fragment.appendChild(tr);
                            });
                        }
                    }

                    showDataTable.appendChild(fragment)

                    let daftarJawaban = response.data.pilihan_jawaban
                    google.charts.load('current', {
                        'packages': ['corechart', 'bar']
                    });
                    google.charts.setOnLoadCallback(drawChart);

                    function drawChart() {
                        let jawaban = [
                            ['Task', pertanyaan.options[pertanyaan.selectedIndex].innerText]
                        ]
                        daftarJawaban.map(function(data) {
                            jawaban.push([data.pilihan_jawaban, data.total])
                        });
                        console.log(jawaban);
                        var data = google.visualization.arrayToDataTable(
                            jawaban
                        );
                        var options = {
                            title: pertanyaan.options[pertanyaan.selectedIndex].innerText,
                            sliceVisibilityThreshold: 0,
                            pieHole: 0.4,
                        };
                        var chart
                        document.querySelector("#formRow").style.display = 'block'
                        document.getElementById('showDiagram').innerHTML = ""
                        document.querySelector("#pie").checked = true;
                        chart = new google.visualization.PieChart(document.getElementById('showDiagram')); //default tampilkan pie chart
                        chart.innerHTML = ""
                        chart.draw(data, options);
                        document.querySelector("#terapkan").addEventListener("click", function() {
                            let diagram = document.querySelector('input[name="diagram"]:checked').value;
                            if (diagram == "pie")
                                chart = new google.visualization.PieChart(document.getElementById('showDiagram'));
                            if (diagram == "bar")
                                chart = new google.visualization.BarChart(document.getElementById('showDiagram'));
                            chart.innerHTML = ""
                            chart.draw(data, options);
                        });
                    }
                } else { //mitra
                    show.style.display = 'none'
                    let url = "{{route('jawaban.count.by.survei.and.pertanyaan.filter',[':pertanyaanId'])}}"
                    url = url.replace(':pertanyaanId', `${pertanyaanSelect.value}`)
                    let dataSend = new FormData()
                    dataSend.append('filter', "mitra")
                    let fetchData = await fetch(url, {
                        method: "POST",
                        body: dataSend
                    })
                    let response = await fetchData.json()
                    console.log(response);;
                    show.style.display = 'block'
                    showDataTable.innerHTML = ""

                    let fragment = document.createDocumentFragment();
                    let jenisJawaban = response.data.pertanyaan_jenis_jawaban
                    fragment.innerHTML = '';
                    if (jenisJawaban == "Text" || jenisJawaban == "Text Panjang") {
                        show.querySelector('#show-diagram').style.display = 'none';
                        if (response.data.jawaban_mitra.length == 0) {
                            let tr = document.createElement('tr');
                            let pilihanJawaban = document.createElement('td');
                            pilihanJawaban.setAttribute('colspan', '3')
                            pilihanJawaban.className = "text-center"
                            pilihanJawaban.innerText = "Data tidak ada"
                            tr.appendChild(pilihanJawaban)
                            fragment.appendChild(tr);
                        } else {
                            response.data.jawaban_mitra.forEach(function(data, i) {
                                let tr = document.createElement('tr');
                                let nomor = document.createElement('td');
                                nomor.innerText = i + 1
                                let pilihanJawaban = document.createElement('td');
                                pilihanJawaban.innerText = data.jawaban
                                let total = document.createElement('td');
                                total.innerText = ''
                                tr.appendChild(nomor)
                                tr.appendChild(pilihanJawaban)
                                tr.appendChild(total)
                                fragment.appendChild(tr);
                            });

                        }
                    } else {
                        show.querySelector('#show-diagram').style.display = 'block';
                        if (response.data.jawaban_mitra.length == 0) {
                            let tr = document.createElement('tr');
                            let pilihanJawaban = document.createElement('td');
                            pilihanJawaban.setAttribute('colspan', '3')
                            pilihanJawaban.className = "text-center"
                            pilihanJawaban.innerText = "Data tidak ada"
                            tr.appendChild(pilihanJawaban)
                            fragment.appendChild(tr);
                        } else {
                            response.data.pilihan_jawaban.forEach(function(data, i) {
                                let tr = document.createElement('tr');
                                let nomor = document.createElement('td');
                                nomor.innerText = i + 1
                                let pilihanJawaban = document.createElement('td');
                                pilihanJawaban.innerText = data.pilihan_jawaban
                                let total = document.createElement('td');
                                total.innerText = data.total
                                tr.appendChild(nomor)
                                tr.appendChild(pilihanJawaban)
                                tr.appendChild(total)
                                fragment.appendChild(tr);
                            });
                        }
                    }

                    showDataTable.appendChild(fragment)

                    let daftarJawaban = response.data.pilihan_jawaban
                    google.charts.load('current', {
                        'packages': ['corechart', 'bar']
                    });
                    google.charts.setOnLoadCallback(drawChart);

                    function drawChart() {
                        let jawaban = [
                            ['Task', pertanyaan.options[pertanyaan.selectedIndex].innerText]
                        ]
                        daftarJawaban.map(function(data) {
                            jawaban.push([data.pilihan_jawaban, data.total])
                        });
                        console.log(jawaban);
                        var data = google.visualization.arrayToDataTable(
                            jawaban
                        );
                        var options = {
                            title: pertanyaan.options[pertanyaan.selectedIndex].innerText,
                            sliceVisibilityThreshold: 0,
                            pieHole: 0.4,
                        };
                        var chart
                        document.querySelector("#formRow").style.display = 'block'
                        document.getElementById('showDiagram').innerHTML = ""
                        document.querySelector("#pie").checked = true;
                        chart = new google.visualization.PieChart(document.getElementById('showDiagram')); //default tampilkan pie chart
                        chart.innerHTML = ""
                        chart.draw(data, options);
                        document.querySelector("#terapkan").addEventListener("click", function() {
                            let diagram = document.querySelector('input[name="diagram"]:checked').value;
                            if (diagram == "pie")
                                chart = new google.visualization.PieChart(document.getElementById('showDiagram'));
                            if (diagram == "bar")
                                chart = new google.visualization.BarChart(document.getElementById('showDiagram'));
                            chart.innerHTML = ""
                            chart.draw(data, options);
                        });
                    }
                }




                // alert(`${pertanyaanSelect.value} - ${id}`)
            })

            // 

        })

    }
    async function showEksport(id) {
        return alert('masih dalam pengembangan')
    }
    async function showPartisipan(id, pageUrl = null) {
        // console.log(e.target.options[e.target.selectedIndex])
        showTemplatePartisipan()
        let surveiId = id
        let url = "{{route('get.participant',':surveiId')}}"
        url = url.replace(':surveiId', surveiId)
        if (pageUrl != null)
            url = pageUrl
        let fetchData = await fetch(url)
        response = await fetchData.json()
        console.log(response);
        // return
        content.dataset.surveiUntuk = response.survei.survei_untuk

        if (response.status === true) {
            const tbody = document.querySelector('#show-data')
            tbody.innerHTML = ""
            const fragment = document.createDocumentFragment()
            if (response.survei.survei_untuk != "mitra") {
                if (response.details.data.length == 0) { //ini kalau datanya kosong
                    const tr = document.createElement('tr')
                    const td = document.createElement('td')
                    td.className = "text-center"
                    td.setAttribute('colspan', 4)
                    td.textContent = 'Tidak ada data'
                    tr.appendChild(td)
                    fragment.appendChild(tr)
                } else { //ini kalau ada datanya
                    let urut = response.details.from //ambil no urut
                    response.details.data.forEach(function(data, i) {
                        const tr = document.createElement('tr')
                        const tdNo = document.createElement('td')
                        tdNo.textContent = urut++
                        tdNo.className = 'text-center'
                        const tdTanggal = document.createElement('td')
                        tdTanggal.textContent = data.sesi_tanggal
                        const tdPengisian = document.createElement('td')
                        if (data.sesi_status == 1)
                            tdPengisian.innerHTML = '<span class="badge bg-success text-uppercase">Selesai</span>'
                        else
                            tdPengisian.innerHTML = '<span class="badge bg-warning text-uppercase">Progres</span>'
                        // tdPengisian.textContent = 'progress'
                        const tdAksi = document.createElement('td')
                        const link = document.createElement('a')
                        link.textContent = "Lihat Jawaban"
                        link.href = "#"
                        if (response.survei.survei_untuk == "mitra")
                            link.dataset.id = data.mitra_id
                        else
                            link.dataset.id = data.id

                        link.setAttribute('onclick', `detailJawaban(event,${surveiId})`)
                        link.dataset.bsToggle = "modal"
                        link.dataset.bsTarget = "#exampleModal"
                        // data-bs-toggle="modal" data-bs-target="#exampleModal"
                        tdAksi.appendChild(link)
                        tr.appendChild(tdNo)
                        tr.appendChild(tdTanggal)
                        tr.appendChild(tdPengisian)
                        tr.appendChild(tdAksi)
                        fragment.appendChild(tr)
                    })
                }
            } else {
                if (response.details.length == 0) { //ini kalau datanya kosong
                    const tr = document.createElement('tr')
                    const td = document.createElement('td')
                    td.className = "text-center"
                    td.setAttribute('colspan', 4)
                    td.textContent = 'Tidak ada data'
                    tr.appendChild(td)
                    fragment.appendChild(tr)
                } else { //ini kalau ada datanya
                    let urut = response.details.from //ambil no urut
                    response.details.data.forEach(function(data, i) {
                        const tr = document.createElement('tr')
                        const tdNo = document.createElement('td')
                        tdNo.textContent = urut++
                        tdNo.className = 'text-center'
                        const tdTanggal = document.createElement('td')
                        tdTanggal.textContent = data.sesi_tanggal
                        const tdPengisian = document.createElement('td')
                        if (data.sesi_status == 1)
                            tdPengisian.innerHTML = '<span class="badge bg-success text-uppercase">Selesai</span>'
                        else
                            tdPengisian.innerHTML = '<span class="badge bg-warning text-uppercase">Progres</span>'
                        // tdPengisian.textContent = 'progress'
                        const tdAksi = document.createElement('td')
                        const link = document.createElement('a')
                        link.textContent = "Lihat Jawaban"
                        link.href = "#"
                        if (response.survei.survei_untuk == "mitra")
                            link.dataset.id = data.id
                        else
                            link.dataset.id = data.id

                        link.setAttribute('onclick', `detailJawaban(event,${surveiId})`)
                        link.dataset.bsToggle = "modal"
                        link.dataset.bsTarget = "#exampleModal"
                        // data-bs-toggle="modal" data-bs-target="#exampleModal"
                        tdAksi.appendChild(link)
                        tr.appendChild(tdNo)
                        tr.appendChild(tdTanggal)
                        tr.appendChild(tdPengisian)
                        tr.appendChild(tdAksi)
                        fragment.appendChild(tr)
                    })
                }
            }
            tbody.appendChild(fragment)
            //ini untuk paginate
            var paginate = document.querySelector('#pagination')
            paginate.innerHTML = ""
            if (response.details.last_page > 1) {
                response.details.links.forEach(function(link) {
                    const li = document.createElement('li')
                    li.className = `page-item ${(link.active==true) ? 'active' : ''}`
                    const a = document.createElement('a')
                    let text = link.label
                    if (text.includes("Previous"))
                        text = "&laquo;"
                    else if (text.includes("Next"))
                        text = "&raquo;"
                    a.innerHTML = text
                    // a.className = ""
                    a.setAttribute('onclick', `showPartisipan(${id}, '${link.url}')`)
                    a.className = `page-link link`
                    li.appendChild(a)
                    paginate.appendChild(li)
                })
            }
        }
    }


    async function detailJawaban(e, surveiId) {
        let url = "{{route('get.detail.jawaban',[':surveiId',':userId'])}}"
        url = url.replace(':surveiId', surveiId)
        url = url.replace(':userId', e.target.dataset.id)
        let fetchData = await fetch(url)
        response = await fetchData.json()
        console.log(response);
        const tbody = document.querySelector('#show-jawaban')
        const fragment = document.createDocumentFragment()
        tbody.innerHTML = ""
        response.data.forEach(function(data, i) {
            const tr = document.createElement('tr')
            const tdNo = document.createElement('td')
            tdNo.textContent = i + 1
            tdNo.setAttribute('rowspan', data.pertanyaan.length + 1)
            const tdBagian = document.createElement('td')
            tdBagian.setAttribute('rowspan', data.pertanyaan.length + 1)
            tdBagian.textContent = `${data.bagian_kode} - ${data.bagian_nama}`
            tr.appendChild(tdNo)
            tr.appendChild(tdBagian)
            fragment.appendChild(tr)
            data.pertanyaan.forEach(function(tanya) {
                console.log(tanya);
                const trPertanyaan = document.createElement('tr')
                const tdPertanyaan = document.createElement('td')
                tdPertanyaan.setAttribute('colspan', 1)
                tdPertanyaan.textContent = tanya.pertanyaan
                const tdjawaban = document.createElement('td')
                if (response.survei.survei_untuk == "mitra") {
                    if (tanya.jawaban_mitra.length > 0) {
                        // tdjawaban.textContent = tanya.jawaban_mitra[0].jawaban
                        if (tanya.pertanyaan_jenis_jawaban == "Lebih Dari Satu Jawaban") {
                            let jawabans = ''
                            tanya.jawaban_mitra.forEach((jawab, index) => {
                                if (index == tanya.jawaban_mitra.length - 1)
                                    jawabans += `${jawab.jawaban}`
                                else
                                    jawabans += `${jawab.jawaban}, `
                            })
                            tdjawaban.textContent = jawabans
                        } else {
                            tdjawaban.textContent = tanya.jawaban_mitra[0].jawaban
                        }
                    } else {

                        tdjawaban.textContent = '-'
                    }
                } else {
                    if (tanya.jawaban.length > 0) {
                        console.log(tanya.jawaban.length);
                        if (tanya.pertanyaan_jenis_jawaban == "Lebih Dari Satu Jawaban") {
                            let jawabans = ''
                            tanya.jawaban.forEach((jawab, index) => {
                                if (index == tanya.jawaban.length - 1)
                                    jawabans += `${jawab.jawaban}`
                                else
                                    jawabans += `${jawab.jawaban}, `
                            })
                            tdjawaban.textContent = jawabans
                        } else {
                            tdjawaban.textContent = tanya.jawaban[0].jawaban
                        }
                    } else {

                        tdjawaban.textContent = '-'
                    }

                }
                trPertanyaan.appendChild(tdPertanyaan)
                trPertanyaan.appendChild(tdjawaban)
                fragment.appendChild(trPertanyaan)
            })
        })
        tbody.appendChild(fragment)

    }
</script>
@endsection
@extends('template')

@section('content')
<!-- Text Content Start -->
<section class="scroll-section" id="textContent">
    <div class="card mb-5">
        <div class="card-body">

            <h4>Pilih Survei</h4>
            <div class="row">
                <div class="col-md-12">
                    <select class="form-select" id="survei">
                        @foreach($data as $item)
                        <option value="{{$item->id}}">{{$item->survei_nama}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Text Content End -->
<section class="scroll-section" id="formRow">

    <div class="card mb-5">
        <div class="card-body">
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

        </div>
    </div>
</section>
<!-- Form Row End -->

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
@endsection
@section('js')
<script>
    document.querySelector("#survei").addEventListener("change", async function(e) {
        // return console.log(e.target.options[e.target.selectedIndex])
        let surveiId = e.target.options[e.target.selectedIndex].value
        let url = "{{route('get.participant',':surveiId')}}"
        url = url.replace(':surveiId', surveiId)
        let fetchData = await fetch(url)
        response = await fetchData.json()
        console.log(response);
        if (response.status === true) {
            const tbody = document.querySelector('#show-data')
            tbody.innerHTML = ""
            const fragment = document.createDocumentFragment()
            if (response.details.length == 0) {
                const tr = document.createElement('tr')
                const td = document.createElement('td')
                td.className = "text-center"
                td.setAttribute('colspan', 4)
                td.textContent = 'Tidak ada data'
                tr.appendChild(td)
                fragment.appendChild(tr)
            } else {
                response.details.forEach(function(data, i) {
                    const tr = document.createElement('tr')
                    const tdNo = document.createElement('td')
                    tdNo.textContent = i + 1
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

                    link.setAttribute('onclick', `getDetail(event,${surveiId})`)
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
            tbody.appendChild(fragment)
        }
    })

    async function getDetail(e, surveiId) {
        // get.detail.jawaban 
        // alert(`${e.target.dataset.id} - ${surveiId}`)
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
                const trPertanyaan = document.createElement('tr')
                const tdPertanyaan = document.createElement('td')
                tdPertanyaan.setAttribute('colspan', 1)
                tdPertanyaan.textContent = tanya.pertanyaan
                const tdjawaban = document.createElement('td')
                if (response.survei.survei_untuk == "mitra") {
                    if (tanya.jawaban_mitra.length > 0)
                        tdjawaban.textContent = tanya.jawaban_mitra[0].jawaban
                    else
                        tdjawaban.textContent = '-'
                } else {
                    if (tanya.jawaban.length > 0)
                        tdjawaban.textContent = tanya.jawaban[0].jawaban
                    else
                        tdjawaban.textContent = '-'

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
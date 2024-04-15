@extends('template')

@section('content')

<!-- Form Row Start -->
<!-- Text Content Start -->
<section class="scroll-section" id="textContent">
    <div class="card mb-5">
        <div class="card-body d-flex flex-column">
            <h3 class="card-title mb-4">Informasi Step</h3>
            <ul>
                <li>Kode Step : {{$bagianData->bagian_kode}}</li>
                <li>Nama Step : {{$bagianData->bagian_nama}}</li>
                <li>Pertanyaan : {{$bagianData->pertanyaan[0]->pertanyaan}}</li>
            </ul>
            <div class="col-2">
                <a href="{{route('admin.pertanyaan.data',[$bagianData->survei_id,$bagianData->id])}}" class="btn btn-dark btn-sm mb-2">
                    <i data-cs-icon="arrow-left" class="icon" data-cs-size="10"></i>
                    <span class="label">Kembali</span>
                </a>
            </div>
        </div>
    </div>
</section>
<!-- Text Content End -->
<section class="scroll-section" id="formRow">
    <div class="card mb-5">
        <div class="card-body">

            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">List Jawaban</th>
                        <th scope="col">Redirect</th>
                        <!-- <th scope="col">Aksi</th> -->
                    </tr>
                </thead>
                <tbody>
                    @foreach ($bagianData->pertanyaan[0]->pilihanJawaban as $index => $item)
                    <tr>
                        <th scope="row">{{$index+1}}</th>
                        <td>{{$item->pilihan_jawaban}}</td>
                        <td>
                            <!-- <a href="#"></a> -->
                            <!-- Button Trigger -->
                            @if($item->directJawaban==null)
                            <a type="button" onclick="choose(event)" href="#" data-id="{{$item->id}}" data-bs-toggle="modal" data-bs-target="#exampleModal">Tentukan</a>
                            @else
                            <a type="button" onclick="choose(event)" href="#" data-id="{{$item->id}}" data-bs-toggle="modal" data-bs-target="#exampleModal">{{$item->directJawaban->bagian->bagian_kode}} - {{$item->directJawaban->bagian->bagian_nama}}</a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>


        </div>
    </div>
</section>
<!-- Form Row End -->

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabelDefault" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <select class="form-select mb-3" id="step" required>
                    <option value="">Pilih Bagian</option>
                    <option value="-">Kosongkan</option>
                    @foreach ($bagianList as $bagian)
                    <option value="{{$bagian->id}}">{{$bagian->bagian_kode}} - {{$bagian->bagian_nama}}</option>
                    @endforeach
                </select>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal" id="delete">Hapus Redirect</button>
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal" id="save">Tentukan Redirect</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    let element

    function choose(event) {
        element = event.target
    }

    document.querySelector("#delete").addEventListener('click', async function() {
        let dataSend = new FormData()
        dataSend.append('pilihan_jawaban_id', element.dataset.id)
        response = await fetch('{{route("admin.delete.jawaban.redirect")}}', {
            method: "POST",
            body: dataSend
        })
        responseMessage = await response.json()
        if (responseMessage.status == "sukses") {
            alert('Redirect Sukses dihapus')
            element.innerText = "Tentukan"
        } else {
            alert('Ada Kesalahan atau Redirect Tidak Ada')
        }
    });
    document.querySelector("#save").addEventListener('click', async function() {
        let dataSend = new FormData()
        let step = document.querySelector("#step")
        // return alert(element.dataset.id)
        if (step.options[step.selectedIndex].value == "")
            return alert('Redirect tidak boleh kosong')
        dataSend.append('pilihan_jawaban_id', element.dataset.id)
        dataSend.append('bagian_id', step.options[step.selectedIndex].value)
        response = await fetch('{{route("admin.store.jawaban.redirect")}}', {
            method: "POST",
            body: dataSend
        })
        responseMessage = await response.json()
        if (responseMessage.status == "sukses") {
            alert('sukses')
            element.innerText = step.options[step.selectedIndex].innerText

        }
        console.log(responseMessage);
    });
</script>
@endsection
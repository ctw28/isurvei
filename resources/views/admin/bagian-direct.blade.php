@extends('template')

@section('content')

<!-- Form Row Start -->
<section class="scroll-section" id="formRow">
    <!-- <h2 class="small-title">Daftar Pertanyaan</h2> -->

    <div class="card mb-5">
        <div class="card-body">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Kode</th>
                        <th scope="col">Deskripsi</th>
                        <th scope="col">Direct Kembali</th>
                        <th scope="col">Direct Selanjutnya</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($bagianData as $index => $item)
                    <tr>
                        <th scope="row">{{$index+1}}</th>
                        <td>{{$item->bagian_kode}}</td>
                        <td>{{$item->bagian_nama}}</td>


                        <td>
                            @if($item->bagianDirect==null)
                            <a type="button" onclick="choose(event)" href="#" data-direct="kembali" data-id="{{$item->id}}" data-bs-toggle="modal" data-bs-target="#exampleModal">Tentukan</a>
                            @else
                            @if($item->bagianDirect->directBack!=null)
                            <a type="button" onclick="choose(event)" href="#" data-direct="kembali" data-id="{{$item->id}}" data-bs-toggle="modal" data-bs-target="#exampleModal">{{$item->bagianDirect->directBack->bagian_kode}} - {{$item->bagianDirect->directBack->bagian_nama}}</a>
                            @else
                            <a type="button" onclick="choose(event)" href="#" data-direct="kembali" data-id="{{$item->id}}" data-bs-toggle="modal" data-bs-target="#exampleModal">-</a>
                            @endif
                            @endif
                        </td>
                        <td>
                            @if($item->bagianDirect==null)
                            <a type="button" onclick="choose(event)" href="#" data-direct="selanjutnya" data-id="{{$item->id}}" data-bs-toggle="modal" data-bs-target="#exampleModal">Tentukan</a>
                            @else
                            @if($item->bagianDirect->direct!=null)
                            <a type="button" onclick="choose(event)" href="#" data-direct="selanjutnya" data-id="{{$item->id}}" data-bs-toggle="modal" data-bs-target="#exampleModal">{{$item->bagianDirect->direct->bagian_kode}} - {{$item->bagianDirect->direct->bagian_nama}}</a>
                            @else
                            <a type="button" onclick="choose(event)" href="#" data-direct="selanjutnya" data-id="{{$item->id}}" data-bs-toggle="modal" data-bs-target="#exampleModal">-</a>
                            @endif
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
                <h2 class="small-title">Tentukan Direct Bagian</h2>
                <div class="col-sm-12">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="is_direct_by_jawaban" value="1">
                        <label class="form-check-label" for="is_direct_by_jawaban">Direct Berdasarkan Jawaban</label>
                    </div>
                </div>
                <select class="form-select mb-3" id="bagian" required>
                    <option value="">Pilih Bagian</option>
                    @foreach ($bagianList as $bagian)
                    <option value="{{$bagian->id}}">{{$bagian->bagian_kode}} - {{$bagian->bagian_nama}}</option>
                    @endforeach
                </select>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal" id="save">Tentukan Direct</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    let element
    let directByJawabanValue = 0
    let isDirectByJawaban = document.querySelector("#is_direct_by_jawaban")
    let save = document.querySelector("#save")

    function choose(event) {
        element = event.target
        console.log(event.target.dataset.direct);
    }

    isDirectByJawaban.addEventListener('click', function() {
        if (isDirectByJawaban.checked) {
            document.querySelector("#bagian").options[0].selected = true;
            document.querySelector("#bagian").setAttribute("disabled", "disabled");
            directByJawabanValue = "1"

        } else {
            document.querySelector("#bagian").removeAttribute('disabled')
            directByJawabanValue = "0"
        }
    })
    save.addEventListener('click', async function() {
        let dataSend = new FormData()
        let bagian = document.querySelector("#bagian")
        dataSend.append('jenis', element.dataset.direct)
        dataSend.append('bagian_id', element.dataset.id)
        dataSend.append('bagian_id_direct', bagian.options[bagian.selectedIndex].value)
        dataSend.append('is_direct_by_jawaban', directByJawabanValue)
        response = await fetch('{{route("admin.bagian.direct.store",$data->id)}}', {
            method: "POST",
            body: dataSend
        })
        responseMessage = await response.json()
        if (responseMessage.status == "sukses") {
            alert("berhasil ubah data")
            if (directByJawabanValue == 1)
                element.innerText = "-"
            else
                element.innerText = bagian.options[bagian.selectedIndex].innerText
        }
        console.log(responseMessage);
    });
</script>
@endsection
@extends('template')

@section('content')

<!-- Form Row Start -->
<!-- Text Content Start -->
<section class="scroll-section" id="textContent">
    <div class="card mb-5">
        <div class="card-body d-flex flex-column">
            <h3 class="card-title mb-4">Bagian / Section</h3>
            <ul>
                <li>Kode bagian : {{$bagian->bagian_kode}}</li>
                <li>Nama bagian : {{$bagian->bagian_nama}}</li>
            </ul>
        </div>
    </div>
</section>
<!-- Text Content End -->
<section class="scroll-section" id="formRow">
    <!-- <h2 class="small-title">Daftar Pertanyaan</h2> -->
    <div class="col-auto d-flex mb-2">
        <a href="{{route('admin.pertanyaan.add',[$data->id,$bagian->id])}}" class="btn btn-primary btn-icon btn-icon-start ms-1">
            <i data-cs-icon="plus"></i>
            <span>Tambah Pertanyaan</span>
        </a>
        <button class="btn btn-danger btn-icon btn-icon-start ms-1 mx-2" data-bs-toggle="modal" data-bs-target="#lExample">
            <i data-cs-icon="plus"></i>
            <span>Copy Pertanyaan</span>
        </button>
    </div>
    <div class="card mb-5">
        <div class="card-body">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Pertanyaan</th>
                        <th scope="col">Urutan</th>
                        <th scope="col">Jenis Jawaban</th>
                        <th scope="col">Required</th>
                        <th scope="col">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($bagian->pertanyaan as $index => $item)
                    <tr>
                        <th scope="row">{{$index+1}}</th>
                        <td>{{$item->pertanyaan}}</td>
                        <td>{{$item->pertanyaan_urutan}}</td>
                        <td>{{$item->pertanyaan_jenis_jawaban}}</td>
                        <td>
                            @if($item->required=="1")
                            Ya
                            @else
                            Tidak
                            @endif
                        </td>
                        <td>
                            <a href="{{route('admin.set.jawaban.redirect',[$data->id,$bagian->id,$item->id])}}" class="btn btn-light btn-sm">Kelola</a>
                            <a href="{{route('admin.pertanyaan.edit',[$bagian->id,$item->id])}}" class="btn btn-warning btn-sm">Ubah</a>
                            <a href="{{route('admin.pertanyaan.delete',[$data->id, $bagian->id,$item->id])}}" class="btn btn-danger btn-sm" onclick="return confirm('Yakin Hapus?')">Hapus</a>
                        </td>

                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>
<!-- Form Row End -->
<div class="modal fade" id="lExample" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Copy Pertanyaan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="col-12">
                    <select onchange="show()" class="form-select mb-3" id="bagian">
                        <option>Pilih Bagian</option>
                        @foreach($data->bagian as $item)
                        <option value="{{$item->id}}">{{$item->bagian_kode}}. {{$item->bagian_nama}}</option>
                        @if($item->bagianParent!=null)
                        @foreach ($item->bagianParent as $child)
                        <option value="{{$child->id}}">&nbsp;&nbsp;&nbsp;- {{$child->bagian_kode}}. {{$child->bagian_nama}}</option>
                        @endforeach
                        @endif
                        @endforeach
                    </select>

                </div>
                <div class="col-12 mt-2" id="showPertanyaan">

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    async function show() {
        let bagian = document.getElementById('bagian')
        // return alert(bagian.value);
        let url = "{{route('get.pertanyaan.bagian',':id')}}"
        url = url.replace(':id', bagian.value)
        let sendRequest = await fetch(url)
        let response = await sendRequest.json()
        console.log(response);
        let contents = '<h4>Daftar Pertanyaan</h4>'
        contents += '<ul>'
        response.map((data) => {
            contents += `<li>${data.pertanyaan}</li>`
        })
        contents += '</ul>'
        contents += `<div class="mt 2">
            <button class="btn btn-danger" id="copy"> Copy Pertanyaan</button>
        </div>`;

        document.querySelector('#showPertanyaan').innerHTML = ''
        document.querySelector('#showPertanyaan').innerHTML = contents

        document.querySelector('#copy').addEventListener('click', async function() {

            let url = "{{route('copy.pertanyaan',[':id',':idCopy'])}}"
            url = url.replace(':id', bagian.value)
            url = url.replace(':idCopy', "{{$bagian->id}}")
            let sendRequest = await fetch(url)
            let response = await sendRequest.json()
            console.log(response);
            if (response.status == true) {
                alert('pertanyaan tercopy')
                window.location.reload();
            }

        })
    }
</script>
@endsection
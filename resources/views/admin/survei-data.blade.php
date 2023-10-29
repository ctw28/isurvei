@extends('template')

@section('content')

<!-- Form Row Start -->
<section class="scroll-section" id="formRow">
    <div class="col-auto d-flex mb-2">
        <a href="{{route('admin.survei.add')}}" class="btn btn-primary btn-icon btn-icon-start ms-1">
            <i data-cs-icon="plus"></i>
            <span>Tambah Survei</span>
        </a>
    </div>
    <div class="card mb-5">
        <div class="card-body">
            <div style="overflow-x:auto;">

                <table class="table table-striped table-hover">
                    <thead>
                        <tr class="text-center">
                            <th scope="col" width="3%">No</th>
                            <th scope="col" width="20%">Dibuat Oleh</th>
                            <th scope="col" width="30%">Nama Survei</th>
                            <th scope="col" width="10%">Untuk</th>
                            <th scope="col" width="10%">Wajib</th>
                            <th scope="col" width="5%">Publish</th>
                            <th scope="col" width="5%">Selesai</th>
                            <th scope="col" width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $index => $item)
                        <tr>
                            <td class="text-center">{{$index+1}}</td>
                            <td>{{$item->user->userPegawai->pegawai->dataDiri->nama_lengkap}} ({{(session('session_role')->role_aktif->detail->role_aplikasi_nama)}})</td>
                            <td>{{$item->survei_nama}}</td>
                            <td class="text-center">{{$item->survei_untuk}}</td>
                            <td class="text-center">
                                {{($item->is_wajib) ? 'Ya' : '-'}}
                            </td>
                            <td class="text-center">
                                <div class="form-check form-switch">
                                    <input onclick="update('is_aktif',event)" data-id="{{$item->id}}" class="form-check-input" type="checkbox" id="is_aktif" name="is_aktif" value="1" {{($item->is_aktif) ? 'checked' : ''}}>
                                </div>
                            </td>
                            <td>
                                <div class="form-check form-switch">
                                    <input onclick="update('survei_status',event)" data-id="{{$item->id}}" class="form-check-input" type="checkbox" id="survei_status" name="survei_status" value="1" {{($item->survei_status) ? 'checked' : ''}}>
                                </div>
                            </td>
                            <td>
                                <a href="{{route('admin.bagian.data',$item->id)}}" class="btn btn-info btn-sm">Bagian</a>
                                @if($item->survei_untuk=="mitra")
                                <button onclick="setLInk('{{$item->decrypt_id}}')" data-bs-toggle="modal" data-bs-target="#exampleModal" class="btn btn-warning btn-sm">Link</button>
                                @endif
                                <button class="btn btn-icon btn-icon-only btn-sm btn-background shadow" type="button" data-bs-toggle="dropdown" aria-expanded="false" aria-haspopup="true">
                                    <i data-cs-icon="more-horizontal" data-acorn-size="15"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-sm dropdown-menu-end shadow">
                                    <a class="dropdown-item" href="{{route('admin.survei.edit',$item->id)}}">Ubah</a>
                                    <a class="dropdown-item" href="{{route('admin.survei.delete',$item->id)}}" onclick="return confirm('Yakin Hapus')">Hapus</a>
                                </div>
                            </td>
                        </tr>

                        @endforeach
                    </tbody>
                </table>
            </div>
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
                        <h2>Copy Link</h2>
                        <input type="text" id="link" value="" class="form-control">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    async function update(column, e) {
        // return alert(e.target.dataset.id)
        let konfirmasi = confirm('yakin?')
        if (konfirmasi) {
            let dataSend = new FormData()
            let url = '{{route("api.survei.update",":id")}}'
            url = url.replace(':id', e.target.dataset.id)
            dataSend.append('column', column)
            dataSend.append('value', e.target.checked)
            response = await fetch(url, {
                method: "POST",
                body: dataSend
            })
            responseMessage = await response.json()
            // return console.log(responseMessage)
            if (responseMessage.status == "sukses") {
                alert(responseMessage.message)
                // element.innerText = "Tentukan"
            } else {
                alert('Ada Kesalahan')
            }
        }
    }

    function setLInk(id) {
        let url = "{{route('mitra.registrasi',':id')}}"
        url = url.replace(':id', id)
        document.getElementById("link").value = url
    }

    function copyLink() {
        // return alert('gg')
        var copyText = document.getElementById("link");

        // Select the text field
        copyText.select();
        copyText.setSelectionRange(0, 99999); // For mobile devices

        // Copy the text inside the text field
        navigator.clipboard.writeText(copyText.value);

        // Alert the copied text
        alert("Link dicopy");
    }
</script>
@endsection
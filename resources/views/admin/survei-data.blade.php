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
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="home-tab" onclick="show('mahasiswa')" data-bs-toggle="tab" data-bs-target="#survei-mahasiswa" type="button" role="tab" aria-controls="home" aria-selected="true">Mahasiswa</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="profile-tab" onclick="show('dosen')" data-bs-toggle="tab" data-bs-target="#survei-dosen" type="button" role="tab" aria-controls="profile" aria-selected="false">Dosen</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="contact-tab" onclick="show('pegawai')" data-bs-toggle="tab" data-bs-target="#survei-pegawai" type="button" role="tab" aria-controls="contact" aria-selected="false">Tendik</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="contact-tab" onclick="show('mitra')" data-bs-toggle="tab" data-bs-target="#survei-mitra" type="button" role="tab" aria-controls="contact" aria-selected="false">Mitra</button>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="survei-mahasiswa" role="tabpanel" aria-labelledby="home-tab">

                </div>
                <div class="tab-pane fade" id="survei-dosen" role="tabpanel" aria-labelledby="profile-tab"></div>
                <div class="tab-pane fade" id="survei-pegawai" role="tabpanel" aria-labelledby="contact-tab"></div>
                <div class="tab-pane fade" id="survei-mitra" role="tabpanel" aria-labelledby="contact-tab"></div>
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
    show('mahasiswa')
    // console.log("{{$organisasiId}}");
    async function show(untuk) {
        // console.log(untuk);
        let url = "{{route('survei.untuk',[':organisasi',':untuk'])}}"
        url = url.replace(":organisasi", "{{$organisasiId}}")
        url = url.replace(":untuk", untuk)
        let request = await fetch(url)
        response = await request.json()
        console.log(response);
        const surveiDataShow = document.querySelector(`#survei-${untuk}`)

        contents = `
        <div style="overflow-x:auto;">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr class="text-center">
                            <th scope="col" width="3%">No</th>
                            <th scope="col" width="30%">Nama Survei</th>
                            <th scope="col" width="10%">Untuk</th> 
        `
        if (untuk == "mahasiswa" || untuk == "dosen")
            contents += `<th scope="col" width="10%">Wajib SIA</th>
                            <th scope="col" width="10%">Multiple</th>
                `
        if (untuk == "pegawai")
            contents += `<th scope="col" width="10%">Wajib SIMPEG</th>
                            <th scope="col" width="10%">Multiple</th>
                `
        contents += `
                                <th scope="col" width="5%">Publish</th>
                            <th scope="col" width="5%">Tutup</th>
                            <th scope="col" width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>       
        `
        response.data.map((data, index) => {
            let urlBagian = "{{route('admin.bagian.data',':id')}}"
            urlBagian = urlBagian.replace(":id", data.id)
            let urlEdit = "{{route('admin.survei.edit',':id')}}"
            urlEdit = urlEdit.replace(":id", data.id)
            let urlHapus = "{{route('admin.survei.delete',':id')}}"
            urlHapus = urlHapus.replace(":id", data.id)
            contents += `
                        <tr>
                            <td class="text-center">${index+1}</td>
                            <td>${data.survei_nama}</td>
                            <td class="text-center">${data.survei_untuk}</td>`
            if (untuk != "mitra")

                contents += `
                            <td class="text-center">
                                ${(data.is_sia)?'Ya':''}
                            </td>
                            <td class="text-center">
                                ${(data.is_multiple) ? 'Ya' : ''}
                            </td>`
            contents += `
                            <td class="text-center">
                                <div class="form-check form-switch">
                                    <input onclick="update('is_aktif',event)" data-id="${data.id}" class="form-check-input" type="checkbox" id="is_aktif" name="is_aktif" value="1" ${(data.is_aktif)?'checked':''}>
                                </div>
                            </td>
                            <td>
                                <div class="form-check form-switch">
                                    <input onclick="update('survei_status',event)" data-id="${data.id}" class="form-check-input" type="checkbox" id="survei_status" name="survei_status" value="1" ${(data.survei_status)?'checked':''}>
                                </div>
                            </td>
                            <td>
                                <a href="${urlBagian}" class="btn btn-info btn-sm">Bagian</a>     
            `

            if (data.survei_untuk == "mitra") {
                contents += `
                                <button onclick="setLInk('${data.decrypt_id}')" data-bs-toggle="modal" data-bs-target="#exampleModal" class="btn btn-warning btn-sm">Link</button>
                            `
            }
            contents += `
                                <button onclick="linkCoba('${data.id_encrypt}','${data.bagian_awal_akhir.bagian_id_first_encrypt}')" data-bs-toggle="modal" data-bs-target="#exampleModal" class="btn btn-success btn-sm">Preview</button>
                                <button class="btn btn-icon btn-icon-only btn-sm btn-background shadow" type="button" data-bs-toggle="dropdown" aria-expanded="false" aria-haspopup="true">
                                    <i data-cs-icon="more-horizontal" data-acorn-size="15"></i>. . .
                                </button>
                                <div class="dropdown-menu dropdown-menu-sm dropdown-menu-end shadow">
                                    <a class="dropdown-item" href="${urlEdit}">Ubah</a>
                                    <a class="dropdown-item" href="${urlHapus}" onclick="return confirm('Yakin Hapus')">Hapus</a>
                                    <a class="dropdown-item" onclick="duplikat('${data.id}')">Duplikat</a>
                                </div>
                            </td>
                        </tr>           
        `
        })
        contents += `
                    </tbody>
                </table>
            </div>`
        surveiDataShow.innerHTML = ''
        surveiDataShow.innerHTML = contents


    }
    async function duplikat(id) {
        let konfirm = confirm("Yakin Copy? semua bagian dan pertanyaan akan ikut tercopy. anda bisa edit setelahnya")
        if (!konfirm)
            return
        let url = "{{route('survei.copy',':id')}}"
        url = url.replace(":id", id)
        let response = await fetch(url)
        responseMessage = await response.json()
        // log re
        // return console.log(responseMessage)
        if (responseMessage.status) {
            alert("Survei Berhasil dicopy")
            window.location.reload();

        }
    }

    async function update(column, e) {
        // return alert(e.target.dataset.id)
        e.preventDefault();

        let konfirmasi
        if (column == "is_aktif")
            konfirmasi = confirm('yakin publish? anda tidak dapat mengubah survei lagi jika sudah dipublish')
        else
            konfirmasi = confirm('yakin selesai? survei tidak akan ditampilkan lagi')
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
                if (!e.checked) {
                    e.target.checked = true;
                    if (column == "is_aktif")
                        e.target.setAttribute('disabled', 'disabled')
                }
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

    function linkCoba(surveiId, bagianId) {
        let url = "{{route('user.show.pertanyaan-coba',[':surveiId',':bagianId'])}}"
        url = url.replace(':surveiId', surveiId)
        url = url.replace(':bagianId', bagianId)
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
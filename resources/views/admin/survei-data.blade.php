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
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Nama Survei</th>
                        <th scope="col">Untuk</th>
                        <th scope="col">Wajib</th>
                        <th scope="col">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $index => $item)
                    <tr>
                        <th scope="row">{{$index+1}}</th>
                        <td>{{$item->survei_nama}}</td>
                        <td>{{$item->survei_untuk}}</td>
                        <td>
                            {{($item->harus_diisi) ? 'Ya' : 'Tidak'}}
                        </td>
                        <td>
                            <a href="{{route('admin.bagian.data',$item->id)}}" class="btn btn-info btn-sm">Bagian</a>
                            @if($item->survei_untuk=="mitra")
                            <button onclick="setLInk({{$item->id}})" data-bs-toggle="modal" data-bs-target="#exampleModal" class="btn btn-warning btn-sm">Link</button>
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
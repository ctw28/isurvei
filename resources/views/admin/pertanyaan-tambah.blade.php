@extends('template')

@section('content')

<!-- Form Row Start -->
<!-- Text Content Start -->
<section class="scroll-section" id="textContent">
    <div class="card mb-5">
        <div class="card-body d-flex flex-column">
            <h3 class="card-title mb-4">Informasi bagian</h3>
            <ul>
                <li>Kode bagian : {{$bagian->bagian_kode}}</li>
                <li>Nama bagian : {{$bagian->bagian_nama}}</li>
            </ul>
        </div>
    </div>
</section>
<!-- Text Content End -->
<section class="scroll-section" id="formRow">
    <div class="card mb-5">
        <div class="card-body">
            <form action="{{route('admin.pertanyaan.store',[$data->id,$bagian->id])}}" method="post" enctype="multipart/form" class="row g-3">
                @csrf
                <input type="hidden" name="bagian_id" value="{{$bagian->id}}" required />
                <div class="col-md-1">
                    <label for="pertanyaan_urutan" class="form-label">Urutan</label>
                    <input type="number" class="form-control" id="pertanyaan_urutan" name="pertanyaan_urutan" placeholder="Urutan" required />
                </div>
                <div class="col-md-11">
                    <label for="pertanyaan" class="form-label">Pertanyaan</label>
                    <textarea class="form-control" placeholder="Tuliskan Pertanyaan" name="pertanyaan" id="pertanyaan" rows="3" required></textarea>
                    <input type="hidden" name="bagian_id" value="{{$bagian->id}}" required />
                </div>

                <div class="col-md-12">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="isRequired" id="isRequired" value="1">
                        <label class="form-check-label" for="isRequired">Harus diisi</label>
                    </div>
                </div>
                <div class="col-md-12">
                    <label for="inputState" class="form-label">Jenis Jawaban</label>
                    <select class="form-select" name="pertanyaan_jenis_jawaban" id="jenis_jawaban">
                        <option value="">Pilih Jenis Jawaban</option>
                        <option value="Text">Text</option>
                        <option value="Text Panjang">Text Panjang</option>
                        <option value="Pilihan">Pilihan</option>
                        <option value="Lebih Dari Satu Jawaban">Lebih Dari Satu Jawaban</option>
                        <option value="Select">Select</option>
                    </select>
                </div>
                <div id="jawabanButton"></div>
                <div id="jawabanContainer">
                    <div id="jawaban">

                    </div>
                    <div id="lainnya">
                    </div>


                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary" style="float: right">Tambah Pertanyaan</button>
                </div>
            </form>
        </div>
    </div>
</section>
<!-- Form Row End -->

<template id="lainnyaTemplate">
    <div class="col-md-12">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="addLainnya" id="addLainnya" value="1">
            <label class="form-check-label" for="addLainnya">Tambahkan Pilihan Lainnya</label>
        </div>
    </div>
</template>
<template id="selectTemplate">
    <div class="col-md-12 mb-3">
        <label for="pertanyaan_urutan" class="form-label">Pilihan</label>
        <input type="text" class="form-control" name="jawaban[]" placeholder="Tuliskan Jawaban" required />
    </div>
</template>

<template id="textTemplate">
    <div class="form-check">
        <input required class="form-check-input" type="radio" name="text_jenis" id="text-biasa" value="text-biasa">
        <label class="form-check-label" for="text-biasa">Text Biasa</label>
    </div>
    <div class="form-check">
        <input required class="form-check-input" type="radio" name="text_jenis" id="text-angka" value="text-angka">
        <label class="form-check-label" for="text-angka">Angka</label>
    </div>
    <div class="form-check">
        <input required class="form-check-input" type="radio" name="text_jenis" id="text-email" value="text-email">
        <label class="form-check-label" for="text-email">Email</label>
    </div>
    <div class="form-check">
        <input required class="form-check-input" type="radio" name="text_jenis" id="text-desimal" value="text-desimal">
        <label class="form-check-label" for="text-desimal">Desimal</label>
    </div>
    <div class="form-check">
        <input required class="form-check-input" type="radio" name="text_jenis" id="text-tanggal" value="text-tanggal">
        <label class="form-check-label" for="text-tanggal">Tanggal</label>
    </div>
</template>
<template id="buttonTemplate">
    <div class="d-flex mt-3">
        <h5 style="margin-right:10px">Tentukan Pilihan Jawaban</h5>
        <button type="button" id="addPilihan" class="ml-3 btn btn-sm btn-dark">+</button>
    </div>
</template>
@endsection

@section('js')
<script>
    function addElement() {
        const template = document.querySelector("#selectTemplate")
        const element = template.content.cloneNode(true);
        const length = pilihanJawaban.getElementsByTagName('input').length;
        element.querySelector('label').innerText = `Jawaban ${length+1}`
        element.querySelector('div').id = `pil_${length+1}`
        pilihanJawaban.appendChild(element)
    }

    const jenisJawaban = document.querySelector('#jenis_jawaban')
    const pilihanJawaban = document.querySelector('#jawaban');
    const pilihanJawabanButton = document.querySelector('#jawabanButton');
    const lainnyaContainer = document.querySelector("#lainnya")

    jenisJawaban.addEventListener('change', function() {
        pilihanJawaban.innerHTML = ""
        pilihanJawabanButton.innerHTML = ""
        lainnyaContainer.innerHTML = ""
        let jawabanValue = jenisJawaban.value
        if (jawabanValue == "Text") {
            const textTemplate = document.querySelector("#textTemplate")
            const text = textTemplate.content.cloneNode(true);
            pilihanJawaban.appendChild(text)

        } else if (jawabanValue != "Text" && jawabanValue != "Text Panjang") {
            addElement()
            const templateButton = document.querySelector("#buttonTemplate")
            const button = templateButton.content.cloneNode(true);
            pilihanJawabanButton.appendChild(button)
            // if (jawabanValue != "Select") {
            // if (jawabanValue != "Select" && jawabanValue != "Lebih Dari Satu Jawaban") {

            const lainnyaTemplate = document.querySelector("#lainnyaTemplate")
            const lainnya = lainnyaTemplate.content.cloneNode(true);
            lainnyaContainer.appendChild(lainnya)
            // }
            const add = document.querySelector("#addPilihan")
            add.addEventListener('click', function() {
                addElement()
            })
        }
    })
</script>
@endsection
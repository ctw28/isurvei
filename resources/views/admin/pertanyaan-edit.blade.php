@extends('template')

@section('content')

<!-- Form Row Start -->
<!-- Text Content Start -->
<section class="scroll-section" id="textContent">
    <div class="card mb-5">
        <div class="card-body d-flex flex-column">
            <h3 class="card-title mb-4">Informasi Bagian</h3>
            <ul>
                <li>Kode Step : {{$stepData->bagian_kode}}</li>
                <li>Nama Step : {{$stepData->bagian_nama}}</li>
            </ul>
        </div>
    </div>
</section>
<!-- Text Content End -->
<section class="scroll-section" id="formRow">
    <div class="card mb-5">
        <div class="card-body">
            <form action="{{route('admin.pertanyaan.update',[$stepData->survei_id,$stepData->id,$stepData->pertanyaan[0]->id])}}" method="post" enctype="multipart/form" class="row g-3">
                @csrf
                <input type="hidden" name="step_id" value="{{$stepData->id}}" required />

                <div class="col-md-1">
                    <label for="pertanyaan_urutan" class="form-label">Urutan</label>
                    <input type="number" class="form-control" id="pertanyaan_urutan" name="pertanyaan_urutan" placeholder="Urutan" value="{{$stepData->pertanyaan[0]->pertanyaan_urutan}}" required />
                </div>
                <div class="col-md-11">
                    <label for="pertanyaan" class="form-label">Pertanyaan</label>
                    <textarea class="form-control" placeholder="Tuliskan Pertanyaan" name="pertanyaan" id="pertanyaan" rows="3" required>{{$stepData->pertanyaan[0]->pertanyaan}}</textarea>
                </div>
                <div class="col-md-12">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="isRequired" value="1" @if($stepData->pertanyaan[0]->required=="1") checked @endif
                        >
                        <label class="form-check-label">Harus diisi</label>
                    </div>
                </div>
                <div class="col-md-12">
                    <label for="inputState" class="form-label">Jenis Jawaban</label>
                    <select class="form-select" name="pertanyaan_jenis_jawaban" id="jenis_jawaban">
                        <option value="">Pilih Jenis Jawaban</option>
                        <option value="Text" @if($stepData->pertanyaan[0]->pertanyaan_jenis_jawaban=="Text") selected @endif>Text</option>
                        <option value="Text Panjang" @if($stepData->pertanyaan[0]->pertanyaan_jenis_jawaban=="Text Panjang") selected @endif>Text Panjang</option>
                        <option value="Pilihan" @if($stepData->pertanyaan[0]->pertanyaan_jenis_jawaban=="Pilihan") selected @endif>Pilihan</option>
                        <option value="Lebih Dari Satu Jawaban" @if($stepData->pertanyaan[0]->pertanyaan_jenis_jawaban=="Lebih Dari Satu Jawaban") selected @endif>Lebih Dari Satu Jawaban</option>
                        <option value="Select" @if($stepData->pertanyaan[0]->pertanyaan_jenis_jawaban=="Select") selected @endif>Select</option>
                    </select>
                </div>
                <div id="jawabanButton">
                    @if($stepData->pertanyaan[0]->pertanyaan_jenis_jawaban!=="Text" AND $stepData->pertanyaan[0]->pertanyaan_jenis_jawaban!="Text Panjang")
                    <div class="d-flex mt-3">
                        <h5 style="margin-right:10px">Pilihan Jawaban</h5>
                        <button type="button" id="addPilihan" class="ml-3 btn btn-sm btn-dark">+</button>
                    </div>
                    @endif
                </div>
                <div id="jawabanContainer">
                    <div id="jawaban">
                        @if($stepData->pertanyaan[0]->pertanyaan_jenis_jawaban=="Text")

                        <div class="form-check">
                            <input required class="form-check-input" type="radio" name="text_jenis" id="text-biasa" value="text-biasa" @if($stepData->pertanyaan[0]->textProperties!=null)
                            @if($stepData->pertanyaan[0]->textProperties->jenis=="text-biasa")
                            {{"checked"}}
                            @endif
                            @endif
                            >
                            <label class="form-check-label" for="text-biasa">Text Biasa</label>
                        </div>
                        <div class="form-check">
                            <input required class="form-check-input" type="radio" name="text_jenis" id="text-angka" value="text-angka" @if($stepData->pertanyaan[0]->textProperties!=null)
                            @if($stepData->pertanyaan[0]->textProperties->jenis=="text-angka")
                            {{"checked"}}
                            @endif
                            @endif
                            >
                            <label class="form-check-label" for="text-angka">Angka</label>
                        </div>
                        <div class="form-check">
                            <input required class="form-check-input" type="radio" name="text_jenis" id="text-desimal" value="text-desimal" @if($stepData->pertanyaan[0]->textProperties!=null)
                            @if($stepData->pertanyaan[0]->textProperties->jenis=="text-desimal")
                            {{"checked"}}
                            @endif
                            @endif
                            >
                            <label class="form-check-label" for="text-desimal">Desimal</label>
                        </div>
                        <div class="form-check">
                            <input required class="form-check-input" type="radio" name="text_jenis" id="text-email" value="text-email" @if($stepData->pertanyaan[0]->textProperties!=null)
                            @if($stepData->pertanyaan[0]->textProperties->jenis=="text-email")
                            {{"checked"}}
                            @endif
                            @endif
                            >
                            <label class="form-check-label" for="text-email">Email</label>
                        </div>

                        <div class="form-check">
                            <input required class="form-check-input" type="radio" name="text_jenis" id="text-tanggal" value="text-tanggal" @if($stepData->pertanyaan[0]->textProperties!=null)
                            @if($stepData->pertanyaan[0]->textProperties->jenis=="text-tanggal")
                            {{"checked"}}
                            @endif
                            @endif
                            >
                            <label class="form-check-label" for="text-tanggal">Tanggal</label>
                        </div>

                        @else
                        @foreach($stepData->pertanyaan[0]->jawabanJenis as $item)
                        @if($item->pilihan_jawaban!="lainnya")
                        <div class="col-md-12 mb-3">
                            <label for="pertanyaan_urutan" class="form-label">Pilihan</label>
                            <input type="text" class="form-control" name="jawaban[]" value="{{$item->pilihan_jawaban}}" required />
                        </div>
                        @endif
                        @endforeach
                        @endif
                    </div>
                    <div id="lainnya">
                        @if($stepData->pertanyaan[0]->pertanyaan_jenis_jawaban=="Pilihan" || $stepData->pertanyaan[0]->pertanyaan_jenis_jawaban=="Lebih Dari Satu Jawaban" || $stepData->pertanyaan[0]->pertanyaan_jenis_jawaban=="Select")

                        <div class="col-md-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="addLainnya" id="addLainnya" value="1" @if($stepData->pertanyaan[0]->lainnya == "1") checked @endif
                                >
                                <label class="form-check-label" for="addLainnya">Tambahkan Pilihan Lainnya</label>
                            </div>
                        </div>
                        @endif

                    </div>


                </div>

                <div class="col-12">
                    <button type="submit" class="btn btn-primary" style="float: right">Edit Pertanyaan</button>
                </div>
            </form>
        </div>
    </div>
</section>
<!-- Form Row End -->

<template id="selectTemplate">
    <div class="col-md-12 mb-3">
        <label for="pertanyaan_urutan" class="form-label">Pilihan</label>
        <input type="text" class="form-control" name="jawaban[]" placeholder="Tuliskan Jawaban" required />
    </div>
</template>
<template id="lainnyaTemplate">
    <div class="col-md-12">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="addLainnya" id="addLainnya" value="1">
            <label class="form-check-label" for="addLainnya">Tambahkan Pilihan Lainnya</label>
        </div>
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
    async function hapusJenisJawaban() {
        alert('Jawaban mau dihapus oooiii')
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
            const add = document.querySelector("#addPilihan")
            // if (jawabanValue != "Select") {
            if (jawabanValue != "Select" && jawabanValue != "Lebih Dari Satu Jawaban") {
                const lainnyaTemplate = document.querySelector("#lainnyaTemplate")
                const lainnya = lainnyaTemplate.content.cloneNode(true);
                lainnyaContainer.appendChild(lainnya)
            }
            add.addEventListener('click', function() {
                addElement()
            })

        }
    })
    const add = document.querySelector("#addPilihan")
    add.addEventListener('click', function() {
        addElement()
    })
</script>
@endsection
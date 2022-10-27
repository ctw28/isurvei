@extends('template')

@section('content')

<section class="scroll-section" id="basic">
    <!-- Basic Start -->
    <div class="card mb-5">
        <div class="card-body">
            <h3 class="mb-4">{{$bagianData->bagian_kode}}. {{$bagianData->bagian_nama}}</h3>
            <form action="{{route('mitra.store.jawaban',[$bagianData->survei_id, $bagianData->id])}}" method="post" enctype="multipart/form">
                @csrf
                <input type="hidden" name="bagian_id" value="{{$bagianData->id}}">
                <input type="hidden" name="awal" value="{{$awal}}">
                <input type="hidden" name="akhir" value="{{$akhir}}">
                @foreach ($bagianData->pertanyaan as $tanya)
                {!!$tanya->form!!}
                @endforeach



                @if($akhir==true)
                <button type="button" class="btn btn-dark" onclick="kembali('{{$bagianData->bagianDirect->bagian_id_direct_back}}')">Kembali</button>
                <button type="submit" class="btn btn-warning" onclick="return confirm('Yakin Selesai?')">Selesai</button>
                @else
                @if($awal==false)
                <button type="button" class="btn btn-dark" onclick="kembali('{{$bagianData->bagianDirect->bagian_id_direct_back}}')">Kembali</button>
                @endif
                <button type="submit" class="btn btn-primary">Simpan dan Lanjut</button>
                @endif
            </form>
        </div>
    </div>
</section>
<!-- Basic End -->

@endsection

@section('js')
<script>
    async function kembali(bagianId) {
        let urlBack = "{{route('mitra.show.pertanyaan',':bagianId')}}"
        urlBack = urlBack.replace(':bagianId', bagianId)
        window.location.replace(urlBack);

        // alert(bagianId)
    }

    function removeTextInput(event, pertanyaanId) {
        if (event.target.parentNode.parentNode.contains(document.querySelector("#lainnya_" + pertanyaanId))) {
            document.querySelector("#lainnya_" + pertanyaanId).remove();
        }
    }

    function showTextInput(event, pertanyaanId) {
        console.log(`${event.target.type} - ${event.target.checked}`);
        if (event.target.type === "checkbox") {
            if (event.target.checked == false)
                return removeTextInput(event, pertanyaanId)
        }
        if (event.target.type === "select-one") {
            if (event.target.value == "lainnya") {
                if (!event.target.parentNode.parentNode.contains(document.querySelector("#lainnya_" + pertanyaanId))) {
                    let input = document.createElement('input');
                    input.className = 'form-control'
                    input.name = `lainnya[${pertanyaanId}]`
                    input.id = `lainnya_${pertanyaanId}`
                    // event.target.removeAttribute('onclick')
                    event.target.closest('div').after(input)
                    return
                }
            } else {
                return removeTextInput(event, pertanyaanId)
            }
        }
        if (!event.target.parentNode.parentNode.contains(document.querySelector("#lainnya_" + pertanyaanId))) {
            let input = document.createElement('input');
            input.className = 'form-control'
            input.name = `lainnya[${pertanyaanId}]`
            input.id = `lainnya_${pertanyaanId}`
            input.setAttribute('required', 'required')
            // event.target.removeAttribute('onclick')
            event.target.closest('div').after(input);
        }
    }
</script>
@endsection
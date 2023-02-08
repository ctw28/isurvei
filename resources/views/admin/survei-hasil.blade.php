@extends('template')

@section('css')
<link rel="stylesheet" href="{{asset('/')}}css/vendor/select2.min.css" />
<link rel="stylesheet" href="{{asset('/')}}css/vendor/select2-bootstrap4.min.css" />
@endsection
@section('content')


<!-- Form Row Start -->
<!-- Text Content Start -->
<section class="scroll-section" id="textContent">
    <div class="card mb-5">
        <div class="card-body d-flex flex-column">
            <h3 class="card-title mb-4">Statistik Kuisioner</h3>
            <select class="form-select" id="bagian">
                <option value="">Pilih Bagian</option>
                @foreach ($data as $item)
                <option value="{{$item->id}}">{{$item->survei_nama}}</option>
                @endforeach
            </select>
            <div id="show-pertanyaan">

            </div>
        </div>
    </div>
</section>
<!-- Text Content End -->
<section class="scroll-section" id="formRow" style="display:none">
    <div class="card mb-5">
        <div class="card-body">
            <h4>Filter</h4>
            <div class="row mb-5">

                <div class="col-md-3">
                    <select class="form-select" id="fakultas">
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select" id="prodi">
                    </select>
                </div>
                <div class="col-md-3">
                    <select multiple="multiple" class="form-select" id="tahun-lulus">
                    </select>
                </div>
                <div class="col-md-3">
                    <button class="btn btn-warning" id="filter">Filter</button>
                </div>

            </div>
            <!-- <h2>Rekap</h2> -->

            <div id="result-show">

            </div>


        </div>
    </div>
</section>


<template id="resultTemplate">
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th scope="col">No</th>
                <th scope="col">Pilihan Jawaban</th>
                <th scope="col">Total</th>
            </tr>
        </thead>
        <tbody id="show-data-table">

        </tbody>
    </table>
    <h2 class="mt-5">Diagram</h2>
    <div>
        <label class="form-label d-block">Pilih Diagram</label>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="diagram" id="pie" value="pie" />
            <label class="form-check-label" for="pie">Pie</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="diagram" id="bar" value="bar" />
            <label class="form-check-label" for="bar">Bar</label>
        </div>
        <div class="form-check form-check-inline">
            <button class="btn btn-primary" id="terapkan">Terapkan</button>
        </div>
    </div>
    <div id="showDiagram" class="col-sm-12" style="height: 500px;"></div>
</template>
<template id="resultAngkaTemplate">
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th scope="col">No</th>
                <th scope="col">Item</th>
                <th scope="col">Hasil</th>
            </tr>
        </thead>
        <tbody id="show-data-hitung">
        </tbody>
    </table>
</template>

<!-- Form Row End -->

@endsection
@section('js')
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script src="{{asset('/')}}js/vendor/select2.full.min.js"></script>
<script src="{{asset('/')}}js/forms/controls.select2.js"></script>

@endsection
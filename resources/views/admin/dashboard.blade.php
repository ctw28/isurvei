@extends('template')

@section('content')
<!-- <h2>Dashboard</h2> -->

<div class="mb-5">
    <div class="row g-2">
        <div class="col-12 col-sm-4 col-lg-4">
            <div class="card sh-11 hover-scale-up cursor-pointer">
                <div class="h-100 row g-0 card-body align-items-center py-3">
                    <div class="col-auto pe-3">
                        <div class="bg-gradient-2 sh-5 sw-5 rounded-xl d-flex justify-content-center align-items-center">
                            <i data-cs-icon="notebook-1" class="text-white"></i>
                        </div>
                    </div>
                    <div class="col">
                        <div class="row gx-2 d-flex align-content-center">
                            <div class="col-12 col-xl d-flex">
                                <div class="d-flex align-items-center lh-1-25">Total Survei</div>
                            </div>
                            <div class="col-12 col-xl-auto">
                                <div class="cta-2 text-primary">{{$survei}}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-4 col-lg-4">
            <div class="card sh-11 hover-scale-up cursor-pointer">
                <div class="h-100 row g-0 card-body align-items-center py-3">
                    <div class="col-auto pe-3">
                        <div class="bg-gradient-2 sh-5 sw-5 rounded-xl d-flex justify-content-center align-items-center">
                            <i data-cs-icon="form" class="text-white"></i>
                        </div>
                    </div>
                    <div class="col">
                        <div class="row gx-2 d-flex align-content-center">
                            <div class="col-12 col-xl d-flex">
                                <div class="d-flex align-items-center lh-1-25">Survei Berjalan</div>
                            </div>
                            <div class="col-12 col-xl-auto">
                                <div class="cta-2 text-primary">{{$surveiAktif}}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-4 col-lg-4">
            <div class="card sh-11 hover-scale-up cursor-pointer">
                <div class="h-100 row g-0 card-body align-items-center py-3">
                    <div class="col-auto pe-3">
                        <div class="bg-gradient-2 sh-5 sw-5 rounded-xl d-flex justify-content-center align-items-center">
                            <i data-cs-icon="check" class="text-white"></i>
                        </div>
                    </div>
                    <div class="col">
                        <div class="row gx-2 d-flex align-content-center">
                            <div class="col-12 col-xl d-flex">
                                <div class="d-flex align-items-center lh-1-25">Survei Selesai</div>
                            </div>
                            <div class="col-12 col-xl-auto">
                                <div class="cta-2 text-primary">{{$surveiSelesai}}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<!-- Stats End -->

<h2 class="small-title">Tentang Aplikasi</h2>
<div class="row">
    <div class="col-12 col-sm-6 col-lg-6">
        <div class="card mb-5 h-auto">
            <div class="card-body h-100">
                <div class="row g-0 h-100">
                    <div class="col-6 h-100 d-flex justify-content-between flex-column">
                        <p class="mb-2">Nama Aplikasi : &nbsp; <b>ISURVEI IAIN KENDARI</b></p>
                        <p class="mb-2">Versi : &nbsp;<b>1.0</b></p>
                        <p class="mb-2">Tahun Pembuatan : &nbsp;<b>2022</b></p>
                        <p class="mb-2">Developer : &nbsp;<b>TIM UPT TIPD</b></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
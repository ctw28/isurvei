@extends('template')

@section('content')

<div class="card mb-5">
    <div class="card-body">
        <h2>Kuisioner Selesai</h2>
        <p>Terima Kasih telah partisipasi.</p>
        <a href="{{route('user.dashboard')}}" class="btn btn-primary">Kembali Ke Beranda</a>
    </div>
</div>
@endsection
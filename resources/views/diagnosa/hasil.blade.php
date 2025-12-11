@extends('layouts.app')

@section('title','Hasil Diagnosa')

@section('content')
<h1 class="page-title">Hasil Diagnosa Penyakit</h1>
<p class="page-subtitle">
    Berdasarkan gejala yang dipilih, berikut kemungkinan penyakit menurut
    metode Certainty Factor dan Dempster–Shafer.
</p>

<div class="card">
    <h3>Ringkasan Hasil</h3>
    <p>
        CF tertinggi:
        <strong>{{ $hasilCF[0]['kode'] }} – {{ $hasilCF[0]['nama'] }}
            ({{ $hasilCF[0]['persen'] }}%)</strong><br>
        DS tertinggi:
        <strong>{{ $hasilDS[0]['kode'] }} – {{ $hasilDS[0]['nama'] }}
            ({{ $hasilDS[0]['persen'] }}%)</strong>
    </p>
</div>

<br>

<div class="card">
    <div class="card-header">
        <h3>Detail Nilai Certainty Factor (CF)</h3>
    </div>
    <table>
        <thead>
        <tr>
            <th>#</th>
            <th>Kode</th>
            <th>Nama Penyakit</th>
            <th>Nilai CF</th>
            <th>Persentase</th>
        </tr>
        </thead>
        <tbody>
        @foreach($hasilCF as $i => $row)
            <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $row['kode'] }}</td>
                <td>{{ $row['nama'] }}</td>
                <td>{{ $row['nilai'] }}</td>
                <td>{{ $row['persen'] }}%</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<br>

<div class="card">
    <div class="card-header">
        <h3>Detail Nilai Dempster–Shafer (DS)</h3>
    </div>
    <table>
        <thead>
        <tr>
            <th>#</th>
            <th>Kode</th>
            <th>Nama Penyakit</th>
            <th>Nilai DS</th>
            <th>Persentase</th>
        </tr>
        </thead>
        <tbody>
        @foreach($hasilDS as $i => $row)
            <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $row['kode'] }}</td>
                <td>{{ $row['nama'] }}</td>
                <td>{{ $row['nilai'] }}</td>
                <td>{{ $row['persen'] }}%</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

{{-- contoh saran simple --}}
<div class="card" style="margin-top:20px;">
    <h3>Saran Penanganan Awal</h3>
    <p>
        Penyakit dengan kemungkinan tertinggi menurut CF adalah
        <strong>{{ $hasilCF[0]['nama'] }}</strong> ({{ $hasilCF[0]['persen'] }}%).
        Disarankan segera menghubungi dokter hewan atau pakar untuk
        konfirmasi lebih lanjut dan penanganan sesuai protokol kesehatan
        ternak yang berlaku.
    </p>
</div>
@endsection

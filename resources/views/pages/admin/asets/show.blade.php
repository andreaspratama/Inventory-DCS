@extends('layouts.admin')

@section('title')
    Detail Assets
@endsection

@section('content')
<main id="main-container">
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                <div class="flex-grow-1">
                    <h1 class="h3 fw-bold mb-2">
                        Detail Assets
                    </h1>
                </div>
                <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">
                            <a class="link-fx" href="javascript:void(0)">Forms</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            Elements
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">Detail Assets</h3>
            </div>

            <div class="block-content block-content-full">
                <form action="{{route('asets.update', $aset->id)}}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row push">
                        <div class="col-lg-12 col-xl-12">

                            <div class="row mb-3">
                <div class="col-md-4"><strong>Kode Barang</strong></div>
                <div class="col-md-8">{{ $aset->kode_brg }}</div>
            </div>
                            <div class="row mb-3">
                <div class="col-md-4"><strong>Nama Barang</strong></div>
                <div class="col-md-8">{{ $aset->nama }}</div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4"><strong>Tipe</strong></div>
                <div class="col-md-8">{{ $aset->type->nama ?? '-' }}</div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4"><strong>Unit</strong></div>
                <div class="col-md-8">{{ $aset->unit->nama ?? '-' }}</div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4"><strong>Brand / Merk</strong></div>
                <div class="col-md-8">{{ $aset->brand }}</div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4"><strong>Jumlah</strong></div>
                <div class="col-md-8">{{ $aset->jumlah }}</div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4"><strong>Lokasi / Ruang</strong></div>
                <div class="col-md-8">{{ $aset->ruang->nama ?? $aset->other_lokasi }}</div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4"><strong>Harga Beli</strong></div>
                <div class="col-md-8">
                    Rp {{ number_format((float) str_replace('.', '', $aset->harga), 0, ',', '.') }}
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4"><strong>Tanggal Pembelian</strong></div>
                <div class="col-md-8">
                    {{ $aset->tgl_beli?->format('d-m-Y') ?? '-' }}
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4"><strong>Sumber Dana</strong></div>
                <div class="col-md-8">
                    {{ $aset->sumber }}
                </div>
            </div>

            @if($aset->deskripsi)
            <div class="row mb-3">
                <div class="col-md-4"><strong>Deskripsi</strong></div>
                <div class="col-md-8">{{ $aset->deskripsi }}</div>
            </div>
            @endif

            <div class="row mb-3">
                <div class="col-md-4"><strong>QR Code</strong></div>
                <div class="col-md-8">
                    @if($aset->barcode)
                        <img src="{{ asset('storage/' . $aset->barcode) }}" alt="QR Code" width="180">
                        <br>
                        <a href="{{ route('downloadQRMultiple', $aset->id) }}" download class="btn btn-sm btn-primary mt-2">
                            Download QR Code
                        </a>
                    @else
                        <span class="text-muted">Tidak ada QR Code</span>
                    @endif
                </div>
            </div>

            <div class="mt-4">
                <a href="{{ route('asets.index') }}" class="btn btn-secondary">Kembali</a>
                <a href="{{ route('asets.edit', $aset->id) }}" class="btn btn-warning">Edit</a>
            </div>

                        </div>
                    </div>

                </form>
            </div>
        </div>

    </div>
</main>
@endsection



{{-- @extends('layouts.admin')

@section('content')
<div class="container py-4">

    <h3 class="fw-bold mb-3">Detail Aset</h3>

    <div class="card">
        <div class="card-body">

            <div class="row mb-3">
                <div class="col-md-4"><strong>Nama Aset</strong></div>
                <div class="col-md-8">{{ $aset->nama }}</div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4"><strong>Tipe</strong></div>
                <div class="col-md-8">{{ $aset->type->nama ?? '-' }}</div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4"><strong>Unit</strong></div>
                <div class="col-md-8">{{ $aset->unit->nama ?? '-' }}</div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4"><strong>Brand / Merk</strong></div>
                <div class="col-md-8">{{ $aset->brand }}</div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4"><strong>Jumlah</strong></div>
                <div class="col-md-8">{{ $aset->jumlah }}</div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4"><strong>Lokasi / Ruang</strong></div>
                <div class="col-md-8">{{ $aset->ruang->nama ?? $aset->other_lokasi }}</div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4"><strong>Harga Beli</strong></div>
                <div class="col-md-8">
                    Rp {{ number_format((float) str_replace('.', '', $aset->harga), 0, ',', '.') }}
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4"><strong>Tanggal Pembelian</strong></div>
                <div class="col-md-8">
                    {{ \Carbon\Carbon::parse($aset->tgl_beli)->format('d M Y') }}
                </div>
            </div>

            @if($aset->deskripsi)
            <div class="row mb-3">
                <div class="col-md-4"><strong>Deskripsi</strong></div>
                <div class="col-md-8">{{ $aset->deskripsi }}</div>
            </div>
            @endif

            <div class="row mb-3">
                <div class="col-md-4"><strong>QR Code</strong></div>
                <div class="col-md-8">
                    @if($aset->barcode)
                        <img src="{{ asset('storage/' . $aset->barcode) }}" alt="QR Code" width="180">
                        <br>
                        <a href="{{ asset('storage/' . $aset->barcode) }}" download class="btn btn-sm btn-primary mt-2">
                            Download QR Code
                        </a>
                    @else
                        <span class="text-muted">Tidak ada QR Code</span>
                    @endif
                </div>
            </div>

            <div class="mt-4">
                <a href="{{ route('asets.index') }}" class="btn btn-secondary">Kembali</a>
                <a href="{{ route('asets.edit', $aset->id) }}" class="btn btn-warning">Edit</a>
            </div>

        </div>
    </div>

</div>
@endsection --}}

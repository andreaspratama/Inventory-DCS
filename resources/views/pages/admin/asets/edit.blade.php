@extends('layouts.admin')

@section('title')
    Edit Assets
@endsection

@section('content')
<main id="main-container">
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                <div class="flex-grow-1">
                    <h1 class="h3 fw-bold mb-2">
                        Edit Assets
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
                <h3 class="block-title">Form Edit Assets</h3>
            </div>

            <div class="block-content block-content-full">
                <form action="{{route('asets.update', $item->id)}}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row push">
                        <div class="col-lg-12 col-xl-12">

                            <!-- UNIT -->
                            <div class="mb-4">
                                <label class="form-label" for="unit_id">Unit</label>
                                <select class="form-select" id="unit_id" name="unit_id">
                                    @foreach ($unit as $ut)
                                        <option value="{{$ut->id}}" {{ $item->unit_id == $ut->id ? 'selected' : '' }}>
                                            {{$ut->nama}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- LOKASI -->
                            <div class="mb-4">
                                <label class="form-label" for="ruang_id">Lokasi</label>
                                <select class="form-select" id="ruang_id" name="ruang_id">
                                    <option value="" disabled>-- Pilih Lokasi --</option>

                                    @foreach ($ruang as $rg)
                                        <option value="{{ $rg->id }}" {{ $item->ruang_id == $rg->id ? 'selected' : '' }}>
                                            {{ $rg->nama }}
                                        </option>
                                    @endforeach

                                    <option value="OTHER"
                                        {{ $item->ruang_id == null && $item->other_lokasi ? 'selected' : '' }}>
                                        Other...
                                    </option>
                                </select>
                            </div>

                            <!-- OTHER LOKASI -->
                            <div class="mb-4" id="other_lokasi_wrapper"
                                style="{{ $item->ruang_id == null && $item->other_lokasi ? '' : 'display:none;' }}">
                                <label class="form-label">Lokasi Lainnya</label>
                                <input type="text" class="form-control" id="other_lokasi" name="other_lokasi"
                                    value="{{ $item->other_lokasi }}" placeholder="Tulis lokasi lainnya">
                            </div>

                            <!-- TYPE -->
                            <div class="mb-4">
                                <label class="form-label" for="type_id">Type</label>
                                <select class="form-select" id="type_id" name="type_id">
                                    @foreach ($type as $tp)
                                        <option value="{{$tp->id}}" {{ $item->type_id == $tp->id ? 'selected' : '' }}>
                                            {{$tp->nama}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- KODE BARANG -->
                            <div class="mb-4">
                                <label class="form-label" for="kode_brg">Kode Barang</label>
                                <input type="text" class="form-control" id="kode_brg" name="kode_brg" value="{{$item->kode_brg}}">
                            </div>

                            <!-- NAMA -->
                            <div class="mb-4">
                                <label class="form-label" for="nama">Nama Barang</label>
                                <input type="text" class="form-control" id="nama" name="nama" value="{{$item->nama}}">
                            </div>

                            <!-- BRAND -->
                            <div class="mb-4">
                                <label class="form-label" for="brand">Brand</label>
                                <input type="text" class="form-control" id="brand" name="brand" value="{{$item->brand}}">
                            </div>

                            <!-- JUMLAH -->
                            <div class="mb-4">
                                <label class="form-label" for="jumlah">Jumlah</label>
                                <input type="text" class="form-control" id="jumlah" name="jumlah" value="{{$item->jumlah}}">
                            </div>

                            <!-- HARGA BELI -->
                            <div class="mb-4">
                                <label class="form-label" for="harga">Harga Beli</label>
                                <input type="text" class="form-control" 
                                        id="harga" name="harga" 
                                        value="{{ old('harga', number_format((int) preg_replace('/\D/', '', $item->getRawOriginal('harga')), 0, ',', '.')) }}">
                            </div>

                            <!-- TANGGAL BELI -->
                            <div class="mb-4">
                                <label class="form-label" for="tgl_beli">Tanggal Beli</label>
                                <input type="date" class="form-control" id="tgl_beli" name="tgl_beli" value="{{$item->tgl_beli}}">
                            </div>

                            <!-- SUMBER DANA -->
                            <div class="mb-4">
                                <label class="form-label" for="sumber">
                                    Sumber Dana <span class="text-danger">*</span>
                                </label>

                                <select class="form-select" id="sumber" name="sumber" required>
                                    <option value="">-- Pilih Sumber Dana --</option>

                                    <option value="Pemerintah / BOS"
                                        {{ old('sumber', $item->sumber) == 'Pemerintah / BOS' ? 'selected' : '' }}>
                                        Pemerintah / BOS
                                    </option>

                                    <option value="DCS"
                                        {{ old('sumber', $item->sumber) == 'DCS' ? 'selected' : '' }}>
                                        DCS
                                    </option>
                                </select>
                            </div>

                            <div>
                                <button type="submit" class="btn btn-primary">Update</button>
                                <a href="{{route('asets.index')}}" class="btn btn-secondary">Cancel</a>
                            </div>

                        </div>
                    </div>

                </form>
            </div>
        </div>

    </div>
</main>
@endsection

@push('prepend-script')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    function toggleOther(){
        let ruang = $('#ruang_id').val();

        if (ruang === 'OTHER') {
            $('#other_lokasi_wrapper').slideDown();   // tampilkan
        } else {
            $('#other_lokasi_wrapper').slideUp();     // sembunyikan
            $('#other_lokasi').val('');               // hapus nilai input
        }
    }

    // Trigger saat dropdown berubah
    $('#ruang_id').on('change', toggleOther);

    // Trigger ketika halaman pertama kali dibuka
    toggleOther();


    // AJAX ketika unit berubah
    $('#unit_id').on('change', function() {
        var unitID = $(this).val();

        if (unitID) {
            $.ajax({
                url: "{{ url('/get-ruang') }}/" + unitID,
                type: 'GET',
                dataType: 'json',
                success: function(data) {

                    $('#ruang_id').empty();
                    $('#ruang_id').append('<option disabled selected>-- Pilih Lokasi --</option>');

                    $.each(data, function(i, v) {
                        $('#ruang_id').append(`<option value="${v.id}">${v.nama}</option>`);
                    });

                    // Tambahkan OTHER
                    $('#ruang_id').append('<option value="OTHER">Other...</option>');

                    toggleOther();
                }
            });
        }
    });
    document.addEventListener('DOMContentLoaded', function () {
        // Format saat user mengetik
        const hargaInput = document.getElementById('harga');
        hargaInput.addEventListener('input', function(e) {
            let raw = this.value.replace(/\D/g, "");
            if (raw) {
                this.value = new Intl.NumberFormat('id-ID').format(raw);
            } else {
                this.value = "";
            }
        });

        // Hapus titik sebelum submit
        document.querySelector('form').addEventListener('submit', function() {
            let raw = hargaInput.value.replace(/\./g, '');
            hargaInput.value = raw;
        });

    });
</script>

@endpush

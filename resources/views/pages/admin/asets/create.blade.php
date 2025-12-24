@extends('layouts.admin')

@section('title')
    Add Assets
@endsection

@section('content')
    <!-- Main Container -->
    <main id="main-container">
        <!-- Hero -->
        <div class="bg-body-light">
          <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
              <div class="flex-grow-1">
                <h1 class="h3 fw-bold mb-2">
                  Add Assets
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
        <!-- END Hero -->

        <!-- Page Content -->
        <div class="content">
          <!-- Basic -->
          <div class="block block-rounded">
            <div class="block-header block-header-default">
              <h3 class="block-title">Form Input</h3>
            </div>
            <div class="block-content block-content-full">
              <form action="{{route('asets.store')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row push">
                  <div class="col-lg-12 col-xl-12">
                    <div class="mb-4">
                      <label class="form-label" for="unit_id">Unit</label> <span class="text-danger">*</span>
                      <select class="form-select" id="unit_id" name="unit_id">
                        <option selected>Select Unit</option>
                        @foreach ($unit as $ut)
                            <option value="{{$ut->id}}">{{$ut->nama}}</option>
                        @endforeach
                      </select>
                    </div>
                    <div class="mb-4">
                        <label class="form-label" for="ruang_id">Lokasi / Ruang</label> <span class="text-danger">*</span>
                        <select class="form-select" id="ruang_id" name="ruang_id">
                            <option selected disabled>-- Pilih Lokasi / Ruang --</option>
                        </select>
                    </div>
                    <div class="mb-4" id="other_lokasi_wrapper" style="display:none;">
                        <label class="form-label">Lokasi Lainnya</label> <span class="text-danger">*</span>
                        <input type="text" class="form-control" id="other_lokasi" name="other_lokasi" placeholder="Tulis lokasi lainnya">
                    </div>
                    <div class="mb-4">
                      <label class="form-label" for="type_id">Type</label> <span class="text-danger">*</span>
                      <select class="form-select" id="type_id" name="type_id">
                        <option selected>Select Type</option>
                        @foreach ($type as $tp)
                            <option value="{{$tp->id}}">{{$tp->nama}}</option>
                        @endforeach
                      </select>
                    </div>
                    <div class="mb-4">
                      <label class="form-label" for="kode_brg">Kode Barang</label> <span class="text-danger">*</span>
                      <input type="text" class="form-control" id="kode_brg" name="kode_brg" placeholder="Kode">
                    </div>
                    <div class="mb-4">
                      <label class="form-label" for="nama">Nama Barang</label> <span class="text-danger">*</span>
                      <input type="text" class="form-control" id="nama" name="nama" placeholder="Nama">
                    </div>
                    <div class="mb-4">
                      <label class="form-label" for="brand">Brand</label> <span class="text-danger">*</span>
                      <input type="text" class="form-control" id="brand" name="brand" placeholder="Brand">
                    </div>
                    <div class="mb-4">
                      <label class="form-label" for="jumlah">Jumlah</label> <span class="text-danger">*</span>
                      <input type="text" class="form-control" id="jumlah" name="jumlah" placeholder="Jumlah">
                    </div>
                    <div class="mb-4">
                      <label class="form-label" for="harga">Harga Beli</label>
                      <input type="text" class="form-control" id="harga" name="harga" placeholder="Harga Beli">
                    </div>
                    <div class="mb-4">
                      <label class="form-label" for="tgl_beli">Tanggal Beli</label>
                      <input type="date" class="form-control" id="tgl_beli" name="tgl_beli" placeholder="Tanggal Beli" min="{{ date('Y-m-d') }}">
                    </div>
                    <div class="mb-4">
                      <label class="form-label" for="sumber">Sumber Dana</label> <span class="text-danger">*</span>
                      <select class="form-select" id="sumber" name="sumber">
                        <option>-- Pilih Sumber Dana --</option>
                        <option value="Pemerintah / BOS">Pemerintah / BOS</option>
                        <option value="DCS">DCS</option>
                      </select>
                    </div>
                    <span style="font-weight:bold">Note:</span> <span class="text-danger">*) Wajib untuk diisi</span>
                    <div>
                       <button type="submit" class="btn btn-primary mt-4">Add</button>
                       <a href="{{route('asets.index')}}" class="btn btn-secondary mt-4">Cancel</a>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
          <!-- END Basic -->
        </div>
        <!-- END Page Content -->
    </main>
    <!-- END Main Container -->
@endsection

@push('prepend-script')
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
  <script>
      $('#unit_id').on('change', function() {
          var unitID = $(this).val();
          if (unitID) {
              $.ajax({
                  url: "{{ url('/get-ruang') }}/" + unitID,
                  type: 'GET',
                  dataType: 'json',
                  success: function(data) {
                      $('#ruang_id').empty();
                      $('#ruang_id').append('<option selected disabled>-- Pilih Lokasi / Ruang --</option>');

                      $.each(data, function(key, value) {
                          $('#ruang_id').append('<option value="' + value.id + '">' + value.nama + '</option>');
                      });

                      // Tambahkan opsi OTHER
                      $('#ruang_id').append('<option value="OTHER">Other...</option>');
                  }
              });
          } else {
              $('#ruang_id').empty();
              $('#ruang_id').append('<option selected disabled>-- Pilih Lokasi --</option>');
          }
      });

      // Tampilkan input jika pilih OTHER
      $('#ruang_id').on('change', function() {
          if ($(this).val() === 'OTHER') {
              $('#other_lokasi_wrapper').show();
          } else {
              $('#other_lokasi_wrapper').hide();
              $('#other_lokasi').val('');
          }
      });

      // Format angka ke format rupiah (tanpa simbol "Rp")
      function formatRupiah(angka) {
          let number_string = angka.replace(/[^,\d]/g, '').toString(),
              split = number_string.split(','),
              sisa  = split[0].length % 3,
              rupiah  = split[0].substr(0, sisa),
              ribuan  = split[0].substr(sisa).match(/\d{3}/gi);

          if (ribuan) {
              let separator = sisa ? '.' : '';
              rupiah += separator + ribuan.join('.');
          }

          return split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
      }

      $('#harga').on('keyup', function() {
          this.value = formatRupiah(this.value);
      });

      $('form').on('submit', function() {
          let raw = $('#harga').val().replace(/\./g, ''); 
          $('#harga').val(raw);
      });
  </script>3
  <script>
    ClassicEditor
        .create(document.querySelector('#deskripsi'), {
            toolbar: [
                'heading', '|',
                'bold', 'italic', 'underline', '|',
                'bulletedList', 'numberedList', '|',
                'blockQuote', '|',
                'undo', 'redo'
            ],
            placeholder: "Tuliskan deskripsi aset..."
        })
        .catch(error => {
            console.error(error);
        });
</script>
@endpush
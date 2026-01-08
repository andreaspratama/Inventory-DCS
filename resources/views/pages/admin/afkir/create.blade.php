@extends('layouts.admin')

@section('title')
    Add Afkir
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
                  Add Afkir
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
              @if ($errors->any())
                  <div class="alert alert-danger">
                      <strong>Oops! Ada kesalahan:</strong>
                      <ul class="mb-0 mt-2">
                          @foreach ($errors->all() as $error)
                              <li>{{ $error }}</li>
                          @endforeach
                      </ul>
                  </div>
              @endif

              <form action="{{route('afkir.store')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row push">
                  <div class="col-lg-12 col-xl-12">
                    <div class="mb-4">
                      <label class="form-label" for="aset_id">Barang</label> <span class="text-danger">*</span>
                      <select class="form-select" id="aset_id" name="aset_id">
                        <option selected>Select Aset</option>
                        @foreach ($asets as $as)
                            <option value="{{$as->id}}">{{$as->nama}}</option>
                        @endforeach
                      </select>
                    </div>
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
                      <label class="form-label" for="jumlah">Jumlah</label> <span class="text-danger">*</span>
                      <input type="text" class="form-control" id="jumlah" name="jumlah" placeholder="Jumlah">
                    </div>
                    <div class="mb-4">
                      <label class="form-label" for="tgl_afkir">Tanggal Afkir</label>
                      <input type="date" class="form-control" id="tgl_afkir" name="tgl_afkir" placeholder="Tanggal Afkir">
                    </div>
                    <div class="mb-4">
                      <label class="form-label" for="kondisi">Kondisi</label> <span class="text-danger">*</span>
                      <select class="form-select" id="kondisi" name="kondisi">
                        <option selected>-- Select One --</option>
                        <option value="Rusak Berat">Rusak Berat</option>
                        <option value="Tidak Layak Pakai">Tidak Layak Pakai</option>
                        <option value="Usang">Usang</option>
                      </select>
                    </div>
                    <div class="mb-4">
                      <label class="form-label" for="tindak_lanjut">Tindak Lanjut</label> <span class="text-danger">*</span>
                      <select class="form-select" id="tindak_lanjut" name="tindak_lanjut">
                        <option selected>-- Select One --</option>
                        <option value="Dimusnahkan">Dimusnahkan</option>
                        <option value="Disimpan">Disimpan</option>
                        <option value="Dihibahkan">Dihibahkan</option>
                        <option value="Dilelang">Dilelang</option>
                      </select>
                    </div>
                    <div class="mb-4">
                      <label class="form-label" for="keterangan">Keterangan</label>
                      <input type="text" class="form-control" id="keterangan" name="keterangan" placeholder="Keterangan">
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
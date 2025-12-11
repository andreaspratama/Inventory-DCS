@extends('layouts.admin')

@section('title')
    List Assets
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
                  Table Assets
                </h1>
              </div>
              <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                  <li class="breadcrumb-item" aria-current="page">
                    Assets
                  </li>
                </ol>
              </nav>
            </div>
          </div>
        </div>
        <!-- END Hero -->

        <!-- Page Content -->
        <div class="content">
          <div class="row">
            <div class="col-xl-12">
              <!-- Table Type -->
              <div class="block block-rounded">
                <div class="block-header block-header-default">
                  <h3 class="block-title">Table Type</h3>
                  <div class="block-options">
                    <div class="block-options-item">
                      <code>.table</code>
                    </div>
                  </div>
                </div>
                <div class="block-content">
                  <table class="table table-vcenter" id="tableAsets">
                    <thead>
                      <tr>
                        <th class="text-center" style="width: 50px;">#</th>
                        <th class="text-center">Unit</th>
                        <th class="text-center">Type</th>
                        <th class="text-center" style="width: 20%">Nama</th>
                        <th class="text-center">Brand</th>
                        <th class="text-center">Jumlah</th>
                        <th class="text-center" style="width: 20%">Tempat</th>
                        <th class="text-center" style="width: 30%">Deskripsi</th>
                        <th class="text-center" style="width: 30%">QrCode</th>
                        {{-- <th>Barcode</th> --}}
                        <th class="text-center">Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                    </tbody>
                  </table>
                </div>
              </div>
              <!-- END Table Type -->
            </div>
          </div>
        </div>
        <!-- END Page Content -->
    </main>
    <!-- END Main Container -->
    
    @include('sweetalert::alert')
@endsection

@push('prepend-style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
@endpush

@push('addon-script')
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    {{-- <script src="https://code.jquery.com/jquery-3.6.0.slim.js" integrity="sha256-HwWONEZrpuoh951cQD1ov2HUK5zA5DwJ1DNUXaM6FsY=" crossorigin="anonymous"></script> --}}
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
      $(document).ready(function() {
        $('#tableAsets').on('click', '.delete', function() {
          var asetid = $(this).attr('data-id');
          swal({
          title: "Delete",
          text: "Are you sure?",
          icon: "warning",
          buttons: true,
          dangerMode: true,
          })
          .then((willDelete) => {
              console.log(willDelete);
            if (willDelete) {
              window.location = "deleteAset/"+asetid+"";
            } else {
              swal("Data is not deleted");
            }
          });
        });
      });
    </script>
    {{-- <script>
      $('.hapus').click( function(){
          swal({
          title: "Are you sure?",
          text: "Once deleted, you will not be able to recover this imaginary file!",
          icon: "warning",
          buttons: true,
          dangerMode: true,
          })
          .then((willDelete) => {
            if (willDelete) {
              swal("Poof! Your imaginary file has been deleted!", {
                icon: "success",
              });
            } else {
              swal("Your imaginary file is safe!");
            }
          });
      });
    </script> --}}
    <script>
        var datatable = $('#tableAsets').DataTable({
            processing: true,
            serverSide: true,
            ordering: true,
            ajax: {
                url: '{!! url()->current() !!}',
            },
            columns: [
                { data: 'number', name: 'number' },
                { data: 'unit_id', name: 'unit_id' },
                { data: 'type_id', name: 'type_id' },
                { data: 'nama', name: 'nama' },
                { data: 'brand', name: 'brand' },
                { data: 'jumlah', name: 'jumlah' },
                { data: 'ruang_id', name: 'ruang_id' },
                { data: 'deskripsi', name: 'deskripsi' },
                { data: 'barcode', name: 'barcode' },
                {
                    data: 'aksi',
                    name: 'aksi',
                    orderable: false,
                    searcable: false,
                    width: '25%'
                },
            ],
            columnDefs: [
            { targets: '_all', className: 'text-center align-middle' }, // semua kolom tengah
            // atau kalau mau kolom tertentu aja misal barcode & aksi:
            // { targets: [7, 8], className: 'text-center align-middle' }
            ]
        })
    </script>
    <script>
        $(document).ready(function () {
            $('#tableAsets').DataTable();
        });
    </script>
@endpush
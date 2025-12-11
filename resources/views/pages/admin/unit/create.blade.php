@extends('layouts.admin')

@section('title')
    Add Unit
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
                  Add Unit
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
              <form action="{{route('unit.store')}}" method="POST" encunit="multipart/form-data">
                @csrf
                <div class="row push">
                  <div class="col-lg-12 col-xl-12">
                    <div class="mb-4">
                      <label class="form-label" for="nama">Unit</label>
                      <input unit="text" class="form-control" id="nama" name="nama" placeholder="Unit">
                    </div>
                    <div>
                       <button unit="submit" class="btn btn-primary">Add</button>
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
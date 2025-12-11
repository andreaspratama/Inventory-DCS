@extends('layouts.admin')

@section('title')
    Dashboard
@endsection

@section('content')
    <!-- Main Container -->
    <main id="main-container">
        <!-- Hero -->
        <div class="content">
          <div class="d-flex flex-column flex-md-row justify-content-md-between align-items-md-center py-2 text-center text-md-start">
            <div class="flex-grow-1 mb-1 mb-md-0">
              <h1 class="h3 fw-bold mb-2">
                Dashboard
              </h1>
              <h2 class="h6 fw-medium fw-medium text-muted mb-0">
                Welcome <a class="fw-semibold" href="be_pages_generic_profile.html">{{Auth::user()->name}}</a>
              </h2>
            </div>
          </div>
        </div>
        <!-- END Hero -->

        <!-- Page Content -->
        <div class="content">
          <!-- Overview -->
          <div class="row items-push">
            <div class="col-sm-6 col-xxl-3">
              <!-- Pending Orders -->
              <div class="block block-rounded d-flex flex-column h-100 mb-0">
                <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                  <dl class="mb-0">
                    <dt class="fs-3 fw-bold">{{$type}}</dt>
                    <dd class="fs-sm fw-medium fs-sm fw-medium text-muted mb-0">Tipe</dd>
                  </dl>
                  <div class="item item-rounded-lg bg-body-light">
                    <i class="fa fa-2x fa-grip"></i>
                  </div>
                </div>
                <div class="bg-body-light rounded-bottom">
                  <a class="block-content block-content-full block-content-sm fs-sm fw-medium d-flex align-items-center justify-content-between" href="{{route('type.index')}}">
                    <span>Lihat Semua Tipe</span>
                    <i class="fa fa-arrow-alt-circle-right ms-1 opacity-25 fs-base"></i>
                  </a>
                </div>
              </div>
              <!-- END Pending Orders -->
            </div>
            <div class="col-sm-6 col-xxl-3">
              <!-- New Customers -->
              <div class="block block-rounded d-flex flex-column h-100 mb-0">
                <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                  <dl class="mb-0">
                    <dt class="fs-3 fw-bold">{{$asets}}</dt>
                    <dd class="fs-sm fw-medium fs-sm fw-medium text-muted mb-0">Aset Barang</dd>
                  </dl>
                  <div class="item item-rounded-lg bg-body-light">
                    <i class="si si-briefcase fa-2x"></i>
                  </div>
                </div>
                <div class="bg-body-light rounded-bottom">
                  <a class="block-content block-content-full block-content-sm fs-sm fw-medium d-flex align-items-center justify-content-between" href="javascript:void(0)">
                    <span>Lihat Semua Aset</span>
                    <i class="fa fa-arrow-alt-circle-right ms-1 opacity-25 fs-base"></i>
                  </a>
                </div>
              </div>
              <!-- END New Customers -->
            </div>
          </div>
          <!-- END Overview -->
        </div>
        <!-- END Page Content -->
    </main>
    <!-- END Main Container -->
@endsection
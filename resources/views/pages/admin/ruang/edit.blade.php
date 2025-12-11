@extends('layouts.admin')

@section('title')
    Edit Type
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
                  Edit Type
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
              <h3 class="block-title">Form Edit</h3>
            </div>
            <div class="block-content block-content-full">
              <form action="{{route('ruang.update', $item->id)}}" method="POST" enctype="multipart/form-data">
                @method('PUT')
                @csrf
                <div class="row push">
                  <div class="col-lg-12 col-xl-12">
                    <div class="mb-4">
                      <label class="form-label" for="unit_id">Unit</label> <span style="color: red; margin-left: 10px">*Ubah bila diperlukan saja</span>
                      <select class="form-select" id="unit_id" name="unit_id">
                        <option value="{{$item->unit_id}}">{{$item->unit->nama}}</option>
                        @foreach ($unit as $ut)
                            <option value="{{$ut->id}}">{{$ut->nama}}</option>
                        @endforeach
                      </select>
                    </div>
                    <div class="mb-4">
                      <label class="form-label" for="nama">Lokasi</label>
                      <input type="text" class="form-control" id="nama" name="nama" placeholder="Lokasi" value="{{$item->nama}}">
                    </div>
                    <div>
                       <button type="submit" class="btn btn-primary">Add</button>
                       <a href="{{route('ruang.index')}}" class="btn btn-secondary">Cancel</a>
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
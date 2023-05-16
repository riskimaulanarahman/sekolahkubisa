@extends('layouts.default', ['sidebarSearch' => true])

@section('title', 'Soal')

@section('content')

    {{-- <h1 class="page-header">Module List</h1> --}}

    <!-- begin panel -->
    <div class="panel panel-info">
        <!-- begin panel-heading -->
        <div class="panel-heading">
            <h4 class="panel-title">Tambah Soal </h4>
            <div class="panel-heading-btn">
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
            
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning"
                    data-click="panel-collapse"><i class="fa fa-minus"></i></a>
            </div>
        </div>
        <!-- end panel-heading -->
        <!-- begin panel-body -->
        <div class="panel-body">
            {{-- <div id="grid-equipment" style="height: 640px; width:100%;"></div> --}}
            @if($errors->any())
                <div>
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        
            <form method="POST" action="{{ route('soales.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label" for="nama_module">Nama Module :</label>
                        <select class="form-control" name="module_id" id="module_id">
                            @foreach($modules as $module)
                                <option value="{{ $module->id }}">{{ $module->nama_module }}</option>
                            @endforeach
                        </select>
                        {{-- <input class="form-control" type="text" name="nama_module" id="nama_module" required> --}}
                    </div>
                </div>

                <div class="col-md-1">
                    <div class="mb-3">
                        <label class="form-label" for="no_soal">No. Soal :</label>
                        <input class="form-control" type="number" name="no_soal" id="no_soal">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label" for="soal">Isi Soal :</label>
                        {{-- <input class="form-control" type="number" name="no_soal" id="no_soal"> --}}
                        <textarea class="form-control" name="soal" id="soal" cols="30" rows="5"></textarea>
                    </div>
                </div>
                
                {{-- <div class="row form-group"> --}}
                    <div class="col-md-6">
                        <button class="btn btn-primary btn-block" type="submit">Simpan</button>
                        {{-- <a class="btn btn-danger btn-block" href="{{ url()->previous() }}">Kembali</a> --}}
                    </div>
                {{-- </div> --}}

            </form>
        </div>
        <!-- end panel-body -->
    </div>
    <!-- end panel -->
		
@endsection

@push('scripts')

@endpush

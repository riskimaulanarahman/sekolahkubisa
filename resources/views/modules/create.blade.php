@extends('layouts.default', ['sidebarSearch' => true])

@section('title', 'Module')

@section('content')

    {{-- <h1 class="page-header">Module List</h1> --}}

    <!-- begin panel -->
    <div class="panel panel-info">
        <!-- begin panel-heading -->
        <div class="panel-heading">
            <h4 class="panel-title">Module List </h4>
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
        
            <form method="POST" action="{{ route('materi.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="col-md-3">
                    <div class="mb-3">
                        <label class="form-label" for="nama_module">Nama Module :</label>
                        <input class="form-control" type="text" name="nama_module" id="nama_module" required>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="mb-3">
                        <label class="form-label" for="file">File :</label>
                        <input class="form-controle" type="file" name="file" id="file" required accept=".pdf">
                    </div>
                </div>
        
                <div class="col-md-3">
                    <button class="btn btn-primary btn-block" type="submit">Create</button>
                </div>
            </form>
        </div>
        <!-- end panel-body -->
    </div>
    <!-- end panel -->
		
@endsection

@push('scripts')

@endpush

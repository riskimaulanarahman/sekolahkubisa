@extends('layouts.default', ['sidebarSearch' => true])

@section('title', 'Biodata')

@section('content')
	<!-- begin row -->
		<!-- begin col-3 -->
        <!-- begin panel -->
        <div class="panel panel-warning">
            <!-- begin panel-heading -->
            <div class="panel-heading">
                <h4 class="panel-title">Biodata </h4>
                <div class="panel-heading-btn">
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning"
                        data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                </div>
            </div>
            <!-- end panel-heading -->
            <!-- begin panel-body -->
            <div class="panel-body">
                <img src="/assets/img/cover/biodata.jpg" alt="" width="100%">
            </div>
            <!-- end panel-body -->
        </div>
        <!-- end panel -->
		
	
@endsection


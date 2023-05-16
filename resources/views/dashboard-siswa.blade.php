@extends('layouts.default', ['sidebarSearch' => true])

@section('title', 'Dashboard')

@section('content')
	<!-- begin row -->
	<div class="widget widget-stats bg-orange">
		<h1>Welcome, {{Auth::user()->username}}</h1>
	</div>
	<hr>
	<div class="row">
		<!-- begin col-3 -->
		<div class="col-xl-3 col-md-6">
			<div class="widget widget-stats bg-indigo">
				<div class="stats-icon stats-icon-lg"><i class="fa fa-book"></i></div>
				<div class="stats-content">
					<div class="stats-number">Module</div>
					<div class="p-5" style="font-size: 15px; font-weight:bold;">Total : <span id="param1cat0">0</span> </div>
					<div class="stats-progress progress">
						<div class="progress-bar" style="width: 100%;"></div>
					</div>
					<div class="stats-desc">
						<a href="/modules"><button class="btn btn-detail-1 btn-warning"> <i class="fa fa-sign-in-alt"></i> Detail</button></a>
					</div>
				</div>
			</div>
		</div>
		
	</div>

@endsection

@push('scripts')
{{-- <script src="/assets/js/kap/dashboard.js?n=3"></script> --}}
<script>
	$.getJSON('countmodule',function(item){
		$('#param1cat0').text(item)
	})
</script>
@endpush

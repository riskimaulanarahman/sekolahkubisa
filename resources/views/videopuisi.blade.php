@extends('layouts.default', ['sidebarSearch' => true])

@section('title', 'Video Puisi')

@section('content')
	<!-- begin row -->
		<!-- begin col-3 -->
        <!-- begin panel -->
        <div class="panel panel-warning">
            <!-- begin panel-heading -->
            <div class="panel-heading">
                <h4 class="panel-title">Video Puisi </h4>
                <div class="panel-heading-btn">
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning"
                        data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                </div>
            </div>
            <!-- end panel-heading -->
            <!-- begin panel-body -->
            <div id="listpuisi" class="panel-body">
                {{-- <img src="/assets/img/cover/biodata.jpg" alt="" width="100%"> --}}
            </div>
            <!-- end panel-body -->
        </div>
        <!-- end panel -->

	
@endsection

@push('scripts')

<script>
    // alert('akak')
    var url = [
        "https://www.youtube.com/watch?v=2R_LQbJiPug",
        "https://www.youtube.com/watch?v=vENzISqL63g&t=16s",
        "https://www.youtube.com/watch?v=MLTOdvXz3Vg",
        "https://www.youtube.com/watch?v=Hqh8gp_k5Es",
        "https://youtu.be/TESE0mTXOg8",
        "https://youtu.be/gYdNdLuhKtY"
    ];
    var judul = [
        "1. Peri Sandi Huizche - Aku melihat Indonesia (Karya Bung Karno)",
        "2. Eci Safitri - Sebuah Jaket Berlumur Darah (Karya Taufiq Ismail)",
        "3. Peri Sandi Huizche - Mata Luka Sengkon Karta",
        "4. Fiersa Besari - Untukmu",
        "5. Laila Yunia Putri - Ibu (Karya D. Zawawi Imron)",
        "6. Musikalisasi Puisi SMAK Immanuel - Interlude Perjalanan (Karya Wayan Jengki Sunarta)"
    ]

    $(document).ready(function() {
    var listPuisiDiv = $("#listpuisi");
    listPuisiDiv.css("text-align", "center");

    for (var i = 0; i < url.length; i++) {
        var videoId = getVideoId(url[i]);
        var embedUrl = "https://www.youtube.com/embed/" + videoId;

        var iframe = $("<iframe></iframe>");
        iframe.attr({
            "width": "560",
            "height": "315",
            "src": embedUrl,
            "title": "YouTube video player",
            "frameborder": "0",
            "allow": "accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share",
            "allowfullscreen": ""
        });

        var judulPuisi = $("<h3></h3>").text(judul[i]);
        judulPuisi.css("text-align", "center");

        listPuisiDiv.append(judulPuisi);
        listPuisiDiv.append(iframe);
    }
});

function getVideoId(url) {
    var videoId = url.split("v=")[1];
    if (!videoId) {
        videoId = url.split("/").pop();
    }
    return videoId;
}
</script>

@endpush


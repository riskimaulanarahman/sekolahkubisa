@extends('layouts.default', ['sidebarSearch' => true])

@section('title', 'Module')

@push('css')
    <style>
        /* Gaya untuk pertanyaan */
.question {
  margin-bottom: 10px;
  font-weight: bold;
}

/* Gaya untuk jawaban */
.answer {
  display: flex;
  align-items: center;
  margin-bottom: 5px;
}

.answer input[type="radio"] {
  margin-right: 5px;
}

.answer label {
  font-weight: normal;
}

/* Gaya untuk nomor soal */
.question-number {
  margin-right: 5px;
  font-weight: bold;
}

    </style>
@endpush

@section('content')

    {{-- <h1 class="page-header">Module List</h1> --}}

    <!-- begin panel -->
    <div class="panel panel-info">
        <!-- begin panel-heading -->
        <div class="panel-heading">
            <h4 class="panel-title">List Module </h4>
            <div class="panel-heading-btn">
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
            
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning"
                    data-click="panel-collapse"><i class="fa fa-minus"></i></a>
            </div>
        </div>
        <!-- end panel-heading -->
        <!-- begin panel-body -->
        <div class="panel-body">
            @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        
        @if(Auth::user()->role == 'admin')
            <a href="{{ route('modules.create') }}" class="btn btn-primary mb-3">Tambah Module Baru</a>
        @endif
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Module</th>
                    <th>File</th>
                    <th>Tanggal</th>
                    <th>Actions</th>
                    {{-- <th>Hasil</th> --}}
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; ?>
                @foreach($modules as $module)
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td>{{ $module->nama_module }}</td>
                        <td><a href="upload/{{$module->pathfile}}" target="_blank"><button class="btn btn-info btn-xs"><i class="fa fa-eye"></i> Lihat</button></a></td>
                        <td>{{ $module->created_at }}</td>
                        @if(Auth::user()->role == 'admin')
                            <td>
                                <a href="{{ route('modules.edit', $module->id) }}" class="btn btn-primary">Ubah</a>
                                <form action="{{ route('modules.destroy', $module->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this module?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Hapus</button>
                                </form>
                                <button type="button" id="viewNilaiBtn" onclick="showViewnilai({{$module->id}})" class="btn btn-warning">Nilai</button>
                            </td>
                        @else
                            <td>
                                <button type="button" id="btnEvaluasi" onclick="showEvaluasi({{$module->id}})" class="btn btn-xs btn-danger"><i class="fa fa-pen"></i> Evaluasi</button>
                            </td>
                        @endif
                        {{-- <td>
                            10
                        </td> --}}
                    </tr>
                @endforeach
            </tbody>
        </table>
        </div>
        <!-- end panel-body -->
    </div>
    <!-- end panel -->

    <!-- Popup Modal -->
<div class="modal fade" id="evaluasiModal" tabindex="-1" role="dialog" aria-labelledby="evaluasiModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="evaluasiModalLabel">Form Evaluasi</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="evaluasiForm">
            {{ csrf_field() }}
            <input type="hidden" id="moduleid" name="module_id">
          </form>
          <hr>
          <button class="btn btn-primary" id="simpanBtn">Simpan</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="viewnilaiModal" tabindex="-1" role="dialog" aria-labelledby="viewnilaiModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="viewnilaiModalLabel">List Nilai Siswa</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" >
            <table class="table table-hover table-responsive" style="width: 100%">
                <thead>
                    <tr>
                        {{-- <th>No.</th> --}}
                        <th>Nama</th>
                        <th>Nilai</th>
                    </tr>
                </thead>
                <tbody id="listnilai">
                    {{-- <tr>
                        <td>1</td>
                        <td>a</td>
                        <td>90</td>
                    </tr> --}}
                </tbody>
            </table>
          {{-- <form id="evaluasiForm">
            {{ csrf_field() }}
            <input type="hidden" id="moduleid" name="module_id">
          </form> --}}
          
        </div>
      </div>
    </div>
  </div>
		
@endsection

@push('scripts')

<script>

    function showEvaluasi(moduleid) {

        $.getJSON('checknilai/'+moduleid,function(item){
            console.log(item)
            if(item > 0) {
                alert('Anda Sudah mengerjakan evaluasi materi ini. check nilai pada guru anda');
                return;
            } else {
                $('#evaluasiModal').modal('show');
            }
        })


        $('#moduleid').val(moduleid);

        $.getJSON('soalevaluasi/'+moduleid,function(data){

            $.each(data, function(index, item) {
                var question = item.soal;
                var answers = item.jawaban;

                // Tambahkan pertanyaan ke dalam form
                var questionElement = $('<div>').addClass('question');
                questionElement.append('<hr>');
                var questionNumber = $('<span>').addClass('question-number').text('Pertanyaan ' + item.no_soal + ': ');
                var questionContent = $('<div>').html(question);
                questionElement.append(questionNumber);
                questionElement.append(questionContent);
                $('#evaluasiForm').append(questionElement);

                // Tambahkan pilihan jawaban sebagai radio button
                $.each(answers, function(i, answer) {
                    var answerElement = $('<div>').addClass('answer');
                    var radioInput = $('<input type="radio" name="jawaban[' + item.no_soal + ']">')
                        .val(answer.benar)
                        .data('benar', answer.benar);
                    var label = $('<label>').text(answer.jawaban);

                    answerElement.append(radioInput);
                    answerElement.append(label);
                    $('#evaluasiForm').append(answerElement);
                });

            });


        })
    }

    $('#simpanBtn').click(function() {

        // var unansweredQuestions = $('input[type=radio]:not(:checked)').length;
        // if (unansweredQuestions > 0) {
        //     alert('Tolong jawab semua pertanyaan sebelum mengirim data.');
        //     return;
        // }
        var formData = $('#evaluasiForm').serialize();
        console.log(formData);
        // You can use the serialized data here for further processing
        $.ajax({
            url: '/jawabsiswa', // Ganti dengan URL yang sesuai untuk menyimpan data
            type: 'POST',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                // Tanggapan sukses dari server
                console.log('Data berhasil disimpan');
                location.reload();
            },
            error: function(xhr, status, error) {
                // Tanggapan error dari server
                console.log('Terjadi kesalahan saat menyimpan data: ' + error);
            }
        });
    });

    function showViewnilai(moduleid) {

        $.getJSON('shownilai/'+moduleid,function(item){
            console.log(item)
            $('#listnilai').html('')
            $.each(item,function(x,y){
                $('#listnilai').append('<tr><td>'+y.user.nama_lengkap+'</td><td>'+y.hasil+'</td></tr>')
            })
        })

        $('#viewnilaiModal').modal('show');

    }
    
</script>

@endpush

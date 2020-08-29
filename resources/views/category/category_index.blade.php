@extends('layouts.app')

@php $title = strtoupper('category'); @endphp

@section('content-header-left')
  <h3 class="content-header-title mb-2">{{ $title }}</h3>
@endsection

@section('content-header-right')
  <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
    @can('editor')
      <a class="btn btn-secondary" href="{{ route('category_create') }}">ADD {{ $title }}</a>
    @else 
      <button class="btn btn-secondary" data-toggle="tooltip" data-placement="bottom" title='Required "Editor" Permission'>ADD {{ $title }}</button>
    @endcan
  </div>
@endsection

@section('content')
  @if (session('status'))
  <div class="alert bg-success alert-icon-left alert-dismissible mb-2" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">×</span>
    </button>
    <strong>Success</strong> บันทึกเรียบร้อยแล้ว
  </div>
  @endif
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h4 class="card-title">{{ $title }}</h4>
        </div>
        <div class="table-responsive">
          <table class="table table-hover table-sm">
            <tr>
              <th class="text-center">#</th>
              <th class="text-center">Title</th>
              <th class="text-center">Slug</th>
              <th class="text-center">Action</th>
            </tr>
            @if (count($datas))
              @foreach ($datas as $item)
                <tr>
                  <td class="text-center align-baseline"><a href="{{ route('category_create', ['id' => $item->_id]) }}"> {{  $loop->iteration  }} </a></td>
                  <td class="text-left align-baseline"><a href="{{ route('category_create', ['id' => $item->_id]) }}">{{  $item->title  }}</a></td>
                  <td class="text-left align-baseline"><a href="{{ route('category_create', ['id' => $item->_id]) }}">{{  $item->slug  }}</a></td>
                  <td class="text-center align-baseline">
                    <button class="btn btn-danger" onclick="handleClickDel('{{(string)$item->_id}}','{{$item->title}}')">ลบ</button>
                  </td>
                </tr>
              @endforeach  
            @else
              <tr>
                <td class="text-center" colspan="99">
                  {{"ไม่มีข้อมูล"}}
                </td>
              </tr>   
            @endif
          </table>
        </div> 
      </div>
    </div>
  </div>
@endsection

@section('script')
<script>
  function handleClickDel(category_id, category_name) {
    url = "{{ route('category_delete') }}"
    postData = {
      _token: "{{ csrf_token() }}",
      id: category_id,
    }
    swal.fire({
      title: "คุณต้องการลบ " +category_name+ " ใช่หรือไม่ ?",
      icon: "warning",
      showCancelButton: true,
      buttons: {
        cancel: {
          text: "ยกเลิก",
          value: null,
          visible: true,
          className: "",
          closeModal: true,
        },
        confirm: {
          text: "ลบ",
          value: true,
          visible: true,
          className: "",
          closeModal: false
        }
      }
    }).then(isConfirm => {
      if (isConfirm.value) {
        $.get(url, postData, function(data, status){
          if(data.status==400) {
            var message = data.message
            courses = data.course.join()
            swal.fire(message + courses)
          } else {
            swal.fire('ดำนินการเรียบร้อย')
            .then(
              location.reload()
            )
          }
        });
      } else {

      }
    });
  }
</script>
@endsection

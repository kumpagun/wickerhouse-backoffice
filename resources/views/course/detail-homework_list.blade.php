<div id="homework" class=" col-md-10 col-xl-8 mb-4">
  <div class="card px-1 py-1 m-0">
    <div class="card-header border-0 pb-0">
      <h4 class="card-title">HOMEWORK</h4>
      @if(empty($homework))
      <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
      <div class="heading-elements">
        <ul class="list-inline mb-0">
          <li>
            @can('editor')
              <a href="{{ route('homework_create', ['course_id' => $data->_id]) }}">
                <button class="btn btn-round btn-secondary"><i class="ft-edit"></i> เพิ่มการบ้าน</button>
              </a>
            @else
              <a><button class="btn btn-round btn-secondary" data-toggle="tooltip" data-placement="bottom" title='Required "Editor" Permission'><i class="ft-edit"></i> เพิ่มการบ้าน</button></a>
            @endcan
          </li>
        </ul>
      </div>
      @endif
      <h6 class="card-subtitle line-on-side text-muted text-center font-small-3 pt-2">
        <span>HOMEWORK INFO</span>
      </h6>
    </div>
    <div class="card-content">
      <div class="card-body pt-0">
        @if(!empty($homework))
        <table class="table table-bordered">
          <tr class="bg-blue-grey bg-lighten-5">
            <th class="text-center">การบ้าน</th>
            <th class="text-center">Action</th>
          </tr>
          <tr>
            <td class="align-baseline text-left"><a href="{{ route('homework_create', ['course_id'=>$data->_id ,'id' => $homework->_id]) }}">แก้ไขการบ้าน</a></td>
            <td class="align-baseline text-center">
              @can('editor')
                <a href="#{{$homework->_id}}" onclick="handleDeleteHomework('{{$homework->_id}}')">
                  <button class="btn btn-danger"><i class="ft-close"></i> ลบ</button>
                </a>
              @else
                <a><button class="btn btn-danger" data-toggle="tooltip" data-placement="bottom" title='Required "Editor" Permission'><i class="ft-close"></i> ลบ</button></a>
              @endcan
            </td>
          </tr>
        </table>
        @else 
        <table class="table table-bordered">
          <tr>
            <td class="align-baseline text-center">ยังไม่มีการบ้าน</td>
          </tr>
        </table>
        @endif
      </div>
    </div>
  </div>
</div>

@push('script')
  <script>
  function handleDeleteHomework(id) {
    url = "{{ route('homework_delete') }}/"+id
    swal({
      title: "คุณต้องการลบคำถามใช่หรือไม่ ?",
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
      if (isConfirm) {
        window.location = url
      } 
    });
  }
  </script>
@endpush
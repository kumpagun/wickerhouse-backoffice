<div id="examination" class="col-12 col-md-10 col-xl-8 mb-4">
  <div class="card px-1 py-1 m-0">
    <div class="card-header border-0 pb-0">
      <h4 class="card-title">EXAMINATION</h4>
      <h6 class="card-subtitle line-on-side text-muted text-center font-small-3 pt-2">
        <span>EXAMINATION INFO</span>
      </h6>
    </div>
    <div class="card-content">
      <div class="card-body pt-0">
        @if(count($examination) > 0) 
        <table class="table table-bordered">
          <tr>
            <th class="text-center">Type</th>
            <th class="text-center">Total</th>
            <th class="text-center">Action</th>
          </tr>
          @foreach ($examination as $item)
            <tr>
              <td class="align-baseline text-center">
                <a href="{{ route('examination_index',['id' => $item->_id]) }}">{{ implode(',',$item->type) }}</a>
              </td>
              <td class="align-baseline text-center">{{ CourseClass::get_exam_total($item->_id) }}</td>
              <td class="align-baseline text-center">
                <a href="#{{$item->_id}}" onclick="handleDelete('{{$item->_id}}')">
                  <button class="btn btn-danger"><i class="ft-close"></i> ลบ</button>
                </a>
              </td>
            </tr>
          @endforeach
        </table>
        @endif
        @if(!empty($examination_type)) 
          <button type="button" data-repeater-create class="btn btn-secondary" data-toggle="modal" data-target="#modalExamination">
            <i class="ft-plus"></i> เพิ่มแบบทดสอบ
          </button>
        @endif
      </div>
    </div>
  </div>
</div>

<div class="modal fade text-left" id="modalExamination" tabindex="-1" role="dialog" aria-labelledby="modalExaminationLabel" style="display: none;" aria-hidden="true">
  <div class="modal-dialog" role="document">
  <div class="modal-content">
    <div class="modal-header">
      <h4 class="modal-title" id="modalExaminationLabel">เพิ่มแบบทดสอบ</h4>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
      </button>
    </div>
    <form class="form-horizontal" action="{{ route('examination_group_store') }}" method="POST">
      @csrf
      <input type="hidden" name="course_id" value="{{ $data->_id }}" />
      <div class="row justify-content-center">
        <div class="col-11 py-2">
          <fieldset class="form-group @if($errors->course->has('require_course')) danger @endif">
            <label for="user-name"> ประเภทแบบทดสอบ </label>
            <select class="select2 form-control" name="type">
              @foreach ($examination_type as $item)
                <option value="{{ $item }}">{{ $item }}</option>
              @endforeach
            </select>
          </fieldset>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-outline-primary">Save changes</button>
      </div>
    </form>
  </div>
  </div>
</div>

@push('script')
<script>
  function handleDelete(id) {
    url = "{{ route('examination_group_delete') }}/"+id
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
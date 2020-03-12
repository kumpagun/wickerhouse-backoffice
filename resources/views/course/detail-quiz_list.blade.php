<div id="quiz" class="col-12 col-md-10 col-xl-8 mb-4">
  <div class="card px-1 py-1 m-0">
    <div class="card-header border-0 pb-0">
      <h4 class="card-title">แบบทดสอบท้าย ep</h4>
      <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
      <div class="heading-elements">
        <ul class="list-inline mb-0">
          <li>
            @can('editor')
              <a><button class="btn btn-round btn-secondary" data-toggle="modal" data-target="#modalQuiz"><i class="ft-edit"></i> เพิ่มแบบทดสอบท้าย ep</button></a>
            @else
              <a><button class="btn btn-round btn-secondary" data-toggle="tooltip" data-placement="bottom" title='Required "Editor" Permission'><i class="ft-edit"></i> เพิ่มแบบทดสอบท้าย ep</button></a>
            @endcan
          </li>
        </ul>
      </div>
      <h6 class="card-subtitle line-on-side text-muted text-center font-small-3 pt-2">
        <span>รายละเอียดแบบทดสอบท้าย EP</span>
      </h6>
    </div>
    <div class="card-content">
      <div class="card-body pt-0">
        @if(count($quiz) > 0) 
        <table class="table table-bordered">
          <tr class="bg-blue-grey bg-lighten-5">
            <th class="text-center">Episode</th>
            <th class="text-center">Total</th>
            <th class="text-center">Action</th>
          </tr>
          @foreach ($quiz as $item)
            <tr>
              <td class="align-baseline text-left">
                <a href="{{ route('quiz_index',['id' => $item->_id]) }}">{{ CourseClass::get_episode_name($item->episode_id) }}</a>
              </td>
              <td class="align-baseline text-center">{{ CourseClass::get_total_quiz($item->episode_id) }}</td>
              <td class="align-baseline text-center">
                @can('editor')
                  <a href="#{{$item->_id}}" onclick="handleDelete('{{$item->_id}}')">
                    <button class="btn btn-danger"><i class="ft-close"></i> ลบ</button>
                  </a>
                @else
                  <a><button class="btn btn-danger" data-toggle="tooltip" data-placement="bottom" title='Required "Editor" Permission'><i class="ft-close"></i> ลบ</button></a>
                @endcan
              </td>
            </tr>
          @endforeach
        </table>
        @else
        <table class="table table-bordered">
          <tr>
            <td class="align-baseline text-center">ยังไม่มีแบบทดสอบท้าย EP</td>
          </tr>
        </table>
        @endif
      </div>
    </div>
  </div>
</div>

<div class="modal fade text-left" id="modalQuiz" tabindex="-1" role="dialog" aria-labelledby="modalQuizLabel" style="display: none;" aria-hidden="true">
  <div class="modal-dialog" role="document">
  <div class="modal-content">
    <div class="modal-header">
      <h4 class="modal-title" id="modalQuizLabel">เพิ่มแบบทดสอบท้าย EP</h4>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
      </button>
    </div>
    <form class="form-horizontal" action="{{ route('quiz_group_store') }}" method="POST">
      @csrf
      <input type="hidden" name="course_id" value="{{ $data->_id }}" />
      <div class="row justify-content-center">
        <div class="col-11 pt-2">
          <fieldset class="form-group @if($errors->course->has('require_course')) danger @endif">
            <label for="user-name"> แบบทดสอบท้าย EP </label>
            <select class="select2 form-control" name="episode_id">
              @foreach ($episode_not_selected as $item)
                <option value="{{ $item->_id }}">{{ $item->title }}</option>
              @endforeach
            </select>
          </fieldset>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">ปิด</button>
        <button type="submit" class="btn btn-outline-primary">บันทึก</button>
      </div>
    </form>
  </div>
  </div>
</div>

@push('script')
  <script>
    function handleDelete(id) {
      url = "{{ route('quiz_group_delete') }}/"+id
      swal.fire({
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
        if (isConfirm.value) {
          window.location = url
        } 
      });
    }
  </script>
@endpush
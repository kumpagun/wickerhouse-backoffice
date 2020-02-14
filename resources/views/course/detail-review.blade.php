<div id="review" class="col-12 col-md-10 col-xl-8 mb-4">
  <div class="card px-1 py-1 m-0">
    <div class="card-header border-0 pb-0">
      <h4 class="card-title">แบบประเมินหลักสูตรออนไลน์</h4>
      <div class="heading-elements">
        <ul class="list-inline mb-0">
          <li>
            @can('editor')
              <a data-toggle="modal" data-target="#addReview">
                <button class="btn btn-round btn-secondary"><i class="ft-plus"></i> เพิ่ม</button>
              </a>
            @else
              <a><button class="btn btn-round btn-secondary" data-toggle="tooltip" data-placement="bottom" title='Required "Editor" Permission'><i class="ft-plus"></i> เพิ่ม</button></a>
            @endcan
          </li>
        </ul>
      </div>
      <h6 class="card-subtitle line-on-side text-muted text-center font-small-3 pt-2">
        <span>รายละเอียดแบบประเมินหลักสูตรออนไลน์</span>
      </h6>
    </div>
    <div class="card-content">
      <div class="card-body pt-0">
        <table class="table table-bordered">
          <tr class="bg-blue-grey bg-lighten-5">
            <th class="text-center">หัวข้อแบบประเมิน</th>
            <th class="text-center">Action</th>
          </tr>
            @if(!empty($review_group))
              @foreach ($review_group as $item)
                <tr>
                  <td class="align-baseline text-left"><a href="{{ route('review_index', ['review_group_id' => $item->_id]) }}">{{ $item->title }}</a></td>
                  <td class="align-baseline text-center">
                    @can('editor')
                      <a href="#review" onclick="handleDeleteReviewUrl('{{ $item->_id }}')">
                        <button class="btn btn-danger"><i class="ft-close"></i> ลบ</button>
                      </a>
                    @else
                      <a><button class="btn btn-danger" data-toggle="tooltip" data-placement="bottom" title='Required "Editor" Permission'><i class="ft-close"></i> ลบ</button></a>
                    @endcan
                  </td>
                </tr>
              @endforeach
            @else
              <tr>
                <td class="text-center" colspan="99">ไม่มีข้อมูล</td>
              </tr>
            @endif
        </table>
      </div>
    </div>
  </div>
</div>

<div class="modal fade text-left" id="addReview" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" style="display: none;" aria-hidden="true">
  <div class="modal-dialog" role="document">
  <div class="modal-content">
    <div class="modal-header">
      <h4 class="modal-title" id="myModalLabel1">แบบประเมินหลักสูตรออนไลน์</h4>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
      </button>
    </div>
    <form class="form-horizontal" action="{{ route('review_group_store') }}" method="POST">
      @csrf
      <input type="hidden" name="course_id" value="{{ $data->_id }}" />
      <input type="hidden" name="status" value=1 />
      <div class="modal-body">
        <fieldset class="form-group floating-label-form-group @if($errors->course->has('title')) danger @endif">
          <label for="user-name">หัวข้อแบบประเมิน</label>
          <input type="text" name="title" class="form-control" placeholder="Title" required>
        </fieldset>
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
  function handleDeleteReviewUrl(review_group_id) {
    url = "{{ route('review_group_delete') }}/"+review_group_id
    swal({
      title: "คุณต้องการลบ Review URL ใช่หรือไม่ ?",
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
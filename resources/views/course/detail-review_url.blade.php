<div id="review_url" class="col-12 col-md-10 col-xl-8 mb-4">
  <div class="card px-1 py-1 m-0">
    <div class="card-header border-0 pb-0">
      <h4 class="card-title">REVIEW URL</h4>
      <div class="heading-elements">
        <ul class="list-inline mb-0">
          <li>
            @if(empty($data->review_url)) 
              @can('editor')
                <a data-toggle="modal" data-target="#addReviewUrl">
                  <button class="btn btn-round btn-secondary"><i class="ft-plus"></i> เพิ่ม</button>
                </a>
              @else
                <a><button class="btn btn-round btn-secondary" data-toggle="tooltip" data-placement="bottom" title='Required "Editor" Permission'><i class="ft-plus"></i> เพิ่ม</button></a>
              @endcan
            @endif
          </li>
        </ul>
      </div>
      <h6 class="card-subtitle line-on-side text-muted text-center font-small-3 pt-2">
        <span>REVIEW URL INFO</span>
      </h6>
    </div>
    <div class="card-content">
      <div class="card-body pt-0">
        <table class="table table-bordered">
          <tr class="bg-blue-grey bg-lighten-5">
            <th class="text-center">URL</th>
            <th class="text-center">Action</th>
          </tr>
          <tr>
            @if(!empty($data->review_url)) 
              <td class="align-baseline text-left"><a href="#review_url" data-toggle="modal" data-target="#addReviewUrl">{{ $data->review_url }}</a></td>
              <td class="align-baseline text-center">
                @can('editor')
                  <a href="#review_url" onclick="handleDeleteReviewUrl()">
                    <button class="btn btn-danger"><i class="ft-close"></i> ลบ</button>
                  </a>
                @else
                  <a><button class="btn btn-danger" data-toggle="tooltip" data-placement="bottom" title='Required "Editor" Permission'><i class="ft-close"></i> ลบ</button></a>
                @endcan
              </td>
            @else
              <td colspan="99">ไม่มี URL</td>
            @endif
          </tr>
        </table>
      </div>
    </div>
  </div>
</div>

<div class="modal fade text-left" id="addReviewUrl" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" style="display: none;" aria-hidden="true">
  <div class="modal-dialog" role="document">
  <div class="modal-content">
    <div class="modal-header">
      <h4 class="modal-title" id="myModalLabel1">REVIEW URL</h4>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
      </button>
    </div>
    <form class="form-horizontal" action="{{ route('course_review_url_store') }}" method="POST">
      @csrf
      <input type="hidden" name="course_id" value="{{ $data->_id }}" />
      <div class="modal-body">
        <fieldset class="form-group floating-label-form-group @if($errors->course->has('title')) danger @endif">
          <label for="user-name">URL</label>
        <input type="text" name="review_url" class="form-control" placeholder="Title" value="@if(!empty($data->review_url)) {{$data->review_url}} @endif" required>
        </fieldset>
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
  function handleDeleteReviewUrl() {
    course_id = "{{ $data->_id }}"
    url = "{{ route('course_review_url_delete') }}/"+course_id
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
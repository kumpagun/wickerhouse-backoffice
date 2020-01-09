<div class="col-12 col-md-10 col-xl-8 mb-4">
  <div id="episodelist" class="card px-1 py-1 m-0">
    <div class="card-header border-0 pb-0">
      <h4 class="card-title">EPISODE LIST</h4>
      <div class="heading-elements">
        <ul class="list-inline mb-0">
          <li>
            <a class="p-0" href="{{ route('episode_create', ['course_id' => $data->_id]) }}">
              <button class="btn btn-round btn-secondary"><i class="ft-plus"></i> เพิ่ม</button>
            </a>
          </li>
        </ul>
      </div>
      <h6 class="card-subtitle line-on-side text-muted text-center font-small-3 pt-2">
        <span>EPISODE INFO</span>
      </h6>
    </div>
    <div class="card-content">
      <div class="card-body pt-0">
        @if(count($episode) > 0) 
        <table class="table table-bordered mb-2">
          <tr>
            <th class="text-center">Title</th>
            <th class="text-center">Transcode Status</th>
            <th class="text-center">Action</th>
          </tr>
          @foreach ($episode as $item)
            <tr>
              <td class="align-baseline text-left"><a href="{{ route('episode_create', ['course_id' => $data->_id ,'id' => $item->_id]) }}">{{ $item->title }}</a></td>
              <td class="align-baseline text-center @if($item->transcode_status=='done') text-success @else @endif">{{ $item->transcode_status }}</td>
              <td class="align-baseline text-center">
                <a href="#{{$item->_id}}" onclick="handleDeleteEp('{{$item->_id}}')">
                  <button class="btn btn-danger"><i class="ft-close"></i> ลบ</button>
                </a>
              </td>
            </tr>
          @endforeach
        </table>
        @endif
      </div>
    </div>
  </div>
</div>

@push('script')
  <script>
    function handleDeleteEp(id) {
      url = "{{ route('episode_delete') }}/"+id
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
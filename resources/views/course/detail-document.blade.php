<div class="col-12 col-md-10 col-xl-8 mb-4">
  <div id="document" class="card px-1 py-1 m-0">
    <div class="card-header border-0 pb-0">
      <h4 class="card-title">DOCUMENT LIST</h4>
      <div class="heading-elements">
        <ul class="list-inline mb-0">
          <li>
            @can('editor')
              <a href="#" data-toggle="modal" data-target="#addDocument">
                <button class="btn btn-round btn-secondary"><i class="ft-plus"></i> เพิ่ม</button>
              </a>
            @else
              <button type="button" class="btn btn-round btn-secondary" data-toggle="tooltip" data-placement="bottom" title='Required "Editor" Permission'><i class="ft-plus"></i> เพิ่ม</button>
            @endcan
          </li>
        </ul>
      </div>
      <h6 class="card-subtitle line-on-side text-muted text-center font-small-3 pt-2">
        <span>DOCUMENT INFO</span>
      </h6>
    </div>
    <div class="card-content">
      <div class="card-body pt-0">
        @if(!empty($document) && count($document) > 0) 
        <table class="table table-bordered mb-2">
          <tr class="bg-blue-grey bg-lighten-5">
            <th class="text-center" colspan="3">ZIP file</th>
          </tr>
          <tr>
            <th class="text-center">Title</th>
            <th class="text-center">File</th>
            <th class="text-center">Action</th>
          </tr>
          @if(!empty($document['document_path']))
            <tr>
              <td class="align-baseline text-left">{{ $document['title'] }}</td>
              <td class="align-baseline text-center"><a target="_blank" download="{{ $document['title'] }}" href="{{ Storage::url('public/'.$document['document_path']) }}">Download</a></td>
              <td class="align-baseline text-center">
                @can('editor')
                  <a onclick="handleDeleteDocumentPath('{{$document['course_id']}}')">
                    <button class="btn btn-danger"><i class="ft-close"></i> ลบ</button>
                  </a>
                @else
                  <button class="btn btn-danger" data-toggle="tooltip" data-placement="bottom" title='Required "Editor" Permission'><i class="ft-close"></i> ลบ</button>
                @endcan
              </td>
            </tr>
          @else 
            <tr><td colspan="3" class="align-baseline text-center blue-grey lighten-2">ไม่มีไฟล์</td></tr>
          @endif
            <tr class="bg-blue-grey bg-lighten-5">
              <th class="text-center" colspan="3">PDF file</th>
            </tr>
            <tr>
              <th class="text-center">Title</th>
              <th class="text-center">File</th>
              <th class="text-center">Action</th>
            </tr>
            @if(!empty($document['document_paths']))
              @foreach ($document['document_paths'] as $item)
                <tr>
                  <td class="align-baseline text-left">{{ $item['title'] }}</td>
                  <td class="align-baseline text-center"><a target="_blank" download="{{$item['title']}}" href="{{ Storage::url('public/'.$item['path']) }}">Download</a></td>
                  <td class="align-baseline text-center">
                    @can('editor')
                    <a onclick="handleDeleteDocumentPaths('{{$document['course_id']}}','{{$item['code']}}')">
                      <button class="btn btn-danger"><i class="ft-close"></i> ลบ</button>
                    </a>
                    @else
                      <button class="btn btn-danger" data-toggle="tooltip" data-placement="bottom" title='Required "Editor" Permission'><i class="ft-close"></i> ลบ</button>
                    @endcan
                  </td>
                </tr>
              @endforeach
            @else 
              <tr><td colspan="3" class="align-baseline text-center blue-grey lighten-2">ไม่มีไฟล์</td></tr>
            @endif
        </table>
        @else
        <table class="table table-bordered">
          <tr>
            <td class="align-baseline text-center">ยังไม่มีไฟล์</td>
          </tr>
        </table>
        @endif
      </div>
    </div>
  </div>
</div>

<div class="modal fade text-left" id="addDocument" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" style="display: none;" aria-hidden="true">
  <div class="modal-dialog" role="document">
  <div class="modal-content">
    <div class="modal-header">
      <h4 class="modal-title" id="myModalLabel1">EPISODE GROUP</h4>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
      </button>
    </div>
    <form class="form-horizontal" action="{{ route('document_store') }}" method="POST" enctype="multipart/form-data">
      @csrf
      <input type="hidden" name="course_id" value="{{ $data->_id }}" />
      <div class="modal-body">
        <fieldset class="form-group floating-label-form-group @if($errors->document->has('title')) danger @endif">
          <label for="user-name">Title</label>
          <input type="text" name="title" class="form-control" placeholder="Title" required >
        </fieldset>
        <div class="row">
          <div class="col-12 mb-2">
            <label for="user-status">ประเภทไฟล์</label>
            <fieldset>
              <input type="radio" name="file_type" id="input-radio-zip" value="zip" required>
              <label for="input-radio-zip">ZIP</label>
            </fieldset>
            <fieldset>
              <input type="radio" name="file_type" id="input-radio-pdf" value="pdf" required>
              <label for="input-radio-pdf">PDF</label>
            </fieldset>
          </div>
          <div class="col-12">
            <fieldset id="field-zip" class="form-group">
              <label for="basicInputFileZip">Document file</label> * ประเภทไฟล์ zip
              <input id="input-zip" type="file" name="file_zip" class="form-control-file" id="basicInputFileZip" accept="application/zip">
            </fieldset>
            <fieldset id="field-pdf" class="form-group">
              <label for="basicInputFilePdf">Document file</label> * ประเภทไฟล์ pdf
              <input id="input-pdf" type="file" name="file_pdf" class="form-control-file" id="basicInputFilePdf" accept="application/pdf">
            </fieldset>
          </div>
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
    $('document').ready(function(){ 
      $("#field-zip").hide();
      $("#field-pdf").hide();
      $('input[type=radio][name=file_type]').change(function() {
        if (this.value == 'zip') {
          $("#field-zip").show();
          $("#field-pdf").hide();
          $("#input-zip").prop('required',true);
          $("#input-pdf").prop('required',false);
        }
        else if (this.value == 'pdf') {
          $("#field-zip").hide();
          $("#field-pdf").show();
          $("#input-zip").prop('required',false);
          $("#input-pdf").prop('required',true);
        }
      });   
    });

    function handleDeleteDocumentPath(course_id) {
      url = "{{ route('document_zip_delete') }}/"+course_id
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

    function handleDeleteDocumentPaths(course_id,code) {
      url = "{{ route('document_pdf_delete') }}/"+course_id+"/"+code
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
<div id="homework" class=" col-md-10 col-xl-8 mb-4">
  <div class="card px-1 py-1 m-0">
    <div class="card-header border-0 pb-0">
      <h4 class="card-title">HOMEWORK</h4>
      <h6 class="card-subtitle line-on-side text-muted text-center font-small-3 pt-2">
        <span>HOMEWORK INFO</span>
      </h6>
    </div>
    <div class="card-content">
      <div class="card-body pt-0">
        @if(empty($homework))
        <a href="{{ route('homework_by_course_create', ['id' => $data->_id]) }}">
          <button type="button" data-repeater-create class="btn btn-secondary">
            <i class="ft-plus"></i> เพิ่มการบ้าน
          </button>
        </a>
        @else
        <a href="{{ route('homework_create', ['id' => $homework->_id]) }}">
          <p>แก้ไขการบ้าน</p>
        </a>
        @endif
      </div>
    </div>
  </div>
</div>
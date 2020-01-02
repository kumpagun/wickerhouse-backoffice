<div class="col-12 col-md-8 mb-4">
  <div id="episodelist" class="card px-1 py-1 m-0">
    <div class="card-header border-0 pb-0">
      <h4 class="card-title">EPISODE LIST</h4>
      <h6 class="card-subtitle line-on-side text-muted text-center font-small-3 pt-2">
        <span>EPISODE INFO</span>
      </h6>
    </div>
    <div class="card-content">
      <div class="card-body pt-0">
        <a href="{{ route('episode_create', ['course_id' => $data->_id]) }}">
          <button type="button" data-repeater-create class="btn btn-secondary">
            <i class="ft-plus"></i> เพิ่ม Episode
          </button>
        </a>
      </div>
    </div>
  </div>
</div>
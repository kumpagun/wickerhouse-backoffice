<div class="col-12">
  <h6 class="card-subtitle line-on-side text-muted text-center font-small-3 pt-2">
    <span>ชื่อหลักสูตร</span>
  </h6>
</div>
<div class="col-12">
  <div class="form-group">
    <p>รหัสสีตัวอักษร (เช่น 000000) <span class="text-danger">*</span></p>
    <div class="controls">
      <input type="text" name="course_color" class="form-control" maxlength="6" minlength="6" value="{{ old('course_color', $data->course_color) }}" required>
    </div>
    </p>
  </div>
</div>
<div class="col-12">
  <div class="form-group">
    <p>ขนาดตัวอักษร (ขนาดตัวอักษรที่แนะนำ 1) <span class="text-danger">*</span></p>
    <div class="controls">
      <input type="number" step="0.1" name="course_size" class="form-control" value="{{ old('course_size', $data->course_size) }}" required>
    </div>
    </p>
  </div>
</div>
<div class="col-12">
  <div class="form-group">
    <p>ส่วนสูงของชื่อ (ส่วนสูงของชื่อที่แนะนำ 65) <span class="text-danger">*</span></p>
    <div class="controls">
      <input type="text" name="course_position" class="form-control" value="{{ old('course_position', $data->course_position) }}" required>
    </div>
    </p>
  </div>
</div>

<div class="col-12">
  <h6 class="card-subtitle line-on-side text-muted text-center font-small-3 pt-2">
    <span>ชื่อ - นามสกุล</span>
  </h6>
</div>
<div class="col-12">
  <div class="form-group">
    <p>รหัสสีตัวอักษร (เช่น 000000) <span class="text-danger">*</span></p>
    <div class="controls">
      <input type="text" name="font_color" class="form-control" maxlength="6" minlength="6" value="{{ old('font_color', $data->font_color) }}" required>
    </div>
    </p>
  </div>
</div>
<div class="col-12">
  <div class="form-group">
    <p>ขนาดตัวอักษร (ขนาดตัวอักษรที่แนะนำ 3.5) <span class="text-danger">*</span></p>
    <div class="controls">
      <input type="number" step="0.1" name="font_size" class="form-control" value="{{ old('font_size', $data->font_size) }}" required>
    </div>
    </p>
  </div>
</div>
<div class="col-12">
  <div class="form-group">
    <p>ส่วนสูงของชื่อ (ส่วนสูงของชื่อที่แนะนำ 50) <span class="text-danger">*</span></p>
    <div class="controls">
      <input type="text" name="font_position" class="form-control" value="{{ old('font_position', $data->font_position) }}" required>
    </div>
    </p>
  </div>
</div>
<div class="col-12">
  <div class="form-group">
    <p>ลักษณะของชื่อ-นามสกุล <span class="text-danger">*</span></p>
    <div class="controls">
      <select class="select2 form-control" name="font_newline" required>
        <option value="">กรุณาลักษณะของชื่อ-นามสกุล</option>
        <option value="true" @if($data->font_newline) selected @endif>ขึ้นบรรทัดใหม่</option>
        <option value="false" @if(!$data->font_newline) selected @endif>อยู่บรรทัดเดียวกัน</option>
      </select>
    </div>
    </p>
  </div>
</div>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <title>Document</title>
  <style>
    @font-face {
      font-family: 'THSarabunNew';
      font-style: normal;
      font-weight: normal;
      src: url("{{ public_path('fonts/THSarabunNew.ttf') }}") format('truetype');
    }
    @font-face {
      font-family: 'THSarabunNew';
      font-style: normal;
      font-weight: bold;
      src: url("{{ public_path('fonts/THSarabunNew Bold.ttf') }}") format('truetype');
    }
    @font-face {
      font-family: 'THSarabunNew';
      font-style: italic;
      font-weight: normal;
      src: url("{{ public_path('fonts/THSarabunNew Italic.ttf') }}") format('truetype');
    }
    @font-face {
      font-family: 'THSarabunNew';
      font-style: italic;
      font-weight: bold;
      src: url("{{ public_path('fonts/THSarabunNew BoldItalic.ttf') }}") format('truetype');
    }
    body {
      font-family: "THSarabunNew";
      font-size: 18px;
      line-height: 18px;
      border: 5px double #59A6BA;
      padding: 20px;
    }
    .head {
      font-size: 22px;
      font-weight: 700;
    }
    .text-center {
      text-align: center;
    }
    .text-left {
      text-align: left;
    }
    .p-0 {
      padding: 0 !important;
    }
    .mb-0 {
      margin-bottom: 0 !important;
    }
    .mb-1 {
      margin-bottom: 5px !important;
    }
    .mt-0 {
      margin-top: 0 !important;
    }
    .mr-1 {
      margin-right: 5px !important;
    }
    .ml-1 {
      margin-left: 5px !important;
    }
    .logo {
      text-align: center;
      margin-bottom: 0 !important;
      padding: 0 !important;
    }
    .logo > img {
      width: 60px;
    }
    .info {
      padding: 0;
      text-align: center;
      margin-top: 10px;
      margin-bottom: 25px;
    }
    .info-detail {
      /* float: left; */
    }
    .training {
      padding: 20px;
      padding-top: 10px;
      margin-top: 0.5em;
      border: 1px;
      border-style: dashed;
    }
    .training-title {
      text-align: center;
      font-weight: 700;
    }
    .sign {
      text-align: right;
      position: absolute;
      width: 300px;
      right: 0;
      bottom: 0;
    }
    .sign > img {
      width: 300px;
    }

    .table {
      width: 100% !important;
    }
    .table-title {
      width: 80%;
    }
    .table-date {
      width: 20%;
    }
  </style>
</head>
<body>
  <div class="logo">
    <img src="{{ public_path('images/only_jaslogo.png') }}" alt="">
  </div>
  
  <p class="head text-center mb-0 p-0">กลุ่มบริษัทจัสมิน อินเตอร์เนชั่นแนล</p>
  <p class="head text-center mt-0 mb-1 p-0">หนังสือรับรองการฝึกอบรม</p>
  <div class="info">
    <div class="info-detail">
      <span class="ml-1 mr-1"><strong>ชื่อ-นามสกุล</strong> {{ $member->fullname }}</span>
      <span class="ml-1 mr-1"><strong>ตำแหน่ง</strong> {{ $member->position }}</span>
      <span class="ml-1 mr-1"><strong>บริษัท</strong> {{ $member->company }}</span>
    </div>
  </div>
  <div class="training">
    <div class="training-title">หลักสูตรฝึกอบรมภายใน</div>
    <table class="table">
      <tr>
        <th class="text-left">ชื่อหลักสูตร</th>
        <th class="text-center">วัน/เดือน/ปี</th>
      </tr>
      @foreach ($training_end as $training)
        <tr>
          <td class="table-title">{{ $training['title'] }}</td>
          <td class="table-date text-center">{{ $training['created_at'] }}</td>
        </tr>
      @endforeach
    </table>
  </div>
  <div class="sign">
    <p class="text-center">.................................................................</p>
    <p class="mb-0 text-center">
      (นายธีรศักดิ์ ธาราวร) <br/>
      <strong>หัวหน้าฝ่ายฝึกอบรมและพัฒนาบุคลากร</strong>
    </p>
  </div>
</body>
</html>
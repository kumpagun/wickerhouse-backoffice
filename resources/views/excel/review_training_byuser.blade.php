<style>
  .breakword {
    word-wrap: break-word;
  }
</style>
<table class="table table-hover table-sm">
  <tr>
    <th class="breakword" rowspan="3">ประทับเวลา</th>
    <th class="breakword" rowspan="3">บริษัท</th>
    <th class="breakword" rowspan="3">ชื่อ-สกุล</th>
    <th class="breakword" rowspan="3">รหัสพนักงาน</th>
    <th class="breakword" rowspan="3">ตำแหน่ง</th>
    
    @foreach ($reviews_arr as $review_group => $title)
      <th class="breakword" colspan="{{ $total_reviews_arr[$review_group] }}">{{ $review_group }}</th>
    @endforeach
  </tr>
  <tr>
    @foreach ($reviews_arr as $review_group => $title_data)
      @foreach ($title_data as $title => $questions)
        <th class="breakword" colspan="{{ count($questions) }}" @if($questions[0]=='rowspan') rowspan="2" @endif>{!! $title !!}</th>
      @endforeach
    @endforeach
  </tr>
  <tr>
    @foreach ($reviews_arr as $review_group => $title_data)
      @foreach ($title_data as $title => $questions)
        @foreach ($questions as $question)
          @if($question!='rowspan')
            <th class="breakword">{{ $question }}</th>
          @endif
        @endforeach
      @endforeach
    @endforeach
  </tr>
  @foreach ($datas_report as $member_id => $member_datas)
    <tr>  
      <td class="breakword">{{ FuncClass::utc_to_carbon_format_time_zone_bkk($datas_report_createdAt[$member_id]) }}</td>
      <td class="breakword">{{ $employees[$member_id]['company'] }}</td>
      <td class="breakword">{{ $employees[$member_id]['name'] }}</td>
      <td class="breakword">{{ $employees[$member_id]['employee_id'] }}</td>
      <td class="breakword">{{ $employees[$member_id]['position'] }}</td>

      @foreach ($member_datas as $review_id => $review_datas)
        @if(!empty($review_datas['choice']))
          @foreach ($review_datas['choice'] as $reviews)
            <td class="breakword">{{ $reviews }}</td>
          @endforeach
        @endif
        @if(!empty($review_datas['text']))
          @foreach ($review_datas['text'] as $reviews)
            <td class="breakword">{{ $reviews }}</td>
          @endforeach
        @endif
      @endforeach
    </tr>
  @endforeach

</table>
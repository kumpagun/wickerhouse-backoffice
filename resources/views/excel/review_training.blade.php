<html>
  <style>
    .bg-blue {
      
    }
  </style>
  @foreach ($review_group as $group)
  <table class="table table-hover table-sm">
    <thead>
      <tr>
        <th colspan="99"><b>หลักสูตร{{ $training->title }}</b></th>
      </tr>
      <tr>
        <th colspan="99"><b> ผู้ประเมิินจำนวน {{ $data_total }} คน</b></th>
      </tr>
      <tr>
        <th colspan="99"><b>{{ $group->title }}</b></th>
      </tr>
    </thead>
    @foreach ($reviews as $review)
      @if($review->type=='choice')
        @if($group->_id == $review->review_group_id)
          <thead>
            <tr>
              <th>{!! $review->title !!}</th>
              @foreach ($data_choice[$review->_id] as $choice)
                <th class="text-center content-table">{{ $choice }}</th>
                <th class="text-center content-table">%{{ $choice }}</th>
              @endforeach
            </tr>
          </thead>
          <tbody>
          @foreach ($data_question[$review->_id] as $index_question => $value_question)
            <tr>
              <td class="text-left">{!! $value_question !!}</td>
              @foreach ($data_choice[$review->_id] as $index => $value)
                @if(!empty($datas_report[$review->_id]['choice'][$index_question][$data_choice[$review->_id][$index]]))
                  <td class="text-center">{{ $datas_report[$review->_id]['choice'][$index_question][$data_choice[$review->_id][$index]] }}</td>
                  @php
                    $percent = ($datas_report[$review->_id]['choice'][$index_question][$data_choice[$review->_id][$index]]/$datas_report[$review->_id]['choice_total'][$index_question]) * 100;
                  @endphp
                  <td class="text-center">
                    {{ number_format($percent,2) }} %
                  </td>
                @else
                  <td class="text-center">0</td>
                  <td class="text-center">0 %</td>
                @endif
              @endforeach
            </tr>
          @endforeach
          </tbody>
        @endif
      @else
        @if($group->_id == $review->review_group_id)
          <tr>
            <th colspan="99">{!! $review->title !!}</th>
          </tr>
          @if(!empty($datas_report[$review->_id]['text']))
            @foreach ($datas_report[$review->_id]['text'] as $value)
              <tr>
                <td class="text-left" colspan="99">{{ $value }}</td>
              </tr>
            @endforeach
            @if($count_report[$review->_id]==10)
              <tr>
                <td class="text-center" colspan="99"><a href="{{ route('report_review_create_answer_text', ['review_id' => $review->_id]) }}">ดูเพิ่มเติม</a></td>
              </tr>
            @endif
          @endif
        @endif
      @endif
    @endforeach
  </table>
  @endforeach
</html>

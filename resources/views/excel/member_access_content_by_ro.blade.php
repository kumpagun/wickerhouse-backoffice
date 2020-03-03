<table>
  <thead>
    <tr>
      <th colspan="99">{{ $training_title }}</th>
    </tr>
  <tr>
      <th>DeptName</th>
      <th>เข้าเรียน</th>
      <th>เข้าเรียน(ผ่าน)</th>
      <th>เข้าเรียน(ไม่ผ่าน)</th>
      <th>ยังไม่เข้าเรียน</th>
      <th>Total</th>
  </tr>
  </thead>
  <tbody>
    @if(count($datas))
      @foreach($datas as $key => $value)
      <tr>
        <td class="">
          {{ $key }}
        </td>
        <td class="text-right">
          @if (!empty($value['user_active']))
            {{ number_format($value['user_active']) }}
          @else
            0
          @endif
        </td>
        <td class="text-right">
          @if (!empty($value['user_active_passing_score']))
            {{ number_format($value['user_active_passing_score']) }}
          @else
            0
          @endif
        </td>
        <td class="text-right">
          @if (!empty($value['user_active_not_passing_score']))
            {{ number_format($value['user_active_not_passing_score']) }}
          @else
            0
          @endif
        </td>
        <td class="text-right">
          @if (!empty($value['user_inactive']))
            {{ number_format($value['user_inactive']) }}
          @else
            0
          @endif
        </td>
        <td class="text-right">
          @if (!empty($value['user_active']) && !empty($value['user_inactive']))
            {{ number_format($value['user_active'] + $value['user_inactive']) }}
          @elseif(!empty($value['user_active']))
            {{ number_format($value['user_active']) }}
          @elseif(!empty($value['user_inactive']))
            {{ number_format($value['user_inactive']) }}
          @else
            0
          @endif
        </td>
      </tr>
      @endforeach
    @else
      <tr>
        <td class="text-center" colspan="6">ไม่มีข้อมูล</td>
      </tr>
    @endif
  </tbody>
</table>

<table>
  <thead>
    <tr>
      <th>DeptName</th>
      <th>ยังไม่เข้าเรียน</th>
      <th>เข้าเรียน(ไม่ผ่าน)</th>
      <th>เข้าเรียน(ผ่าน)</th>
    </tr>
  </thead>
  <tbody>
    @if(count($datas))
      @foreach($datas as $key => $value)
      <tr>
        <td class="">
          {{ $key }}
        </td>
        <td class="text-right">
          @if (!empty($value['user_inactive']))
            {{ number_format($value['user_inactive']) }}
          @else
            0
          @endif
        </td>
        <td class="text-right">
          @if (!empty($value['user_active_not_passing_score']))
            {{ number_format($value['user_active_not_passing_score']) }}
          @else
            0
          @endif
        </td>
        <td class="text-right">
          @if (!empty($value['user_active_passing_score']))
            {{ number_format($value['user_active_passing_score']) }}
          @else
            0
          @endif
        </td>
      </tr>
      @endforeach
    @else
      <tr>
        <td class="text-center" colspan="6">ไม่มีข้อมูล</td>
      </tr>
    @endif
  </tbody>
</table>

<table>
  <thead>
    <tr>
      <th>DeptName</th>
      <th>ยังไม่เข้าเรียน%</th>
    </tr>
  </thead>
  <tbody>
    @if(count($datas))
      @foreach($datas as $key => $value)
      <tr>
        <td class="">
          {{ $key }}
        </td>
        <td class="text-right">
          @if (!empty($value['user_inactive']))
            {{ number_format(($value['user_inactive']*100)/$data_total['user_inactive'],2) }}%
          @else
            {{ number_format(0,2) }}%
          @endif
        </td>
      </tr>
      @endforeach
    @else
      <tr>
        <td class="text-center" colspan="6">ไม่มีข้อมูล</td>
      </tr>
    @endif
  </tbody>
</table>
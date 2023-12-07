@extends('PrnView.PrnMaster')

@section('mainrep')
    <div>

        <div style="text-align: center">
            <label style="font-size: 10pt;">{{$RepDate}}</label>
            @if($By==1)
                <label style="font-size: 14pt;margin-right: 12px;" >تقرير بإجمالي العقود حسب فروع المصارف بتاريخ : </label>
            @else
                <label style="font-size: 14pt;margin-right: 12px;" >تقرير بإجمالي العقود حسب المصارف التجميعية بتاريخ : </label>
            @endif

        </div>

        <table style="width:  90%; margin-left: 5%;margin-right: 5%; margin-bottom: 4%; margin-top: 2%;">
            <thead style="  margin-top: 8px;">
            <tr style="background: #9dc1d3;">
                <th style="width: 14%">الرصيد</th>
                <th style="width: 14%">المسدد</th>
                <th style="width: 14%">اجمالي العقد</th>
                <th style="width: 14%">عدد العقود</th>
                <th>اسم المصرف</th>

            </tr>
            </thead>
            <tbody id="addRow" class="addRow">
            @foreach($RepTable as $key=> $item)
                <tr >
                    <td> {{ number_format($item->raseed,2, '.', ',') }} </td>
                    <td> {{ number_format($item->pay,2, '.', ',') }} </td>
                    <td> {{ number_format($item->sul,2, '.', ',') }} </td>
                    <td style="text-align: center"> {{ $item->count }} </td>
                    @if($By==1)
                      <td> {{ $item->BankName }} </td>
                    @else
                      <td> {{ $item->TajName }} </td>
                    @endif
                </tr>
            @endforeach
            </tbody>
        </table>


    </div>



@endsection


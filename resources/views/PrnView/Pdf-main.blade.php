@extends('PrnView.PrnMaster')

@section('mainrep')

    <div style="position: fixed; text-align: center;  width: 100%;  margin: 10px;
                              display: flex;  justify-content: center;">
      <label style="font-size: 14pt;">{{$res->id}}</label>
      <label  style="width: 20%;font-size: 14pt;">رقم العقد</label>
    </div>
    <br>
    <br>
    <table style=" border: none;margin-bottom: 5px; padding-right: 5%">
        <tbody >
            <tr style="border: none; line-height: 18px;">
                <td style="border: none;width: 15%; ">  </td>
                <td class="order-td" style="width: 15%; text-align: center"> {{$res->sul_begin}} </td>
                <td style="border: none;width: 12%;font-size: 12pt; "> تاريخ العقد </td>
                <td style="border: none;width: 2%; ">  </td>
                <td class="order-td" style="width: 30%;"> {{$res->Customer->name}} </td>
                <td style="border: none;width: 15%;font-size: 14pt; "> اسم الزبون </td>
            </tr>
            <tr style="border: none; line-height: 18px;">
                <td style="border: none;width: 15%; ">  </td>
                <td class="order-td" style="width: 15%; text-align: center"> {{$res->acc}} </td>
                <td style="border: none;width: 12%;font-size: 12pt; "> رقم الحساب </td>
                <td style="border: none;width: 2%; ">  </td>
                <td class="order-td" style="width: 30%;"> {{$res->Bank->BankName}} </td>
                <td style="border: none;width: 12%;font-size: 12pt; "> اسم المصرف </td>
            </tr>
        </tbody>
    </table>
    <table style=" border: none;margin-bottom: 10px;padding-right: 5%">
        <tbody >
        <tr style="border: none; line-height: 18px;">
            <td style="border: none;width: 30%; ">  </td>
            <td class="order-td" style="width: 15%; text-align: center">  </td>
            <td style="border: none;width: 12%;font-size: 12pt; ">  </td>
            <td style="border: none;width: 2%; ">  </td>
            <td class="order-td" style="width: 15%;"> {{$res->sul}} </td>
            <td style="border: none;width: 20%;font-size: 12pt; "> اجمالي التقسيط </td>
        </tr>
        <tr style="border: none; line-height: 18px;">
            <td style="border: none;width: 30%; ">  </td>
            <td class="order-td" style="width: 15%; text-align: center"> {{$res->pay}} </td>
            <td style="border: none;width: 12%;font-size: 12pt; "> المسدد </td>
            <td style="border: none;width: 2%; ">  </td>
            <td class="order-td" style="width: 15%;"> {{$res->raseed}} </td>
            <td style="border: none;width: 12%;font-size: 12pt; "> المطلوب </td>
        </tr>
        <tr style="border: none; line-height: 18px;">
            <td style="border: none;width: 30%; ">  </td>
            <td class="order-td" style="width: 15%; text-align: center"> {{$res->kst_count}} </td>
            <td style="border: none;width: 15%;font-size: 12pt; "> عدد الأقساط </td>
            <td style="border: none;width: 2%; ">  </td>
            <td class="order-td" style="width: 15%;"> {{$res->kst}} </td>
            <td style="border: none;width: 12%;font-size: 12pt; "> القسط </td>
        </tr>

        </tbody>

    </table>



<br>

    <table style="width:  80%; margin-left: 10%;margin-right: 10%;">

        <thead style=" font-family: DejaVu Sans, sans-serif; margin-top: 8px;" >
        <tr  style="background: #9dc1d3;" >
            <th style="width: 32%">طريقة الخصم</th>
            <th style="width: 16%">الخصم</th>
            <th style="width: 20%">تاريخ الخصم</th>
            <th style="width: 20%">تاريخ الاستحقاق</th>
            <th style="width: 12%">ت</th>

        </tr>
        </thead>
        <tbody style="margin-bottom: 40px; ">
        @foreach($res2 as $key => $item)
            <tr>
                <td> {{ $item->ksm_type_id->name }} </td>
                <td> {{ $item->ksm }} </td>
                <td style="text-align: center"> {{ $item->ksm_date }} </td>
                <td style="text-align: center"> {{ $item->kst_date }} </td>
                <td style="text-align: center"> {{ $item->ser }} </td>
            </tr>
        @endforeach
        </tbody>

    </table>
    <div class="page-break"></div>

@endsection








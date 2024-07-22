@extends('PrnView.PrnMasterOne')

@section('mainrep')

<div style="display:flex; flex-direction: row; justify-content:
     center; align-items: center; margin-right: 80px; font-size: 16pt;">
    <label> {{$BankName}}</label>
    <label >السادة المحترومون / </label>
</div>

<label style="margin-right: 80px; font-size: 16pt;">تحية طيبة </label>
<br>
<br>
<div style="display:flex; flex-direction: row; justify-content:
     center; align-items: center; margin-right: 80px; font-size: 14pt;">
    <label style="font-weight: bold;font-family: DejaVu Sans, sans-serif;
           font-size: 11pt;"> {{$record->Customer->name}}</label>
    <label >نأمل منكم إيقاف خصم الأقساط من حساب السيد / </label>
</div>
<div style="display:flex; flex-direction: row; justify-content:
     center; align-items: center; margin-right: 80px; font-size: 14pt;">
    <label style="font-weight: bold;font-family: DejaVu Sans, sans-serif;
           font-size: 11pt;"> {{$record->acc}}</label>
    <label >حساب جاري رقم  </label>
</div>

@if($record->kst !=0)
<div style="display:flex; flex-direction: row; justify-content:
     center; align-items: center; margin-right: 80px; font-size: 14pt;">
    <label style="font-weight: bold;font-family: DejaVu Sans, sans-serif;
           font-size: 11pt;"> {{$record->kst}}</label>
    <label >وقيمة القسط  </label>
</div>
@endif

<div style="display:flex; flex-direction: row; justify-content:
     center; align-items: center; margin-right: 80px; font-size: 14pt;">
    <label style="font-weight: bold;font-family: DejaVu Sans, sans-serif;
           font-size: 11pt;"> {{$TajAcc}}</label>
    <label >لحساب الشركة التجميعي رقم   </label>
</div>
<div style="display:flex; flex-direction: row; justify-content:
     center; align-items: center; margin-right: 80px; font-size: 14pt;">
    <label style="font-weight: bold;font-family: DejaVu Sans, sans-serif;
           font-size: 11pt;"> {{$record->Stop->stop_date}}</label>
    <label >اعتباراً من تاريخ   </label>
</div>
<label style="margin-right: 80px; font-size: 14pt;">مع رفع الحجز إن وجد  </label>
<br><br>
<label style="margin-right: 100px; font-size: 14pt;">نشكركم علي حسن تعاونكم  </label>
<br><br>
<div style="text-align: center;font-size: 14pt;">
   والسلام عليكم ورحمة الله وبركاته
</div>

<br>
<br><br>
<div style="text-align: left; margin-left: 100px; font-size: 14pt;">التوقيع ...................  </div>
<div style="text-align: left; margin-left: 100px;font-size: 14pt;">    مفوض الشركة /      </div>


@endsection

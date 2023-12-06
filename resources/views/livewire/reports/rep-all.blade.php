<div class="text-sm ">

    <div class="flex gap-2  justify-between">
        <div class="flex  gap-6">
            <div class="flex gap-2">
                <x-label  class="text-primary-400" for="radio1" value="{{ __('بالتجميعي') }}"/>
                <x-input type="radio" class="ml-4" wire:model.live="By" name="radio1" value="2" />
            </div>
            <div class="flex gap-2">
                <x-label  class="text-primary-400" for="radio2" value="{{ __('بفروع المصارف') }}"/>
                <x-input type="radio" class="ml-4" wire:model.live="By" name="radio2" value="1"/>
            </div>

        </div>
        <div class="flex gap-1">
            <span class="text-primary-500">طباعة</span>
            @if($By==1 && $rep_name=='Mosdada')
                <a  href="{{route('pdfmosdadabank',['Baky'=>$Baky,'bank_id'=>$bank_id])}}"  class="text-primary-500">
                    <x-icon.print/>
                </a>
            @endif
            @if($By==1 && $rep_name=='Motakra')
                <a  href="{{route('pdfmotakrabank',['Baky'=>$Baky,'bank_id'=>$bank_id])}}"  class="text-primary-500">
                    <x-icon.print/>
                </a>
            @endif
            @if($By==1 && $rep_name=='Mohasla')
                <a  href="{{route('pdfmotakrabank',['Baky'=>$Baky,'bank_id'=>$bank_id])}}"  class="text-primary-500">
                    <x-icon.print/>
                </a>
            @endif
        </div>
    </div>
        <div class=" mt-2 rounded shadow-inner bg-blue-100">
            {{ $this->form }}
        </div>
    @if($rep_name=='Mosdada' || $rep_name=='Motakra')
    <div class="w-full mt-2">
        {{ $this->table }}
    </div>
    @endif
    @if($rep_name=='Mohasla' )

        <div class="w-full mt-2">
            @livewire('reports.rep-aksat-get' ,['Date1'=>$Date1 ,'Date2'=>$Date2 ,'bank_id'=>$bank_id])


        </div>
    @endif


</div>

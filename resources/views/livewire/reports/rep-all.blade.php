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
            <a  href="{{route('pdfbanksum',['By'=>$By])}}"  class="text-primary-500">
                <x-icon.print/>
            </a>
        </div>
    </div>
        <div class=" mt-2 rounded shadow-inner bg-blue-100">
            {{ $this->form }}
        </div>







    <div class="w-full mt-2">
        {{ $this->table }}
    </div>

</div>

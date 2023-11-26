<div class="text-sm ">
    <div class="flex">
        <div class="w-1/2 mt-2 rounded shadow-inner bg-blue-100">
            {{ $this->form }}
        </div>


        <div class="flex gap-2 my-1 py-1 w-1/2  justify-center">
            <x-label  class="text-primary-400" for="radio1" value="{{ __('بالتجميعي') }}"/>
            <x-input type="radio" class="ml-4" wire:model.live="By" name="radio1" value="2" />

            <x-label  class="text-primary-400" for="radio2" value="{{ __('بفروع المصارف') }}"/>
            <x-input type="radio" class="ml-4" wire:model.live="By" name="radio2" value="1"/>
        </div>

    </div>

    <div class="w-full mt-2">
        {{ $this->table }}
    </div>

</div>

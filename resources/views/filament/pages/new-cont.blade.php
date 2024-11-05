<x-filament-panels::page>
    <div class="flex gap-2">
        <div class="w-2/5">
            {{$this->contForm}}
        </div>
        <div class="w-3/5">
            {{$this->sellForm}}
        </div>
    </div>



    <x-filament::modal id="create-sell" width="7xl">
        <livewire:sell-modal/>
    </x-filament::modal>

</x-filament-panels::page>

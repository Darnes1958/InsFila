<div class="gap-2">
    <div class="flex">
        <div class="w-1/4 mb-2">
            {{ $this->form }}
        </div>
        <div  wire:click="DoArc" class="flex w-1/4 mb-4 justify-end">
            <x-button>
                استرجاع من الأرشيف
            </x-button>
        </div>
    </div>
    <div class="flex gap-2">
        <div class="w-1/2 text-xs ">
            <div >
                {{ $this->mainArcInfolist }}
            </div>
        </div>

        <div class="w-1/2">
            {{ $this->table }}
        </div>
    </div>


</div>

@push('scripts')
    <script src="https://cdn.tailwindcss.com"></script>



@endpush


<div class="gap-2">
    <div class="flex">
        <div class="w-2/4 mb-2">
            {{ $this->form }}
        </div>
        <div class="w-1/4 mb-2 px-4 gap-4">
            {{ $this->printAction }}
            {{ $this->printContAction }}
        </div>
        <div x-show="$wire.montahy" wire:click="DoArc" class="flex w-1/4 mb-4 justify-end">
            <x-button>
                نقل للأرشيف
            </x-button>
        </div>
    </div>
    <div class="flex gap-2">
        <div class="w-1/2 text-xs ">
            <div >
                {{ $this->mainInfolist }}
            </div>
        </div>

        <div class="w-1/2">
            {{ $this->table }}
            @livewire(\App\Livewire\widgets\OverWidget::class)
        </div>
    </div>


</div>

@push('scripts')
    <script src="https://cdn.tailwindcss.com"></script>



@endpush


<?php

namespace App\Filament\Pages;

use App\Models\Main;
use Filament\Forms\Components\Section;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class KsmKst extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.ksm-kst';
  public static function shouldRegisterNavigation(): bool
  {
    return  auth()->id()==1;
  }

    public $tranData;
    public $main_id;
    public $main;

    public function mount(): void
    {
        $this->contForm->fill(['sul_begin'=>now()]);
    }

    protected function getForms(): array
    {
        return array_merge(parent::getForms(),[
            'contForm'=> $this->makeForm()
                ->model(Main::class)
                ->schema($this->getContFormSchema())
                ->statePath('contData'),
        ]);
    }
    public function go($who){
        $this->dispatch('gotoitem', test: $who);
    }
    public function store(){
        $this->validate();
        Main:: create(collect($this->contData)->except(['total','pay','baky'])->toArray());
        Notification::make()
            ->title('تم تحزين البانات بنجاح')
            ->success()
            ->send();
        $this->mount();

    }
    protected function getContFormSchema(): array
    {
        return [
            Section::make()

        ];
    }
}

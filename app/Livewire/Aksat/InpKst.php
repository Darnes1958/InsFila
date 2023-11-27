<?php

namespace App\Livewire\Aksat;

use App\Livewire\Forms\MainView;
use App\Livewire\Forms\TransForm;
use App\Models\Main;
use App\Models\Tran;
use Livewire\Component;
use Livewire\WithPagination;

class InpKst extends Component
{
    use WithPagination;
    public TransForm $TransForm;
    public $ShowDeleteModal=false;
    public $search='';
    public $IsSearch=true;
    public $ShowManyMessage=false;
    public MainView $mainView;
    public $Mod='inp';
    public $main_id;
    public $trans_id;
    public $acc;
    public $IdSelected=0;
    public $bank_name;
    public Main $rec;
    public $color='bg-gray-100';

    public function updatedSearch(){
        $this->ShowManyMessage=false;
    }
    public function OpenTable(){
        $this->IsSearch=true;
    }
    public function CloseTable(){
        $this->search='';
        $this->IsSearch=false;
        $this->ShowManyMessage=false;
    }
    public function selectItem($id){
        $this->main_id=$id;
        $this->CloseTable();
        $this->Main_idGo();
    }

    public function Main_idGo(){
        $this->mainView->SetMainView($this->main_id);
        $this->acc=$this->mainView->acc;
        $this->TransForm->ksm=$this->mainView->kst;
        $this->TransForm->main_id=$this->main_id;
        $this->dispatch('goto', test: 'ksm_date');

    }
    public function updatedIdSelected(){
        if ($this->main_id){
            $this->Main_idGo();
        }
        $this->IdSelected=0;
    }
    public function ChkAcc(){
        if ($this->search){
            $res=Main::where('acc',$this->search)->get();
            if (count($res)>0){
                if (count($res)==1) {
                    $this->main_id=$res->first()->id;
                    $this->CloseTable();
                    $this->Main_idGo();
                } else $this->ShowManyMessage=true;
            }
        }
    }
    public function Edit(Tran $transrec){
        $this->Mod='upd';
        $this->TransForm->SetTrans($transrec);
        $this->trans_id=$transrec->id;
        $this->color='bg-blue-100';
        $this->dispatch('goto', test: 'ksm_date');
    }
    public function cancel(){
        $this->Mod='inp';
        $this->color='bg-gray-100';
        $this->TransForm->reset();
        $this->dispatch('goto', test: 'accc');
    }
    public function Delete($id){
        $this->trans_id=$id;
        $this->ShowDeleteModal=true;

    }
    public function DoDelete(){
        $this->TransForm->TransDelete($this->trans_id);
        $this->mainView->Tarseed();
        $this->ShowDeleteModal=false;
    }
    public function store(){
        if ($this->Mod=='inp') $this->TransForm->FillTrans($this->main_id);

        try {
            $this->validate();
        } catch (\Illuminate\Validation\ValidationException $e) {
            $validator = $e->validator;
            info($validator->errors());
            throw $e;
        }


        if ($this->Mod=='inp'){
            Tran::create(
                $this->TransForm->all()
            );
            $this->mainView->tarseed();

        }
        if ($this->Mod=='upd'){

            Tran::where('id',$this->trans_id)->update(
                $this->TransForm->all()
            );
            $this->mainView->Tarseed();
            $this->Mod='inp';
            $this->color='bg-gray-100';
        }
        $this->search=$this->acc;
        $this->mainView->reset();

        $this->dispatch('goto', test: 'search');
    }
    public function mount(){
        $this->resetPage('Trans-page');
        $this->resetPage('Main-page');
        $this->TransForm->ksm_date=date('Y-m-d');
    }

    public function render()
    {

        return view('livewire.aksat.inp-kst',[
            'Table'=>Tran::where('main_id',$this->main_id)->paginate(10, pageName: 'Trans-page'),
            'MainSearch' => Main::
            whereHas('customer', function($custQuery) {
                $custQuery->where('CusName', 'LIKE', '%'.$this->search.'%' );
            })
                ->orwhere('acc', 'like', '%'.$this->search.'%')
                ->orwhere('id', 'like', '%'.$this->search.'%')

                ->paginate(5, pageName: 'Main-page')

        ]);

    }
}

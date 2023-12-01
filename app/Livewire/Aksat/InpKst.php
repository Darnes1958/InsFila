<?php

namespace App\Livewire\Aksat;

use App\Livewire\Forms\MainView;
use App\Livewire\Forms\TransForm;
use App\Livewire\Forms\WrongForm;
use App\Livewire\Traits\AksatTrait;
use App\Models\Main;
use App\Models\Overkst;
use App\Models\Tran;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class InpKst extends Component
{
    use WithPagination;
    use AksatTrait;
    public TransForm $TransForm;
    public WrongForm $wrongForm;

    public $ShowDeleteModal=false;
    public $search='';
    public $IsSearch=true;
    public $ShowManyMessage=false;
    public $ShowOverMessage=false;
    public $ShowDeteteMessage=false;
    public $OverMessage;
    public $DeleteMessage;
    public MainView $mainView;
    public $Mod='inp';
    public $main_id;
    public $trans_id;
    public $acc;
    public $IdSelected=0;
    public $bank_name;
    public Main $rec;
    public $color='bg-gray-100';
    public $has_over=false;
    public $over_id;
    public $can_delete=true;
    public $isWrong=false;
    public $bankSelected=0;
    public function updatedSearch(){
        $this->ShowManyMessage=false;
    }
    public function OpenTable(){
        $this->IsSearch=true;
        $this->isWrong=false;
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
        $this->has_over=false;
        $this->can_delete=true;
        $this->ShowOverMessage=false;
        $this->ShowDeteteMessage=false;
        $this->mainView->SetMainView($this->main_id);
        if ($this->mainView->raseed<=0) {$this->ShowOverMessage=true;$this->OverMessage='خصم بالفائض';}
        $this->has_over= $this->mainView->over_count>0;
        if ($this->has_over) {
            if ($this->mainView->over_count > 1) {
                $this->ShowDeteteMessage=true;
                $this->DeleteMessage = ' هذا الزبون لديه عدة اقساط بالفائض .. ولا يسمح بالتعديل أو الإلغاء إلا بعد الغاءها';
                $this->can_delete=false;
            }else
            {

                $res=Overkst::where('main_id',$this->main_id)->first();

                if ($res->tar_id>0) {
                    $this->ShowDeteteMessage=true;
                    $this->DeleteMessage = 'هذا الزبون لديه قسط بالفائض وتم ترجيعه .. ولا يسمح بالتعديل أو الإلغاء قبل الغاء الترجيع';
                    $this->can_delete=false;}

            }

        }


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
    public function ChkKst(){
      if ($this->TransForm->ksm){
        if ($this->TransForm->ksm>$this->mainView->raseed){
          if ($this->mainView->raseed<=0) {
            $this->OverMessage='خصم بالفائض';
          } else $this->OverMessage='خصم جزئي';
          $this->ShowOverMessage=true;
        }else $this->ShowOverMessage=false;
        $this->dispatch('goto', test: 'Transstore');
      }
    }

    public function ChkAcc(){
        $this->ShowOverMessage=false;
        if ($this->search){
            $res=Main::where('acc',$this->search)->get();
            if (count($res)>0){
                if (count($res)==1) {
                    $this->main_id=$res->first()->id;
                    if ($res[0]['raseed']<=0) {$this->OverMessage='خصم بالفائض'; $this->ShowOverMessage=true;}

                    $this->CloseTable();
                    $this->Main_idGo();
                } else $this->ShowManyMessage=true;
            }else {
              $this->wrongForm->reset();
              $this->wrongForm->acc=$this->search;
              $this->wrongForm->wrong_date=date('Y-m-d');
              $this->isWrong=true;
              $this->dispatch('goto', test: 'wrong_date');
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
    public function Delete($id,$over_id){
        $this->trans_id=$id;
        $this->over_id=$over_id;
        $this->ShowDeleteModal=true;

    }
    public function DoDelete(){
        if ($this->over_id!=0){
            Overkst::where('id',$this->over_id)->delete();
            $this->OverTarseed($this->main_id);
        }
        $this->TransForm->TransDelete($this->trans_id);
        $this->mainView->Tarseed();
        $this->ShowDeleteModal=false;
    }

    public function wrongStore(){

      $this->wrongForm->store();

      $this->isWrong=false;
      $this->dispatch('goto', test: 'search');
    }
    public function store(){
      $this->ShowOverMessage=false;
      if ($this->Mod=='inp') $this->TransForm->FillTrans($this->main_id);

        try {
            $this->validate();
        } catch (\Illuminate\Validation\ValidationException $e) {
            $validator = $e->validator;
            info($validator->errors());
            throw $e;
        }


        if ($this->Mod=='inp'){
            if ($this->mainView->raseed<=0) $this->TransForm->DoOver($this->mainView->id);
            if ($this->mainView->raseed>0){
              if ($this->mainView->raseed<$this->TransForm->ksm) $this->TransForm->DoBaky($this->mainView->id,$this->mainView->raseed);
              $res=Tran::create($this->TransForm->all());
              if ($this->TransForm->over_id!=0) Overkst::where('id',$this->TransForm->over_id)->update(['tran_id'=>$res->id]);

              $this->mainView->tarseed($this->TransForm->ksm_date,$this->TransForm->kst_date);
            }
            $this->TransForm->reset();
            $this->TransForm->ksm_date=date('Y-m-d');
            $this->TransForm->ksm=$this->mainView->kst;
            $this->TransForm->main_id=$this->main_id;
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


<div  class="flex gap-2  w-full" >

                <div class="w-6/12 p-2 rounded shadow-inner {{$color}}">
                   <div class="flex gap-2  py-2 px-2 border-2 justify-center">
                        <x-label  for="radio1" value="{{ __('نقدا') }}"/>
                        <x-input type="radio" class="ml-4" wire:model="TransForm.ksm_type_id" name="radio1" value="1" />

                        <x-label  for="radio2" value="{{ __('مصرفي') }}"/>
                        <x-input type="radio" class="ml-4" wire:model="TransForm.ksm_type_id" name="radio2" value="2"/>

                        <x-label  for="radio3" value="{{ __('صك') }}"/>
                        <x-input type="radio" class="ml-4" wire:model="TransForm.ksm_type_id" name="radio3" value="3"/>

                        <x-label  for="radio4" value="{{ __('الكتروني') }}"/>
                        <x-input type="radio" class="ml-4" wire:model="TransForm.ksm_type_id" name="radio4" value="4"/>
                    </div>
                   <div class="flex gap-4 mt-2  ">
                        <div class="flex w-8/12 gap-1">
                            <x-label class="w-4/12" for="search" value="{{ __('الحساب') }}"/>
                            <x-input id="search" wire:model.live="search"
                                     wire:keydown.enter="ChkAcc" wire:click="OpenTable"
                                     class="w-full leading-none text-indigo-700 text-sm py-1"   type="text" autofocus autocomplete="off"/>
                        </div>
                        <div class="flex w-4/12 gap-1">
                            <x-label class="w-3/12 " for="main_id" value="{{ __('العقد') }}"/>
                            <x-input id="main_id" wire:model="main_id"
                                     disabled  class="w-full leading-none text-md py-1 bg-gray-200 text-center text-blue-400"
                                     type="text"/>
                        </div>

                        <x-input-error for="accc"></x-input-error>
                    </div>

                    @if($isWrong)
                   <div  class="w-full rounded shadow-inner bg-primary-500/10 mt-2">
                       <div class="w-full my-2 mx-2">
                           <span class="text-danger-600">قسط وارد بالخطأ !!</span>
                       </div>
                       <div class="w-full my-2 py-2" wire:ignore>
                           <select
                               x-init="$($el).select2({placeholder: 'ابحث هنا ..'});$($el).on('change',function(){
                                        $wire.set('wrongForm.bank_id',$($el).val());
                                        $wire.set('bankSelected',2);
                                        });
                                         $($el).val($($el).val()); $($el).trigger('change'); "
                               wire:model.live="wrongForm.bank_id"
                               name="bank_id" id="bank_id" class="select2 w-full ">
                               <option value="">اختيار من القائمة</option>
                               @foreach(App\Models\Bank::all() as $s)
                                   <option value="{{ $s->id }}">{{ $s->BankName }}</option>
                               @endforeach
                           </select>
                       </div>
                       <x-input-error for="wrongForm.bank_id"></x-input-error>
                       <div class=" gap-4 mt-2  ">
                          <div class="flex gap-1 mt-2 px-2 w-full">

                               <div class="flex gap-1 w-1/2 mt-2 mx-1">
                                   <x-label class="w-3/12" for="wrong_date" value="{{ __('التاريخ') }}"/>
                                   <x-input id="wrong_date" wire:model="wrongForm.wrong_date"
                                            wire:keydown.enter="$dispatch('goto', {test: 'wrong_kst'})"
                                            class="w-full leading-none text-blue-400 text-md py-1" type="date"/>

                               </div>
                               <x-input-error for="wrongForm.wrong_date"></x-input-error>


                               <div class="flex gap-1 w-1/2 mt-2 mx-1">
                                   <x-label class="w-3/12" for="wrong_kst" value="{{ __('الخصم') }}"/>
                                   <x-input id="wrong_kst" wire:model="wrongForm.kst"
                                            wire:keydown.enter="$dispatch('goto', {test: 'wrongstore'})"
                                            class="block w-full leading-none text-blue-400 text-md py-1" type="number"/>
                               </div>
                               <x-input-error for="wrongForm.kst"></x-input-error>

                           </div>
                         <div class="flex flex-row items-center justify-center gap-4">
                           <x-button wire:click="wrongStore" id="wrongstore" class="mt-4 mb-4">
                               تخزين البيانات
                           </x-button>
                           <x-button.primary  wire:click="cancel" id="wrongcancel" class="mt-4 mb-4">
                               تجاهل
                           </x-button.primary>

                         </div>
                       </div>
                   </div>
                    @endif

                    @if($isOverArc)
                        <div  class="w-full rounded shadow-inner bg-primary-500/10 mt-2">
                            <div class="w-full my-2 mx-2">
                                <span class="text-danger-600">بالفائض من الأرشيف !!</span>
                            </div>

                            <div class=" gap-4 mt-2  ">
                                <div class="flex w-full gap-2">
                                    <div class="flex  w-4/12 ">
                                        <x-label class="w-2/5"  value="{{ __('رقم العقد') }}"/>
                                        <x-input  wire:model="mainArcId" class="w-3/5 leading-none text-center text-blue-400 text-md py-1" disabled/>
                                    </div>

                                    <div class="flex  w-8/12 ">
                                        <x-label class="w-2/12"  value="{{ __('الاسم') }}"/>
                                        <x-input  wire:model="mainArcName" class="w-10/12 leading-none text-blue-400 text-md p-1" disabled/>
                                    </div>

                                </div>

                                <div class="flex gap-1 mt-2 px-2 w-full">


                                    <div class="flex gap-1 w-1/2 mt-2 mx-1">
                                        <x-label class="w-1/5" for="over_date" value="{{ __('التاريخ') }}"/>
                                        <x-input id="over_date" wire:model="overForm.over_date"
                                                 wire:keydown.enter="$dispatch('goto', {test: 'over_kst'})"
                                                 class="w-4/5 leading-none text-blue-400 text-md py-1 text-center" type="date"/>
                                    </div>

                                    <div class="flex gap-1 w-1/2 mt-2 mx-1">
                                        <x-label class="w-1/5" for="wrong_kst" value="{{ __('الخصم') }}"/>
                                        <x-input id="over_kst" wire:model="overForm.kst"
                                                 wire:keydown.enter="$dispatch('goto', {test: 'overstore'})"
                                                 class="w-4/5 leading-none text-blue-400 text-md py-1" type="number"/>
                                    </div>

                                </div>
                                <x-input-error for="overForm.over_date"></x-input-error>
                                <x-input-error for="overForm.kst"></x-input-error>
                                <div class="flex flex-row items-center justify-center gap-4">
                                    <x-button wire:click="overArcStore" id="overstore" class="mt-4 mb-4">
                                        تخزين البيانات
                                    </x-button>
                                    <x-button.primary  wire:click="cancel" id="overcancel" class="mt-4 mb-4">
                                        تجاهل
                                    </x-button.primary>

                                </div>
                            </div>
                        </div>
                    @endif

                         @if($ShowManyMessage)
                        <div  class="text-red-400">يوجد اكثر من عقد .. يجب الاختيار</div>
                        @endif

                        @if($IsSearch && !$isWrong)
                        <div class="mt-2">
                            <x-table class="table-fixed font-medium">
                                <x-slot name="head">
                                    <x-table.heading class="w-3/12 text-right" >رقم الحساب</x-table.heading>
                                    <x-table.heading class="w-5/12 text-right">الإسم</x-table.heading>
                                    <x-table.heading class="w-2/12 text-right" >رقم العقد</x-table.heading>
                                    <x-table.heading class="w-2/12 text-right">القسط</x-table.heading>
                                </x-slot>

                                <x-slot name="body">
                                    @forelse ($MainSearch as $item)

                                        <x-table.row wire:loading.class.delay="opacity-75" class=" text-xs " style="height: 10pt;">
                                            <x-table.cell>
                                                <x-button.link class="text-xs text-blue-400" wire:click="selectItem({{ $item->id }})">
                                                    {{$item->acc}}
                                                </x-button.link>
                                            </x-table.cell>
                                            <x-table.cell>
                                                <x-button.link class="text-xs text-blue-400" wire:click="selectItem({{ $item->id }})">
                                                    {{$item->customer->name}}
                                                </x-button.link>
                                            </x-table.cell>
                                            <x-table.cell>
                                                <x-button.link class=" text-blue-400" wire:click="selectItem({{ $item->id }})">
                                                    {{$item->id}}
                                                </x-button.link>
                                            </x-table.cell>

                                            <x-table.cell >
                                                <x-button.link class="text-xs text-blue-400" wire:click="selectItem({{ $item->id }})">
                                                    {{$item->kst}}
                                                </x-button.link>
                                            </x-table.cell>


                                        </x-table.row>
                                    @empty
                                        <x-table.row colspan="9">
                                            <x-table.cell colspan="9">
                                                <div class="flex justify-center items-center space-x-2">
                                                    <span>لا توجد أي نتائج بحث ..</span>
                                                    <x-icon.inbox class="h-8 w-8 text-cool-gray-400"/>
                                                </div>

                                            </x-table.cell>
                                        </x-table.row>
                                    @endforelse
                                </x-slot>
                            </x-table>
                            {{$MainSearch->links('Mypaginator')}}

                        </div>
                          @endif

                    <div class="gap-4 mt-2  ">
                        <div class="flex gap-1 w-full">
                            <x-label class="w-3/12"  value="{{ __('الاسم') }}"/>
                            <x-input  wire:model="mainView.name"
                                      disabled class="w-full leading-none text-indigo-800 text-md py-1 bg-gray-200"
                                      type="text"/>
                        </div>
                    </div>
                    <div class="gap-4 mt-2  ">
                        <div class="flex gap-1 w-full">
                            <x-label class="w-3/12"  value="{{ __('المصرف') }}"/>
                            <x-input  wire:model="mainView.BankName"
                                      readonly class="w-full leading-none text-blue-400 text-md py-1 bg-gray-200"
                                      type="text"/>
                        </div>
                    </div>
                    <div class="gap-4 mt-2  ">
                        <div class="flex gap-1 w-full">
                            <x-label class="w-3/12 "  value="{{ __('الاجمـــــــالي') }}"/>
                            <x-input  wire:model="mainView.sul"
                                      readonly class=" w-3/12 ml-2 leading-none text-blue-400 text-md py-1 bg-gray-200"
                                      type="text"/>
                            <x-label class="w-2/12"  value="{{ __('المدفوع') }}"/>
                            <x-input  wire:model="mainView.pay"
                                      readonly class=" w-4/12 leading-none text-blue-400 text-md py-1 bg-gray-200"
                                      type="text"/>

                        </div>
                    </div>
                    <div class="gap-4 mt-2  ">
                        <div class="flex gap-1 w-full">
                            <x-label class="w-3/12 "  value="{{ __('عدد الاقساط') }}"/>
                            <x-input  wire:model="mainView.kst_count"
                                      readonly class=" w-3/12 ml-2  leading-none text-blue-400 text-md py-1 bg-gray-200"
                                      type="text"/>
                            <x-label class="w-2/12"  value="{{ __('القسط') }}"/>
                            <x-input  wire:model="mainView.kst"
                                      readonly class=" w-4/12 leading-none text-blue-400 text-md py-1 bg-gray-200"
                                      type="numeric"/>

                        </div>
                    </div>
                    <div class="gap-4 mt-2  ">
                        <div class="flex gap-1 w-full justify-center">
                            <x-label class="w-auto ml-3"  value="{{ __('الرصيد') }}"/>
                            <x-input  wire:model="mainView.raseed"
                                      readonly class=" w-4/12 ml-3 leading-none text-md py-1 bg-gray-200 text-red-500 text-center"  type="text"/>
                        </div>
                    </div>
                <div x-show="!$wire.isWrong">
                    <div class="gap-4 mt-2  ">
                        <div class="flex gap-1 w-full">
                            <x-label class="w-3/12"  value="{{ __('ملاحظات') }}"/>
                            <x-input  wire:model="TransForm.ksm_notes"
                                      wire:keydown.enter="$dispatch('goto', {test: 'ksm_date'})"
                                      class="w-full leading-none text-blue-400 text-md py-1 " type="text"/>
                        </div>
                    </div>


                    <div class="flex gap-4 mt-2  ">
                        <div class="flex gap-1 w-1/2">
                            <x-label class="w-3/12" for="ksm" value="{{ __('التاريخ') }}"/>
                            <x-input id="ksm_date" wire:model="TransForm.ksm_date"
                                     wire:keydown.enter="$dispatch('goto', {test: 'ksm'})"
                                     class="w-full leading-none text-blue-400 text-md py-1" type="date"/>

                        </div>
                        <x-input-error for="TransForm.ksm_date"></x-input-error>


                        <div class="flex gap-1 w-1/2">
                            <x-label class="w-3/12" for="ksm" value="{{ __('الخصم') }}"/>
                            <x-input id="ksm" wire:model="TransForm.ksm"
                                     wire:keydown.enter="ChkKst"
                                     class="block w-full leading-none text-blue-400 text-md py-1" type="number"/>
                        </div>
                        <x-input-error for="TransForm.ksm"></x-input-error>

                    </div>
                    @if($ShowOverMessage)
                        <div  class="text-red-400">{{ $OverMessage }}</div>
                    @endif


                    <div class="flex flex-row items-center justify-center gap-4">
                        <x-button wire:click="store" id="Transstore" class="mt-4 mb-4">
                            تخزين البيانات
                        </x-button>
                        <x-button.primary x-show="$wire.Mod=='upd'" wire:click="cancel" id="cancel" class="mt-4 mb-4">
                            تجاهل
                        </x-button.primary>

                    </div>
                </div>
                </div>
                <div class="w-full ">
                    @if($ShowDeteteMessage)
                        <div  class="text-red-400">{{ $DeleteMessage }}</div>
                    @endif
                    <x-table class="table-fixed font-medium ">
                        <x-slot name="head">
                            <x-table.heading  class="w-1/12 text-right ">ت</x-table.heading>
                            <x-table.heading  class="w-2/12 text-right">تاريخ القسط</x-table.heading>
                            <x-table.heading  class="w-2/12 text-right">تاريخ الخصم</x-table.heading>
                            <x-table.heading  class="w-1/12 text-right">الخصم</x-table.heading>
                            <x-table.heading  class="w-2/12 text-right">طريقة الدفع</x-table.heading>
                            <x-table.heading  class="w-3/12 text-right">ملاحظات</x-table.heading>
                            <x-table.heading  class="w-1/12 text-right"></x-table.heading>
                        </x-slot>

                        <x-slot name="body">
                            @forelse ($Table as $item)
                                <x-table.row wire:loading.class.delay="opacity-75" class=" text-xs text-blue-400" >
                                    <x-table.cell  >{{$item->ser}}</x-table.cell>
                                    <x-table.cell  >{{$item->kst_date}}</x-table.cell>
                                    <x-table.cell  >{{$item->ksm_date}}</x-table.cell>
                                    <x-table.cell  >{{$item->ksm}}</x-table.cell>
                                    <x-table.cell  >{{$item->ksm_type_id->name}}</x-table.cell>

                                    <x-table.cell  >{{$item->ksm_notes}}</x-table.cell>
                                    <x-table.cell  >
                                        @if( !$has_over )
                                        <x-button.link class=" text-blue-400" wire:click="Edit({{$item}})">
                                            <x-icon.edit dim="w-4 h-4"/>
                                        </x-button.link>
                                        @endif
                                            @if( !$has_over || ($item->baky>0 && $can_delete) )
                                        <x-button.link class="text-red-400" wire:click="Delete({{$item->id}},{{ $item->over_id }})">
                                            <x-icon.delete dim="w-4 h-4"/>
                                        </x-button.link>
                                            @endif
                                    </x-table.cell>
                                </x-table.row>
                            @empty
                                <x-table.row colspan="9">
                                    <x-table.cell colspan="9">
                                        <div class="flex justify-center items-center space-x-2">
                                            <span>لم يتم ادخال أقساط بعد ..</span>

                                        </div>

                                    </x-table.cell>
                                </x-table.row>
                            @endforelse
                        </x-slot>
                    </x-table>
                    {{$Table->links('Mypaginator')}}
                </div>
                <x-modal.confirmation wire:model.defer="ShowDeleteModal">
                    <x-slot name="title"></x-slot>

                    <x-slot name="content">
                        <div class="flex space-x-6">
                            <label class="mx-6">هل انت متأكد من الغاء القسط</label>
                            <!--   <label class="text-blue-400"></label> -->
                        </div>

                    </x-slot>
                    <x-slot name="footer">
                        <div class="flex justify-center space-x-6">
                            <x-danger-button class="mx-6" wire:click="DoDelete">نعم</x-danger-button>
                            <x-button.secondary autofocus wire:click="$set('ShowDeleteModal',false)">لا</x-button.secondary>
                        </div>
                    </x-slot>
                </x-modal.confirmation>

            </div>

  @push('scripts')
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
          <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
          <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
            <script src="https://cdn.tailwindcss.com"></script>


            <script>

                document.addEventListener('livewire:initialized', () => {
                @this.on('goto', (event) => {
                    postid = (event.test);

                    if (postid == 'search') {
                        $("#search").focus();
                        $("#search").select();
                    }
                    if (postid == 'ksm_date') {
                        $("#ksm_date").focus();
                        $("#ksm_date").select();
                    }
                    if (postid == 'ksm') {
                        $("#ksm").focus();
                        $("#ksm").select();
                    }

                    if (postid == 'Transstore') {
                        setTimeout(function () {
                            document.getElementById('Transstore').focus();
                        }, 100);
                    }

                    if (postid == 'wrong_kst') {
                        $("#wrong_kst").focus();
                        $("#wrong_kst").select();
                    }
                    if (postid == 'wrong_date') {

                        $("#wrong_date").focus();
                        $("#wrong_date").select();
                    }

                    if (postid == 'wrongstore') {
                        setTimeout(function () {
                            document.getElementById('wrongstore').focus();
                        }, 100);
                    }

                    if (postid == 'over_kst') {
                        $("#over_kst").focus();
                        $("#over_kst").select();
                    }
                    if (postid == 'over_date') {

                        $("#over_date").focus();
                        $("#over_date").select();
                    }

                    if (postid == 'overstore') {
                        setTimeout(function () {
                            document.getElementById('overstore').focus();
                        }, 100);
                    }

                });
                });

            </script>

        @endpush





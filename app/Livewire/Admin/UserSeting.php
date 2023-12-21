<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserSeting extends Component
{
    public Role $role;
    public Permission $permission;

    public ?array $roleData = [];
    public ?array $permissionData = [];

    public ?string $newRole = null;
    public ?string $newPermission = null;

    protected function getForms(): array
    {
        return [
            'createRole',
            'createPermission',
        ];
    }
    public function createRole(Form $form): Form
    {

        return $form
            ->schema([
                TextInput::make('newRole')
                    ->required(),

            ])
            ->statePath('roleData')
            ->model($this->role);
    }

    public function createPernission(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('newRole')
                    ->required(),

            ])
            ->statePath('permissionData')
            ->model($this->permission);
    }

    public function render()
    {
        return view('livewire.admin.user-seting');
    }
}

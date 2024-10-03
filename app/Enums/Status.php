<?php

namespace App\Enums;
use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasColor;

enum Status: string implements HasLabel,HasColor
{
  case مرجع = 'مرجع';
  case غير_مرجع = 'غير مرجع';
    case مصحح = 'مصحح';


  public function getLabel(): ?string
  {
    return $this->name;
  }
  public function getColor(): string | array | null
  {
    return match ($this) {
      self::مرجع => 'success',
      self::غير_مرجع => 'info',
      self::مصحح => 'primary',
    };
  }

}



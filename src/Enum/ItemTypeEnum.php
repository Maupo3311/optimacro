<?php

namespace App\Enum;

enum ItemTypeEnum: string
{
    case PRODUCTS_AND_COMPONENTS = 'Изделия и компоненты';
    case EQUIPMENT_OPTIONS = 'Варианты комплектации';
    case DIRECT_COMPONENTS = 'Прямые компоненты';
}

<?php

//CREADON CON LE COMANDO : sail php artisan make:filament-relation-manager OrderResource address street_address
//OPCIONES chart,admin panel,Stats overview
namespace App\Filament\Resources\OrderResource\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;

class OrderStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Nuevas', Order::Query()->where('status', 'new')->count()),
            //Stat::make('Pendientes', Order::Query()->where('status', 'pending')->count()),
            Stat::make('Procesando', Order::Query()->where('status', 'processing')->count()),
            Stat::make('Enviadas', Order::Query()->where('status', 'shipped')->count()),
            Stat::make('precio medio',Number::currency(Order::Query()->avg('grand_total'), 'COL')),
            //Stat::make('Entregadas', Order::Query()->where('status', 'delivered')->count()),
            //Stat::make('Canceladas', Order::Query()->where('status', 'canceled')->count()),
        ];
    }
}

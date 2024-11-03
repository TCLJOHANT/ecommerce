<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use App\Filament\Resources\OrderResource\Widgets\OrderStats;
use App\Models\Order;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            OrderStats::class,
        ];
    }

    // protected function getFooterWidgets(): array
    // {
    //     return [
    //         OrderStats::class,
    //     ];
    // }

    public function getTabs(): array
    {
        return [
            null => Tab::make('Todo'),
            'new' => Tab::make('Nuevo')->query(fn($query) => $query->where('status', 'new')),
            'processing' => Tab::make('Procesando')->query(fn($query) => $query->where('status', 'processing')),
            'shipped' => Tab::make('Enviado')->query(fn($query) => $query->where('status', 'shipped')),
            'delivered' => Tab::make('Entregado')->query(fn($query) => $query->where('status', 'delivered')),
            'canceled' => Tab::make('Cancelado')->query(fn($query) => $query->where('status', 'canceled')),
        ];
    }
}

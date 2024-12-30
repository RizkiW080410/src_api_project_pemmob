<?php

namespace App\Filament\Admin\Resources\OrderResource\Api\Handlers;

use App\Filament\Admin\Resources\OrderResource;
use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use Spatie\QueryBuilder\QueryBuilder;

class DetailHandler extends Handlers
{
    public static ?string $uri = '/{id}';

    public static ?string $resource = OrderResource::class;

    public function handler(Request $request)
    {
        $id = $request->route('id');

        $query = QueryBuilder::for(static::getModel())
            ->with(['products', 'employee', 'discount', 'client', 'branchCompany'])
            ->whereHas('client.employee', fn ($query) => $query->where('user_id', auth()->user()->id))
            ->whereHas('branchCompany.employee', fn ($query) => $query->where('user_id', auth()->user()->id))
            ->where('id', $id)
            ->first();

        if (!$query) {
            return static::sendNotFoundResponse();
        }

        return static::sendSuccessResponse($query, 'Order Details Retrieved Successfully');
    }
}

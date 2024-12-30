<?php

namespace App\Filament\Admin\Resources\OrderResource\Api\Handlers;

use App\Filament\Admin\Resources\OrderResource;
use Rupadana\ApiService\Http\Handlers;
use Spatie\QueryBuilder\QueryBuilder;

class PaginationHandler extends Handlers
{
    public static ?string $uri = '/';

    public static ?string $resource = OrderResource::class;

    public function handler()
    {
        $query = QueryBuilder::for(static::getModel())
            ->allowedFilters(['status', 'employee_id', 'discount_id', 'client_id', 'branch_company_id'])
            ->with(['products', 'employee', 'discount', 'client', 'branchCompany'])
            ->whereHas('client.employee', fn ($query) => $query->where('user_id', auth()->user()->id))
            ->whereHas('branchCompany.employee', fn ($query) => $query->where('user_id', auth()->user()->id))
            ->paginate(request()->query('per_page', 10))
            ->appends(request()->query());

        return static::sendSuccessResponse($query, 'Order List Retrieved Successfully');
    }
}

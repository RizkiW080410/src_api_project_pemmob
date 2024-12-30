<?php

namespace App\Filament\Admin\Resources\EmployeeResource\Api\Handlers;

use App\Filament\Admin\Resources\EmployeeResource;
use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use Spatie\QueryBuilder\QueryBuilder;

class DetailHandler extends Handlers
{
    public static ?string $uri = '/{id}';

    public static ?string $resource = EmployeeResource::class;

    public function handler(Request $request)
    {
        $id = $request->route('id');

        $query = QueryBuilder::for(static::getModel())
            ->with(['department', 'branch_company', 'client', 'user', 'orders'])
            ->where('id', $id)
            ->first();

        if (!$query) {
            return static::sendNotFoundResponse();
        }

        return static::sendSuccessResponse($query, 'Employee Details Retrieved Successfully');
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DeviceModelNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ModelNotificationController extends Controller
{
    public function store(Request $request, string $uuid): JsonResponse
    {
        $request->validate([
            'models' => ['required', 'array'],
            'models.*.name' => ['required', 'string', 'max:255'],
            'models.*.display_name' => ['required', 'string', 'max:255'],
            'models.*.size_gb' => ['required', 'numeric', 'min:0'],
            'models.*.version' => ['nullable', 'string', 'max:255'],
        ]);

        $device = $request->attributes->get('device');
        $incomingModels = collect($request->input('models'));

        $existingModelNames = DeviceModelNotification::where('device_id', $device->id)
            ->pluck('model_name')
            ->all();

        $newModels = $incomingModels->filter(
            fn (array $model) => ! in_array($model['name'], $existingModelNames, true)
        )->values();

        if ($newModels->isNotEmpty()) {
            $now = now();

            DB::table('device_model_notifications')->insert(
                $newModels->map(fn (array $model) => [
                    'device_id' => $device->id,
                    'model_name' => $model['name'],
                    'version' => $model['version'] ?? null,
                    'notified_at' => $now,
                    'created_at' => $now,
                    'updated_at' => $now,
                ])->all()
            );
        }

        return response()->json([
            'new_models' => $newModels->all(),
        ]);
    }
}

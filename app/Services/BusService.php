<?php

namespace App\Services;

use App\Models\Bus;
use Exception;
use Illuminate\Support\Facades\DB;

class BusService
{

    /**
     * @param Bus $bus
     */
    public function __construct(
        public Bus $bus,
    )
    {
    }

    /**
     * @param $userId
     */
    public function findBusById($userId)
    {
        return $this->query()->find($userId);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function query()
    {
        return $this->bus->query();
    }

    /**
     * @param array $search
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function list(array $search = [])
    {
        $searchString = data_get($search, 'searchString', false);
        $searchFriday = data_get($search, 'searchFriday', false);
        $searchSaturday = data_get($search, 'searchSaturday', false);
        $searchSunday = data_get($search, 'searchSunday', false);
        $query = $this->query();

        if ($searchString) {
            $query = $query->where(function ($query) use ($searchString) {
                return $query->whereHas('passenger', function ($query) use ($searchString) {
                    return $query->where('name', 'like', "%{$searchString}%")
                        ->orWhere('doc', 'like', "%{$searchString}%");
                })
                    ->orWhere('obs', 'like', "%{$searchString}%");
            });
        }

        if ($searchFriday) {
            $query = $query->where('friday', $searchFriday);
        }

        if ($searchSaturday) {
            $query = $query->where('saturday', $searchSaturday);
        }

        if ($searchSunday) {
            $query = $query->where('sunday', $searchSunday);
        }

        return $query;
    }

    /**
     * @param Bus $bus
     * @return array
     */
    public function update(Bus $bus)
    {
        return $this->create($bus);
    }

    /**
     * @param Bus $bus
     * @return array
     */
    public function create(Bus $bus)
    {
        try {
            DB::transaction(function () use ($bus) {
                if (!empty($bus->amount)) {
                    $bus->amount = priceFormat($bus->amount);
                } else {
                    $bus->amount = 0;
                }

                if (!$bus->save())
                    throw new Exception('Erro ao inserir no banco de dados');

                return true;
            });

        } catch (Exception $exception) {
            $errors = json_decode($exception->getMessage(), true);
            if (isset($errors['errors'])) {
                return ['success' => false, 'message' => $errors['errors']];
            } else {
                return ['success' => false, 'message' => $exception->getMessage()];
            }
        }

        return ['success' => true, 'message' => 'Registro salvo com sucesso'];
    }

    /**
     * @param Bus $bus
     * @return bool|null
     */
    public function delete(Bus $bus)
    {
        return $bus->delete();
    }
}

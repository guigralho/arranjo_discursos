<?php

namespace App\Services;

use App\Models\Passenger;
use Exception;
use Illuminate\Support\Facades\DB;

class PassengerService
{

    /**
     * @param Passenger $passenger
     */
    public function __construct(
        public Passenger $passenger,
    )
    {
    }

    /**
     * @param $userId
     */
    public function findPassengerById($userId)
    {
        return $this->query()->find($userId);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function query()
    {
        return $this->passenger->query();
    }

    /**
     * @param array $search
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function list(array $search = [])
    {
        $searchString = data_get($search, 'searchString', false);
        $query = $this->query();

        if ($searchString) {
            $query = $query->where(function ($query) use ($searchString) {
                return $query->where('name', 'like', "%{$searchString}%")
                    ->orWhere('phone', 'like', "%{$searchString}%")
                    ->orWhere('doc', 'like', "%{$searchString}%");
            });
        }

        return $query;
    }

    /**
     * @param Passenger $passenger
     * @return array
     */
    public function update(Passenger $passenger)
    {
        return $this->create($passenger);
    }

    /**
     * @param Passenger $passenger
     * @return array
     */
    public function create(Passenger $passenger)
    {
        try {
            DB::transaction(function () use ($passenger) {
                if (!$passenger->save())
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
     * @param Passenger $passenger
     * @return bool|null
     */
    public function delete(Passenger $passenger)
    {
        return $passenger->delete();
    }
}

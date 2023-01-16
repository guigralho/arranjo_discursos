<?php

namespace App\Services;

use App\Models\Schedule;
use Exception;
use Illuminate\Support\Facades\DB;

class ScheduleService
{

    /**
     * @param Schedule $schedule
     */
    public function __construct(
        public Schedule $schedule,
    )
    {
    }

    /**
     * @param $userId
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public function findScheduleById($userId)
    {
        return $this->query()->find($userId);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function query()
    {
        return $this->schedule->query();
    }

    /**
     * @param array $search
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function list(array $search = [])
    {
        $searchString = data_get($search, 'searchString', false);
        $searchDate = data_get($search, 'searchDate', false);
        $query = $this->query();

        if ($searchString) {
            $query = $query->where(function ($query) use ($searchString) {
                return $query->where('congregation', 'like', "%{$searchString}%");
            });
        }

        if ($searchDate) {
            dd(dateConverter($searchDate));
            $query = $query->where('month', dateConverter($searchDate));
        }

        return $query;
    }

    /**
     * @param Schedule $schedule
     * @return array
     */
    public function update(Schedule $schedule)
    {
        return $this->create($schedule);
    }

    /**
     * @param Schedule $schedule
     * @return array
     */
    public function create(Schedule $schedule)
    {

        try {
            DB::transaction(function () use ($schedule) {
                if (!is_null($schedule->month))
                    $schedule->month = dateConverter($schedule->month);

                if (!$schedule->save())
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
     * @param Schedule $schedule
     * @return bool|null
     */
    public function delete(Schedule $schedule)
    {
        return $schedule->delete();
    }
}

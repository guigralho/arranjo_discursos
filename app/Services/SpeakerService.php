<?php

namespace App\Services;

use App\Models\Speaker;
use Exception;
use Illuminate\Support\Facades\DB;

class SpeakerService
{

    /**
     * @param Speaker $speaker
     */
    public function __construct(
        public Speaker $speaker,
    )
    {
    }

    /**
     * @param $userId
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public function findSpeakerById($userId)
    {
        return $this->query()->find($userId);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function query()
    {
        return $this->speaker->query();
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
                return $query->where('privilege', $searchString)
                    ->orWhere('name', 'like', "%{$searchString}%");
            });
        }

        return $query;
    }

    /**
     * @param Speaker $speaker
     * @return array
     */
    public function update(Speaker $speaker)
    {
        return $this->create($speaker);
    }

    /**
     * @param Speaker $speaker
     * @return array
     */
    public function create(Speaker $speaker)
    {
        try {
            DB::transaction(function () use ($speaker) {
                if (!$speaker->save())
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
     * @param Speaker $speaker
     * @return bool|null
     */
    public function delete(Speaker $speaker)
    {
        return $speaker->delete();
    }
}

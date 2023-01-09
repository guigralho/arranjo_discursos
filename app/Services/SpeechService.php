<?php

namespace App\Services;

use App\Models\Speech;
use Exception;
use Illuminate\Support\Facades\DB;

class SpeechService
{

    /**
     * @param Speech $speech
     */
    public function __construct(
        public Speech $speech,
    )
    {
    }

    /**
     * @param $userId
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public function findSpeechById($userId)
    {
        return $this->query()->find($userId);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function query()
    {
        return $this->speech->query();
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
                return $query->where('number', 'like', "%{$searchString}%")
                    ->orWhere('theme', 'like', "%{$searchString}%");
            });
        }

        return $query;
    }

    /**
     * @param Speech $speech
     * @return array
     */
    public function update(Speech $speech)
    {
        return $this->create($speech);
    }

    /**
     * @param Speech $speech
     * @return array
     */
    public function create(Speech $speech)
    {
        try {
            DB::transaction(function () use ($speech) {
                if (!$speech->save())
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
     * @param Speech $speech
     * @return bool|null
     */
    public function delete(Speech $speech)
    {
        return $speech->delete();
    }
}

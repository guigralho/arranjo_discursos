<?php

namespace App\Observers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class DeleteObserver
{
    /**
     * @param Model $model
     * @return void
     */
    public function deleted(Model $model)
    {
        if (!$model->isForceDeleting()){
            $model->user_deleted_id = Auth::user()->id;
            $model->save();
        }
    }
}

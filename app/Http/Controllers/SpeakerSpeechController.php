<?php

namespace App\Http\Controllers;

use App\Models\Speaker;
use App\Models\Speech;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;

class SpeakerSpeechController extends Controller
{
    public function __construct(public Request $request)
    {
    }

    public function store($speakerId)
    {
        $this->request->validate([
            'speech_id' => ['required', Rule::exists(Speech::class, 'id')],
        ]);

        $speaker = Speaker::find($speakerId);

        $speaker->speeches()->attach($this->request->speech_id, ['created_at' => now()]);

        Session::flash('message', ['value' => 'Tema incluído!', 'uuid' => uniqid()]);

        return Redirect::back();
    }

    public function destroy($speakerId, $speechId)
    {
        $speaker = Speaker::find($speakerId);

        $speaker->speeches()->detach($speechId);

        Session::flash('message', ['value' => 'Excluído com sucesso!', 'uuid' => uniqid()]);

        return Redirect::back();
    }
}

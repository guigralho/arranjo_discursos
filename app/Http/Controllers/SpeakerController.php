<?php

namespace App\Http\Controllers;

use App\Http\Requests\SpeakerRequest;
use App\Models\Speaker;
use App\Models\Speech;
use App\Services\SpeakerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;

class SpeakerController extends Controller
{
    public function __construct(public SpeakerService $speakerService, public Request $request)
    {
    }

    public function index()
    {
        $search = [];

        if ($this->request->has('search'))
            $search['searchString'] = $this->request->get('search');

        $list = $this->speakerService
            ->list($search)
            ->paginate($this->request->get('perPage', 10))
            ->withQueryString()
            ->onEachSide(1);

        return Inertia::render('Speaker/List', [
            'name' => 'Oradores',
            'list' => $list,
            'filters' => $this->request->only('search')
        ]);
    }

    public function store(SpeakerRequest $speakerRequest)
    {

        $speaker = new Speaker();
        $speaker->name = $speakerRequest->name;
        $speaker->privilege = $speakerRequest->privilege;

        $speaker->user_created_id = $speakerRequest->user()->id;

        $this->speakerService->create($speaker);

        Session::flash('message', ['value' => 'Criado com sucesso!', 'uuid' => uniqid()]);

        return Redirect::route('speakers.show', $speaker->id);
    }

    public function create()
    {
        return Inertia::render('Speaker/Create', [
            'name' => 'Novo orador',
        ]);
    }

    public function show($speaker)
    {
        $name = 'Alterar orador';
        $speaker = Speaker::whereId($speaker)->with('userCreated')->with('userUpdated')->first();

        $speeches = Speech::query()->whereNotIn('id', $speaker->speeches()->get()->pluck('pivot.speech_id'))->get();

        $mySpeeches = $speaker->speeches()->orderBy('number')->paginate(10);

        return Inertia::render('Speaker/Show', compact('name', 'speaker', 'mySpeeches', 'speeches'));
    }

    public function update(SpeakerRequest $speakerRequest, Speaker $speaker)
    {
        $speaker->name = $speakerRequest->name;
        $speaker->privilege = $speakerRequest->privilege;

        $speaker->user_updated_id = $speakerRequest->user()->id;

        $this->speakerService->create($speaker);

        Session::flash('message', ['value' => 'Atualizado com sucesso!', 'uuid' => uniqid()]);

        return Redirect::route('speakers.show', $speaker->id);
    }

    public function destroy(Speaker $speaker)
    {
        $this->speakerService->delete($speaker);

        Session::flash('message', ['value' => 'Excluído com sucesso!', 'uuid' => uniqid()]);

        return Redirect::back();
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\SpeechRequest;
use App\Models\Speech;
use App\Services\SpeechService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;

class SpeechController extends Controller
{
    public function __construct(public SpeechService $speechService, public Request $request)
    {
    }

    public function index()
    {
        $search = [];

        if ($this->request->has('search'))
            $search['searchString'] = $this->request->get('search');

        if ($this->request->filled('hasSpeakers'))
            $search['hasSpeakers'] = $this->request->get('hasSpeakers');

        $field = $this->request->filled('orderField') ? $this->request->get('orderField') : 'id';
        $dir = $this->request->filled('orderDir') ? $this->request->get('orderDir') : 'asc';

        $list = $this->speechService
            ->list($search)
            ->with('speakers')
            ->orderBy($field, $dir)
            ->paginate($this->request->get('perPage', 10))
            ->withQueryString()
            ->onEachSide(1);

        return Inertia::render('Speech/List', [
            'name' => 'Discursos',
            'list' => $list,
            'filters' => $this->request->only(['search', 'orderField', 'orderDir', 'page', 'hasSpeakers'])
        ]);
    }

    public function store(SpeechRequest $speechRequest)
    {

        $speech = new Speech();
        $speech->number = $speechRequest->number;
        $speech->theme = $speechRequest->theme;

        $speech->user_created_id = $speechRequest->user()->id;

        $this->speechService->create($speech);

        Session::flash('message', ['value' => 'Criado com sucesso!', 'uuid' => uniqid()]);

        return Redirect::route('speeches.index');
    }

    public function create()
    {
        return Inertia::render('Speech/Create', [
            'name' => 'Novo discurso',
        ]);
    }

    public function show($speech)
    {
        $speech = Speech::whereId($speech)->with('userCreated')->with('userUpdated')->first();

        return Inertia::render('Speech/Show', [
            'name' => 'Alterar discurso',
            'speech' => $speech
        ]);
    }

    public function update(SpeechRequest $speechRequest, Speech $speech)
    {
        $speech->number = $speechRequest->number;
        $speech->theme = $speechRequest->theme;

        $speech->user_updated_id = $speechRequest->user()->id;

        $this->speechService->create($speech);

        Session::flash('message', ['value' => 'Atualizado com sucesso!', 'uuid' => uniqid()]);

        return Redirect::route('speeches.show', $speech->id);
    }

    public function destroy(Speech $speech)
    {
        $this->speechService->delete($speech);

        Session::flash('message', ['value' => 'Excluído com sucesso!', 'uuid' => uniqid()]);

        return Redirect::back();
    }
}

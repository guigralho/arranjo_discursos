<?php

namespace App\Http\Controllers;

use App\Http\Requests\SpeechRequest;
use App\Models\Speech;
use App\Services\SpeechService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

        if ($this->request->filled('moreThan2Years'))
            $search['moreThan2Years'] = $this->request->get('moreThan2Years');

        $field = $this->request->filled('orderField') ? $this->request->get('orderField') : 'speeches.id';
        $dir = $this->request->filled('orderDir') ? $this->request->get('orderDir') : 'asc';

        $query = $this->speechService
            ->list($search)
            ->select('speeches.*')
            ->with(['speakers', 'lastMade'])
            ->join('receive_speakers', 'receive_speakers.speech_id', '=', 'speeches.id', 'left')
            ->groupBy('speeches.id');

        if ($field == 'max(receive_speakers.date)') {
            $query = $query->orderBy(DB::raw($field), $dir)
                ->orderBy('number', 'asc');
        } else {
            $query = $query->orderBy(DB::raw($field), $dir);
        }

        $list = $query->paginate($this->request->get('perPage', 10))
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
        $speech = Speech::whereId($speech)->with(['userCreated', 'userUpdated', 'speakers'])->first();

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

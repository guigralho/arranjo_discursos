<?php

namespace App\Http\Controllers;

use App\Services\SpeechService;
use Illuminate\Http\Request;
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

        $list = $this->speechService
            ->list($search)
            ->paginate($this->request->get('perPage', 10))
            ->withQueryString()
            ->onEachSide(1);

        return Inertia::render('Speech/List', [
            'name' => 'Discursos',
            'list' => $list
        ]);
    }
}

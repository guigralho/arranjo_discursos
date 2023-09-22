<?php

namespace App\Http\Controllers;

use App\Http\Requests\PassengerRequest;
use App\Models\Passenger;
use App\Services\PassengerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;

class PassengerController extends Controller
{
    public function __construct(public PassengerService $passengerService, public Request $request)
    {
    }

    public function index()
    {
        $search = [];

        if ($this->request->has('search'))
            $search['searchString'] = $this->request->get('search');

        $field = $this->request->filled('orderField') ? $this->request->get('orderField') : 'passengers.name';
        $dir = $this->request->filled('orderDir') ? $this->request->get('orderDir') : 'asc';

        $list = $this->passengerService
            ->list($search)
            ->orderBy(DB::raw($field), $dir)
            ->paginate($this->request->get('perPage', 10))
            ->withQueryString()
            ->onEachSide(1);

        return Inertia::render('Passenger/List', [
            'name' => 'Passageiros',
            'list' => $list,
            'filters' => $this->request->only(['search', 'orderField', 'orderDir', 'page', 'hasSpeakers'])
        ]);
    }

    public function store(PassengerRequest $passengerRequest)
    {

        $passenger = new Passenger();
        $passenger->name = $passengerRequest->name;
        $passenger->doc = $passengerRequest->doc;
        $passenger->phone = $passengerRequest->phone;

        $passenger->user_created_id = $passengerRequest->user()->id;

        $this->passengerService->create($passenger);

        Session::flash('message', ['value' => 'Criado com sucesso!', 'uuid' => uniqid()]);

        return Redirect::route('passengers.index');
    }

    public function create()
    {
        return Inertia::render('Passenger/Create', [
            'name' => 'Novo passageiro',
        ]);
    }

    public function show($passenger)
    {
        $passenger = Passenger::whereId($passenger)->with(['userCreated', 'userUpdated'])->first();

        return Inertia::render('Passenger/Show', [
            'name' => 'Alterar passageiro',
            'passenger' => $passenger
        ]);
    }

    public function update(PassengerRequest $passengerRequest, Passenger $passenger)
    {
        $passenger->name = $passengerRequest->name;
        $passenger->doc = $passengerRequest->doc;
        $passenger->phone = $passengerRequest->phone;

        $passenger->user_updated_id = $passengerRequest->user()->id;

        $this->passengerService->create($passenger);

        Session::flash('message', ['value' => 'Atualizado com sucesso!', 'uuid' => uniqid()]);

        return Redirect::route('passengers.show', $passenger->id);
    }

    public function destroy(Passenger $passenger)
    {
        $this->passengerService->delete($passenger);

        Session::flash('message', ['value' => 'Excluído com sucesso!', 'uuid' => uniqid()]);

        return Redirect::back();
    }
}

<?php

namespace App\Http\Controllers;

use App\Exports\BusListExport;
use App\Http\Requests\BusRequest;
use App\Models\Bus;
use App\Models\Passenger;
use App\Services\BusService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class BusController extends Controller
{
    public function __construct(public BusService $busService, public Request $request)
    {
    }

    public function index()
    {
        $search = [];

        if ($this->request->has('search'))
            $search['searchString'] = $this->request->get('search');

        if ($this->request->has('friday'))
            $search['searchFriday'] = $this->request->get('friday') == 'true' ? 1 : 0;

        if ($this->request->has('saturday'))
            $search['searchSaturday'] = $this->request->get('saturday') == 'true' ? 1 : 0;

        if ($this->request->has('sunday'))
            $search['searchSunday'] = $this->request->get('sunday') == 'true' ? 1 : 0;

        $field = $this->request->filled('orderField') ? $this->request->get('orderField') : 'buses.id';
        $dir = $this->request->filled('orderDir') ? $this->request->get('orderDir') : 'asc';

        $list = $this->busService
            ->list($search)
            ->with('passenger')
            ->select('buses.*')
            ->join('passengers', 'buses.passenger_id', '=', 'passengers.id', 'left')
            ->orderBy(DB::raw($field), $dir)
            ->paginate($this->request->get('perPage', 50))
            ->withQueryString()
            ->onEachSide(1);

        $totais = $this->busService
            ->list($search)
            ->select(DB::raw('sum(buses.friday) as friday, sum(buses.saturday) as saturday, sum(buses.sunday) as sunday, sum(buses.amount) as amount'))
            ->first();

        $totais->friday = $totais->friday ?? 0;
        $totais->saturday = $totais->saturday ?? 0;
        $totais->sunday = $totais->sunday ?? 0;
        $totais->amount = $totais->amount ?? 0;

        return Inertia::render('Bus/List', [
            'name' => 'Oradores',
            'list' => $list,
            'totais' => $totais,
            'filters' => $this->request->only(['search', 'friday', 'saturday', 'sunday', 'orderField', 'orderDir', 'page'])
        ]);
    }

    public function store(BusRequest $busRequest)
    {
        $bus = new Bus();
        $bus->passenger_id = $busRequest->passenger_id;
        $bus->friday = $busRequest->friday;
        $bus->saturday = $busRequest->saturday;
        $bus->sunday = $busRequest->sunday;
        $bus->amount = $busRequest->amount;
        $bus->obs = $busRequest->obs;

        $bus->user_created_id = $busRequest->user()->id;

        $this->busService->create($bus);

        Session::flash('message', ['value' => 'Criado com sucesso!', 'uuid' => uniqid()]);

        return Redirect::route('bus.index');
    }

    public function create()
    {
        return Inertia::render('Bus/Create', [
            'passengers' => Passenger::query()->orderBy('name')->get(),
        ]);
    }

    public function show($bu)
    {
        $bus = Bus::whereId($bu)
            ->with(['passenger'])
            ->first();

        $passengers = Passenger::query()->orderBy('name')->get();

        return Inertia::render('Bus/Show', compact('bus', 'passengers'));
    }

    public function update(BusRequest $busRequest, Bus $bu)
    {
        $bu->passenger_id = $busRequest->passenger_id;
        $bu->friday = $busRequest->friday;
        $bu->saturday = $busRequest->saturday;
        $bu->sunday = $busRequest->sunday;
        $bu->amount = $busRequest->amount;
        $bu->obs = $busRequest->obs;

        $bu->user_updated_id = $busRequest->user()->id;

        $this->busService->update($bu);

        Session::flash('message', ['value' => 'Atualizado com sucesso!', 'uuid' => uniqid()]);

        return Redirect::route('bus.index');
    }

    public function destroy(Bus $bus)
    {
        $this->busService->delete($bus);

        Session::flash('message', ['value' => 'Excluído com sucesso!', 'uuid' => uniqid()]);

        return Redirect::back();
    }

    public function downloadList()
    {
        $export = new BusListExport();

        return Excel::download($export, 'arranjo_onibus.xlsx');
    }
}

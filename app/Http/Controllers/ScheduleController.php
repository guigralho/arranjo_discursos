<?php

namespace App\Http\Controllers;

use App\Http\Requests\ScheduleRequest;
use App\Models\Schedule;
use App\Models\Speech;
use App\Services\ScheduleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;

class ScheduleController extends Controller
{
    public function __construct(public ScheduleService $scheduleService, public Request $request)
    {
    }

    public function index()
    {
        $search = [];

        if ($this->request->has('search'))
            $search['searchString'] = $this->request->get('search');

        if ($this->request->has('searchDate'))
            $search['searchDate'] = $this->request->get('searchDate');

        $field = $this->request->filled('orderField') ? $this->request->get('orderField') : 'month';
        $dir = $this->request->filled('orderDir') ? $this->request->get('orderDir') : 'desc';

        $list = $this->scheduleService
            ->list($search)
            ->orderBy($field, $dir)
            ->paginate($this->request->get('perPage', 10))
            ->withQueryString()
            ->onEachSide(1);

        return Inertia::render('Schedule/List', [
            'name' => 'Arranjos',
            'list' => $list,
            'filters' => $this->request->only(['search', 'searchDate', 'orderField', 'orderDir', 'page'])
        ]);
    }

    public function store(ScheduleRequest $scheduleRequest)
    {
        $schedule = new Schedule();
        $schedule->month = $scheduleRequest->formatted_month;
        $schedule->congregation = $scheduleRequest->congregation;
        $schedule->day = $scheduleRequest->day;
        $schedule->hour = $scheduleRequest->hour;
        $schedule->address = $scheduleRequest->address;
        $schedule->contact = $scheduleRequest->contact;
        $schedule->phone = $scheduleRequest->phone;

        $schedule->user_created_id = $scheduleRequest->user()->id;

        $this->scheduleService->create($schedule);

        Session::flash('message', ['value' => 'Criado com sucesso!', 'uuid' => uniqid()]);

        return Redirect::route('schedules.show', $schedule->id);
    }

    public function create()
    {
        return Inertia::render('Schedule/Create', [
            'name' => 'Novo arranjo',
        ]);
    }

    public function show($schedule)
    {
        $name = 'Alterar arranjo';
        $schedule = Schedule::whereId($schedule)->with('userCreated')->with('userUpdated')->first();
        $speeches = Speech::get();

        return Inertia::render('Schedule/Show', compact('name', 'schedule', 'speeches'));
    }

    public function update(ScheduleRequest $scheduleRequest, Schedule $schedule)
    {
        $schedule->month = $scheduleRequest->formatted_month;
        $schedule->congregation = $scheduleRequest->congregation;
        $schedule->day = $scheduleRequest->day;
        $schedule->hour = $scheduleRequest->hour;
        $schedule->address = $scheduleRequest->address;
        $schedule->contact = $scheduleRequest->contact;
        $schedule->phone = $scheduleRequest->phone;

        $schedule->user_updated_id = $scheduleRequest->user()->id;

        $this->scheduleService->create($schedule);

        Session::flash('message', ['value' => 'Atualizado com sucesso!', 'uuid' => uniqid()]);

        return Redirect::route('schedules.show', $schedule->id);
    }

    public function destroy(Schedule $schedule)
    {
        $this->scheduleService->delete($schedule);

        Session::flash('message', ['value' => 'Excluído com sucesso!', 'uuid' => uniqid()]);

        return Redirect::back();
    }
}

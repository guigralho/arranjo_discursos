<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\ReceiveSpeakers;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {
        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $meetingDayChanged = $request->user()->isDirty('meeting_day');
        $oldMeetingDay = $request->user()->getOriginal('meeting_day');

        $request->user()->save();

        if ($meetingDayChanged && $oldMeetingDay !== null && $request->user()->meeting_day !== null) {
            $this->moveReceiveDates((int) $oldMeetingDay, (int) $request->user()->meeting_day);
        }

        return Redirect::route('profile.edit');
    }

    /**
     * Move as datas de recebimento programadas (mês atual em diante) para o novo dia de reunião.
     * Datas que cruzam o mês são excluídas; ocorrências novas do dia dentro do mês são criadas vazias.
     */
    private function moveReceiveDates(int $oldDay, int $newDay): void
    {
        $offset = (($newDay - $oldDay + 10) % 7) - 3;

        Schedule::where('month', '>=', now()->startOfMonth()->format('Y-m-d'))
            ->with('toReceive')
            ->get()
            ->each(function (Schedule $schedule) use ($oldDay, $newDay, $offset) {
                $month = Carbon::parse($schedule->month);

                foreach ($schedule->toReceive as $receive) {
                    $date = Carbon::parse($receive->getRawOriginal('date'));

                    if ($date->dayOfWeek !== $oldDay) {
                        continue;
                    }

                    $date->addDays($offset);

                    if ($date->isSameMonth($month)) {
                        $receive->update(['date' => $date->format('Y-m-d')]);
                    } else {
                        $receive->delete();
                    }
                }

                $this->createMissingReceiveDates($schedule, $month, $newDay);
            });
    }

    private function createMissingReceiveDates(Schedule $schedule, Carbon $month, int $meetingDay): void
    {
        $existingDates = $schedule->toReceive()->get()
            ->map(fn (ReceiveSpeakers $receive) => Carbon::parse($receive->getRawOriginal('date'))->format('Y-m-d'));

        foreach ($month->copy()->startOfMonth()->daysUntil($month->copy()->endOfMonth()) as $day) {
            $formattedDay = $day->format('Y-m-d');

            if ($day->dayOfWeek === $meetingDay && ! $existingDates->contains($formattedDay)) {
                $schedule->toReceive()->create(['date' => $formattedDay]);
            }
        }
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current-password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}

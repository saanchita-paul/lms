<?php

namespace App\Observers;

use App\Models\Clinic;

class ClinicObserver
{
    /**
     * Handle the Clinic "created" event.
     *
     * @param  \App\Models\Clinic  $clinic
     * @return void
     */
    public function created(Clinic $clinic)
    {
        $salesTrainingUrl = 'https://calendly.com/kim-middleton/discovery-call-clone';
        $marketingDashboardUrl = 'https://calendly.com/lmoore-2020/30min';
        $scheduleMeetingUrl = 'https://calendar.app.google/tHJraewsvXLWHPhU6';
        $successCoachUrl = 'https://calendar.app.google/mdy7S9c8V7KfoT7Q8';

        $clinic->update([
            'salestrainingurl' => $salesTrainingUrl,
            'marketingdashboardurl' => $marketingDashboardUrl,
            'schedulemeetingurl' => $scheduleMeetingUrl,
            'success_coach_url' => $successCoachUrl,
        ]);
    }

    /**
     * Handle the Clinic "updated" event.
     *
     * @param  \App\Models\Clinic  $clinic
     * @return void
     */
    public function updated(Clinic $clinic)
    {
        //
    }

    /**
     * Handle the Clinic "deleted" event.
     *
     * @param  \App\Models\Clinic  $clinic
     * @return void
     */
    public function deleted(Clinic $clinic)
    {
        //
    }

    /**
     * Handle the Clinic "restored" event.
     *
     * @param  \App\Models\Clinic  $clinic
     * @return void
     */
    public function restored(Clinic $clinic)
    {
        //
    }

    /**
     * Handle the Clinic "force deleted" event.
     *
     * @param  \App\Models\Clinic  $clinic
     * @return void
     */
    public function forceDeleted(Clinic $clinic)
    {
        //
    }
}

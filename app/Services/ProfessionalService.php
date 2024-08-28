<?php


namespace App\Services;

use App\Models\Booking;
use App\Models\Commission;
use App\Models\Professional;
use App\Models\ProfessionalTime;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;


class ProfessionalService
{
    public function getProfessionals($latitude = '', $longitude = '', $area = null, $skill = null, $phone = null)
    {
        $professionals = Professional::with('Skill', 'Area')
            ->select('*')
            ->selectRaw(
                '(3958.756 * acos(cos(radians(?)) * cos(radians(current_latitude)) * cos(radians(current_longitude) - radians(?)) + sin(radians(?)) * sin(radians(current_latitude)))) AS distance',
                [$latitude, $longitude, $latitude],
            )
            ->whereNotNull('current_latitude')
            ->whereNotNull('current_longitude')
            ->where('free_busy', 1) // Available
            ->where('online_offline', 1) // Online
            ->having('distance', '<', 50) // Example: filter professionals within 50 miles radius
            ->orderBy('distance', 'asc');

        if (!empty($phone)) {
            $professionals->where('phone_number', 'like', '%' . $phone . '%');
        }

        if (!empty($area)) {
            $country_area_id = (int) $area;
            $professionals->where('country_area_id', $country_area_id);
        }

        if (!empty($skill)) {
            $skill_category_id = (int) $skill;
            $professionals->whereHas('Skill', function ($query) use ($skill_category_id) {
                $query->where('skills.id', $skill_category_id);
            });
        }

        Log::info($professionals->toSql(), $professionals->getBindings());

        return $professionals->get();
    }

    public function createOnlineOffline($status, $professional)
    {
        $onlineTime = ($status == 1) ? Carbon::now() : '';
        $offlineTime = ($status == 1) ? '' : Carbon::now();

        $professional_time = ProfessionalTime::where('professional_id', $professional)->first();

        if (empty($professional_time)) {
            ProfessionalTime::create([
                'professional_id' => $professional,
                'time_intervals' => json_encode([['online_time' => $onlineTime, 'offline_time' => $offlineTime]]),
            ]);
        } else {
            $timeIntervals = json_decode($professional_time->time_intervals, true) ?: [];

            if (isset($timeIntervals[0]['online_time']) && Carbon::parse($timeIntervals[0]['online_time'])->isToday()) {
                $timeIntervals[] = ['online_time' => $onlineTime, 'offline_time' => $offlineTime];
                $professional_time->update(['time_intervals' => json_encode($timeIntervals)]);

            } else {
                ProfessionalTime::create([
                    'professional_id' => $professional,
                    'time_intervals' => json_encode([['online_time' => $onlineTime, 'offline_time' => $offlineTime]]),
                ]);
            }
        }
    }

    public function generateUniqueReferralCode()
    {
        $code = strtoupper(substr(md5(uniqid()), 0, 6));

        if (Professional::where('referral_code', $code)->exists())
            return $this->generateUniqueReferralCode();
        return $code;
    }

    public function profile_image_upload($image)
    {
        $extension = $image->getClientOriginalExtension();
        $imagename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
        $imagename = str_replace(' ', '_', $imagename);
        $imagename = substr($imagename, 0, 20);
        $imagename = $imagename . round(microtime(true) * 10) . '.' . $extension;
        $imageUrl = '/backend/professional/profile/' . $imagename;
        $image->move('backend/professional/profile/', $imagename);
        return $imageUrl;
    }

    public function companyCommissionCut($booking_id)
    {
        $commission = Commission::where('status', 1)->first();

        if (!empty($commission)) {
            Booking::find($booking_id)->update([
                'company_cut' => $commission->amount,
            ]);
        }
    }

    public function skill_image_upload($image)
    {
        $extension = $image->getClientOriginalExtension();
        $imagename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
        $imagename = str_replace(' ', '_', $imagename);
        $imagename = substr($imagename, 0, 20);
        $imagename = $imagename . round(microtime(true) * 10) . '.' . $extension;
        $imageUrl = '/backend/skill/' . $imagename;
        $image->move('backend/skill/', $imagename);
        return $imageUrl;
    }

}


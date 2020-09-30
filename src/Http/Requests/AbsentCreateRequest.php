<?php

namespace GGPHP\Absent\Http\Requests;

use Carbon\Carbon;
use GGPHP\Absent\Models\AbsentType;
use GGPHP\ShiftSchedule\Models\Holiday;
use Illuminate\Foundation\Http\FormRequest;

class AbsentCreateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $type = AbsentType::where('type', AbsentType::ANNUAL_LEAVE)->first();
        $awolType = AbsentType::where('type', AbsentType::AWOL)->first();

        return [
            'absent_type_id' => 'required|exists:absent_types,id',
            'absent_reason_id' => request('absent_type_id') == $awolType->id ? '' : 'required|exists:absent_reasons,id',
            'user_id' => 'required|exists:users,id',
            'store_id' => 'required|exists:stores,id',
            'start_date' => [
                'date',
                'date_format:Y-m-d',
                function ($attribute, $value, $fail) use ($type, $awolType) {
                    if (!is_null(request()->pass_validate_weekend) || request('absent_type_id') == $awolType->id ) {
                        return true;
                    }

                    if ((int) request('absent_type_id') == $type->id) {
                        $accessSameHoliday = $this->checkSameHoliday($value);
                        if ($accessSameHoliday === true) {
                            return true;
                        }
                        return $fail("Không được nghỉ vào ngày lễ " . $accessSameHoliday);
                    }
                    return true;
                },
                function ($attribute, $value, $fail) use ($type, $awolType) {
                    if (!is_null(request()->pass_validate_weekend) || request('absent_type_id') == $awolType->id ) {
                        return true;
                    }

                    if ((int) request('absent_type_id') == $type->id) {
                        $accessWeekend = $this->checkWeekend($value);
                        if ($accessWeekend === true) {
                            return true;
                        }
                        return $fail('Không được nghỉ vào thứ 6, thứ 7, chủ nhật');
                    }
                    return true;
                },
            ],
            'end_date' => [
                'date',
                'date_format:Y-m-d',
                'after_or_equal:start_date',
                function ($attribute, $value, $fail) use ($type, $awolType) {
                    if (!is_null(request()->pass_validate_weekend) || request('absent_type_id') == $awolType->id ) {
                        return true;
                    }

                    if ((int) request('absent_type_id') == $type->id) {
                        $accessWeekend = $this->checkWeekend($value);
                        if ($accessWeekend === true) {
                            return true;
                        }
                        return $fail('Không được nghỉ vào thứ 6, thứ 7, chủ nhật');
                    }
                    return;
                },
            ],
        ];
    }

    /**
     * @param $value
     * @return bool|string
     */
    private function checkSameHoliday($value)
    {
        $year = Carbon::parse($value)->format('Y');
        $date = Carbon::parse($value)->format('Y-m-d');
        $startDate = request()->start_date;
        $endDate = request()->end_date;

        if ($startDate === $endDate) {
            $holiday = Holiday::where('name', $year)->whereHas('holidayDetail', function ($query) use ($startDate) {
                $query->where('date', $startDate);
            })->first();

            if (!is_null($holiday)) {
                return Carbon::parse($date)->format('Y-m-d');
            }
        }

        $begin = new \DateTime($startDate);
        $end = new \DateTime($endDate);

        $interval = \DateInterval::createFromDateString('1 day');
        $period = new \DatePeriod($begin, $interval, $end);

        foreach ($period as $date) {
            $holiday = Holiday::where('name', $year)->whereHas('holidayDetail', function ($query) use ($date) {
                $query->where('date', $date);
            })->first();

            if (!is_null($holiday)) {
                return Carbon::parse($date)->format('Y-m-d');
            }
        }

        return true;
    }

    /**
     * @param $value
     * @return bool
     */
    private function checkWeekend($value)
    {
        $check = Carbon::parse($value)->format('l');
        if ($check === 'Friday' || $check === 'Saturday' || $check === 'Sunday') {
            return false;
        }

        return true;
    }
}

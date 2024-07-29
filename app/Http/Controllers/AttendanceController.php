<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\AttendanceUser;
use App\Models\Position;
use App\Models\SalaryInformation;
use App\Models\Shift;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AttendanceController extends Controller
{
    private int $hour;
    private string $date;
    private int $shift_id;

    public function __construct()
    {
        $now = Carbon::now('Asia/Bangkok');
        $this->date = $now->format('Y-m-d');
        $this->hour = (int) $now->format('H'); 

        switch (true) {
            case $this->hour >= 6 && $this->hour <= 11:
                $this->shift_id = 1;
                break;
            case $this->hour >= 12 && $this->hour <= 17:
                $this->shift_id = 2;
                break;
            case $this->hour >= 18 && $this->hour <= 24:
                $this->shift_id = 3;
                break;
            default:
                $this->shift_id = 3; 
                break;
        }
    }

    public function index()
    {
        $now = Carbon::now('Asia/Bangkok');
        $date = $now->format('d/m/Y');
        
        $users = User::selectRaw('users.id as id ,users.name as name, positions.name as role_name')
                        ->join('positions', 'users.role', '=', 'positions.id')
                        ->where('users.role', '<>', 1)
                        ->where('users.shift_id', $this->shift_id)
                        ->get();
        $shift = Shift::query()
                        ->where('id', $this->shift_id)
                        ->value('description');
        $attendance_id = Attendance::query()
                    ->where([
                        'shift_id' => $this->shift_id,
                        'date' => $this->date,
                    ])
                    ->orderByDesc('shift_id')
                    ->value('id');
        $statuses = [];
        if(!empty($attendance_id))
        {
            $attendances = AttendanceUser::query()
                        ->select([
                            'user_id',
                            'status',
                        ])
                        ->where('attendance_id', $attendance_id)
                        ->get();
            foreach($attendances as $attendance)
            {
                $statuses[$attendance->user_id] = (int)$attendance->status;
            }
            return view('attendance.index',[
                'users' => $users,
                'date' => $date,
                'shift' => $shift,
                'statuses' => $statuses,
            ]);
        }
        return view('attendance.index',[
            'users' => $users,
            'date' => $date,
            'shift' => $shift,
        ]);
    }

    public function attendance(Request $request)
    {
        // dd($request->all(), $this->hour, $this->date);
        $statuses = $request->get('statuses');
        $shift_id = $this->shift_id;
        // dd($statuses);
        //kiểm tra ngày đấy và ca đấy đã tồn tại trong cơ sở dữ liệu chưa
        $attendance = Attendance::query()
                    ->where([
                        'date' => $this->date,
                        'shift_id' => $shift_id,
                    ])->first();
        if(is_null($attendance))
        {
            $attendance = Attendance::create([
                'date' => $this->date,
                'shift_id' => $shift_id,
            ]);
        }
        
        foreach ($statuses as $user_id => $status) 
        {
            // echo $status;
            $attendance_user = AttendanceUser::where('attendance_id', $attendance->id)
                                             ->where('user_id', $user_id)
                                             ->first();

            if ($attendance_user) {
                AttendanceUser::query()
                            ->where('attendance_id', $attendance->id)
                            ->where('user_id', $user_id)
                            ->update([
                                'status' => $status,
                            ]);
            } else {  
                // dd('attendance user not found', $attendance->id, $user_id, $status);
                // dd($attendance->id, $user_id, $status);
                // AttendanceUser::create([
                //     'attendance_id' => $attendance->id,
                //     'user_id' => $user_id,
                //     'status' => (int) $status,
                // ]);
                $attendance_user = new AttendanceUser();
                $attendance_user->attendance_id = $attendance->id;
                $attendance_user->user_id = $user_id;
                $attendance_user->status = $status;
                $attendance_user->save();
            }
            // dd($attendance_user->status, $status);
            // if ($attendance_user) {
            //     dd($attendance_user->status, $status);
            // } else {
            //     dd('Attendance user not found', $status);
            // }

            $user = User::find($user_id);
            $position = Position::find($user->role);
            $last_salary_infor = SalaryInformation::query()
                                    ->where('user_id', $user_id)
                                    ->whereNull('payroll_date')
                                    ->orderBy('created_at', 'desc')
                                    ->first();
            
            // dd($last_salary_infor->created_at->diffInDays(Carbon::now()));
            // dd($last_salary_infor);
            // dd(($last_salary_infor->work_hours + $work_hours) * $position->salary);
            if($last_salary_infor && $last_salary_infor->created_at->diffInDays(Carbon::now()) <= 30)
            {
                // dd($attendance_user->status, $status);
                if($attendance_user && $attendance_user->status == 1 && (int)$status == 2)
                {
                    // dd(1);
                    $new_work_hours = $last_salary_infor->work_hours - 4;
                    $last_salary_infor->update([
                        'work_hours' => $new_work_hours,
                        'total_amount' => $new_work_hours * $position->salary,
                    ]);
                }
                if($attendance_user && $attendance_user->status == 2 && (int)$status == 1)
                {
                    // dd(2);
                    $work_hours = $status == 1 ? 4 : 0;
                    $last_salary_infor->update([
                        'work_hours' => $last_salary_infor->work_hours + $work_hours,
                        'total_amount' => ($last_salary_infor->work_hours + $work_hours) * $position->salary,
                    ]);
                }
                if($attendance_user && $attendance_user->status == 1 && (int)$status === 1)
                {
                    // dd(3);
                    // $work_hours = $status == 1 ? 4 : 0;
                    $new_work_hours = $last_salary_infor->work_hours + 4;
                    $last_salary_infor->update([
                        'work_hours' => $new_work_hours,
                        'total_amount' => $new_work_hours * $position->salary,
                    ]);
                }
            }
            else
            {
                if($last_salary_infor)
                {
                    $last_salary_infor->update([
                        'payroll_date' => Carbon::now(),
                    ]);
                }
                $work_hours = $status == 1 ? 4 : 0;
                SalaryInformation::create([
                    'user_id' => $user_id,
                    // 'payroll_date' => Carbon::now(),
                    'work_hours' => $work_hours,
                    'total_amount' => $work_hours * $position->salary,
                ]);
            }


            // if ($attendance_user) {
            //     // if($attendance_user->status == 1 && $status == 2)
            //     // {
            //     //     $work_hours -= 4;
            //     // } else {
            //     //     $work_hours = $status == 1 ? 4 : 0;
            //     // }
            //     // $attendance_user -> update([
            //     //     'status' => $status,
            //     // ]);
            //     AttendanceUser::query()
            //                 ->where('attendance_id', $attendance->id)
            //                 ->where('user_id', $user_id)
            //                 ->update([
            //                     'status' => $status,
            //                 ]);
            // } else {
            //     AttendanceUser::create([
            //         'attendance_id' => $attendance->id,
            //         'user_id' => $user_id,
            //         'status' => (int) $status,
            //     ]);
            // }

            
        }

        // foreach ($statuses as $user_id => $status) {
        //     // Log::info('Updating attendance for user', [
        //     //     'attendance_id' => $attendance->id,
        //     //     'user_id' => $user_id,
        //     //     'status' => $status
        //     // ]);
        //     // AttendanceUser::updateOrCreate(
        //     //     [
        //     //         'attendance_id' => $attendance->id,
        //     //         'user_id' => $user_id,
        //     //     ],
        //     //     [
        //     //         'status' => $status,
        //     //     ]
        //     // );

        //     $attendance_user = AttendanceUser::query()
        //                     ->where([
        //                         'attendance_id' => $attendance->id,
        //                         'user_id' => $user_id,
        //                     ])->first();
        //     if($attendance_user)
        //     {
                
        //         $attendance_user->status = (int)$status;
        //         $attendance_user->save();
        //     }else
        //     {
        //         Attendance::create([
        //             'attendance_id' => $attendance_id,
        //             'user_id' => $user_id,
        //             'status' => (int)$status,
        //         ]);
        //     }
        // }
        

        // foreach ($statuses as $user_id => $status) {
        //     $attendance_user = AttendanceUser::where('attendance_id', $attendance->id)
        //                                      ->where('user_id', $user_id)
        //                                      ->first();
        //     if ($attendance_user) {
        //         AttendanceUser::query()
        //                     ->where('attendance_id', $attendance->id)
        //                     ->where('user_id', $user_id)
        //                     ->update([
        //                         'status' => $status,
        //                     ]);
        //     } else {
        //         AttendanceUser::create([
        //             'attendance_id' => $attendance->id,
        //             'user_id' => $user_id,
        //             'status' => (int) $status,
        //         ]);
        //     }
        // }
        
        // foreach ($statuses as $user_id => $status) {
        //     $attendanceUser = AttendanceUser::firstOrNew([
        //         'attendance_id' => $attendance->id,
        //         'user_id' => $user_id,
        //     ]);
        //     $attendanceUser->status = $status;
        //     $attendanceUser->save();
        // }
        // $user_ids = array_keys($statuses);
        // $attendance_users = AttendanceUser::where('attendance_id', $attendance->id)
        //                                 ->whereIn('user_id', $user_ids)
        //                                 ->get()
        //                                 ->keyBy('user_id');
        
        // foreach ($statuses as $user_id => $status) {
        //     if (isset($attendance_users[$user_id])) {
        //         $attendance_users[$user_id]->update([
        //             'status' => $status,
        //         ]);
        //     } else {
        //         AttendanceUser::create([
        //             'attendance_id' => $attendance->id,
        //             'user_id' => $user_id,
        //             'status' => (int) $status,
        //         ]);
        //     }
        // }

        return redirect()->route('attendance.index');
    }
}

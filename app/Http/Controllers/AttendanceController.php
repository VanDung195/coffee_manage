<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\AttendanceUser;
use App\Models\Shift;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

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
                $this->shift_id = null; 
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
                $statuses[$attendance->user_id] = $attendance->status;
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
        foreach ($statuses as $user_id => $status) {
            // dd($attendance->id, $user_id, $status);
            AttendanceUser::updateOrCreate([
                'attendance_id' => $attendance->id,
                'user_id' => $user_id,
            ],[
                'status' => $status,
            ]);
        }

        return redirect()->route('attendance.index');
    }
}

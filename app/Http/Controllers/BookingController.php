<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BookingController extends Controller
{
    //create data
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [

            'booking_number' => 'required',
            'name_customer'  => 'required',
            'email_customer' => 'required',
            // 'booking_date' => 'required',
            'check_in_date' => 'required',
            'duration' => 'required',
            // 'check_out_date' => 'required',
            'guest_name' => 'required',
            'room_quantity' => 'required',
            'id_type_room' => 'required',
            // 'status_booking' => 'required',

        ]);

        if ($validator->fails()) {
            return Response()->json($validator->errors());
        }

        // menghitung durasi menginap
        $duration = $request->duration;
        $in = new Carbon($request->check_in_date);
        $out = $in->addDays($duration);
        $now = Carbon::now();


        $store = Booking::create([

            'booking_number' => $request->booking_number,
            'name_customer' => $request->name_customer,
            'email_customer' => $request->email_customer,
            'booking_date' => $now,
            'check_in_date' => $request->check_in_date,
            'check_out_date' => $out,
            'guest_name' => $request->guest_name,
            'room_quantity' => $request->room_quantity,
            'id_type_room' => $request->id_type_room,
            'status_booking' => 1,

        ]);

        $data = Booking::where('booking_number', '=', $request->booking_number)->get();
        if ($store) {
            return Response()->json([
                'status' => 1,
                'message' => 'Succes create new data!',
                'data' => $data
            ]);
        } else {
            return Response()->json([
                'status' => 0,
                'message' => 'Failed create new data!'
            ]);
        }
    }



    //read data
    public function show(Request $request)
    {
        return Booking::all();
        // $booking = DB::table('booking')
        //     ->select('booking.*', 'users.*')
        //     ->join('type_room', 'type_room.id_type_room', '=', 'booking.id_type_room')
        //     ->join('users', 'users.id_user', '=', 'booking.id_user')
        //     ->get();
        // return Response()->json($booking);


    }

    // filter receptionist by checkin
    public function filterByCheckIn($date)
    {
        if(Booking::where('check_in_date', $date)->exists()){
            $data = Booking::where('check_in_date', $date)->get();

            return response()->json([
                'data' => $data
            ]);
        }
    }

    // filter receptionist by name 
    public function filterByName($name)
    {
        if(Booking::where('guest_name', $name)->exists()){
            $data = Booking::where('guest_name', $name)->get();

            return response()->json([
                'data' => $data
            ]);
        }
    }



    public function detail($id)
    {
        if (DB::table('booking')->where('id_booking', $id)->exists()) {
            $detail_booking = DB::table('booking')
                ->select('booking.*')
                ->where('id_booking', '=', $id)
                ->get();
            return Response()->json($detail_booking);
        } else {
            return Response()->json(['message' => 'Could not find the data']);
        }
    }

    // update data 
    public function update($id, Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                // 'booking_number' => 'required',
                // 'name_customer'  => 'required',
                // 'email_customer' => 'required',
                // 'booking_date' => 'required',
                // 'check_in_date' => 'required',
                // 'check_out_date' => 'required',
                // 'guest_name' => 'required',
                // 'room_quantity' => 'required',
                // 'id_type_room' => 'required',
                'status_booking' => 'required',
                'id_user' => 'required',
            ]
        );

        if ($validator->fails()) {
            return Response()->json($validator->errors());
        }

        $update = DB::table('booking')
            ->where('id_booking', '=', $id)
            ->update(
                [
                    // 'booking_number' => $request->booking_number,
                    // 'name_customer' => $request->name_customer,
                    // 'email_customer' => $request->email_customer,
                    // 'booking_date' => $request->booking_date,
                    // 'check_in_date' => $request->check_in_date,
                    // 'check_out_date' => $request->check_out_date,
                    // 'guest_name' => $request->guest_name,
                    // 'room_quantity' => $request->room_quantity,
                    // 'id_type_room' => $request->id_type_room,
                    'status_booking' => $request->status_booking,
                    'id_user' => $request->id_user,
                ]
            );

        $data = Booking::where('id_booking', '=', $id)->get();
        if ($update) {
            return Response()->json([
                'status' => 1,
                'message' => 'Success updating data!',
                'data' => $data
            ]);
        } else {
            return Response()->json([
                'status' => 0,
                'message' => 'Failed updating data!'
            ]);
        }
    }

    //delete data 
    public function delete($id)
    {
        $delete = DB::table('booking')
            ->where('id_booking', '=', $id)
            ->delete();

        if ($delete) {
            return Response()->json([
                'status' => 1,
                'message' => 'Succes delete data!'
            ]);
        } else {
            return Response()->json([
                'status' => 0,
                'message' => 'Failed delete data!'
            ]);
        }
    }
}

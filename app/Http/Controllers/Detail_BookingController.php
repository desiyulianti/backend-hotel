<?php

namespace App\Http\Controllers;

use App\Models\Detail_Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class Detail_BookingController extends Controller
{
    //create data
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [

            'id_booking' => 'required',
            'id_room' => 'required',
            'access_date'  => 'required',
            'price' => 'required',

        ]);

        if ($validator->fails()) {
            return Response()->json($validator->errors());
        }

        $store = Detail_Booking::create([
            'id_booking' => $request->id_booking,
            'id_room' => $request->id_room,
            'access_date' => $request->access_date,
            'price' => $request->price,

        ]);

        $data = Detail_Booking::where('id_booking', '=', $request->id_booking)->get();
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
    public function show()
    {
        return Detail_Booking::all();
    }

    public function detail($id)
    {
        if (DB::table('detail_booking')->where('id_detail_booking', $id)->exists()) {
            $detail_booking = DB::table('detail_booking')
                ->select('detail_booking.*')
                ->where('id_detail_booking', '=', $id)
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
                'id_booking' => 'required',
                'id_room' => 'required',
                'access_date'  => 'required',
                'price' => 'required',
            ]
        );

        if ($validator->fails()) {
            return Response()->json($validator->errors());
        }

        $update = DB::table('detail_booking')
            ->where('id_detail_booking', '=', $id)
            ->update(
                [
                    'id_booking' => $request->id_booking,
                    'id_room' => $request->id_room,
                    'access_date' => $request->access_date,
                    'price' => $request->price,

                ]
            );

        $data = Detail_Booking::where('id_detail_booking', '=', $id)->get();
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
        $delete = DB::table('detail_booking')
            ->where('id_detail_booking', '=', $id)
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

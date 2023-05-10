<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class RoomController extends Controller
{
    // create data 
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'number_room' => 'required',
                'id_type_room' => 'required',
            ]
        );

        if ($validator->fails()) {
            return Response()->json($validator->errors());
        }

        $store = Room::create([
            'number_room' => $request->number_room,
            'id_type_room' => $request->id_type_room
        ]);

        $data = Room::where('number_room', '=', $request->number_room)->get();
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

    // read data 
    public function show()
    {
        $room = DB::table('room')
            ->select('room.*', 'type_room.type_room_name', 'type_room.price', 'type_room.desc', 'type_room.image')
            ->join('type_room', 'type_room.id_type_room', '=', 'room.id_type_room')
            ->get();
        return Response()->json($room);
    }

    public function detail($id)
    {
        if (DB::table('room')->where('id_room', $id)->exists()) {
            $detail_room = DB::table('room')
                ->select('room.*')
                ->where('id_room', '=', $id)
                ->get();
            return Response()->json($detail_room);
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
                'number_room' => 'required',
                'id_type_room' => 'required'
            ]
        );

        if ($validator->fails()) {
            return Response()->json($validator->errors());
        }

        $update = DB::table('room')
            ->where('id_room', '=', $id)
            ->update(
                [
                    'number_room' => $request->number_room,
                    'id_type_room' => $request->id_type_room

                ]
            );

        $data = Room::where('id_room', '=', $id)->get();
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
        $delete = DB::table('room')
            ->where('id_room', '=', $id)
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

<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Type_Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class TypeRoomController extends Controller
{
    //create data
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'type_room_name' => 'required',
                'price' => 'required',
                'desc' => 'required',
                // 'image' => 'required',
            ]
        );
        if ($validator->fails()) {
            return Response()->json($validator->errors());
        }

        $store = Type_Room::create([
            'type_room_name' => $request->type_room_name,
            'price' =>  $request->price,
            'desc' =>  $request->desc,
            //'image' =>  $request->image,
        ]);

        $data = Type_Room::where('type_room_name', '=', $request->type_room_name)->get();
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
        return Type_Room::all();
    }


    // public function searchRooms(Request $request)
    // {
    //     $check_in = $request->input('check_in_date');
    //     $check_out = $request->input('check_out_date');
    //     $room_type = $request->input('type_room_name');

    //     // Mengambil kamar-kamar yang tidak memiliki reservasi pada tanggal tertentu
    //     $available_rooms = Type_Room::whereDoesntHave('booking', function ($query) use ($check_in, $check_out) {
    //         $query->where(function ($query) use ($check_in, $check_out) {
    //             $query->where('check_in_date', '<', $check_out)
    //                 ->where('check_out_date', '>', $check_in);
    //         });
    //     })
    //         ->where('type_room_name', $room_type)
    //         ->get();

    //     // Mengirim data kamar-kamar yang tersedia ke view
    //     return view('rooms', compact('available_rooms', 'check_in', 'check_out', 'room_type'));
    // }

   
    //...

    public function availableRooms(Request $request)
    {
        $checkin = $request->input('check_in_date');
        $checkout = $request->input('check_out_date');
        $requestedType = $request->input('type');

        $availableRooms = Room::where('type', $requestedType)
            ->whereDoesntHave('bookings', function ($query) use ($checkin, $checkout) {
                $query->where(function ($query) use ($checkin, $checkout) {
                    $query->where('check_in_date', '>=', $checkin)
                        ->where('check_in_date', '<', $checkout);
                })->orWhere(function ($query) use ($checkin, $checkout) {
                    $query->where('check_out_date', '>', $checkin)
                        ->where('check_out_date', '<=', $checkout);
                })->orWhere(function ($query) use ($checkin, $checkout) {
                    $query->where('check_in_date', '<=', $checkin)
                        ->where('check_out_date', '>=', $checkout);
                });
            })
            ->get();

            return response()->json([
                'data' => $availableRooms
            ]);

        return view('available-rooms', compact('availableRooms'));
    }

    //...



    

    



    public function detail($id)
    {
        if (DB::table('type_room')->where('id_type_room', $id)->exists()) {
            $detail_type = DB::table('type_room')
                ->select('type_room.*')
                ->where('id_type_room', $id)
                ->get();
            return Response()->json($detail_type);
        } else {
            return Response()->json(['message' => 'Couldnt find the data']);
        }
    }

    //uplload image cover
    public function upload_room_image(Request $request, $id)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'image' => 'required|file|mimes:jpeg,png,jpg|max:2048',
            ]
        );

        if ($validator->fails()) {
            return Response()->json($validator->errors());
        }

        //define nama file yang akan di upload
        $imageName = time() . '.' . $request->image->extension();

        //proses upload
        $request->image->move(public_path('images'), $imageName);

        $update = Type_Room::where('id_type_room', $id)->update([
            'image' => $imageName
        ]);

        $data = Type_Room::where('id_type_room', '=', $id)->get();
        if ($update) {
            return Response()->json([
                'status' => 1,
                'message' => 'success upload image room !',
                'data' => $data
            ]);
        } else {
            return Response()->json([
                'status' => 0,
                'message' => 'failed upload image room !'
            ]);
        }
    }





    //update data
    public function update($id, Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'type_room_name' => 'required',
                'price' => 'required',
                'desc' => 'required',
                //'image' => 'required',
            ]
        );

        if ($validator->fails()) {
            return Response()->json($validator->errors());
        }

        $update = DB::table('type_room')
            ->where('id_type_room', '=', $id)
            ->update(
                [
                    'type_room_name' => $request->type_room_name,
                    'price' =>  $request->price,
                    'desc' =>  $request->desc,
                    // 'image' =>  $request->image,
                ]
            );

        $data = Type_Room::where('id_type_room', '=', $id)->get();
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
        $delete = DB::table('type_room')
            ->where('id_type_room', '=', $id)
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

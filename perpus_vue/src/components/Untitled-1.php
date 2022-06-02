<?php

namespace App\Http\Controllers;

use App\Buku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class BukuController extends Controller
{
    //greet user
    public function bookAuth(){
        $data = "Welcome " . Auth::user()->name;
        return response()->json($data, 200);
    }
    //greet user end

    //create data start
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'nama_buku' => 'required',
                'pengarang' => 'required',
                'deskripsi' => 'required'
            ]
        );

        if($validator -> fails()) {
            return Response() -> json($validator->errors());
        }

        $store = Buku::create([
            'nama_buku' =>$request->nama_buku, 
            'pengarang' => $request->pengarang,
            'deskripsi' => $request->deskripsi
        ]);

        $data = Buku::where('nama_buku', '=', $request->nama_buku)-> get();
        if($store){
            return Response() -> json([
                'status' => 1,
                'message' => 'Succes create new data!',
                'data' => $data
            ]);
        } else 
        {
            return Response() -> json([
                'status' => 0,
                'message' => 'Failed create data!'
            ]);
        }
    }

    public function uploadCover(Request $request , $id)
    {
        $validator = Validator::make($request->all(),
            [
                'buku_cover' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
            ]
        );

        if($validator->fails()){
            return Response()->json($validator->errors());
        }

        //define nama file yang akan di upload
        $imageName = time () .'.'. $request->buku_cover->extension();

        //proses upload
        $request->buku_cover->move(public_path('images'), $imageName);

        $update=Buku::where('id_buku',$id)->update([
            'image' => $imageName
        ]);

        $data = Buku::where('id_buku', '=', $id)-> get();

        if($update){
            return Response() -> json([
                'status' => 1,
                'message' => 'Succes upload book cover!',
                'data' => $data
            ]);
        } else 
        {
            return Response() -> json([
                'status' => 0,
                'message' => 'Failed upload book cover!'
            ]);
        }
    }
    //create data end

    //read data start
    public function show(){
        return Buku::all();
    }

    public function detail($id){
        if(DB::table('buku')->where('id_buku', $id)->exists()){
            $detail_book = DB::table('buku')
            ->select('buku.*')
            ->where('id_buku', $id)
            ->get();
            return Response()->json($detail_book);
        }else {
            return Response()->json(['message' => 'Couldnt find the data']);
        }
    }
    //read data end

    //update data start
    public function update($id, Request $request){
        $validator=Validator::make($request->all(),
        [
            'nama_buku' => 'required',
            'pengarang' => 'required',
            'deskripsi' => 'required'
        ]);

        if($validator->fails()){
            return Response()->json($validator->errors());
        }

        $update=DB::table('buku')
        ->where('id_buku', '=', $id)
        ->update([
            'nama_buku' =>$request->nama_buku, 
            'pengarang' => $request->pengarang,
            'deskripsi' => $request->deskripsi
        ]);

        $data=Buku::where('id_buku', '=', $id)->get();
        if($update){
            return Response() -> json([
                'status' => 1,
                'message' => 'Success updating data!',
                'data' => $data  
            ]);
        } else {
            return Response() -> json([
                'status' => 0,
                'message' => 'Failed updating data!'
            ]);
        }
    }
    //update data end

    //delete data start
    public function delete($id){
        $delete=DB::table('buku')
        ->where('id_buku', '=', $id)
        ->delete();

        if($delete){
            return Response() -> json([
                'status' => 1,
                'message' => 'Succes delete data!'
        ]);
        } else {
            return Response() -> json([
                'status' => 0,
                'message' => 'Failed delete data!'
        ]);
        }

    }
    //delete data end
}
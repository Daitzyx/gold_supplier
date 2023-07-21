<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Models\User;
use App\Http\Requests\UserRequest;

class UserController extends Controller
{

    public function index (Request $request) {
        $user = User::all();

        return $user;
    }

    public function show ($id) {
        $user = User::find($id);

        return response()->json($user, 200);
    }

    public function store (UserRequest $request) {
        try
        {
            $user = new User();
            $user->fill($request->all());

            DB::beginTransaction();

            $user->save();

            DB::commit();

            return response()->json($user, 200);
        }
        catch(\Exception $e)
        {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }

    public function update (Request $request, $id) {
        try {
            $user = User::find($id);

            if (!$user) {
                return response()->json(["message" => "User not found"], 404);
            }

            $user->fill([
                'name' => $request->input('name')
            ]);

            DB::beginTransaction();
            $user->save();
            DB::commit();

            return response()->json([$user], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }

    public function destroy (Request $request, $id) {
        try
        {
            $user = User::find($id);

            if(!$user) return response()->json(["message" => "User not found"], 404);

            DB::beginTransaction();

            $user->delete();

            DB::commit();

            return response()->json(["message"=> "User deleted!"], 200);;
        }
        catch(\Exception $e)
        {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }
}

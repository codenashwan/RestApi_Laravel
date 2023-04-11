<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\{contacts, User, cities, properties, categories};

class api extends Controller
{

    public function home(Request $request)
    {
        info($request->all());
        return [
            'categories' => categories::latest()->get(),
            'cities' => cities::latest()->get(),
            'newest' => properties::OfUser($request->user_id)
                ->OfCategory($request->category_id)
                ->OfCity($request->city_id)
                ->OfSearch($request->search)
                ->OfPrice($request->price)
                ->latest()
                ->take(7)
                ->get(),
            'users' => User::latest()->take(7)->get(),
            'popular' => properties::latest()
                ->OfCategory($request->category_id)
                ->OfCity($request->city_id)
                ->OfSearch($request->search)
                ->OfPrice($request->price)
                ->take(7)->orderBy('price', 'DESC')->get(),
        ];
    }

    public function contact(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|min:3',
            'email' => 'required|email',
            'phonenumber' => 'required|min:11',
            'message' => 'required|min:3',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()], 401);
        } else {
            contacts::create([
                'full_name' => $request->full_name,
                'email' => $request->email,
                'phonenumber' => $request->phonenumber,
                'message' => $request->message,
            ]);
            return response()->json(['success' => 'Message sent successfully'], 200);
        }
    }

    public function properties(Request $request)
    {
        return properties::latest()
            ->OfCategory($request->category_id)
            ->OfCity($request->city_id)
            ->OfSearch($request->search)
            ->OfPrice($request->price)
            ->OfUser($request->user_id)
            ->orderBy('price', 'DESC')->paginate(10);
    }

    public function property(Request $request)
    {
        return properties::with(['category', 'user', 'city'])->findOrFail($request->id);
    }

    public function users(Request $request)
    {
        return User::latest()->paginate(10);
    }
    public function user(Request $request)
    {
        return User::findOrFail($request->id);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\{contacts, User, cities, properties, categories};
use Nette\Utils\Validators;
use Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;

class api extends Controller
{

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'password' => 'required',
        ]);
        if (!$validator->fails()) {
            $user = User::where('email', $request->email)->first();
            if (Hash::check($request->password, $user->password)) {
                return response()->json([
                    'token' => $user->createToken('authToken')->plainTextToken,
                    'user' => $user
                ]);
            } else {
                return response()->json(['errors' => [__('auth.failed')]], 401);
            }
        } else {
            return response()->json(['errors' => $validator->errors()->all()], 401);
        }
    }
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['success' => 'Logged out successfully'], 200);
    }

    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        if (!$validator->fails()) {

            $user  = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'email_verified_at' => null,
            ]);

            event(new Registered($user));

            return response()->json([
                'token' => $user->createToken('authToken')->plainTextToken,
                'user' => $user
            ]);
        } else {
            return response()->json(['errors' => $validator->errors()->all()], 401);
        }
    }

    public function verify(Request $request)
    {

        $user = User::findOrFail($request->id);

        if ($user->hasVerifiedEmail()) {
            return response()->json("User already verified", 200);
        } else {

            if (!hash_equals((string) $request->hash, sha1($user->getEmailForVerification()))) {
                return response()->json("Invalid verification code", 401);
            } else {
                if ($user->markEmailAsVerified()) {
                    event(new Verified($user));
                    return response()->json("Email verified successfully", 200);
                } else {
                    return response()->json("Email not verified", 401);
                }
            }
        }
    }
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

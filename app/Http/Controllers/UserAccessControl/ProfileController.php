<?php

namespace App\Http\Controllers\UserAccessControl;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdateRequest;
use App\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function edit(Request $request): View
    {
        $data = [
            'title' => 'Profile',
            'user' => auth()->user(),
        ];
        return view('backend.user.profile', $data);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->hasFile('profile_image')) {
            $imageUrl = $this->userService->profile_image_upload($request->file('profile_image'));
            $request->user()->profile_image = $imageUrl;
        }

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }
}

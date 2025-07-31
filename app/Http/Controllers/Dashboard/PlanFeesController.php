<?php
namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class PlanFeesController extends Controller
{

    public function index()
    {

        $data = [
            'title' => 'Users',
            'users' => User::paginate(10)
        ];

        return view('dashboard.users.index', $data);
    }

    public function show($user_id)
    {
        $user = User::find($user_id);

        $data = [
            'title' => 'User details',
            'user' => $user,
            'roles' => Role::all()
        ];

        return view('dashboard.users.show', $data);
    }

    public function update($user_id, Request $request)
    {

        if ($request->action == 'save_general') {

            $validated = $request->validate([
                'firstname' => 'nullable',
                'lastname' => 'nullable',
                'email' => 'nullable|email',
                'phone' => 'nullable',
                'birthday' => 'nullable|date',
                'role' => 'required',
            ]);

            $user = User::find($user_id);
            $user->update( $validated );

            $user->setRole($request->role);

            return redirect()->back()->with('success', 'User updated successfully');
        }

        if ($request->action == 'save_password') {

            $request->validate([
                'password' => 'required',
            ]);

            $user = User::find($user_id);
            $user->password = bcrypt($request->password);
            $user->save();

            return redirect()->route('dashboard.users.index');
        }

        //$user = User::find($user_id);
        //$user->update(request()->all());

        return redirect()->route('dashboard.users.index');
    }


    public function destroy($user_id)
    {
        $user = User::find($user_id);
        $user->delete();

        return redirect()->route('dashboard.users.index');
    }

}

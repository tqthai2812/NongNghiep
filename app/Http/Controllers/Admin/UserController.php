<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('avatars', $filename);
            $data['avatar'] = $path;
        }

        $data['password'] = Hash::make($data['password']);

        User::create($data);

        return redirect()->route('admin.users.index')->with('success', 'Người dùng đã được tạo thành công.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $data = $request->validated();

        // 1. Xử lý Mật khẩu: Chỉ cập nhật nếu người dùng có nhập mới
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        } else {
            unset($data['password']);
        }

        // 2. Xử lý Trạng thái
        $data['is_active'] = $request->has('is_active');

        // 3. Xử lý Avatar
        if ($request->hasFile('avatar')) {
            // Xóa ảnh cũ nếu tồn tại để tránh rác server
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            $file = $request->file('avatar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $data['avatar'] = $file->storeAs('avatars', $filename, ['disk' => 'public']);
        }

        $user->update($data);
        return redirect()->route('admin.users.index')->with('success', 'Cập nhật thông tin thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // 1. Kiểm tra không cho tự xóa chính mình
        if (Auth::id() === $user->id) {
            return back()->with('error', 'Bạn không thể tự xóa chính mình!');
        }

        try {
            // 2. Tiến hành xóa
            // Nếu có ảnh đại diện thì xóa file vật lý trước
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            $user->delete();

            return redirect()->route('admin.users.index')->with('success', 'Đã xóa người dùng thành công!');
        } catch (QueryException $e) {
            // 3. Bắt lỗi vi phạm khóa ngoại (Mã lỗi 23000)
            if ($e->getCode() === '23000') {
                return back()->with('error', 'Không thể xóa! Người dùng này đã có dữ liệu liên quan (Đơn hàng, đánh giá,...) trong hệ thống.');
            }

            // Các lỗi cơ sở dữ liệu khác
            return back()->with('error', 'Có lỗi xảy ra khi kết nối cơ sở dữ liệu.');
        }
    }
}

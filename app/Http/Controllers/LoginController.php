<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Display a listing of the resource.
     */

     public function index()
    {

        return view('login');
    }

    public function login(Request $request)
    {
        try {
            $contact = $request->input('input_contactpetugas');
            $password = $request->input('input_passwordpetugas');

            // Kirim request ke REST API
            $response = Http::post('http://localhost:1111/api/users/login', [
                'p_contactUsers' => $contact,
                'p_passwordUsers' => $password,
            ]);

            if ($response->successful()) {
                $result = $response->json();

                if (isset($result['message']) && $result['message'] === 'Login berhasil') {
                    $data = $result['data'];

                    // Simpan data ke session
                    Session::put('tb_petugas', $data);
                    Session::put('foto_petugas', $data['gambar_user']);

                    // Redirect berdasarkan role
                    if ($data['role_user'] === 'admin') {
                        return redirect('admin/dashboard');
                    } elseif ($data['role_user'] === 'kasir') {
                        return redirect('kasir/dashboard');
                    } else {
                        return redirect('/'); // fallback jika role tidak dikenali
                    }
                } else {
                    return back()->with('error', $result['message'] ?? 'Gagal login');
                }
            } else {
                return back()->with('error', 'Tidak dapat terhubung ke server login.');
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan pada koneksi');
        }
    }

    public function logout(Request $request)
    {
        // Hapus session petugas
        $request->session()->forget('tb_petugas');

        // Logout pengguna menggunakan Auth::logout()
        Auth::logout();

        // Redirect ke halaman login
        return redirect('/');
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

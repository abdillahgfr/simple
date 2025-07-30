<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function showForm()
    {
        return view('Backend.Auth.login');
    }

    private $urlLogin = 'https://jakaset.jakarta.go.id/api_login/api';

    private function callUser($username, $password, $tahun = '', $safety = false)
    {
        $user = null;
        try {
            $opts = [
                'http' => [
                    'method'  => 'POST',
                    'header'  => 'Content-Type: application/x-www-form-urlencoded',
                    'content' => http_build_query([
                        'password' => $password,
                        'tahun'    => $tahun,
                        'username' => $username,
                        // 'sistem' => "Jakaset/" . env('SERVER'),
                    ]),
                ],
            ];
            $context = stream_context_create($opts);
            $url     = $this->urlLogin . '/auth';
            logger()->info("Mengirim request ke API: $url", ['username' => $username, 'tahun' => $tahun]);
            
            $user = @file_get_contents($url, false, $context);

            if (empty($user)) {
                logger()->warning("API login gagal atau tidak membalas");
                throw new \Exception("Gagal melakukan login", 1);
            }

            $decoded = json_decode($user);
            logger()->info("Respon API login:", (array) $decoded);

            return $decoded;
        } catch (\Throwable $th) {
            logger()->error("Exception saat login: " . $th->getMessage());
            if ($safety == false) {
                throw $th;
            }
        }

        return empty($user) ? null : $user;
    }

    public function authenticate($username, $password, $tahun = '')
    {
        $user = $this->callUser($username, $password, $tahun, true);
        if (!$user && is_numeric($tahun)) {
            logger()->info("Coba tahun alternatif: " . ($tahun + 1));
            $user = $this->callUser($username, $password, $tahun + 1, true);
        }
        return $user;
    }

    public function loginApi(Request $request)
    {
        $username = $request->input('username');
        $password = $request->input('password');
        $tahun    = $request->input('tahun', '2025');

        logger()->info("Proses login dimulai", ['username' => $username, 'tahun' => $tahun]);

        $user = $this->authenticate($username, $password, $tahun);

        if ($user && isset($user->success) && $user->code == 200) {
            session(['user' => $user->user]);
            logger()->info("Login berhasil, user: ", (array) $user->user);
            return redirect()->route('frontend.dashboard')->with('success', 'Login successful!');
        }

        logger()->warning("Login gagal", ['response' => (array) $user]);
        return back()->withErrors(['login_error' => 'Username atau Password tidak terdaftar.']);
    }

    public function logout()
    {
        $user = session('user');
        logger()->info("User akan logout", ['user' => $user]);

        // Hapus semua session
        session()->flush();

        logger()->info("Session berhasil dihapus.");

        return redirect()->route('login')->with('success', 'You have been logged out successfully.');
    }

}

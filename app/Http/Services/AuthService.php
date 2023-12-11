<?php
namespace App\Http\Services;

use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AuthService
{
    /**
     * Authenticate user
     *
     * @param string $email
     * @param string $password
     * @return array
     */
    public function authenticate(array $credentials)
    {

        abort_if(
            ! $token = Auth::attempt($credentials, true),
            Response::HTTP_BAD_REQUEST,
            'Credentials do not match our records'
        );

        return $this->respondWithToken($token);
    }

    /* Get the token array structure.
    *
    * @param  string $token
    *
    * @return array
    */
   public function respondWithToken($token): array
   {
       return [
           'user' => auth()->user(),
           'token' => $token,
           'token_type' => 'bearer',
           'expires_in' => auth()->factory()->getTTL() * 60
       ];
   }

   /**
    * Refresh token
    *
    * @return array
    */
    public function refresh(): array
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Register a user
     *
     * @param array $data
     * @return \App\Models\User
     */
    public function register(array $data): \App\Models\User
    {
        $user = new User();
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = bcrypt($data['password']);
        $user->save();

        if(! $user){
            throw new \Exception('An unexpected error occured');
        }

        return $user;
    }
}

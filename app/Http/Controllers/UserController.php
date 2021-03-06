<?php   
namespace App\Http\Controllers;

    use App\Models\User;
    use App\Models\BankAccount;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Hash;
    use Illuminate\Support\Facades\Validator;
    use JWTAuth;
    use Tymon\JWTAuth\Exceptions\JWTException;

class UserController extends Controller
{

    //Metodo de autenticacion de usuario
    public function authenticate( Request $request ){


        $credentials = $request->only('email', 'password');
        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 400);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }
        return response()->json(compact('token'),201);
    }

    public function getAuthenticatedUser(){

        try {

            if ( !$user = JWTAuth::parseToken()->authenticate() ) {

                    return response()->json(['user_not_found'], 404);
            }
            } catch ( Tymon\JWTAuth\Exceptions\TokenExpiredException $e ) {

                    return response()->json(['token_expired'], $e->getStatusCode());

            } catch ( Tymon\JWTAuth\Exceptions\TokenInvalidException $e ) {

                    return response()->json(['token_invalid'], $e->getStatusCode());

            } catch ( Tymon\JWTAuth\Exceptions\JWTException $e ) {

                    return response()->json(['token_absent'], $e->getStatusCode());

            }

        return response()->json(compact('user'));
    }

    //Registro de usuario
    public function register( Request $request ){

        //Validador de parametros
        $validator = Validator::make( $request->all(), [

            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',

        ]);

        if( $validator->fails() ){

                return response()->json($validator->errors()->toJson(), 400);
        }

        $user = User::create([

            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password')),

        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json(compact('user','token'),201);
    }
    
    //Cambio de contrase??a 
    public function changeUserPasswd( Request $request ){

        $current_user = JWTAuth::parseToken()->authenticate();

        $passwd_old =  $request->get( 'passwd_old' );
        $passwd_new =  $request->input( 'passwd_new' );
       

        if(Hash::check( $passwd_old , $current_user->password )) {
            $current_user->password = Hash::make( $passwd_new );
            $current_user->save();
            
            return response()->json( compact( 'current_user' ) , 201 );
        }
        
        return response()->json(['Error, password has not been changed ']);
        

        
    }
}
<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * @OA\Post(
     * path="/api/auth/register",
     * operationId="Register",
     * tags={"Register"},
     * summary="User Register",
     * description="User Register here",
     *     @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={"name","email", "password"},
     *               @OA\Property(property="name", type="text"),
     *               @OA\Property(property="email", type="text"),
     *               @OA\Property(property="password", type="password"),
     *            ),
     *        ),
     *    ),
     *      @OA\Response(
     *         response=201,
     *         description="success",
     *         @OA\JsonContent(
     *              @OA\Property( property="status",type="string", example="success"),
     *              @OA\Property(
     *                 type="array",
     *                 property="data",
     *                      @OA\Items(
     *                      type="object",
     *                          @OA\Property( property="message",type="string", example="User Created Successfully"),
     *                          @OA\Property( property="token",type="string", example="14|MwNOc2gK1SIK4Ls2xLcKThvNtBgNY5Jpk5eRRdZj"),
     *                      ),
     *              ),
     *          )
     *      ),
     *      @OA\Response(
     *     response=400,
     *     description="Bad request",
     *          @OA\JsonContent(
     *              @OA\Property( property="status",type="string", example="error"),
     *              @OA\Property(
     *                 type="array",
     *                 property="data",
     *                      @OA\Items(
     *                      type="object",
     *                          @OA\Property( property="message",type="string", example="validation error"),
     *                          @OA\Property( property="errors",type="array",
     *                          @OA\Items(type="object",@OA\Property( property="email",type="array", @OA\Items(type="string")),),
     *                          ),
     *                      ),
     *              ),
     *          ),
     *      ),
     *      @OA\Response(
     *     response=500,
     *     description="Bad request",
     *          @OA\JsonContent(
     *              @OA\Property( property="status",type="string", example="error"),
     *              @OA\Property( property="data",type="string", example="SQLSTATE[HY000]: General error:"),
     *              ),
     *          ),
     *      ),
     * )
     */
    public function createUser(Request $request)
    {
        try {
            //Validated
            $validateUser = Validator::make($request->all(),
                [
                    'name' => 'required',
                    'email' => 'required|email|unique:users,email',
                    'password' => 'required'
                ]);

            if($validateUser->fails()){
                return response()->json([
                    'status' => 'error',
                    'data' => [
                        'message' => 'validation error',
                        'errors' => $validateUser->errors()
                    ]
                ], 401);
            }

            $user = User::create([
                'names' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);

            return response()->json([
                'status' => 'success',
                'data' => [
                    'message' => 'User Created Successfully',
                    'token' => $user->createToken("API TOKEN")->plainTextToken
                ]
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'data' => $th->getMessage()

            ], 500);
        }
    }

    /**
     * @OA\Post(
     * path="/api/auth/login",
     * operationId="Sing-in",
     * tags={"Login"},
     * summary="User sing-in",
     * description="User sing-in here",
     *     @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={"email", "password"},
     *               @OA\Property(property="email", type="text"),
     *               @OA\Property(property="password", type="password"),
     *
     *            ),
     *
     *        ),
     *    ),
     *      @OA\Response(
     *         response=201,
     *         description="success",
     *         @OA\JsonContent(
     *              @OA\Property( property="status",type="string", example="success"),
     *              @OA\Property(
     *                 type="array",
     *                 property="data",
     *                      @OA\Items(
     *                      type="object",
     *                          @OA\Property( property="message",type="string", example="User Logged In Successfully"),
     *                          @OA\Property( property="token",type="string", example="14|MwNOc2gK1SIK4Ls2xLcKThvNtBgNY5Jpk5eRRdZj"),
     *                      ),
     *              ),
     *          )
     *      ),
     *      @OA\Response(
     *     response=401,
     *     description="Unauthorized",
     *          @OA\JsonContent(
     *              @OA\Property( property="status",type="string", example="error"),
     *              @OA\Property(
     *                 type="array",
     *                 property="data",
     *                      @OA\Items(
     *                          @OA\Property( property="message",type="string", example="Email & Password does not match with our record."),
     *                          ),
     *                      ),
     *              ),
     *          ),
     *@OA\Response(
     *     response=500,
     *     description="Bad request",
     *          @OA\JsonContent(
     *              @OA\Property( property="status",type="string", example="error"),
     *              @OA\Property( property="data",type="string", example="SQLSTATE[HY000]: General error:"),
     *              ),
     *          ),
     *      ),
     *
     *
     * )
     */
    public function loginUser(Request $request)
    {
        try {
            $validateUser = Validator::make($request->all(),
                [
                    'email' => 'required|email',
                    'password' => 'required'
                ]);

            if($validateUser->fails()){
                return response()->json([
                    'status' => 'error',
                    'data' => [
                        'message' => 'validation error',
                        'errors' => $validateUser->errors()
                    ]
                ], 401);
            }

            if(!Auth::attempt($request->only(['email', 'password']))){
                return response()->json([
                    'status' => 'error',
                    'data' => [
                        'message' => 'Email & Password does not match with our record.',
                    ]
                ], 401);
            }

            $user = User::where('email', $request->email)->first();

            return response()->json([
                'status' => 'success',
                'data' => [
                    'message' => 'User Logged In Successfully',
                    'token' => $user->createToken("API TOKEN")->plainTextToken
                ]
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'data' => $th->getMessage()
            ], 500);
        }
    }
}

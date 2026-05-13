<?php

namespace App\Http\Controllers\Api\V1\User;

use Illuminate\Http\Request;

use Symfony\Component\HttpFoundation\Response;

use Auth;
use DB;

use App\Http\Controllers\Api\V1\BaseController;

use App\Models\User\Friend;
use App\Models\User;

class FriendController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @param  int  $user_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request, $user_id = null)
    {
        $user = $this->requestedUser($user_id);

        if ($this->canReadUserRelationships($user)) {
            return response()->json(
                $this->paginateQuery(
                    $this->friendsQuery((int) $user->id),
                    $request,
                    function ($record) {
                        return $this->serializeFriendRecord($record);
                    }
                ),
                Response::HTTP_OK
            );
        } else {
            return $this->sendUnauthorizedResponse();
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @param  int  $user_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function friend_requests(Request $request, $user_id = null)
    {
        $user = $this->requestedUser($user_id);
        
        if ($this->canReadUserRelationships($user)) {
            return response()->json(
                $this->paginateQuery(
                    $this->friendRequestsQuery((int) $user->id),
                    $request,
                    function ($record) {
                        return $this->serializeFriendRecord($record);
                    }
                ),
                Response::HTTP_OK
            );
        } else {
            return $this->sendUnauthorizedResponse();
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $user_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, $user_id = null)
    {
        $user = Auth::user();
        
        if (!empty($user_id)) {
            $user = User::findOrFail($user_id);
        }

        if ($user->id == Auth::user()->id || Auth::user()->isAdmin()) {
            $params = $request->all();
            $params['user_id'] = $user->id;
            $params['accepted'] = false;

            $friend = new Friend($params);

            if ($friend->save()) {
                return response()->json([
                    'id' => (int) $friend->id,
                    'user_id' => (int) $friend->user_id,
                    'friend_id' => (int) $friend->friend_id,
                    'accepted' => (bool) $friend->accepted,
                ], Response::HTTP_CREATED);
            } else {
                return $this->sendInvalidResponse($friend->getErrors());
            }
        } else {
            return $this->sendUnauthorizedResponse();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, $id)
    {
        $user_id = $request->user_id;

        $user = Auth::user();

        if (!empty($user_id)) {
            $user = User::findOrFail($user_id);
        }

        $friend = $user->friends()->findOrFail($id);

        return response()->json($friend, Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $user_id = $request->user_id;

        $user = Auth::user();

        if (!empty($user_id)) {
            $user = User::findOrFail($user_id);
        }

        if ($user->id == Auth::user()->id || Auth::user()->isAdmin()) {
            $params = $request->all();

            $user_friend = $user->friends()->findOrFail($id);

            if ($user_friend->update($params)) {
                return response()->json($user_friend, Response::HTTP_OK);
            } else {
                return $this->sendInvalidResponse($user_friend->getErrors());
            }
        } else {
            return $this->sendUnauthorizedResponse();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, $id)
    {
        $user_id = $request->user_id;
        
        $user = Auth::user();

        if (!empty($user_id)) {
            $user = User::findOrFail($user_id);
        }

        if ($user->id == Auth::user()->id || Auth::user()->isAdmin()) {
            $user_friend = $user->friends()->findOrFail($id);
            $user_friend->delete();
    
            return response()->json(null, Response::HTTP_NO_CONTENT);
        } else {
            return $this->sendUnauthorizedResponse();
        }
    }
    
    /*
    * @param  \Illuminate\Http\Request  $request
    * @param  int  $id
    * @return \Illuminate\Http\JsonResponse
    */
    public function accept(Request $request, $id)
    {
        $user_id = $request->user_id;

        $user = Auth::user();

        if (!empty($user_id)) {
            $user = User::findOrFail($user_id);
        }

        if ($user->id == Auth::user()->id || Auth::user()->isAdmin()) {
            $user_friend = $user->friendRequests()->findOrFail($id);
            $user_friend->accepted = true;
            $user_friend->save();

            return response()->json([
                'id' => (int) $user_friend->id,
                'user_id' => (int) $user_friend->user_id,
                'friend_id' => (int) $user_friend->friend_id,
                'accepted' => (bool) $user_friend->accepted,
            ], Response::HTTP_OK);
        } else {
            return $this->sendUnauthorizedResponse();
        }
    }

    /**
    * @param  \Illuminate\Http\Request  $request
    * @param  int  $id
    * @return \Illuminate\Http\JsonResponse
    */
    public function reject(Request $request, $id)
    {
        $user_id = $request->user_id;
        
        $user = Auth::user();

        if (!empty($user_id)) {
            $user = User::findOrFail($user_id);
        }

        if ($user->id == Auth::user()->id || Auth::user()->isAdmin()) {
            $user_friend = $user->friendRequests()->findOrFail($id);
            $user_friend->delete();

            return response()->json(null, Response::HTTP_NO_CONTENT);
        } else {
            return $this->sendUnauthorizedResponse();
        }
    }
    
    /**
    * @param  int  $user_id
    * @return \Illuminate\Http\JsonResponse
    */
    public function available(Request $request, $user_id = null)
    {
        $user = $this->requestedUser($user_id);

        if (!$this->canReadUserRelationships($user)) {
            return $this->sendUnauthorizedResponse();
        }

        return response()->json(
            $this->paginateQuery(
                $this->availableUsersQuery($user, $request),
                $request,
                function ($record) {
                    return $this->serializeAvailableUser($record);
                }
            ),
            Response::HTTP_OK
        );
    }

    protected function requestedUser($user_id = null)
    {
        if (!empty($user_id)) {
            return User::findOrFail($user_id);
        }

        return Auth::user();
    }

    protected function canReadUserRelationships(User $user)
    {
        return $user->id == Auth::user()->id || Auth::user()->isAdmin();
    }

    protected function paginateQuery($query, Request $request, callable $transform)
    {
        $page = max((int) $request->input('page', 1), 1);
        $perPage = min(max((int) $request->input('per_page', 12), 1), 24);

        $records = $query->forPage($page, $perPage + 1)->get();
        $hasMore = $records->count() > $perPage;

        if ($hasMore) {
            $records = $records->slice(0, $perPage)->values();
        } else {
            $records = $records->values();
        }

        return [
            'data' => $records->map($transform)->values()->all(),
            'meta' => [
                'page' => $page,
                'per_page' => $perPage,
                'has_more' => $hasMore,
                'next_page' => $hasMore ? $page + 1 : null,
            ],
        ];
    }

    protected function friendsQuery($userId)
    {
        $friendsAsRequester = DB::table('user_friends as relationships')
            ->join('users as profiles', 'profiles.id', '=', 'relationships.friend_id')
            ->where('relationships.user_id', '=', $userId)
            ->where('relationships.accepted', '=', 1)
            ->select($this->relationshipSelectColumns('relationships', 'profiles'));

        $friendsAsRecipient = DB::table('user_friends as relationships')
            ->join('users as profiles', 'profiles.id', '=', 'relationships.user_id')
            ->where('relationships.friend_id', '=', $userId)
            ->where('relationships.accepted', '=', 1)
            ->select($this->relationshipSelectColumns('relationships', 'profiles'));

        return DB::query()
            ->fromSub($friendsAsRequester->unionAll($friendsAsRecipient), 'relationships')
            ->orderBy('profile_name')
            ->orderByDesc('id');
    }

    protected function friendRequestsQuery($userId)
    {
        return DB::table('user_friends as relationships')
            ->join('users as profiles', 'profiles.id', '=', 'relationships.user_id')
            ->where('relationships.friend_id', '=', $userId)
            ->where('relationships.accepted', '=', 0)
            ->select($this->relationshipSelectColumns('relationships', 'profiles'))
            ->orderByDesc('relationships.created_at')
            ->orderByDesc('relationships.id');
    }

    protected function availableUsersQuery(User $user, Request $request)
    {
        $search = trim((string) $request->input('search', ''));

        return DB::table('users')
            ->select([
                'users.id',
                'users.name',
                'users.location',
                'users.age',
                'users.address',
                'users.privacy',
                'users.profile_picture',
            ])
            ->where('users.id', '!=', $user->id)
            ->whereNotExists(function ($query) use ($user) {
                $query->select(DB::raw(1))
                    ->from('user_friends as relationships')
                    ->where(function ($nestedQuery) use ($user) {
                        $nestedQuery->where(function ($directQuery) use ($user) {
                            $directQuery->where('relationships.user_id', '=', $user->id)
                                ->whereColumn('relationships.friend_id', 'users.id');
                        })->orWhere(function ($reverseQuery) use ($user) {
                            $reverseQuery->where('relationships.friend_id', '=', $user->id)
                                ->whereColumn('relationships.user_id', 'users.id');
                        });
                    })
                    ->where('accepted', 1);
            })
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($searchQuery) use ($search) {
                    $like = "%{$search}%";

                    $searchQuery->where('users.name', 'like', $like)
                        ->orWhere('users.email', 'like', $like)
                        ->orWhere('users.business', 'like', $like)
                        ->orWhere('users.location', 'like', $like);
                });
            })
            ->orderBy('users.name')
            ->orderBy('users.id');
    }

    protected function relationshipSelectColumns($relationshipTable, $profileTable)
    {
        return [
            "{$relationshipTable}.id as id",
            "{$relationshipTable}.user_id",
            "{$relationshipTable}.friend_id",
            "{$relationshipTable}.accepted",
            "{$profileTable}.id as profile_id",
            "{$profileTable}.name as profile_name",
            "{$profileTable}.location as profile_location",
            "{$profileTable}.age as profile_age",
            "{$profileTable}.address as profile_address",
            "{$profileTable}.privacy as profile_privacy",
            "{$profileTable}.profile_picture as profile_picture",
        ];
    }

    protected function serializeFriendRecord($record)
    {
        return [
            'id' => (int) $record->id,
            'user_id' => (int) $record->user_id,
            'friend_id' => (int) $record->friend_id,
            'accepted' => (bool) $record->accepted,
            'friend' => $this->serializePublicUser([
                'id' => (int) $record->profile_id,
                'name' => $record->profile_name,
                'location' => $record->profile_location,
                'age' => $record->profile_age,
                'address' => $record->profile_address,
                'privacy' => (bool) $record->profile_privacy,
                'profile_picture' => $record->profile_picture,
            ]),
        ];
    }

    protected function serializeAvailableUser($record)
    {
        return $this->serializePublicUser([
            'id' => (int) $record->id,
            'name' => $record->name,
            'location' => $record->location,
            'age' => $record->age,
            'address' => $record->address,
            'privacy' => (bool) $record->privacy,
            'profile_picture' => $record->profile_picture,
        ]);
    }

    protected function serializePublicUser(array $user)
    {
        $user['profilePictureUrl'] = $this->profilePictureUrl($user['id'], $user['profile_picture']);

        unset($user['profile_picture']);

        return $user;
    }

    protected function profilePictureUrl($userId, $profilePicture)
    {
        if (empty($profilePicture)) {
            return asset('/images/iconimages/load.png');
        }

        $assetsBaseUrl = env('APP_WEB_ASSETS_URL') ?: env('AWS_S3_URL');

        if (empty($assetsBaseUrl)) {
            return asset('/images/iconimages/load.png');
        }

        return rtrim($assetsBaseUrl, '/').'/users/uid-'.$userId.'/profile_pictures/'.$profilePicture;
    }
}

<?php

    namespace App\Repos;

    use App\Models\User;
    use DB, Auth;
    use Carbon\Carbon;

    class ConnectionRepo
    {

        public function __construct() {
        }

        /*
         * get loged in user connection counts
         */
        public function connectionCount() {
            $authUser = auth()->user();
            $authUserId = $authUser->id;
            $allUserCount = User::where('id', '!=', $authUserId)->count();

            $connectionsCount = $authUser->confiremConnections()->count();
            $pendingConnectionCount = $authUser->pendingConnectsTo->count()+$authUser->pendingConnectsFrom->count();
            $sentRequestsCount = $authUser->pendingConnectsTo()->count();
            $receiveRequestsCount = $authUser->pendingConnectsFrom()->count();
            $suggestionsCount = $allUserCount - ($connectionsCount + $pendingConnectionCount);
            return [
                'suggestionsCount' => $suggestionsCount,
                'connectionsCount' => $connectionsCount,
                'pendingConnectionCount' => $pendingConnectionCount,
                'sentRequestsCount' => $sentRequestsCount,
                'receiveRequestsCount' => $receiveRequestsCount
            ];
        }

        /*
         * Get suggested users
         */
        public function getSuggestions() {
            $authUser = auth()->user();
            $connectToIds = [];
            $connectFromIds = [];
            $connectTo = $authUser->connectsTo();
            $connectFrom = $authUser->connectsFrom();
            if ($connectTo) {
                $connectToIds = $connectTo->pluck('connected_user_id');
            }
            if ($connectFrom) {
                $connectFromIds = $connectFrom->pluck('user_id');
            }
            $allConnectIds = $connectToIds->merge($connectFromIds);
            return User::where('id', '!=', $authUser->id)->whereNotIn('id', $allConnectIds)->skip(5)->take(10)->get();
        }

        /**
         *   sent Request data
         */
        public function sentRequest() {
            $authUser = auth()->user();
            $data = User::join('connections', 'users.id', '=', 'connections.user_id')
                ->where('connections.accepted', 0)
                ->where('connections.connected_user_id', '=', $authUser->id)
                ->select('users.*', 'connections.connected_user_id')
                ->get();
            return $data;
        }

        /**
         *   sent Request data
         */
        public function receivedRequest() {
            $authUser = auth()->user();
            $data = User::join('connections', 'users.id', '=', 'connections.connected_user_id')
                ->where('connections.accepted', 0)
                ->where('connections.user_id', '=', $authUser->id)
                ->select('users.*', 'connections.user_id')
                ->get();
            return $data;
        }

        /**
         *   sent Request data
         */
        public function confirmedConnections() {
            $authUser = auth()->user();
            $data = User::join('connections', 'users.id', '=', 'connections.connected_user_id')
                ->where('connections.accepted', 1)
                ->where('connections.user_id', '=', $authUser->id)
                ->select('users.*', 'connections.id as connection_id')
                ->get();
            $data1 = User::join('connections', 'users.id', '=', 'connections.user_id')
                ->where('connections.accepted', 1)
                ->where('connections.connected_user_id', '=', $authUser->id)
                ->select('users.*', 'connections.id as connection_id')
                ->get();
            $merged = $data1->merge($data);
            return $merged->all();
        }

        public function storeConnect($request) {
            $authUser = auth()->user();
            $user_id = $request->user_id;
            $connected_user_id = $authUser->id;
            DB::table('connections')->insert([
                'user_id' => $user_id,
                'connected_user_id' => $connected_user_id
            ]);
        }

        public function acceptConnect($id) {
            $authUser = auth()->user();
            $connected_user_id = $authUser->id;
            DB::table('connections')
                ->where('user_id', $authUser->id)
                ->where('connected_user_id', $id)
                ->update(['accepted' => 1]);
        }

        /**
         * @param $id
         * with draw request
         */
        public function withDrawRequest($id) {
            $authUser = auth()->user();
            $connected_user_id = $authUser->id;
            DB::table('connections')
                ->where('user_id', $id)
                ->where('connected_user_id', $connected_user_id)
                ->delete();
        }

        /**
         * @param $id
         * with draw request
         */
        public function removeConnection($id) {
            DB::table('connections')
                ->where('id', $id)
                ->delete();
        }
    }

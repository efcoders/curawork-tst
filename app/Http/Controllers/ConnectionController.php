<?php

    namespace App\Http\Controllers;

    use Illuminate\Http\Request;
    use App\Repos\ConnectionRepo;

    class ConnectionController extends Controller
    {
        /**
         * Create a new controller instance.
         *
         * @return void
         */
        public function __construct(ConnectionRepo $connectionRepo) {
            $this->middleware('auth');
            $this->connectionRepo = $connectionRepo;
        }

        /**
         * Display a listing of the resource.
         *
         * @return \Illuminate\Http\Response
         */
        public function index() {
            $users = $this->connectionRepo->getSuggestions();
            return view('components.suggestion', compact('users'));
        }

        /**
         * Display a listing sent request.
         *
         * @return \Illuminate\Http\Response
         */
        public function sentRequest() {
            $data = $this->connectionRepo->sentRequest();
            $mode = 'sent';
            return view('components.request', compact(['data','mode']));
        }

        /**
         * Display a listing sent request.
         *
         * @return \Illuminate\Http\Response
         */
        public function receivedRequest() {
            $data = $this->connectionRepo->receivedRequest();
            $mode = 'receive';
            return view('components.request', compact(['data','mode']));
        }

        public function confirmedConnections(){
            $data = $this->connectionRepo->confirmedConnections();
            return view('components.connection', compact(['data']));
        }


        public function connectionCounts() {
            return $this->connectionRepo->connectionCount();
        }
    }

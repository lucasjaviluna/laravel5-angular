<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

use App\Http\Requests;
use App\Joke;
use App\User;

class JokesController extends Controller
{
    public function index() {
      $jokes = Joke::with('user')->get();
      return Response::json([
        'data' => $this->transformCollection($jokes)
      ], 200);
    }

    public function show($id) {
      $joke = Joke::with(['user' => function($query) {
        $query->select('id', 'name');
      }])->find($id);

      if (!$joke) {
        return Response::json([
          'error' => [
            'message' => 'Joke does not exist'
          ]
        ], 400);
      }

      //get previous joke id
      $previous = Joke::where('id', '<', $joke->id)->max('id');

      //get next joke id
      $next = Joke::where('id', '>', $joke->id)->min('id');

      return Response::json([
        'previous_joke_id' => $previous,
        'next_joke_id' => $next,
        'data' => $this->transform($joke)
      ]);
    }

    public function store(Request $request) {
      if(! $request->body or ! $request->user_id){
          return Response::json([
              'error' => [
                  'message' => 'Please Provide Both body and user_id'
              ]
          ], 422);
      }
      $joke = Joke::create($request->all());

      return Response::json([
              'message' => 'Joke Created Succesfully',
              'data' => $this->transform($joke)
      ]);
    }

    public function update(Request $request, $id) {
      if(! $request->body or ! $request->user_id){
        return Response::json([
          'error' => [
            'message' => 'Please Provide Both body and user_id'
          ]
        ], 422);
      }

      $joke = Joke::find($id);
      $joke->body = $request->body;
      $joke->user_id = $request->user_id;
      $joke->save();
      return Response::json([
        'message' => 'Joke Updated Succesfully'
      ]);
    }

    private function transformCollection($jokes) {
      return array_map([$this, 'transform'], $jokes->toArray());
    }

    private function transform($joke) {
      return [
        'joke_id' => $joke['id'],
        'joke' => $joke['body'],
        'submitted_by' => $joke['user']['name']
      ];
    }
}

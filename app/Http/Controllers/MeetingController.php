<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Meeting;

class MeetingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title    = 'Meeting';
        $meetings = Meeting::all();
        // foreach ($meetings as $meeting) {
        //     $meeting->view_meeting = [
        //         'href'   => 'api/v1/meeting/' . $meeting->id,
        //         'method' => 'GET'
        //     ];
        // }

        // $response = [
        //     'msg'      => 'List of all meetings',
        //     'meetings' => $meetings
        // ];

        // return response()->json($response, 200);

        return view('pages.meeting.index', [
            'title'     => $title,
            'meetings'  => $meetings
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Code
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title'       => 'required',
            'description' => 'required',
            'time'        => 'required',
            'user_id'     => 'required'
        ]);

        $title       = $request->input('title');
        $description = $request->input('description');
        $time        = $request->input('time');
        $user_id     = $request->input('user_id');

        $meeting = new Meeting([
            'time'        => $time,
            'title'       => $title,
            'description' => $description
        ]);

        if ($meeting->save()) {
            $meeting->users()->attach($user_id);
            $meeting->view_meeting = [
                'href'   => 'api/v1/meeting/' . $meeting->id,
                'method' => 'POST'
            ];
            $message = [
                'msg'     => 'Meeting created!',
                'meeting' => $meeting
            ];
            return response()->json($message, 201);
        }

        $response = [
            'msg' => 'Error during creation!'
        ];

        return response()->json($response, 404);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $title   = 'Detail Meeting';
        $meeting = Meeting::with('users')->where('id', $id)->firstOrFail();

        $meeting->view_meeting = [
            'href' => 'api/v1/meeting',
            'method' => 'GET'
        ];

        $response = [
            'msg' => 'Meeting information',
            'meeting' => $meeting
        ];
        // return response()->json($response, 200);

        return view('pages.meeting.show', [
            'title'   => $title,
            'meeting' => $meeting
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function edit(Meeting $meeting)
    {
        // Code
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'required',
            'description' => 'required',
            'time' => 'required',
            'user_id' => 'required'
        ]);

        $title       = $request->input('title');
        $description = $request->input('description');
        $time        = $request->input('time');
        $user_id     = $request->input('user_id');

        $meeting     = Meeting::with('users')->findOrFail($id);

        if (!$meeting->users()->where('users.id', $user_id)->first()) {
            return response()->json(['msg' => 'User not registered for meeting, update not successful!'], 404);
        }

        $meeting->time        = $time;
        $meeting->title       = $title;
        $meeting->description = $description;

        if (!$meeting->update()) {
            return response()->json([
                'msg' => 'Error during update!'
            ], 404);
        }

        $meeting->view_meeting = [
            'href'   => 'api/v1/meeting/' . $meeting->id,
            'method' => 'GET'
        ];

        $response = [
            'msg'     => 'Meeting Updated!',
            'meeting' => $meeting
        ];

        return response()->json($response, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $meeting = Meeting::findOrFail($id);
        $users   = $meeting->users;

        $meeting->users()->detach();

        if (!$meeting->delete()) {
            foreach ($users as $user) {
                $meeting->users()->attach($user);
            }
            return response()->json([
                'msg' => 'Deletion failed!'
            ], 404);
        }

        $response = [
            'msg'    => 'Meeting deleted!',
            'create' => [
                'href'   => 'api/v1/meeting',
                'method' => 'POST',
                'params' => 'time, title, description'
            ]
        ];

        // return response()->json($response, 200);

        return redirect('/');
    }
}

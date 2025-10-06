<?php
namespace App\Http\Controllers;

use App\Models\Circle;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\ClassSession;



class StreamController extends Controller
{


    public function startLive($sessionId)
    {
        $session = ClassSession::find($sessionId);

        $roomName = 'live_' . Str::uuid();

        $session->link = env("FRONTEND_URL") . "/live/session/" . $roomName;
        $session->status = 'live';
        $session->save();

        return response()->json(
            $roomName
        );

    }


    public function endLive($sessionId)
    {
        $session = ClassSession::find($sessionId);
        $session->status = 'ended';
        $session->save();

        return response()->json(
            "Done"
        );

    }



    // Circle //
    public function startCircleLive($circle_id)
    {

        $circle = Circle::find($circle_id);

        $roomName = 'live_' . Str::uuid();

        $circle->link = env("FRONTEND_URL") . "/liveCircle/" . $roomName . "/" . $circle->id;
        $circle->status = 'online';
        $circle->save();

        return response()->json(
            $roomName
        );

    }

    public function endCircleLive($circle_id)
    {
        $circle = Circle::find($circle_id);
        $circle->link = "";
        $circle->status = 'offline';
        $circle->save();

        return response()->json(
            "Done"
        );

    }




}

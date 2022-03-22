<?php

namespace App\Http\Controllers;

use App\Models\Level;
use App\Models\Score;
use Illuminate\Http\Request;

class LevelController extends Controller
{
    public function get_main_levels($user_id)
    {
        Level::select('id', 'name')->where('user_id', 1)->get();
    }

    public function get_community_levels()
    {
        $levels = Level::select('id', 'name')->where('user_id', '!=', 1)->get();
        if (!$levels->isEmpty()) {
            $result = [];
            foreach ($levels as $l) {
                $actual_level = [$l->id =>[ 'name' => $l->name]];

                $top = Score::join('users', 'users.id', 'scores.user_id')->select('scores.value', 'users.name')->where('level_id', $l->id)->orderBy('value')->take(3)->get();
                for ($i = 0; $i < $top->count(); $i++) {
                    $actual_level[$l->id] += ["top_" . $i => ["score" => $top[$i]->value, "name" => $top[$i]->name]];
                }
                // array_push($result, $actual_level);
                $result += $actual_level;
            }
        }
        return json_encode($result);
    }

    public function post_community_level(Request $request)
    {
        $level = new Level();
        $level->user_id = $request->user_id;
        $level->file_name = $request->file_name;
        $level->name = $request->name;
        $level->lives = $request->lives;
        $level->digsideers = $request->digsideers;
        $level->digdowners = $request->digdowners;
        $level->stopperers = $request->stopperers;
        $level->umbrellaers = $request->umbrellaers;
        $level->stairers = $request->stairers;
        $level->climbers = $request->climbers;
        $level->scene = $request->scene;
        $level->save();
    }

    public function get_level($level_id)
    {
        return false;
    }
}

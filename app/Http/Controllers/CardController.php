<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CardController extends Controller
{
    // half an hour
    public function index(Request $request)
    {
        $persons = $request->person;
        if ($persons == 0) {
            return response()->json([
                'code' => 200,
                'data' => 'No card is distributed',
            ]);
        }

        $cards = [];
        $shapes = ['S', 'H', 'D', 'C'];

        // *****************************
        // to form a card set
        // *****************************
        foreach ($shapes as $shape) {
            for ($i = 1; $i <= 13; $i++) {
                switch ($i) {
                    case 1:
                        $num = 'A';
                        break;
                    case 10:
                        $num = 'X';
                        break;
                    case 11:
                        $num = 'J';
                        break;
                    case 12:
                        $num = 'Q';
                        break;
                    case 13:
                        $num = 'K';
                        break;
                    default:
                        $num = $i;
                }

                $cards[] = $shape . '-' . $num;
            }
        }

        // *****************************
        // shuffle the card
        // *****************************
        shuffle($cards);

        // *****************************
        // distribute the card
        // *****************************
        $player = [];
        $j = 1;
        foreach ($cards as $card) {
            if ($j > $persons) {
                $j = 1;
            }

            $player[$j][] = $card;
            $j++;
        }

        // *****************************
        // format response string
        // *****************************
        $data = "";
        foreach ($player as $key => $value) {
            $data .= "Player " . $key . ": ";
            $str = "";
            foreach ($value as $c) {
                $str .= $c . ", ";
            }
            $data .= rtrim($str, ', ') . "<br />";
        }

        return response()->json([
            'code' => 200,
            'data' => $data,
        ]);
    }
}

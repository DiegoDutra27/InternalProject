<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Laravel\Jetstream\Jetstream;


class ZipCodeController extends Controller
{
    public function getZipCode($id)
    {
        $response = Http::get("https://viacep.com.br/ws/" . $id . "/json/")->json();

        return $response;
    }
}
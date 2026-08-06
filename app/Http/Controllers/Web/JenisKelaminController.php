<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\JenisKelamin;
use Illuminate\Http\Request;

class JenisKelaminController extends Controller
{
    public function index()
    {
        $jenisKelamin = JenisKelamin::all();
        return view('jenis-kelamin.index', compact('jenisKelamin'));
    }
}
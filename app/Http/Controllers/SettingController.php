<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $setting = Setting::all();
        return response()->json([
            'setting' => $setting
        ], 200);
    }

    public function editsetting(Request $request, $id)
    {
        // Validation
        $attr = $request->validate([
            'jam_buka'          => 'required|string',
            'nama_rekening'     => 'required|string',
            'nomor_rekening'    => 'required|string',
        ]);

        $setting = Setting::find($id);

        if (!$setting) {
            return response()->json(['message' => 'Setting not found'], 404);
        }

        // Update setting data
        $setting->jam_buka          = $attr['jam_buka'];
        $setting->nama_rekening     = $attr['nama_rekening'];
        $setting->nomor_rekening    = $attr['nomor_rekening'];

        $setting->save();

        return response()->json([
            'message' => 'Setting berhasil diupdate',
        ], 200);
    }
}

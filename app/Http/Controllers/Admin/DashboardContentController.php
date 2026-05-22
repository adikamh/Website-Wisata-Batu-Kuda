<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HomepageContent;
use Illuminate\Support\Facades\Auth;

class DashboardContentController extends Controller
{
    //
    public function updateContent(Request $request)
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'about_title' => ['nullable', 'string', 'max:255'],
            'about_subtitle' => ['nullable', 'string', 'max:500'],
            'about_description' => ['nullable', 'string'],
            'info_location' => ['nullable', 'string'],
            'info_opening_hours' => ['nullable', 'string', 'max:255'],
            'info_ticket_price' => ['nullable', 'string'],
            'info_contact' => ['nullable', 'string'],
            'features' => ['nullable', 'array'],
            'features.*.title' => ['nullable', 'string', 'max:120'],
            'features.*.description' => ['nullable', 'string', 'max:250'],
            'tips' => ['nullable', 'array'],
            'tips.*.title' => ['nullable', 'string', 'max:120'],
            'tips.*.description' => ['nullable', 'string', 'max:250'],
        ]);

        $content = HomepageContent::firstOrCreate([]);
        $content->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Konten dashboard berhasil diperbarui.',
            'data' => $content,
        ], 200);
    }

    public function getContent()
    {
        $content = HomepageContent::first();

        if (!$content) {
            $content = HomepageContent::create([]);
        }

        return response()->json([
            'success' => true,
            'data' => $content,
        ], 200);
    }

    private function authorizeAdmin(): void
    {
        abort_if(! Auth::check() || Auth::user()->role !== 'admin', 403);
    }
}

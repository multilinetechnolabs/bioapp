<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CertificateTemplate;
use Illuminate\Http\Request;

class CertificateTemplateController extends Controller
{
    public function edit()
    {
        return view('admin.pages.certificate.form', [
            'template' => CertificateTemplate::current(),
        ]);
    }

    public function update(Request $request)
    {
        $template = CertificateTemplate::current();

        $data = $request->validate([
            'cert_eyebrow' => 'nullable|string|max:255',
            'cert_title' => 'nullable|string|max:1000',
            'cert_intro' => 'nullable|string|max:255',
            'cert_body' => 'nullable|string',
            'cert_disclaimer' => 'nullable|string',
            'issuer_name' => 'nullable|string|max:255',
            'issuer_email' => 'nullable|email|max:255',
            'accent_color' => 'nullable|string|max:20',
            'badge_enabled' => 'nullable|boolean',
            'badge_label' => 'nullable|string|max:60',
            'badge_caption' => 'nullable|string|max:255',
            'badge_subtext' => 'nullable|string|max:500',
        ]);

        $data['badge_enabled'] = $request->boolean('badge_enabled');

        $template->update($data);

        return redirect()->route('admin.certificate.edit')->with('success', 'Certificate template updated.');
    }
}

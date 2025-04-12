<?php

namespace App\Http\Controllers\Admin;

use Mail;
use App\Models\Cms;
use App\Models\User;
use App\Enums\CmsType;
use Illuminate\Http\Request;
use App\Mail\SendEmailMailable;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Mailer\Messenger\SendEmailMessage;
use Validator;

class CmsController extends Controller
{
    public function index()
    {
        $cms = Cms::get();
        return inertia('admin.cms.index', ['cms' => $cms]);
    }

    
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cms' => ['required', 'array'],
            'cms.*.type' => ['required', new Enum(CmsType::class)],
            'cms.*.title' => ['nullable', 'string'],
            'cms.*.content' => ['nullable', 'string'],
            'cms.*.image' => ['nullable', 'file', 'image', 'max:2048'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();

        foreach ($validated['cms'] as $data) {
            $cms = Cms::where('type', $data['type'])->first();

            $values = [
                'type' => $data['type'],
                'content' => $data['content'] ?? null,
            ];

            if (isset($data['image'])) {
                if ($cms && $cms->image && Storage::exists("public/{$cms->image}")) {
                    Storage::delete("public/{$cms->image}");
                }

                $values['image'] = $data['image']->store('cms', 'public');
            } elseif ($cms) {
                $values['image'] = $cms->image;
            }

            Cms::updateOrCreate(
                ['type' => $data['type']],
                $values
            );
        }

        return redirect()->back()->with('success', 'CMS entries updated successfully.');
    
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;
use App\Models\Setting;

class HomeController extends Controller
{
    public function index(Request $request) {
        $isContactsPublic = Setting::firstWhere('name', 'is-contacts-public');
        $isContactsPublic = !empty($isContactsPublic) ? (bool) (int) $isContactsPublic->value : false;

        return view('home', [
            'isContactsPublic' => $isContactsPublic,
        ]);
    }

    public function contacts(Request $request) {
        $isContactsPublic = Setting::firstWhere('name', 'is-contacts-public');
        $isContactsPublic = !empty($isContactsPublic) ? (bool) (int) $isContactsPublic->value : false;

        if (!$isContactsPublic) {
            abort(404);
        }

        $contacts = Contact::orderBy('name', 'asc')->get()->all();

        return view('contacts', [
            'contacts' => $contacts,
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Session;
use App\Models\Contact;
use App\Models\Setting;

class AdminController extends Controller
{
    private $defaultStringColumnLength = 0;

    public function __construct() {
        $this->defaultStringColumnLength = \Illuminate\Database\Schema\Builder::$defaultStringLength;

        // the parent has no constructor
        // parent::__construct();
    }

    public function index() {
        return view('admin.index');
    }

    public function contacts() {
        $contacts = Contact::orderBy('name', 'asc')->get()->all();
        $isContactsPublic = Setting::firstWhere('name', 'is-contacts-public');
        $isContactsPublic = !empty($isContactsPublic) ? (bool) (int) $isContactsPublic->value : false;

        return view('admin.contacts', [
            'contacts' => $contacts,
            'isContactsPublic' => $isContactsPublic
        ]);
    }

    public function deleted() {
        $contacts = Contact::onlyTrashed()->orderBy('name', 'asc')->get()->all();

        return view('admin.deleted', [
            'contacts' => $contacts
        ]);
    }

    public function create(Request $request, Contact $contact = null, bool $isRestore = false) {
        if ($request->isMethod('post')) {
            $uniqueRule = Rule::unique('contacts');

            if (!empty($contact)) {
                $uniqueRule->ignore($contact->id);
            }

            // either phone or email is required
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:' . $this->defaultStringColumnLength,
                'notes' => 'nullable',
                // we're using a closure and not 'requiredIf'
                // so we can easily set a custom error message.
                // 'phone' => Rule::requiredIf(empty($request->email)),
                'phone' => [
                    function ($attribute, $value, $fail) use ($request) {
                        if (empty($value) && empty($request->email)) {
                            $fail('Either phone or email must be provided.');
                        }
                    }
                ],
                'email' => [
                    'nullable',
                    'email',
                    $uniqueRule
                ]
            ]);

            // if a phone was provided, check for its uniqueness
            $validator->sometimes('phone', $uniqueRule, function ($input) {
                return !empty($input->phone);
            });

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $validatedData = $validator->validated();

            if (empty($contact)) {
                $contact = new Contact;
            }

            $contact->name = $validatedData['name'];
            // we're not using the null coalescing operator
            // because we're not only interested if the value is set (isset(), i.e. not null),
            // but also if a non-empty value was actually submitted (and not, for example, an empty string).
            $contact->phone = !empty($validatedData['phone']) ? $validatedData['phone'] : null;
            $contact->email = !empty($validatedData['email']) ? $validatedData['email'] : null;
            $contact->notes = !empty($validatedData['notes']) ? $validatedData['notes'] : null;

            $result = $contact->save();

            if ($isRestore) {
                $result = $result && $contact->restore();
            }

            if ($result) {
                Session::flash('success-message', 'The contact was saved' . ($isRestore ? ' and restored' : '') . ' successfully!');
            } else {
                Session::flash('error-message', 'There was an error while saving' . ($isRestore ? ' and restoring' : '') . ' the contact. Please try again later. If the error persists, please contact us.');
            }

            return redirect()->route($isRestore ? 'admin.deleted' : 'admin.contacts');
        }

        return view('admin.create', [
            'contact' => $contact,
            'isRestore' => $isRestore
        ]);
    }

    public function editRestore(Request $request, Contact $deletedContact) {
        return app()->call('App\Http\Controllers\AdminController@create', ['contact' => $deletedContact, 'isRestore' => true]);
    }

    public function delete(Request $request, $id) {
        $contact = Contact::find((int) $id);

        if (empty($contact)) {
            Session::flash('error-message', 'Contact not found.');

            return redirect()->route('admin.contacts');
        }

        if ($contact->delete() === true) {
            Session::flash('success-message', 'The contact was deleted successfully!');
        } else {
            Session::flash('error-message', 'There was an error while deleting the contact. Please try again later. If the error persists, please contact us.');
        }

        return redirect()->route('admin.contacts');
    }

    public function restore(Request $request, $id) {
        $contact = Contact::withTrashed()->where('id', (int) $id)->first();

        if (empty($contact)) {
            Session::flash('error-message', 'Contact not found.');

            return redirect()->route('admin.deleted');
        }

        if ($contact->restore() === true) {
            Session::flash('success-message', 'The contact was restored successfully!');
        } else {
            Session::flash('error-message', 'There was an error while restoring the contact. Please try again later. If the error persists, please contact us.');
        }

        return redirect()->route('admin.deleted');
    }

    public function deleteForever(Request $request, $id) {
        $contact = Contact::withTrashed()->where('id', (int) $id)->first();

        if (empty($contact)) {
            Session::flash('error-message', 'Contact not found.');

            return redirect()->route('admin.deleted');
        }

        if ($contact->forceDelete() === true) {
            Session::flash('success-message', 'The contact was deleted forever successfully!');
        } else {
            Session::flash('error-message', 'There was an error while deleting the contact forever. Please try again later. If the error persists, please contact us.');
        }

        return redirect()->route('admin.deleted');
    }

    /**
     * Set contacts' visibility,
     * i.e. whether they are publicly accessible or not.
     */
    public function setVisibility(Request $request) {
        $settingName = 'is-contacts-public';

        $setting = Setting::firstWhere('name', $settingName);

        if (empty($setting)) {
            $setting = new Setting;
            $setting->name = $settingName;
        }

        $setting->value = (int) $request->isPublic;

        if ($setting->save()) {
            // we're not using exit() because it will
            // exit while running the tests.
            return 1;
        }

        return 0;
    }
}

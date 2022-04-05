<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Contact;

class ContactTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateContact() {
        $this->actingAs(User::factory()->make());

        $contact = Contact::factory()->make()->toArray();

        $this->post('/admin/create', $contact);

        $this->assertDatabaseHas('contacts', $contact);
    }

    public function testEditContact() {
        $this->actingAs(User::factory()->make());

        $contact = Contact::factory()->create();
        $newContactData = Contact::factory()->make()->toArray();

        $this->post('/admin/create/' . $contact->id, $newContactData);

        $this->assertDatabaseMissing('contacts', $contact->toArray());
        $this->assertDatabaseHas('contacts', $newContactData);
    }

    public function testDeleteContact() {
        $this->actingAs(User::factory()->make());

        $contact = Contact::factory()->create();

        $this->get('/admin/delete/' . $contact->id);

        $this->assertSoftDeleted($contact);
    }

    public function testDeleteContactForever() {
        $this->actingAs(User::factory()->make());

        $contact = Contact::factory()->create();

        $this->get('/admin/delete-forever/' . $contact->id);

        $this->assertDatabaseMissing('contacts', $contact->toArray());
    }

    public function testRestoreContact() {
        $this->actingAs(User::factory()->make());

        $contact = Contact::factory()->create([
            'deleted_at' => now()
        ]);

        // make sure the contact is initially soft deleted
        $this->assertSoftDeleted($contact);

        $this->get('/admin/restore/' . $contact->id);

        // ...and then check if it has indeed been restored.
        $this->assertNotSoftDeleted($contact);
    }

    public function testEditAndRestoreDeletedContact() {
        $this->actingAs(User::factory()->make());

        $contact = Contact::factory()->create([
            'deleted_at' => now()
        ]);
        $newContactData = Contact::factory()->make()->toArray();

        // make sure the contact is initially soft deleted
        $this->assertSoftDeleted($contact);

        // then edit and restore it
        $this->post('/admin/edit-restore/' . $contact->id, $newContactData);

        // ...then check if it has indeed been restored
        $this->assertNotSoftDeleted($contact);

        // ...and edited.
        $this->assertDatabaseMissing('contacts', $contact->toArray());
        $this->assertDatabaseHas('contacts', $newContactData);
    }

    /**
     * Check that the endpoint for edit and restore
     * can only be accessed with a deleted contact,
     * and otherwise, a 404 status code is received.
     */
    public function testEditAndRestoreNonDeletedContact() {
        $this->actingAs(User::factory()->make());

        $contact = Contact::factory()->create();

        $response = $this->post('/admin/edit-restore/' . $contact->id);

        $response->assertStatus(404);
    }
}

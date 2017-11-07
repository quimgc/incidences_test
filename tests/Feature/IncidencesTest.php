<?php

namespace Tests\Feature;

use Quimgc\Incidences\Models\Event;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class EventsTest.
 *
 * @package Tests\Feature
 */
class IncidencesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @group todo
     *
     * @test
     */
    public function testShowAllIncidences()
    {

        // 1) Preparo el test
        $events = factory(Event::class,50)->create();
        // 2) Executo el codi que vull provar
        $response = $this->get('/events');

        // 3) Comprovo: assert
        $response->assertStatus(200);
        $response->assertSuccessful();
        $response->assertViewIs('events::list_events');
        $events = Event::all();
        $response->assertViewHas('events',$events);

        foreach ($events as $event) {
            $response->assertSeeText($event->name);
            $response->assertSeeText($event->description);
        }
    }

    /**
     * Test show and event
     */
    public function testShowAnEvent()
    {
//        $this->withoutExceptionHandling();
        $event = factory(Event::class)->create();
        $response = $this->get('/events/' . $event->id);
        // Comprovo
        $response->assertStatus(200);
        $response->assertSuccessful();
        $response->assertViewIs('events::show_event');
        $response->assertViewHas('event');

        $response->assertSeeText('Event:');
        $response->assertSeeText($event->name);
        $response->assertSeeText($event->description);

    }

    /**
     * @group todo1
     */
    public function testNotShowAnEvent()
    {

        // Executo
        $response = $this->get('/events/9999999');
        // Comprovo
        $response->assertStatus(404);

    }

    public function testShowCreateEventForm()
    {
        // Preparo
        // Executo
        $response = $this->get('/events/create');
        // Comprovo
        $response->assertStatus(200);
        $response->assertViewIs('events::create_event');
        $response->assertSeeText('Create Event');
    }

    public function testShowEditEventForm()
    {
        // Preparo
        // Executo
        $response = $this->get('/events/edit');
        // Comprovo
        $response->assertStatus(200);
        $response->assertViewIs('edit_event');
        $response->assertSeeText('Edit Event');
    }

    public function testStoreEventForm()
    {
        // Preparo
        $event = factory(Event::class)->make();
        // Executo
        $response = $this->post('/events',[
            'name' => $event->name,
            'description' => $event->description,
        ]);
        // Comprovo
        $response->assertStatus(200);
        $response->assertRedirect('events/create');
        $response->assertSeeText('Created ok!');

        $this->assertDatabaseHas('events',[
            'name' => $event->name,
            'description' => $event->description,
        ]);
    }

    public function testUpdateEventForm()
    {
        // Preparo
        $event = factory(Event::class)->create();
        // Executo
        $newEvent = factory(Event::class)->make();
        $response = $this->patch('/events/' . $event->id,[
            'name' => $newEvent->name,
            'description' => $newEvent->description,
        ]);
        // Comprovo
        $response->assertStatus(200);
        $response->assertRedirect('events/create');
        $response->assertSeeText('Edited ok!');

        $this->assertDatabaseHas('events',[
            'id' =>  $event->id,
            'name' => $newEvent->name,
            'description' => $newEvent->description,
        ]);

        $this->assertDatabaseMissing('events',[
            'id' =>  $event->id,
            'name' => $event->name,
            'description' => $event->description,
        ]);
    }

    /**
     * @group caca1
     */
    public function testDeleteEvent()
    {
        // Preparo
        $event = factory(Event::class)->create();
        // Executo
        $response = $this->call('DELETE','/events/' . $event->id);

//        $response->dump();

        // Comprovo
        $this->assertDatabaseMissing('events',[
            'name' => $event->name,
            'description' => $event->description,
        ]);

//        $response->assertStatus(200);
        $response->assertRedirect('events');
        $response->assertSeeText('Event was deleted successful');


    }
}

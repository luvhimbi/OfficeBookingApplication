<?php

namespace Tests\Unit;

use App\Models\Boardroom;
use App\Models\Campus;
use App\Models\Building;
use App\Models\Floor;
use App\Services\BoardroomService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class BoardroomServiceTest extends TestCase
{
    use RefreshDatabase;

    protected BoardroomService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new BoardroomService();
    }

     #[Test]
    public function it_can_get_all_boardrooms_with_relationships()
    {
        $campus = Campus::factory()->create();
        $building = Building::factory()->create(['campus_id' => $campus->id]);
        $floor = Floor::factory()->create(['building_id' => $building->id]);
        $boardroom = Boardroom::factory()->create([
            'campus_id' => $campus->id,
            'building_id' => $building->id,
            'floor_id' => $floor->id,
        ]);

        $all = $this->service->getAllBoardrooms();

        $this->assertCount(1, $all);
        $this->assertEquals($boardroom->id, $all->first()->id);
        $this->assertTrue($all->first()->relationLoaded('campus'));
        $this->assertTrue($all->first()->relationLoaded('building'));
        $this->assertTrue($all->first()->relationLoaded('floor'));
    }

     #[Test]
    public function it_can_create_a_boardroom()
    {
        $campus = Campus::factory()->create();
        $building = Building::factory()->create(['campus_id' => $campus->id]);
        $floor = Floor::factory()->create(['building_id' => $building->id]);

        $data = [
            'name' => 'Boardroom 1',
            'capacity' => 20,
            'campus_id' => $campus->id,
            'building_id' => $building->id,
            'floor_id' => $floor->id,
        ];

        $boardroom = $this->service->createBoardroom($data);

        $this->assertDatabaseHas('boardrooms', [
            'id' => $boardroom->id,
            'name' => 'Boardroom 1',
            'capacity' => 20,
        ]);
    }

   #[Test]
    public function it_can_update_a_boardroom()
    {
        $boardroom = Boardroom::factory()->create(['name' => 'Old Name']);

        $data = ['name' => 'Updated Name', 'capacity' => 25];
        $updated = $this->service->updateBoardroom($boardroom, $data);

        $this->assertEquals('Updated Name', $updated->name);
        $this->assertEquals(25, $updated->capacity);
        $this->assertDatabaseHas('boardrooms', ['id' => $boardroom->id, 'name' => 'Updated Name']);
    }

   #[Test]
    public function it_can_delete_a_boardroom()
    {
        $boardroom = Boardroom::factory()->create();

        $this->service->deleteBoardroom($boardroom);

        $this->assertDatabaseMissing('boardrooms', ['id' => $boardroom->id]);
    }
}

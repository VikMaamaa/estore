<?php

namespace Tests\Unit\Models\Shop;

use App\Http\Controllers\Api\BrandController;
use App\Http\Requests\BrandRequest;
use App\Models\Shop\Brand;
use App\Services\BrandService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use Tests\TestCase;


class BrandTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function it_can_create_a_brand()
    {
        $brandData = [
            'name' => 'Test Brand',
            'slug' => 'test-brand',
            'website' => 'http://example.com',
            'description' => 'This is a test brand',
            'position' => 1,
            'is_visible' => true,
            'seo_title' => 'Test SEO Title',
            'seo_description' => 'Test SEO Description',
            'sort' => 100,
        ];

        $brand = Brand::create($brandData);

        $this->assertInstanceOf(Brand::class, $brand);
        $this->assertEquals($brandData['name'], $brand->name);
        $this->assertEquals($brandData['slug'], $brand->slug);
        $this->assertEquals($brandData['website'], $brand->website);
        $this->assertEquals($brandData['description'], $brand->description);
        $this->assertEquals($brandData['position'], $brand->position);
        $this->assertEquals($brandData['is_visible'], $brand->is_visible);
        $this->assertEquals($brandData['seo_title'], $brand->seo_title);
        $this->assertEquals($brandData['seo_description'], $brand->seo_description);
        $this->assertEquals($brandData['sort'], $brand->sort);
    }


    /** @test */
    public function it_can_list_brands()
    {
        // Mock request data
        $request = new Request([
            'per_page' => 10,
            'page' => 1,
        ]);

        // Mock the result from the BrandService
        $brandsData = [
            [
                'id' => 1,
                'name' => 'Lockman Ltd',
                'slug' => 'lockman-ltd',
                'website' => 'https://www.kerluke.com',
                'description' => "Bill,' thought Alice,) 'Well, I shan't grow any more--As it is, I suppose?' 'Yes,' said Alice sharply, for she had drunk half the bottle, she found she could see, as she could not even room for her.",
                'position' => 0,
                'is_visible' => true,
                'seo_title' => null,
                'seo_description' => null,
                'sort' => null,
                'created_at' => '2023-06-09T14:02:19.000000Z',
                'updated_at' => '2023-08-09T22:31:03.000000Z',
            ],
        ];

        $perPage = $request->input('per_page', 10);
        $page = $request->input('page', 1);

        // Mock the BrandService
        $brandServiceMock = $this->getMockBuilder(BrandService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $brandServiceMock->expects($this->once())
            ->method('search')
            ->with($request->all(), $perPage, $page)
            ->willReturn($brandsData);

        // Read the base URL from the .env file
        $baseUrl = env('APP_URL', 'http://localhost');

        // Create an instance of BrandController with the mocked BrandService
        $brandController = new BrandController($brandServiceMock);

        // Call the index method
        $response = $brandController->index($request);


        // Assert the response is successful
        $this->assertEquals(200, $response->getStatusCode());

        $responseData = json_decode($response->getContent(), true);

        // Assert specific elements in the JSON structure
        $this->assertTrue($responseData['success']);
        $this->assertEquals('Brands retrieved successfully', $responseData['message']);
    }


    /** @test */
    public function it_can_update_brand()
    {
        // Create a brand and persist it to the database
        $brand = Brand::factory()->create();

        // Mock request data
        $requestData = [
            'name' => $this->faker->company,
            'slug' => $this->faker->slug,
            'website' => $this->faker->url,
            'description' => $this->faker->paragraph,
            'position' => $this->faker->randomDigit,
            'is_visible' => $this->faker->boolean,
            'seo_title' => $this->faker->sentence(6),
            'seo_description' => $this->faker->sentence(15),
            'sort' => $this->faker->randomDigit,
        ];

        // Mock the BrandRequest instance
        $brandRequestMock = $this->getMockBuilder(BrandRequest::class)
            ->disableOriginalConstructor()
            ->getMock();

        $brandRequestMock->expects($this->once())
            ->method('validated')
            ->willReturn($requestData);

        // Mock the BrandService
        $brandServiceMock = $this->getMockBuilder(BrandService::class)
            ->disableOriginalConstructor()
            ->getMock();

        // Mock the update method in the BrandService
        $updatedBrand = new Brand($requestData); // Create a new Brand instance with updated data
        $brandServiceMock->expects($this->once())
            ->method('update')
            ->with($brand, $requestData)
            ->willReturn($updatedBrand);

        // Create an instance of BrandController with the mocked BrandService
        $brandController = new BrandController($brandServiceMock);

        // Call the update method
        $response = $brandController->update($brandRequestMock, $brand);

        // Assert the response is successful
        $this->assertEquals(200, $response->getStatusCode());

        $responseData = json_decode($response->getContent(), true);

        // Assert the response structure
        $this->assertTrue($responseData['success']);
        $this->assertEquals('Brand updated successfully', $responseData['message']);
    }

    /** @test */
    public function it_can_soft_delete_brand()
    {
        // Create a brand and persist it to the database
        $brand = Brand::factory()->create();

        // Mock the BrandService
        $brandServiceMock = $this->getMockBuilder(BrandService::class)
            ->disableOriginalConstructor()
            ->getMock();

        // Mock the delete method in the BrandService
        $brandServiceMock->expects($this->once())
            ->method('delete')
            ->with($brand);

        // Create an instance of BrandController with the mocked BrandService
        $brandController = new BrandController($brandServiceMock);

        // Call the destroy method
        $response = $brandController->destroy($brand);

        // Assert the response is successful
        $this->assertEquals(200, $response->getStatusCode());

        $responseData = json_decode($response->getContent(), true);

        // Assert the response structure
        $this->assertTrue($responseData['success']);
        $this->assertEquals('Brand Deleted successfully', $responseData['message']);


    }
}

<?php

namespace Tests;

use App\Http\Controllers\Admin\Users\UsersController;
use Mockery;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Http\Request;
use App\Jobs\UserFileReaderJob;

/**
 * Class UsersControllerTest
 *
 * @package Tests
 *
 * @author  Kacper Kowalski kacperk83@gmail.com
 */
class UsersControllerTest extends TestCase
{

    /**
     * @var array $mockContainer
     */
    private $mockContainer = [];

    /**
     * setUp
     */
    public function setUp()
    {
        if (! $this->app) {
            $this->refreshApplication();
        }

        $this->mockContainer['Request'] = Mockery::mock(Request::class);
        $this->mockContainer['Job'] = Mockery::mock(UserFileReaderJob::class);
        $this->mockContainer['dispatcher'] = Mockery::mock(Dispatcher::class);
    }

    /**
     * Refresh the application instance.
     *
     * @return \Illuminate\Foundation\Application
     */
    protected function refreshApplication()
    {
        putenv('APP_ENV=testing');

        $this->app = $this->createApplication();
    }

    /**
     * testImportCorrectScenario
     */
    public function testImportCorrectScenario()
    {
        $filename = 'challenge.json';
        $path = 'imports\challenge.json';

        $this->mockContainer['Request']->shouldReceive('file')->with('file')->andReturnSelf();
        $this->mockContainer['Request']->shouldReceive('isValid')->andReturn(true);

        $this->mockContainer['Request']->shouldReceive('file')->with('file')->andReturnSelf();
        $this->mockContainer['Request']->shouldReceive('getClientOriginalName')->andReturn($filename);

        $this->mockContainer['Request']->shouldReceive('file')->with('file')->andReturnSelf();
        $this->mockContainer['Request']->shouldReceive('storeAs')->with('imports', 'challenge.json')->andReturn($path);

        $this->mockContainer['Job']->shouldReceive('init')
            ->with($path);

        //Mock the job handler
        $this->mockContainer['dispatcher']->shouldReceive('dispatch')->once()
            ->andReturn(0);
        $this->app->instance(Dispatcher::class, $this->mockContainer['dispatcher']);

        $class = new UsersController($this->mockContainer['Request'], $this->mockContainer['Job']);
        $class->import();
    }

    /**
     * testImportIncorrectFileScenario
     */
    public function testImportIncorrectFileScenario()
    {
        $this->mockContainer['Request']->shouldReceive('file')->with('file')->andReturnSelf();
        $this->mockContainer['Request']->shouldReceive('isValid')->andReturn(false);

        $this->mockContainer['Request']->shouldReceive('file')->with('file')->andReturnSelf();
        $this->mockContainer['Request']->shouldReceive('getClientOriginalName')->never();
    }
}
